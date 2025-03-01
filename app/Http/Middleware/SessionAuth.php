<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Gunakan Auth::check() untuk mengecek apakah user sudah login
        if (!Auth::check()) {
            dd(Auth::check(), Auth::user());
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }       

        return $next($request);
    }
}
