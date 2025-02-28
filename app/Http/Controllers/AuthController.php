<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Cek apakah remember me dicentang
    
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
    
            // Cek apakah pengguna login sebagai guest@example.com
            if ($request->email === 'user@example.com') {
                return redirect('/inventory');
            } else {
                return redirect('/shopping-cart');
            }    
        }
    
        return back()->with('error', 'Email atau password salah.');
    }
    


public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Anda berhasil logout.');
}

}
