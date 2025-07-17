<?php

namespace App\Service;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Service\ImageUploadService;
use App\Service\ProductScraper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    protected $productScraper;
    protected $imageUploadService;

    public function __construct(
        ProductScraper $productScraper,
        ImageUploadService $imageUploadService
    ) {
        $this->productScraper = $productScraper;
        $this->imageUploadService = $imageUploadService;
    }

    public function getProducts(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::all());
    }

    public function createProduct(array $data, Request $request): ProductResource
    {
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

    public function scrapeAndStoreProduct(string $barcode)
    {
        $url = "https://world.openfoodfacts.org/product/{$barcode}";

        $productData = $this->productScraper->scrapeProduct($url);

        if (!isset($productData['barcode'])) {
            throw new NotFoundHttpException("Product with barcode {$barcode} not found.");
        }

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
