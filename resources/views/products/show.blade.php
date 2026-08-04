@extends('layouts.admin')
@section('title', 'Product � ' . $product->name)

@push('styles')
    {{-- Swiper --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    {{-- GLightbox --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <style>
        /* -- Swiper main -- */
        .prod-swiper-main {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            overflow: hidden;
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            position: relative;
        }

        .prod-swiper-main .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bs-body-bg);
        }

        .prod-swiper-main .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            cursor: zoom-in;
            transition: transform .3s ease;
        }

        .prod-swiper-main .swiper-slide img:hover {
            transform: scale(1.04);
        }

        /* navigation arrows */
        .prod-swiper-main .swiper-button-prev,
        .prod-swiper-main .swiper-button-next {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, .9);
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
            color: #333;
            --swiper-navigation-size: 15px;
        }

        .prod-swiper-main .swiper-button-prev {
            left: 8px;
        }

        .prod-swiper-main .swiper-button-next {
            right: 8px;
        }

        /* pagination dots */
        .prod-swiper-main .swiper-pagination-bullet-active {
            background: var(--bs-primary);
        }

        /* counter badge */
        .swiper-counter {
            position: absolute;
            bottom: 8px;
            right: 10px;
            background: rgba(0, 0, 0, .45);
            color: #fff;
            font-size: .68rem;
            padding: 2px 8px;
            border-radius: 20px;
            z-index: 10;
            pointer-events: none;
            backdrop-filter: blur(4px);
        }

        /* expand icon */
        .swiper-expand-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, .85);
            border-radius: 6px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .18);
            transition: background .15s;
        }

        .swiper-expand-btn:hover {
            background: #fff;
        }

        /* -- Swiper thumbs -- */
        .prod-swiper-thumbs {
            margin-top: 10px;
        }

        .prod-swiper-thumbs .swiper-slide {
            width: 56px !important;
            height: 56px !important;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid var(--bs-border-color);
            opacity: .5;
            transition: opacity .18s, border-color .18s;
        }

        .prod-swiper-thumbs .swiper-slide-thumb-active {
            opacity: 1;
            border-color: var(--bs-primary);
        }

        .prod-swiper-thumbs .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* -- No-image placeholder -- */
        .prod-no-image {
            aspect-ratio: 1/1;
            border-radius: 12px;
            border: 1px solid var(--bs-border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bs-body-bg);
        }

        /* dark mode */
        [data-bs-theme="dark"] .prod-swiper-main .swiper-button-prev,
        [data-bs-theme="dark"] .prod-swiper-main .swiper-button-next {
            background: rgba(40, 40, 60, .85);
            color: #ccc;
        }

        [data-bs-theme="dark"] .swiper-expand-btn {
            background: rgba(40, 40, 60, .85);
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
            __('messages.prod_manufacturer') => $product->manufacturer,
            __('messages.prod_model_number') => $product->model_number,
            __('messages.prod_part_serial') => $product->part_number,
            __('messages.prod_warranty') => $product->warranty,
            __('messages.prod_color') => $product->color,
            __('messages.prod_weight') => $product->weight,
            __('messages.prod_country_origin') => $product->country_of_origin,
        ]);

        $purchases = $product->purchaseItems->sortByDesc(fn($i) => $i->purchase?->purchase_date);
        $sales = $product->saleItems->sortByDesc(fn($i) => $i->sale?->invoice_date);
        $adjustments = $product->stockAdjustments->sortByDesc('transaction_date');
        $purReturns = $product->purchaseReturnItems->sortByDesc(fn($i) => $i->purchaseReturn?->return_date);
        $saleReturns = $product->saleReturnItems->sortByDesc(fn($i) => $i->saleReturn?->return_date);
    @endphp

    {{-- -- Page Header -- --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.prod_detail') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('products.index') }}">{{ __('messages.menu_products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 35) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @can('stocks.create')
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#quickAdjustModal">
                    <i class="bx bx-plus-circle me-1"></i>{{ __('messages.quick_adjust_btn') }}
                </button>
                <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}" class="btn btn-outline-warning btn-sm">
                    <i class="bx bx-slider me-1"></i>{{ __('messages.prod_adjust_stock_btn') }}
                </a>
            @endcan
            @can('products.create')
                <a href="{{ route('products.copy', $product->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-copy me-1"></i>{{ __('messages.prod_copy_label') }}
                </a>
            @endcan
            @can('products.update')
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-edit me-1"></i>{{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i>{{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            @if ($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}"
                    class="rounded-circle border border-2 border-white flex-shrink-0"
                    style="width:52px;height:52px;object-fit:cover;"
                    onerror="this.outerHTML='<div class=\'rounded-circle border border-2 border-white d-flex align-items-center justify-content-center\' style=\'width:52px;height:52px;background:rgba(255,255,255,.2)\'><i class=\'bx bx-package text-white fs-4\'></i></div>'">
            @else
                <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:52px;height:52px;background:rgba(255,255,255,.2)">
                    <i class="bx bx-package text-white fs-4"></i>
                </div>
            @endif
            <div class="flex-grow-1 min-w-0">
                <div class="text-white fw-bold fs-6">{{ $product->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-barcode me-1"></i>{{ $product->code }}</span>
                    @if ($product->mainCategory)
                        <span>� {{ $product->mainCategory->name }}</span>
                    @endif
                    @if ($product->brand)
                        <span>� {{ $product->brand->name }}</span>
                    @endif
                    @if ($product->barcode)
                        <span>� {{ $product->barcode }}</span>
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

    {{-- -- Main content row -- --}}
    <div class="row g-4">

        {{-- --------------------------------------------------------
             LEFT  col-lg-8
        -------------------------------------------------------- --}}
        <div class="col-lg-8">

            {{-- -- Product Detail Card -- --}}
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
                    <div class="row g-4">

                        {{-- -- Image Swiper (left) -- --}}
                        <div class="col-12 col-sm-5">
                            @if ($allImgs)
                                {{-- Hidden lightbox anchors --}}
                                <div class="d-none" id="lightboxLinks">
                                    @foreach ($allImgs as $idx => $img)
                                        <a class="glightbox-prod" href="{{ asset('uploads/products/' . $img) }}"
                                            data-gallery="prod-gallery"
                                            data-glightbox="description: {{ $product->name }} ({{ $idx + 1 }}/{{ count($allImgs) }})"
                                            data-index="{{ $idx }}"></a>
                                    @endforeach
                                </div>

                                {{-- Main swiper --}}
                                <div class="prod-swiper-main mb-2" id="productSwiperMain">
                                    <div class="swiper-wrapper">
                                        @foreach ($allImgs as $idx => $img)
                                            <div class="swiper-slide">
                                                <img src="{{ asset('uploads/products/' . $img) }}"
                                                    alt="{{ $product->name }} {{ $idx + 1 }}"
                                                    loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                                                    data-lightbox-idx="{{ $idx }}"
                                                    onerror="this.parentElement.innerHTML='<div class=\'w-100 h-100 d-flex align-items-center justify-content-center\'><i class=\'bx bx-image-alt text-muted\' style=\'font-size:3.5rem;opacity:.25;\'></i></div>'">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($allImgs) > 1)
                                        <div class="swiper-button-prev"></div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-pagination"></div>
                                        <span class="swiper-counter" id="swiperCounter">1 / {{ count($allImgs) }}</span>
                                    @endif
                                    {{-- Expand / lightbox button --}}
                                    <button class="swiper-expand-btn" id="swiperExpandBtn"
                                        title="{{ __('messages.view_fullscreen') }}">
                                        <i class="bx bx-expand-alt" style="font-size:.95rem;color:#555;"></i>
                                    </button>
                                </div>

                                {{-- Thumbs swiper (below main) --}}
                                @if (count($allImgs) > 1)
                                    <div class="swiper prod-swiper-thumbs" id="productSwiperThumbs">
                                        <div class="swiper-wrapper">
                                            @foreach ($allImgs as $img)
                                                <div class="swiper-slide">
                                                    <img src="{{ asset('uploads/products/' . $img) }}" alt="thumb"
                                                        onerror="this.closest('.swiper-slide').style.display='none'">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="prod-no-image">
                                    <i class="bx bx-package text-primary" style="font-size:5rem;opacity:.3;"></i>
                                </div>
                            @endif
                        </div>

                        {{-- -- Product info (right of carousel) -- --}}
                        <div class="col-12 col-sm-7 d-flex flex-column gap-3">
                            {{-- Category / Brand badges --}}
                            <div class="d-flex flex-wrap gap-1">
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

                            {{-- Name + meta --}}
                            <div>
                                <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                                <div class="d-flex flex-wrap gap-2 text-muted small mt-1">
                                    <span><i class="bx bx-barcode me-1"></i>{{ __('messages.sku') }}: <code
                                            class="text-primary fw-semibold">{{ $product->code }}</code></span>
                                    @if ($product->barcode)
                                        <span><i class="bx bx-qr-scan me-1"></i>{{ __('messages.barcode') }}:
                                            <code>{{ $product->barcode }}</code></span>
                                    @endif
                                    <span><i class="bx bx-ruler me-1"></i>{{ $product->unit_name ?? '�' }}
                                        ({{ $product->unit_code ?? '�' }})</span>
                                    <span><i
                                            class="bx bx-calendar me-1"></i>{{ $product->created_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            {{-- Stock status badge --}}
                            <div>
                                <span class="badge {{ $sBadge }} fs-6 px-3 py-2">
                                    <i class="bx {{ $sIcon }} me-1"></i>{{ $sLabel }}
                                    &nbsp;�&nbsp;<strong>{{ number_format($qty, 0) }}</strong>
                                    {{ $product->unit_code ?? 'PCS' }}
                                </span>
                            </div>

                            {{-- Price stats --}}
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="rounded-3 p-3 text-center bg-label-success">
                                        <div class="fw-bold text-success" style="font-size:1.1rem;">
                                            {{ format_currency($product->selling_price) }}</div>
                                        <div class="text-muted small mt-1">{{ __('messages.prod_sell_price') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3 text-center bg-label-warning">
                                        <div class="fw-bold text-warning" style="font-size:1.1rem;">
                                            {{ format_currency($product->purchase_price) }}</div>
                                        <div class="text-muted small mt-1">{{ __('messages.prod_cost_price') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3 text-center bg-label-info">
                                        <div class="fw-bold {{ $profit >= 0 ? 'text-info' : 'text-danger' }}"
                                            style="font-size:1.1rem;">{{ format_currency($profit) }}</div>
                                        <div class="text-muted small mt-1">{{ __('messages.prod_profit_lbl') }}
                                            ({{ $margin }}%)</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 p-3 text-center bg-label-primary">
                                        <div class="fw-bold fs-4 text-primary">{{ number_format($qty, 0) }}</div>
                                        <div class="text-muted small mt-1">{{ __('messages.th_stock') }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            @if ($product->short_description || $product->full_description)
                                <div class="border-top pt-2">
                                    @if ($product->short_description)
                                        <p class="text-muted small fw-semibold mb-1">
                                            {{ __('messages.description_label') }}</p>
                                        <p class="small mb-1">{{ $product->short_description }}</p>
                                    @endif
                                    @if ($product->full_description)
                                        <p class="small mb-0" style="white-space:pre-line;">
                                            {{ $product->full_description }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>{{-- /info col --}}
                    </div>{{-- /row inside card --}}
                </div>{{-- /card-body --}}
            </div>{{-- /Product Detail Card --}}

            {{-- -- Technical Specifications -- --}}
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

            {{-- -- Transaction History (tabs) -- --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" id="txnTabs" role="tablist"
                        style="border-bottom:none;gap:.2rem;">
                        <li class="nav-item">
                            <button class="nav-link active px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-pur" type="button">
                                <i class="bx bx-cart-add me-1"></i>{{ __('messages.prod_tab_purchases') }}
                                <span class="badge bg-label-primary ms-1">{{ $purchases->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-sal" type="button">
                                <i class="bx bx-receipt me-1"></i>{{ __('messages.prod_tab_sales') }}
                                <span class="badge bg-label-success ms-1">{{ $sales->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-adj" type="button">
                                <i class="bx bx-slider me-1"></i>{{ __('messages.prod_tab_adjustments') }}
                                <span class="badge bg-label-warning ms-1">{{ $adjustments->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-pr" type="button">
                                <i class="bx bx-undo me-1"></i>{{ __('messages.prod_tab_pur_returns') }}
                                <span class="badge bg-label-info ms-1">{{ $purReturns->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link px-3 py-2 small fw-semibold" data-bs-toggle="tab"
                                data-bs-target="#tab-sr" type="button">
                                <i class="bx bx-undo me-1"></i>{{ __('messages.prod_tab_sal_returns') }}
                                <span class="badge bg-label-danger ms-1">{{ $saleReturns->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">

                    {{-- Purchases tab --}}
                    <div class="tab-pane fade show active" id="tab-pur">
                        @if ($purchases->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-cart-add d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <span class="small">{{ __('messages.prod_no_purchase_records') }}</span>
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
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchases as $item)
                                            @php $pur = $item->purchase; @endphp
                                            <tr>
                                                <td><code
                                                        class="small text-primary fw-semibold">{{ $pur?->purchase_no ?? '�' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $pur?->purchase_date ? \Carbon\Carbon::parse($pur->purchase_date)->format('d M Y') : '�' }}
                                                </td>
                                                <td class="fw-semibold small">{{ $pur?->supplier?->name ?? '�' }}</td>
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
                                                    @elseif ($pur?->status === 'pending')
                                                        <span
                                                            class="badge bg-warning text-dark rounded-pill">{{ __('messages.prod_pending_status') }}</span>
                                                    @elseif ($pur?->status === 'ordered')
                                                        <span
                                                            class="badge bg-primary rounded-pill">{{ __('messages.prod_ordered') }}</span>
                                                    @elseif ($pur?->status)
                                                        <span
                                                            class="badge bg-secondary rounded-pill">{{ $pur->status }}</span>
                                                    @else
                                                        <span class="text-muted">�</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($pur)
                                                        <a href="{{ route('purchases.show', $pur->id) }}"
                                                            class="btn btn-sm btn-icon btn-outline-info rounded-circle"
                                                            style="width:28px;height:28px;padding:0;">
                                                            <i class="bx bx-show" style="font-size:.85rem;"></i>
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

                    {{-- Sales tab --}}
                    <div class="tab-pane fade" id="tab-sal">
                        @if ($sales->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-receipt d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <span class="small">{{ __('messages.prod_no_sales_records') }}</span>
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
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales as $item)
                                            @php $sale = $item->sale; @endphp
                                            <tr>
                                                <td><code
                                                        class="small text-success fw-semibold">{{ $sale?->invoice_no ?? '�' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $sale?->invoice_date ? \Carbon\Carbon::parse($sale->invoice_date)->format('d M Y') : '�' }}
                                                </td>
                                                <td class="fw-semibold small">
                                                    {{ $sale?->customer?->name ?? __('messages.prod_walk_in') }}</td>
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
                                                    @elseif ($sale?->payment_status === 'Partial')
                                                        <span
                                                            class="badge bg-warning text-dark rounded-pill">{{ __('messages.partial') }}</span>
                                                    @elseif ($sale)
                                                        <span
                                                            class="badge bg-danger rounded-pill">{{ __('messages.unpaid') }}</span>
                                                    @else
                                                        <span class="text-muted">�</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($sale)
                                                        <a href="{{ route('sales.show', $sale->id) }}"
                                                            class="btn btn-sm btn-icon btn-outline-success rounded-circle"
                                                            style="width:28px;height:28px;padding:0;">
                                                            <i class="bx bx-show" style="font-size:.85rem;"></i>
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

                    {{-- Adjustments tab --}}
                    <div class="tab-pane fade" id="tab-adj">
                        @if ($adjustments->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-slider d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <span class="small">{{ __('messages.prod_no_adj_records') }}</span>
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
                                                <td><code class="small">{{ $adj->voucher_no ?? '�' }}</code></td>
                                                <td class="text-muted small">
                                                    {{ $adj->transaction_date ? \Carbon\Carbon::parse($adj->transaction_date)->format('d M Y') : $adj->created_at->format('d M Y') }}
                                                </td>
                                                <td>
                                                    @if ($adj->adjustment_type === 'Plus')
                                                        <span
                                                        class="badge bg-success">{{ __('messages.adj_add_badge') }}</span>@else<span
                                                            class="badge bg-danger">{{ __('messages.adj_remove_badge') }}</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="text-end fw-bold {{ $adj->quantity_change >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $adj->quantity_change >= 0 ? '+' : '' }}{{ number_format($adj->quantity_change, 2) }}
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $adj->user?->name ?? __('messages.system') }}</td>
                                                <td class="text-muted small">{{ Str::limit($adj->notes ?? '�', 35) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Purchase Returns tab --}}
                    <div class="tab-pane fade" id="tab-pr">
                        @if ($purReturns->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-cart-download d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <span class="small">{{ __('messages.prod_no_pur_return_records') }}</span>
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
                                                        class="small text-info fw-semibold">{{ $ret?->return_no ?? '�' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $ret?->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : '�' }}
                                                </td>
                                                <td class="small">{{ $ret?->purchase?->supplier?->name ?? '�' }}</td>
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

                    {{-- Sale Returns tab --}}
                    <div class="tab-pane fade" id="tab-sr">
                        @if ($saleReturns->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-undo d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <span class="small">{{ __('messages.prod_no_sal_return_records') }}</span>
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
                                                        class="small text-danger fw-semibold">{{ $ret?->return_no ?? '�' }}</code>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $ret?->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : '�' }}
                                                </td>
                                                <td class="small">
                                                    {{ $ret?->sale?->customer?->name ?? __('messages.prod_walk_in') }}
                                                </td>
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

        {{-- --------------------------------------------------------
             RIGHT  col-lg-4
        -------------------------------------------------------- --}}
        <div class="col-lg-4">

            {{-- Information Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}</h6>
                </div>
                <div class="card-body p-0">
                    @php
                        $infoRows = [
                            ['label' => 'ID', 'value' => '#' . $product->id, 'type' => 'text'],
                            ['label' => __('messages.sku'), 'value' => $product->code, 'type' => 'code-primary'],
                            ['label' => __('messages.barcode'), 'value' => $product->barcode, 'type' => 'code'],
                            ['label' => __('messages.th_status'), 'value' => $product->status, 'type' => 'status'],
                            [
                                'label' => __('messages.stock_status_col'),
                                'value' => [$sBadge, $sLabel],
                                'type' => 'stock-badge',
                            ],
                            [
                                'label' => __('messages.th_stock'),
                                'value' => number_format($qty, 2) . ' ' . ($product->unit_code ?? 'PCS'),
                                'type' => $sCls,
                            ],
                            [
                                'label' => __('messages.min_stock_alert'),
                                'value' =>
                                    $alert > 0 ? number_format($alert, 0) . ' ' . ($product->unit_code ?? '') : null,
                                'type' => 'text-warning',
                            ],
                            [
                                'label' => __('messages.purchase_price'),
                                'value' => format_currency($product->purchase_price),
                                'type' => 'text',
                            ],
                            [
                                'label' => __('messages.selling_price'),
                                'value' => format_currency($product->selling_price),
                                'type' => 'text-primary',
                            ],
                            [
                                'label' => __('messages.tax_label'),
                                'value' => number_format($product->tax_percentage ?? 0, 2) . '%',
                                'type' => 'text',
                            ],
                            [
                                'label' => __('messages.unit_label'),
                                'value' => ($product->unit_name ?? '�') . ' (' . ($product->unit_code ?? '�') . ')',
                                'type' => 'text',
                            ],
                            [
                                'label' => __('messages.brand'),
                                'value' => $product->brand?->name,
                                'type' => 'badge-primary',
                            ],
                            [
                                'label' => __('messages.category'),
                                'value' => $product->mainCategory?->name,
                                'type' => 'badge-secondary',
                            ],
                            [
                                'label' => __('messages.sub_category'),
                                'value' => $product->subCategory?->name,
                                'type' => 'badge-info',
                            ],
                            [
                                'label' => __('messages.created_at'),
                                'value' => $product->created_at->format('d M Y'),
                                'type' => 'text',
                            ],
                            [
                                'label' => __('messages.updated_at'),
                                'value' => $product->updated_at->format('d M Y'),
                                'type' => 'text',
                            ],
                        ];
                    @endphp
                    @foreach ($infoRows as $row)
                        @if ($row['value'] !== null && $row['value'] !== '' && $row['value'] !== '�')
                            <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom">
                                <span class="text-muted small fw-semibold">{{ $row['label'] }}</span>
                                <span class="fw-semibold small text-end">
                                    @if ($row['type'] === 'code-primary')
                                        <code class="text-primary">{{ $row['value'] }}</code>
                                    @elseif ($row['type'] === 'code')
                                        <code>{{ $row['value'] }}</code>
                                    @elseif ($row['type'] === 'status')
                                        <span
                                            class="badge rounded-pill {{ $row['value'] === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($row['value']) }}</span>
                                    @elseif ($row['type'] === 'stock-badge')
                                        <span
                                            class="badge rounded-pill {{ $row['value'][0] }}">{{ $row['value'][1] }}</span>
                                    @elseif ($row['type'] === 'badge-primary')
                                        <span class="badge bg-label-primary">{{ $row['value'] }}</span>
                                    @elseif ($row['type'] === 'badge-secondary')
                                        <span class="badge bg-label-secondary">{{ $row['value'] }}</span>
                                    @elseif ($row['type'] === 'badge-info')
                                        <span class="badge bg-label-info">{{ $row['value'] }}</span>
                                    @else
                                        <span class="{{ $row['type'] }}">{{ $row['value'] }}</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('products.update')
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i>{{ __('messages.edit') }}
                        </a>
                    @endcan
                    @can('stocks.create')
                        <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                            class="btn btn-outline-warning">
                            <i class="bx bx-slider me-1"></i>{{ __('messages.prod_adjust_stock_btn') }}
                        </a>
                    @endcan
                    @can('products.create')
                        <a href="{{ route('products.copy', $product->id) }}" class="btn btn-outline-secondary">
                            <i class="bx bx-copy me-1"></i>{{ __('messages.prod_copy_label') }}
                        </a>
                    @endcan
                    @can('products.delete')
                        <form id="deleteForm" action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $product->name }}">
                                <i class="bx bx-trash me-1"></i>{{ __('messages.delete') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>{{-- /col-lg-4 --}}
    </div>{{-- /row --}}

    {{-- ---------------------------------------------------------------------
         Quick Stock Adjustment Modal
    --------------------------------------------------------------------- --}}
    @can('stocks.create')
        <div class="modal fade" id="quickAdjustModal" tabindex="-1" aria-labelledby="quickAdjustModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                <div class="modal-content">
                    <form action="{{ route('stocks.store_adjustment') }}" method="POST" id="quickAdjustForm">
                        @csrf
                        <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                        <input type="hidden" name="_redirect_back" value="{{ url()->current() }}">
                        <input type="hidden" name="transaction_date" id="qa_transaction_date"
                            value="{{ date('Y-m-d') }}">

                        <div class="modal-header border-bottom py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span
                                    class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10"
                                    style="width:36px;height:36px;">
                                    <i class="bx bx-slider text-warning fs-5"></i>
                                </span>
                                <div>
                                    <h6 class="modal-title fw-bold mb-0" id="quickAdjustModalLabel">
                                        {{ __('messages.quick_adj_modal_title') }}
                                    </h6>
                                    <div class="text-muted small">{{ $product->name }} &bull; {{ __('messages.sku') }}:
                                        <code>{{ $product->code }}</code>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">

                            {{-- Current Stock Info --}}
                            <div class="rounded-3 p-3 mb-4 d-flex align-items-center gap-3"
                                style="background:rgba(105,108,255,.07);border:1px solid rgba(105,108,255,.15);">
                                <i class="bx bx-package text-primary fs-3"></i>
                                <div>
                                    <div class="small text-muted fw-semibold">{{ __('messages.current_stock_lbl') }}</div>
                                    <div class="fw-bold fs-5 text-primary" id="qa_current_stock">
                                        {{ number_format($qty, 0) }} {{ $product->unit_code ?? 'PCS' }}
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge {{ $sBadge }} px-2 py-1">{{ $sLabel }}</span>
                                </div>
                            </div>

                            {{-- Type (Plus / Minus) --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.adj_type_label') }} <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="items[0][type]" id="qa_type_plus"
                                        value="Plus" checked>
                                    <label class="btn btn-outline-success w-50 fw-semibold" for="qa_type_plus">
                                        <i class="bx bx-plus-circle me-1"></i> {{ __('messages.plus_label') }}
                                    </label>
                                    <input type="radio" class="btn-check" name="items[0][type]" id="qa_type_minus"
                                        value="Minus">
                                    <label class="btn btn-outline-danger w-50 fw-semibold" for="qa_type_minus">
                                        <i class="bx bx-minus-circle me-1"></i> {{ __('messages.minus_label') }}
                                    </label>
                                </div>
                            </div>

                            {{-- Quantity --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.quantity_label') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" id="qa_minus_btn">
                                        <i class="bx bx-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center fw-bold" name="items[0][quantity]"
                                        id="qa_quantity" value="1" min="1" required>
                                    <button class="btn btn-outline-secondary" type="button" id="qa_plus_btn">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                    <span class="input-group-text">{{ $product->unit_code ?? 'PCS' }}</span>
                                </div>
                            </div>

                            {{-- After adjustment preview --}}
                            <div class="rounded-3 p-3 mb-3 d-flex align-items-center gap-3"
                                style="background:rgba(40,199,111,.07);border:1px solid rgba(40,199,111,.2);">
                                <i class="bx bx-trending-up text-success fs-4"></i>
                                <div>
                                    <div class="small text-muted fw-semibold">{{ __('messages.after_adjustment_lbl') }}</div>
                                    <div class="fw-bold fs-5 text-success" id="qa_after_stock">
                                        {{ number_format($qty + 1, 0) }} {{ $product->unit_code ?? 'PCS' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('messages.date_label_short') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="transaction_date"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            {{-- Notes --}}
                            <div class="mb-0">
                                <label class="form-label fw-semibold">{{ __('messages.notes_label') }}</label>
                                <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('messages.ph_adj_reason') }}"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer border-top py-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-warning fw-semibold">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save_adjustment_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('scripts')
    {{-- Swiper --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- GLightbox --}}
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        $(document).ready(function() {

            // -- Tab persistence --
            const tabKey = 'prod_show_tab_{{ $product->id }}';
            const saved = localStorage.getItem(tabKey);
            if (saved) {
                const $t = $('[data-bs-target="' + saved + '"]');
                if ($t.length) new bootstrap.Tab($t[0]).show();
            }
            $('#txnTabs .nav-link').on('shown.bs.tab', function(e) {
                localStorage.setItem(tabKey, $(e.target).data('bs-target'));
            });

            // -- Swiper setup --
            @if ($allImgs && count($allImgs) > 0)
                @if (count($allImgs) > 1)
                    // Thumbs swiper
                    const swiperThumbs = new Swiper('#productSwiperThumbs', {
                        spaceBetween: 8,
                        slidesPerView: 'auto',
                        freeMode: true,
                        watchSlidesProgress: true,
                    });

                    // Main swiper with thumbs controller
                    const swiperMain = new Swiper('#productSwiperMain', {
                        spaceBetween: 0,
                        navigation: {
                            nextEl: '#productSwiperMain .swiper-button-next',
                            prevEl: '#productSwiperMain .swiper-button-prev',
                        },
                        pagination: {
                            el: '#productSwiperMain .swiper-pagination',
                            clickable: true,
                            dynamicBullets: true,
                        },
                        thumbs: {
                            swiper: swiperThumbs
                        },
                        keyboard: {
                            enabled: true
                        },
                        on: {
                            slideChange: function() {
                                const counter = document.getElementById('swiperCounter');
                                if (counter) counter.textContent = (this.realIndex + 1) +
                                    ' / {{ count($allImgs) }}';
                            }
                        }
                    });
                @else
                    // Single image � no navigation needed
                    const swiperMain = new Swiper('#productSwiperMain', {
                        spaceBetween: 0
                    });
                @endif

                // -- GLightbox --
                const lightbox = GLightbox({
                    selector: '.glightbox-prod',
                    touchNavigation: true,
                    loop: true,
                    autoplayVideos: false,
                    openEffect: 'zoom',
                    closeEffect: 'fade',
                });

                // Clicking main image opens lightbox at current index
                $('#productSwiperMain').on('click', 'img', function() {
                    @if (count($allImgs) > 1)
                        const idx = swiperMain.realIndex;
                    @else
                        const idx = 0;
                    @endif
                    const links = document.querySelectorAll('.glightbox-prod');
                    if (links[idx]) links[idx].click();
                });

                // Expand button
                document.getElementById('swiperExpandBtn')?.addEventListener('click', function() {
                    @if (count($allImgs) > 1)
                        const idx = swiperMain.realIndex;
                    @else
                        const idx = 0;
                    @endif
                    const links = document.querySelectorAll('.glightbox-prod');
                    if (links[idx]) links[idx].click();
                });
            @endif

            // -- Delete confirm --
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
                }).then(r => {
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
