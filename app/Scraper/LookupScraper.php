<?php

namespace App\Scraper;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LookupScraper
{

    public function scrapeProduct($barcode)
    {
        $host = env('PUPPETEER_SCRAPER_URL');
        $response = Http::get("$host/scrape/lookup/$barcode");
        if ($response->status() === 404) {
            throw new NotFoundHttpException("Product with barcode {$barcode} not found.");
        }
        if (!$response->successful()) {
            throw new \Exception("Scraper service returned status {$response->status()}");
        }
        $data = $response->json();
        return $data;
    }
}
