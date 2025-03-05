<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DataController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/data-page', function () {
    return Inertia::render('DataPage');
// })->middleware(['auth', 'verified'])->name('data-page');
})->name('data-page');

// Route::middleware('auth')->prefix('/test')->group(function () {
Route::prefix('/test')->group(function () {
    Route::get('/', [DataController::class, 'getData']);  // /test
    Route::post('/', [DataController::class, 'postData']); // /test
    Route::put('/{id}', [DataController::class, 'putData']); // /test/{id}
    Route::delete('/{id}', [DataController::class, 'deleteData']); // /test/{id}
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
