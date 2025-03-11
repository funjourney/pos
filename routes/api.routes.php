<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(function () {    
    Route::apiResource('data-page', DataController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategorieController::class);
    Route::apiResource('process-payments', DataController::class);
});



