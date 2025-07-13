
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
    Route::get('/scrape/{barcode}', [ProductController::class, 'scrapeProduct']);
});



Route::post('test-ai', function (Request $request) {

    $request->validate([
        'barcode' => 'required|string'
    ]);

    $barcode = $request->input('barcode');

    $text = GeminiAPI::callAPI($barcode);
    return response()->json($text);
});
