<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string'
    ]);

    $username = $request->username;
    $password = $request->password;

    Log::info('Login attempt for: ' . $username);

    // ========== 1. CHECK ADMIN ==========
    if ($username === 'admin' && $password === 'admin') {
        // ĐĂNG KÝ SESSION mà không cần regenerate trước
        session([
            'user_type' => 'admin',
            'username' => 'admin',
            'admin_logged_in' => true,
            'logged_in' => true
        ]);
        
        Log::info('Admin login successful. Session ID: ' . session()->getId());
        return redirect()->route('admin.dashboard');
    }

    // ========== 2. CHECK STUDENT ==========
    $student = Student::where('username', $username)->first();

    if ($student && Hash::check($password, $student->password)) {
        // ĐĂNG KÝ SESSION đơn giản
        session([
            'user_type' => 'student',
            'student_logged_in' => true,
            'logged_in' => true,
            'username' => $student->username,
            'student_name' => $student->Studentname,
            'student_id' => $student->StudentID,
            'student_db_id' => $student->id
        ]);
        
        Log::info('Student login successful: ' . $student->username);
        return redirect('/student/dashboard'); // Dùng URL trực tiếp
    }

    // ========== 3. LOGIN FAILED ==========
    Log::warning('Login failed for: ' . $username);
    
    // QUAN TRỌNG: Dùng withErrors và không redirect
    return back()
        ->withErrors(['login' => 'Invalid username or password'])
        ->withInput($request->only('username'));
}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:students',
            'password' => 'required|confirmed|min:6',
            'Studentname' => 'required',
            'StudentID' => 'required|unique:students',
            'Class' => 'required'
        ]);

        $student = Student::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'Studentname' => $request->Studentname,
            'StudentID' => $request->StudentID,
            'Class' => $request->Class
        ]);

        Log::info('New student registered', [
            'id' => $student->id,
            'username' => $student->username,
            'student_id' => $student->StudentID
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please login with your new account.');
    }

    public function logout()
    {
        $userType = Session::get('user_type');
        $username = Session::get('username');
        
        Log::info('User logged out', [
            'user_type' => $userType,
            'username' => $username
        ]);
        
        // **SỬA: Thứ tự đúng cho logout**
        Session::flush();      // Xóa tất cả session data
        Session::regenerate(); // Tạo session ID mới
        Session::save();       // Đảm bảo lưu session
        
        return redirect('/login')
            ->with('success', 'You have been logged out successfully.');
    }
    
    // **SỬA: DEBUG SESSION**
    public function debugSession()
    {
        echo "<h1>Session Debug</h1>";
        
        echo "<h3>Current Session ID:</h3>";
        echo Session::getId();
        
        echo "<h3>All Session Data:</h3>";
        echo "<pre>";
        print_r(Session::all());
        echo "</pre>";
        
        echo "<h3>CSRF Token:</h3>";
        echo csrf_token();
        
        echo "<br><br><a href='/login'>Back to Login</a>";
    }
}