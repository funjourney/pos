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
        return redirect('/shopping-chart');
    }

    return back()->with('error', 'Email atau password salah.');
});

// Tampilkan halaman shopping-chart (dengan middleware)
Route::get('/shopping-chart', function () {
    return view('shopping-chart');
})->name('shopping-chart')->middleware('auth.session');

// Logout
Route::post('/logout', function () {
    session()->forget('user'); // Hapus sesi login
    return redirect('/login')->with('success', 'Anda berhasil logout.');
});
