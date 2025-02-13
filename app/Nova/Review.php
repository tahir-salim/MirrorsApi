<?php

namespace App\Nova;

use App\Nova\Actions\ApproveReview;
use App\Nova\Actions\RejectReview;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;

class Review extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Review::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','details'
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

            BelongsTo::make('Booking Request','bookingRequest', BookingRequest::class)
                ->sortable()
                ->rules('required')
                ->required(),

            Number::make('Rating','rating')
                ->rules('required','gt:-1','lte:5')
                ->required()
                ->help('Rating must be starts from zero and end at five ie: 0 to 5'),

            Textarea::make('Details','details'),
            Select::make('Status')
                ->options(\App\Models\Review::GET_STATUSES())
                ->displayUsingLabels()
                ->exceptOnForms(),
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
        return [];
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
        return [
            ApproveReview::make(),
            RejectReview::make(),
        ];
    }

     public function authorizedToUpdate(Request $request)
    {
        $authorizedActions = ['reject-review', 'approve-review'];
        if (in_array($request->action, $authorizedActions)) {
            return true;
        }
        return false;
    }
}
