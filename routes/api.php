
<?php

use App\Http\Controllers\ProductController;
use App\Service\TarracoScraper;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/open-food-scrape/{barcode}', [ProductController::class, 'scrapeOpenFoodFacts']);
    Route::get('/ai-scrape/{barcode}', [ProductController::class, 'aiScrapeProduct']);
    Route::get('/tarraco-scrape/{barcode}', [ProductController::class, 'scrapeTarraco']);
});
