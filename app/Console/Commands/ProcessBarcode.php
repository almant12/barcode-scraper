<?php

namespace App\Console\Commands;

use App\Service\ProductService;
use Illuminate\Console\Command;

class ProcessBarcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:product {barcode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a product by barcode';

    protected ProductService $productService;

    // Inject your service via the constructor (or resolve inside handle)
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $barcode = $this->argument('barcode');

        if (!is_numeric($barcode)) {
            $this->error('Barcode must be a number');
            return 1;
        }

        $this->tryScrape(fn() => $this->productService->scrapeOpenFoodFacts($barcode), 'OpenFoodFacts');
        $this->tryScrape(fn() => $this->productService->scrapeLookup($barcode), 'Lookup');
        $this->tryScrape(fn() => $this->productService->scrapeTarraco($barcode), 'Tarraco');

        $this->info("Product processing finished for barcode: {$barcode}");
        return 0;
    }

    protected function tryScrape(callable $scrapeFunc, string $source)
    {
        try {
            $scrapeFunc();
            $this->info("{$source} scraped successfully.");
        } catch (\Throwable $e) {
            $this->warn("{$source} failed: " . $e->getMessage());
        }
    }
}
