<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->prefix('/data-page')->group(function () {
Route::prefix('/data-page')->group(function () {
    Route::get('/', [DataController::class, 'getData']);  // /data-page
    Route::post('/', [DataController::class, 'postData']); // /data-page
    Route::put('/{id}', [DataController::class, 'putData']); // /data-page/{id}
    Route::delete('/{id}', [DataController::class, 'deleteData']); // /data-page/{id}
});