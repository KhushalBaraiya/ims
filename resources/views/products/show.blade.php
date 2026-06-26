@extends('layouts.admin')
@section('title', 'Product — ' . $product->name)

@push('styles')
    <style>
        .img-viewer-wrap {
            position: relative;
            height: 300px;
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
            transform: scale(1.03);
        }

        .thumb-strip {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-top: .75rem;
        }

        .thumb-item {
            width: 60px;
            height: 60px;
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

        .stat-chip {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem .75rem;
            border-radius: 12px;
            min-width: 0;
            background: #f8f9ff;
            border: 1px solid rgba(105, 108, 255, .1);
            flex: 1;
        }

        .stat-chip .val {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-chip .lbl {
            font-size: .68rem;
            color: #9099a8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-top: .2rem;
        }

        .spec-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .55rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .spec-row:last-child {
            border-bottom: none;
        }

        .spec-row .spec-key {
            font-size: .8rem;
            color: #697a8d;
        }

        .spec-row .spec-val {
            font-size: .82rem;
            font-weight: 600;
            text-align: right;
        }

        .profit-bar-wrap {
            background: rgba(0, 0, 0, .06);
            border-radius: 99px;
            height: 6px;
            overflow: hidden;
        }

        .profit-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #696cff, #9c3fe4);
            transition: width .5s;
        }

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
    </style>
@endpush

@section('content')
    @php
        $qty = (float) ($product->stock->quantity ?? 0);
        $alert = (float) ($product->minimum_stock_alert ?? 0);
        $isOut = $qty <= 0;
        $isLow = !$isOut && $qty <= $alert;
        $profit = $product->selling_price - $product->purchase_price;
        $margin = $product->selling_price > 0 ? round(($profit / $product->selling_price) * 100, 1) : 0;
        $markup = $product->purchase_price > 0 ? round(($profit / $product->purchase_price) * 100, 1) : 0;
        $sCls = $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success');
        $sBadge = $isOut ? 'bg-danger' : ($isLow ? 'bg-warning text-dark' : 'bg-success');
        $sLabel = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');
    @endphp

    {{-- Header --}}
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

    <div class="row g-4">

        {{-- ── LEFT COLUMN ─────────────────────────── --}}
        <div class="col-xl-4 col-lg-5">

            {{-- Image viewer --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="img-viewer-wrap">
                        <img id="primaryViewer"
                            src="{{ $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image' }}"
                            alt="{{ $product->name }}">
                    </div>
                    @php$allImgs = [];
                        if ($product->image) {
                            $allImgs[] = $product->image;
                        }
                        if ($product->gallery) {
                            $allImgs = array_merge($allImgs, $product->gallery);
                    } @endphp
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

            {{-- Quick stats chips --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex gap-2">
                        <div class="stat-chip">
                            <div class="val text-primary">{{ format_currency($product->selling_price) }}</div>
                            <div class="lbl">Sell Price</div>
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
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Profit Margin</small>
                            <small
                                class="fw-bold {{ $margin >= 0 ? 'text-success' : 'text-danger' }}">{{ $margin }}%</small>
                        </div>
                        <div class="profit-bar-wrap">
                            <div class="profit-bar"
                                style="width:{{ min(abs($margin), 100) }}%; background: {{ $margin < 0 ? '#ea5455' : 'linear-gradient(90deg,#696cff,#9c3fe4)' }};">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock status card --}}
            <div class="card shadow-sm mb-3 border-0"
                style="background: {{ $isOut ? 'linear-gradient(135deg,#fff5f5,#ffe0e0)' : ($isLow ? 'linear-gradient(135deg,#fffbf0,#fff0d0)' : 'linear-gradient(135deg,#f0fff4,#dcfce7)') }}">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:48px;height:48px;background:{{ $isOut ? 'rgba(234,84,85,.15)' : ($isLow ? 'rgba(255,171,0,.15)' : 'rgba(40,199,111,.15)') }}">
                        <i class="bx {{ $isOut ? 'bx-x-circle text-danger' : ($isLow ? 'bx-error-circle text-warning' : 'bx-check-circle text-success') }}"
                            style="font-size:1.5rem;"></i>
                    </div>
                    <div>
                        <div class="fw-bold {{ $sCls }}">{{ $sLabel }}</div>
                        <div class="text-muted small">
                            @if ($product->hasTransactions())
                                {{ number_format($qty, 2) }} {{ $product->unit_code ?? 'units' }} on hand
                                · Alert at {{ number_format($alert, 2) }}
                            @else
                                No transactions yet
                            @endif
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
        </div>

        {{-- ── RIGHT COLUMN ────────────────────────── --}}
        <div class="col-xl-8 col-lg-7">

            {{-- Product identity card --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
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
                            <h4 class="fw-bold mb-1 lh-sm">{{ $product->name }}</h4>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="bx bx-barcode me-1"></i>SKU: <code>{{ $product->code }}</code></span>
                                @if ($product->barcode)
                                    <span><i class="bx bx-qr-scan me-1"></i>Barcode:
                                        <code>{{ $product->barcode }}</code></span>
                                @endif
                                <span><i class="bx bx-ruler me-1"></i>Unit: {{ $product->unit_name ?? '-' }}
                                    ({{ $product->unit_code ?? '-' }})</span>
                            </div>
                        </div>
                        <span
                            class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }} flex-shrink-0">
                            <i
                                class="bx {{ $product->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($product->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Pricing breakdown --}}
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
            @php
                $specs = array_filter([
                    'Manufacturer' => $product->manufacturer,
                    'Model Number' => $product->model_number,
                    'Part / MPN' => $product->part_number,
                    'Warranty' => $product->warranty,
                    'Color' => $product->color,
                    'Weight' => $product->weight,
                    'Country of Origin' => $product->country_of_origin,
                ]);
            @endphp
            @if (count($specs))
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-transparent py-2 px-3 border-bottom">
                        <span class="fw-semibold small"><i class="bx bx-chip me-1 text-warning"></i>Specifications</span>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($specs as $key => $val)
                            <div class="spec-row">
                                <span class="spec-key">{{ $key }}</span>
                                <span class="spec-val">{{ $val }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Timestamps --}}
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
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

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.thumb-item').on('click', function() {
                $('#primaryViewer').attr('src', $(this).data('src'));
                $('.thumb-item').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
@endpush
