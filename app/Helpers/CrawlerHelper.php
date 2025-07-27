<?php

namespace App\Helpers;

use Symfony\Component\DomCrawler\Crawler;

function extractText(Crawler $crawler, string $selector): ?string
{
    return $crawler->filter($selector)->count()
        ? $crawler->filter($selector)->text()
        : null;
}

function extractAttr(Crawler $crawler, string $selector, string $attr): ?string
{
    return $crawler->filter($selector)->count()
        ? $crawler->filter($selector)->attr($attr)
        : null;
}

function extractAttrs(Crawler $crawler, string $selector, string $attr): array
{
    if ($crawler->filter($selector)->count() === 0) {
        return [];
    }

    return $crawler->filter($selector)->each(function (Crawler $node) use ($attr) {
        return $node->attr($attr);
    });
}

function extractListText(Crawler $crawler, string $selector): array
{
    if ($crawler->filter($selector)->count() === 0) {
        return [];
    }

    return $crawler->filter($selector)->each(function (Crawler $node) {
        return trim($node->text());
    });
}

function extractTable(Crawler $crawler, string $selector): array
{
    return array_values(array_filter($crawler->filter($selector)->each(function (Crawler $row) {
        $columns = $row->filter('td');

        if ($columns->count() < 3) {  // <-- require at least 3 columns
            return null;
        }

        return [
            'name' => trim($columns->eq(0)->text()),
            'value_per_100g' => trim($columns->eq(1)->text()),
            'comparison' => trim($columns->eq(2)->text())
        ];
    })));
}



function extractBrand($title)
{
    $words = explode(' ', $title);
    return implode(' ', array_slice($words, 0, 2));
}
