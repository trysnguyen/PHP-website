<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\OrderBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        // Kiểm tra đăng nhập admin
        if (Session::get('user_type') !== 'admin') {
            return redirect()->route('login')->withErrors(['auth' => 'Please login as admin.']);
        }

        $books = Book::orderBy('BookID')->get();
        $orders = OrderBook::orderBy('created_at', 'desc')->get();
        $pendingOrders = OrderBook::where('Status', 'Pending')->count();
        $totalBooks = Book::count();
        $availableBooks = Book::where('Quantity', '>', 0)->count();

        return view('admin.dashboard', compact('books', 'orders', 'pendingOrders', 'totalBooks', 'availableBooks'));
    }

    // Quản lý đơn hàng
    public function ordersManagement()
    {
        if (Session::get('user_type') !== 'admin') {
            return redirect()->route('login');
        }

        $orders = OrderBook::where('Status', 'Accept')->get();
        return view('admin.orders-management', compact('orders'));
    }

    // Tìm kiếm sách
    public function searchBooks(Request $request)
    {
        $search = $request->input('search', '');
        
        $books = Book::where(function($query) use ($search) {
                $query->where('Bookname', 'like', "%$search%")
                      ->orWhere('Author', 'like', "%$search%")
                      ->orWhere('Category', 'like', "%$search%");
            })
            ->orderBy('BookID')
            ->get();

        if ($request->ajax()) {
            return response()->json($books);
        }

        return back();
    }

    // Thêm sách mới
    public function addBook(Request $request)
    {
        $request->validate([
            'Bookname' => 'required|string|max:200',
            'Author' => 'required|string|max:100',
            'Category' => 'required|string|max:50',
            'Quantity' => 'required|integer|min:0'
        ]);

        Book::create([
            'Bookname' => $request->Bookname,
            'Author' => $request->Author,
            'Category' => $request->Category,
            'Quantity' => $request->Quantity
        ]);

        return back()->with('success', 'Book added successfully!');
    }

    // Cập nhật sách
    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'Bookname' => 'required|string|max:200',
            'Author' => 'required|string|max:100',
            'Category' => 'required|string|max:50',
            'Quantity' => 'required|integer|min:0'
        ]);

        $book = Book::find($id);
        if ($book) {
            $book->update($request->all());
            return back()->with('success', 'Book updated successfully!');
        }

        return back()->withErrors(['error' => 'Book not found.']);
    }

    // Xóa sách
    public function deleteBook($id)
    {
        $book = Book::find($id);
        if ($book) {
            // Kiểm tra xem sách có trong đơn hàng không
            $hasOrders = OrderBook::where('Bookname', $book->Bookname)->exists();
            
            if ($hasOrders) {
                return back()->withErrors(['error' => 'Cannot delete. This book has existing orders.']);
            }
            
            $book->delete();
            return back()->with('success', 'Book deleted successfully!');
        }

        return back()->withErrors(['error' => 'Book not found.']);
    }

    // Duyệt đơn hàng (Accept/Refuse)
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:order_books,OrderID',
            'status' => 'required|in:Accept,Refuse'
        ]);

        $order = OrderBook::find($request->order_id);
        
        if ($order->Status !== 'Pending') {
            return back()->withErrors(['error' => 'This order has already been processed.']);
        }

        $order->Status = $request->status;
        $order->OrderedDate = now();
        
        if ($request->status === 'Accept') {
            $order->ReturnedDate = now()->addDays(3);
            
            // Giảm số lượng sách
            $book = Book::where('Bookname', $order->Bookname)->first();
            if ($book && $book->Quantity > 0) {
                $book->decrement('Quantity');
            }
        }
        
        $order->save();

        $message = $request->status === 'Accept' ? 'Order accepted!' : 'Order refused!';
        return back()->with('success', $message);
    }

    // Đánh dấu đã trả sách
    public function markAsReturned(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:order_books,OrderID'
        ]);

        $order = OrderBook::find($request->order_id);
        
        if ($order->Status !== 'Accept') {
            return back()->withErrors(['error' => 'Only accepted orders can be marked as returned.']);
        }

        $order->Status = 'Returned';
        $order->ReturnedDate = now();
        $order->save();

        // Tăng số lượng sách
        $book = Book::where('Bookname', $order->Bookname)->first();
        if ($book) {
            $book->increment('Quantity');
        }

        return back()->with('success', 'Book marked as returned!');
    }

    // Thống kê
    public function statistics()
    {
        $totalBooks = Book::count();
        $totalOrders = OrderBook::count();
        $pendingOrders = OrderBook::where('Status', 'Pending')->count();
        $acceptedOrders = OrderBook::where('Status', 'Accept')->count();
        $returnedOrders = OrderBook::where('Status', 'Returned')->count();
        
        // Sách được mượn nhiều nhất
        $popularBooks = OrderBook::select('Bookname')
            ->selectRaw('COUNT(*) as total_orders')
            ->groupBy('Bookname')
            ->orderBy('total_orders', 'desc')
            ->limit(5)
            ->get();

        return view('admin.statistics', compact(
            'totalBooks', 'totalOrders', 'pendingOrders', 
            'acceptedOrders', 'returnedOrders', 'popularBooks'
        ));
    }
}