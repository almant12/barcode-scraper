<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
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
        return $this->productService->getProducts();
    }


    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        return $this->productService->createProduct($data, $request);
    }


    public function scrapeOpenFoodFacts(string $barcode)
    {
        return $this->productService->scrapeOpenFoodFacts($barcode);
    }

    public function scrapeTarraco(string $barcode)
    {
       return $this->productService->scrapeTarraco($barcode);
    }

    public function aiScrapeProduct(string $barcode)
    {
        return $this->productService->storeScrapeProductAi($barcode);
    }
}
