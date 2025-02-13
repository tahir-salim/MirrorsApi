<?php

namespace App\Nova;

use App\Nova\Filters\DaysFilter;
use App\Nova\Filters\TimeFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TalentSchedule extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\TalentSchedule::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title()
    {
        return optional($this)->user? optional($this)->user->name:$this->id;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','day','time'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [

            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('User', 'user', Talent::class)
                ->searchable()
                ->sortable(),

            Select::make('Day', 'day')
                ->rules('required','gt:-1','min:0','max:6')
                ->required()
                ->options([
                    \App\Models\TalentSchedule::SUNDAY => 'Sunday',
                    \App\Models\TalentSchedule::MONDAY => 'Monday',
                    \App\Models\TalentSchedule::TUESDAY => 'Tuesday',
                    \App\Models\TalentSchedule::WEDNESDAY => 'Wednesday',
                    \App\Models\TalentSchedule::THURSDAY => 'Thursday',
                    \App\Models\TalentSchedule::FRIDAY => 'Friday',
                    \App\Models\TalentSchedule::SATURDAY => 'Saturday',
                ])
                ->displayUsingLabels(),
                // ->help('Day should be in numeric representation of the day of the week. : 0 (for Sunday) through 6 (for Saturday)'),

            Select::make('Time','time')
                ->sortable()
                ->searchable()
                ->rules('required','date_format:g:i A')
                ->required()
                ->options(\App\Models\TalentSchedule::todayTimeSlot())
                ->displayUsingLabels()
                //  ->help('Time should be in '.now()->format('g:i A'). ' this format and should be in 30 min time slot from start time'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            DaysFilter::make(),
            TimeFilter::make(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

//    protected static function fillFields(NovaRequest $request, $model, $fields)
//    {
//        $fillFields = parent::fillFields($request, $model, $fields);
//
//        // first element should be model object
//        $modelObject = $fillFields[0];
//
//        // remove all attributes do not have relevant columns in model table
//        unset($modelObject->start_time);
//        unset($modelObject->end_time);
//
//        return $fillFields;
//    }
}
