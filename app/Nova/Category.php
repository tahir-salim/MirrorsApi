<?php

namespace App\Nova;

use App\Nova\Filters\ActiveCategories;
use App\Nova\Filters\FeaturedCategories;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

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

            Text::make('Name')
                ->rules('required')
                ->required(),

            Image::make('Image wide','image_wide')
                ->disk('s3')
                ->path('images/categories')
                ->creationRules('required','image','mimes:jpeg,png,jpg')
                ->updateRules(function (NovaRequest $request) {
                    $model = $request->findModelOrFail();
                    return $model->image_wide ? [] : ['required','image','mimes:jpeg,png,jpg'];
                })
                ->deletable(false)
                ->required()
                ->hideFromIndex(),

            Image::make('Image square','image_square')
                ->disk('s3')
                ->path('images/categories')
                ->creationRules('required','image','mimes:jpeg,png,jpg','dimensions:ratio=1/1')
                ->updateRules(function (NovaRequest $request) {
                    $model = $request->findModelOrFail();
                     return $model->image_square ? ['image','mimes:jpeg,png,jpg','dimensions:ratio=1/1'] : ['required','image','mimes:jpeg,png,jpg','dimensions:ratio=1/1'];
                })
                ->deletable(false)
                ->required(),

            Boolean::make('Is active','is_active')
                ->default(true)
                ->rules('required')
                ->required(),

            Boolean::make('Is Featured','is_featured')
                ->default(false)
                ->rules('required')
                ->required(),

            HasMany::make('Sub Categories','subCategories', SubCategory::class),
            BelongsToMany::make('Talent','users', Talent::class)
                ->searchable(),


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
        return [
            ActiveCategories::make(),
            FeaturedCategories::make(),
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
