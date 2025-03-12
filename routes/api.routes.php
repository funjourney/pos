<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(function () {    
    Route::apiResource('data-page', DataController::class);
    Route::get('/categories/products', [CategorieController::class, 'indexWithListProduct']); // posisi harus diatas route utama
    Route::apiResource('categories', CategorieController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('checkouts', CheckoutController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('process-payments', DataController::class);
});



