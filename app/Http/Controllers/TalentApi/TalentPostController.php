<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Libraries\ImageConversation;
use App\Models\TalentPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class TalentPostController extends Controller
{
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,png,jpeg',
            'body' => 'required|min:3|max:2000',
            'media_type' =>'required|integer'
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $talent = User::isTalent()->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'unauthorized', null, 404);
        }

        $file = $request->file('file');

        $media_url = $file->store('talentPost', 's3');

        // for thumbnail media
        $thumbnail_url_path = '-';
        $imageResize = ImageConversation::imageResize($file,300,300);

        // if image is resized
        if ($imageResize){
            $ext = $file->getClientOriginalExtension();
            $thumbnail_url_path = 'talentPost/'.Str::random(40).'.'.$ext;
            Storage::disk('s3')->put($thumbnail_url_path, $imageResize);
        }

        $media_thumbnail_url = $thumbnail_url_path;

        $data = [
            'media_type' => $request->media_type,
            'media_url' => $media_url,
            'media_thumbnail_url' => $media_thumbnail_url,
            'body' => $request->body
        ];

        $talent->talentPosts()->create($data);

        return $this->formatResponse('success', 'talent-post-created', $data);
    }

    public function delete($postID)
    {
        $talent = User::isTalent()->find(Auth::id());
        if (!$talent){
            return $this->formatResponse('error', 'unauthorized', null, 403);
        }

        $talentPost = TalentPost::find($postID);

        if(!$talentPost) {
            return $this->formatResponse('error', 'post-not-found', null, 404);
        }
        if($talentPost->user_id != Auth::id()) {
            return $this->formatResponse('error', 'unauthorized', null, 403);
        }

        $talentPost->delete();
        return $this->formatResponse('success', 'post-deleted', null);
    }

    public function update(Request $request, $postID)
    {
          $validation = Validator::make($request->all(), [
            'body' => 'required|min:3|max:2000',
        ]);

         if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

         $talent = User::isTalent()->find(Auth::id());
        if (!$talent){
            return $this->formatResponse('error', 'unauthorized', null, 403);
        }

        $talentPost = TalentPost::find($postID);

        if(!$talentPost) {
            return $this->formatResponse('error', 'post-not-found', null, 404);
        }
        if($talentPost->user_id != Auth::id()) {
            return $this->formatResponse('error', 'unauthorized', null, 403);
        }

         $talentPost->update([
            'body' => $request->body
        ]);

        return $this->formatResponse('success', 'post-updated', $talentPost);

    }
}
