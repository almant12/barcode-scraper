<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Service\ImageUploadService;
use App\Service\ProductScraperService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private ImageUploadService $imageUploadService;
    private ProductScraperService $productScraperService;

    public function __construct(
        ImageUploadService $imageUploadService,
        ProductScraperService $productScraperService
    ) {
        $this->imageUploadService = $imageUploadService;
        $this->productScraperService = $productScraperService;
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
        $product->description = $data['description'] ?? null;
        $product->image_url = $imagePath;
        $product->price = $data['price'];
        $product->source = $data['source'] ?? null;
        $product->save();

        return new ProductResource($product);
    }


    public function scrapeProduct(Request $request, string $barcode)
    {
        $url = "https://world.openfoodfacts.org/product/{$barcode}";

        $productData = $this->productScraperService->scrapeProduct($url);

        return response()->json($productData);
    }
}
