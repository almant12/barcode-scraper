<?php

namespace App\Service;

use App\Models\Product;
use App\Models\Source;
use App\Scraper\LookupScraper;
use App\Scraper\OpenFoodFactsScraper;
use App\Scraper\TarracoScraper;
use App\Service\ImageUploadService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function App\Helpers\extractBrand;

class ProductService
{
    protected $productScraper;
    protected $imageUploadService;
    protected $geminiAi;
    protected $openFoodFactsScraper;
    protected $tarracoScraper;
    protected $lookupScraper;

    public function __construct(
        LookupScraper $lookupScraper,
        TarracoScraper $tarracoScraper,
        OpenFoodFactsScraper $openFoodFactsScraper,
        ImageUploadService $imageUploadService,
        GeminiAi $geminiAi,
    ) {
        $this->lookupScraper = $lookupScraper;
        $this->tarracoScraper = $tarracoScraper;
        $this->openFoodFactsScraper = $openFoodFactsScraper;
        $this->imageUploadService = $imageUploadService;
        $this->geminiAi = $geminiAi;
    }

    public function getProducts()
    {
        return Product::all();
    }

    public function createProduct(array $data, Request $request)
    {
        $imagePath = $this->imageUploadService->uploadImage($request, 'image', 'images');

        $product = new Product();
        $product->name = $data['name'];
        $product->brand = $data['brand'];
        $product->description = $data['description'] ?? null;
        $product->image_url = $imagePath;
        $product->price = $data['price'];
        $product->source_url = $data['source'] ?? null;
        $product->save();

        return $product;
    }

    public function scrapeOpenFoodFacts(string $barcode)
    {
        $productData = $this->openFoodFactsScraper->scrapeProduct($barcode);
        $imagePath = $this->imageUploadService->uploadMultipleImagesFromUrls($productData['image_urls'], 'open-food-facts');
        $sourceId = Source::where('name', 'openfoodfacts')->value('id');

        Product::updateOrCreate(
            [
                'source_url' => $productData['source_url'],
                'barcode'    => $barcode,
            ],
            [
                'title'     => $productData['title'] ?? null,
                'brand'            => $productData['brand'] ?? null,
                'categories'       => $productData['categories'] ?? null,
                'labels'           => $productData['labels'] ?? null,
                'countries_sold'   => $productData['countries_sold'] ?? null,
                'image_urls'        => $imagePath ?? null,
                'nutrient_levels'  => $productData['nutrient_levels'] ?? null,
                'nutrient_table'   => $productData['nutrient_table'] ?? null,
                'ingredients'      => $productData['ingredients'] ?? null,
                'ingredients_info' => $productData['ingredientsInfo'] ?? null,
                'source_id' => $sourceId
            ]
        );
    }

    public function scrapeTarraco(string $barcode)
    {
        $productData = $this->tarracoScraper->scrapeProduct($barcode);
        $imagePaths = $this->imageUploadService->uploadMultipleImagesFromUrls($productData['images'], 'tarraco');
        $sourceId = Source::where('name', 'tarraco-import-export')->value('id');
        Product::updateOrCreate(
            [
                'source_url' => $productData['productLink'],
                'barcode' => $barcode
            ],
            [
                'title' => $productData['title'],
                'brand' => extractBrand($productData['title']),
                'reference' => $productData['reference'],
                'image_urls' => $imagePaths ?? null,
                'data_sheet' => $productData['dataSheet'],
                'source_id' => $sourceId

            ]
        );
    }

    public function scrapeLookup(string $barcode)
    {
        $productData = $this->lookupScraper->scrapeProduct($barcode);
        $imagePaths = $this->imageUploadService->uploadMultipleImagesFromUrls($productData['images'], 'lookup');
        $sourceId = Source::where('name', 'barcode-lookup')->value('id');
        Product::updateOrCreate(
            [
                'source_url' => $productData['url'],
                'barcode' => $barcode
            ],
            [
                'title' => $productData['title'],
                'description' => $productData['description'],
                'image_urls' => $imagePaths ?? null,
                'source_id' => $sourceId
            ]
        );
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
