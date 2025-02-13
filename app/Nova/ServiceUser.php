<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;

class ServiceUser extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ServiceUser::class;

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
        'id',
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

            BelongsTo::make('Talent','user', Talent::class)
                ->searchable()
                ->sortable(),

            BelongsTo::make('Service','service', Service::class)
                ->searchable()
                ->sortable(),

            Number::make('Price', 'price')
                ->step(0.01)
                ->rules('required','numeric','gt:-1')
                ->required()
                ->onlyOnForms()
                ->showOnUpdating(),
            Text::make('Price', function() {
                return '$' . $this->price;
            })->exceptOnForms(),
            Text::make('Duration', 'duration')
                ->rules('required', 'date_format:G:i')
                ->default('0:30')
                ->help('Duration should be in ' . now()->startOfDay()->format('G:i') . ' this format')
                ->required(),

            Boolean::make('Is active','is_active')
                ->rules('required')
                ->default(true),

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
        return [];
    }
}
