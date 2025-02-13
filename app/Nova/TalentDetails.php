<?php

namespace App\Nova;

use App\Nova\Filters\IsFeaturedFilter;
use App\Nova\Filters\TalentStatusFilter;
use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;

class TalentDetails extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\TalentDetails::class;

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
        'id','title','bank_iban','bank_account_owner','bank_name'
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
            (new Tabs('Talent Details', [
                'Main Details' => [

                    ID::make()->sortable(),

                    BelongsTo::make('Talent','user', Talent::class)
                        ->searchable()
                        ->sortable(),

                    Text::make('Title','title'),

                    Textarea::make('About','about'),

                    Select::make('Status','status')
                        ->options([
                            \App\Models\TalentDetails::INACTIVE => 'Inactive',
                            \App\Models\TalentDetails::ACTIVE => 'Active',
                            \App\Models\TalentDetails::BLOCKED => 'Blocked'
                        ])
                        ->default(\App\Models\TalentDetails::ACTIVE)
                        ->rules('required')
                        ->required()
                        ->displayUsingLabels(),

                    Boolean::make('Is Featured','is_featured')
                        ->default(false)
                        ->rules('required')
                        ->required(),

                    Image::make('Avatar','avatar')
                        ->rules('nullable','image', 'mimes:jpeg,png,jpg')
                        ->disk('s3')
                        ->path('images/talentDetail'),

                ],
                'Social Details' => [
                    Text::make('Social instagram')
                        ->hideFromIndex(),

                    Text::make('Social snapchat')
                        ->hideFromIndex(),

                    Text::make('Social youtube')
                        ->hideFromIndex(),

                    Text::make('Social twitter')
                        ->hideFromIndex(),

                    Text::make('Social tik tok')
                        ->hideFromIndex(),
                ],
                'Bank Details' => [
                    Text::make('Bank name','bank_name')
                        ->hideFromIndex(),

                    Text::make('Bank account owner','bank_account_owner')
                        ->hideFromIndex(),

                    Text::make('Bank iban','bank_iban')
                        ->rules('nullable','string','max:34' ,'regex:/(^[A-Z0-9]*$)/')
                        ->hideFromIndex()
                        ->help('Only numerical characters (0 – 9) and capital letters (A – Z) are permitted. and not more then 34 digits'),

                ],
            ]))->withToolbar()->defaultSearch(true),
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
            TalentStatusFilter::make(),
            IsFeaturedFilter::make(),
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
