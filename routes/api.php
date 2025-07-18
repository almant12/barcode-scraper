
<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/scrape/{barcode}', [ProductController::class, 'scrapeProduct']);
    Route::get('/ai-scrape/{barcode}', [ProductController::class, 'aiScrapeProduct']);
});
