@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="container">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h3><i class="bi bi-list-check"></i> Orders Management</h3>
                    <p class="mb-0">Manage all book orders from students.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Pending Orders (Need Approval)</h5>
                </div>
                <div class="card-body">
                    @php
                        // QUAN TRỌNG: Dùng 'Accept' và 'Refuse' (ENUM values)
                        $pendingOrders = isset($orders) ? $orders->where('Status', 'Pending') : collect();
                    @endphp
                    
                    @if($pendingOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingOrders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>
                                        <strong>{{ $order->Studentname ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $order->StudentID ?? 'N/A' }}</small>
                                    </td>
                                    <td><strong>{{ $order->Bookname ?? 'N/A' }}</strong></td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <!-- QUAN TRỌNG: Dùng 'Accept' (ENUM value) -->
                                        <form action="{{ route('admin.update.order.status') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->OrderID }}">
                                            <input type="hidden" name="status" value="Accept"> <!-- ĐÚNG: 'Accept' -->
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Accept this order?')">
                                                <i class="bi bi-check-circle"></i> Accept
                                            </button>
                                        </form>
                                        <!-- QUAN TRỌNG: Dùng 'Refuse' (ENUM value) -->
                                        <form action="{{ route('admin.update.order.status') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->OrderID }}">
                                            <input type="hidden" name="status" value="Refuse"> <!-- ĐÚNG: 'Refuse' -->
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Refuse this order?')">
                                                <i class="bi bi-x-circle"></i> Refuse
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No pending orders.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Accepted Orders -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Accepted Orders (To be Returned)</h5>
                </div>
                <div class="card-body">
                    @php
                        // QUAN TRỌNG: Dùng 'Accept' (ENUM value)
                        $acceptedOrders = isset($orders) ? $orders->where('Status', 'Accept') : collect();
                    @endphp
                    
                    @if($acceptedOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Ordered Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acceptedOrders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>
                                        <strong>{{ $order->Studentname ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $order->StudentID ?? 'N/A' }}</small>
                                    </td>
                                    <td><strong>{{ $order->Bookname ?? 'N/A' }}</strong></td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('admin.mark.returned') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->OrderID }}">
                                            <button type="submit" class="btn btn-info btn-sm"
                                                    onclick="return confirm('Mark this book as returned?')">
                                                <i class="bi bi-arrow-return-left"></i> Mark Returned
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No accepted orders.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Refused Orders -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-x-circle"></i> Refused Orders</h5>
                </div>
                <div class="card-body">
                    @php
                        // QUAN TRỌNG: Dùng 'Refuse' (ENUM value)
                        $refusedOrders = isset($orders) ? $orders->where('Status', 'Refuse') : collect();
                    @endphp
                    
                    @if($refusedOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Order Date</th>
                                    <th>Status Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refusedOrders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>
                                        <strong>{{ $order->Studentname ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $order->StudentID ?? 'N/A' }}</small>
                                    </td>
                                    <td><strong>{{ $order->Bookname ?? 'N/A' }}</strong></td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-x-circle text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No refused orders.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Returned Orders -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-return-left"></i> Returned Orders</h5>
                </div>
                <div class="card-body">
                    @php
                        $returnedOrders = isset($orders) ? $orders->where('Status', 'Returned') : collect();
                    @endphp
                    
                    @if($returnedOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Order Date</th>
                                    <th>Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnedOrders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>
                                        <strong>{{ $order->Studentname ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $order->StudentID ?? 'N/A' }}</small>
                                    </td>
                                    <td><strong>{{ $order->Bookname ?? 'N/A' }}</strong></td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $order->ReturnedDate ? date('d/m/Y', strtotime($order->ReturnedDate)) : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-arrow-return-left text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No returned orders.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- All Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> All Orders ({{ $orders->count() ?? 0 }})</h5>
                </div>
                <div class="card-body">
                    @if(isset($orders) && $orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>
                                        {{ $order->Studentname ?? 'N/A' }}<br>
                                        <small class="text-muted">{{ $order->StudentID ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $order->Bookname ?? 'N/A' }}</td>
                                    <td>
                                        <!-- QUAN TRỌNG: Dùng đúng ENUM values -->
                                        <span class="badge 
                                            @if($order->Status == 'Pending') bg-warning
                                            @elseif($order->Status == 'Accept') bg-success
                                            @elseif($order->Status == 'Refuse') bg-danger
                                            @elseif($order->Status == 'Returned') bg-info
                                            @else bg-secondary @endif">
                                            {{ $order->Status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">No orders found.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Debug Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Orders Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="bg-warning p-3 rounded">
                                <h4 class="mb-0">{{ $pendingOrders->count() }}</h4>
                                <small>Pending</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="bg-success p-3 rounded text-white">
                                <h4 class="mb-0">{{ $acceptedOrders->count() }}</h4>
                                <small>Accepted</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="bg-danger p-3 rounded text-white">
                                <h4 class="mb-0">{{ $refusedOrders->count() }}</h4>
                                <small>Refused</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="bg-info p-3 rounded text-white">
                                <h4 class="mb-0">{{ $returnedOrders->count() }}</h4>
                                <small>Returned</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection