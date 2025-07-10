
<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {});
Route::get('/product', [ProductController::class, 'index']);
