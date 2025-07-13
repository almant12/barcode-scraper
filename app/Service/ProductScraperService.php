<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ProductScraperService
{

   public function scrapeProduct($url): array
    {
        $response = Http::get($url);
        $html = $response->getBody();
        $crawler = new Crawler($html);

        return [
            'product_name' => $crawler->filter('h2[property="food:name"]')->count()
                ? $crawler->filter('h2[property="food:name"]')->text()
                : 'N/A',

            'brand' => $crawler->filter('#field_brands_value')->count()
                ? $crawler->filter('#field_brands_value')->text()
                : 'N/A',

            'categories' => $crawler->filter('#field_categories_value')->count()
                ? $crawler->filter('#field_categories_value')->text()
                : 'N/A',

            'labels' => $crawler->filter('#field_labels_value')->count()
                ? $crawler->filter('#field_labels_value')->text()
                : 'N/A',

            'countries_sold' => $crawler->filter('#field_countries_value')->count()
                ? $crawler->filter('#field_countries_value')->text()
                : 'N/A',

            'barcode' => $crawler->filter('#barcode')->count()
                ? $crawler->filter('#barcode')->text()
                : 'N/A',

            'image_url' => $crawler->filter('meta[property="og:image"]')->count()
                ? $crawler->filter('meta[property="og:image"]')->attr('content')
                : null,

            'source_url' => $url,
        ];
    }
}
