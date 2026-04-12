<?php

namespace App\Services;

use Illuminate\Support\Str;

class ImageUploadService
{
    public function uploadToPublic($file, $folder = 'blog_images')
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path($folder);

        // Create folder if not exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Move file to public folder
        $file->move($destinationPath, $filename);

        return [
            'path' => $folder . '/' . $filename,
            'url'  => asset($folder . '/' . $filename),
        ];
    }
}