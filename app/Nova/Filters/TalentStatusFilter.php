<?php

namespace App\Nova\Filters;

use App\Models\TalentDetails;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TalentStatusFilter extends Filter
{
    public $name = 'Talent Status';
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
        return $query->where('status', $value);
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
            'Inactive' => TalentDetails::INACTIVE,
            'Active' => TalentDetails::ACTIVE,
            'Blocked' => TalentDetails::BLOCKED,
        ];
    }
}
