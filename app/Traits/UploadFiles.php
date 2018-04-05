<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait UploadFiles {

    protected function processImage($requestFile, $folder = 'avatars')
    {

        $path = Storage::disk('public')->put("images/$folder", $requestFile);

        if ($folder === 'avatars') Image::make($path)->fit(350, 350)->save($path);

        return $path;

    }

    protected function deleteImage($file)
    {
        Storage::disk('public')->delete($file);
    }

}