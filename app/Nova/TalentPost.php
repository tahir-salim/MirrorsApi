<?php

namespace App\Nova;

use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class TalentPost extends Resource
{
    use HasConditionalContainer;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\TalentPost::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title()
    {
        return optional($this)->user ? optional($this)->user->name : $this->id;
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
            ID::make()->sortable(),

            BelongsTo::make('Talent', 'user', Talent::class)
                ->sortable()
                ->searchable(),

            Select::make('Media type', 'media_type')
                ->options([
                    \App\Models\TalentPost::IMAGE => 'Image',
                    // \App\Models\TalentPost::VIDEO => 'Video',
                    // \App\Models\TalentPost::FILE => 'File',

                ])
                ->rules('required')
                ->required()
                ->displayUsingLabels(),

            ConditionalContainer::make([
                File::make('Media', 'media_url')
                    ->required()
                    ->disk('s3')
                    ->path('files/talentPost')
                    ->rules('required'),

                File::make('Media thumbnail', 'media_thumbnail_url')
                    ->required()
                    ->disk('s3')
                    ->path('files/talentPost')
                    ->rules('required'),
            ])
                ->if('media_type = 3 OR media_type = 2'),

            // ConditionalContainer::make([

            //     Image::make('Media', 'media_url')
            //         ->required()
            //         ->disk('s3')
            //         ->path('talentPost')
            //         ->creationRules('required', 'image', 'mimes:jpg,png,jpeg')
            //         ->updateRules(function (NovaRequest $request) {
            //             $model = $request->findModelOrFail();
            //             return $model->media_url ? ['image', 'mimes:jpg,png,jpeg'] : ['required', 'image', 'mimes:jpg,png,jpeg'];
            //         }),

            //     Image::make('Media Thumbnail', 'media_thumbnail_url')
            //         ->onlyOnDetail()
            //         ->disk('s3'),

            // ])->if('media_type = 1'),

            Image::make('Media', 'media_url')
                ->required()
                ->disk('s3')
                ->path('talentPost')
                ->creationRules('required', 'image', 'mimes:jpg,png,jpeg')
                ->updateRules(function (NovaRequest $request) {
                    $model = $request->findModelOrFail();
                    return $model->media_url ? ['image', 'mimes:jpg,png,jpeg'] : ['required', 'image', 'mimes:jpg,png,jpeg'];
                }),

            Image::make('Media Thumbnail', 'media_thumbnail_url')
                ->onlyOnDetail()
                ->disk('s3'),

            Textarea::make('Body')->rules('required')->required(),

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
