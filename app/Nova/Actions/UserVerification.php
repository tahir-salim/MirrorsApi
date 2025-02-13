<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class UserVerification extends Action
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

            if ($model->is_blocked == 1) {
                return Action::danger('This user is blocked');
            }

            if (is_null($model->phone_verified_at)) {

                // phone verify
                $model->phone_verified_at = now();
                $model->save();

                if ($model->verification){
                    $model
                        ->verification()
                        ->update([
                            'status' => \App\Models\UserVerification::STATUS_VERIFIED,
                            'token' => null,
                            ]);
                }
                continue;
            }
            else {
                return Action::danger('Already verified');
            }
        }
        return Action::message('Verified Successfully');
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
