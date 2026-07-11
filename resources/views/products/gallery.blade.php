@extends('layouts.admin')
@section('title', 'Product Gallery')

@push('styles')
    <style>
        /* ════════════════════════════════════════════════════════════
                                           PRODUCT GALLERY — BASE STYLES
                                           ════════════════════════════════════════════════════════════ */

        /* ── Card ── */
        .pg-card {
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, .07);
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #fff;
        }

        .pg-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(105, 108, 255, .17) !important;
            border-color: rgba(105, 108, 255, .25);
        }

        /* ── Image area ── */
        .pg-img-wrap {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: #f4f5f8;
            flex-shrink: 0;
        }

        .pg-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .pg-card:hover .pg-img-wrap img {
            transform: scale(1.05);
        }

        .pg-no-img {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #c8d0da;
            gap: .3rem;
        }

        .pg-no-img i {
            font-size: 2.8rem;
        }

        .pg-no-img span {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* ── Overlay badges ── */
        .pg-tl {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
        }

        .pg-tr {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 3px;
            align-items: flex-end;
        }

        /* ── Card body ── */
        .pg-body {
            padding: .85rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ── Price box ── */
        .pg-price-box {
            background: linear-gradient(135deg, #f0f1ff, #f5f0ff);
            border-radius: 8px;
            padding: .5rem .7rem;
            margin-bottom: .6rem;
        }

        /* ── Action buttons ── */
        .pg-actions .btn {
            font-size: .75rem;
            padding: .3rem .6rem;
            border-radius: 6px;
        }

        /* ── Gallery thumbs ── */
        .pg-thumb {
            width: 26px;
            height: 26px;
            object-fit: cover;
            border-radius: 4px;
            border: 1.5px solid #dee2e6;
        }

        /* ── Toolbar selects ── */
        .pg-toolbar select.form-select {
            min-width: 0;
            flex: 1 1 auto;
        }

        /* ── Dark mode ── */
        [data-bs-theme="dark"] .pg-card {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .07);
        }

        [data-bs-theme="dark"] .pg-img-wrap {
            background: #1e1e2e;
        }

        [data-bs-theme="dark"] .pg-no-img {
            color: #3d4460;
        }

        [data-bs-theme="dark"] .pg-price-box {
            background: linear-gradient(135deg, #25264a, #2e1a44);
        }

        /* ── Pagination ── */
        .pg-pagination .pagination {
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pg-pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid #dee2e6;
            color: #697a8d;
            padding: .38rem .72rem;
            font-size: .82rem;
            font-weight: 500;
            line-height: 1.4;
            transition: all .15s;
            min-width: 36px;
            text-align: center;
        }

        .pg-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #696cff, #9c3fe4);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px rgba(105, 108, 255, .4);
        }

        .pg-pagination .page-item:not(.active) .page-link:hover {
            background: rgba(105, 108, 255, .08);
            border-color: rgba(105, 108, 255, .3);
            color: #696cff;
        }

        .pg-pagination .page-item.disabled .page-link {
            opacity: .45;
            background: transparent;
        }

        /* ════════════════════════════════════════════════════════════
                                           RESPONSIVE — LARGE TABLET  (768 – 991px)
                                           ════════════════════════════════════════════════════════════ */
        @media (min-width: 768px) and (max-width: 991.98px) {

            /* Image shorter on tablet to save vertical space */
            .pg-img-wrap {
                height: 155px;
            }

            /* Card body comfortable */
            .pg-body {
                padding: .75rem;
            }

            /* Filters: 2-col layout */
            #filtersCard .col-md-3,
            #filtersCard .col-md-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* Toolbar wrap on narrow tablet */
            .pg-toolbar {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .5rem;
            }

            .pg-toolbar .d-flex.gap-2 {
                width: 100%;
            }

            .pg-toolbar select.form-select {
                flex: 1 1 0;
            }

            /* Pagination */
            .pg-pagination nav ul.pagination {
                justify-content: flex-start !important;
            }
        }

        /* ════════════════════════════════════════════════════════════
                                           RESPONSIVE — SMALL  (576 – 767px)
                                           ════════════════════════════════════════════════════════════ */
        @media (min-width: 576px) and (max-width: 767.98px) {

            /* Image height */
            .pg-img-wrap {
                height: 140px;
            }

            /* Card body padding */
            .pg-body {
                padding: .65rem;
            }

            /* Selling price smaller */
            .pg-price-box .fw-bold.text-primary {
                font-size: .85rem !important;
            }

            /* Filters all stacked */
            #filtersCard .col-md-3,
            #filtersCard .col-md-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* Page header buttons wrap */
            .d-flex.gap-2.flex-wrap .btn {
                font-size: .78rem;
            }

            /* Toolbar */
            .pg-toolbar {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .4rem;
            }

            .pg-toolbar .d-flex.gap-2 {
                width: 100%;
            }

            .pg-toolbar select.form-select {
                flex: 1 1 0;
            }

            /* Pagination row stack */
            .d-flex.align-items-center.justify-content-between.flex-wrap.gap-2.px-1.py-3 {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }

        /* ════════════════════════════════════════════════════════════
                                           RESPONSIVE — MOBILE  (0 – 575px)
                                           ════════════════════════════════════════════════════════════ */
        @media (max-width: 575.98px) {

            /* ── Page header ── */
            .d-flex.align-items-center.justify-content-between.mb-4 {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: .6rem;
            }

            .d-flex.align-items-center.justify-content-between.mb-4 h4 {
                font-size: 1.05rem !important;
            }

            /* Header buttons: 2-col grid */
            .d-flex.align-items-center.justify-content-between.mb-4>div:last-child {
                width: 100%;
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: .4rem;
            }

            .d-flex.align-items-center.justify-content-between.mb-4>div:last-child .btn {
                width: 100%;
                font-size: .76rem !important;
                padding: .3rem .5rem !important;
                justify-content: center;
            }

            /* ── Stats cards — 2 per row ── */
            .row.g-3.mb-4 .col-6.col-xl-3 {
                width: 50% !important;
            }

            /* ── Filters ── */
            #filtersCard .col-md-3,
            #filtersCard .col-md-2,
            #filtersCard [class*="col-md-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            #filtersCard .card-body {
                padding: .85rem !important;
            }

            #filtersCard .d-flex.justify-content-end.gap-2.mt-3 {
                flex-direction: row;
                justify-content: stretch !important;
            }

            #filtersCard .d-flex.justify-content-end.gap-2.mt-3 .btn {
                flex: 1 1 auto;
                justify-content: center;
            }

            /* ── Toolbar ── */
            .pg-toolbar {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: .4rem;
            }

            .pg-toolbar p.text-muted {
                font-size: .75rem !important;
            }

            .pg-toolbar .d-flex.gap-2 {
                width: 100%;
                flex-wrap: nowrap;
            }

            .pg-toolbar select.form-select {
                flex: 1 1 0;
                min-width: 0;
                font-size: .78rem !important;
            }

            /* ── 1-column: horizontal card layout ── */
            .pg-card {
                flex-direction: row !important;
                height: auto !important;
                min-height: 110px;
                border-radius: 10px;
            }

            /* Image = left thumbnail */
            .pg-img-wrap {
                width: 110px !important;
                min-width: 110px !important;
                height: auto !important;
                min-height: 110px;
                border-radius: 10px 0 0 10px !important;
                flex-shrink: 0;
            }

            /* Disable zoom on horizontal */
            .pg-card:hover .pg-img-wrap img {
                transform: none;
            }

            .pg-no-img i {
                font-size: 1.6rem;
            }

            .pg-no-img span {
                font-size: .48rem;
            }

            /* Overlay badges */
            .pg-tl {
                top: 6px;
                left: 6px;
            }

            .pg-tr {
                top: 6px;
                right: 6px;
            }

            .pg-tl .badge,
            .pg-tr .badge {
                font-size: .5rem !important;
                padding: .1em .28em !important;
            }

            /* Body fills rest of width */
            .pg-body {
                padding: .55rem .65rem !important;
                flex: 1;
                min-width: 0;
                justify-content: space-between;
            }

            /* Chips */
            .pg-body .badge {
                font-size: .5rem !important;
                padding: .1em .28em !important;
            }

            /* Product name — 1 line */
            .pg-body h6 {
                font-size: .78rem !important;
                -webkit-line-clamp: 1 !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block !important;
            }

            /* SKU */
            .pg-body p.text-muted {
                font-size: .6rem !important;
                margin-bottom: .3rem !important;
            }

            /* Price box compact */
            .pg-price-box {
                padding: .3rem .45rem !important;
                margin-bottom: .3rem !important;
                border-radius: 6px !important;
            }

            .pg-price-box .fw-bold.text-primary {
                font-size: .82rem !important;
            }

            .pg-price-box .text-muted.fw-semibold {
                font-size: .62rem !important;
            }

            .pg-price-box div[style*="font-size:.58rem"] {
                font-size: .48rem !important;
            }

            /* Hide profit row — save vertical space */
            .pg-price-box>div:last-child {
                display: none !important;
            }

            /* Stock row */
            .pg-body .d-flex.justify-content-between.align-items-center.mb-2 {
                margin-bottom: .25rem !important;
            }

            .pg-body .d-flex.align-items-center.gap-1 span {
                font-size: .62rem !important;
            }

            /* Hide gallery thumbs on horizontal card */
            .pg-body .d-flex.gap-1.mb-2.flex-wrap {
                display: none !important;
            }

            /* Action buttons */
            .pg-actions {
                gap: .3rem !important;
                margin-top: auto !important;
            }

            .pg-actions .btn {
                font-size: .68rem !important;
                padding: .22rem .4rem !important;
                flex: 1 1 auto;
                border-radius: 5px !important;
            }

            /* Show labels on horizontal (space is available) */
            .pg-actions .pg-btn-label {
                display: inline !important;
            }

            /* ── Pagination row ── */
            .d-flex.align-items-center.justify-content-between.flex-wrap.gap-2.px-1.py-3 {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: .5rem !important;
            }

            .d-flex.align-items-center.justify-content-between.flex-wrap.gap-2.px-1.py-3 p {
                font-size: .72rem !important;
            }

            .pg-pagination .page-item .page-link {
                padding: .28rem .5rem !important;
                font-size: .72rem !important;
                min-width: 28px !important;
                border-radius: 6px !important;
            }
        }

        /* ════════════════════════════════════════════════════════════
                                           RESPONSIVE — EXTRA SMALL  (0 – 400px)
                                           ════════════════════════════════════════════════════════════ */
        @media (max-width: 400px) {

            /* Image thumb narrower on very small */
            .pg-img-wrap {
                width: 90px !important;
                min-width: 90px !important;
                min-height: 100px;
            }

            .pg-body {
                padding: .45rem .5rem !important;
            }

            .pg-body h6 {
                font-size: .72rem !important;
            }

            .pg-price-box .fw-bold.text-primary {
                font-size: .75rem !important;
            }

            .pg-actions .btn {
                font-size: .62rem !important;
                padding: .18rem .3rem !important;
            }

            /* Header buttons single column on very small */
            .d-flex.align-items-center.justify-content-between.mb-4>div:last-child {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Product Gallery</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">Gallery</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i> {{ __('messages.filters') }}
                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-list-ul"></i> List View
            </a>
            <a href="{{ route('products.by-category') }}" class="btn btn-outline-success d-flex align-items-center gap-1">
                <i class="bx bx-category"></i> By Category
            </a>
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> {{ __('messages.add_product') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Stats ────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $products->total() }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-package"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Active</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $activeCount }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Low Stock</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $lowStockCount }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Out of Stock</p>
                        <h4 class="mb-0 fw-bold text-danger">{{ $outOfStockCount }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Collapsible Filters ──────────────────────────────────── --}}
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filter_products') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('products.gallery') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Name, SKU…" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.brand') }}</label>
                            <select name="brand_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_brands') }}</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.category') }}</label>
                            <select name="main_category_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_categories') }}</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('main_category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Stock</label>
                            <select name="stock_filter" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_stock') }}</option>
                                <option value="ok" {{ request('stock_filter') === 'ok' ? 'selected' : '' }}>In Stock
                                </option>
                                <option value="low" {{ request('stock_filter') === 'low' ? 'selected' : '' }}>Low Stock
                                </option>
                                <option value="out" {{ request('stock_filter') === 'out' ? 'selected' : '' }}>Out of
                                    Stock</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.th_status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('products.gallery') }}" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Toolbar: count + sort + per-page ────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2 pg-toolbar">
        <p class="text-muted small mb-0">
            Showing
            <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
            of <strong>{{ $products->total() }}</strong> products
        </p>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <select class="form-select form-select-sm" style="width:auto;" data-no-select2
                onchange="applyParam('sort',this.value)">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price ↑</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price ↓</option>
                <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>Stock ↑</option>
                <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Stock ↓</option>
            </select>
            <select class="form-select form-select-sm" style="width:auto;" data-no-select2
                onchange="applyParam('per_page',this.value)">
                <option value="12" {{ request('per_page', 24) == 12 ? 'selected' : '' }}>12 / page</option>
                <option value="24" {{ request('per_page', 24) == 24 ? 'selected' : '' }}>24 / page</option>
                <option value="48" {{ request('per_page', 24) == 48 ? 'selected' : '' }}>48 / page</option>
                <option value="96" {{ request('per_page', 24) == 96 ? 'selected' : '' }}>96 / page</option>
            </select>
        </div>
    </div>

    {{-- ── Grid ──────────────────────────────────────────────────── --}}
    @if ($products->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bx bx-package d-block mb-3" style="font-size:4rem;opacity:.12;"></i>
            <p class="fw-semibold mb-1">No products found.</p>
            <p class="small mb-0">Try adjusting your filters.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-2 g-sm-3 mb-4">
            @foreach ($products as $product)
                @php
                    $qty = (float) ($product->stock->quantity ?? 0);
                    $alert = (float) ($product->minimum_stock_alert ?? 0);
                    $isOut = $qty <= 0;
                    $isLow = !$isOut && $qty <= $alert;
                    $profit = $product->selling_price - $product->purchase_price;
                    $pct = $product->purchase_price > 0 ? round(($profit / $product->purchase_price) * 100, 1) : 0;
                    $gallery = $product->gallery ?? [];
                    $inactive = $product->status !== 'active';
                    $sCls = $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success');
                    $sBadge = $isOut ? 'bg-danger' : ($isLow ? 'bg-warning text-dark' : 'bg-success');
                    $sLabel = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');
                @endphp
                <div class="col">
                    <div class="card pg-card shadow-sm {{ $inactive ? 'opacity-75' : '' }}"
                        onclick="window.location='{{ route('products.show', $product->id) }}'">

                        {{-- ── Image ─── --}}
                        <div class="pg-img-wrap">
                            @if ($product->image)
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}"
                                    loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'pg-no-img\'><i class=\'bx bx-package\'></i><span>{{ __('messages.no_image') }}</span></div>'">
                            @else
                                <div class="pg-no-img"><i class="bx bx-package"></i><span>{{ __('messages.no_image') }}</span></div>
                            @endif

                            {{-- stock badge TL --}}
                            <div class="pg-tl">
                                <span class="badge {{ $sBadge }}"
                                    style="font-size:.6rem;">{{ $sLabel }}</span>
                            </div>

                            {{-- inactive / gallery count TR --}}
                            <div class="pg-tr">
                                @if ($inactive)
                                    <span class="badge bg-secondary" style="font-size:.58rem;">Inactive</span>
                                @endif
                                @if (count($gallery))
                                    <span class="badge bg-dark bg-opacity-55" style="font-size:.58rem;">
                                        <i class="bx bx-images"></i> +{{ count($gallery) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- ── Body ─── --}}
                        <div class="pg-body">

                            {{-- Brand / Category chips --}}
                            <div class="d-flex gap-1 flex-wrap mb-1" style="min-height:18px;">
                                @if ($product->brand)
                                    <span class="badge bg-label-primary"
                                        style="font-size:.55rem;padding:.2em .45em;">{{ $product->brand->name }}</span>
                                @endif
                                @if ($product->mainCategory)
                                    <span class="badge bg-label-secondary"
                                        style="font-size:.55rem;padding:.2em .45em;">{{ $product->mainCategory->name }}</span>
                                @endif
                            </div>

                            {{-- Name + SKU --}}
                            <h6 class="fw-bold mb-0 lh-sm" title="{{ $product->name }}"
                                style="font-size:.8rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $product->name }}
                            </h6>
                            <p class="text-muted mb-2" style="font-size:.65rem;">
                                <code style="font-size:.65rem;">{{ $product->code }}</code>
                            </p>

                            {{-- Price box --}}
                            <div class="pg-price-box">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <div
                                            style="font-size:.58rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                                            Sell</div>
                                        <div class="fw-bold text-primary lh-1" style="font-size:.95rem;">
                                            {{ format_currency($product->selling_price) }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div
                                            style="font-size:.58rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                                            Cost</div>
                                        <div class="text-muted fw-semibold lh-1" style="font-size:.75rem;">
                                            {{ format_currency($product->purchase_price) }}</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1 pt-1"
                                    style="border-top:1px solid rgba(0,0,0,.07);">
                                    <span style="font-size:.58rem;color:#bbb;font-weight:600;">Profit</span>
                                    <span class="fw-bold {{ $pct >= 0 ? 'text-success' : 'text-danger' }}"
                                        style="font-size:.68rem;">
                                        {{ format_currency($profit) }}
                                        <span class="badge {{ $pct >= 0 ? 'bg-success' : 'bg-danger' }}"
                                            style="font-size:.52rem;padding:.15em .38em;">
                                            {{ $pct >= 0 ? '+' : '' }}{{ $pct }}%
                                        </span>
                                    </span>
                                </div>
                            </div>

                            {{-- Stock + GST --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bx bx-cube {{ $sCls }}" style="font-size:.85rem;"></i>
                                    <span class="fw-bold {{ $sCls }}" style="font-size:.73rem;">
                                        {{ number_format($qty, 0) }} {{ $product->unit_code ?? 'pcs' }}
                                    </span>
                                </div>
                                @if ($product->tax_percentage)
                                    <span class="badge bg-label-warning" style="font-size:.55rem;padding:.18em .42em;">
                                        GST {{ $product->tax_percentage }}%
                                    </span>
                                @endif
                            </div>

                            {{-- Gallery strip --}}
                            @if (count($gallery))
                                <div class="d-flex gap-1 mb-2 flex-wrap">
                                    @foreach (array_slice($gallery, 0, 3) as $gi)
                                        <img src="{{ asset('uploads/products/' . $gi) }}" class="pg-thumb"
                                            loading="lazy" onerror="this.style.display='none'">
                                    @endforeach
                                    @if (count($gallery) > 3)
                                        <div class="pg-thumb d-flex align-items-center justify-content-center bg-light text-muted fw-bold"
                                            style="font-size:.6rem;">+{{ count($gallery) - 3 }}</div>
                                    @endif
                                </div>
                            @endif

                            {{-- Action buttons --}}
                            <div class="pg-actions d-flex gap-1 mt-auto" onclick="event.stopPropagation()">
                                @can('products.view')
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="btn btn-outline-secondary flex-fill">
                                        <i class="bx bx-show"></i><span class="pg-btn-label ms-1">View</span>
                                    </a>
                                @endcan
                                @can('products.update')
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-outline-primary flex-fill">
                                        <i class="bx bx-edit"></i><span class="pg-btn-label ms-1">Edit</span>
                                    </a>
                                @endcan
                            </div>

                        </div>{{-- /pg-body --}}
                    </div>{{-- /pg-card --}}
                </div>{{-- /col --}}
            @endforeach
        </div>

        {{-- ── Pagination ───────────────────────────────────────── --}}
        @if ($products->hasPages())
            <div
                class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-1 py-3 mt-2 border-top pg-pagination">
                <p class="text-muted small mb-0">
                    Showing <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong>
                    of <strong>{{ $products->total() }}</strong> products
                    &nbsp;·&nbsp; Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                </p>
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    @endif

@endsection

@push('scripts')
    <script>
        function applyParam(key, val) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, val);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        $(document).ready(function() {
            const hasActiveFilter =
                {{ request()->hasAny(['search', 'brand_id', 'main_category_id', 'stock_filter', 'status']) ? 'true' : 'false' }};
            if (hasActiveFilter) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').removeClass('bx-chevron-down').addClass('bx-chevron-up');
            }

            $('#toggleFiltersBtn').on('click', function() {
                const $card = $('#filtersCard');
                const $chevron = $('#filtersChevron');
                $card.toggleClass('d-none');
                const hidden = $card.hasClass('d-none');
                $chevron.toggleClass('bx-chevron-down', hidden).toggleClass('bx-chevron-up', !hidden);
            });
        });
    </script>
@endpush


