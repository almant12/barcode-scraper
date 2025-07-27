<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Service\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return ProductResource::collection($this->productService->getProducts());
    }


    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        return new ProductResource($this->productService->createProduct($data, $request));
    }


    public function scrapeOpenFoodFacts(string $barcode)
    {
        return new ProductResource($this->productService->scrapeOpenFoodFacts($barcode));
    }

    public function scrapeTarraco(string $barcode)
    {
        return new ProductResource($this->productService->scrapeTarraco($barcode));
    }

    public function scrapeLookup(string $barcode)
    {
        return new ProductResource($this->productService->scrapeLookup($barcode));
    }

    public function aiScrapeProduct(string $barcode)
    {
        return new ProductResource($this->productService->storeScrapeProductAi($barcode));
    }
}
