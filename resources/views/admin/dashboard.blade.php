@extends('layouts.admin')
@section('title', __('admin.dashboard'))

@push('styles')
    <style>
        .stats-card-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            border-radius: 50%;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }
        }

        .stat-border-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }

        .stat-border-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    {{-- ── Welcome Banner ── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card overflow-hidden" style="background: linear-gradient(135deg,#696cff 0%,#9155fd 100%);">
                <div class="d-flex align-items-center row">
                    <div class="col-sm-7">
                        <div class="card-body text-white py-4">
                            <h4 class="text-white mb-1">{{ __('admin.welcome_back', ['name' => auth()->user()->name]) }}</h4>
                            <p class="mb-3" style="opacity:.8;">{{ __('admin.store_overview') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.order.index') }}" class="btn btn-light btn-sm">
                                    <i class="bx bx-cart-alt me-1"></i> {{ __('admin.orders') }}
                                </a>
                                <a href="{{ route('admin.product.create') }}" class="btn btn-outline-light btn-sm">
                                    <i class="bx bx-plus me-1"></i> {{ __('admin.add') }} {{ __('admin.products') }}
                                </a>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-light btn-sm">
                                    <i class="bx bx-group me-1"></i> {{ __('admin.users') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- Live Date & Time --}}
                    <div class="col-sm-5 d-none d-sm-flex align-items-center justify-content-center py-3">
                        <div class="text-center text-white" id="dashboardClock">
                            <div id="dashClock-time"
                                style="font-size:2.6rem;font-weight:700;letter-spacing:.03em;line-height:1.1;font-variant-numeric:tabular-nums;">
                                --:--:--
                            </div>
                            <div id="dashClock-ampm"
                                style="font-size:1rem;font-weight:600;opacity:.75;margin-top:2px;letter-spacing:.1em;">
                                --
                            </div>
                            <div id="dashClock-date" style="font-size:.9rem;opacity:.8;margin-top:6px;font-weight:500;">
                                -- -- ----
                            </div>
                            <div id="dashClock-day"
                                style="font-size:.78rem;opacity:.6;margin-top:2px;letter-spacing:.08em;text-transform:uppercase;">
                                --------
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Today Stats ── --}}
    <div class="row mb-2">
        <div class="col-12">
            <p class="text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em;">{{ __('admin.today') }}
            </p>
        </div>
        <div class="col-6 col-xl-3 mb-4">
            <div class="card stat-border-card border-primary h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('admin.todays_orders') }}</p>
                        <h3 class="mb-0 text-primary">{{ $todayOrders }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-primary">
                        <i class="bx bx-cart-alt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3 mb-4">
            <div class="card stat-border-card border-success h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('admin.todays_revenue') }}</p>
                        <h3 class="mb-0 text-success">₹{{ number_format($todayRevenue, 0) }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-success">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3 mb-4">
            <div class="card stat-border-card border-warning h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('admin.pending_orders') }}</p>
                        <h3 class="mb-0 text-warning">{{ $pendingOrders }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-warning">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3 mb-4">
            <div class="card stat-border-card border-danger h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('admin.low_stock_products') }}</p>
                        <h3 class="mb-0 text-danger">{{ $lowStockProducts->count() }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-danger">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Stats Cards ── --}}
    <div class="row mb-4">
        <div class="col-12">
            <p class="text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em;">{{ __('admin.overall') }}
            </p>
        </div>

        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('admin.total_revenue') }}</p>
                            <h4 class="mb-1 fw-bold">₹{{ number_format($totalRevenue, 0) }}</h4>
                            <small class="text-success"><i class="bx bx-trending-up"></i>
                                {{ __('admin.from_paid_orders') }}</small>
                        </div>
                        <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.5rem;">
                            <i class="bx bx-rupee"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('admin.total_orders') }}</p>
                            <h4 class="mb-1 fw-bold">{{ $totalOrders }}</h4>
                            <small class="text-success"><i class="bx bx-check-circle"></i> {{ $confirmedOrders }}
                                {{ __('admin.paid') }}</small>
                        </div>
                        <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.5rem;">
                            <i class="bx bx-cart-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('admin.total_products') }}</p>
                            <h4 class="mb-1 fw-bold">{{ $totalProducts }}</h4>
                            <small class="text-danger"><i class="bx bx-error-circle"></i> {{ $lowStockProducts->count() }}
                                {{ __('admin.low_stock_label') }}</small>
                        </div>
                        <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.5rem;">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('admin.total_users') }}</p>
                            <h4 class="mb-1 fw-bold">{{ $totalUsers }}</h4>
                            <small class="text-primary"><i class="bx bx-user-check"></i>
                                {{ __('admin.registered') }}</small>
                        </div>
                        <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.5rem;">
                            <i class="bx bx-group"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Charts Row ── --}}
    <div class="row mb-4">

        {{-- Revenue + Orders Line Chart --}}
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ __('admin.revenue_orders') }}</h5>
                        <small class="text-muted">{{ __('admin.last_6_months') }}</small>
                    </div>
                    <div class="d-flex gap-3">
                        <span><span
                                class="badge bg-primary me-1">&nbsp;</span><small>{{ __('admin.revenue') }}</small></span>
                        <span><span
                                class="badge bg-warning me-1">&nbsp;</span><small>{{ __('admin.orders') }}</small></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Status Doughnut --}}
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('admin.order_breakdown') }}</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <canvas id="orderStatusChart" height="180" style="max-width:180px;"></canvas>
                    <div class="w-100 mt-3">
                        @php
                            $statusList = [
                                ['label' => __('admin.pending'), 'count' => $pendingOrders, 'color' => 'bg-warning'],
                                ['label' => __('admin.paid'), 'count' => $confirmedOrders, 'color' => 'bg-success'],
                                ['label' => __('admin.failed'), 'count' => $cancelledOrders, 'color' => 'bg-danger'],
                            ];
                        @endphp
                        @foreach ($statusList as $s)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small"><span
                                        class="badge {{ $s['color'] }} me-1">&nbsp;</span>{{ $s['label'] }}</span>
                                <strong class="small">{{ $s['count'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent Orders + Top Products ── --}}
    <div class="row mb-4">

        {{-- Recent Orders --}}
        <div class="col-xl-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.recent_orders') }}</h5>
                    <a href="{{ route('admin.order.index') }}"
                        class="btn btn-sm btn-outline-primary">{{ __('admin.view_all') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('admin.order_number') }}</th>
                                <th>{{ __('admin.customer') }}</th>
                                <th>{{ __('admin.amount') }}</th>
                                <th>{{ __('admin.payment') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong class="text-primary">{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="user-avatar bg-label-primary me-2">
                                                {{ strtoupper(substr($order->user->name ?? 'N', 0, 1)) }}
                                            </span>
                                            <span class="text-truncate"
                                                style="max-width: 150px;">{{ $order->user->name ?? __('admin.na') }}</span>
                                        </div>
                                    </td>
                                    <td><strong>₹{{ number_format($order->total_amount, 0) }}</strong></td>
                                    <td>
                                        @php $ps = $order->payment_status; @endphp
                                        <span
                                            class="badge {{ $ps == 'paid' ? 'bg-success' : ($ps == 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($ps) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php $oc = ['active' => 'bg-success', 'inactive' => 'bg-secondary']; @endphp
                                        <span class="badge {{ $oc[$order->status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $order->created_at->format('d M') }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_orders') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.top_products') }}</h5>
                    <a href="{{ route('admin.product.index') }}"
                        class="btn btn-sm btn-outline-primary">{{ __('admin.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topProducts as $i => $product)
                            <li class="list-group-item d-flex align-items-center px-4 py-3">
                                <span class="badge bg-label-primary me-3"
                                    style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                                    {{ $i + 1 }}
                                </span>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate">{{ $product->name }}</h6>
                                    <small class="text-muted">₹{{ number_format($product->price, 0) }}</small>
                                </div>
                                <span class="badge bg-label-success ms-2">{{ $product->order_items_count }}
                                    {{ __('admin.sold') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">{{ __('admin.no_data') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Quick Stats Summary ── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ __('admin.quick_stats') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ \App\Models\MainCategory::count() }}</h4>
                                <small class="text-muted">{{ __('admin.categories') }}</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ \App\Models\Brand::count() }}</h4>
                                <small class="text-muted">{{ __('admin.brands') }}</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border-end">
                                <h4 class="text-warning mb-1">{{ \App\Models\Coupon::where('status', 'active')->count() }}
                                </h4>
                                <small class="text-muted">{{ __('admin.active_coupons') }}</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <h4 class="text-info mb-1">{{ \App\Models\Reviews::where('status', 'active')->count() }}</h4>
                            <small class="text-muted">{{ __('admin.total_reviews') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Recent Users + Low Stock + Reviews ── --}}
    <div class="row">

        {{-- Recent Users --}}
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.recent_users') }}</h5>
                    <a href="{{ route('admin.user.index') }}"
                        class="btn btn-sm btn-outline-primary">{{ __('admin.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentUsers as $user)
                            <li class="list-group-item d-flex align-items-center px-4 py-3">
                                @if ($user->image)
                                    <img src="{{ asset($user->image) }}" class="rounded-circle me-3" width="36"
                                        height="36" style="object-fit:cover;" alt="{{ $user->name }}">
                                @else
                                    <span class="user-avatar bg-label-primary me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                @endif
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate">{{ $user->name }}</h6>
                                    <small
                                        class="text-muted text-truncate d-block">{{ $user->role->name ?? __('admin.users') }}</small>
                                </div>
                                <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->status ? __('admin.active') : __('admin.inactive') }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">{{ __('admin.no_users') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.low_stock') }}</h5>
                    <a href="{{ route('admin.stock.index') }}"
                        class="btn btn-sm btn-outline-danger">{{ __('admin.manage') }}</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lowStockProducts as $product)
                            <li class="list-group-item d-flex align-items-center px-4 py-3">
                                <span class="avatar-initial rounded bg-label-danger me-3"
                                    style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:8px;">
                                    <i class="bx bx-package"></i>
                                </span>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate">{{ $product->name }}</h6>
                                    <small class="text-muted">₹{{ number_format($product->price, 0) }}</small>
                                </div>
                                <span class="badge bg-danger ms-2">{{ $product->quantity }} {{ __('admin.left') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-success py-4">
                                <i class="bx bx-check-circle fs-4"></i><br>{{ __('admin.all_in_stock') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Recent Reviews --}}
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('admin.recent_reviews') }}</h5>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="btn btn-sm btn-outline-primary">{{ __('admin.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentReviews as $review)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 text-truncate" style="max-width:60%;">
                                        {{ $review->product->name ?? __('admin.na') }}</h6>
                                    <div>
                                        @for ($s = 1; $s <= 5; $s++)
                                            <i class="bx {{ $s <= $review->rating ? 'bxs-star text-warning' : 'bx-star text-muted' }}"
                                                style="font-size:.75rem;"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ Str::limit($review->comment, 60) }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">{{ __('admin.no_reviews') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue & Orders Line Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [{
                        label: '{{ __('admin.revenue') }} (₹)',
                        data: {!! json_encode($monthlyRevenue) !!},
                        borderColor: '#696cff',
                        backgroundColor: 'rgba(105,108,255,0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#696cff',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: '{{ __('admin.orders') }}',
                        data: {!! json_encode($monthlyOrders) !!},
                        borderColor: '#ffab00',
                        backgroundColor: 'rgba(255,171,0,0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffab00',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        // Order Status Doughnut Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['{{ __('admin.pending') }}', '{{ __('admin.paid') }}', '{{ __('admin.failed') }}'],
                datasets: [{
                    data: [{{ $pendingOrders }}, {{ $confirmedOrders }}, {{ $cancelledOrders }}],
                    backgroundColor: ['#ffab00', '#71dd37', '#ff3e1d'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                }
            }
        });
    </script>

    {{-- ── Dashboard Live Clock ── --}}
    <script>
        (function() {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tick() {
                const now = new Date();
                let h = now.getHours();
                const m = now.getMinutes();
                const s = now.getSeconds();
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;

                const timeEl = document.getElementById('dashClock-time');
                const ampmEl = document.getElementById('dashClock-ampm');
                const dateEl = document.getElementById('dashClock-date');
                const dayEl = document.getElementById('dashClock-day');

                if (!timeEl) return;

                timeEl.textContent = pad(h) + ':' + pad(m) + ':' + pad(s);
                ampmEl.textContent = ampm;
                dateEl.textContent = pad(now.getDate()) + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
                dayEl.textContent = days[now.getDay()];
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
@endpush
