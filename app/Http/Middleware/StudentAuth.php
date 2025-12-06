<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra cả 2 cách lưu session
        if (!Session::get('student_logged_in') && !(Session::get('user_type') === 'student')) {
            return redirect()->route('login')->with('error', 'Please login as student.');
        }
        
        return $next($request);
    }
}