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

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #696cff!important;">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h5 class="fw-bold text-primary mb-0">{{ format_currency($summary['total_sales']) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #ff3e1d!important;">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">Total Purchases</p>
                    <h5 class="fw-bold text-danger mb-0">{{ format_currency($summary['total_purchases']) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100"
                style="border-left:4px solid {{ $netProfit >= 0 ? '#71dd37' : '#ff3e1d' }}!important;">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">Net Profit</p>
                    <h5 class="fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        {{ format_currency($netProfit) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #03c3ec!important;">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">Total Products</p>
                    <h5 class="fw-bold text-info mb-0">{{ $summary['total_products'] }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #ffab00!important;">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">Low Stock</p>
                    <h5 class="fw-bold text-warning mb-0">{{ $summary['low_stock'] }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #ff3e1d!important;">
                <div class="card-body py-3">
                    <p class="text-muted small mb-1">Out of Stock</p>
                    <h5 class="fw-bold text-danger mb-0">{{ $summary['out_of_stock'] }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Cards Grid --}}
    <div class="row g-4">

        {{-- Sales Report --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card" onclick="window.location='{{ route('reports.sales') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:52px;height:52px;background:linear-gradient(135deg,#696cff,#9c3fe4);">
                            <i class="bx bx-cart-alt text-white" style="font-size:1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Sales Report</h5>
                            <p class="text-muted small mb-3">Invoices, totals, payment status by date range & customer.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-primary">Date Filter</span>
                                <span class="badge bg-label-primary">Customer Filter</span>
                                <span class="badge bg-label-primary">Status Filter</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top py-2 px-4">
                    <a href="{{ route('reports.sales') }}" class="text-primary small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Purchase Report --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card"
                onclick="window.location='{{ route('reports.purchases') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:52px;height:52px;background:linear-gradient(135deg,#03c3ec,#0396bd);">
                            <i class="bx bx-cart-download text-white" style="font-size:1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Purchase Report</h5>
                            <p class="text-muted small mb-3">Purchase orders, supplier-wise spending by date range.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-info">Date Filter</span>
                                <span class="badge bg-label-info">Supplier Filter</span>
                                <span class="badge bg-label-info">Status Filter</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top py-2 px-4">
                    <a href="{{ route('reports.purchases') }}" class="text-info small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Profit & Loss --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card"
                onclick="window.location='{{ route('reports.profit-loss') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:52px;height:52px;background:linear-gradient(135deg,#71dd37,#3eb900);">
                            <i class="bx bx-trending-up text-white" style="font-size:1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Profit & Loss</h5>
                            <p class="text-muted small mb-3">Revenue vs COGS, gross profit, net profit & monthly trend.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-success">Revenue</span>
                                <span class="badge bg-label-success">COGS</span>
                                <span class="badge bg-label-success">Net Profit</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top py-2 px-4">
                    <a href="{{ route('reports.profit-loss') }}"
                        class="text-success small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Top Selling --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card"
                onclick="window.location='{{ route('reports.top-selling') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:52px;height:52px;background:linear-gradient(135deg,#ffab00,#e09400);">
                            <i class="bx bx-trophy text-white" style="font-size:1.5rem;"></i>
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
                <div class="card-footer bg-transparent border-top py-2 px-4">
                    <a href="{{ route('reports.top-selling') }}"
                        class="text-warning small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Stock Alert --}}
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100 border-0 report-card"
                onclick="window.location='{{ route('reports.stock-alert') }}'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:52px;height:52px;background:linear-gradient(135deg,#ff3e1d,#c0341a);">
                            <i class="bx bx-error text-white" style="font-size:1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Stock Alert Report</h5>
                            <p class="text-muted small mb-3">Products below alert threshold or out of stock with restock
                                value.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-danger">Out of Stock</span>
                                <span class="badge bg-label-warning">Low Stock</span>
                                <span class="badge bg-label-danger">Restock Value</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top py-2 px-4">
                    <a href="{{ route('reports.stock-alert') }}"
                        class="text-danger small fw-semibold text-decoration-none">
                        View Report <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .report-card {
            cursor: pointer;
            transition: transform .18s, box-shadow .18s;
        }

        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1) !important;
        }
    </style>
@endpush
