<?php

namespace App\Jobs;

use App\Service\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScrapeOpenFoodFactsJob implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    protected string $barcode;

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    public function handle(): void
    {

        $productService = app(ProductService::class);

            $productService->scrapeOpenFoodFacts($this->barcode);
        
    }
}
