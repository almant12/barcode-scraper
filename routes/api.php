
<?php

use App\Http\Controllers\ProductController;
use App\Service\TarracoScraper;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {});


Route::prefix('scrape')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/open-food/{barcode}', [ProductController::class, 'scrapeOpenFoodFacts']);
    Route::get('/ai/{barcode}', [ProductController::class, 'aiScrapeProduct']);
    Route::get('/tarraco/{barcode}', [ProductController::class, 'scrapeTarraco']);
    Route::get('/lookup/{barcode}', [ProductController::class, 'scrapeLookup']);
});
