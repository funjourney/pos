<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// Tampilkan halaman login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Proses login (pastikan controller sudah dibuat)
Route::post('/login', function () {
    // Contoh login sederhana tanpa database (hanya untuk testing)
    request()->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    return redirect('/')->with('success', 'Login berhasil!');
});

// Tampilkan halaman shopping-chart
Route::get('/shopping-chart', function () {
    return view('shopping-chart');
})->name('shopping-chart');
