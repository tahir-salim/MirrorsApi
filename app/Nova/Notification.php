<?php

namespace App\Nova;

use App\Nova\Filters\NotificationActionType;
use App\Nova\Filters\ReadNotification;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Notification extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Notification::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title',
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

            BelongsTo::make('User/Talent', 'user', User::class)
                ->searchable()
                ->sortable(),

            Text::make('Title')
                ->rules('required', 'string'),

            Textarea::make('Body', 'body')
                ->rules('required')
                ->required(),

            Boolean::make('Is read', 'is_read')
                ->default(false)
                ->rules('required')
                ->required(),

            Select::make('Action type', 'action_type')
                ->rules('required')
                ->required()
                ->options([
                    \App\Models\Notification::TYPE_BOOKING_ACCEPTED => 'Type Booking Accepted',
                    \App\Models\Notification::TYPE_BOOKING_REJECTED => 'Type Booking Rejected',
                    \App\Models\Notification::TYPE_USER_COMMENT => 'Type User Comment',
                    \App\Models\Notification::TYPE_TALENT_COMMENT => 'Type Talent Comment',
                    \App\Models\Notification::TYPE_BOOKING_CANCELED => 'Type Booking Canceled',
                    \App\Models\Notification::TYPE_BOOKING_REVIEW => 'Type Booking Review',
                    \App\Models\Notification::TYPE_NEW_BOOKING => 'Type New Booking',
                    \App\Models\Notification::TYPE_BOOKING_UPDATE => 'Type Booking Update',
                    \App\Models\Notification::TYPE_REVIEW_INQUIRY => 'Type Review Inquiry',
                ])
                ->displayUsingLabels(),

            MorphTo::make('Notificationable', 'notificationable')
                ->types([
                    BookingRequest::class,
                    RequestComment::class,
                    Review::class,
                    Message::class,
                    User::class,
                    Talent::class,
                    TalentPost::class,
                    Transaction::class,
                    TalentSchedule::class,
                    Chat::class,
                    ChatUser::class,
                ])
                ->searchable(),
        ];
    }

    public static function relatableUsers(NovaRequest $request, $query, $field)
    {
        if ($field instanceof BelongsTo) {
            if ($field->belongsToRelationship == 'user') {
                return $query->whereIn('role_id', [\App\Models\User::ROLE_USER, \App\Models\User::ROLE_TALENT]);
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
        return [
            ReadNotification::make(),
            NotificationActionType::make(),
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
