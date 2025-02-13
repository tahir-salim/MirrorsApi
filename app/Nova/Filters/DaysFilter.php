<?php

namespace App\Nova\Filters;

use App\Models\TalentSchedule;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DaysFilter extends Filter
{
    public $name = 'Days';
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('day', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Sunday' => TalentSchedule::SUNDAY,
            'Monday' => TalentSchedule::MONDAY,
            'Tuesday'=> TalentSchedule::TUESDAY,
            'Wednesday' => TalentSchedule::WEDNESDAY,
            'Thursday' => TalentSchedule::THURSDAY,
            'Friday' => TalentSchedule::FRIDAY,
            'Saturday' => TalentSchedule::SATURDAY,
        ];
    }
}
