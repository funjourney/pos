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

        $remember = $request->has('remember');

        // Coba login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'user' => $user,
                'redirect' => $user->email === 'user@example.com' ? '/inventory' : '/shopping-cart'
            ]);
        }

        // Jika login gagal
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user(); // Dapatkan user sebelum logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
            'redirect' => $user && $user->email === 'user@example.com' ? '/login' : '/scan-barcode-table'
        ]);
    }
}
