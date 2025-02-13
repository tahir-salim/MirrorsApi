<?php

namespace App\Nova;

use App\Nova\Filters\SuccessTransaction;
use App\Nova\Filters\TransactionTapStatus;
use Illuminate\Contracts\Support\Jsonable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Textarea;

class Transaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title()
    {
        return optional($this->user)->name . ' ( ' . $this->tap_status . ' )';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'status', 'currency', 'tap_customer_id'
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
            ID::make()->sortable(),

            BelongsTo::make('User', 'user', User::class)
                ->searchable()
                ->sortable()
                ->rules('required')
                ->required(),



            Select::make('Status', 'status')
                ->rules('required')
                ->required()
                ->options([
                    \App\Models\Transaction::PENDING => 'PENDING',
                    \App\Models\Transaction::CANCEL => 'CANCEL',
                    \App\Models\Transaction::COMPLETED => 'COMPLETED',
                ])
                ->displayUsingLabels()
                ->hideFromIndex(),

            Number::make('Amount')
                ->step(0.01),

            Number::make('Usd amount')
                ->step(0.01)
                ->hideFromIndex(),

            Text::make('Currency'),

            DateTime::make('Payed At', 'paid_at')
                ->hideFromIndex(),

            Text::make('Tap Customer ID')
                ->hideFromIndex(),

            Text::make('Tap Charge Id')
                ->hideFromIndex(),

            Select::make('Tap Status')
                ->options(\App\Models\Transaction::GET_TAP_STATUS())
                ->displayUsingLabels(),
            Text::make('Tap Response Code'),

            KeyValue::make('Tap Response')
                ->rules('json')
                ->hideFromIndex(),

            Text::make('Payment Link')
                ->hideFromIndex(),

            DateTime::make('Created at')
                ->hideFromIndex(),

            DateTime::make('Updated at')
                ->hideFromIndex(),

            Text::make('Origin')
                ->hideFromIndex(),

            Boolean::make('Is success', 'is_success')
                ->default(false)
                ->rules('required')
                ->required(),

            BelongsTo::make('Booking Request', 'bookingRequest', BookingRequest::class)
                ->searchable()
                ->sortable()
                ->required(),

            BelongsTo::make('Request Charge', 'requestCharge', RequestCharges::class)
                ->searchable()
                ->sortable()
                ->required(),
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
            SuccessTransaction::make(),
            TransactionTapStatus::make()
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
}
