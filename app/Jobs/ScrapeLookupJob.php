<?php

namespace App\Jobs;

use App\Service\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScrapeLookupJob implements ShouldQueue
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

        
            $productService->scrapeLookup($this->barcode);

    }
}
