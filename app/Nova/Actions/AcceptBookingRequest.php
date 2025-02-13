<?php

namespace App\Nova\Actions;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class AcceptBookingRequest extends Action
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

            if ($model->status === BookingRequest::ACCEPTED) {
                return Action::danger('Request is already Accepted');
            }

            if ($model->status === BookingRequest::PENDING) {
                $model->status = BookingRequest::ACCEPTED;
                $model->save();
                continue;
            }
            else {
                return Action::danger('You can not proceed the request at this stage');
            }
        }
        return Action::message('Request Accepted Successfully');
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
