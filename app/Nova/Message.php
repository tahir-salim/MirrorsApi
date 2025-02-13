<?php

namespace App\Nova;

use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Message extends Resource
{
    use HasConditionalContainer;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Message::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title()
    {
        return optional($this)->chat->id.' ( '.optional($this)->user->name.' )';
    }

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
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('User/Talent','user', User::class)
                ->rules('required')
                ->required()
                ->sortable()
                ->searchable(),

            BelongsTo::make('Chat','chat', Chat::class)
                ->rules('required')
                ->required()
                ->sortable(),

            Textarea::make('Message','message'),

            Boolean::make('Is media','is_media')
                ->default(false),

            ConditionalContainer::make([

                File::make('Media','media')
                    ->disk('s3')
                    ->path('images/messages')
                    ->creationRules('required')
                    ->updateRules(function (NovaRequest $request) {
                        $model = $request->findModelOrFail();
                        return $model->media ? [] : ['required'];
                    }),

            ])
                ->if('is_media = 1'),

            HasMany::make('Users', 'chatUser', ChatUser::class)
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
