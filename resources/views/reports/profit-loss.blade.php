@extends('layouts.admin')
@section('title', __('messages.profit_loss'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.profit_loss') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">{{ __('messages.all_reports') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('messages.profit_loss') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> {{ __('messages.print') }}
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.all_reports') }}
            </a>
        </div>
    </div>

    {{-- Date Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-success"></i>{{ __('messages.date_range') }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('reports.profit-loss') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                    <div class="col-md-4 d-flex gap-2 flex-wrap">
                        <a href="?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}"
                            class="btn btn-outline-secondary btn-sm">{{ __('messages.rpt_this_month') }}</a>
                        <a href="?date_from={{ now()->subMonth()->startOfMonth()->toDateString() }}&date_to={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                            class="btn btn-outline-secondary btn-sm">{{ __('messages.rpt_last_month') }}</a>
                        <a href="?date_from={{ now()->startOfYear()->toDateString() }}&date_to={{ now()->toDateString() }}"
                            class="btn btn-outline-secondary btn-sm">{{ __('messages.rpt_this_year') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- KPI Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #696cff!important;">
                <div class="card-body py-4 text-center">
                    <span class="avatar-initial rounded-circle bg-label-primary p-3 mb-3 d-inline-flex"
                        style="font-size:1.5rem;">
                        <i class="bx bx-trending-up"></i>
                    </span>
                    <p class="text-muted small mb-1">{{ __('messages.total_revenue_rpt') }}</p>
                    <h4 class="fw-bold text-primary mb-1">{{ format_currency($totalRevenue) }}</h4>
                    <small class="text-muted">{{ $totalSalesCount }} {{ __('messages.completed_invoices') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #ff3e1d!important;">
                <div class="card-body py-4 text-center">
                    <span class="avatar-initial rounded-circle bg-label-danger p-3 mb-3 d-inline-flex"
                        style="font-size:1.5rem;">
                        <i class="bx bx-package"></i>
                    </span>
                    <p class="text-muted small mb-1">{{ __('messages.cogs_label') }}</p>
                    <h4 class="fw-bold text-danger mb-1">{{ format_currency($totalCogs) }}</h4>
                    <small class="text-muted">{{ __('messages.purchase_cost_note') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #71dd37!important;">
                <div class="card-body py-4 text-center">
                    <span class="avatar-initial rounded-circle bg-label-success p-3 mb-3 d-inline-flex"
                        style="font-size:1.5rem;">
                        <i class="bx bx-dollar-circle"></i>
                    </span>
                    <p class="text-muted small mb-1">{{ __('messages.gross_profit_rpt') }}</p>
                    <h4 class="fw-bold {{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }} mb-1">
                        {{ format_currency($grossProfit) }}</h4>
                    <small class="text-muted">Revenue − COGS</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                style="border-top:4px solid {{ $netProfit >= 0 ? '#71dd37' : '#ff3e1d' }}!important;">
                <div class="card-body py-4 text-center">
                    <span
                        class="avatar-initial rounded-circle {{ $netProfit >= 0 ? 'bg-label-success' : 'bg-label-danger' }} p-3 mb-3 d-inline-flex"
                        style="font-size:1.5rem;">
                        <i class="bx {{ $netProfit >= 0 ? 'bx-award' : 'bx-trending-down' }}"></i>
                    </span>
                    <p class="text-muted small mb-1">{{ __('messages.net_profit_rpt') }}</p>
                    <h4 class="fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} mb-1">
                        {{ format_currency($netProfit) }}</h4>
                    <small class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                        Margin: {{ $profitMargin }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        {{-- P&L Statement --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-file me-2 text-success"></i>P&amp;L Statement
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            <tr class="table-light">
                                <td colspan="2" class="fw-bold ps-4 py-2 small text-uppercase text-muted">
                                    <i class="bx bx-trending-up me-1 text-primary"></i>{{ __('messages.income_section') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">{{ __('messages.gross_revenue') }}</td>
                                <td class="text-end fw-semibold text-primary">{{ format_currency($totalRevenue) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted small">— Sale Returns</td>
                                <td class="text-end text-danger small">({{ format_currency($totalSaleReturns) }})</td>
                            </tr>
                            <tr class="border-top">
                                <td class="ps-4 fw-bold">{{ __('messages.net_revenue') }}</td>
                                <td class="text-end fw-bold text-primary">{{ format_currency($netRevenue) }}</td>
                            </tr>

                            <tr class="table-light">
                                <td colspan="2" class="fw-bold ps-4 py-2 small text-uppercase text-muted">
                                    <i class="bx bx-package me-1 text-danger"></i>{{ __('messages.expenses_section') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">{{ __('messages.cogs_full') }}</td>
                                <td class="text-end text-danger">{{ format_currency($totalCogs) }}</td>
                            </tr>
                            <tr class="border-top">
                                <td class="ps-4 fw-bold">{{ __('messages.gross_profit') }}</td>
                                <td class="text-end fw-bold {{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency($grossProfit) }}
                                </td>
                            </tr>

                            <tr class="table-light">
                                <td colspan="2" class="fw-bold ps-4 py-2 small text-uppercase text-muted">
                                    <i
                                        class="bx bx-transfer me-1 text-warning"></i>{{ __('messages.adjustments_section') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">{{ __('messages.purchase_returns_rec') }}</td>
                                <td class="text-end text-success">+{{ format_currency($totalPurchaseReturns) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted small">{{ __('messages.tax_collected_th') }}</td>
                                <td class="text-end small text-muted">{{ format_currency($totalTaxCollected) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted small">{{ __('messages.discounts_given') }}</td>
                                <td class="text-end small text-danger">({{ format_currency($totalDiscounts) }})</td>
                            </tr>
                            <tr class="border-top bg-label-{{ $netProfit >= 0 ? 'success' : 'danger' }}">
                                <td class="ps-4 fw-bold fs-6">{{ __('messages.net_profit_loss') }}</td>
                                <td class="text-end fw-bold fs-6 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency($netProfit) }}
                                    <br><small class="fw-normal text-muted">Margin: {{ $profitMargin }}%</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Monthly Breakdown --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>{{ __('messages.monthly_revenue') }}
                    </h6>
                    @if ($monthlyData->count())
                        <span class="badge bg-label-primary">{{ $monthlyData->count() }} month(s)</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if ($monthlyData->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>{{ __('messages.month') }}</th>
                                        <th class="text-end">{{ __('messages.invoices_th') }}</th>
                                        <th class="text-end">{{ __('messages.revenue_label') }}</th>
                                        <th style="min-width:140px;">{{ __('messages.share_th') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maxRev = $monthlyData->max('revenue') ?: 1;
                                        $totalRev = $monthlyData->sum('revenue') ?: 1;
                                    @endphp
                                    @foreach ($monthlyData as $i => $m)
                                        <tr>
                                            <td class="text-muted small ps-3">{{ $i + 1 }}</td>
                                            <td class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($m->month . '-01')->format('M Y') }}
                                            </td>
                                            <td class="text-end small text-muted">{{ $m->count }}</td>
                                            <td class="text-end fw-bold text-primary">{{ format_currency($m->revenue) }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1"
                                                        style="height:8px;border-radius:4px;">
                                                        <div class="progress-bar bg-primary"
                                                            style="width:{{ round(($m->revenue / $maxRev) * 100) }}%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted"
                                                        style="min-width:32px;">{{ round(($m->revenue / $totalRev) * 100) }}%</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="text-end ps-3">{{ __('messages.totals_label') }}</td>
                                        <td class="text-end">{{ $monthlyData->sum('count') }}</td>
                                        <td class="text-end text-primary">
                                            {{ format_currency($monthlyData->sum('revenue')) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-bar-chart-alt-2 d-block mb-2" style="font-size:3rem;opacity:.15;"></i>
                            <p class="mt-2 mb-1 fw-semibold">{{ __('messages.no_data_period') }}</p>
                            <small>{{ __('messages.apply_date_filter') }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        @media print {

            .layout-menu,
            .layout-navbar,
            .breadcrumb,
            .card-header .d-flex .btn,
            form {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .row.g-4 {
                page-break-inside: avoid;
            }
        }
    </style>
@endpush
