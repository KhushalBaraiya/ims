@extends('layouts.admin')
@section('title', __('messages.dashboard'))

@push('styles')
    <style>
        /* ── Welcome Banner ─────────────────────────── */
        .dash-banner {
            background: linear-gradient(135deg, #696cff 0%, #9155fd 55%, #c053d8 100%);
            border-radius: 14px;
            overflow: hidden;
            position: relative;
        }

        .dash-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
            pointer-events: none;
        }

        .dash-banner::after {
            content: '';
            position: absolute;
            bottom: -70px;
            right: 120px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        /* ── KPI Cards ─────────────────────────────── */
        .kpi-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            transition: transform .2s, box-shadow .2s;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .13);
        }

        .kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .kpi-accent {
            height: 3px;
            border-radius: 12px 12px 0 0;
        }

        /* ── Section Header ─────────────────────────── */
        .sec-hd {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #8592a3;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: .85rem;
        }

        .sec-hd::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(0, 0, 0, .06);
        }

        [data-bs-theme="dark"] .sec-hd::after {
            background: rgba(255, 255, 255, .08);
        }

        /* ── Inner card ─────────────────────────────── */
        .inner-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        /* ── Table ──────────────────────────────────── */
        .d-tbl thead th {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #8592a3;
            padding: .55rem .85rem;
            border-bottom: 1px solid #eef0f8;
            background: #f8f9ff;
        }

        [data-bs-theme="dark"] .d-tbl thead th {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .08);
        }

        .d-tbl tbody td {
            padding: .55rem .85rem;
            font-size: .82rem;
            vertical-align: middle;
        }

        .d-tbl tbody tr:hover td {
            background: rgba(105, 108, 255, .025);
        }

        /* ── Status pills ───────────────────────────── */
        .s-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .7rem;
            font-weight: 700;
            padding: .2rem .55rem;
            border-radius: 99px;
        }

        /* ── Activity feed ──────────────────────────── */
        .act-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #696cff;
            flex-shrink: 0;
            margin-top: 5px;
        }

        /* ── Clock ──────────────────────────────────── */
        #dashClock {
            font-variant-numeric: tabular-nums;
        }

        #dashClock-time {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.1;
        }
    </style>
@endpush

@section('content')
    @php
        $sym = optional(current_currency())->symbol ?? '₹';
        $user = auth()->user();
    @endphp

    {{-- ════ WELCOME BANNER ════ --}}
    <div class="dash-banner card border-0 mb-4">
        <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
            style="position:relative;z-index:1;">
            <div>
                <h4 class="fw-bold text-white mb-1">
                    👋 {{ __('messages.welcome_back') }}, {{ $user->name }}!
                </h4>
                <p class="text-white mb-3" style="opacity:.82;font-size:.88rem;">
                    {{ now()->format('l, d F Y') }} &nbsp;·&nbsp; {{ __('messages.store_overview') }}
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @can('purchases.create')
                        <a href="{{ route('purchases.create') }}" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bx bx-cart-download me-1"></i> {{ __('messages.add_purchase') }}
                        </a>
                    @endcan
                    @can('sales.create')
                        <a href="{{ route('sales.create') }}" class="btn btn-sm fw-semibold"
                            style="background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3);">
                            <i class="bx bx-receipt me-1"></i> {{ __('messages.add_sale') }}
                        </a>
                    @endcan
                    @can('products.create')
                        <a href="{{ route('products.create') }}" class="btn btn-sm fw-semibold"
                            style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);">
                            <i class="bx bx-package me-1"></i> {{ __('messages.add_products') }}
                        </a>
                    @endcan
                    @can('stocks.create')
                        <a href="{{ route('stocks.adjust') }}" class="btn btn-sm fw-semibold"
                            style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.15);">
                            <i class="bx bx-slider me-1"></i> {{ __('messages.dash_adjust_stock_title') }}
                        </a>
                    @endcan
                </div>
            </div>
            <div id="dashClock" class="text-white text-center d-none d-sm-block" style="min-width:150px;">
                <div id="dashClock-time">--:--:--</div>
                <div id="dashClock-ampm" style="font-size:.8rem;opacity:.75;letter-spacing:.1em;font-weight:600;">--</div>
                <div id="dashClock-date" style="font-size:.77rem;opacity:.82;margin-top:4px;font-weight:500;">-- -- ----
                </div>
            </div>
        </div>
    </div>

    {{-- ════ ROW 1 · 6 KPI CARDS ════ --}}
    <div class="row g-3 mb-4">

        {{-- Total Revenue --}}
        @can('sales.view')
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent bg-success w-100"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;">{{ __('messages.kpi_total_revenue') }}</span>
                            <div class="kpi-icon bg-label-success"><i class="bx bx-trending-up text-success fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-success" style="font-size:1.1rem;">{{ format_currency($totalRevenue ?? 0) }}
                        </div>
                        <small class="text-muted">{{ $completedSalesCount ?? 0 }} {{ __('messages.kpi_completed') }}</small>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Today Sales --}}
        @can('sales.view')
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent w-100" style="background:linear-gradient(90deg,#71dd37,#29c770);"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;">{{ __('messages.kpi_today_sales') }}</span>
                            <div class="kpi-icon bg-label-success"><i class="bx bx-dollar-circle text-success fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-success" style="font-size:1.1rem;">{{ format_currency($todaySales ?? 0) }}
                        </div>
                        <small class="text-muted">{{ $todaySalesCount ?? 0 }} {{ __('messages.kpi_today_sales') }}</small>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Purchase Value --}}
        @can('purchases.view')
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent bg-info w-100"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;">{{ __('messages.kpi_purchases') }}</span>
                            <div class="kpi-icon bg-label-info"><i class="bx bx-cart-download text-info fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-info" style="font-size:1.1rem;">
                            {{ format_currency($totalPurchaseValue ?? 0) }}</div>
                        <small class="text-muted">{{ $totalPurchases ?? 0 }} {{ __('messages.kpi_orders') }}</small>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Inventory --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card card h-100">
                <div class="kpi-accent bg-warning w-100"></div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"
                            style="font-size:.73rem;font-weight:600;">{{ __('messages.kpi_inventory') }}</span>
                        <div class="kpi-icon bg-label-warning"><i class="bx bx-store text-warning fs-5"></i></div>
                    </div>
                    <div class="fw-bold text-warning" style="font-size:1.1rem;">{{ format_currency($inventoryValue ?? 0) }}
                    </div>
                    <small class="text-muted">{{ $totalProducts ?? 0 }} {{ __('messages.kpi_products_label') }}</small>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card card h-100">
                <div class="kpi-accent bg-primary w-100"></div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"
                            style="font-size:.73rem;font-weight:600;">{{ __('messages.kpi_customers_label') }}</span>
                        <div class="kpi-icon bg-label-primary"><i class="bx bx-user-circle text-primary fs-5"></i></div>
                    </div>
                    <div class="fw-bold text-primary" style="font-size:1.1rem;">{{ $totalCustomers ?? 0 }}</div>
                    <small class="text-muted">{{ $totalSuppliers ?? 0 }} {{ __('messages.kpi_suppliers_label') }}</small>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        @can('sales.view')
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent bg-danger w-100"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;">{{ __('messages.kpi_pending') }}</span>
                            <div class="kpi-icon bg-label-danger"><i class="bx bx-time-five text-danger fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-danger" style="font-size:1.1rem;">{{ $pendingSales ?? 0 }}</div>
                        <small class="text-muted">{{ __('messages.kpi_draft_invoices') }}</small>
                    </div>
                </div>
            </div>
        @endcan

    </div>

    {{-- ════ ROW 2 · QUICK STATS ════ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-primary"><i class="bx bx-package text-primary fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-primary" style="font-size:1.3rem;line-height:1;">
                            {{ $totalProducts ?? 0 }}</div>
                        <div class="text-muted small">{{ __('messages.qs_products') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-warning"><i class="bx bx-award text-warning fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-warning" style="font-size:1.3rem;line-height:1;">{{ $totalBrands ?? 0 }}
                        </div>
                        <div class="text-muted small">{{ __('messages.qs_brands') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-info"><i class="bx bx-category text-info fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-info" style="font-size:1.3rem;line-height:1;">
                            {{ $totalCategories ?? 0 }}</div>
                        <div class="text-muted small">{{ __('messages.qs_categories') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-secondary"><i class="bx bx-group text-secondary fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-secondary" style="font-size:1.3rem;line-height:1;">
                            {{ $totalUsers ?? 0 }}</div>
                        <div class="text-muted small">{{ __('messages.qs_users') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════ ROW 3 · CHARTS ════ --}}
    @if ($user->can('sales.view') || $user->can('purchases.view'))
        <div class="sec-hd"><i class="bx bx-bar-chart-alt-2"></i> {{ __('messages.dash_analytics') }}</div>
        <div class="row g-3 mb-4">

            {{-- Weekly Bar Chart --}}
            <div class="col-lg-8 col-md-12">
                <div class="inner-card card h-100">
                    <div
                        class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-bar-chart-alt-2 text-primary me-2"></i>
                            {{ __('messages.dash_week_chart_title') }}
                        </h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-label-primary" style="font-size:.65rem;">●
                                {{ __('messages.dash_sales_legend') }}</span>
                            <span class="badge bg-label-info" style="font-size:.65rem;">●
                                {{ __('messages.dash_purchases_legend') }}</span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <canvas id="weekChart" style="height:230px;max-height:230px;"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top Products Doughnut --}}
            <div class="col-lg-4 col-md-12">
                <div class="inner-card card h-100">
                    <div class="card-header border-0 py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-trophy text-warning me-2"></i>
                            {{ __('messages.dash_top_products_title') }} · {{ now()->format('M Y') }}
                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-2">
                        @if (isset($topProducts) && $topProducts->count())
                            <canvas id="topProductsChart" style="max-height:200px;"></canvas>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bx bx-package d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <p class="small mb-0">{{ __('messages.dash_no_sales_data') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- ════ ROW 4 · RECENT SALES + TOP CUSTOMERS ════ --}}
    @if ($user->can('sales.view') || $user->can('customers.view'))
        <div class="sec-hd"><i class="bx bx-receipt"></i> {{ __('messages.dash_sales_customers') }}</div>
        <div class="row g-3 mb-4">

            {{-- Recent Sales --}}
            @can('sales.view')
                <div class="col-lg-7 col-md-12">
                    <div class="inner-card card h-100">
                        <div
                            class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="fw-semibold mb-0">
                                <i class="bx bx-receipt text-primary me-2"></i> {{ __('messages.dash_recent_sales') }}
                            </h6>
                            <a href="{{ route('sales.index') }}"
                                class="btn btn-sm btn-outline-primary">{{ __('messages.dash_view_all') }}</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table d-tbl mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.dash_col_invoice') }}</th>
                                            <th>{{ __('messages.dash_col_customer') }}</th>
                                            <th>{{ __('messages.dash_col_amount') }}</th>
                                            <th>{{ __('messages.dash_col_payment') }}</th>
                                            <th>{{ __('messages.dash_col_status') }}</th>
                                            <th>{{ __('messages.dash_col_date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentSales ?? [] as $sale)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}"
                                                        class="fw-bold text-primary text-decoration-none small">
                                                        {{ $sale->invoice_no }}
                                                    </a>
                                                </td>
                                                <td class="fw-semibold small">
                                                    {{ Str::limit($sale->customer->name ?? '-', 18) }}</td>
                                                <td class="fw-bold small">{{ format_currency($sale->grand_total) }}</td>
                                                <td>
                                                    @if ($sale->payment_status === 'Paid')
                                                        <span
                                                            class="s-pill bg-success bg-opacity-10 text-success">{{ __('messages.dash_status_paid') }}</span>
                                                    @elseif($sale->payment_status === 'Partial')
                                                        <span
                                                            class="s-pill bg-warning bg-opacity-10 text-warning">{{ __('messages.dash_status_partial') }}</span>
                                                    @else
                                                        <span
                                                            class="s-pill bg-danger bg-opacity-10 text-danger">{{ __('messages.dash_status_unpaid') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($sale->status === 'Completed')
                                                        <span class="s-pill bg-success bg-opacity-10 text-success"><i
                                                                class="bx bx-check-circle"></i>
                                                            {{ __('messages.dash_status_done') }}</span>
                                                    @elseif($sale->status === 'Draft')
                                                        <span class="s-pill bg-warning bg-opacity-10 text-warning"><i
                                                                class="bx bx-edit"></i>
                                                            {{ __('messages.dash_status_draft') }}</span>
                                                    @else
                                                        <span class="s-pill bg-secondary bg-opacity-10 text-secondary"><i
                                                                class="bx bx-dots-horizontal"></i> {{ $sale->status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted" style="font-size:.75rem;">{{ $sale->invoice_date }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">
                                                    <i class="bx bx-receipt d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.2;"></i>
                                                    {{ __('messages.dash_no_recent_sales') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- Top Customers Doughnut --}}
            @can('customers.view')
                <div class="col-lg-5 col-md-12">
                    <div class="inner-card card h-100">
                        <div
                            class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="fw-semibold mb-0">
                                <i class="bx bx-crown text-warning me-2"></i>
                                {{ __('messages.dash_top_customers_title') }} · {{ now()->format('M Y') }}
                            </h6>
                            <a href="{{ route('customers.index') }}"
                                class="btn btn-sm btn-outline-warning">{{ __('messages.dash_all_btn') }}</a>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center py-2">
                            @if (isset($topCustomers) && $topCustomers->count())
                                <canvas id="topCustomersChart" style="max-height:240px;"></canvas>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bx bx-user d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                    <p class="small mb-0">{{ __('messages.dash_no_customer_data') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endcan

        </div>
    @endif

    {{-- ════ ROW 5 · STOCK ALERT + ACTIVITY ════ --}}
    <div class="sec-hd"><i class="bx bx-error-circle"></i> {{ __('messages.dash_alerts_activity') }}</div>
    <div class="row g-3 mb-4">

        {{-- Low Stock --}}
        <div class="col-lg-8 col-md-12">
            <div class="inner-card card h-100">
                <div class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-error-circle text-danger me-2"></i>
                        {{ __('messages.dash_low_stock_alert') }}
                        @php $stockCount = ($lowStockProducts ?? collect())->count(); @endphp
                        @if ($stockCount > 0)
                            <span class="badge bg-danger ms-1"
                                style="font-size:.62rem;border-radius:99px;">{{ $stockCount }}</span>
                        @endif
                    </h6>
                    <a href="{{ route('stocks.index') }}"
                        class="btn btn-sm btn-outline-danger">{{ __('messages.dash_manage_btn') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table d-tbl mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.dash_col_product') }}</th>
                                    <th>{{ __('messages.dash_col_sku') }}</th>
                                    <th>{{ __('messages.dash_col_alert') }}</th>
                                    <th>{{ __('messages.dash_col_stock') }}</th>
                                    <th>{{ __('messages.dash_col_status') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts ?? [] as $p)
                                    @php
                                        $product = $p instanceof \App\Models\Product ? $p : null;
                                        $qty = (float) (optional(optional($product)->stock)->quantity ?? 0);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if (!empty(optional($product)->image))
                                                    <img src="{{ asset('uploads/products/' . optional($product)->image) }}"
                                                        class="rounded flex-shrink-0"
                                                        style="width:30px;height:30px;object-fit:cover;"
                                                        onerror="imgError(this)">
                                                @else
                                                    <div class="rounded d-flex align-items-center justify-content-center bg-light flex-shrink-0"
                                                        style="width:30px;height:30px;">
                                                        <i class="bx bx-package text-muted" style="font-size:.8rem;"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-semibold small"
                                                    style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ optional($product)->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td><code style="font-size:.68rem;">{{ optional($product)->code ?? '-' }}</code>
                                        </td>
                                        <td class="text-muted small">{{ optional($product)->minimum_stock_alert ?? 0 }}
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $qty <= 0 ? 'text-danger' : 'text-warning' }} small">
                                                {{ number_format($qty, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($qty <= 0)
                                                <span class="s-pill bg-danger bg-opacity-10 text-danger"><i
                                                        class="bx bx-x-circle"></i>
                                                    {{ __('messages.dash_stock_out') }}</span>
                                            @else
                                                <span class="s-pill bg-warning bg-opacity-10 text-warning"><i
                                                        class="bx bx-error"></i>
                                                    {{ __('messages.dash_stock_low') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('stocks.create')
                                                @if ($product)
                                                    <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                                                        class="btn btn-outline-primary btn-sm"
                                                        style="padding:.15rem .4rem;font-size:.7rem;"
                                                        title="{{ __('messages.dash_adjust_stock_title') }}">
                                                        <i class="bx bx-slider"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bx bx-check-shield text-success d-block mb-2"
                                                style="font-size:2.5rem;"></i>
                                            {{ __('messages.dash_all_stock_healthy') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="col-lg-4 col-md-12">
            <div class="inner-card card h-100">
                <div class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-history text-secondary me-2"></i>{{ __('messages.dash_recent_activity') }}
                    </h6>
                    @can('activity_logs.view')
                        <a href="{{ route('activity-logs.index') }}"
                            class="btn btn-sm btn-outline-secondary">{{ __('messages.dash_all_btn') }}</a>
                    @endcan
                </div>
                <div class="card-body py-1 px-3" style="max-height:360px;overflow-y:auto;">
                    @forelse($recentActivities as $log)
                        <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="act-dot mt-1"></div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 fw-semibold small text-truncate">{{ $log->user->name ?? 'System' }}</p>
                                <p class="mb-0 text-muted small text-truncate" style="font-size:.77rem;">
                                    {{ $log->activity ?? ($log->description ?? '') }}
                                </p>
                                <span class="text-muted"
                                    style="font-size:.7rem;">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bx bx-history d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                            <p class="small mb-0">{{ __('messages.dash_no_recent_activity') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // ── Live Clock ─────────────────────────────────────────────────────
        (function() {
            const M = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tick() {
                const n = new Date(),
                    h = n.getHours(),
                    ap = h >= 12 ? 'PM' : 'AM',
                    hh = h % 12 || 12;
                const t = document.getElementById('dashClock-time');
                const a = document.getElementById('dashClock-ampm');
                const d = document.getElementById('dashClock-date');
                if (t) t.textContent = pad(hh) + ':' + pad(n.getMinutes()) + ':' + pad(n.getSeconds());
                if (a) a.textContent = ap;
                if (d) d.textContent = n.getDate() + ' ' + M[n.getMonth()] + ' ' + n.getFullYear();
            }
            tick();
            setInterval(tick, 1000);
        })();

        // ── Chart defaults ─────────────────────────────────────────────────
        Chart.defaults.font.family = "'Public Sans',sans-serif";
        const _dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const _grid = _dark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
        const _tick = _dark ? '#a3a4cc' : '#697a8d';
        const _sym = '{{ addslashes($sym) }}';

        // ── 1. Weekly Bar ──────────────────────────────────────────────────
        (function() {
            const ctx = document.getElementById('weekChart');
            if (!ctx) return;
            const raw = @json($weekDates ?? []);
            const salesD = @json($weekSalesData ?? []);
            const purchD = @json($weekPurchasesData ?? []);
            const labels = raw.map(d => {
                const dt = new Date(d + 'T00:00:00');
                return dt.toLocaleDateString('en', {
                    weekday: 'short',
                    day: 'numeric'
                });
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: '{{ __('messages.menu_sales') }}',
                            data: salesD,
                            backgroundColor: 'rgba(105,108,255,.78)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: '{{ __('messages.menu_purchases') }}',
                            data: purchD,
                            backgroundColor: 'rgba(3,195,236,.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: c => _sym + Number(c.parsed.y).toLocaleString('en-IN', {
                                    minimumFractionDigits: 2
                                })
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: _grid
                            },
                            ticks: {
                                color: _tick,
                                callback: v => {
                                    if (v >= 1e5) return _sym + (v / 1e5).toFixed(1) + 'L';
                                    if (v >= 1e3) return _sym + (v / 1e3).toFixed(0) + 'K';
                                    return _sym + v;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: _tick
                            }
                        }
                    }
                }
            });
        })();

        // ── 2. Top Products Doughnut ───────────────────────────────────────
        (function() {
            const ctx = document.getElementById('topProductsChart');
            if (!ctx) return;
            const labels = @json(($topProducts ?? collect())->map(fn($i) => Str::limit($i->product?->name ?? 'Unknown', 18)));
            const data = @json(($topProducts ?? collect())->pluck('total_qty'));
            const clrs = ['#696cff', '#03c3ec', '#71dd37', '#ffab00', '#ff3e1d', '#9c3fe4'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: clrs,
                        borderWidth: 2,
                        borderColor: _dark ? '#2b2c40' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: _tick,
                                font: {
                                    size: 10
                                },
                                boxWidth: 10,
                                padding: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: c => ' ' + c.label + ': ' + c.parsed + ' units'
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        })();

        // ── 3. Top Customers Doughnut ──────────────────────────────────────
        (function() {
            const ctx = document.getElementById('topCustomersChart');
            if (!ctx) return;
            const labels = @json(($topCustomers ?? collect())->map(fn($i) => Str::limit($i->customer?->name ?? 'Unknown', 15)));
            const data = @json(($topCustomers ?? collect())->pluck('total_spent'));
            const clrs = ['#ffab00', '#696cff', '#03c3ec', '#71dd37', '#ff3e1d'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: clrs,
                        borderWidth: 2,
                        borderColor: _dark ? '#2b2c40' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: _tick,
                                font: {
                                    size: 10
                                },
                                boxWidth: 10,
                                padding: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: c => ' ' + c.label + ': ' + _sym + Number(c.parsed).toLocaleString(
                                    'en-IN', {
                                        minimumFractionDigits: 2
                                    })
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        })();
    </script>
@endpush
