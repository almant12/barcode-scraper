<?php

namespace App\Service;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Scraper\OpenFoodFactsScraper;
use App\Scraper\TarracoScraper;
use App\Service\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    protected $productScraper;
    protected $imageUploadService;
    protected $geminiAi;
    protected $openFoodFactsScraper;
    protected $tarracoScraper;

    public function __construct(
        TarracoScraper $tarracoScraper,
        OpenFoodFactsScraper $openFoodFactsScraper,
        ImageUploadService $imageUploadService,
        GeminiAi $geminiAi,
    ) {
        $this->tarracoScraper = $tarracoScraper;
        $this->openFoodFactsScraper = $openFoodFactsScraper;
        $this->imageUploadService = $imageUploadService;
        $this->geminiAi = $geminiAi;
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

    public function scrapeOpenFoodFacts(string $barcode)
    {
        $url = "https://world.openfoodfacts.org/product/{$barcode}";

        $productData = $this->openFoodFactsScraper->scrapeProduct($url);

        if (!isset($productData['barcode'])) {
            throw new NotFoundHttpException("Product with barcode {$barcode} not found.");
        }

        $imagePath = $this->imageUploadService->uploadMultipleImagesFromUrls($productData['image_urls'], 'images');

        $product = Product::updateOrCreate(
            ['source_url' => $url],
            [
                'title'     => $productData['title'] ?? null,
                'brand'            => $productData['brand'] ?? null,
                'categories'       => $productData['categories'] ?? null,
                'labels'           => $productData['labels'] ?? null,
                'countries_sold'   => $productData['countries_sold'] ?? null,
                'barcode'          => $barcode,
                'image_urls'        => $imagePath ?? null,
                'nutrient_levels'  => $productData['nutrient_levels'] ?? null,
                'nutrient_table'   => $productData['nutrient_table'] ?? null,
                'ingredients'      => $productData['ingredients'] ?? null,
                'ingredients_info' => $productData['ingredientsInfo'] ?? null,
                'source_url'       => $url,
            ]
        );

        return new ProductResource($product);
    }

    public function scrapeTarraco(string $barcode)
    {
        $productData = $this->tarracoScraper->scrapeProduct($barcode);
      
        return new ProductResource($productData);
    }

    public function storeScrapeProductAi(string $barcode)
    {
        $response = $this->geminiAi->scrapeProduct($barcode);
        $productData = json_decode($response);

        $product = Product::create([
            'product' => $productData->name,
            'brand' => $productData->brand ?? null,
            'description' => $productData->description ?? null,
            'image_url' => $productData->iamge_url ?? null,
            'price' => $productData->price ?? null,
            'source_url' => $productData->sourceUrl ?? null,
        ]);

        return $product;
    }
}
