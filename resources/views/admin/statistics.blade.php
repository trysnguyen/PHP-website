@extends('layouts.app')

@section('title', 'Library Statistics')

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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3><i class="bi bi-graph-up"></i> Library Statistics</h3>
                    <p class="mb-0">Overview of library operations and performance metrics.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-book" style="font-size: 1.5rem;"></i>
                    </div>
                    <h2 class="text-primary">{{ $totalBooks ?? 0 }}</h2>
                    <p class="text-muted mb-0">Total Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-cart-check" style="font-size: 1.5rem;"></i>
                    </div>
                    <h2 class="text-success">{{ $totalOrders ?? 0 }}</h2>
                    <p class="text-muted mb-0">Total Orders</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-clock" style="font-size: 1.5rem;"></i>
                    </div>
                    <h2 class="text-warning">{{ $pendingOrders ?? 0 }}</h2>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle" style="font-size: 1.5rem;"></i>
                    </div>
                    <h2 class="text-danger">{{ $acceptedOrders ?? 0 }}</h2>
                    <p class="text-muted mb-0">Accepted Orders</p>
                </div>
            </div>
        </div>
    </div>

    <!-- More Stats -->
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-arrow-return-left" style="font-size: 1.5rem;"></i>
                    </div>
                    <h2 class="text-info">{{ $returnedOrders ?? 0 }}</h2>
                    <p class="text-muted mb-0">Returned Orders</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-x-circle" style="font-size: 1.5rem;"></i>
                    </div>
                    <h2 class="text-secondary">{{ $refusedOrders ?? 0 }}</h2>
                    <p class="text-muted mb-0">Refused Orders</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-percent" style="font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="text-dark">
                        @if($totalOrders > 0)
                            {{ number_format(($acceptedOrders / $totalOrders) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </h4>
                    <p class="text-muted mb-0">Acceptance Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Books -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Most Popular Books</h5>
                </div>
                <div class="card-body">
                    @if(isset($popularBooks) && $popularBooks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Book Name</th>
                                    <th>Total Orders</th>
                                    <th>Popularity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularBooks as $index => $book)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning">🥇 1st</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">🥈 2nd</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger">🥉 3rd</span>
                                        @else
                                            <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $book->Bookname }}</strong></td>
                                    <td>{{ $book->total_orders }}</td>
                                    <td>
                                        @php
                                            $maxOrders = $popularBooks->first()->total_orders;
                                            $percentage = $maxOrders > 0 ? ($book->total_orders / $maxOrders) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $book->total_orders }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No order data available.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-month me-2"></i>Monthly Stats ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    @if(isset($monthlyStats) && $monthlyStats->count() > 0)
                    <div class="list-group">
                        @foreach($monthlyStats as $stat)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                {{ DateTime::createFromFormat('!m', $stat->month)->format('F') }}
                            </span>
                            <span class="badge bg-primary rounded-pill">{{ $stat->count }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No monthly data available.</p>
                    </div>
                    @endif
                    
                    <!-- Quick Stats -->
                    <div class="mt-4">
                        <h6>Quick Insights:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>Peak Month:</strong> 
                                @if(isset($monthlyStats) && $monthlyStats->count() > 0)
                                    @php
                                        $peakMonth = $monthlyStats->sortByDesc('count')->first();
                                    @endphp
                                    {{ DateTime::createFromFormat('!m', $peakMonth->month)->format('F') }} 
                                    ({{ $peakMonth->count }} orders)
                                @else
                                    N/A
                                @endif
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-info-circle text-info me-2"></i>
                                <strong>Avg Monthly:</strong> 
                                @if(isset($monthlyStats) && $monthlyStats->count() > 0)
                                    {{ number_format($monthlyStats->avg('count'), 1) }} orders
                                @else
                                    0
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Order Status Distribution</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusData = [
                            ['Pending', $pendingOrders ?? 0, 'warning'],
                            ['Accept', $acceptedOrders ?? 0, 'success'],
                            ['Refuse', $refusedOrders ?? 0, 'danger'],
                            ['Returned', $returnedOrders ?? 0, 'info']
                        ];
                        $total = array_sum(array_column($statusData, 1));
                    @endphp
                    
                    @if($total > 0)
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="statusChart" width="200" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3">
                                @foreach($statusData as $status)
                                @if($status[1] > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>
                                        <span class="badge bg-{{ $status[2] }} me-2">&nbsp;</span>
                                        {{ $status[0] }}
                                    </span>
                                    <span>
                                        {{ $status[1] }} 
                                        <small class="text-muted">
                                            ({{ $total > 0 ? number_format(($status[1] / $total) * 100, 1) : 0 }}%)
                                        </small>
                                    </span>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-pie-chart text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No order data available for chart.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Insights & Recommendations</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @if(($pendingOrders ?? 0) > 5)
                        <div class="list-group-item list-group-item-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Action Needed:</strong> {{ $pendingOrders }} pending orders need review.
                        </div>
                        @endif
                        
                        @if(($acceptedOrders ?? 0) > 10)
                        <div class="list-group-item list-group-item-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>High Demand:</strong> {{ $acceptedOrders }} books currently borrowed.
                        </div>
                        @endif
                        
                        @if(($returnedOrders ?? 0) < 1 && ($acceptedOrders ?? 0) > 0)
                        <div class="list-group-item list-group-item-secondary">
                            <i class="bi bi-clock-history me-2"></i>
                            <strong>Follow-up:</strong> No books returned yet. Check due dates.
                        </div>
                        @endif
                        
                        @if($totalOrders > 0)
                        <div class="list-group-item">
                            <i class="bi bi-graph-up-arrow me-2"></i>
                            <strong>Performance:</strong> 
                            {{ $totalOrders }} total orders processed.
                        </div>
                        @endif
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <h6>Quick Actions:</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-list-check me-1"></i>Review Pending Orders
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-speedometer2 me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Pie Chart for Order Status Distribution
    @if(isset($statusData) && $total > 0)
    var ctx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                @foreach($statusData as $status)
                    @if($status[1] > 0)
                        '{{ $status[0] }}',
                    @endif
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($statusData as $status)
                        @if($status[1] > 0)
                            {{ $status[1] }},
                        @endif
                    @endforeach
                ],
                backgroundColor: [
                    @foreach($statusData as $status)
                        @if($status[1] > 0)
                            @if($status[2] == 'warning')
                                'rgba(255, 193, 7, 0.8)',
                            @elseif($status[2] == 'success')
                                'rgba(25, 135, 84, 0.8)',
                            @elseif($status[2] == 'danger')
                                'rgba(220, 53, 69, 0.8)',
                            @elseif($status[2] == 'info')
                                'rgba(13, 202, 240, 0.8)',
                            @else
                                'rgba(108, 117, 125, 0.8)',
                            @endif
                        @endif
                    @endforeach
                ],
                borderColor: [
                    @foreach($statusData as $status)
                        @if($status[1] > 0)
                            'rgba(255, 255, 255, 1)',
                        @endif
                    @endforeach
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            var percentage = Math.round((value / {{ $total }}) * 100);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    @endif
    
    // Update current year in header
    var yearElement = document.querySelector('.current-year');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
});
</script>
@endsection