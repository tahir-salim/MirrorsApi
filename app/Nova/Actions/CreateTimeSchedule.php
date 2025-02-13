<?php

namespace App\Nova\Actions;

use App\Models\TalentSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class CreateTimeSchedule extends Action
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
        if(!$models->count()){
            return Action::danger('please select talent');
        }

        foreach ($models as $model){
            // Delete the slots for the day
            TalentSchedule::where('user_id', $model['id'])->where('day', $fields['day'])->delete();
            $timeSlots = (new \App\Models\TalentSchedule)->splitTime($fields->start_time,$fields->end_time);

            foreach ($timeSlots as $time){

                TalentSchedule::firstOrCreate(
                    ['user_id' =>  $model['id'],'day' => $fields['day'], 'time' => $time],
                    ['user_id' =>  $model['id'],'day' => $fields['day'], 'time' => $time]
                );

            }
        }

    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $fields=[];

        $fields[] = Select::make('Day', 'day')
            ->rules('required','gt:-1','min:0','max:6')
            ->required()
            ->options([
                TalentSchedule::SUNDAY => 'Sunday',
                TalentSchedule::MONDAY => 'Monday',
                TalentSchedule::TUESDAY => 'Tuesday',
                TalentSchedule::WEDNESDAY => 'Wednesday',
                TalentSchedule::THURSDAY => 'Thursday',
                TalentSchedule::FRIDAY => 'Friday',
                TalentSchedule::SATURDAY => 'Saturday',
            ])
            ->displayUsingLabels();

        $fields[] = Select::make('Starting Time','start_time')
                ->rules('required')
                ->required()
                ->searchable()
                ->options(TalentSchedule::todayTimeSlot())
                ->displayUsingLabels();

         $fields[] = Select::make('Ending Time','end_time')
                ->rules('required','after:start_time')
                ->required()
                ->searchable()
                ->options(TalentSchedule::todayTimeSlot())
                ->displayUsingLabels();

        return $fields;
    }
}
