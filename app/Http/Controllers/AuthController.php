<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember'); // Cek apakah "Remember Me" dicentang

        // Coba login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect berdasarkan email
            if ($user->email === 'user@example.com') {
                return redirect()->intended('/inventory');
            } else {
                return redirect()->intended('/shopping-cart');
            }
        }

        // Jika login gagal
        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logout()
    {
        $user = Auth::user(); // Dapatkan user sebelum logout
        Auth::logout();
    
        if ($user && $user->email === 'user@example.com') {
            return redirect('/login');
        } else {
            return redirect('/scan-barcode-table');
        }
    }
    
}
