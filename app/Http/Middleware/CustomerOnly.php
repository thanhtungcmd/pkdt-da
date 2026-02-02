<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu là Admin (VaiTro = 1) thì chặn
        if (Auth::check() && Auth::user()->VaiTro == 1) {
            return redirect()->route('home')->with('error', 'Chức năng này chỉ dành cho khách hàng!');
        }

        return $next($request);
    }
}