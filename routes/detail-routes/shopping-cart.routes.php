<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->prefix('/shopping-cart')->group(function () {
Route::prefix('/shopping-cart')->group(function () {
    Route::get('/', [DataController::class, 'getData']);  // /shopping-cart
    Route::post('/', [DataController::class, 'postData']); // /shopping-cart
    Route::put('/{id}', [DataController::class, 'putData']); // /shopping-cart/{id}
    Route::delete('/{id}', [DataController::class, 'deleteData']); // /shopping-cart/{id}
});