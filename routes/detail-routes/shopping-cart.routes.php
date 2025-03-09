<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->prefix('/shopping-cart')->group(function () {
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // /products
    Route::post('/', [ProductController::class, 'store']); // /products
    Route::get('/{id}', [ProductController::class, 'show']); // /products/{id}
    Route::put('/{id}', [ProductController::class, 'update']); // /products/{id}
    Route::delete('/{id}', [ProductController::class, 'destroy']); // /products/{id}
});