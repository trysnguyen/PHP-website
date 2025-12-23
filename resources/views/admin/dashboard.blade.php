@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <!-- Hiển thị thông báo từ session -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1"><i class="bi bi-speedometer2"></i> Admin Dashboard</h3>
                        <p class="mb-0">Welcome, {{ session('username') }}! You are logged in as Administrator.</p>
                    </div>
                    <div class="text-end">
                        <small class="d-block">Today: {{ date('F d, Y') }}</small>
                        <small class="d-block">{{ date('h:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- THANH TÌM KIẾM -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.search.books') }}" method="GET" id="searchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   name="search" 
                                   id="searchInput"
                                   placeholder="Search books by name, author, or category..."
                                   value="{{ request('search', $search ?? '') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                            @if(request('search') || isset($search))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                            @endif
                        </div>
                        @if(request('search') || isset($search))
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                Searching in: Book Name, Author, Category
                                @if(isset($books) && $books->count() > 0)
                                    | Found: {{ $books->count() }} book(s)
                                @endif
                            </small>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK STATS -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Books
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-book fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Available Books
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBooks ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-history fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-check fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- LEFT COLUMN: QUICK ACTIONS & POPULAR BOOKS -->
        <div class="col-md-6 mb-3">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Quick Actions</h5>
                    @if($pendingOrders > 0)
                    <span class="badge bg-warning">{{ $pendingOrders }} pending</span>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mb-4">
                        <a href="{{ route('admin.orders') }}" class="btn btn-primary">
                            <i class="bi bi-list-check"></i> Manage Orders
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBookModal">
                            <i class="bi bi-plus-circle"></i> Add New Book
                        </button>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-warning">
                            <i class="bi bi-graph-up"></i> View Statistics
                        </a>
                    </div>
                    
                    <!-- Popular Books Section -->
                    <div class="mt-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-trophy"></i> Popular Books
                        </h6>
                        <?php
                        // Lấy top 3 sách được đặt nhiều nhất
                        $popularBooks = \App\Models\OrderBook::select('Bookname')
                            ->selectRaw('COUNT(*) as order_count')
                            ->groupBy('Bookname')
                            ->orderBy('order_count', 'desc')
                            ->limit(3)
                            ->get();
                        ?>
                        
                        @if($popularBooks->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($popularBooks as $index => $popular)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge 
                                        @if($index == 0) bg-warning
                                        @elseif($index == 1) bg-secondary
                                        @else bg-info @endif me-2">
                                        #{{ $index + 1 }}
                                    </span>
                                    <small class="text-truncate" style="max-width: 180px;" 
                                           title="{{ $popular->Bookname }}">
                                        {{ $popular->Bookname }}
                                    </small>
                                </div>
                                <span class="badge bg-primary">{{ $popular->order_count }} orders</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-3">
                            <i class="bi bi-book text-muted fs-4 mb-2"></i>
                            <p class="text-muted small mb-0">No order data yet</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- System Info -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-primary fw-bold">{{ $totalBooks ?? 0 }}</div>
                                <small class="text-muted">Total Books</small>
                            </div>
                            <div class="col-6">
                                <div class="text-success fw-bold">
                                    {{ \App\Models\Student::count() ?? 0 }}
                                </div>
                                <small class="text-muted">Total Students</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- RIGHT COLUMN: RECENT ORDERS -->
        <div class="col-md-6 mb-3">
            <div class="card shadow h-100">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Orders</h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-dark">View All</a>
                </div>
                <div class="card-body">
                    @if(isset($orders) && $orders->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($orders->take(6) as $order)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-truncate" title="{{ $order->Bookname }}">
                                            {{ $order->Bookname }}
                                        </h6>
                                        <p class="mb-1 small">
                                            <i class="bi bi-person me-1"></i>{{ $order->Studentname }}
                                            <span class="text-muted">({{ $order->StudentID }})</span>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">{{ $order->created_at->format('H:i') }}</small>
                                        <span class="badge 
                                            @if($order->Status == 'Pending') bg-warning
                                            @elseif($order->Status == 'Accept') bg-success
                                            @elseif($order->Status == 'Refuse') bg-danger
                                            @else bg-secondary @endif">
                                            {{ $order->Status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $order->created_at->format('M d, Y') }}
                                    </small>
                                    @if($order->Status == 'Pending')
                                    <small class="text-warning">
                                        <i class="bi bi-clock"></i> Waiting approval
                                    </small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted fs-1 mb-3"></i>
                        <h6 class="text-muted">No Orders Yet</h6>
                        <p class="text-muted small">No orders have been placed yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- BOOKS TABLE -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="bi bi-book-half"></i> 
                            @if(request('search') || isset($search))
                                Search Results
                            @else
                                All Books
                            @endif
                        </h5>
                        @if(request('search') || isset($search))
                            <small class="text-light">Found {{ $books->count() }} book(s) for "{{ request('search', $search ?? '') }}"</small>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                            <i class="bi bi-plus"></i> Add Book
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($books) && $books->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Book Name</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Order Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
                                <?php
                                $ordersForThisBook = \App\Models\OrderBook::where('Bookname', $book->Bookname)->get();
                                
                                $pendingCount = 0;
                                $acceptedCount = 0;
                                $refusedCount = 0;
                                $returnedCount = 0;
                                
                                foreach ($ordersForThisBook as $order) {
                                    switch ($order->Status) {
                                        case 'Pending': $pendingCount++; break;
                                        case 'Accept': $acceptedCount++; break;
                                        case 'Refuse': $refusedCount++; break;
                                        case 'Returned': $returnedCount++; break;
                                    }
                                }
                                
                                $hasActiveOrders = ($pendingCount > 0 || $acceptedCount > 0);
                                $hasRefusedOrReturned = ($refusedCount > 0 || $returnedCount > 0);
                                $hasAnyOrders = $ordersForThisBook->count() > 0;
                                ?>
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $book->BookID }}</span></td>
                                    <td><strong>{{ $book->Bookname }}</strong></td>
                                    <td>{{ $book->Author }}</td>
                                    <td><span class="badge bg-info">{{ $book->Category }}</span></td>
                                    <td>
                                        @if($book->Quantity > 10)
                                            <span class="badge bg-success">{{ $book->Quantity }}</span>
                                        @elseif($book->Quantity > 0)
                                            <span class="badge bg-warning">{{ $book->Quantity }} (Low)</span>
                                        @else
                                            <span class="badge bg-danger">Out of stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasAnyOrders)
                                            <div class="small">
                                                @if($pendingCount > 0)<span class="badge bg-warning me-1" title="{{ $pendingCount }} pending">P:{{ $pendingCount }}</span>@endif
                                                @if($acceptedCount > 0)<span class="badge bg-success me-1" title="{{ $acceptedCount }} accepted">A:{{ $acceptedCount }}</span>@endif
                                                @if($refusedCount > 0)<span class="badge bg-danger me-1" title="{{ $refusedCount }} refused">R:{{ $refusedCount }}</span>@endif
                                                @if($returnedCount > 0)<span class="badge bg-secondary" title="{{ $returnedCount }} returned">RT:{{ $returnedCount }}</span>@endif
                                            </div>
                                        @else
                                            <span class="badge bg-light text-dark">No orders</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-warning mb-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBookModal"
                                                data-book-id="{{ $book->BookID }}"
                                                data-book-name="{{ $book->Bookname }}"
                                                data-book-author="{{ $book->Author }}"
                                                data-book-category="{{ $book->Category }}"
                                                data-book-quantity="{{ $book->Quantity }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        @if($hasActiveOrders)
                                            <button class="btn btn-sm btn-danger" disabled 
                                                    title="Has {{ $pendingCount + $acceptedCount }} active/pending order(s)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @elseif($hasRefusedOrReturned)
                                            <form action="{{ route('admin.delete.book', $book->BookID) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete \u0022{{ $book->Bookname }}\u0022?\n\n⚠️ This book has {{ $refusedCount }} refused and {{ $returnedCount }} returned orders.\n\nThese orders will still exist in history.\n\n⚠️ This action cannot be undone!')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.delete.book', $book->BookID) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete \u0022{{ $book->Bookname }}\u0022?\n\n⚠️ This action cannot be undone!')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        @if(request('search') || isset($search))
                            <i class="bi bi-search text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted">No books found</h5>
                            <p class="text-muted mb-4">No books match your search</p>
                        @else
                            <i class="bi bi-book text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted">No Books Found</h5>
                            <p class="text-muted mb-4">Start by adding your first book to the library</p>
                        @endif
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
                            <i class="bi bi-plus-circle"></i> Add New Book
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
@include('admin.modals.add-book')
@include('admin.modals.edit-book')

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Modal
    const editModal = document.getElementById('editBookModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const bookId = button.getAttribute('data-book-id');
            const bookName = button.getAttribute('data-book-name');
            const author = button.getAttribute('data-book-author');
            const category = button.getAttribute('data-book-category');
            const quantity = button.getAttribute('data-book-quantity');
            
            document.getElementById('editBookId').value = bookId;
            document.getElementById('editBookname').value = bookName;
            document.getElementById('editAuthor').value = author;
            document.getElementById('editCategory').value = category;
            document.getElementById('editQuantity').value = quantity;
            
            const editForm = document.getElementById('editBookForm');
            editForm.action = "{{ route('admin.update.book', ':id') }}".replace(':id', bookId);
        });
    }
    
    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<style>
.card {
    border-radius: 8px;
    border: none;
}

.card-header {
    border-radius: 8px 8px 0 0 !important;
}

.badge {
    font-size: 0.75em;
    padding: 4px 8px;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection
