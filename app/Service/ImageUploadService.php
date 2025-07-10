<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ImageUploadService
{

    public function uploadImage(Request $request, $filename, $path)
    {

        if ($request->hasFile($filename)) {
            $image = $request->{$filename};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'image_' . uniqid() . '.' . $ext;
            $pathImage = $image->storeAs($path, $imageName, 'public');

            return 'storage/' . $pathImage;
        }
    }

    public function updateImage(Request $request, $filename, $oldPath, $path)
    {

        if ($request->hasFile($filename)) {
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $image = $request->{$filename};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'image_' . uniqid() . '.' . $ext;
            $pathImage = $image->storeAs($path, $imageName, 'public');

            return 'storage/' . $pathImage;
        }
    }

    public function deleteImage($path): void
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
