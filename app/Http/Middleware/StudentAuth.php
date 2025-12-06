<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class StudentAuth {
    public function handle(Request $r, Closure $n) {
        if (!Session::has('user_type') || Session::get('user_type') !== 'student') {
            return redirect()->route('login')->withErrors(['auth'=>'Student access only']);
        }
        return $n($r);
    }
}
