<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class TarracoScraper
{
    function scrapeProduct(string $barcode)
    {
        $host = env('PUPPETEER_SCRAPER_URL');

        $response = Http::post($host . '/scrape', [
            'barcode' => $barcode,
        ]);

        $data = $response->json();

        return $data;
    }
}
