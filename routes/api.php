<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth')->prefix('/test')->group(function () {
Route::prefix('/api')->group(function () {
    require __DIR__.'/detail-routes/data-page.routes.php';
    require __DIR__.'/detail-routes/shopping-cart.routes.php';
});

