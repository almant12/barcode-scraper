<?php

namespace App\Scraper;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function App\Helpers\extractAttrs;
use function App\Helpers\extractListText;
use function App\Helpers\extractTable;
use function App\Helpers\extractText;

class OpenFoodFactsScraper
{

    public function scrapeProduct($barcode): array
    {

        $url = "https://world.openfoodfacts.org/product/$barcode";
        $response = Http::get($url);
        if ($response->status() === 404) {
            throw new NotFoundHttpException("Product with barcode {$barcode} not found.");
        }
        $html = $response->getBody();
        $crawler = new Crawler($html);

        $product_name_raw = extractText($crawler, 'h2[property="food:name"]');
        $product_name = preg_split('/\s+This|\n/', $product_name_raw)[0];

        return [
            'title'   => $product_name,
            'brand'          => extractText($crawler, '#field_brands_value'),
            'categories'     => extractText($crawler, '#field_categories_value'),
            'labels'         => extractText($crawler, '#field_labels_value'),
            'countries_sold' => extractText($crawler, '#field_countries_value'),
            'barcode'        => extractText($crawler, '#barcode'),
            'image_urls'      => extractAttrs($crawler, 'img#og_image', 'src'),
            'nutrient_levels' => extractListText($crawler, '#panel_nutrient_levels_content .accordion-navigation h4'),
            'nutrient_table' => extractTable($crawler, '#panel_nutrition_facts_table_content table tbody tr'),
            'ingredients'    => extractText($crawler, '#panel_ingredients_content .panel_text'),
            'ingredientsInfo' => extractListText($crawler, '#panel_ingredients_list .panel_title h4'),

            'source_url'     => $url,
        ];
    }
}
