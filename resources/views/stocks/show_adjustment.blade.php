@extends('layouts.admin')
@section('title', __('messages.voucher_details'))

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.voucher_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('stocks.history') }}">{{ __('messages.stock_adjustments') }}</a></li>
                    <li class="breadcrumb-item active">{{ $voucherNo }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @can('stocks.create')
                <a href="{{ route('stocks.edit_adjustment', $voucherNo) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit_adjustment') }}
                </a>
            @endcan
            <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="text-muted small fw-semibold">{{ __('messages.voucher_no') }}</div>
                            <div class="fw-bold fs-4 text-primary">{{ $voucherNo }}</div>
                        </div>
                        <span class="badge bg-label-primary">{{ __('messages.stock_adjustments') }}</span>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('messages.transaction_date') }}</div>
                        <div class="fw-semibold">{{ $transactionDate }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('messages.th_created_by') }}</div>
                        <div class="fw-semibold">{{ optional($adjustments->first()->user)->name ?? 'System' }}</div>
                    </div>

                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.notes') }}</div>
                        <div class="fw-semibold text-muted">{{ $notes ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold text-primary">
                        <i class="bx bx-list-ul me-2"></i> {{ __('messages.adjusted_products') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.adj_product_col') }}</th>
                                    <th>{{ __('messages.current_stock') }}</th>
                                    <th>{{ __('messages.adj_type') }}</th>
                                    <th>{{ __('messages.adj_quantity') }}</th>
                                    <th>{{ __('messages.notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adjustments as $adjustment)
                                    @php
                                        $product = $adjustment->product;
                                        $stockQty = optional($product->stock)->quantity ?? 0;
                                        $isPositive = $adjustment->quantity_change >= 0;
                                        $sign = $isPositive ? '+' : '';
                                        $colorClass = $isPositive ? 'text-success' : 'text-danger';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $product->name ?? __('messages.unknown_product') }}
                                            </div>
                                            <div class="small text-muted">{{ $product->code ?? '—' }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-semibold {{ $stockQty <= 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($stockQty, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $isPositive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ $adjustment->adjustment_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-bold {{ $colorClass }}">{{ $sign }}{{ number_format($adjustment->quantity_change, 2) }}</span>
                                        </td>
                                        <td class="text-muted small">{{ $adjustment->notes ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
