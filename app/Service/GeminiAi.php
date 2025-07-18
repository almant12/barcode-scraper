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
                        ['text' => "You are an expert at finding product details on the internet using a barcode. Always return details only if the product actually exists and include only REAL data from the first result in Google. Return a JSON object with these keys: name, brand, description, image_url, price, sourceUrl. If you find nothing valid, respond with: { \"error\": \"Product not found\" }"],
                        ['text' => "Find details for this barcode: $barcode"],
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


