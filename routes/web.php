<?php

// =========== FIX: ĐĂNG KÝ MIDDLEWARE TRỰC TIẾP ===========
try {
    $router = app()->make('router');
    
    // Đăng ký middleware nếu chưa có
    $currentMiddlewares = $router->getMiddleware();
    
    if (!isset($currentMiddlewares['auth.student'])) {
        $router->aliasMiddleware('auth.student', \App\Http\Middleware\StudentAuth::class);
        error_log("✓ Registered auth.student middleware");
    }
    
    if (!isset($currentMiddlewares['auth.admin'])) {
        $router->aliasMiddleware('auth.admin', \App\Http\Middleware\AdminAuth::class);
        error_log("✓ Registered auth.admin middleware");
    }
} catch (Exception $e) {
    error_log("Middleware registration error: " . $e->getMessage());
}
// =========================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================
// PUBLIC ROUTES (Không cần đăng nhập)
// ============================================

// Trang chủ - Redirect đến login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// STUDENT ROUTES (Cần đăng nhập student)
// ============================================
// Route::middleware(['auth.student'])->group(function () {
Route::middleware([\App\Http\Middleware\StudentAuth::class])->group(function () {
    // Dashboard student
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    
    // Search books
    Route::get('/student/search', [StudentController::class, 'searchBooks'])->name('student.search');
    
    // Order book
    Route::post('/student/order', [StudentController::class, 'orderBook'])->name('student.order');
    
    // Cancel order
    Route::post('/student/cancel', [StudentController::class, 'cancelOrder'])->name('student.cancel');
    
    // Order history
    Route::get('/student/history', [StudentController::class, 'orderHistory'])->name('student.history');
});

// ============================================
// ADMIN ROUTES (Cần đăng nhập admin)
// ============================================

Route::middleware(['auth.admin'])->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Orders management
    Route::get('/admin/orders', [AdminController::class, 'ordersManagement'])->name('admin.orders');
    
    // Books management
    Route::get('/admin/search-books', [AdminController::class, 'searchBooks'])->name('admin.search.books');
    Route::post('/admin/add-book', [AdminController::class, 'addBook'])->name('admin.add.book');
    Route::post('/admin/update-book/{id}', [AdminController::class, 'updateBook'])->name('admin.update.book');
    Route::delete('/admin/delete-book/{id}', [AdminController::class, 'deleteBook'])->name('admin.delete.book');
    
    // Order management
    Route::post('/admin/update-order-status', [AdminController::class, 'updateOrderStatus'])->name('admin.update.order.status');
    Route::post('/admin/mark-returned', [AdminController::class, 'markAsReturned'])->name('admin.mark.returned');
    
    // Statistics
    Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
});

// ============================================
// FALLBACK ROUTE (404)
// ============================================

Route::fallback(function () {
    return redirect()->route('login');
});

// Thêm routes cho cancel order (nếu chưa có)
Route::post('/student/cancel', [StudentController::class, 'cancelOrder'])->name('student.cancel');

// Thêm routes cho statistics
Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');

// ============================================
// TEST ROUTES
// ============================================

Route::get('/test-middleware', function() {
    try {
        // Thử tạo instance middleware
        $middleware = new \App\Http\Middleware\StudentAuth();
        return "✓ StudentAuth class exists and can be instantiated";
    } catch (Exception $e) {
        return "✗ Error: " . $e->getMessage();
    }
});

// Thêm route test mới
Route::get('/test-middleware-registration', function() {
    $router = app('router');
    $middlewares = $router->getMiddleware();
    
    echo "<h1>Middleware Registration Test</h1>";
    echo "<pre>";
    print_r($middlewares);
    echo "</pre>";
    
    if (isset($middlewares['auth.student'])) {
        return "<p style='color:green; font-size:20px;'>✓ SUCCESS! auth.student is registered!</p>";
    } else {
        return "<p style='color:red; font-size:20px;'>✗ FAILED! auth.student not registered.</p>";
    }
});

// Test route với middleware
Route::middleware(['auth.student'])->get('/test-protected-route', function() {
    return "✓ SUCCESS! This page is protected by auth.student middleware";
});

// Thêm route debug
Route::get('/debug-login', [AuthController::class, 'debugLogin']);

// Test login
Route::get('/test-login/{username}/{password}', function($username, $password) {
    $student = \App\Models\Student::where('username', $username)->first();
    
    if (!$student) {
        return "Student not found";
    }
    
    echo "Student: {$student->username}<br>";
    echo "DB Password: {$student->password}<br>";
    echo "Length: " . strlen($student->password) . "<br>";
    echo "Hash::check('{$password}'): " . (\Illuminate\Support\Facades\Hash::check($password, $student->password) ? '✓ YES' : '✗ NO') . "<br>";
    echo "Direct compare: " . ($student->password === $password ? '✓ YES' : '✗ NO') . "<br>";
    
    // Test login
    session()->flush();
    
    if (\Illuminate\Support\Facades\Hash::check($password, $student->password) || $student->password === $password) {
        session(['user_type' => 'student', 'student_logged_in' => true]);
        return "✓ Login should work!";
    } else {
        return "✗ Login failed";
    }
});

// Thêm các route debug này
Route::get('/check-session', function() {
    return response()->json([
        'logged_in' => session()->has('user_type'),
        'user_type' => session('user_type'),
        'username' => session('username'),
        'session_id' => session()->getId()
    ]);
});

Route::get('/debug-login-flow', function() {
    return view('debug.login-flow'); // Tạo view này nếu cần
});

Route::get('/force-login', function() {
    $student = \App\Models\Student::where('username', 'nguyendinhtri')->first();
    
    if ($student) {
        session()->flush();
        session()->regenerate();
        
        session([
            'user_type' => 'student',
            'student_logged_in' => true,
            'username' => $student->username,
            'student_name' => $student->Studentname,
            'student_id' => $student->StudentID,
            'logged_in' => true
        ]);
        
        return redirect()->route('student.dashboard')->with('success', 'Auto-login successful!');
    }
    
    return redirect()->route('login')->with('error', 'Student not found');
});

// Thêm route debug (nếu cần)
Route::get('/auth/debug', [AuthController::class, 'debugLogin']);

// Đảm bảo các route sau đúng:
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post'); // QUAN TRỌNG: phải là login.post
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Student routes
Route::middleware(['auth.student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    // ... các route khác
});

// Admin routes  
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // ... các route khác
});