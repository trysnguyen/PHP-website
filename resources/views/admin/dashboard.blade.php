@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
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
                    <h5 class="text-info">{{ $orders->count() ?? 0 }}</h5>
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

    <!-- Books Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-book-half"></i> All Books</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                        <i class="bi bi-plus"></i> Add Book
                    </button>
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $book)
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
                                        <button class="btn btn-sm btn-warning" onclick="editBook('{{ $book->BookID }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.delete.book', $book->BookID) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this book?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No books in database. Add your first book!</p>
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

<script>
function editBook(bookId) {
    alert('Edit book with ID: ' + bookId + '\n(Feature coming soon)');
}
</script>
@endsection
