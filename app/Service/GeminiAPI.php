<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class GeminiAPI
{


    public static function callAPI($prompt)
    {
        $apiKey = env('GEMINI_API_KEY');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey, [
            'contents' => [
                ['parts' => [['text' => $prompt]]],
            ]
        ]);

        return $response->json()['candidates'][0]['content']['parts'][0]['text'];
    }
}
