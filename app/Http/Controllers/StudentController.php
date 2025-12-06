<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\OrderBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    // Hiển thị dashboard student
    public function dashboard()
    {
        // Kiểm tra đăng nhập - DÙNG CÁCH NÀY CHO CHẮC
        if (!Session::get('student_logged_in') && Session::get('user_type') !== 'student') {
            Log::warning('Student dashboard access denied', [
                'user_type' => Session::get('user_type'),
                'student_logged_in' => Session::get('student_logged_in')
            ]);
            return redirect()->route('login')->withErrors(['auth' => 'Please login as student.']);
        }

        $studentId = Session::get('student_id');
        $username = Session::get('username');
        
        Log::info('Student dashboard accessed', [
            'student_id' => $studentId,
            'username' => $username
        ]);
        
        // Lấy danh sách sách có sẵn
        $books = Book::where('Quantity', '>', 0)->get();
        
        // Lấy đơn hàng của sinh viên
        $orders = OrderBook::where('StudentID', $studentId)->get();

        return view('student.dashboard', compact('books', 'orders'));
    }

    // Tìm kiếm sách
    public function searchBooks(Request $request)
    {
        $search = $request->input('search', '');
        
        $books = Book::where('Quantity', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('Bookname', 'like', "%$search%")
                      ->orWhere('Author', 'like', "%$search%")
                      ->orWhere('Category', 'like', "%$search%");
            })
            ->get();

        if ($request->ajax()) {
            return response()->json($books);
        }

        return view('student.search', compact('books', 'search'));
    }

    // Đặt mượn sách
    public function orderBook(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Session::get('student_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'book_id' => 'required|exists:books,BookID'
        ]);

        $studentId = Session::get('student_id');
        $studentName = Session::get('student_name');
        $username = Session::get('username');
        $book = Book::find($request->book_id);

        // Kiểm tra sách còn không
        if ($book->Quantity <= 0) {
            return back()->withErrors(['order' => 'Book is out of stock.']);
        }

        // Kiểm tra đã đặt chưa
        $existingOrder = OrderBook::where('StudentID', $studentId)
            ->where('Bookname', $book->Bookname)
            ->where('Status', 'Pending')
            ->first();

        if ($existingOrder) {
            return back()->withErrors(['order' => 'You have already ordered this book.']);
        }

        // Tạo đơn hàng
        OrderBook::create([
            'username' => $username,
            'Studentname' => $studentName,
            'StudentID' => $studentId,
            'Bookname' => $book->Bookname,
            'Status' => 'Pending'
        ]);

        Log::info('Book ordered', [
            'student_id' => $studentId,
            'book_id' => $book->BookID,
            'book_name' => $book->Bookname
        ]);

        return back()->with('success', 'Book ordered successfully! Wait for admin approval.');
    }

    // Hủy đơn hàng
    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:order_books,OrderID'
        ]);

        $order = OrderBook::find($request->order_id);
        $studentId = Session::get('student_id');

        // Kiểm tra quyền
        if ($order->StudentID !== $studentId) {
            return back()->withErrors(['error' => 'You cannot cancel this order.']);
        }

        // Chỉ hủy được khi còn Pending
        if ($order->Status !== 'Pending') {
            return back()->withErrors(['error' => 'Cannot cancel. Order is already processed.']);
        }

        $order->delete();

        Log::info('Order cancelled', [
            'order_id' => $request->order_id,
            'student_id' => $studentId
        ]);

        return back()->with('success', 'Order cancelled successfully.');
    }

    // View order history
    public function orderHistory()
    {
        $studentId = Session::get('student_id');
        $orders = OrderBook::where('StudentID', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.history', compact('orders'));
    }
}