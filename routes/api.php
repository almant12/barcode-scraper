
<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {});


Route::prefix('scrape')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/ai/{barcode}', [ProductController::class, 'aiScrapeProduct']);
    Route::get('/open-food/{barcode}', [ProductController::class, 'scrapeOpenFoodFacts']);
    Route::get('/tarraco/{barcode}', [ProductController::class, 'scrapeTarraco']);
    Route::get('/lookup/{barcode}', [ProductController::class, 'scrapeLookup']);
});


Route::get('scraped-products', function () {

    $results = DB::table('sources')->join('products', 'products.source_id', '=', 'sources.id')
        ->select(
            'sources.name',
            'sources.url',
            DB::raw('COUNT(products.id) as productsScrape')
        )->groupBy('sources.id')
        ->get();

    $totalProductScrape = $results->sum('productsScrape');

    return [
        'sources' => $results,
        'totalProductScrape' => $totalProductScrape
    ];
});
