<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class GeminiAi
{
    public static function scrapeProduct(string $barcode)
    {
        $apiKey = env('GEMINI_API_KEY');

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => <<<PROMPT
You are an expert at finding real product details using a barcode.

Search specifically through these trusted websites:
- https://www.barcodelookup.com/
- https://world.openfoodfacts.org/

Only use information from one of those sites or their subpages. Do not guess or use made-up data.

Make sure the product exists and includes:
- A real, clear image of the physical product (not a logo or icon).
- Verified data from the actual page.

Return your response as JSON with these exact keys:
- name: Product name
- brand: Brand name
- description: Short product description
- image_url: Direct link to the real product image (must show the actual product)
- price: Typical price (if available)
- sourceUrl: URL to the page where the product was found

If no valid product is found on either site, respond only with:
{ "error": "Product not found" }

Barcode: $barcode
PROMPT
                        ],
                    ],
                ],
            ],
        ];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post($url, $payload);

        return self::cleanGeminiResponse($response['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from Gemini');
    }


    private static function cleanGeminiResponse(string $text): string
    {
        // Remove ```json or ``` and ending ```
        $text = preg_replace('/^```(?:json)?\s*/', '', $text); // remove starting ```
        $text = preg_replace('/\s*```$/', '', $text);          // remove ending ```

        return trim($text);
    }
}
