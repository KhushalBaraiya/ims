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
                <a href="{{ route('stocks.adjust') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_adjustment') }}
                </a>
                <a href="{{ route('stocks.edit_adjustment', $voucherNo) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit_adjustment') }}
                </a>
            @endcan
            <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    @php
        $totalAdded = $adjustments->where('quantity_change', '>', 0)->sum('quantity_change');
        $totalRemoved = $adjustments->where('quantity_change', '<', 0)->sum('quantity_change');
        $netChange = $adjustments->sum('quantity_change');
        $createdBy = optional($adjustments->first()->user)->name ?? 'System';
    @endphp

    {{-- Hero Summary --}}
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #696cff 0%, #9c3fe4 100%);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.18)">
                <i class="bx bx-receipt text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ __('messages.adjustment_vouchers') }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-hash me-1"></i>{{ $voucherNo }}</span>
                    <span><i class="bx bx-calendar-event me-1"></i>{{ $transactionDate }}</span>
                    <span><i class="bx bx-user me-1"></i>{{ $createdBy }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white text-dark fw-semibold rounded-pill">{{ __('messages.stock_adjustments') }}</span>
                <span class="badge bg-white text-primary fw-semibold rounded-pill">{{ $adjustments->count() }}
                    {{ __('messages.adjusted_products') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="bx bx-info-circle text-primary me-2"></i>Voucher Summary</h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.voucher_no') }}</span>
                            <code class="fw-bold text-primary">{{ $voucherNo }}</code>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.transaction_date') }}</span>
                            <span class="fw-semibold">{{ $transactionDate }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created_by') }}</span>
                            <span class="fw-semibold">{{ $createdBy }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.adjusted_products') }}</span>
                            <span class="fw-semibold">{{ $adjustments->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.added_quantity') }}</span>
                            <span class="fw-bold text-success">+{{ number_format($totalAdded, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.removed_quantity') }}</span>
                            <span class="fw-bold text-danger">{{ number_format($totalRemoved, 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="bx bx-note text-warning me-2"></i>{{ __('messages.notes') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-0" style="white-space: pre-line;">
                        {{ $notes ?: __('messages.no_records') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-semibold mb-0"><i
                                class="bx bx-list-ul text-primary me-2"></i>{{ __('messages.adjusted_products') }}</h6>
                        <small class="text-muted">Review quantities and adjustment notes</small>
                    </div>
                    <span class="badge bg-label-primary">{{ $adjustments->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
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
