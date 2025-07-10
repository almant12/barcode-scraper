
<?php

use App\Http\Controllers\ProductController;
use App\Service\GeminiAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

Route::get('/user', function () {});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
});


Route::post('test-ai', function (Request $request) {

    $validated = $request->validate([
        'prompt' => 'required'
    ]);

    $prompt = $validated['prompt'];

    $text = GeminiAPI::callAPI($prompt);
    return response()->json($text);
});
