<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TarracoScraperTest extends TestCase
{
    public function test_example(): void
    {
        $host = env('PUPPETEER_SCRAPER_URL');

        $response = Http::post($host . '/scrape', [
            'barcode' => '5000174003451',
        ]);

        echo $response->body();
    }
}
