@extends('layouts.admin')
@section('title', __('messages.all_reports'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.all_reports') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.all_reports') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Summary KPI Strip --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.total_revenue') }}</p>
                        <h5 class="mb-0 fw-bold text-primary">{{ format_currency($summary['total_sales']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;"><i
                            class="bx bx-trending-up"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.total_purchases') }}</p>
                        <h5 class="mb-0 fw-bold text-danger">{{ format_currency($summary['total_purchases']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;"><i
                            class="bx bx-cart-download"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.rpt_net_profit') }}</p>
                        <h5 class="mb-0 fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ format_currency($netProfit) }}</h5>
                    </div>
                    <span
                        class="avatar-initial rounded-circle {{ $netProfit >= 0 ? 'bg-label-success' : 'bg-label-danger' }} p-3"
                        style="font-size:1rem;"><i
                            class="bx {{ $netProfit >= 0 ? 'bx-award' : 'bx-trending-down' }}"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.total_products') }}</p>
                        <h5 class="mb-0 fw-bold text-info">{{ number_format($summary['total_products']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;"><i
                            class="bx bx-package"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.low_stock') }}</p>
                        <h5 class="mb-0 fw-bold text-warning">{{ $summary['low_stock'] }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;"><i
                            class="bx bx-error-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.out_of_stock') }}</p>
                        <h5 class="mb-0 fw-bold text-danger">{{ $summary['out_of_stock'] }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;"><i
                            class="bx bx-x-circle"></i></span>
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
                            <h5 class="fw-bold mb-1">{{ __('messages.sales_report') }}</h5>
                            <p class="text-muted small mb-3">{{ __('messages.sales_report_desc') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-primary">{{ __('messages.rpt_date_filter') }}</span>
                                <span class="badge bg-label-primary">{{ __('messages.customer') }}</span>
                                <span class="badge bg-label-primary">{{ __('messages.status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.sales') }}" class="text-primary small fw-semibold text-decoration-none">
                        {{ __('messages.rpt_view_report') }} <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">{{ __('messages.rpt_invoices_totals') }}</span>
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
                            <h5 class="fw-bold mb-1">{{ __('messages.purchase_report') }}</h5>
                            <p class="text-muted small mb-3">{{ __('messages.purchase_report_desc') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-info">{{ __('messages.rpt_date_filter') }}</span>
                                <span class="badge bg-label-info">{{ __('messages.supplier') }}</span>
                                <span class="badge bg-label-info">{{ __('messages.status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.purchases') }}" class="text-info small fw-semibold text-decoration-none">
                        {{ __('messages.rpt_view_report') }} <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">{{ __('messages.rpt_orders_spending') }}</span>
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
                            <h5 class="fw-bold mb-1">{{ __('messages.profit_loss') }}</h5>
                            <p class="text-muted small mb-3">{{ __('messages.profit_loss_desc') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-success">{{ __('messages.rpt_revenue') }}</span>
                                <span class="badge bg-label-success">{{ __('messages.rpt_cogs') }}</span>
                                <span class="badge bg-label-success">{{ __('messages.rpt_net_profit') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.profit-loss') }}"
                        class="text-success small fw-semibold text-decoration-none">
                        {{ __('messages.rpt_view_report') }} <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">{{ __('messages.rpt_pl_statement') }}</span>
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
                            <h5 class="fw-bold mb-1">{{ __('messages.top_selling') }}</h5>
                            <p class="text-muted small mb-3">{{ __('messages.top_selling_desc') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-warning">{{ __('messages.rpt_by_qty') }}</span>
                                <span class="badge bg-label-warning">{{ __('messages.rpt_by_revenue') }}</span>
                                <span class="badge bg-label-warning">{{ __('messages.rpt_by_profit') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.top-selling') }}"
                        class="text-warning small fw-semibold text-decoration-none">
                        {{ __('messages.rpt_view_report') }} <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">{{ __('messages.rpt_product_rankings') }}</span>
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
                            <h5 class="fw-bold mb-1">{{ __('messages.stock_alert_menu') }}</h5>
                            <p class="text-muted small mb-3">{{ __('messages.stock_alert_report_desc') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-label-danger">{{ __('messages.out_of_stock') }}</span>
                                <span class="badge bg-label-warning">{{ __('messages.low_stock_badge') }}</span>
                                @if ($summary['out_of_stock'] > 0 || $summary['low_stock'] > 0)
                                    <span class="badge bg-danger">{{ $summary['out_of_stock'] + $summary['low_stock'] }}
                                        {{ __('messages.rpt_alerts') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="card-footer bg-transparent border-top py-2 px-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('reports.stock-alert') }}"
                        class="text-danger small fw-semibold text-decoration-none">
                        {{ __('messages.rpt_view_report') }} <i class="bx bx-right-arrow-alt"></i>
                    </a>
                    <span class="text-muted" style="font-size:.7rem;">{{ __('messages.rpt_restock_alerts') }}</span>
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
