<?php

namespace App\Helpers;

use Symfony\Component\DomCrawler\Crawler;

class CrawlerHelper
{
    public static function extractText(Crawler $crawler, string $selector): ?string
    {
        return $crawler->filter($selector)->count()
            ? $crawler->filter($selector)->text()
            : null;
    }

    public static function extractAttr(Crawler $crawler, string $selector, string $attr): ?string
    {
        return $crawler->filter($selector)->count()
            ? $crawler->filter($selector)->attr($attr)
            : null;
    }

    public static function extractListText(Crawler $crawler, string $selector): array
    {
        return $crawler->filter($selector)->each(function (Crawler $node) {
            return trim($node->text());
        });
    }

    public static function extractTable(Crawler $crawler, string $selector): array
    {
        return $crawler->filter($selector)->each(function (Crawler $row) {
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
