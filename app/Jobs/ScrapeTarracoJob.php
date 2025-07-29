<?php

namespace App\Jobs;

use App\Service\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScrapeTarracoJob implements ShouldQueue
{
    use Dispatchable, SerializesModels;
    public $timeout = 10;
    protected string $barcode;

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    public function handle(): void
    {

        $productService = app(ProductService::class);


        $productService->scrapeTarraco($this->barcode);
    }
}
