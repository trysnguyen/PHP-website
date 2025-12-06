<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra cả 2 cách lưu session
        if (!Session::get('admin_logged_in') && !(Session::get('user_type') === 'admin')) {
            return redirect()->route('login')->with('error', 'Please login as admin.');
        }
        
        return $next($request);
    }
}