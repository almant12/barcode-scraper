<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{

    public function uploadImage(Request $request, $filename, $path)
    {

        if ($request->hasFile($filename)) {
            $image = $request->{$filename};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'image_' . uniqid() . '.' . $ext;
            $pathImage = $image->storeAs($path, $imageName, 'public');

            return $pathImage;
        }
    }

    public function uploadMultipleImages(Request $request, $filename, $path)
    {
        $imagesPaths = [];
        if ($request->hasFile($filename)) {
            $images = $request->{$filename};
            foreach ($images as $image) {
                $ext = $image->getClientOriginalExtension();
                $imageName = 'media_' . uniqid() . '.' . $ext;
                $image->move(public_path($path), $imageName);
                $imagesPaths[] = $path . '/' . $imageName;
            }
            return $imagesPaths;
        }
    }

    public function uploadMultipleImagesFromUrls(array $imageUrls, string $path): array
    {
        $imagesPaths = [];

        foreach ($imageUrls as $imageUrl) {
            $storedPath = $this->uploadImageFromUrl($imageUrl, $path);
            if ($storedPath !== null) {
                $imagesPaths[] = $storedPath;
            }
        }

        return $imagesPaths;
    }

    public function uploadImageFromUrl($imageUrl, $path)
    {
        $imageContents = file_get_contents($imageUrl);
        if ($imageContents === false) {
            return null;
        }

        $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
        $ext = $ext ?: "jpg";

        $imageName = 'image_' . uniqid() . '_' . $ext;
        Storage::disk('public')->put($path . '/' . $imageName, $imageContents);

        return $path . '/' . $imageName;
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

            return $pathImage;
        }
    }

    public function deleteImage($path): void
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
