@extends('layouts.admin')
@section('title', __('messages.menu_dashboard'))

@push('styles')
    <style>
        .stats-card-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .stat-border-card {
            border-left: 4px solid;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-border-card:hover,
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .1);
        }

        .kpi-card {
            transition: transform .2s, box-shadow .2s;
        }

        .section-label {
            font-size: .72rem;
            letter-spacing: .09em;
            font-weight: 600;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #696cff 0%, #9155fd 100%);
        }

        #dashClock-time {
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: .03em;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }
    </style>
@endpush

@section('content')

    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card overflow-hidden welcome-banner">
                <div class="d-flex align-items-center row">
                    <div class="col-sm-7">
                        <div class="card-body text-white py-4">
                            <h4 class="text-white mb-1">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }} 👋</h4>
                            <p class="mb-3" style="opacity:.82;">{{ __('messages.store_overview') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                @can('purchases.create')
                                    <a href="{{ route('purchases.create') }}" class="btn btn-light btn-sm">
                                        <i class="bx bx-cart-download me-1"></i>{{ __('messages.purchases') }}
                                    </a>
                                @endcan
                                @can('sales.create')
                                    <a href="{{ route('sales.create') }}" class="btn btn-outline-light btn-sm">
                                        <i class="bx bx-plus me-1"></i>{{ __('messages.add_sale') ?? __('messages.sales') }}
                                    </a>
                                @endcan
                                @can('users.create')
                                    <a href="{{ route('users.create') }}" class="btn btn-outline-light btn-sm">
                                        <i class="bx bx-group me-1"></i>{{ __('messages.users') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 d-none d-sm-flex align-items-center justify-content-center py-3">
                        <div class="text-center text-white" id="dashboardClock">
                            <div id="dashClock-time">--:--:--</div>
                            <div id="dashClock-ampm"
                                style="font-size:1rem;font-weight:600;opacity:.75;margin-top:2px;letter-spacing:.1em;">--
                            </div>
                            <div id="dashClock-date" style="font-size:.9rem;opacity:.82;margin-top:6px;font-weight:500;">--
                                -- ----</div>
                            <div id="dashClock-day"
                                style="font-size:.76rem;opacity:.6;margin-top:2px;letter-spacing:.08em;text-transform:uppercase;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('messages.total_products') }}</p>
                        <h3 class="mb-0 fw-bold text-primary">{{ $totalProducts ?? 0 }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-primary"><i class="bx bx-package"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('messages.customers') }}</p>
                        <h3 class="mb-0 fw-bold text-success">{{ $totalCustomers ?? 0 }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-success"><i class="bx bx-user"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('messages.suppliers') }}</p>
                        <h3 class="mb-0 fw-bold text-warning">{{ $totalSuppliers ?? 0 }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-warning"><i class="bx bx-buildings"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="mb-1 text-muted small">{{ __('messages.total_users') }}</p>
                        <h3 class="mb-0 fw-bold text-info">{{ $totalUsers ?? 0 }}</h3>
                    </div>
                    <span class="stats-card-icon bg-label-info"><i class="bx bx-group"></i></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Today Stats --}}
    @if (auth()->user()->can('sales.view') || auth()->user()->can('purchases.view'))
        <p class="section-label text-muted mb-2">{{ strtoupper(__('messages.today')) }}</p>
        <div class="row mb-4">
            @can('purchases.view')
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card stat-border-card border-primary h-100">
                        <div class="card-body py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.todays_purchases') }}</p>
                                <h3 class="mb-0 text-primary fw-bold">{{ $todayPurchases ?? 0 }}</h3>
                                <small class="text-muted">{{ __('messages.all_time') }}</small>
                            </div>
                            <span class="stats-card-icon bg-label-primary"><i class="bx bx-cart-download"></i></span>
                        </div>
                    </div>
                </div>
            @endcan
            @can('sales.view')
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card stat-border-card border-success h-100">
                        <div class="card-body py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.todays_sales') }}</p>
                                <h3 class="mb-0 text-success fw-bold">{{ format_currency($todaySales ?? 0) }}</h3>
                                <small class="text-muted">{{ __('messages.from_sales') }}</small>
                            </div>
                            <span class="stats-card-icon bg-label-success"><i class="bx bx-trending-up"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="card stat-border-card border-warning h-100">
                        <div class="card-body py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.pending_sales') }}</p>
                                <h3 class="mb-0 text-warning fw-bold">{{ $pendingSales ?? 0 }}</h3>
                                <small class="text-muted">{{ __('messages.draft') }}</small>
                            </div>
                            <span class="stats-card-icon bg-label-warning"><i class="bx bx-time-five"></i></span>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    @endif

    {{-- Overall Financial --}}
    @can('sales.view')
        <p class="section-label text-muted mb-2">{{ strtoupper(__('messages.overall')) }}</p>
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card kpi-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('messages.total_revenue') }}</p>
                            <h4 class="mb-1 fw-bold">{{ format_currency($totalRevenue ?? 0) }}</h4>
                            <small class="text-success"><i class="bx bx-check-circle"></i>
                                {{ __('messages.from_sales') }}</small>
                        </div>
                        <span class="stats-card-icon bg-label-success" style="width:52px;height:52px;font-size:1.6rem;">
                            <i class="bx bx-dollar-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
            @can('purchases.view')
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card kpi-card h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.total_purchases') }}</p>
                                <h4 class="mb-1 fw-bold">{{ $totalPurchases ?? 0 }}</h4>
                                <small class="text-info"><i class="bx bx-cart"></i> {{ __('messages.all_time') }}</small>
                            </div>
                            <span class="stats-card-icon bg-label-info" style="width:52px;height:52px;font-size:1.6rem;">
                                <i class="bx bx-cart-download"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="col-md-4 col-sm-12 mb-3">
                <div class="card kpi-card h-100">
                    <div class="card-body">
                        <p class="mb-2 text-muted small fw-semibold">{{ __('messages.quick_stats') }}</p>
                        <div class="row text-center g-0">
                            <div class="col-6" style="border-right:1px solid #e9ecef;">
                                <h5 class="text-primary mb-0 fw-bold">{{ $totalCategories ?? 0 }}</h5>
                                <small class="text-muted">{{ __('messages.categories') }}</small>
                            </div>
                            <div class="col-6">
                                <h5 class="text-success mb-0 fw-bold">{{ $totalBrands ?? 0 }}</h5>
                                <small class="text-muted">{{ __('messages.brands') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Row 1: This Week Chart + Top Selling Products --}}
    @if (auth()->user()->can('sales.view') && auth()->user()->can('purchases.view'))
        <div class="row mb-4">
            {{-- This Week Sales & Purchases Bar Chart --}}
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="card h-100">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-bar-chart-alt-2 me-1 text-primary"></i> This Week Sales &amp; Purchases
                        </h6>
                    </div>
                    <div class="card-body" style="position:relative;">
                        <canvas id="weekChart" style="width:100%;height:260px;"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top Selling Products Pie --}}
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-pie-chart-alt-2 me-1 text-success"></i>
                            Top Selling Products ({{ now()->format('F') }})
                        </h6>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height:280px;">
                        @if (isset($topProducts) && $topProducts->count())
                            <canvas id="topProductsChart"></canvas>
                        @else
                            <div class="text-center text-muted">
                                <i class="bx bx-package fs-1 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 small">No sales data this month</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Row 2: Top 5 Customers Pie + Recent Sales Table --}}
    @if (auth()->user()->can('sales.view') || auth()->user()->can('customers.view'))
        <div class="row mb-4">
            {{-- Top 5 Customers Pie --}}
            @can('customers.view')
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-pie-chart me-1 text-info"></i>
                                Top 5 Customers ({{ now()->format('F') }})
                            </h6>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center" style="min-height:290px;">
                            @if (isset($topCustomers) && $topCustomers->count())
                                <canvas id="topCustomersChart"></canvas>
                            @else
                                <div class="text-center text-muted">
                                    <i class="bx bx-user fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0 small">No customer sales this month</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endcan

            {{-- Recent Sales Table --}}
            @can('sales.view')
                <div class="{{ auth()->user()->can('customers.view') ? 'col-lg-7' : 'col-12' }}">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-receipt me-1 text-primary"></i> {{ __('messages.recent_sales') }}
                            </h6>
                            <a href="{{ route('sales.index') }}"
                                class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.th_invoice') }}</th>
                                        <th>{{ __('messages.th_customer') }}</th>
                                        <th>{{ __('messages.th_total') }}</th>
                                        <th>{{ __('messages.th_status') }}</th>
                                        <th>{{ __('messages.th_date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales ?? [] as $sale)
                                        <tr>
                                            <td><strong class="text-primary">{{ $sale->invoice_no }}</strong></td>
                                            <td>{{ $sale->customer->name ?? '-' }}</td>
                                            <td><strong>{{ format_currency($sale->grand_total) }}</strong></td>
                                            <td>
                                                @if ($sale->status === 'Completed')
                                                    <span
                                                        class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                                @elseif($sale->status === 'Draft')
                                                    <span
                                                        class="badge rounded-pill bg-warning text-dark">{{ __('messages.draft') }}</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-danger">{{ __('messages.cancelled') }}</span>
                                                @endif
                                            </td>
                                            <td><small class="text-muted">{{ $sale->invoice_date }}</small></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bx bx-receipt fs-2 d-block mb-1 opacity-50"></i>
                                                {{ __('messages.no_recent_sales') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    @endif

    {{-- Row 3: Stock Alert Table (full width) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-error-circle me-1 text-danger"></i> {{ __('messages.low_stock_alert') }}
                    </h6>
                    <a href="{{ route('stocks.index') }}"
                        class="btn btn-sm btn-outline-danger">{{ __('messages.manage') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Code</th>
                                <th>{{ __('messages.th_alert_level') }}</th>
                                <th>{{ __('messages.quantity') }}</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts ?? [] as $product)
                                @php $qty = $product->stock->quantity ?? 0; @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="rounded"
                                                    style="width:32px;height:32px;object-fit:cover;" alt="">
                                            @else
                                                <span class="stats-card-icon bg-label-secondary"
                                                    style="width:32px;height:32px;font-size:.85rem;flex-shrink:0;">
                                                    <i class="bx bx-package"></i>
                                                </span>
                                            @endif
                                            <span class="fw-semibold text-truncate"
                                                style="max-width:200px;">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td><small class="text-muted">{{ $product->code ?? 'N/A' }}</small></td>
                                    <td><span class="text-muted">{{ $product->minimum_stock_alert ?? 0 }}</span></td>
                                    <td><strong>{{ $qty }}</strong></td>
                                    <td>
                                        @if ($qty <= 0)
                                            <span class="badge bg-danger">{{ __('messages.out_of_stock') }}</span>
                                        @else
                                            <span
                                                class="badge bg-warning text-dark">{{ __('messages.low_stock_badge') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bx bx-check-shield fs-2 text-success d-block mb-1"></i>
                                        {{ __('messages.all_stock_healthy') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    @can('activity_logs.view')
        @if ($recentActivities->count())
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-history me-1 text-secondary"></i> Recent Activity
                                @if (auth()->user()->can('activity_logs.own') && !auth()->user()->getRoleNames()->contains('Super Admin'))
                                    <span class="badge bg-label-info ms-1 small">{{ __('messages.own') ?? 'Own' }}</span>
                                @endif
                            </h6>
                            <a href="{{ route('activity-logs.index') }}"
                                class="btn btn-sm btn-outline-secondary">{{ __('messages.view_all') }}</a>
                        </div>
                        <div class="card-body py-2">
                            @foreach ($recentActivities as $log)
                                <div class="d-flex gap-3 py-2 border-bottom">
                                    <div
                                        style="width:8px;height:8px;border-radius:50%;background:#696cff;flex-shrink:0;margin-top:6px;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0" style="font-size:.875rem;">
                                            <strong>{{ $log->user->name ?? __('messages.system') }}</strong>
                                            — {{ $log->activity ?? ($log->description ?? '') }}
                                        </p>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

@endsection

@push('scripts')
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // ── Live Clock ────────────────────────────────────────────
        (function() {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tick() {
                const now = new Date();
                let h = now.getHours();
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                const t = document.getElementById('dashClock-time');
                const a = document.getElementById('dashClock-ampm');
                const d = document.getElementById('dashClock-date');
                const w = document.getElementById('dashClock-day');
                if (t) t.textContent = pad(h) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
                if (a) a.textContent = ampm;
                if (d) d.textContent = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
                if (w) w.textContent = days[now.getDay()];
            }
            tick();
            setInterval(tick, 1000);
        })();

        // ── Shared Chart Defaults ─────────────────────────────────
        Chart.defaults.font.family = "'Public Sans', sans-serif";
        Chart.defaults.plugins.legend.position = 'right';

        // ── 1. This Week Sales & Purchases (Bar) ──────────────────
        (function() {
            const ctx = document.getElementById('weekChart');
            if (!ctx) return;
            const labels = @json($weekDates ?? []);
            const salesD = @json($weekSalesData ?? []);
            const purchD = @json($weekPurchasesData ?? []);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: 'Sales',
                            data: salesD,
                            backgroundColor: 'rgba(105,108,255,0.7)',
                            borderColor: '#696cff',
                            borderWidth: 1,
                            borderRadius: 4,
                        },
                        {
                            label: 'Purchases',
                            data: purchD,
                            backgroundColor: 'rgba(3,195,236,0.7)',
                            borderColor: '#03c3ec',
                            borderWidth: 1,
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + Number(ctx.parsed.y).toLocaleString()
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => {
                                    if (v >= 1e6) return '$ ' + (v / 1e6).toFixed(1) + 'M';
                                    if (v >= 1e3) return '$ ' + (v / 1e3).toFixed(0) + 'K';
                                    return '$ ' + v;
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        })();

        // ── 2. Top Selling Products Pie ───────────────────────────
        (function() {
            const ctx = document.getElementById('topProductsChart');
            if (!ctx) return;

            const labels = @json(($topProducts ?? collect())->map(fn($i) => $i->product->name ?? 'Unknown')->values());
            const data = @json(($topProducts ?? collect())->pluck('total_qty')->values());
            const colors = ['#696cff', '#71dd37', '#ffab00', '#ff3e1d', '#03c3ec', '#8592a3'];

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: colors.slice(0, data.length),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 14,
                                padding: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' units'
                            }
                        }
                    }
                }
            });
        })();

        // ── 3. Top 5 Customers Pie ────────────────────────────────
        (function() {
            const ctx = document.getElementById('topCustomersChart');
            if (!ctx) return;

            const labels = @json(($topCustomers ?? collect())->map(fn($s) => $s->customer->name ?? 'Unknown')->values());
            const data = @json(($topCustomers ?? collect())->pluck('total_spent')->map(fn($v) => (float) $v)->values());
            const colors = ['#696cff', '#71dd37', '#ffab00', '#ff3e1d', '#03c3ec'];

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: colors.slice(0, data.length),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 14,
                                padding: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' + Number(ctx.parsed).toLocaleString()
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endpush
