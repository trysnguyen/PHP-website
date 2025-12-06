<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->username;
        $password = $request->password;

        // Check admin
        if ($username === 'admin' && $password === 'admin') {
            Session::put('user_type', 'admin');
            Session::put('username', 'admin');
            return redirect()->route('admin.dashboard');
        }

        // Check student
        $student = Student::where('username', $username)->first();

        if ($student && $student->password === $password) {
            Session::put('user_type', 'student');
            Session::put('student_id', $student->StudentID);
            Session::put('student_name', $student->Studentname);
            Session::put('username', $student->username);
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['login' => 'Invalid username or password']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:students',
            'password' => 'required|confirmed',
            'Studentname' => 'required',
            'StudentID' => 'required|unique:students',
            'Class' => 'required'
        ]);

        Student::create([
            'username' => $request->username,
            'password' => $request->password,
            'Studentname' => $request->Studentname,
            'StudentID' => $request->StudentID,
            'Class' => $request->Class
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}