<?php

namespace App\Nova\Actions;

use App\Models\TalentDetails;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class BlockUser extends Action
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
                return Action::danger('this user is already blocked');
            }

            if ($model->is_blocked == 0) {

                if ($model->role_id == User::ROLE_TALENT){
                    if ($model->talentDetail){
                        $model->talentDetail()->update([
                            'status' => TalentDetails::BLOCKED
                        ]);
                    }
                }

                $model->is_blocked = 1;
                $model->save();

                continue;
            }
        }
        return Action::message('user blocked successfully');
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
