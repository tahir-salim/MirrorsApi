<?php

namespace App\Nova\Filters;

use App\Models\Notification;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class NotificationActionType extends Filter
{
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
        return $query->where('action_type', $value);
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
            'Type Booking Accepted' => Notification::TYPE_BOOKING_ACCEPTED,
            'Type Booking Rejected' => Notification::TYPE_BOOKING_REJECTED,
            'Type User Comment' => Notification::TYPE_USER_COMMENT,
            'Type Talent Comment' => Notification::TYPE_TALENT_COMMENT,
            'Type Booking Canceled' => Notification::TYPE_BOOKING_CANCELED,
            'Type Booking Review' => Notification::TYPE_BOOKING_REVIEW,
            'Type New Booking' => Notification::TYPE_NEW_BOOKING,
            'Type Booking Update' => Notification::TYPE_BOOKING_UPDATE,
            'Type Review Inquiry' => Notification::TYPE_REVIEW_INQUIRY
        ];
    }
}
