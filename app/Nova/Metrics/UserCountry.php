<?php

namespace App\Nova\Metrics;

use App\Models\Country;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class UserCountry extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $data = Country::withCount('usersCountry')
            ->where('is_active', true)
            ->whereHas('usersCountry')
            ->get()
            ->toArray();

        return $this->result(array_combine(array_column($data, 'name'), array_column($data, 'users_country_count')));
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-country';
    }
}
