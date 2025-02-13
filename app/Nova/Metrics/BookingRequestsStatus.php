<?php

namespace App\Nova\Metrics;

use App\Models\BookingRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class BookingRequestsStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, BookingRequest::class, 'status')
            ->label(function ($value) {
                switch ($value) {
                    case BookingRequest::PENDING:
                        return 'Pending';
                    case BookingRequest::ACCEPTED:
                        return 'Accepted';
                    case BookingRequest::CANCELED:
                        return 'Canceled';
                    case BookingRequest::REJECTED:
                        return 'Rejected';
                    case BookingRequest::COMPLETED:
                        return 'Completed';
                    default:
                        return '-';
                }
            });
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
        return 'booking-requests-status';
    }
}
