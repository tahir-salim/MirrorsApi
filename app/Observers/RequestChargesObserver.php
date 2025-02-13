<?php

namespace App\Observers;

use App\Libraries\NotificationsWrapper;
use App\Models\BookingRequest;
use App\Models\RequestCharges;

class RequestChargesObserver
{
    /**
     * Handle the RequestCharges "creating" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function creating(RequestCharges $requestCharges)
    {
        $bookingRequest = $requestCharges->bookingRequest;
        // Calculate tax here
        $requestCharges->tax_amount = $requestCharges->price * RequestCharges::TAX_AMOUNT;
        $requestCharges->total_price = $requestCharges->price + $requestCharges->tax_amount;

        if($bookingRequest->payment_status == BookingRequest::STATUS_PAID && $bookingRequest->processed_at)
        {
            $requestCharges->paid_with_request = RequestCharges::PAID_WITHOUT_REQUEST;
            $requestCharges->payment_status = RequestCharges::STATUS_UNPAID;

        }
        else
        {
            $requestCharges->paid_with_request = RequestCharges::PAID_WITH_REQUEST;
            $requestCharges->payment_status = RequestCharges::STATUS_PAID_WITH_REQUEST;
            $bookingRequest->price+=$requestCharges->price;
            $bookingRequest->tax_amount = $bookingRequest->price * BookingRequest::TAX_AMOUNT;
            $bookingRequest->total_price = $bookingRequest->price + $bookingRequest->tax_amount;
            $bookingRequest->save();
        }
    }

    /**
     * Handle the RequestCharges "creating" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function created(RequestCharges $requestCharges)
    {
        $bookingRequest = $requestCharges->bookingRequest;

        // Send a notification to the user
        NotificationsWrapper::newRequestCharge($bookingRequest);
    }

     /**
     * Handle the RequestCharges "updating" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function updating(RequestCharges $requestCharges)
    {
        $bookingRequest = $requestCharges->bookingRequest;
        // Calculate tax here
        $requestCharges->tax_amount = $requestCharges->price * RequestCharges::TAX_AMOUNT;
        $requestCharges->total_price = $requestCharges->price + $requestCharges->tax_amount;

        if(!($bookingRequest->payment_status == BookingRequest::STATUS_PAID && $bookingRequest->processed_at))
        {
            // update price of the booking request
            $bookingRequest->price+=$requestCharges->price;
            $bookingRequest->tax_amount = $bookingRequest->price * BookingRequest::TAX_AMOUNT;
            $bookingRequest->total_price = $bookingRequest->price + $bookingRequest->tax_amount;
            $bookingRequest->save();
        }

    }
    /**
     * Handle the RequestCharges "updated" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function updated(RequestCharges $requestCharges)
    {
        //
    }

    /**
     * Handle the RequestCharges "deleted" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function deleted(RequestCharges $requestCharges)
    {
        //
    }

    /**
     * Handle the RequestCharges "restored" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function restored(RequestCharges $requestCharges)
    {
        //
    }

    /**
     * Handle the RequestCharges "force deleted" event.
     *
     * @param  \App\Models\RequestCharges  $requestCharges
     * @return void
     */
    public function forceDeleted(RequestCharges $requestCharges)
    {
        //
    }
}
