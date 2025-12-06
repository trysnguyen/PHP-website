<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class AdminAuth {
    public function handle(Request $r, Closure $n) {
        if (!Session::has('user_type') || Session::get('user_type') !== 'admin') {
            return redirect()->route('login')->withErrors(['auth'=>'Admin access only']);
        }
        return $n($r);
    }
}
