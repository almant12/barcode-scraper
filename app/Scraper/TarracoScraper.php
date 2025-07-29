<?php

namespace App\Scraper;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TarracoScraper
{
    public function scrapeProduct(string $barcode)
    {
        $host = env('PUPPETEER_SCRAPER_URL');

        $response = Http::get("$host/scrape/tarraco/$barcode");
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
