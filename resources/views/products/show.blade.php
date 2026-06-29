@extends('layouts.admin')
@section('title', 'Product — ' . $product->name)

@push('styles')
    <style>
        /* ── Image Viewer ──────────────────────────────────── */
        .img-viewer-wrap {
            position: relative;
            height: 280px;
            background: linear-gradient(135deg, #f8f9ff, #f0f1ff);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid rgba(105, 108, 255, .15);
        }

        .img-viewer-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform .3s;
        }

        .img-viewer-wrap:hover img {
            transform: scale(1.04);
        }

        .thumb-strip {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
            margin-top: .65rem;
        }

        .thumb-item {
            width: 54px;
            height: 54px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color .15s, box-shadow .15s;
            flex-shrink: 0;
        }

        .thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-item.active {
            border-color: #696cff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, .2);
        }

        .thumb-item:hover {
            border-color: rgba(105, 108, 255, .5);
        }

        /* ── Stat chips ────────────────────────────────────── */
        .stat-chip {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: .85rem .6rem;
            border-radius: 12px;
            min-width: 0;
            background: #f8f9ff;
            border: 1px solid rgba(105, 108, 255, .1);
            flex: 1;
        }

        .stat-chip .val {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-chip .lbl {
            font-size: .65rem;
            color: #9099a8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-top: .2rem;
        }

        /* ── Spec rows ─────────────────────────────────────── */
        .spec-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .5rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .spec-row:last-child {
            border-bottom: none;
        }

        .spec-row .spec-key {
            font-size: .78rem;
            color: #697a8d;
        }

        .spec-row .spec-val {
            font-size: .8rem;
            font-weight: 600;
            text-align: right;
        }

        /* ── Profit bar ────────────────────────────────────── */
        .profit-bar-wrap {
            background: rgba(0, 0, 0, .06);
            border-radius: 99px;
            height: 5px;
            overflow: hidden;
        }

        .profit-bar {
            height: 100%;
            border-radius: 99px;
            transition: width .5s;
            background: linear-gradient(90deg, #696cff, #9c3fe4);
        }

        /* ── Transaction tabs ──────────────────────────────── */
        .txn-tabs {
            border-bottom: 2px solid rgba(105, 108, 255, .12);
        }

        .txn-tabs .nav-link {
            color: #697a8d;
            font-size: .8rem;
            font-weight: 600;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            padding: .55rem 1rem;
            border-radius: 0;
            background: transparent;
        }

        .txn-tabs .nav-link:hover {
            color: #696cff;
        }

        .txn-tabs .nav-link.active {
            color: #696cff;
            border-bottom-color: #696cff;
        }

        .txn-tabs .nav-link .badge {
            font-size: .58rem;
            vertical-align: middle;
        }

        .txn-table thead th {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #8592a3;
            background: #f8f9ff;
            padding: .5rem .75rem;
            border-bottom: 1px solid rgba(105, 108, 255, .1);
        }

        .txn-table tbody td {
            font-size: .78rem;
            padding: .5rem .75rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, .04);
        }

        .txn-table tbody tr:last-child td {
            border-bottom: none;
        }

        .txn-table tbody tr:hover td {
            background: rgba(105, 108, 255, .025);
        }

        /* ── Dark mode ─────────────────────────────────────── */
        [data-bs-theme="dark"] .stat-chip {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .08);
        }

        [data-bs-theme="dark"] .img-viewer-wrap {
            background: linear-gradient(135deg, #1e1e2e, #25264a);
        }

        [data-bs-theme="dark"] .spec-row {
            border-bottom-color: rgba(255, 255, 255, .06);
        }

        [data-bs-theme="dark"] .txn-table thead th {
            background: #25264a;
        }

        [data-bs-theme="dark"] .txn-table tbody tr:hover td {
            background: rgba(255, 255, 255, .03);
        }

        [data-bs-theme="dark"] .txn-tabs {
            border-bottom-color: rgba(255, 255, 255, .08);
        }
    </style>
@endpush

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
        $sLabel = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');

        $allImgs = [];
        if ($product->image) {
            $allImgs[] = $product->image;
        }
        if ($product->gallery) {
            $allImgs = array_merge($allImgs, $product->gallery);
        }

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

    {{-- ── Page Header ──────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Product Detail</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @can('products.create')
                <a href="{{ route('products.copy', $product->id) }}" class="btn btn-outline-warning btn-sm">
                    <i class="bx bx-copy me-1"></i> Copy
                </a>
            @endcan
            @can('products.update')
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-edit me-1"></i> Edit
                </a>
            @endcan
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- ── Identity Banner ──────────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            @if ($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}"
                    class="rounded-circle border border-white border-2 flex-shrink-0"
                    style="width:52px;height:52px;object-fit:cover;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center border border-white border-2 flex-shrink-0"
                    style="width:52px;height:52px;background:rgba(255,255,255,.2);">
                    <i class="bx bx-package text-white" style="font-size:1.4rem;"></i>
                </div>
            @endif
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $product->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-barcode me-1"></i>{{ $product->code }}</span>
                    @if ($product->barcode)
                        <span>· Barcode: {{ $product->barcode }}</span>
                    @endif
                    <span>· {{ $product->mainCategory->name ?? 'Uncategorized' }}</span>
                    @if ($product->brand)
                        <span>· {{ $product->brand->name }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-white {{ $product->status === 'active' ? 'text-success' : 'text-danger' }} fw-bold">
                    <i
                        class="bx {{ $product->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($product->status) }}
                </span>
                <span class="badge {{ $sBadge }}">{{ $sLabel }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- ═══════════════════ LEFT COLUMN ══════════════════ --}}
        <div class="col-xl-3 col-lg-5">

            {{-- Image Viewer --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="img-viewer-wrap">
                        <img id="primaryViewer"
                            src="{{ $allImgs ? asset('uploads/products/' . $allImgs[0]) : 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image' }}"
                            alt="{{ $product->name }}"
                            onerror="this.src='https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image'">
                    </div>
                    @if (count($allImgs) > 1)
                        <div class="thumb-strip">
                            @foreach ($allImgs as $i => $img)
                                <div class="thumb-item {{ $i === 0 ? 'active' : '' }}"
                                    data-src="{{ asset('uploads/products/' . $img) }}">
                                    <img src="{{ asset('uploads/products/' . $img) }}" loading="lazy"
                                        onerror="this.parentElement.style.display='none'">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pricing chips --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex gap-2 mb-3">
                        <div class="stat-chip">
                            <div class="val text-primary">{{ format_currency($product->selling_price) }}</div>
                            <div class="lbl">Sell</div>
                        </div>
                        <div class="stat-chip">
                            <div class="val text-muted">{{ format_currency($product->purchase_price) }}</div>
                            <div class="lbl">Cost</div>
                        </div>
                        <div class="stat-chip">
                            <div class="val {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ format_currency($profit) }}</div>
                            <div class="lbl">Profit</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Profit Margin</small>
                        <small
                            class="fw-bold {{ $margin >= 0 ? 'text-success' : 'text-danger' }}">{{ $margin }}%</small>
                    </div>
                    <div class="profit-bar-wrap">
                        <div class="profit-bar"
                            style="width:{{ min(abs($margin), 100) }}%;{{ $margin < 0 ? 'background:#ea5455;' : '' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock Status --}}
            <div class="card shadow-sm mb-3 border-0"
                style="background:{{ $isOut ? 'linear-gradient(135deg,#fff5f5,#ffe0e0)' : ($isLow ? 'linear-gradient(135deg,#fffbf0,#fff0d0)' : 'linear-gradient(135deg,#f0fff4,#dcfce7)') }}">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:46px;height:46px;background:{{ $isOut ? 'rgba(234,84,85,.15)' : ($isLow ? 'rgba(255,171,0,.15)' : 'rgba(40,199,111,.15)') }}">
                        <i class="bx {{ $isOut ? 'bx-x-circle text-danger' : ($isLow ? 'bx-error-circle text-warning' : 'bx-check-circle text-success') }}"
                            style="font-size:1.5rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold {{ $sCls }}">{{ $sLabel }}</div>
                        <div class="text-muted small">
                            <span class="fw-semibold">{{ number_format($qty, 2) }}</span>
                            {{ $product->unit_name ?? 'Units' }}
                            ({{ $product->unit_code ?? 'PCS' }}) on hand
                        </div>
                        @if ($alert > 0)
                            <div class="text-muted" style="font-size:.72rem;">
                                Alert threshold: {{ number_format($alert, 2) }} {{ $product->unit_code ?? '' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Activity Stats --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                    <span class="fw-semibold small"><i class="bx bx-line-chart me-1 text-primary"></i>Activity
                        Summary</span>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0 text-center">
                        <div class="col-4 p-3 border-end">
                            <div class="fw-bold text-primary" style="font-size:1.1rem;">
                                {{ number_format($totalPurchased, 0) }}</div>
                            <div class="text-muted" style="font-size:.65rem;font-weight:600;text-transform:uppercase;">
                                Purchased</div>
                        </div>
                        <div class="col-4 p-3 border-end">
                            <div class="fw-bold text-success" style="font-size:1.1rem;">
                                {{ number_format($totalSold, 0) }}</div>
                            <div class="text-muted" style="font-size:.65rem;font-weight:600;text-transform:uppercase;">
                                Sold</div>
                        </div>
                        <div class="col-4 p-3">
                            <div class="fw-bold text-warning" style="font-size:1.1rem;">
                                {{ number_format($totalReturned, 0) }}</div>
                            <div class="text-muted" style="font-size:.65rem;font-weight:600;text-transform:uppercase;">
                                Returned</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if ($product->short_description || $product->full_description)
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent py-2 px-3 border-bottom">
                        <span class="fw-semibold small"><i class="bx bx-text me-1 text-primary"></i>Description</span>
                    </div>
                    <div class="card-body p-3">
                        @if ($product->short_description)
                            <p class="small fw-semibold text-muted mb-1">Summary</p>
                            <p class="small mb-3">{{ $product->short_description }}</p>
                        @endif
                        @if ($product->full_description)
                            <p class="small fw-semibold text-muted mb-1">Full Details</p>
                            <p class="small mb-0" style="white-space:pre-line;">{{ $product->full_description }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>{{-- end left col --}}

        {{-- ═══════════════════ CENTRE COLUMN ══════════════════ --}}
        <div class="col-xl-6 col-lg-7">

            {{-- Identity Card --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @if ($product->brand)
                                    <span class="badge bg-label-primary">{{ $product->brand->name }}</span>
                                @endif
                                @if ($product->mainCategory)
                                    <span class="badge bg-label-secondary">{{ $product->mainCategory->name }}</span>
                                @endif
                                @if ($product->subCategory)
                                    <span class="badge bg-label-info">{{ $product->subCategory->name }}</span>
                                @endif
                            </div>
                            <h4 class="fw-bold mb-2 lh-sm">{{ $product->name }}</h4>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="bx bx-barcode me-1"></i>SKU: <code>{{ $product->code }}</code></span>
                                @if ($product->barcode)
                                    <span><i class="bx bx-qr-scan me-1"></i>Barcode:
                                        <code>{{ $product->barcode }}</code></span>
                                @endif
                                <span><i class="bx bx-ruler me-1"></i>Unit: {{ $product->unit_name ?? '—' }}
                                    ({{ $product->unit_code ?? '—' }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing Breakdown --}}
            <div class="card shadow-sm mb-3">
                <div
                    class="card-header bg-transparent py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fw-semibold small"><i class="bx bx-money me-1 text-success"></i>Pricing Breakdown</span>
                    <span class="badge bg-label-success small">Markup {{ $markup }}%</span>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-6 col-md-3 p-3 border-end text-center">
                            <div class="text-muted small mb-1">Purchase Price</div>
                            <div class="fw-bold fs-6">{{ format_currency($product->purchase_price) }}</div>
                        </div>
                        <div class="col-6 col-md-3 p-3 border-end text-center">
                            <div class="text-muted small mb-1">Selling Price</div>
                            <div class="fw-bold fs-6 text-primary">{{ format_currency($product->selling_price) }}</div>
                        </div>
                        <div class="col-6 col-md-3 p-3 border-end text-center">
                            <div class="text-muted small mb-1">Tax</div>
                            <div class="fw-bold fs-6 text-warning">{{ number_format($product->tax_percentage ?? 0, 2) }}%
                            </div>
                        </div>
                        <div class="col-6 col-md-3 p-3 text-center">
                            <div class="text-muted small mb-1">Discount</div>
                            <div class="fw-bold fs-6 text-info">
                                {{ number_format($product->discount_percentage ?? 0, 2) }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Specifications --}}
            @if (count($specs))
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-transparent py-2 px-3 border-bottom">
                        <span class="fw-semibold small"><i class="bx bx-chip me-1 text-warning"></i>Technical
                            Specifications</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            @foreach ($specs as $key => $val)
                                <div class="col-md-6">
                                    <div class="spec-row">
                                        <span class="spec-key">{{ $key }}</span>
                                        <span class="spec-val">{{ $val }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Timestamps --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="row g-0 text-center">
                        <div class="col-6 border-end">
                            <div class="text-muted small mb-1"><i class="bx bx-calendar-plus me-1"></i>Created</div>
                            <div class="fw-semibold small">{{ $product->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1"><i class="bx bx-calendar-edit me-1"></i>Last Updated</div>
                            <div class="fw-semibold small">{{ $product->updated_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Transaction History ─────────────────────── --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-2 px-3 border-bottom">
                    <span class="fw-semibold small"><i class="bx bx-history me-1 text-primary"></i>Transaction
                        History</span>
                </div>
                <div class="card-body p-0">
                    <ul class="nav txn-tabs px-3 pt-2" id="txnTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-purchases"
                                type="button">
                                <i class="bx bx-cart-add me-1"></i>Purchases
                                <span class="badge bg-label-primary ms-1">{{ $purchases->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-sales" type="button">
                                <i class="bx bx-receipt me-1"></i>Sales
                                <span class="badge bg-label-success ms-1">{{ $sales->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-adjustments"
                                type="button">
                                <i class="bx bx-slider me-1"></i>Adjustments
                                <span class="badge bg-label-warning ms-1">{{ $adjustments->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-purreturns"
                                type="button">
                                <i class="bx bx-undo me-1"></i>Purchase Returns
                                <span class="badge bg-label-info ms-1">{{ $purReturns->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-salereturns"
                                type="button">
                                <i class="bx bx-undo me-1"></i>Sale Returns
                                <span class="badge bg-label-danger ms-1">{{ $saleReturns->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content">

                        {{-- ── Purchases Tab ── --}}
                        <div class="tab-pane fade show active" id="tab-purchases">
                            @if ($purchases->isEmpty())
                                <div class="text-center py-4 text-muted small">
                                    <i class="bx bx-cart-add" style="font-size:2rem;opacity:.2;display:block;"></i>
                                    No purchase records yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table txn-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Purchase #</th>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Unit Price</th>
                                                <th class="text-end">Total</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purchases as $item)
                                                @php $pur = $item->purchase; @endphp
                                                <tr>
                                                    <td><code class="small">{{ $pur?->purchase_no ?? '—' }}</code></td>
                                                    <td class="text-muted">
                                                        {{ $pur?->purchase_date ? \Carbon\Carbon::parse($pur->purchase_date)->format('d M Y') : '—' }}
                                                    </td>
                                                    <td>{{ $pur?->supplier?->name ?? '—' }}</td>
                                                    <td class="text-end fw-semibold">
                                                        {{ number_format($item->quantity, 2) }} <small
                                                            class="text-muted">{{ $product->unit_code }}</small></td>
                                                    <td class="text-end">{{ format_currency($item->purchase_price) }}</td>
                                                    <td class="text-end fw-bold text-primary">
                                                        {{ format_currency($item->total_amount) }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill {{ $pur?->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $pur?->status ?? '—' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($pur)
                                                            <a href="{{ route('purchases.show', $pur->id) }}"
                                                                class="btn btn-sm btn-outline-primary py-0 px-2"
                                                                style="font-size:.7rem;">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:rgba(105,108,255,.03);">
                                                <td colspan="3" class="fw-bold small text-end text-muted">Totals:</td>
                                                <td class="text-end fw-bold">
                                                    {{ number_format($purchases->sum('quantity'), 2) }}</td>
                                                <td></td>
                                                <td class="text-end fw-bold text-primary">
                                                    {{ format_currency($purchases->sum('total_amount')) }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- ── Sales Tab ── --}}
                        <div class="tab-pane fade" id="tab-sales">
                            @if ($sales->isEmpty())
                                <div class="text-center py-4 text-muted small">
                                    <i class="bx bx-receipt" style="font-size:2rem;opacity:.2;display:block;"></i>
                                    No sales records yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table txn-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Unit Price</th>
                                                <th class="text-end">Total</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sales as $item)
                                                @php $sale = $item->sale; @endphp
                                                <tr>
                                                    <td><code class="small">{{ $sale?->invoice_no ?? '—' }}</code></td>
                                                    <td class="text-muted">
                                                        {{ $sale?->invoice_date ? \Carbon\Carbon::parse($sale->invoice_date)->format('d M Y') : '—' }}
                                                    </td>
                                                    <td>{{ $sale?->customer?->name ?? 'Walk-in' }}</td>
                                                    <td class="text-end fw-semibold">
                                                        {{ number_format($item->quantity, 2) }} <small
                                                            class="text-muted">{{ $product->unit_code }}</small></td>
                                                    <td class="text-end">{{ format_currency($item->unit_price) }}</td>
                                                    <td class="text-end fw-bold text-success">
                                                        {{ format_currency($item->total_amount) }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill {{ $sale?->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $sale?->status ?? '—' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($sale)
                                                            <a href="{{ route('sales.show', $sale->id) }}"
                                                                class="btn btn-sm btn-outline-success py-0 px-2"
                                                                style="font-size:.7rem;">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background:rgba(40,199,111,.04);">
                                                <td colspan="3" class="fw-bold small text-end text-muted">Totals:</td>
                                                <td class="text-end fw-bold">
                                                    {{ number_format($sales->sum('quantity'), 2) }}</td>
                                                <td></td>
                                                <td class="text-end fw-bold text-success">
                                                    {{ format_currency($sales->sum('total_amount')) }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- ── Stock Adjustments Tab ── --}}
                        <div class="tab-pane fade" id="tab-adjustments">
                            @if ($adjustments->isEmpty())
                                <div class="text-center py-4 text-muted small">
                                    <i class="bx bx-slider" style="font-size:2rem;opacity:.2;display:block;"></i>
                                    No stock adjustments yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table txn-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Voucher #</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th class="text-end">Qty Change</th>
                                                <th>Notes</th>
                                                <th>By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($adjustments as $adj)
                                                @php
                                                    $isPos = $adj->quantity_change >= 0;
                                                    $adjColor = $isPos ? 'text-success' : 'text-danger';
                                                    $adjBadge = match ($adj->adjustment_type) {
                                                        'Restock', 'Addition' => 'bg-label-success',
                                                        'Damage', 'Removal' => 'bg-label-danger',
                                                        default => 'bg-label-secondary',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td><code class="small">{{ $adj->voucher_no ?? '—' }}</code></td>
                                                    <td class="text-muted">
                                                        {{ $adj->transaction_date ? \Carbon\Carbon::parse($adj->transaction_date)->format('d M Y') : '—' }}
                                                    </td>
                                                    <td><span
                                                            class="badge {{ $adjBadge }}">{{ $adj->adjustment_type }}</span>
                                                    </td>
                                                    <td class="text-end fw-bold {{ $adjColor }}">
                                                        {{ $isPos ? '+' : '' }}{{ number_format($adj->quantity_change, 2) }}
                                                        <small
                                                            class="text-muted fw-normal">{{ $product->unit_code }}</small>
                                                    </td>
                                                    <td class="text-muted"
                                                        style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                                        title="{{ $adj->notes }}">
                                                        {{ $adj->notes ?? '—' }}
                                                    </td>
                                                    <td class="text-muted">{{ $adj->user?->name ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- ── Purchase Returns Tab ── --}}
                        <div class="tab-pane fade" id="tab-purreturns">
                            @if ($purReturns->isEmpty())
                                <div class="text-center py-4 text-muted small">
                                    <i class="bx bx-undo" style="font-size:2rem;opacity:.2;display:block;"></i>
                                    No purchase returns yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table txn-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Return #</th>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Total</th>
                                                <th>Reason</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purReturns as $item)
                                                @php $ret = $item->purchaseReturn; @endphp
                                                <tr>
                                                    <td><code class="small">{{ $ret?->return_no ?? '—' }}</code></td>
                                                    <td class="text-muted">
                                                        {{ $ret?->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : '—' }}
                                                    </td>
                                                    <td>{{ $ret?->supplier?->name ?? '—' }}</td>
                                                    <td class="text-end fw-semibold text-warning">
                                                        {{ number_format($item->quantity, 2) }} <small
                                                            class="text-muted">{{ $product->unit_code }}</small></td>
                                                    <td class="text-end fw-bold text-warning">
                                                        {{ format_currency($item->total_amount) }}</td>
                                                    <td class="text-muted small"
                                                        style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                                        title="{{ $item->reason }}">
                                                        {{ $item->reason ?? '—' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill {{ $ret?->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $ret?->status ?? '—' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- ── Sale Returns Tab ── --}}
                        <div class="tab-pane fade" id="tab-salereturns">
                            @if ($saleReturns->isEmpty())
                                <div class="text-center py-4 text-muted small">
                                    <i class="bx bx-undo" style="font-size:2rem;opacity:.2;display:block;"></i>
                                    No sale returns yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table txn-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Return #</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Total</th>
                                                <th>Reason</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saleReturns as $item)
                                                @php $ret = $item->saleReturn; @endphp
                                                <tr>
                                                    <td><code class="small">{{ $ret?->return_no ?? '—' }}</code></td>
                                                    <td class="text-muted">
                                                        {{ $ret?->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : '—' }}
                                                    </td>
                                                    <td>{{ $ret?->customer?->name ?? '—' }}</td>
                                                    <td class="text-end fw-semibold text-danger">
                                                        {{ number_format($item->quantity, 2) }} <small
                                                            class="text-muted">{{ $product->unit_code }}</small></td>
                                                    <td class="text-end fw-bold text-danger">
                                                        {{ format_currency($item->total_amount) }}</td>
                                                    <td class="text-muted small"
                                                        style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                                        title="{{ $item->reason }}">
                                                        {{ $item->reason ?? '—' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge rounded-pill {{ $ret?->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $ret?->status ?? '—' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>{{-- end tab-content --}}
                </div>{{-- end card-body --}}
            </div>{{-- end Transaction card --}}

        </div>{{-- end centre col --}}

        {{-- ═══════════════ RIGHT — QUICK ACTIONS ══════════════ --}}
        <div class="col-xl-3 col-lg-12">

            {{-- Information Card --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bx bx-info-circle text-primary"></i>
                    <span class="fw-semibold small">Information</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0" style="font-size:.78rem;">
                        <tbody>
                            <tr>
                                <td class="text-muted ps-3 py-2">ID</td>
                                <td class="fw-semibold text-end pe-3 py-2">#{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">SKU</td>
                                <td class="fw-semibold text-end pe-3 py-2"><code>{{ $product->code }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">Brand</td>
                                <td class="fw-semibold text-end pe-3 py-2">{{ $product->brand->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">Category</td>
                                <td class="text-end pe-3 py-2">
                                    <span class="badge bg-label-secondary"
                                        style="font-size:.68rem;">{{ $product->mainCategory->name ?? '—' }}</span>
                                </td>
                            </tr>
                            @if ($product->subCategory)
                                <tr>
                                    <td class="text-muted ps-3 py-2">Sub Category</td>
                                    <td class="text-end pe-3 py-2">
                                        <span class="badge bg-label-info"
                                            style="font-size:.68rem;">{{ $product->subCategory->name }}</span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-muted ps-3 py-2">Unit</td>
                                <td class="fw-semibold text-end pe-3 py-2">{{ $product->unit_name ?? '—' }}
                                    ({{ $product->unit_code ?? '—' }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">Status</td>
                                <td class="text-end pe-3 py-2">
                                    <span
                                        class="badge rounded-pill {{ $product->status === 'active' ? 'bg-label-success' : 'bg-label-danger' }}"
                                        style="font-size:.68rem;">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">Stock</td>
                                <td class="fw-bold text-end pe-3 py-2 {{ $sCls }}">{{ number_format($qty, 2) }}
                                    {{ $product->unit_code }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">Created</td>
                                <td class="fw-semibold text-end pe-3 py-2">{{ $product->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-3 py-2">Updated</td>
                                <td class="fw-semibold text-end pe-3 py-2">{{ $product->updated_at->format('d M Y') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-2 px-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bx bx-zap text-warning"></i>
                    <span class="fw-semibold small">Quick Actions</span>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2">

                    @can('products.update')
                        <a href="{{ route('products.edit', $product->id) }}"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bx bx-edit"></i> Edit Product
                        </a>
                    @endcan

                    @can('products.create')
                        <a href="{{ route('products.copy', $product->id) }}"
                            class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bx bx-copy"></i> Copy Product
                        </a>
                    @endcan

                    @can('products.create')
                        <a href="{{ route('products.create') }}"
                            class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bx bx-plus"></i> Add Product
                        </a>
                    @endcan

                    @can('purchases.create')
                        <a href="{{ route('purchases.create') }}"
                            class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bx bx-cart-add"></i> New Purchase
                        </a>
                    @endcan

                    @can('sales.create')
                        <a href="{{ route('sales.create') }}"
                            class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bx bx-receipt"></i> New Sale
                        </a>
                    @endcan

                    <a href="{{ route('stocks.index') }}"
                        class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bx bx-slider"></i> Stock Management
                    </a>

                    <hr class="my-1">

                    <a href="{{ route('products.index') }}"
                        class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>

                    @can('products.delete')
                        <button type="button" id="deleteBtn"
                            class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bx bx-trash"></i> Delete Product
                        </button>
                    @endcan

                </div>
            </div>

        </div>{{-- end quick actions col --}}

    </div>{{-- end row --}}

    {{-- Hidden delete form (submitted by Quick Actions delete button) --}}
    @can('products.delete')
        <form id="deleteForm" action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-none">
            @csrf @method('DELETE')
        </form>
    @endcan

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ── Thumbnail image switcher ──────────────────────────
            $('.thumb-item').on('click', function() {
                $('#primaryViewer').attr('src', $(this).data('src'));
                $('.thumb-item').removeClass('active');
                $(this).addClass('active');
            });

            // ── Delete confirmation ──────────────────────────────
            $('#deleteBtn').on('click', function() {
                Swal.fire({
                    title: 'Delete Product?',
                    html: 'This will soft-delete <strong>{{ addslashes($product->name) }}</strong>.<br>Transaction records will be preserved.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(r => {
                    if (r.isConfirmed) $('#deleteForm').submit();
                });
            });

            // ── Persist active tab in localStorage ───────────────
            const tabKey = 'product_show_tab_{{ $product->id }}';
            const savedTab = localStorage.getItem(tabKey);
            if (savedTab) {
                const el = document.querySelector(`[data-bs-target="${savedTab}"]`);
                if (el) bootstrap.Tab.getOrCreateInstance(el).show();
            }
            document.querySelectorAll('#txnTabs button[data-bs-toggle="tab"]').forEach(btn => {
                btn.addEventListener('shown.bs.tab', e => {
                    localStorage.setItem(tabKey, e.target.dataset.bsTarget);
                });
            });

        });
    </script>
@endpush
