<?php

namespace App\Observers;

use App\Libraries\ImageConversation;
use App\Models\TalentPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class TalentPostObserver
{
    /**
     * Handle the TalentPost "created" event.
     *
     * @param  \App\Models\TalentPost  $talentPost
     * @return void
     */
    public function creating(TalentPost $talentPost)
    {
        Nova::whenServing(function (NovaRequest $request) use ($talentPost) {
            // Only invoked during Nova requests...
            $file = $request->media_url;
            $thumbnail_url_path = '-';
            $imageResize = ImageConversation::imageResize($file, 300, 300);

            // if image is resized
            if ($imageResize) {
                $ext = $request->media_url->extension();
                $thumbnail_url_path = 'talentPost/' . Str::random(40) . '.' . $ext;
                Storage::disk('s3')->put($thumbnail_url_path, $imageResize);
            }

            $talentPost->media_thumbnail_url = $thumbnail_url_path;
        });
    }

    /**
     * Handle the TalentPost "created" event.
     *
     * @param  \App\Models\TalentPost  $talentPost
     * @return void
     */
    public function created(TalentPost $talentPost)
    {
        //
    }

    /**
     * Handle the TalentPost "updated" event.
     *
     * @param  \App\Models\TalentPost  $talentPost
     * @return void
     */
    public function updating(TalentPost $talentPost)
    {

        Nova::whenServing(function (NovaRequest $request) use ($talentPost) {

            if ($request->hasFile('media_url')) {
                // Only invoked during Nova requests...
                $file = $request->media_url;
                $thumbnail_url_path = '-';
                $imageResize = ImageConversation::imageResize($file, 300, 300);

                // if image is resized
                if ($imageResize) {
                    $ext = $request->media_url->extension();
                    $thumbnail_url_path = 'talentPost/' . Str::random(40) . '.' . $ext;
                    Storage::disk('s3')->put($thumbnail_url_path, $imageResize);
                }

                $talentPost->media_thumbnail_url = $thumbnail_url_path;
            }
        });
    }

    /**
     * Handle the TalentPost "deleted" event.
     *
     * @param  \App\Models\TalentPost  $talentPost
     * @return void
     */
    public function deleted(TalentPost $talentPost)
    {
        //
    }

    /**
     * Handle the TalentPost "restored" event.
     *
     * @param  \App\Models\TalentPost  $talentPost
     * @return void
     */
    public function restored(TalentPost $talentPost)
    {
        //
    }

    /**
     * Handle the TalentPost "force deleted" event.
     *
     * @param  \App\Models\TalentPost  $talentPost
     * @return void
     */
    public function forceDeleted(TalentPost $talentPost)
    {
        //
    }
}
