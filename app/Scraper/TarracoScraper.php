<?php

namespace App\Scraper;

use Illuminate\Support\Facades\Http;

class TarracoScraper
{
    public function scrapeProduct(string $barcode)
    {
        $host = env('PUPPETEER_SCRAPER_URL');

        $response = Http::get("$host/scrape/{$barcode}");

        $data = $response->json();

        return $data;
    }
}
