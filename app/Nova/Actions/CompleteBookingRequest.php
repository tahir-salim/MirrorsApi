<?php

namespace App\Nova\Actions;

use App\Models\BookingRequest;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class CompleteBookingRequest extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {

            if ($model->status === BookingRequest::COMPLETED) {
                return Action::danger('Request is already Completed');
            }

            //check if the request has been paid
            if (is_null($model->transaction) || $model->transaction->paid_at == null || $model->transaction->tap_status != Transaction::CAPTURED){
                return Action::danger('Unpaid Request');
            }

            if ($model->status === BookingRequest::ACCEPTED) {

                $model->status = BookingRequest::COMPLETED;
                $model->completed_at = now();
                $model->save();
                continue;
            }
            else {
                return Action::danger('You can not proceed the request at this stage');
            }
        }
        return Action::message('Request Completed Successfully');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
