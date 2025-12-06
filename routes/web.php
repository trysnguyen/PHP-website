<?php

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

Route::middleware(['auth.student'])->group(function () {
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
