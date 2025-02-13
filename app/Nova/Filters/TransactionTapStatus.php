<?php

namespace App\Nova\Filters;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TransactionTapStatus extends Filter
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
        return $query->where('tap_status', $value);
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
            'Initiated' => Transaction::INITIATED,
            'Cancelled' => Transaction::CANCELLED,
            'Declined' => Transaction::DECLINED,
            'Captured' => Transaction::CAPTURED,
            'Failed' => Transaction::FAILED,
            'Unknown' => Transaction::UNKNOWN
        ];
    }
}
