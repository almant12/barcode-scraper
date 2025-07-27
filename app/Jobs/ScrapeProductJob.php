<?php

namespace App\Jobs;

use App\Service\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected string $barcode;

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    public function handle(): void
    {
        $productService = app(ProductService::class);

        $productService->scrapeTarraco($this->barcode);
        $productService->scrapeLookup($this->barcode);
        $productService->scrapeOpenFoodFacts($this->barcode);
    }
}
