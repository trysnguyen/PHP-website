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

        Log::info('=== LOGIN ATTEMPT ===', ['username' => $username]);

        // ========== 1. CHECK ADMIN ==========
        if ($username === 'admin' && $password === 'admin') {
            Log::info('Admin login successful');
            
            // QUAN TRỌNG: Regenerate session ID để bảo mật
            Session::regenerate();
            
            Session::put('user_type', 'admin');
            Session::put('username', 'admin');
            Session::put('admin_logged_in', true);
            Session::put('logged_in', true);
            
            Log::info('Session set for admin', [
                'user_type' => Session::get('user_type'),
                'admin_logged_in' => Session::get('admin_logged_in')
            ]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
        }

        // ========== 2. CHECK STUDENT ==========
        $student = Student::where('username', $username)->first();

        if ($student) {
            Log::info('Student found', [
                'id' => $student->id,
                'username' => $student->username,
                'name' => $student->Studentname
            ]);
            
            // QUAN TRỌNG: Kiểm tra password với Hash::check
            if (Hash::check($password, $student->password)) {
                Log::info('Password correct!');
                
                // QUAN TRỌNG: Regenerate session ID
                Session::regenerate();
                
                // Set session đầy đủ
                Session::put('user_type', 'student');
                Session::put('student_logged_in', true);
                Session::put('logged_in', true);
                Session::put('username', $student->username);
                Session::put('student_name', $student->Studentname);
                Session::put('student_id', $student->StudentID);
                Session::put('student_db_id', $student->id);
                Session::put('student_class', $student->Class);
                
                Log::info('Student session SET complete', [
                    'user_type' => Session::get('user_type'),
                    'student_logged_in' => Session::get('student_logged_in'),
                    'username' => Session::get('username'),
                    'student_name' => Session::get('student_name')
                ]);
                
                // Debug: Kiểm tra route có tồn tại không
                try {
                    $redirectUrl = route('student.dashboard');
                    Log::info("Redirecting to: {$redirectUrl}");
                    return redirect($redirectUrl)->with('success', 'Welcome ' . $student->Studentname . '!');
                } catch (\Exception $e) {
                    Log::error('Route not found: ' . $e->getMessage());
                    return redirect('/student/dashboard')->with('success', 'Welcome ' . $student->Studentname . '!');
                }
                
            } else {
                Log::warning('Password incorrect for student', [
                    'username' => $username,
                    'db_password_hash' => substr($student->password, 0, 20) . '...'
                ]);
            }
        } else {
            Log::warning('User not found in database', ['username' => $username]);
        }

        // ========== 3. LOGIN FAILED ==========
        Log::info('=== LOGIN FAILED ===');
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

        // Tạo student với password đã hash
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
        
        // Xóa session an toàn
        Session::flush();
        Session::regenerate();
        
        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
    
    // ========== THÊM METHOD DEBUG ==========
    public function debugLogin(Request $request)
    {
        echo "<h1>Debug Login System</h1>";
        
        echo "<h2>Session Status:</h2>";
        echo "<pre>";
        print_r(session()->all());
        echo "</pre>";
        
        echo "<h2>Test Student Login:</h2>";
        $student = Student::where('username', 'nguyendinhtri')->first();
        
        if ($student) {
            echo "Student found: {$student->username}<br>";
            echo "Password hash: " . substr($student->password, 0, 20) . "...<br>";
            echo "Test Hash::check('123'): " . 
                 (Hash::check('123', $student->password) ? '✓ SUCCESS' : '✗ FAILED') . "<br>";
            
            // Test login
            if (Hash::check('123', $student->password)) {
                Session::regenerate();
                Session::put('user_type', 'student');
                Session::put('student_logged_in', true);
                Session::put('username', $student->username);
                
                echo "<p style='color:green;'>✓ Login successful via debug!</p>";
                echo '<a href="' . route('student.dashboard') . '">Go to Dashboard</a>';
            }
        }
        
        return "<hr><a href='" . route('login') . "'>Back to Login</a>";
    }
}