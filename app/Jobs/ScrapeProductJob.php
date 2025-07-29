<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class ScrapeProductJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected string $barcode;

    public function __construct(string $barcode)
    {
        $this->barcode = $barcode;
    }

    public function handle(): void
    {

        dispatch(new ScrapeOpenFoodFactsJob($this->barcode));
        dispatch(new ScrapeLookupJob($this->barcode));
        dispatch(new ScrapeTarracoJob($this->barcode));
    }
}
