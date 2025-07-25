<?php

namespace App\Service;

use App\Helpers\CrawlerHelper;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ProductScraper
{

    public function scrapeProduct($url): array
    {
        $response = Http::get($url);
        $html = $response->getBody();
        $crawler = new Crawler($html);

        $product_name_raw = $this->extractText($crawler, 'h2[property="food:name"]');
        $product_name = preg_split('/\s+This|\n/', $product_name_raw)[0];

        return [
            'product_name'   => $product_name,
            'brand'          => $this->extractText($crawler, '#field_brands_value'),
            'categories'     => $this->extractText($crawler, '#field_categories_value'),
            'labels'         => $this->extractText($crawler, '#field_labels_value'),
            'countries_sold' => $this->extractText($crawler, '#field_countries_value'),
            'barcode'        => $this->extractText($crawler, '#barcode'),
            'image_url'      => $this->extractAttr($crawler, 'img#og_image', 'src'),
            'nutrient_levels' => $this->extractListText($crawler, '#panel_nutrient_levels_content .accordion-navigation h4'),
            'nutrient_table' => $this->extractTable($crawler, '#panel_nutrition_facts_table_content table tbody tr'),
            'ingredients'    => $this->extractText($crawler, '#panel_ingredients_content .panel_text'),
            'ingredientsInfo' => $this->extractListText($crawler, '#panel_ingredients_list .panel_title h4'),

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

    private function extractListText(Crawler $crawler, string $selector): array
    {
        return $crawler->filter($selector)->each(function (Crawler $node) {
            return trim($node->text());
        });
    }

    private function extractTable(Crawler $crawler, string $selector): array
    {
        $rows = $crawler->filter($selector);

        return $rows->each(function (Crawler $row) {
            $columns = $row->filter('td');
            if ($columns->count() < 2) {
                return null;
            }

            return [
                'name' => trim($columns->eq(0)->text()),
                'value_per_100g' => trim($columns->eq(1)->text()),
                'comparison' => trim($columns->eq(2)->text())
            ];
        });
    }
}
