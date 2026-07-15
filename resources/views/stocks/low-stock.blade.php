@extends('layouts.admin')
@section('title', 'Low Stock Alert')

@section('content')

    {{-- Page header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Low Stock Alert</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.low_stock_alert') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stocks.adjust') }}" class="btn btn-outline-primary btn-sm">
                <i class="bx bx-plus-medical me-1"></i> {{ __('messages.adjust_stock') }}
            </a>
            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.stock_overview') }}
            </a>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.total_alerts') }}</p>
                        <h4 class="fw-bold mb-0">{{ $summary['total'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-error"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.out_of_stock') }}</p>
                        <h4 class="fw-bold mb-0 text-danger">{{ $summary['out_of_stock'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.low_stock') }}</p>
                        <h4 class="fw-bold mb-0 text-warning">{{ $summary['low_stock'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.restock_value') }}</p>
                        <h4 class="fw-bold mb-0 text-primary">{{ format_currency($summary['restock_value']) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">{{ __('messages.alert_type') }}</label>
                    <select name="filter" class="form-select form-select-sm">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>{{ __('messages.all_alerts') }}
                        </option>
                        <option value="low" {{ $filter === 'low' ? 'selected' : '' }}>
                            {{ __('messages.low_stock_only') }}</option>
                        <option value="out" {{ $filter === 'out' ? 'selected' : '' }}>
                            {{ __('messages.out_of_stock_only') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">{{ __('messages.brand') }}</label>
                    <select name="brand_id" class="form-select form-select-sm">
                        <option value="">{{ __('messages.all_brands') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">{{ __('messages.category') }}</label>
                    <select name="main_category_id" class="form-select form-select-sm">
                        <option value="">{{ __('messages.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('main_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="{{ __('messages.search_placeholder') }}">
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.apply') }}</button>
                    <a href="{{ route('stocks.low_stock') }}"
                        class="btn btn-outline-secondary btn-sm">{{ __('messages.reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Low stock table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if ($products->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.product') }}</th>
                                <th>{{ __('messages.brand') }}</th>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th class="text-end">{{ __('messages.current_qty') }}</th>
                                <th class="text-end">{{ __('messages.th_alert_level') }}</th>
                                <th class="text-end">{{ __('messages.qty_needed') }}</th>
                                <th class="text-end">{{ __('messages.purchase_price') }}</th>
                                <th class="text-end">{{ __('messages.restock_value') }}</th>
                                <th class="text-center">{{ __('messages.action_label') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($product->image)
                                                <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                    class="rounded" style="width:40px;height:40px;object-fit:cover;"
                                                    onerror="imgError(this)">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                    style="width:40px;height:40px;">
                                                    <i class="bx bx-package text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold small">{{ $product->name }}</div>
                                                <div class="text-muted small">{{ $product->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $product->brand?->name ?? '—' }}</td>
                                    <td class="small text-muted">{{ $product->mainCategory?->name ?? '—' }}</td>
                                    <td class="text-center">
                                        @if ($product->stock_status === 'out')
                                            <span
                                                class="badge bg-danger rounded-pill">{{ __('messages.out_of_stock') }}</span>
                                        @else
                                            <span
                                                class="badge bg-warning text-dark rounded-pill">{{ __('messages.low_stock') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">{{ number_format($product->current_qty, 0) }}</td>
                                    <td class="text-end text-muted">{{ number_format($product->alert_qty, 0) }}</td>
                                    <td class="text-end text-primary fw-semibold">
                                        {{ $product->qty_needed > 0 ? '+' . number_format($product->qty_needed, 0) : '—' }}
                                    </td>
                                    <td class="text-end">{{ format_currency($product->purchase_price) }}</td>
                                    <td class="text-end fw-bold text-primary">
                                        {{ $product->restock_value > 0 ? format_currency($product->restock_value) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        @can('stocks.create')
                                            <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                                                class="btn btn-sm btn-outline-primary px-2 py-1">
                                                {{ __('messages.adjust_stock') }}
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-5 text-center text-muted">
                    <i class="bx bx-check-circle" style="font-size:3rem;opacity:.4;"></i>
                    <p class="mt-3 mb-0 fw-semibold">{{ __('messages.no_products_alert') }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection
