<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Service\ImageUploadService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->$imageUploadService = $imageUploadService;
    }

    public function index()
    {
        return ProductResource::collection(Product::all());
    }


    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $imagePath = $this->imageUploadService->uploadImage($request, 'image', 'images');

        $product = new Product();
        $product->name = $data['name'];
        $product->brand = $data['brand'];
        $product->description = $data['description'];
        $product->image_url = $imagePath;
        $product->price = $data['price'];
        $product->source = $data['source'];
        $product->save();

        return new ProductResource($product);
    }
}
