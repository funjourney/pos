<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Tampilkan halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Tampilkan halaman login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Proses login dengan email
Route::post('/login', function (Request $request) {
    // Validasi input
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Cek kredensial (hardcoded sesuai permintaan)
    if ($credentials['email'] === 'user@example.com' && $credentials['password'] === 'password') {
        session(['user' => 'authenticated']); // Simpan sesi login
        return redirect('/inventory');
    } else if ($credentials['email'] === 'guest@example.com' && $credentials['password'] === 'password') {
        session(['user' => 'authenticated']); // Simpan sesi login
        return redirect('/shopping-cart');
    }

    return back()->with('error', 'Email atau password salah.');
});

// Tampilkan halaman shopping-cart (dengan middleware)
Route::get('/shopping-cart', function () {
    return view('shopping-cart');
})->name('shopping-cart')->middleware('auth.session');

// Tampilkan halaman payment (dengan middleware)
Route::get('/payment', function () {
    return view('payment');
})->name('payment')->middleware('auth.session');

// Proses process-payment dengan email
Route::post('/process-payment', function (Request $request) {
    // // Validasi input
    // $credentials = $request->validate([
    //     'email' => 'required|email',
    //     'password' => 'required|string',
    // ]);

    // // Cek kredensial (hardcoded sesuai permintaan)
    // if ($credentials['email'] === 'user@example.com' && $credentials['password'] === 'password') {
    //     session(['user' => 'authenticated']); // Simpan sesi process-payment
    //     return redirect('/shopping-cart');
    // }

    // return back()->with('error', 'Email atau password salah.');
    return redirect('/process');
});

// Tampilkan halaman process (dengan middleware)
Route::get('/process', function () {
    return view('process');
})->name('process')->middleware('auth.session');


// Tampilkan halaman scan-barcode-table (dengan middleware)
Route::get('/scan-barcode-table', function () {
    return view('scan-barcode-table');
})->name('scan-barcode-table');

// Tampilkan halaman inventory (dengan middleware)
Route::get('/inventory', function () {
    return view('inventory');
})->name('inventory')->middleware('auth.session');

// Logout
Route::post('/logout', function () {
    session()->forget('user'); // Hapus sesi login
    return redirect('/login')->with('success', 'Anda berhasil logout.');
});


