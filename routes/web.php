<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

// Tampilkan halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Proses login dengan email
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login')->withoutMiddleware('auth');
Route::post('/logout', [AuthController::class, 'logout']);

// Tampilkan halaman shopping-cart (dengan middleware)
Route::get('/shopping-cart', function () {
    return view('shopping-cart');
})->name('shopping-cart');//->middleware('auth');

// Tampilkan halaman payment (dengan middleware)
Route::get('/payment', function () {
    return view('payment');
})->name('payment');//->middleware('auth');

// Proses process-payment dengan email
Route::post('/process-payment', function (Request $request) {
    return redirect('/process');
});

// Tampilkan halaman process (dengan middleware)
Route::get('/process', function () {
    return view('process');
})->name('process');//->middleware('auth');


// Tampilkan halaman scan-barcode-table (dengan middleware)
Route::get('/scan-barcode-table', function () {
    return view('scan-barcode-table');
})->name('scan-barcode-table');

// Tampilkan halaman inventory (dengan middleware)
Route::get('/inventory', function () {
    return view('inventory');
})->name('inventory');//->middleware('auth');



