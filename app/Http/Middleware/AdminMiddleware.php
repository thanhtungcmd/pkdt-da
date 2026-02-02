<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
        }

        if (Auth::user()->VaiTro != 1) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}