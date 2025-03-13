<?php

use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;

//
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// //
// Route::get('/data-page', function () {
//     return Inertia::render('DataPage');
// })//->middleware(['auth', 'verified'])
// ->name('data-page');

// // //
// // Route::get('/scan-barcode-table', function () {
// //     return Inertia::render('ScanBarcodeTable');
// // })
// // ->name('scan-barcode-table');

// //
// Route::get('/shopping-cart', function () {
//     return Inertia::render('ShoppingCart');
// })//->middleware(['auth', 'verified'])
// ->name('shopping-cart');

// //
// Route::get('/payment', function () {
//     return Inertia::render('Payment');
// })//->middleware(['auth', 'verified'])
// ->name('payment');

// //
// Route::get('/process', function () {
//     return Inertia::render('Process');
// })//->middleware(['auth', 'verified'])
// ->name('process');

// //
// Route::get('/inventory', function () {
//     return Inertia::render('Inventory');
// })//->middleware(['auth', 'verified'])
// ->name('inventory');