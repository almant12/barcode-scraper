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
            'product_name'   => $this->extractText($crawler, 'h2[property="food:name"]'),
            'brand'          => $this->extractText($crawler, '#field_brands_value'),
            'categories'     => $this->extractText($crawler, '#field_categories_value'),
            'labels'         => $this->extractText($crawler, '#field_labels_value'),
            'countries_sold' => $this->extractText($crawler, '#field_countries_value'),
            'barcode'        => $this->extractText($crawler, '#barcode'),
            'image_url'      => $this->extractAttr($crawler, 'meta[property="og:image"]', 'content'),
            'source_url'     => $url,
        ];
    }


    private function extractText(Crawler $crawler, string $selector): ?string
    {

        return $crawler->filter($selector)->count() ?
            $crawler->filter($selector)->text() : null;
    }

    private function extractAttr(Crawler $crawler, string $selector, string $attr): ?string
    {
        return $crawler->filter($selector)->count() ?
            $crawler->filter($selector)->attr($attr) : null;
    }
}
