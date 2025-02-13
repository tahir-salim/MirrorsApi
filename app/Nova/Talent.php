<?php

namespace App\Nova;

use App\Nova\Actions\BlockUser;
use App\Nova\Actions\CreateTimeSchedule;
use App\Nova\Filters\BlockedUser;
use App\Nova\Filters\UserByCountry;
use App\Nova\Filters\VerifiedUser;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Talent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public function title()
    {
        $resource = request()->route('resource');
        $resourceId = request()->route('resourceId');
        if ($resource === 'request-comments' || $resource === 'user-verifications' || $resource === 'notifications' || $resource === 'chat-users' || $resource === 'messages' || request()->viaRelationship === 'usersCountry') {
            return $this->id . ' | ' . optional($this)->name . ' ( ' . ((optional($this)->role_id && $this->role_id === \App\Models\User::ROLE_TALENT) ? 'Talent' : 'User') . ' )';
        }

        return $this->id . ' | ' . optional($this)->name . ' ( ' . optional($this)->email . ' )';
    }

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

            (new Tabs('Talent', [

                'Main Details' => [

                    ID::make(__('ID'), 'id')->sortable(),

                    Text::make('Name', 'name')
                        ->rules('required', 'string')
                        ->required(),

                    Text::make('Email', 'email')
                        ->sortable()
                        ->rules('required', 'email', 'max:254')
                        ->creationRules('unique:users,email,NULL,id,deleted_at,NULL')
                        ->updateRules('unique:users,email,{{resourceId}},id,deleted_at,NULL')
                        ->hideWhenUpdating(),

                    BelongsTo::make('Country', 'country', Country::class)
                        ->nullable()
                        ->sortable()
                        ->searchable(),

                    Text::make('Phone', 'phone')
                        ->sortable()
                        ->rules('required', 'integer', 'regex:/(^[0-9]+$)/', 'digits_between:7,15')
                        ->creationRules('unique:users,phone,NULL,id,deleted_at,NULL')
                        ->updateRules('unique:users,phone,{{resourceId}},id,deleted_at,NULL')
                        ->help('phone should be with country code without + sign')
                        ->hideWhenUpdating(),

                    Password::make('Password')
                        ->onlyOnForms()
                        ->creationRules('required', 'string', 'min:6')
                        ->updateRules('nullable', 'string', 'min:6'),

                    Boolean::make('Is Verified', function ($q) {
                        return $this->phone_verified_at ? true : false;
                    })
                        ->exceptOnForms(),

                    Boolean::make('Is blocked', 'is_blocked')
                        ->default(false)
                        ->rules('required')
                        ->required(),

                    Avatar::make('Avatar', 'avatar')
                        ->rules('nullable', 'image', 'mimes:jpeg,png,jpg')
                        ->disk('s3')
                        ->path('images/users'),
                ],

                'Talent Detail' => [
                    // Talent Detail for User Type Id Talent
                    HasOne::make('Detail', 'talentDetail', TalentDetails::class),
                ],

                'Schedule' => [
                    HasMany::make('Schedules', 'talentSchedules', TalentSchedule::class),
                ],

            ]))->withToolbar()->defaultSearch(true),
            (new Tabs('Relationships', [

                'Booking Request' => [
                    HasMany::make('Booking Request', 'talentRequests', BookingRequest::class),
                ],

                'Booking Request Comments' => [
                    BelongsToMany::make('Booking Request Comments', 'requestComments', BookingRequest::class)
                        ->fields(function () {
                            return [
                                Text::make('Comment', 'comment')
                                    ->rules('required')
                                    ->required(),
                            ];
                        }),
                ],

                //'Chats' => [
                //BelongsToMany::make('Chats','chats', Chat::class),
                //],

            ]))->defaultSearch(true),
            (new Tabs('Others', [
                'Categories' => [
                    BelongsToMany::make('Categories', 'categories', Category::class),
                ],

                'Packages' => [
                    HasMany::make('Packages', 'packages', Package::class),
                ],

                'Services' => [
                    BelongsToMany::make('Services', 'services', Service::class)
                        ->fields(function () {
                            return [

                                Number::make('Price', 'price')
                                    ->rules('required', 'numeric', 'gt:-1')
                                    ->step(0.01)
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

                                Boolean::make('Is active', 'is_active')->default(true),
                            ];
                        }),
                ],
            ]))->defaultSearch(true),
            (new Tabs('Other Relationships', [
                'Posts' => [
                    HasMany::make('Talent Posts', 'talentPosts', TalentPost::class),
                ],
                'Notifications' => [
                    HasMany::make('Notifications', 'notifications', Notification::class),
                ],
                'Transactions' => [
                    HasMany::make('transactions', 'transactions', Transaction::class),
                ],
                'Verification' => [
                    HasOne::make('Verification', 'verification', UserVerification::class),
                ],
            ]))->defaultSearch(true),

            // All Hidden Fields
            Hidden::make('role_id')
                ->default(\App\Models\User::ROLE_TALENT),

            Hidden::make('Email verified at', 'email_verified_at')
                ->default(function ($request) {return now();}),

            Hidden::make('Phone verified at', 'phone_verified_at')
                ->default(function ($request) {return now();}),

            Hidden::make('Last activity', 'last_activity')
                ->default(function ($request) {return now();}),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {

        $resource = $request->route('resource');
        $resourceId = $request->route('resourceId');
        if ($resource === 'request-comments' || $resource === 'user-verifications' || $resource === 'notifications' && !$resourceId && !$request->viaResource) {
            return $query->whereIn('role_id', [\App\Models\User::ROLE_USER, \App\Models\User::ROLE_TALENT]);
        }

        return parent::indexQuery($request, $query->where('role_id', \App\Models\User::ROLE_TALENT));
    }

    public static function relatableQuery(NovaRequest $request, $query)
    {
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
            VerifiedUser::make(),
            BlockedUser::make(),
            UserByCountry::make(),
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
            CreateTimeSchedule::make(),
            BlockUser::make(),
            \App\Nova\Actions\UserVerification::make(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return true;
    }
}
