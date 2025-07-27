<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    protected function makePublicPath(string $path): string
    {
        return '/storage/' . ltrim($path, '/');
    }

    public function uploadImage(Request $request, string $filename, string $path): ?string
    {
        if ($request->hasFile($filename)) {
            $image = $request->file($filename);
            $ext = $image->getClientOriginalExtension();
            $imageName = 'image_' . uniqid() . '.' . $ext;
            $pathImage = $image->storeAs($path, $imageName, 'public');

            return $this->makePublicPath($pathImage);
        }
        return null;
    }

    public function uploadMultipleImages(Request $request, string $filename, string $path): array
    {
        $imagesPaths = [];
        if ($request->hasFile($filename)) {
            $images = $request->file($filename);
            foreach ($images as $image) {
                $ext = $image->getClientOriginalExtension();
                $imageName = 'media_' . uniqid() . '.' . $ext;
                $pathImage = $image->storeAs($path, $imageName, 'public');
                $imagesPaths[] = $this->makePublicPath($pathImage);
            }
        }
        return $imagesPaths;
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

    public function uploadImageFromUrl(string $imageUrl, string $path): ?string
    {
        try {
            $imageContents = file_get_contents($imageUrl);
            if ($imageContents === false) {
                return null;
            }
            $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $imageName = 'image_' . uniqid() . '.' . $ext;
            $storagePath = $path . '/' . $imageName;

            Storage::disk('public')->put($storagePath, $imageContents);

            return $this->makePublicPath($storagePath);
        } catch (\Exception $e) {
            // Optionally log the error
            return null;
        }
    }

    public function uploadBase64Image(string $base64Image, string $path): ?string
    {

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $data = substr($base64Image, strpos($base64Image, ',') + 1);
            $data = base64_decode($data);

            if ($data === false) {
                return null;
            }

            $extension = strtolower($type[1]); // jpg, png, gif etc.
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                return null;
            }

            $fileName = 'image_' . uniqid() . '.' . $extension;
            $storagePath = $path . '/' . $fileName;

            Storage::disk('public')->put($storagePath, $data);

            return $this->makePublicPath($storagePath);
        }

        return null;
    }


    public function uploadMultipleBase64Images(array $base64Images, string $path): array
    {
        $storedPaths = [];
        foreach ($base64Images as $base64Image) {
            $uploadedPath = $this->uploadBase64Image($base64Image, $path);
            if ($uploadedPath !== null) {
                $storedPaths[] = $uploadedPath;
            }
        }
        return $storedPaths;
    }

    public function updateImage(Request $request, string $filename, string $oldPath, string $path): ?string
    {
        if ($request->hasFile($filename)) {
            Storage::disk('public')->delete($oldPath);
            return $this->uploadImage($request, $filename, $path);
        }
        return null;
    }

    public function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
