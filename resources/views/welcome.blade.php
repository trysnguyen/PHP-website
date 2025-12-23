@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary mb-2">Student's Dashboard</h2>
            <p class="text-muted">Library Order System</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Available Books -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-3">Available Books</h4>
                        <span class="badge bg-primary text-white p-2">
                            {{ $books->count() }} books
                        </span>
                    </div>
                    
                    <!-- Search Box -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" 
                                       id="searchInput" 
                                       class="form-control" 
                                       placeholder="Search by book name, author, category..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button id="searchBtn" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                                @if(request('search'))
                                <button id="resetBtn" class="btn btn-light border">
                                    <i class="fas fa-rotate-left me-1"></i>Reset
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Book Name</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($books as $index => $book)
                                <?php
                                $hasOrdered = \App\Models\OrderBook::where('StudentID', session('student_id'))
                                    ->where('Bookname', $book->Bookname)
                                    ->whereIn('Status', ['Pending', 'Accept'])
                                    ->exists();
                                ?>
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-medium">{{ $book->Bookname }}</div>
                                        @if($book->Quantity <= 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle"></i> Out of Stock
                                        </small>
                                        @endif
                                    </td>
                                    <td>{{ $book->Author }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $book->Category }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if(!$hasOrdered && $book->Quantity > 0)
                                        <form method="POST" action="{{ route('student.order') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->BookID }}">
                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                <i class="fas fa-cart-plus me-1"></i>Order
                                            </button>
                                        </form>
                                        @elseif($hasOrdered)
                                        <button class="btn btn-secondary btn-sm px-3" disabled>
                                            <i class="fas fa-clock me-1"></i>Ordered
                                        </button>
                                        @else
                                        <button class="btn btn-outline-danger btn-sm px-3" disabled>
                                            <i class="fas fa-ban me-1"></i>Not Available
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($books->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No books available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: My Orders -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-3">My Orders</h4>
                        <span class="badge bg-info text-white p-2">
                            {{ $orders->count() }} orders
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Book Name</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $order->Bookname }}</div>
                                        <div class="small text-muted">
                                            <div>{{ $order->Studentname }}</div>
                                            <div>ID: {{ $order->StudentID }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($order->Status == 'Pending')
                                        <span class="badge bg-warning text-white p-2">
                                            <i class="fas fa-clock me-1"></i>{{ $order->Status }}
                                        </span>
                                        @elseif($order->Status == 'Accept')
                                        <span class="badge bg-success text-white p-2">
                                            <i class="fas fa-check-circle me-1"></i>{{ $order->Status }}
                                        </span>
                                        @elseif($order->Status == 'Refuse')
                                        <span class="badge bg-danger text-white p-2">
                                            <i class="fas fa-times-circle me-1"></i>{{ $order->Status }}
                                        </span>
                                        @else
                                        <span class="badge bg-secondary text-white p-2">
                                            {{ $order->Status }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No orders yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
    
    .badge {
        font-weight: 500;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.35rem 1rem;
        font-size: 0.85rem;
    }
    
    .form-control {
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    .table-light {
        background-color: #f8f9fa;
    }
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchText = document.getElementById('searchInput').value;
        
        if (searchText.trim() === '') {
            window.location.href = "{{ route('student.dashboard') }}";
            return;
        }
        
        window.location.href = "{{ route('student.dashboard') }}?search=" + encodeURIComponent(searchText);
    });

    // Reset search
    const resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location.href = "{{ route('student.dashboard') }}";
        });
    }

    // Search on Enter
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });

    // Fill search input if URL has search parameter
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) {
        document.getElementById('searchInput').value = decodeURIComponent(searchParam);
    }
});
</script>
@endsection
@endsection
