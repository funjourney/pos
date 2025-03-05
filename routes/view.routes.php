<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])
->name('dashboard');

//
Route::get('/data-page', function () {
    return Inertia::render('DataPage');
})//->middleware(['auth', 'verified'])
->name('data-page');

//
Route::get('/scan-barcode-table', function () {
    return Inertia::render('ScanBarcodeTable');
})
->name('scan-barcode-table');

//
Route::get('/shopping-cart', function () {
    return Inertia::render('ShoppingCart');
})//->middleware(['auth', 'verified'])
->name('shopping-cart');
