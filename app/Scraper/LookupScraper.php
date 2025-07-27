<?php

namespace App\Scraper;

use Illuminate\Support\Facades\Http;

class LookupScraper
{

    public function scrapeProduct($barcode)
    {
        $host = env('PUPPETEER_SCRAPER_URL');
        $response = Http::get("$host/scrape/lookup/$barcode");

        $data = $response->json();
        return $data;
    }
}
