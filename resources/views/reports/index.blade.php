@extends('layouts.admin')
@section('title', 'Reports')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Reports</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Summary KPI Strip --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Revenue</p>
                        <h5 class="mb-0 fw-bold text-primary">{{ format_currency($summary['total_sales']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-trending-up"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Purchases</p>
                        <h5 class="mb-0 fw-bold text-danger">{{ format_currency($summary['total_purchases']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-cart-download"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Net Profit</p>
                        <h5 class="mb-0 fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ format_currency($netProfit) }}</h5>
                    </div>
                    <span
                        class="avatar-initial rounded-circle {{ $netProfit >= 0 ? 'bg-label-success' : 'bg-label-danger' }} p-3"
                        style="font-size:1rem;">
                        <i class="bx {{ $netProfit >= 0 ? 'bx-award' : 'bx-trending-down' }}"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Products</p>
                        <h5 class="mb-0 fw-bold text-info">{{ number_format($summary['total_products']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;">
                        <i class="bx bx-package"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Low Stock</p>
                        <h5 class="mb-0 fw-bold text-warning">{{ $summary['low_stock'] }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Out of Stock</p>
                        <h5 class="mb-0 fw-bold text-danger">{{ $summary['out_of_stock'] }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Cards --}}
    <div class="row g-4">

        {{-- Sales Report --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:54px;height:54px;background:linear-gradient(135deg,#696cff,#9c3fe4);">
                            <i class="bx bx-cart-alt text-white" style="font-size:1.6rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Sales Report</h5>
                            <p class="text-muted small mb-3">Invoices, totals, payment status by date range &amp; customer.
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-primary">Date Filter</span>
                                <span class="badge bg-label-primary">Customer</span>
                                <span class="badge bg-label-primary">Status</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.sales') }}" class="text-primary small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">Invoices &amp; totals</span>
                </div>
            </div>
        </div>

        {{-- Purchase Report --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:54px;height:54px;background:linear-gradient(135deg,#03c3ec,#0396bd);">
                            <i class="bx bx-cart-download text-white" style="font-size:1.6rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Purchase Report</h5>
                            <p class="text-muted small mb-3">Purchase orders, supplier-wise spending by date range.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-info">Date Filter</span>
                                <span class="badge bg-label-info">Supplier</span>
                                <span class="badge bg-label-info">Status</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.purchases') }}" class="text-info small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">Orders &amp; spending</span>
                </div>
            </div>
        </div>

        {{-- Profit & Loss --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:54px;height:54px;background:linear-gradient(135deg,#71dd37,#3eb900);">
                            <i class="bx bx-trending-up text-white" style="font-size:1.6rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Profit &amp; Loss</h5>
                            <p class="text-muted small mb-3">Revenue vs COGS, gross profit, net profit &amp; monthly trend.
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-success">Revenue</span>
                                <span class="badge bg-label-success">COGS</span>
                                <span class="badge bg-label-success">Net Profit</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.profit-loss') }}"
                        class="text-success small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">P&amp;L Statement</span>
                </div>
            </div>
        </div>

        {{-- Top Selling --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:54px;height:54px;background:linear-gradient(135deg,#ffab00,#e09400);">
                            <i class="bx bx-trophy text-white" style="font-size:1.6rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Top Selling Products</h5>
                            <p class="text-muted small mb-3">Rank products by qty sold, revenue, or profit with date
                                filter.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-warning">By Qty</span>
                                <span class="badge bg-label-warning">By Revenue</span>
                                <span class="badge bg-label-warning">By Profit</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.top-selling') }}"
                        class="text-warning small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">Product rankings</span>
                </div>
            </div>
        </div>

        {{-- Stock Alert --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:54px;height:54px;background:linear-gradient(135deg,#ff3e1d,#c0341a);">
                            <i class="bx bx-error text-white" style="font-size:1.6rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Stock Alert Report</h5>
                            <p class="text-muted small mb-3">Products below alert threshold or out of stock with restock
                                value.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-danger">Out of Stock</span>
                                <span class="badge bg-label-warning">Low Stock</span>
                                @if ($summary['out_of_stock'] > 0 || $summary['low_stock'] > 0)
                                    <span class="badge bg-danger">{{ $summary['out_of_stock'] + $summary['low_stock'] }}
                                        Alerts</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.stock-alert') }}"
                        class="text-danger small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">Restock alerts</span>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <style>
        .report-card {
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(105, 108, 255, .12) !important;
        }
    </style>
@endpush
