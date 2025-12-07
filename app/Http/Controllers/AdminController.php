<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\OrderBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        // Kiểm tra đăng nhập admin
        if (!Session::get('admin_logged_in') && Session::get('user_type') !== 'admin') {
            Log::warning('Admin dashboard access denied', [
                'user_type' => Session::get('user_type'),
                'admin_logged_in' => Session::get('admin_logged_in')
            ]);
            return redirect()->route('login')->withErrors(['auth' => 'Please login as admin.']);
        }

        Log::info('Admin dashboard accessed', ['username' => Session::get('username')]);

        $books = Book::orderBy('BookID')->get();
        $orders = OrderBook::orderBy('created_at', 'desc')->get();
        
        // Tính toán stats
        $pendingOrders = $orders->where('Status', 'Pending')->count();
        $totalBooks = $books->count();
        $availableBooks = $books->where('Quantity', '>', 0)->count();
        $acceptedOrders = $orders->where('Status', 'Accept')->count();
        $refusedOrders = $orders->where('Status', 'Refuse')->count();
        $returnedOrders = $orders->where('Status', 'Returned')->count();
        $totalOrders = $orders->count();

        return view('admin.dashboard', compact(
            'books', 'orders', 'pendingOrders', 'totalBooks', 
            'availableBooks', 'acceptedOrders', 'refusedOrders', 
            'returnedOrders', 'totalOrders'
        ));
    }

    // Quản lý đơn hàng
    public function ordersManagement()
    {
        // Kiểm tra đăng nhập admin
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('login')->with('error', 'Please login as admin.');
        }

        // Lấy tất cả orders với sắp xếp mới nhất trước
        $orders = OrderBook::orderBy('created_at', 'desc')->get();

        Log::info('Admin viewing orders', ['order_count' => $orders->count()]);

        return view('admin.orders-management', compact('orders'));
    }

    // Tìm kiếm sách
    public function searchBooks(Request $request)
{
    if (!Session::get('admin_logged_in')) {
        return redirect()->route('login');
    }

    $search = $request->input('search', '');
    
    $books = Book::where(function($query) use ($search) {
            $query->where('Bookname', 'like', "%$search%")
                  ->orWhere('Author', 'like', "%$search%")
                  ->orWhere('Category', 'like', "%$search%");
        })
        ->orderBy('BookID')
        ->get();

    // Lấy tất cả orders cho stats
    $orders = OrderBook::orderBy('created_at', 'desc')->get();
    
    // Tính toán stats
    $pendingOrders = $orders->where('Status', 'Pending')->count();
    $totalBooks = Book::count();
    $availableBooks = Book::where('Quantity', '>', 0)->count();
    $acceptedOrders = $orders->where('Status', 'Accept')->count();
    $refusedOrders = $orders->where('Status', 'Refuse')->count();
    $returnedOrders = $orders->where('Status', 'Returned')->count();
    $totalOrders = $orders->count();

    // KHÔNG trả về view riêng, mà trả về dashboard view với dữ liệu tìm kiếm
    return view('admin.dashboard', compact(
        'books', 'orders', 'pendingOrders', 'totalBooks', 
        'availableBooks', 'acceptedOrders', 'refusedOrders', 
        'returnedOrders', 'totalOrders', 'search'
    ));
}

    // Thêm sách mới
    public function addBook(Request $request)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'Bookname' => 'required|string|max:200',
            'Author' => 'required|string|max:100',
            'Category' => 'required|string|max:50',
            'Quantity' => 'required|integer|min:0'
        ]);

        $book = Book::create([
            'Bookname' => $request->Bookname,
            'Author' => $request->Author,
            'Category' => $request->Category,
            'Quantity' => $request->Quantity
        ]);

        Log::info('Book added by admin', [
            'book_id' => $book->BookID,
            'book_name' => $book->Bookname,
            'quantity' => $book->Quantity
        ]);

        return back()->with('success', 'Book added successfully!');
    }

    // Cập nhật sách
    public function updateBook(Request $request, $id)
{
    if (!Session::get('admin_logged_in')) {
        return redirect()->route('login')->with('error', 'Please login as admin.');
    }

    $request->validate([
        'Bookname' => 'required|string|max:200',
        'Author' => 'required|string|max:100',
        'Category' => 'required|string|max:50',
        'Quantity' => 'required|integer|min:0'
    ]);

    $book = Book::find($id);
    if (!$book) {
        return back()->withErrors(['error' => 'Book not found.']);
    }

    $oldQuantity = $book->Quantity;
    $newQuantity = $request->Quantity;
    
    // KIỂM TRA NẾU GIẢM SỐ LƯỢNG
    if ($newQuantity < $oldQuantity) {
        // Đếm số sách đang được mượn (status = 'Accept')
        $borrowedCount = OrderBook::where('Bookname', $book->Bookname)
            ->where('Status', 'Accept')
            ->count();
        
        // Không thể giảm số lượng nhỏ hơn số sách đang mượn
        if ($newQuantity < $borrowedCount) {
            return back()->withErrors([
                'error' => "Cannot reduce quantity to {$newQuantity}. 
                          There are {$borrowedCount} books currently borrowed."
            ]);
        }
    }
    
    // Cập nhật sách
    $book->update([
        'Bookname' => $request->Bookname,
        'Author' => $request->Author,
        'Category' => $request->Category,
        'Quantity' => $newQuantity
    ]);

    Log::info('Book updated by admin', [
        'book_id' => $book->BookID,
        'book_name' => $book->Bookname,
        'old_quantity' => $oldQuantity,
        'new_quantity' => $newQuantity
    ]);

    return back()->with('success', "Book '{$book->Bookname}' updated successfully!");
}

    // Xóa sách
    public function deleteBook($id)
{
    if (!Session::get('admin_logged_in')) {
        return redirect()->route('login')->with('error', 'Please login as admin.');
    }

    $book = Book::find($id);
    if (!$book) {
        return back()->with('error', 'Book not found.');
    }

    // KIỂM TRA THEO BOOKNAME (vì database không có BookID trong order_books)
    $ordersForThisBook = OrderBook::where('Bookname', $book->Bookname)->get();
    
    // Kiểm tra xem có đơn hàng active không (Pending hoặc Accept)
    $hasActiveOrders = $ordersForThisBook->contains(function ($order) {
        return in_array($order->Status, ['Pending', 'Accept']);
    });
    
    if ($hasActiveOrders) {
        $activeCount = $ordersForThisBook->whereIn('Status', ['Pending', 'Accept'])->count();
        return back()->with('error', "Cannot delete. This book has {$activeCount} active/pending order(s).");
    }
    
    // Đếm số lượng đơn refused/returned
    $refusedCount = $ordersForThisBook->where('Status', 'Refuse')->count();
    $returnedCount = $ordersForThisBook->where('Status', 'Returned')->count();
    $hasAnyOrders = $ordersForThisBook->count() > 0;
    
    $bookName = $book->Bookname;
    $book->delete();
    
    // Thông báo khác nhau tùy theo loại đơn hàng
    if ($hasAnyOrders) {
        $message = "Book '{$bookName}' deleted successfully. ";
        $message .= "Note: {$ordersForThisBook->count()} refused/returned orders are still in history.";
    } else {
        $message = "Book '{$bookName}' deleted successfully!";
    }
    
    return back()->with('success', $message);
}

    public function deleteMultipleBooks(Request $request)
{
    if (!Session::get('admin_logged_in')) {
        return redirect()->route('login');
    }

    $request->validate([
        'book_ids' => 'required|array',
        'book_ids.*' => 'exists:books,BookID'
    ]);

    $deletedCount = 0;
    $failedBooks = [];

    foreach ($request->book_ids as $bookId) {
        $book = Book::find($bookId);
        
        // Kiểm tra đơn hàng
        $hasOrders = OrderBook::where('BookID', $book->BookID)->exists();
        
        if ($hasOrders) {
            $failedBooks[] = $book->Bookname;
            continue;
        }
        
        $book->delete();
        $deletedCount++;
        
        Log::info('Book deleted in batch', [
            'book_id' => $book->BookID,
            'book_name' => $book->Bookname
        ]);
    }

    $response = [];
    if ($deletedCount > 0) {
        $response['success'] = "Successfully deleted {$deletedCount} book(s).";
    }
    if (!empty($failedBooks)) {
        $response['warning'] = "Could not delete: " . implode(', ', $failedBooks) . 
                              " (have existing orders)";
    }

    return back()->with($response);
}

    // Duyệt đơn hàng (Accept/Refuse) - ĐÃ SỬA CHO ĐÚNG ENUM
    public function updateOrderStatus(Request $request)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'order_id' => 'required|exists:order_books,OrderID',
            'status' => 'required|in:Accept,Refuse,Pending,Returned' // QUAN TRỌNG: Dùng đúng ENUM values
        ]);

        $order = OrderBook::find($request->order_id);
        
        // Map các giá trị nếu cần (từ view gửi sai)
        $statusMap = [
            'Accepted' => 'Accept',
            'Rejected' => 'Refuse',
            'Approved' => 'Accept',
            'Refused' => 'Refuse'
        ];
        
        // Chuyển đổi nếu cần
        $dbStatus = $statusMap[$request->status] ?? $request->status;
        
        $oldStatus = $order->Status;
        $order->Status = $dbStatus;
        
        // Nếu chấp nhận đơn, giảm số lượng sách
        if ($dbStatus === 'Accept' && $oldStatus !== 'Accept') {
            $book = Book::where('Bookname', $order->Bookname)->first();
            if ($book && $book->Quantity > 0) {
                $book->decrement('Quantity');
                Log::info('Book quantity decreased', [
                    'book_id' => $book->BookID,
                    'book_name' => $book->Bookname,
                    'new_quantity' => $book->Quantity
                ]);
            }
            
            // Cập nhật ngày đặt hàng
            $order->OrderedDate = now();
        }
        
        // Nếu từ chối và trước đó đã chấp nhận, hoàn lại số lượng sách
        if ($dbStatus === 'Refuse' && $oldStatus === 'Accept') {
            $book = Book::where('Bookname', $order->Bookname)->first();
            if ($book) {
                $book->increment('Quantity');
                Log::info('Book quantity increased (order refused)', [
                    'book_id' => $book->BookID,
                    'book_name' => $book->Bookname
                ]);
            }
        }
        
        $order->save();

        Log::info('Order status updated', [
            'order_id' => $order->OrderID,
            'old_status' => $oldStatus,
            'new_status' => $dbStatus,
            'student_id' => $order->StudentID
        ]);

        return back()->with('success', "Order #{$order->OrderID} has been updated to {$dbStatus}.");
    }

    // Đánh dấu đã trả sách
    public function markAsReturned(Request $request)
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'order_id' => 'required|exists:order_books,OrderID'
        ]);

        $order = OrderBook::find($request->order_id);
        
        // Chỉ đánh dấu trả nếu đã được chấp nhận
        if ($order->Status !== 'Accept') {
            return back()->withErrors(['error' => 'Only accepted orders can be marked as returned.']);
        }
        
        $order->Status = 'Returned';
        $order->ReturnedDate = now();
        $order->save();

        // Tăng số lượng sách khi trả
        $book = Book::where('Bookname', $order->Bookname)->first();
        if ($book) {
            $book->increment('Quantity');
            
            Log::info('Book returned', [
                'order_id' => $order->OrderID,
                'book_name' => $order->Bookname,
                'student_id' => $order->StudentID
            ]);
        }

        return back()->with('success', "Order #{$order->OrderID} marked as returned.");
    }

    // Thống kê
    public function statistics()
{
    if (!Session::get('admin_logged_in')) {
        return redirect()->route('login');
    }

    $totalBooks = Book::count();
    $totalOrders = OrderBook::count();
    $pendingOrders = OrderBook::where('Status', 'Pending')->count();
    $acceptedOrders = OrderBook::where('Status', 'Accept')->count(); // Đúng ENUM
    $refusedOrders = OrderBook::where('Status', 'Refuse')->count();   // Đúng ENUM
    $returnedOrders = OrderBook::where('Status', 'Returned')->count();
    
    // Sách được mượn nhiều nhất
    $popularBooks = OrderBook::select('Bookname')
        ->selectRaw('COUNT(*) as total_orders')
        ->groupBy('Bookname')
        ->orderBy('total_orders', 'desc')
        ->limit(5)
        ->get();

    // Thống kê theo tháng
    $monthlyStats = OrderBook::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return view('admin.statistics', compact(
        'totalBooks', 'totalOrders', 'pendingOrders', 
        'acceptedOrders', 'refusedOrders', 'returnedOrders', 
        'popularBooks', 'monthlyStats'
    ));
}
    
    // Helper method để debug
    public function debugOrders()
    {
        echo "<h1>Debug Orders</h1>";
        
        $orders = OrderBook::all();
        echo "<p>Total orders: " . $orders->count() . "</p>";
        
        echo "<h2>Order Status Distribution:</h2>";
        $statuses = $orders->groupBy('Status');
        foreach ($statuses as $status => $group) {
            echo "<p>{$status}: " . $group->count() . " orders</p>";
        }
        
        echo "<h2>ENUM values in database:</h2>";
        $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM order_books LIKE 'Status'");
        foreach ($columns as $col) {
            echo "<p>Status column type: " . $col->Type . "</p>";
        }
        
        echo "<h2>Test Update:</h2>";
        $order = OrderBook::first();
        if ($order) {
            echo "<p>First order ID: {$order->OrderID}, Status: {$order->Status}</p>";
            
            echo '<form action="' . route('admin.update.order.status') . '" method="POST">';
            echo csrf_field();
            echo '<input type="hidden" name="order_id" value="' . $order->OrderID . '">';
            echo '<select name="status">';
            echo '<option value="Pending">Pending</option>';
            echo '<option value="Accept">Accept</option>';
            echo '<option value="Refuse">Refuse</option>';
            echo '<option value="Returned">Returned</option>';
            echo '</select>';
            echo '<button type="submit">Test Update</button>';
            echo '</form>';
        }
        
        return "<hr><a href='" . route('admin.dashboard') . "'>Back to Dashboard</a>";
    }
}