<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class UserVerification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\UserVerification::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','email','phone','token'
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

            Text::make('Email','email')
                ->sortable()
                ->rules('nullable', 'email', 'max:254')
                ->creationRules('unique:user_verifications,email')
                ->updateRules('unique:user_verifications,email,{{resourceId}}')
                ->hideWhenUpdating(),

            BelongsTo::make('Country','country', Country::class)
                ->hideWhenUpdating(),

            Text::make('Phone','phone')
                ->sortable()
                ->rules('required', 'string')
                ->creationRules('unique:user_verifications,phone')
                ->updateRules('unique:user_verifications,phone,{{resourceId}}')
                ->hideWhenUpdating(),

            Text::make('Token','token')
                ->rules('nullable','max:4'),

            BelongsTo::make('User / Talent','user', User::class)
                ->hideWhenUpdating(),

            Select::make('Status','status')
                ->options([
                    \App\Models\UserVerification::STATUS_PENDING => 'Pending',
                    \App\Models\UserVerification::STATUS_VERIFIED => 'Verified',
                ])
                ->default(\App\Models\UserVerification::STATUS_PENDING)
                ->rules('required')
                ->required()
                ->displayUsingLabels(),

        ];
    }

    public static function relatableUsers(NovaRequest $request, $query, $field)
    {
        if ($field instanceof BelongsTo) {
            if($field->belongsToRelationship == 'user'){
                return $query->whereIn('role_id', [\App\Models\User::ROLE_USER,\App\Models\User::ROLE_TALENT]);
            }
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
