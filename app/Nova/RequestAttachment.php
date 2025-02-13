<?php

namespace App\Nova;

use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class RequestAttachment extends Resource
{
    use HasConditionalContainer;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\RequestAttachment::class;

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

            BelongsTo::make('Request', 'bookingRequest', BookingRequest::class)
                ->searchable()
                ->sortable(),

            Select::make('Type','file_type')
                ->options([
                    \App\Models\RequestAttachment::IMAGE => 'Image',
                    //\App\Models\RequestAttachment::VIDEO => 'Video',
                    //\App\Models\RequestAttachment::DOCUMENT => 'Document',
                ])
                ->default(\App\Models\RequestAttachment::IMAGE)
                ->required()
                ->rules('required')
                ->displayUsingLabels(),

            File::make('File path', 'file_path')
                ->disk('s3')
                ->path('requestAttachment/attachments')
                ->rules('required','image','mimes:jpeg,png,jpg')
                ->required(),

            Text::make('File Name', 'file_name'),
            // ConditionalContainer::make([
            //     File::make('File path', 'file_path')
            //         ->disk('s3')
            //         ->path('requestAttachment/attachments')
            //         ->rules('required|file')
            //         ->required(),
            // ])
            //     ->if('file_type = 3'),

            // ConditionalContainer::make([
            //     File::make('File path', 'file_path')
            //         ->disk('s3')
            //         ->path('requestAttachment/attachments')
            //         ->rules('required|video')
            //         ->required(),
            // ])
            //     ->if('file_type = 2'),

            // ConditionalContainer::make([
            //     Image::make('File path', 'file_path')
            //         ->disk('s3')
            //         ->path('requestAttachment/attachments')
            //         ->rules('required|dimensions:min_width=100,min_height=200')
            //         ->required(),
            // ])
            //     ->if('file_type = 1'),

            Text::make('Description', 'description'),
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
