<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->prefix('/process-payment')->group(function () {
Route::prefix('/process-payment')->group(function () {
    Route::get('/', [DataController::class, 'getData']);  // /process-payment
    Route::post('/', [DataController::class, 'postData']); // /process-payment
    Route::put('/{id}', [DataController::class, 'putData']); // /process-payment/{id}
    Route::delete('/{id}', [DataController::class, 'deleteData']); // /process-payment/{id}
});