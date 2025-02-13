<?php

namespace App\Nova;

use App\Nova\Filters\ServiceCategoriesFilter;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Service extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Service::class;

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
        'id', 'name',
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
            (new Tabs('Service', [

                'Main Details' => [

                    ID::make()->sortable(),

                    BelongsTo::make('ServiceCategory', 'serviceCategory', ServiceCategory::class)
                        ->searchable()
                        ->sortable(),

                    Text::make('Name')
                        ->rules('required', 'string'),

                    Text::make('Description')
                        ->rules('required')
                        ->required(),

                    Image::make('Icon', 'icon')
                        ->disk('s3')
                        ->path('images/service')
                        ->creationRules('image', 'mimes:jpeg,png,jpg', 'dimensions:ratio=1/1')
                        ->updateRules(function (NovaRequest $request) {
                            return ['dimensions:ratio=1/1'];
                        }),

                ],
            ]))->withToolbar()->defaultSearch(true),

            (new Tabs('Relationships', [

                'Talent' => [
                    BelongsToMany::make('Talent', 'serviceUsers', Talent::class)
                        ->fields(function () {
                            return [
                                Number::make('Price')
                                    ->rules('required', 'numeric', 'gt:-1')
                                    ->step(0.01)
                                    ->required()
                                    ->onlyOnForms()
                                    ->showOnUpdating(),
                                Text::make('Price', function() {
                                    return '$' . $this->price;
                                })->exceptOnForms(),
                                Text::make('Duration')
                                    ->rules('required', 'date_format:G:i')
                                    ->required()
                                    ->default('0:30')
                                    ->help('Duration should be in ' . now()->startOfDay()->format('G:i') . ' this format'),

                                Boolean::make('Is active', 'is_active')
                                    ->rules('required')
                                    ->default(false),
                            ];
                        })
                        ->searchable(),
                ],

                'Packages' => [
                    BelongsToMany::make('Packages', 'packages', Package::class),
                ],

                'Booking Request' => [
                    BelongsToMany::make('Booking Requests', 'bookingRequest', BookingRequest::class),
                ],

            ]))->defaultSearch(true),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $resource = $request->route('resource');
        $resourceId = $request->route('resourceId');

        // this query is for only when you came from booking-requests resource
        if ($resource === 'booking-requests' && $resourceId) {
            $servicesIds = [];
            $bookingRequest = \App\Models\BookingRequest::find($resourceId);

            if ($bookingRequest && $bookingRequest->talent_user_id) {

                $servicesIds = \App\Models\ServiceUser::isActive()
                    ->where('user_id', $bookingRequest->talent_user_id)
                    ->pluck('service_id');

                if (count($servicesIds)) {
                    return $query->whereIn('id', $servicesIds);
                }
            }

            return $query->whereIn('id', $servicesIds);
        }

        // for all resources except booking-requests
        return parent::indexQuery($request, $query);
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
            ServiceCategoriesFilter::make(),
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
