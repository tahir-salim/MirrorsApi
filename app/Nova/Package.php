<?php

namespace App\Nova;

use App\Nova\Filters\TalentFilter;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Package extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Package::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','name'
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
                ->sortable()
                ->hideWhenUpdating(),

            Text::make('Name','name')
                ->rules('required')
                ->required(),

            Number::make('Price','price')
                ->step(0.01)
                ->rules('required','numeric','gt:-1')
                ->required()
                ->onlyOnForms()
                ->showOnUpdating(),
            Text::make('Price', function() {
                return '$' . $this->price;
            })->exceptOnForms(),
            Textarea::make('Description','description'),

            BelongsToMany::make('Booking Request','bookingRequest', BookingRequest::class),

            BelongsToMany::make('Service','services', Service::class),

        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $resource = $request->route('resource');
        $resourceId = $request->route('resourceId');
        // only show those talent packages which is selected
        if($resource === 'booking-requests' && $resourceId){
            $talentId = \App\Models\BookingRequest::find($resourceId);
            return $query->where('user_id', $talentId['talent_user_id']);
        }
        return $query;
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
            TalentFilter::make(),
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
