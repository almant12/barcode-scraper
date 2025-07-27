<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeProductJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ScrapeBarcodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:products {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read barcodes from a file and dispatch scraping jobs';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (File::exists($filePath)) {
            $this->error('File not found: {$filePath}');
            return 1;
        }

        $barcodes = File::lines($filePath)
            ->map(fn($line) => trim($line))
            ->filter(fn($line) => !empty($line))
            ->all();

        if (empty($barcodes)) {
            $this->info('No barcodes found in file.');
            return 0;
        }

        foreach ($barcodes as $barcode) {
            ScrapeProductJob::dispatch($barcode);
            $this->info("Dispatched scraping job for barcode: {$barcode}");
        }

        $this->info('All scraping jobs dispatched.');
        return 0;
    }
}
