<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Service\ImageUploadService;
use App\Service\ProductScraperService;
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


    public function scrapeProduct(string $barcode)
    {
        $url = "https://world.openfoodfacts.org/product/{$barcode}";

        $productData = $this->productScraperService->scrapeProduct($url);

        $imagePath = $this->imageUploadService->uploadImageFromUrl($productData['image_url'], 'images');

        $product = Product::updateOrCreate(
            ['barcode' => $barcode],
            [
                'product_name'     => $productData['product_name'] ?? null,
                'brand'            => $productData['brand'] ?? null,
                'categories'       => $productData['categories'] ?? null,
                'labels'           => $productData['labels'] ?? null,
                'countries_sold'   => $productData['countries_sold'] ?? null,
                'barcode'          => $barcode,
                'image_url'        => $imagePath ?? null,
                'nutrient_levels'  => $productData['nutrient_levels'] ?? null,
                'nutrient_table'   => $productData['nutrient_table'] ?? null,
                'ingredients'      => $productData['ingredients'] ?? null,
                'ingredients_info' => $productData['ingredientsInfo'] ?? null,
                'source_url'       => $url,
            ]
        );
        return new ProductResource($product);
    }
}
