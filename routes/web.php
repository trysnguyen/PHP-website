<?php

// =========== REMOVE: XÓA PHẦN ĐĂNG KÝ MIDDLEWARE THỦ CÔNG ===========
// Phần này không cần thiết vì Laravel tự động register middleware trong Kernel.php

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

// **FIX QUAN TRỌNG: Đổi tên route POST login để tránh trùng với GET**
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// **FIX: Đổi logout sang POST method để bảo mật**
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// STUDENT ROUTES (Cần đăng nhập student)
// ============================================
Route::middleware(['auth.student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/search', [StudentController::class, 'searchBooks'])->name('student.search');
    Route::post('/student/order', [StudentController::class, 'orderBook'])->name('student.order');
    Route::post('/student/cancel', [StudentController::class, 'cancelOrder'])->name('student.cancel');
    Route::get('/student/history', [StudentController::class, 'orderHistory'])->name('student.history');
});

// ============================================
// ADMIN ROUTES (Cần đăng nhập admin)
// ============================================
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/orders', [AdminController::class, 'ordersManagement'])->name('admin.orders');
    Route::get('/admin/search-books', [AdminController::class, 'searchBooks'])->name('admin.search.books');
    Route::post('/admin/add-book', [AdminController::class, 'addBook'])->name('admin.add.book');
    Route::post('/admin/update-book/{id}', [AdminController::class, 'updateBook'])->name('admin.update.book');
    Route::delete('/admin/delete-book/{id}', [AdminController::class, 'deleteBook'])->name('admin.delete.book');
    Route::post('/admin/update-order-status', [AdminController::class, 'updateOrderStatus'])->name('admin.update.order.status');
    Route::post('/admin/mark-returned', [AdminController::class, 'markAsReturned'])->name('admin.mark.returned');
    Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');

});

// ============================================
// FALLBACK ROUTE (404)
// ============================================
Route::fallback(function () {
    return redirect()->route('login');
});

// ============================================
// DEBUG ROUTES (Chỉ dùng trong development)
// ============================================
if (env('APP_DEBUG', false)) {
    Route::get('/debug/session', function() {
        return response()->json([
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'csrf_token' => csrf_token(),
            'app_key' => env('APP_KEY'),
        ]);
    });
    
    Route::get('/debug/middleware', function() {
        $router = app('router');
        return response()->json([
            'middlewares' => $router->getMiddleware(),
            'middleware_groups' => $router->getMiddlewareGroups()
        ]);
    });
    
    Route::get('/debug/login-test', function() {
        // Test session
        session()->put('test', 'value');
        $sessionId = session()->getId();
        
        return view('debug.login-test', [
            'session_id' => $sessionId,
            'csrf_token' => csrf_token()
        ]);
    });
}