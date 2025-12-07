@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <h3>Available Books</h3>
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" 
                   placeholder="Search book/author/category"
                   value="{{ request('search') }}">
            <button id="searchBtn" class="btn btn-primary mt-2">Search</button>
            <button id="resetBtn" class="btn btn-secondary mt-2">Reset</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="booksTable">
                    @foreach($books as $index => $book)
                    <?php
                    $hasOrdered = \App\Models\OrderBook::where('StudentID', session('student_id'))
                        ->where('Bookname', $book->Bookname)
                        ->whereIn('Status', ['Pending', 'Accept'])
                        ->exists();
                    ?>
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $book->Bookname }}</td>
                        <td>{{ $book->Author }}</td>
                        <td>{{ $book->Category }}</td>
                        <td>
                            @if(!$hasOrdered && $book->Quantity > 0)
                            <form method="POST" action="{{ route('student.order') }}">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->BookID }}">
                                <button type="submit" class="btn btn-success btn-sm">Order</button>
                            </form>
                            @elseif($hasOrdered)
                            <button class="btn btn-secondary btn-sm" disabled>Ordered</button>
                            @else
                            <button class="btn btn-danger btn-sm" disabled>Unavailable</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="col-md-6">
        <h3>My Orders</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Book Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->username }}</td>
                        <td>{{ $order->Studentname }}</td>
                        <td>{{ $order->StudentID }}</td>
                        <td>{{ $order->Bookname }}</td>
                        <td>
                            <span class="badge 
                                @if($order->Status == 'Pending') bg-warning
                                @elseif($order->Status == 'Accept') bg-success
                                @elseif($order->Status == 'Refuse') bg-danger
                                @else bg-secondary @endif">
                                {{ $order->Status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Tìm kiếm đơn giản
document.getElementById('searchBtn').addEventListener('click', function() {
    const searchText = document.getElementById('searchInput').value;
    
    if (searchText.trim() === '') {
        // Nếu search rỗng, reload trang
        window.location.href = "{{ route('student.dashboard') }}";
        return;
    }
    
    // Chuyển đến trang với search parameter
    window.location.href = "{{ route('student.dashboard') }}?search=" + encodeURIComponent(searchText);
});

// Reset
document.getElementById('resetBtn').addEventListener('click', function() {
    window.location.href = "{{ route('student.dashboard') }}";
});

// Tìm kiếm khi nhấn Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});

// Nếu URL có search parameter, điền vào input
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) {
        document.getElementById('searchInput').value = decodeURIComponent(searchParam);
    }
});
</script>
@endsection
@endsection