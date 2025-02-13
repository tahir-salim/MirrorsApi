<?php

namespace App\Nova;

use App\Nova\Actions\BlockUser;
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
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
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
        'id', 'name', 'email', 'phone',
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

            (new Tabs('User', [

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

            ]))->withToolbar()->defaultSearch(true),

            (new Tabs('Relationships', [
                'Booking Request' => [

                    HasMany::make('Booking Request', 'requests', BookingRequest::class),

                ],
                'Booking Request Comments' => [
                    BelongsToMany::make('Booking Request', 'requestComments', BookingRequest::class)
                        ->fields(function () {
                            return [
                                Text::make('Comment', 'comment')
                                    ->rules('required')
                                    ->required(),
                            ];
                        }),
                ],
                // 'Chats' => [
                // BelongsToMany::make('Chats','chats', Chat::class),
                // ],
                //'Categories' => [
                //BelongsToMany::make('Categories','categories', Category::class),
                //],
            ]))->defaultSearch(true),
            (new Tabs('Other Relationships', [
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
                ->default(\App\Models\User::ROLE_USER),

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
        if ($resource === 'request-comments' || $resource === 'user-verifications' || $resource === 'notifications' || $resource === 'chat-users' || $resource === 'messages' || request()->viaRelationship === 'usersCountry') {
            return $query->whereIn('role_id', [\App\Models\User::ROLE_USER, \App\Models\User::ROLE_TALENT]);
        }

        return parent::indexQuery($request, $query->where('role_id', \App\Models\User::ROLE_USER)); // TODO: Change the autogenerated stub
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
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
            new BlockUser(),
            new \App\Nova\Actions\UserVerification(),
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return true;
    }
}
