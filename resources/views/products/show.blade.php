@extends('layouts.admin')
@section('title', 'Product — ' . $product->name)

@section('content')
    @php
        $qty = (float) ($product->stock->quantity ?? 0);
        $alert = (float) ($product->minimum_stock_alert ?? 0);
        $isOut = $qty <= 0;
        $isLow = !$isOut && $alert > 0 && $qty <= $alert;
        $profit = $product->selling_price - $product->purchase_price;
        $margin = $product->selling_price > 0 ? round(($profit / $product->selling_price) * 100, 1) : 0;
        $markup = $product->purchase_price > 0 ? round(($profit / $product->purchase_price) * 100, 1) : 0;
        $sCls = $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success');
        $sBadge = $isOut ? 'bg-danger' : ($isLow ? 'bg-warning text-dark' : 'bg-success');
        $sLabel = $isOut
            ? __('messages.out_of_stock')
            : ($isLow
                ? __('messages.low_stock_badge')
                : __('messages.in_stock'));
        $sIcon = $isOut ? 'bx-x-circle' : ($isLow ? 'bx-error-circle' : 'bx-check-circle');

        $allImgs = array_values(
            array_filter(array_merge($product->image ? [$product->image] : [], $product->gallery ?? [])),
        );

        $specs = array_filter([
            'Manufacturer' => $product->manufacturer,
            'Model Number' => $product->model_number,
            'Part / MPN' => $product->part_number,
            'Warranty' => $product->warranty,
            'Color' => $product->color,
            'Weight' => $product->weight,
            'Country of Origin' => $product->country_of_origin,
        ]);

        $purchases = $product->purchaseItems->sortByDesc(fn($i) => $i->purchase?->purchase_date);
        $sales = $product->saleItems->sortByDesc(fn($i) => $i->sale?->invoice_date);
        $adjustments = $product->stockAdjustments->sortByDesc('transaction_date');
        $purReturns = $product->purchaseReturnItems->sortByDesc(fn($i) => $i->purchaseReturn?->return_date);
        $saleReturns = $product->saleReturnItems->sortByDesc(fn($i) => $i->saleReturn?->return_date);
        $totalPurchased = $purchases->sum('quantity');
        $totalSold = $sales->sum('quantity');
        $totalReturned = $saleReturns->sum('quantity');
    @endphp

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.prod_detail') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.menu_products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 35) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('stocks.create')
                <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}" class="btn btn-outline-warning">
                    <i class="bx bx-slider me-1"></i> {{ __('messages.prod_adjust_stock_btn') }}
                </a>
            @endcan
            @can('products.create')
                <a href="{{ route('products.copy', $product->id) }}" class="btn btn-outline-secondary">
                    <i class="bx bx-copy me-1"></i> {{ __('messages.prod_copy_label') }}
                </a>
            @endcan
            @can('products.update')
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Hero Banner --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            @if ($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}"
                    class="rounded-circle border border-2 border-white flex-shrink-0"
                    style="width:54px;height:54px;object-fit:cover;"
                    onerror="this.outerHTML='<div class=\'rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center\' style=\'width:54px;height:54px;background:rgba(255,255,255,.2)\'><i class=\'bx bx-package text-white fs-4\'></i></div>'">
            @else
                <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                    style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                    <i class="bx bx-package text-white fs-4"></i>
                </div>
            @endif
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $product->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-barcode me-1"></i>{{ $product->code }}</span>
                    @if ($product->mainCategory)
                        <span>· {{ $product->mainCategory->name }}</span>
                    @endif
                    @if ($product->brand)
                        <span>· {{ $product->brand->name }}</span>
                    @endif
                    @if ($product->barcode)
                        <span>· {{ $product->barcode }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $product->status === 'active' ? 'text-success' : 'text-secondary' }}">
                    <i
                        class="bx {{ $product->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($product->status) }}
                </span>
                <span class="badge {{ $sBadge }} fw-semibold">
                    <i class="bx {{ $sIcon }} me-1"></i>{{ $sLabel }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ══ LEFT col-lg-8 ══ --}}
        <div class="col-lg-8">

            {{-- Product Detail Card --}}
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-package me-2 text-primary"></i>{{ __('messages.prod_product_details_card') }}
                    </h6>
                    <span class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">

                    {{-- Image + Identity --}}
                    <div class="d-flex align-items-start gap-4 mb-4 flex-wrap">
                        <div class="flex-shrink-0">
                            @if ($allImgs)
                                <img src="{{ asset('uploads/products/' . $allImgs[0]) }}" class="rounded-3 border"
                                    style="width:100px;height:100px;object-fit:contain;background:#f8f9ff;"
                                    onerror="imgError(this)">
                            @else
                                <div class="rounded-3 border d-flex align-items-center justify-content-center bg-label-primary"
                                    style="width:100px;height:100px;">
                                    <i class="bx bx-package text-primary" style="font-size:2.5rem;"></i>
                                </div>
                            @endif
                            {{-- Thumbnails --}}
                            @if (count($allImgs) > 1)
                                <div class="d-flex gap-1 mt-2 flex-wrap" style="max-width:100px;">
                                    @foreach (array_slice($allImgs, 1, 4) as $gImg)
                                        <img src="{{ asset('uploads/products/' . $gImg) }}" class="rounded border"
                                            style="width:44px;height:44px;object-fit:cover;cursor:pointer;"
                                            onclick="document.querySelector('#mainProductImg').src=this.src"
                                            onerror="this.style.display='none'">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @if ($product->brand)
                                    <span class="badge bg-label-primary"><i
                                            class="bx bx-award me-1"></i>{{ $product->brand->name }}</span>
                                @endif
                                @if ($product->mainCategory)
                                    <span class="badge bg-label-secondary"><i
                                            class="bx bx-category me-1"></i>{{ $product->mainCategory->name }}</span>
                                @endif
                                @if ($product->subCategory)
                                    <span class="badge bg-label-info"><i
                                            class="bx bx-sitemap me-1"></i>{{ $product->subCategory->name }}</span>
                                @endif
                            </div>
                            <h4 class="fw-bold mb-2">{{ $product->name }}</h4>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="bx bx-barcode me-1"></i>SKU: <code
                                        class="text-primary fw-semibold">{{ $product->code }}</code></span>
                                @if ($product->barcode)
                                    <span><i class="bx bx-qr-scan me-1"></i>Barcode:
                                        <code>{{ $product->barcode }}</code></span>
                                @endif
                                <span><i class="bx bx-ruler me-1"></i>{{ $product->unit_name ?? '—' }}
                                    ({{ $product->unit_code ?? '—' }})</span>
                                <span><i class="bx bx-calendar me-1"></i>{{ $product->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">{{ number_format($qty, 0) }}</div>
                                <div class="text-muted small">{{ __('messages.th_stock') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-5 text-success">{{ format_currency($product->selling_price) }}</div>
                                <div class="text-muted small">{{ __('messages.prod_sell_price') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center bg-label-warning">
                                <div class="fw-bold fs-5 text-warning">{{ format_currency($product->purchase_price) }}
                                </div>
                                <div class="text-muted small">{{ __('messages.prod_cost_price') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center bg-label-info">
                                <div class="fw-bold fs-5 {{ $profit >= 0 ? 'text-info' : 'text-danger' }}">
                                    {{ format_currency($profit) }}</div>
                                <div class="text-muted small">{{ __('messages.prod_profit_lbl') }} ({{ $margin }}%)
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if ($product->short_description || $product->full_description)
                        <div class="border-top pt-3">
                            @if ($product->short_description)
                                <p class="text-muted small fw-semibold mb-1">{{ __('messages.description_label') }}</p>
                                <p class="mb-2 small">{{ $product->short_description }}</p>
                            @endif
                            @if ($product->full_description)
                                <p class="small mb-0" style="white-space:pre-line;">{{ $product->full_description }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Technical Specifications --}}
            @if (count($specs))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-chip me-2 text-warning"></i>{{ __('messages.prod_tech_specs_card') }}
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($specs as $key => $val)
                            <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom">
                                <span class="text-muted small fw-semibold">{{ $key }}</span>
                                <span class="fw-semibold small text-end">{{ $val }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Transaction History --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" id="txnTabs" role="tablist"
                        style="border-bottom:none;gap:.25rem;">
                        <li class="nav-item">
                            <button class="nav-link active px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-pur" type="button">
                                <i class="bx bx-cart-add me-1"></i>{{ __('messages.prod_tab_purchases') }} <span
                                    class="badge bg-label-primary ms-1">{{ $purchases->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-sal" type="button">
                                <i class="bx bx-receipt me-1"></i>{{ __('messages.prod_tab_sales') }} <span
                                    class="badge bg-label-success ms-1">{{ $sales->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-adj" type="button">
                                <i class="bx bx-slider me-1"></i>{{ __('messages.prod_tab_adjustments') }} <span
                                    class="badge bg-label-warning ms-1">{{ $adjustments->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-pr" type="button">
                                <i class="bx bx-undo me-1"></i>{{ __('messages.prod_tab_pur_returns') }} <span
                                    class="badge bg-label-info ms-1">{{ $purReturns->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-sr" type="button">
                                <i class="bx bx-undo me-1"></i>{{ __('messages.prod_tab_sal_returns') }} <span
                                    class="badge bg-label-danger ms-1">{{ $saleReturns->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">

                    {{-- Purchases --}}
                    <div class="tab-pane fade show active" id="tab-pur">
                        @if ($purchases->isEmpty())
                            <div style="width:100%;text-align:center;padding:2.5rem 0;">
                                <div class="text-muted"
                                    style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                    <i class="bx bx-cart-add" style="font-size:3rem;opacity:.25;line-height:1;"></i>
                                    <span class="small">{{ __('messages.prod_no_purchase_records') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.prod_pur_hash') }}</th>
                                            <th>{{ __('messages.prod_date_col') }}</th>
                                            <th>{{ __('messages.prod_supplier_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_qty_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_price_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_total_col') }}</th>
                                            <th class="text-center">{{ __('messages.th_status') }}</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchases as $item)
                                            @php $pur = $item->purchase; @endphp
                                            <tr>
                                                <td><code
                                                        class="small text-primary fw-semibold">{{ $pur?->purchase_no ?? '—' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $pur?->purchase_date ? \Carbon\Carbon::parse($pur->purchase_date)->format('d M Y') : '—' }}
                                                </td>
                                                <td class="fw-semibold small">{{ $pur?->supplier?->name ?? '—' }}</td>
                                                <td class="text-end fw-semibold">{{ number_format($item->quantity, 2) }}
                                                </td>
                                                <td class="text-end small">
                                                    {{ format_currency($item->purchase_price ?? 0) }}</td>
                                                <td class="text-end fw-bold text-primary">
                                                    {{ format_currency($item->total_amount ?? $item->quantity * ($item->purchase_price ?? 0)) }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($pur?->status === 'received')
                                                        <span
                                                            class="badge bg-success rounded-pill">{{ __('messages.prod_received') }}</span>
                                                    @elseif($pur?->status === 'pending')
                                                        <span
                                                            class="badge bg-warning text-dark rounded-pill">{{ __('messages.prod_pending_status') }}</span>
                                                    @elseif($pur?->status === 'ordered')
                                                        <span
                                                            class="badge bg-primary rounded-pill">{{ __('messages.prod_ordered') }}</span>
                                                    @elseif($pur?->status)
                                                        <span
                                                            class="badge bg-secondary rounded-pill">{{ $pur->status }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($pur)
                                                        <a href="{{ route('purchases.show', $pur->id) }}"
                                                            class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                            style="width:28px;height:28px;padding:0;" title="View">
                                                            <i class="bx bx-show" style="font-size:.9rem;"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Sales --}}
                    <div class="tab-pane fade" id="tab-sal">
                        @if ($sales->isEmpty())
                            <div style="width:100%;text-align:center;padding:2.5rem 0;">
                                <div class="text-muted"
                                    style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                    <i class="bx bx-receipt" style="font-size:3rem;opacity:.25;line-height:1;"></i>
                                    <span class="small">{{ __('messages.prod_no_sales_records') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.prod_inv_hash') }}</th>
                                            <th>{{ __('messages.prod_date_col') }}</th>
                                            <th>{{ __('messages.prod_customer_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_qty_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_price_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_total_col') }}</th>
                                            <th class="text-center">{{ __('messages.prod_payment_col') }}</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales as $item)
                                            @php $sale = $item->sale; @endphp
                                            <tr>
                                                <td><code
                                                        class="small text-success fw-semibold">{{ $sale?->invoice_no ?? '—' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $sale?->invoice_date ? \Carbon\Carbon::parse($sale->invoice_date)->format('d M Y') : '—' }}
                                                </td>
                                                <td class="fw-semibold small">
                                                    {{ $sale?->customer?->name ?? __('messages.prod_walk_in') }}
                                                </td>
                                                <td class="text-end fw-semibold">{{ number_format($item->quantity, 2) }}
                                                </td>
                                                <td class="text-end small">{{ format_currency($item->unit_price ?? 0) }}
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    {{ format_currency($item->total_amount ?? $item->quantity * ($item->unit_price ?? 0)) }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($sale?->payment_status === 'Paid')
                                                        <span
                                                            class="badge bg-success rounded-pill">{{ __('messages.paid') }}</span>
                                                    @elseif($sale?->payment_status === 'Partial')
                                                        <span
                                                            class="badge bg-warning text-dark rounded-pill">{{ __('messages.partial') }}</span>
                                                    @elseif($sale)
                                                        <span
                                                            class="badge bg-danger rounded-pill">{{ __('messages.unpaid') }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($sale)
                                                        <a href="{{ route('sales.show', $sale->id) }}"
                                                            class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                            style="width:28px;height:28px;padding:0;" title="View">
                                                            <i class="bx bx-show" style="font-size:.9rem;"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Adjustments --}}
                    <div class="tab-pane fade" id="tab-adj">
                        @if ($adjustments->isEmpty())
                            <div style="width:100%;text-align:center;padding:2.5rem 0;">
                                <div class="text-muted"
                                    style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                    <i class="bx bx-slider" style="font-size:3rem;opacity:.25;line-height:1;"></i>
                                    <span class="small">{{ __('messages.prod_no_adj_records') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.prod_voucher_hash') }}</th>
                                            <th>{{ __('messages.prod_date_col') }}</th>
                                            <th>{{ __('messages.prod_type_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_change_col') }}</th>
                                            <th>{{ __('messages.prod_by_col') }}</th>
                                            <th>{{ __('messages.prod_notes_col') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($adjustments as $adj)
                                            <tr>
                                                <td><code class="small">{{ $adj->voucher_no ?? '—' }}</code></td>
                                                <td class="text-muted small">
                                                    {{ $adj->transaction_date ? \Carbon\Carbon::parse($adj->transaction_date)->format('d M Y') : $adj->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    @if ($adj->adjustment_type === 'Plus')
                                                    <span class="badge bg-success">+ Add</span>@else<span
                                                            class="badge bg-danger">− Remove</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="text-end fw-bold {{ $adj->quantity_change >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $adj->quantity_change >= 0 ? '+' : '' }}{{ number_format($adj->quantity_change, 2) }}
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $adj->user?->name ?? __('messages.system') }}</td>
                                                <td class="text-muted small">{{ Str::limit($adj->notes ?? '—', 35) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Purchase Returns --}}
                    <div class="tab-pane fade" id="tab-pr">
                        @if ($purReturns->isEmpty())
                            <div style="width:100%;text-align:center;padding:2.5rem 0;">
                                <div class="text-muted"
                                    style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                    <i class="bx bx-cart-download" style="font-size:3rem;opacity:.25;line-height:1;"></i>
                                    <span class="small">{{ __('messages.prod_no_pur_return_records') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.return_no') }}</th>
                                            <th>{{ __('messages.prod_date_col') }}</th>
                                            <th>{{ __('messages.prod_supplier_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_qty_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_total_col') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purReturns as $item)
                                            @php $ret = $item->purchaseReturn; @endphp
                                            <tr>
                                                <td><code
                                                        class="small text-info fw-semibold">{{ $ret?->return_no ?? '—' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $ret?->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : '—' }}
                                                </td>
                                                <td class="small">{{ $ret?->purchase?->supplier?->name ?? '—' }}</td>
                                                <td class="text-end fw-semibold">{{ number_format($item->quantity, 2) }}
                                                </td>
                                                <td class="text-end fw-bold text-info">
                                                    {{ format_currency($item->total_amount ?? $item->quantity * ($item->unit_price ?? 0)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Sale Returns --}}
                    <div class="tab-pane fade" id="tab-sr">
                        @if ($saleReturns->isEmpty())
                            <div style="width:100%;text-align:center;padding:2.5rem 0;">
                                <div class="text-muted"
                                    style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                    <i class="bx bx-undo" style="font-size:3rem;opacity:.25;line-height:1;"></i>
                                    <span class="small">{{ __('messages.prod_no_sal_return_records') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.return_no') }}</th>
                                            <th>{{ __('messages.prod_date_col') }}</th>
                                            <th>{{ __('messages.prod_customer_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_qty_col') }}</th>
                                            <th class="text-end">{{ __('messages.prod_total_col') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saleReturns as $item)
                                            @php $ret = $item->saleReturn; @endphp
                                            <tr>
                                                <td><code
                                                        class="small text-danger fw-semibold">{{ $ret?->return_no ?? '—' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $ret?->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : '—' }}
                                                </td>
                                                <td class="small">{{ $ret?->sale?->customer?->name ?? 'Walk-in' }}</td>
                                                <td class="text-end fw-semibold">{{ number_format($item->quantity, 2) }}
                                                </td>
                                                <td class="text-end fw-bold text-danger">
                                                    {{ format_currency($item->total_amount ?? $item->quantity * ($item->unit_price ?? 0)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>{{-- /tab-content --}}
            </div>{{-- /Transaction card --}}

        </div>{{-- /col-lg-8 --}}

        {{-- ══ RIGHT col-lg-4 ══ --}}
        <div class="col-lg-4">

            {{-- Information Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $product->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">SKU</span>
                            <code class="fw-semibold text-primary">{{ $product->code }}</code>
                        </li>
                        @if ($product->barcode)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small fw-semibold">Barcode</span>
                                <code>{{ $product->barcode }}</code>
                            </li>
                        @endif
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span
                                class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Stock Status</span>
                            <span class="badge rounded-pill {{ $sBadge }}">{{ $sLabel }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_stock') }}</span>
                            <span class="fw-bold {{ $sCls }}">{{ number_format($qty, 2) }}
                                {{ $product->unit_code ?? 'PCS' }}</span>
                        </li>
                        @if ($alert > 0)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small fw-semibold">Min Stock Alert</span>
                                <span class="fw-semibold text-warning">{{ number_format($alert, 0) }}
                                    {{ $product->unit_code ?? '' }}</span>
                            </li>
                        @endif
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Purchase Price</span>
                            <span class="fw-semibold">{{ format_currency($product->purchase_price) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Selling Price</span>
                            <span class="fw-bold text-primary">{{ format_currency($product->selling_price) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Tax</span>
                            <span class="fw-semibold">{{ number_format($product->tax_percentage ?? 0, 2) }}%</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Unit</span>
                            <span>{{ $product->unit_name ?? '—' }} ({{ $product->unit_code ?? '—' }})</span>
                        </li>
                        @if ($product->brand)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small fw-semibold">Brand</span>
                                <span class="badge bg-label-primary">{{ $product->brand->name }}</span>
                            </li>
                        @endif
                        @if ($product->mainCategory)
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small fw-semibold">Category</span>
                                <span class="badge bg-label-secondary">{{ $product->mainCategory->name }}</span>
                            </li>
                        @endif
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $product->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $product->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Activity Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bar-chart-alt-2 me-2 text-info"></i>Activity Summary
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0 text-center">
                        <div class="col-4 py-3 border-end">
                            <div class="fw-bold text-primary" style="font-size:1.1rem;">
                                {{ number_format($totalPurchased, 0) }}</div>
                            <div class="text-muted"
                                style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
                                Bought</div>
                        </div>
                        <div class="col-4 py-3 border-end">
                            <div class="fw-bold text-success" style="font-size:1.1rem;">
                                {{ number_format($totalSold, 0) }}</div>
                            <div class="text-muted"
                                style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
                                Sold</div>
                        </div>
                        <div class="col-4 py-3">
                            <div class="fw-bold text-warning" style="font-size:1.1rem;">
                                {{ number_format($totalReturned, 0) }}</div>
                            <div class="text-muted"
                                style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
                                Returned</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('products.update')
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> Edit Product
                        </a>
                    @endcan
                    @can('stocks.create')
                        <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                            class="btn btn-outline-warning">
                            <i class="bx bx-slider me-1"></i> Adjust Stock
                        </a>
                    @endcan
                    @can('products.create')
                        <a href="{{ route('products.copy', $product->id) }}" class="btn btn-outline-secondary">
                            <i class="bx bx-copy me-1"></i> Copy Product
                        </a>
                    @endcan
                    @can('products.delete')
                        <form id="deleteForm" action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $product->name }}">
                                <i class="bx bx-trash me-1"></i> Delete Product
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>{{-- /col-lg-4 --}}
    </div>{{-- /row --}}

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Tab persistence
            const tabKey = 'prod_show_tab_{{ $product->id }}';
            const saved = localStorage.getItem(tabKey);
            if (saved) {
                const $t = $('[data-bs-target="' + saved + '"]');
                if ($t.length) new bootstrap.Tab($t[0]).show();
            }
            $('#txnTabs .nav-link').on('shown.bs.tab', function(e) {
                localStorage.setItem(tabKey, $(e.target).data('bs-target'));
            });

            // Delete confirm
            $(document).on('click', '.delete-btn', function() {
                const name = $(this).data('name');
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `{{ __('messages.delete') }} "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: $('#deleteForm').attr('action'),
                            type: 'POST',
                            data: $('#deleteForm').serialize(),
                            success: function(res) {
                                if (res.success) {
                                    showAdminToast(res.message, 'success');
                                    setTimeout(() => window.location.href =
                                        '{{ route('products.index') }}', 1200);
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: function() {
                                showAdminToast('{{ __('messages.error_occurred') }}',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
