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
    protected $signature = 'barcode:process {barcode}';

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

        $productData = $this->productService->scrapeOpenFoodFacts($barcode);
        // Your processing logic here
        $this->info("Product processed successfully for barcode: {$barcode}");

        // Optionally print some product data
        $this->line("Product name: " . ($productData['product_name'] ?? 'N/A'));

        return 0;
    }
}
