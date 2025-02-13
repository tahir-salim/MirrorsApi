<?php

namespace App\Http\Controllers;

use App\Libraries\NotificationsWrapper;
use App\Libraries\TapPayment;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\RequestAttachment;
use App\Models\RequestPackage;
use App\Models\RequestService;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceUser;
use App\Models\Settings;
use App\Models\TalentSchedule;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\BookingRequestTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    use BookingRequestTrait;

    public function getUserCompletedRequests(Request $request)
    {
        $userRequests = BookingRequest::where('user_id', Auth::id())
            ->with(['review', 'packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges'])
            ->where('status', BookingRequest::COMPLETED)
            ->whereHas('talentUser', function ($query) use ($request) {
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success', 'get-completed-requests', $userRequests);
    }

    public function getRejectedRequests(Request $request)
    {
        $userRequests = BookingRequest::where('user_id', Auth::id())
            ->with('packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges')
            ->where('status', BookingRequest::REJECTED)
            ->whereHas('talentUser', function ($query) use ($request) {
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success', 'get-rejected-requests', $userRequests);
    }

    public function getAcceptedRequests(Request $request)
    {
        $acceptedRequests = BookingRequest::where('user_id', Auth::id())
            ->with(['review' => function ($q) {
                $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges'])
            ->where('status', BookingRequest::ACCEPTED)
            ->whereHas('talentUser', function ($query) use ($request) {
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success', 'get-accepted-requests', $acceptedRequests);
    }

    public function getPendingRequests(Request $request)
    {
        $pendingRequests = BookingRequest::where('user_id', Auth::id())
            ->with(['review' => function ($q) {
                $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges'])
            ->where('status', BookingRequest::PENDING)
            ->whereHas('talentUser', function ($query) use ($request) {
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success', 'get-pending-requests', $pendingRequests);
    }

    public function getUserRequests()
    {

        $acceptedRequests = BookingRequest::where('user_id', Auth::id())
            ->with(['review' => function ($q) {
                $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges'])
            ->where('status', BookingRequest::ACCEPTED)
            ->orderByDesc('id')
            ->paginate(20);

        $pendingRequests = BookingRequest::where('user_id', Auth::id())
            ->with(['review' => function ($q) {
                $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges'])
            ->where('status', BookingRequest::PENDING)
            ->orderByDesc('id')
            ->paginate(20);

        $rejectedRequests = BookingRequest::where('user_id', Auth::id())
            ->with('packages', 'services', 'requestAttachments', 'requestComments.user', 'talentUser', 'requestCharges')
            ->where('status', BookingRequest::REJECTED)
            ->orderByDesc('id')
            ->paginate(20);

        $ongoingRequests = [
            'pending' => $pendingRequests,
            'accepted' => $acceptedRequests,
            'rejected' => $rejectedRequests,
        ];

        return $this->formatResponse('success', 'get-user-requests', $ongoingRequests);
    }

    public function reviewRequest(Request $request, $requestId)
    {
        $validation = Validator::make($request->all(), [
            'rating' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $userRequest = BookingRequest::find($requestId);

        if (!$userRequest) {
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if ($userRequest->user_id != Auth::id()) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        if ($userRequest->review) {
            return $this->formatResponse('error', 'already-reviewed', null, 400);
        }

        $isRatingValid = $request->rating >= 1 && $request->rating <= 5;

        if (!$isRatingValid) {
            return $this->formatResponse('error', 'rating-not-valid', null, 400);
        }
        $review = new Review();
        $review->booking_request_id = $userRequest->id;
        $review->rating = $request->rating;
        $review->details = $request->details;

        $review->save();
        return $this->formatResponse('success', 'review-created', $review);

    }

    public function cancelRequest($requestID)
    {

        $userBookingRequest = BookingRequest::find($requestID);

        if (!$userBookingRequest) {
            return $this->formatResponse('error', 'booking-request-not-found', null, 403);
        }

        if ($userBookingRequest->status != BookingRequest::PENDING) {
            return $this->formatResponse('error', 'your-request-is-not-pending', null, 403);
        }

        if ($userBookingRequest->user_id != Auth::id()) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        $userBookingRequest->update([
            'status' => BookingRequest::CANCELED,
        ]);
        NotificationsWrapper::bookingCanceled($userBookingRequest);
        return $this->formatResponse('success', 'booking-request-canceled-successfully', $userBookingRequest);
    }

    public function storeRequest(Request $request, $talentID)
    {

        $validation = Validator::make($request->all(), [
            'details' => 'nullable|max:2000',
            'packages' => 'nullable|array',
            'packages.*' => 'integer',
            'services' => 'nullable|array',
            'services.*' => 'integer',
            'requested_delivery_date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:g:i A',
            'duration' => 'required|date_format:G:i',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        // Check if both packages and services are null
        if (!$request->packages && !$request->services) {
            return $this->formatResponse('error', 'packages-and-services-not-provided', null, 400);
        }
        $user = Auth::user();

        // check auth user is not talent
        if (!$user || $user['role_id'] != User::ROLE_USER) {
            return $this->formatResponse('error', 'unauthorized', null, 403);
        }

        // check DB For talent exist
        $talent = User::isNotBlocked()->isTalent()->find($talentID);

        if (!$talent) {
            return $this->formatResponse('error', 'talent-not-found', null, 403);
        }

        // validation for requested time and date on talent schedule
        $talentSchedules = $this->validateTime($request, $talent['id']);

        if (!$talentSchedules) {
            return $this->formatResponse('error', 'date-time-unavailable', null, 403);
        }

        // validation for requested duration on talent schedule
        $talentDurationAvailability = $this->validateDuration($request, $talentSchedules);

        if (!$talentDurationAvailability) {
            return $this->formatResponse('error', 'time-duration-unavailable', null, 403);
        }

        // if booking Request already exist with selected date time which is not cancel and rejected
        // $bookingRequest = BookingRequest::where('talent_user_id', $talent->id)
        //  ->where('requested_delivery_date', $request['requested_delivery_date'])
        //  ->where('time', $request['time'])
        //  ->whereNotIn('status', [BookingRequest::CANCELED, BookingRequest::REJECTED])
        //  ->count();
        //
        //  if ($bookingRequest){
        //   return $this->formatResponse('error', 'this-talent-already-reserved-for-this-time-schedule', null, 403);
        // }

        //  $checkBookingExist =  self::getBookingTime($talentSchedules,$talent,$request['requested_delivery_date']);

        // The user can only have one request with the same talent (pending or accepted)
        $checkForBookingRequest = $user
            ->requests()
            ->where('talent_user_id', $talent->id)
            ->whereIn('status', [BookingRequest::PENDING, BookingRequest::ACCEPTED])
            ->first();

        if ($checkForBookingRequest) {
            return $this->formatResponse('error', 'talent-request-exists', null, 403);
        }

        // allowed numbers of request per day
        $maxRequestsPerDay = Settings::showForUser()
            ->select('value')
            ->where('name', Settings::MAX_REQUEST_PER_DAY)
            ->first();

        if (!$maxRequestsPerDay) {
            return $this->formatResponse('error', 'max-requests-value-not-found', null, 403);
        }

        // check submitted Request Today
        $submittedRequestsToday = $user
            ->requests()
            ->whereDate('created_at', today())
            ->count();

        // The user can only submit a max of 3 requests per day
        if ($maxRequestsPerDay['value'] <= $submittedRequestsToday) {
            return $this->formatResponse('error', 'daily-max-reached', null, 403);
        }

        $packages = [];
        if (isset($request->packages) && $request->packages) {
            // check DB For Packages
            $packages = Package::where('user_id', $talent->id)->whereIn('id', $request->packages)->get();
        }

        // if (!isset($packages) || !count($packages)){
        //     return $this->formatResponse('error', 'no-package-found', null, 403);
        // }

        // if services selected
        $services = [];
        $servicesUser = [];
        if (isset($request->services) && $request->services) {
            // check DB For Packages
            $services = Service::whereHas('serviceUsers', function ($query) use ($talent) {
                $query->where('user_id', $talent->id);
            })
                ->whereIn('id', isset($request->services) ? $request->services : [])
                ->get();

            if (!$request->services || !isset($services) || !count($services)) {
                return $this->formatResponse('error', 'no-service-found', null, 403);
            }

            $servicesUser = ServiceUser::where('is_active', true)
                ->where('user_id', $talent->id)
                ->whereIn('service_id', $request->services)
                ->get();

            if (!isset($servicesUser) || !count($servicesUser)) {
                return $this->formatResponse('error', 'no-service-user-found', null, 403);
            }
        }

        // new booking request
        $newBookingRequest = new BookingRequest();
        $newBookingRequest->user_id = $user['id'];
        $newBookingRequest->talent_user_id = $talent['id'];
        if ((isset($request->packages) && $request->packages) && (isset($request->services) && $request->services)) {
            $newBookingRequest->price = $packages->sum('price') + $servicesUser->sum('price') ?? 0;
        } elseif ((isset($request->packages) && $request->packages) && !(isset($request->services) && $request->services)) {
            $newBookingRequest->price = $packages->sum('price') ?? 0;
        } elseif (!(isset($request->packages) && $request->packages) && (isset($request->services) && $request->services)) {
            $newBookingRequest->price = $servicesUser->sum('price') ?? 0;
        }
        $newBookingRequest->details = $request->details;
        $newBookingRequest->status = BookingRequest::PENDING;
        $newBookingRequest->payment_status = BookingRequest::STATUS_UNPAID;
        $newBookingRequest->requested_delivery_date = $request->requested_delivery_date;
        $newBookingRequest->time = Carbon::createFromFormat('g:i A', $request->time)->format('g:i A');
        $newBookingRequest->duration = Carbon::createFromFormat('G:i', $request->duration)->format('G:i');
        $newBookingRequest->processed_at = null;
        $newBookingRequest->save();

        // add package request
        if (isset($request->packages) && $request->packages) {
            // add booking request packages
            foreach ($packages as $package) {

                $request_package = new RequestPackage();
                $request_package->booking_request_id = $newBookingRequest->id;
                $request_package->package_id = $package->id;
                $request_package->save();

            }
        }

        // add service request
        if (isset($request->services) && $request->services) {
            // add booking request service
            foreach ($services as $service) {

                $request_service = new RequestService();
                $request_service->booking_request_id = $newBookingRequest->id;
                $request_service->service_id = $service->id;
                $request_service->save();

            }
        }
        // push notification
        NotificationsWrapper::newBooking($newBookingRequest);
        return $this->formatResponse('success', 'your-booking-request-submit-successfully', $newBookingRequest);
    }

    public function updateRequest(Request $request, $requestID)
    {

        $validation = Validator::make($request->all(), [
            'details' => 'nullable|min:3|max:2000',
            'deleted_attachments' => 'nullable|array',
            'deleted_attachments.*' => 'integer',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $userRequest = BookingRequest::find($requestID);

        if (!$userRequest) {
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if ($userRequest->status != BookingRequest::PENDING) {
            return $this->formatResponse('error', 'your-request-is-not-pending', null, 403);
        }

        if ($userRequest->user_id != Auth::id()) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        if ($request->details) {
            // update request details
            $userRequest->update([
                'details' => $request->details,
            ]);
        }

        // check if exist in DB
        if ($request->deleted_attachments) {
            $requestAttachments = RequestAttachment::whereIn('id', $request->deleted_attachments)
                ->pluck('id');
            // if deleted attachments array has record
            if (count($requestAttachments)) {
                $userRequest->requestAttachments()
                    ->whereIn('id', $requestAttachments)
                    ->delete();
            }
        }

        NotificationsWrapper::bookingUpdate($userRequest);
        return $this->formatResponse('success', 'update-user-request-successfully');

    }

    public function getMonthSchedule(Request $request)
    {
        // validate talent exists
        $talent = $this->valdiateTalent($request->all());
        if (!$talent) {
            return $this->formatResponse('error', 'Talent not found');
        }

        $validator = Validator::make($request->all(), ['date' => 'date']);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'date-required');
        }
        $date = $request->date;

        // New Work 12-2-2019
        $startDate = new Carbon($date);
        $endDate = (new Carbon($date))->endOfMonth();

        $currentDate = new Carbon($startDate);
        $schedule_array = [];

        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $date = $currentDate->format('Y-m-d');
            $schedule_array[$date] = [];

            $schedule = TalentSchedule::where('user_id', $talent->id)
                ->where('day', date('w', strtotime($date)))
                ->get(); //Get talent schedule

            if (!$schedule->isEmpty()) {
                $schedule = $schedule->keyBy('id'); //Making the ID keys

                // $schedule = $this->getBookingTime($schedule, $talent, $date); // Allow multiple bookings on the same date for now
                $schedule = $this->findHalfTime($schedule);

                foreach ($schedule as $item) {
                    $schedule_array[$date][] = $item['time'];
                }
                $schedule_array[$date] = $this->getTimeSlots($schedule_array[$date]);
            }

            $currentDate->addDay();
        }

        return $this->formatResponse('success', null, $schedule_array);
    }

    public function getBookingTime($schedule, $talent, $date)
    {
        $bookingRequests = BookingRequest::where('talent_user_id', $talent->id)->where('requested_delivery_date', $date)->whereNotIn('status', [BookingRequest::CANCELED, BookingRequest::REJECTED])->get(); //Get all tutor booking where day is today and following, and where booking not canceled
        //        $booking = Booking::where('tutor_id', $tutor->id)->where('day', '>=', $startFrom)->where('canceled', false)->get(); //Get all tutor booking where day is today and following, and where booking not canceled
        $times = []; //Create an array
        foreach ($bookingRequests as $book) {
            $time = explode(':', $book->duration);
            $minutes = ($time[0] * 60.0 + $time[1] * 1.0);
            for ($i = 0; $i < $minutes; $i += 30) {
                array_push($times, date('g:i A', strtotime($book->time) + ($i * 60)));
            }
        }
        foreach ($times as $time) {
            $scheduleTime = $schedule->where('time', $time)->first();
            if ($scheduleTime && $scheduleTime->id) {
                $schedule->forget($scheduleTime->id); //Remove from schedule time when schedule is reserved.
            }
        }

        return $schedule;
    }

    public function findHalfTime($schedules)
    {
        foreach ($schedules as $schedule) {
            $past_time = date('g:i A', strtotime($schedule->time) + (30 * 60)); //Get time(data time + 30 minutes)
            $prev_time = date('g:i A', strtotime($schedule->time) - (30 * 60)); //Get time(data time - 30 minutes)
            if (!$schedules->where('time', $past_time)->first() && !$schedules->where('time', $prev_time)->first()) {
                $schedules->forget($schedule->id);
            }
        }

        return $schedules;
    }

    public function getTimeSlots($schedule)
    {
        $timeslots = [];
        foreach ($schedule as $timeslot) {
            $carbonTime = new Carbon($timeslot);
            $carbonTime = $carbonTime->addMinute(BookingRequest::TIMESLOT_DURATION_MINUTES)
                ->format('g:i A');
            if (
                in_array($carbonTime, $schedule)
            ) {
                $timeslots[] = ['start_time' => $timeslot,
                    'end_time' => $carbonTime,
                    'duration' => BookingRequest::TIMESLOT_DURATION_HOUR];
            }
        }
        return $timeslots;
    }

    public function paymentRequests($bookingRequestID)
    {

        $user = User::isNotBlocked()
            ->isVerified()
            ->isUser()
            ->find(Auth::id());

        if (!$user) {
            return $this->formatResponse('error', 'unauthorized-user', null, 404);
        }

        $bookingRequest = BookingRequest::with([
            'user',
            'talentUser',
            'transaction',
            'packages',
            'services',
        ])
            ->find($bookingRequestID);

        if (!$bookingRequest) {
            return $this->formatResponse('error', 'booking-request-not-found', null, 404);
        }

        if (is_null($bookingRequest->talentUser)) {
            return $this->formatResponse('error', 'talent-not-found', null, 404);
        }

        if ($bookingRequest->talentUser && $bookingRequest->talentUser->is_blocked) {
            return $this->formatResponse('error', 'talent-is-blocked', null, 404);
        }

        if ($bookingRequest->user_id != $user->id) {
            return $this->formatResponse('error', 'user-not-found', null, 404);
        }

        if ($bookingRequest->status != BookingRequest::ACCEPTED) {
            return $this->formatResponse('error', 'you-can-only-proceed-when-booking-is-on-accepted-state', null, 404);
        }

        if (is_null($bookingRequest->completed_at) && $bookingRequest->processed_at && $bookingRequest->transaction && $bookingRequest->transaction->paid_at && $bookingRequest->transaction->tap_status === Transaction::CAPTURED) {
            return $this->formatResponse('error', 'you-already-paid-for-this-request', null, 404);
        }

        // check DB for existing transaction if transaction is in INITIATED state
        $transaction = $bookingRequest->transaction()
            ->where('status', Transaction::PENDING)
            ->where('tap_status', Transaction::INITIATED)
            ->first();

        // $transaction = Transaction::where('user_id', $bookingRequest['user_id'])
        //     // ->where('booking_request_id',$bookingRequest['id'])
        //     ->where('status',Transaction::PENDING)
        //     ->where('tap_status',Transaction::INITIATED)
        //     ->first();

        $transaction_amount = $bookingRequest['total_price'] ?? $bookingRequest['price'];
        // tap payment
        $result = TapPayment::createCharge(
            'USD',
            $user['name'],
            $user['phone'],
            config('app.url') . '/payment/booking-request/verify',
            $transaction_amount,
            TapPayment::SOURCE_CARD
        );

        // create new transaction if not exist
        if (!$transaction) {
            // create new transaction record if not found
            $transaction = new Transaction();
            $transaction->user_id = $bookingRequest['user_id'];
            $transaction->status = Transaction::PENDING;
            $transaction->is_success = false;
        }

        // updating record
        $transaction->currency = 'USD';
        $transaction->origin = null;
        $transaction->amount = $transaction_amount;
        $transaction->usd_amount = $transaction_amount;

        $tapResponse = null;

        if ($result && isset($result['code'])) {

            $tapResponse = isset($result['data']) ? $result['data'] : null;

            if ($tapResponse) {

                $transaction->tap_response = $tapResponse;
                $transaction->tap_charge_id = $tapResponse->id;
                $transaction->tap_status = $tapResponse->status;
                $transaction->payment_link = optional($tapResponse->transaction)->url ?? null;
                $transaction->is_success = true;
                $transaction->tap_response_code = $result['code'];

            }
        }

        $transaction->save();

        // add transaction_id to booking request
        $bookingRequest->transaction_id = $transaction->id;
        $bookingRequest->save();

        if (!$tapResponse) {
            return $this->formatResponse(
                false,
                'booking-insert-payment-failed',
                [
                    'booking' => $bookingRequest,
                    'tap_response' => $tapResponse,
                ],
                400
            );
        }

        return $this->formatResponse(
            true,
            'booking-insert',
            [
                'booking' => $bookingRequest,
                'success_url' => config('app.url') . '/tap/payment/success',
                'failed_url' => config('app.url') . '/tap/payment/failed',
                'payment_url' => optional($tapResponse->transaction)->url,
            ]
        );

    }

}
