<?php

namespace App\Jobs;

use App\Service\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScrapeProductJob implements ShouldQueue
{
    use Queueable;

    protected string $barcode;

    /**
     * Create a new job instance.
     */
    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scrapeService = new ProductService()

        
    }
}
