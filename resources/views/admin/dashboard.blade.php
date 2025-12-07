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
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3><i class="bi bi-speedometer2"></i> Admin Dashboard</h3>
                    <p class="mb-0">Welcome, {{ session('username') }}! You are logged in as Administrator.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- THANH TÌM KIẾM - THÊM VÀO ĐÂY -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.search.books') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   name="search" 
                                   placeholder="Search books by name, author, or category..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                            @if(request('search'))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Stats -->
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-primary">{{ $totalBooks ?? 0 }}</h5>
                    <p class="text-muted mb-0">Total Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-success">{{ $availableBooks ?? 0 }}</h5>
                    <p class="text-muted mb-0">Available Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-warning">{{ $pendingOrders ?? 0 }}</h5>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="text-info">{{ $totalOrders ?? 0 }}</h5>
                    <p class="text-muted mb-0">Total Orders</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
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
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if(isset($orders) && $orders->count() > 0)
                        <div class="list-group">
                            @foreach($orders->take(5) as $order)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $order->Bookname }}</h6>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </div>
                                <p class="mb-1 small">
                                    {{ $order->Studentname }} ({{ $order->StudentID }})
                                </p>
                                <span class="badge 
                                    @if($order->Status == 'Pending') bg-warning
                                    @elseif($order->Status == 'Accept') bg-success
                                    @elseif($order->Status == 'Refuse') bg-danger
                                    @else bg-secondary @endif">
                                    {{ $order->Status }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Books Table - ĐÃ THÊM HIỂN THỊ KẾT QUẢ TÌM KIẾM -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="bi bi-book-half"></i> All Books</h5>
                        @if(request('search'))
                            <small class="text-light">Search results for: "{{ request('search') }}"</small>
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
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Book Name</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Order Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
                                <?php
                                // Kiểm tra chi tiết trạng thái đơn hàng
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
                                    <td>{{ $book->BookID }}</td>
                                    <td><strong>{{ $book->Bookname }}</strong></td>
                                    <td>{{ $book->Author }}</td>
                                    <td><span class="badge bg-secondary">{{ $book->Category }}</span></td>
                                    <td>
                                        @if($book->Quantity > 0)
                                            <span class="badge bg-success">{{ $book->Quantity }}</span>
                                        @else
                                            <span class="badge bg-danger">Out of stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasAnyOrders)
                                            <small>
                                                @if($pendingCount > 0)<span class="badge bg-warning me-1">P:{{ $pendingCount }}</span>@endif
                                                @if($acceptedCount > 0)<span class="badge bg-success me-1">A:{{ $acceptedCount }}</span>@endif
                                                @if($refusedCount > 0)<span class="badge bg-danger me-1">R:{{ $refusedCount }}</span>@endif
                                                @if($returnedCount > 0)<span class="badge bg-secondary">RT:{{ $returnedCount }}</span>@endif
                                            </small>
                                        @else
                                            <span class="badge bg-light text-dark">No orders</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Nút Sửa -->
                                        <button class="btn btn-sm btn-warning mb-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBookModal"
                                                data-book-id="{{ $book->BookID }}"
                                                data-book-name="{{ $book->Bookname }}"
                                                data-book-author="{{ $book->Author }}"
                                                data-book-category="{{ $book->Category }}"
                                                data-book-quantity="{{ $book->Quantity }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <br>
                                        
                                        <!-- Form Xóa -->
                                        @if($hasActiveOrders)
                                            <button class="btn btn-sm btn-danger" disabled 
                                                    title="Has {{ $pendingCount + $acceptedCount }} active/pending order(s)">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @elseif($hasRefusedOrReturned)
                                            <form action="{{ route('admin.delete.book', $book->BookID) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete \u0022{{ $book->Bookname }}\u0022?\n\n⚠️ This book has {{ $refusedCount }} refused and {{ $returnedCount }} returned orders.\n\nThese orders will still exist in history.\n\n⚠️ This action cannot be undone!')">
                                                    <i class="bi bi-trash"></i> Delete
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
                                                    <i class="bi bi-trash"></i> Delete
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
                    <p class="text-muted text-center">
                        @if(request('search'))
                            No books found for "{{ request('search') }}".
                        @else
                            No books in database. Add your first book!
                        @endif
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Book</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.add.book') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Book Name *</label>
                        <input type="text" class="form-control" name="Bookname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author *</label>
                        <input type="text" class="form-control" name="Author" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <input type="text" class="form-control" name="Category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" class="form-control" name="Quantity" min="0" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBookForm" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editBookId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Book Name *</label>
                        <input type="text" class="form-control" id="editBookname" name="Bookname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author *</label>
                        <input type="text" class="form-control" id="editAuthor" name="Author" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <input type="text" class="form-control" id="editCategory" name="Category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" class="form-control" id="editQuantity" name="Quantity" min="0" required>
                        <small class="text-muted">Note: Cannot reduce below currently borrowed books</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection