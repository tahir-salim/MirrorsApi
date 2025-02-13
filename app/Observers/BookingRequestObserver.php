<?php

namespace App\Observers;

use App\Http\Controllers\TalentApi\RequestController;
use App\Models\BookingRequest;
use App\Exceptions\CustomException;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
class BookingRequestObserver
{
    /**
     * Handle the BookingRequest "creating" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function creating(BookingRequest $bookingRequest)
    {
          Nova::whenServing(function (NovaRequest $request) use ($bookingRequest) {
            // Only invoked during Nova requests...
            // Check if the talent has another request at this date and time
            if($bookingRequest->talent_user_id) {
                $bookingRequests = BookingRequest::where('talent_user_id', $bookingRequest->talent_user_id)
                                ->where('requested_delivery_date', $bookingRequest->requested_delivery_date)
                                ->whereIn('status', [BookingRequest::PENDING, BookingRequest::ACCEPTED])
                                ->get();
                $times = []; //get unavialable slots
                foreach ($bookingRequests as $booking) {
                    $time = explode(':', $booking->duration);
                    $minutes = $time[0] * 60.0 + $time[1] * 1.0;
                    for ($i = 0; $i < $minutes; $i += 30) {
                        array_push($times, date('g:i A', strtotime($booking->time) + $i * 60));
                    }
                }

                $bookingTimes = [];
                $bookingTime = explode(':', $bookingRequest->duration);
                $minutes = $bookingTime[0] * 60.0 + $bookingTime[1] * 1.0;
                for ($i = 0; $i < $minutes; $i += 30) {
                    array_push($bookingTimes, date('g:i A', strtotime($bookingRequest->time) + $i * 60));
                }

                foreach($bookingTimes as $bookingTime) {
                    if (in_array($bookingTime, $times)) {
                        throw new CustomException('Time conflict with another request');
                    }
                }

            }
        });


        // Add tax amount and total price here
        if($bookingRequest->price) {
            $bookingRequest->tax_amount = $bookingRequest->price * BookingRequest::TAX_AMOUNT;
            $bookingRequest->total_price = $bookingRequest->price + $bookingRequest->tax_amount;
        }

    }

    /**
     * Handle the BookingRequest "created" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function created(BookingRequest $bookingRequest)
    {
        (new RequestController)->talentPendingDifferentRequestsAtSameTime($bookingRequest);
    }


    /**
     * Handle the BookingRequest "updating" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function updating(BookingRequest $bookingRequest)
    {
        Nova::whenServing(function (NovaRequest $request) use ($bookingRequest) {
            // Only invoked during Nova requests...
            if(($bookingRequest->isDirty('talent_user_id') || $bookingRequest->isDirty('requested_delivery_date') || $bookingRequest->isDirty('time')) && $bookingRequest->storyteller_id) {
            // Check if the talent has another request at this date and time
            if($bookingRequest->talent_user_id) {
                $bookingRequests = BookingRequest::where('talent_user_id', $bookingRequest->talent_user_id)
                                ->where('requested_delivery_date', $bookingRequest->requested_delivery_date)
                                ->whereIn('status', [BookingRequest::PENDING, BookingRequest::ACCEPTED])
                                ->get();
                $times = []; //get unavialable slots
                foreach ($bookingRequests as $booking) {
                    $time = explode(':', $booking->duration);
                    $minutes = $time[0] * 60.0 + $time[1] * 1.0;
                    for ($i = 0; $i < $minutes; $i += 30) {
                        array_push($times, date('g:i A', strtotime($booking->time) + $i * 60));
                    }
                }

                $bookingTimes = [];
                $bookingTime = explode(':', $bookingRequest->duration);
                $minutes = $bookingTime[0] * 60.0 + $bookingTime[1] * 1.0;
                for ($i = 0; $i < $minutes; $i += 30) {
                    array_push($bookingTimes, date('g:i A', strtotime($bookingRequest->time) + $i * 60));
                }

                foreach($bookingTimes as $bookingTime) {
                    if (in_array($bookingTime, $times)) {
                        throw new CustomException('Time conflict with another request');
                    }
                }
            }
        }
        });

        if($bookingRequest->isDirty('price')) {
            $bookingRequest->tax_amount = $bookingRequest->price * BookingRequest::TAX_AMOUNT;
            $bookingRequest->total_price = $bookingRequest->price + $bookingRequest->tax_amount;
        }
    }

    /**
     * Handle the BookingRequest "updated" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function updated(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Handle the BookingRequest "deleting" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function deleting(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Handle the BookingRequest "deleted" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function deleted(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Handle the BookingRequest "restored" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function restored(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Handle the BookingRequest "force deleted" event.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return void
     */
    public function forceDeleted(BookingRequest $bookingRequest)
    {
        //
    }
}
