<?php

namespace App\Nova;

use App\Models\BookingRequest as ModelsBookingRequest;
use App\Models\ServiceUser;
use App\Nova\Actions\AcceptBookingRequest;
use App\Nova\Actions\CompleteBookingRequest;
use App\Nova\Actions\RejectBookingRequest;
use App\Nova\Filters\BookingRequestStatusFilter;
use App\Nova\Filters\HasRequest;
use App\Nova\Filters\RequestedDeliveryDateFilter;
use App\Nova\Filters\TalentFilter;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class BookingRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BookingRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title()
    {
        return optional($this->user)->name . ' ( ' . optional($this->talentUser)->name . ' - ' . date_format($this->requested_delivery_date, 'Y-m-d') . ' )';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'status',
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
            (new Tabs('Booking Details', [

                'Main Details' => [

                    ID::make()->sortable(),

                    BelongsTo::make('User', 'user', User::class)
                        ->searchable()
                        ->sortable()
                        ->rules('required')
                        ->required(),

                    BelongsTo::make('Talent', 'talentUser', Talent::class)
                        ->searchable()
                        ->sortable()
                        ->rules('required')
                        ->required(),

                    // this will show only on updating
                    Number::make('Price', 'price')
                        ->rules('numeric', 'gt:-1')
                        ->step(0.01)
                        ->onlyOnForms()
                        ->showOnUpdating()
                        ->hideWhenCreating(),


                    // this will return price and update price
                    Text::make('Price', function () {

                        $servicesPrice = 0;

                        if (!is_null(optional($this)->services) && optional($this)->services) {

                            $services = ServiceUser::where('is_active', true)
                                ->where('user_id', $this->talent_user_id)
                                ->whereIn('service_id', optional($this)->services->pluck('id'))
                                ->get();

                            if ($services) {
                                $servicesPrice = $services->sum('price');
                            }
                        }

                        // merge packages and services prices
                        $price = optional($this)->packages->sum('price') + $servicesPrice;
                        if ($this->payment_status != ModelsBookingRequest::STATUS_PAID && !$this->processed_at) {

                            $price = $price + $this->requestCharges()->sum('price');
                        }

                        // update price
                        if (!is_null($price)) {
                            $this->update([
                                'price' => $price,
                            ]);
                        }

                        return '$' . $price;
                    })
                        ->exceptOnForms(),
                    Text::make('Tax Amount', function() {
                        return '$' . $this->tax_amount;
                    })->exceptOnForms(),
                     Text::make('Total Price', function() {
                        return '$' . $this->total_price;
                    })->exceptOnForms(),
                    Textarea::make('Details', 'details'),

                    Select::make('Status', 'status')
                        ->options([
                            \App\Models\BookingRequest::PENDING => 'Pending',
                            \App\Models\BookingRequest::ACCEPTED => 'Accepted',
                            \App\Models\BookingRequest::CANCELED => 'Canceled',
                            \App\Models\BookingRequest::REJECTED => 'Rejected',
                            \App\Models\BookingRequest::COMPLETED => 'Completed',
                        ])
                        ->default(\App\Models\BookingRequest::PENDING)
                        ->rules('required')
                        ->required()
                        ->displayUsingLabels(),

                    Date::make('Requested delivery date', 'requested_delivery_date')
                        ->rules('required', 'date_format:Y-m-d', 'date', 'after_or_equal:' . today()->format('Y-m-d'))
                        ->required(),
                    //  ->help('Requested delivery date should be in ' . today()->format('Y-m-d') . ' this format'),

                    Select::make('Time', 'time')
                        ->sortable()
                        ->searchable()
                        ->rules('required', 'date_format:g:i A')
                        ->required()
                        ->options(\App\Models\TalentSchedule::todayTimeSlot())
                        ->displayUsingLabels(),

                    Boolean::make('Request on Same date', 'has_request_on_date')
                        ->default(false)
                        ->hideWhenCreating(),

                    Text::make('Duration', 'duration')
                        ->rules('required', 'date_format:G:i')
                        ->required()
                        ->default('0:30')
                        ->help('Duration should be in ' . now()->startOfDay()->format('G:i') . ' this format'),

                    File::make('Report', 'report_file_path')
                        ->disk('s3')
                        ->path('images/bookingRequest')
                        ->rules('nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048')
                        ->hideWhenCreating()
                        ->canSee(function () {
                            if ($this->status === \App\Models\BookingRequest::ACCEPTED || $this->status === \App\Models\BookingRequest::COMPLETED && $this->transaction && $this->transaction->tap_status === \App\Models\Transaction::CAPTURED) {
                                return true;
                            }
                            return false;

                        }),

                ],
                'Transaction Detail' => [

                    BelongsTo::make('Transaction', 'transaction', Transaction::class)
                        ->nullable()
                        ->exceptOnForms()
                        ->hideFromIndex(),

                    DateTime::make('Completed at', 'completed_at')
                        ->exceptOnForms()
                        ->hideFromIndex(),

                    DateTime::make('Processed at', 'processed_at')
                        ->exceptOnForms()
                        ->hideFromIndex(),
                ],
                'Review' => [
                    HasOne::make('Review', 'review', Review::class),
                ],
                'Attachments' => [
                    HasMany::make('Attachments', 'requestAttachments', RequestAttachment::class),
                ],
                'Comments' => [
                    HasMany::make('Request Comments', 'requestComments', RequestComment::class),
                ],
                //                'Talent Comments' => [
                //
                //                    BelongsToMany::make('Talent','requestComments', Talent::class)
                //                        ->fields(function () {
                //                            return [
                //                                Text::make('Comment', 'comment')
                //                                    ->rules('required')
                //                                    ->required()
                //                            ];
                //                        }),
                //
                //                ],
            ]))
                ->withToolbar()
                ->defaultSearch(true),
            (new Tabs('Relationships', [
                'Packages' => [
                    BelongsToMany::make('Packages', 'packages', Package::class),
                ],
                'Services' => [
                    BelongsToMany::make('Services', 'services', Service::class),
                ],
                'Request Charges' => [
                    HasMany::make('Request Charges', 'requestCharges', RequestCharges::class),
                ],
            ]))
                ->defaultSearch(true),
        ];
    }

    public static function relatableUsers(NovaRequest $request, $query, $field)
    {
        if ($field instanceof BelongsToMany) {
            if ($field->manyToManyRelationship === 'requestComments') {
                return $query->whereIn('role_id', [\App\Models\User::ROLE_USER, \App\Models\User::ROLE_TALENT]);
            }
        }
        return $query;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {

        $resource = $request->route('resource');
        $resourceId = $request->route('resourceId');
        if ($resource === 'talent' && $resourceId) {
            return $query->where('talent_user_id', $resourceId);
        }
        if ($resource === 'users' && $resourceId) {
            return $query->where('user_id', $resourceId);
        }

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
            BookingRequestStatusFilter::make(),
            TalentFilter::make(),
            RequestedDeliveryDateFilter::make(),
            HasRequest::make(),
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
        return [
            new AcceptBookingRequest(),
            new CompleteBookingRequest(),
            new RejectBookingRequest(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return true;
    }

    public function authorizedToUpdate(Request $request)
    {
        $authorizedActions = ['accept-booking-request', 'reject-booking-request', 'complete-booking-request'];
        if (in_array($request->action, $authorizedActions)) {
            return true;
        }
        return false;
    }
}
