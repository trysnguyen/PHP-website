@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="container">
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
                        $pendingOrders = $orders->where('Status', 'Pending') ?? collect();
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
                                        <strong>{{ $order->Studentname }}</strong><br>
                                        <small class="text-muted">{{ $order->StudentID }}</small>
                                    </td>
                                    <td><strong>{{ $order->Bookname }}</strong></td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.update.order.status') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->OrderID }}">
                                            <input type="hidden" name="status" value="Accept">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Accept this order?')">
                                                <i class="bi bi-check-circle"></i> Accept
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.update.order.status') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->OrderID }}">
                                            <input type="hidden" name="status" value="Refuse">
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
                    <p class="text-muted text-center">No pending orders.</p>
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
                        $acceptedOrders = $orders->where('Status', 'Accept') ?? collect();
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
                                    <th>Return Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acceptedOrders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>
                                        <strong>{{ $order->Studentname }}</strong><br>
                                        <small class="text-muted">{{ $order->StudentID }}</small>
                                    </td>
                                    <td><strong>{{ $order->Bookname }}</strong></td>
                                    <td>{{ $order->OrderedDate ? date('d/m/Y', strtotime($order->OrderedDate)) : 'N/A' }}</td>
                                    <td>{{ $order->ReturnedDate ? date('d/m/Y', strtotime($order->ReturnedDate)) : 'N/A' }}</td>
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
                    <p class="text-muted text-center">No accepted orders.</p>
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
                    <h5 class="mb-0"><i class="bi bi-table"></i> All Orders</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th>Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->OrderID }}</td>
                                    <td>{{ $order->Studentname }}<br><small>{{ $order->StudentID }}</small></td>
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
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($order->ReturnedDate)
                                            {{ date('d/m/Y', strtotime($order->ReturnedDate)) }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
