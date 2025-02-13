<?php

namespace App\Libraries;
use Intervention\Image\Facades\Image;
class ImageConversation
{
    public static function imageResize($imageUrl, $resizeWidth, $min)
    {
        $imageResize = Image::make($imageUrl);

        if ($imageResize->width() > $min || $imageResize->height() > $min) {
            $imageResize->resize($resizeWidth, $resizeWidth, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            return $imageResize->encode();
        }
        else{
            return $imageResize->encode();
        }
    }

}
