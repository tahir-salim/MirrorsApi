<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Libraries\NotificationsWrapper;
use App\Models\BookingRequest;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function getTalentCompletedRequests(Request $request)
    {
        $talentRequests = BookingRequest::where('talent_user_id', Auth::id())
            ->with(['review' => function ($q) {
                    $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services.serviceUser', 'requestAttachments', 'requestComments.user', 'user', 'requestCharges'])
            ->isCompleted()
            ->whereHas('user', function ($query) use ($request){
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success','get-completed-requests',$talentRequests);
    }

    public function getTalentPendingRequests(Request $request)
    {
        $pendingRequests = BookingRequest::where('talent_user_id', Auth::id())
            ->with(['review' => function ($q) {
                    $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'user', 'requestCharges'])
            ->where('status', BookingRequest::PENDING)
            ->whereHas('user', function ($query) use ($request){
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success','get-pending-requests',$pendingRequests);
    }

     public function getTalentAcceptedRequests(Request $request)
    {
        $acceptedRequests = BookingRequest::where('talent_user_id', Auth::id())
            ->with(['review' => function ($q) {
                    $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'user', 'requestCharges'])
            ->where('status', BookingRequest::ACCEPTED)
            ->whereHas('user', function ($query) use ($request){
                $query->where('name', 'like', "%$request->text%");
            })
            ->orderByDesc('id')
            ->paginate(20);

        return $this->formatResponse('success','get-accepted-requests',$acceptedRequests);
    }

    public function getTalentRequests(Request $request)
    {

        $newRequests = BookingRequest::where('talent_user_id', Auth::id())
            ->with(['review' => function ($q) {
                    $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'user', 'requestCharges'])
            ->where('status', BookingRequest::PENDING)
            ->orderByDesc('id')
            ->paginate(20);

        $ongoingRequests = BookingRequest::where('talent_user_id', Auth::id())
            ->with(['review' => function ($q) {
                    $q->where('status', Review::STATUS_APPROVED);
            }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'user'])
            ->where('status', BookingRequest::ACCEPTED)
            ->orderByDesc('id')
            ->paginate(20);

         $completedRequests = BookingRequest::where('talent_user_id', Auth::id())
            ->with('review', 'packages', 'services.serviceUser', 'requestAttachments', 'requestComments.user', 'user')
            ->isCompleted()
            ->orderByDesc('id')
            ->paginate(20);

        $requests = [
            'new' => $newRequests,
            'ongoing' => $ongoingRequests,
            'completed' => $completedRequests
        ];

        return $this->formatResponse('success','get-talent-requests', $requests);

    }

    public function rejectRequest($requestID){

        $talentRequest = BookingRequest::find($requestID);

        if (!$talentRequest){
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if($talentRequest->talent_user_id != Auth::id()) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        if ($talentRequest->status != BookingRequest::PENDING){
            return $this->formatResponse('error','your-request-is-not-pending', null, 403);
        }

        $talentRequest->update([
            'status' => BookingRequest::REJECTED
        ]);
        NotificationsWrapper::bookingRejected($talentRequest);
        return $this->formatResponse('success','booking-request-rejected', $talentRequest);
    }

    public function completeRequest(Request $request, $requestID)
    {

        $validation = Validator::make($request->all(), [
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $talentRequest = BookingRequest::find($requestID);

        if (!$talentRequest){
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if($talentRequest->talent_user_id != Auth::id()) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        if ($talentRequest->status != BookingRequest::ACCEPTED){
            return $this->formatResponse('error','your-request-is-not-accepted', null, 403);
        }

        //check if the request has been paid
        if ($talentRequest->payment_status != BookingRequest::STATUS_PAID || is_null($talentRequest->transaction) || $talentRequest->transaction->paid_at == null || $talentRequest->transaction->is_success == 0 || $talentRequest->transaction->tap_status != Transaction::CAPTURED){
            return $this->formatResponse('error','unpaid-request', null, 403);
        }

        //upload and save report file if uploaded
        $filePath = null;
        if ($request->file('file')){
            $filePath = $request->file('file')->store('images/bookingRequest', 's3');
        }

        $talentRequest->update([
            'status' => BookingRequest::COMPLETED,
            'completed_at' => now(),
            'report_file_path' => $request->file('file')?$filePath:null,
        ]);
        NotificationsWrapper::reviewInquiry($talentRequest);

        // Lock the chat
        $chatUser = ChatUser::where('user_id', $talentRequest->user_id)
            ->whereHas('chat', function ($query) {
                $query->whereHas('chatUsers', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->get()
            ->pluck('chat')
            ->first();

        if($chatUser) {
            $chatUser->update([
            'is_locked' => true
        ]);
        }

        return $this->formatResponse('success','booking-request-completed', $talentRequest);

    }

    public function acceptRequest($requestID)
    {

        $talentRequest = BookingRequest::find($requestID);

        if (!$talentRequest){
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if($talentRequest->talent_user_id != Auth::id()) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        if ($talentRequest->status != BookingRequest::PENDING){
            return $this->formatResponse('error','your-request-is-not-pending', null, 403);
        }

        $talentRequest->update([
            'status' => BookingRequest::ACCEPTED
        ]);
        NotificationsWrapper::bookingAccepted($talentRequest);

        return $this->formatResponse('success','booking-request-accepted', $talentRequest);
    }

    public function talentPendingDifferentRequestsAtSameTime($bookingRequest){

        $talentBookingRequests = BookingRequest::where('talent_user_id', $bookingRequest['talent_user_id'])
            ->whereDate('requested_delivery_date', $bookingRequest['requested_delivery_date'])
            ->where('time', $bookingRequest['time'])
            ->where('status', BookingRequest::PENDING)
            ->get();

        if (count($talentBookingRequests) > 1){

            // if booking request exist at same time and date then apply has_request_on_date
            foreach ($talentBookingRequests as $talentBookingRequest){

                // if has_request_on_date is false then enable flag
                if ($talentBookingRequest->has_request_on_date === 0){
                    $talentBookingRequest->has_request_on_date = true;
                    $talentBookingRequest->save();
                }

            }
        }

    }
}
