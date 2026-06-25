@extends('layouts.admin')
@section('title', 'Product Gallery')

@push('styles')
    <style>
        /* ─── Card ─────────────────────────────────────────── */
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

        /* ─── Image area ─────────────────────────────────── */
        .pg-img-wrap {
            position: relative;
            height: 170px;
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
            font-size: 2.5rem;
        }

        .pg-no-img span {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* ─── Overlay badges ─────────────────────────────── */
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

        /* ─── Card body ──────────────────────────────────── */
        .pg-body {
            padding: .8rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ─── Price box ──────────────────────────────────── */
        .pg-price-box {
            background: linear-gradient(135deg, #f0f1ff, #f5f0ff);
            border-radius: 8px;
            padding: .5rem .65rem;
            margin-bottom: .6rem;
        }

        [data-bs-theme="dark"] .pg-price-box {
            background: linear-gradient(135deg, #25264a, #2e1a44);
        }

        /* ─── Thumbnail ──────────────────────────────────── */
        .pg-thumb {
            width: 28px;
            height: 28px;
            object-fit: cover;
            border-radius: 5px;
            border: 1.5px solid #dee2e6;
        }

        /* ─── Dark mode ──────────────────────────────────── */
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

        /* ─── Pagination bootstrap override ─────────────── */
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
    </style>
@endpush

@section('content')

    {{-- ── Header ─────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Product Gallery</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Gallery</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-list-ul me-1"></i> List View
            </a>
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> Add Product
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Stats ───────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
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
        <div class="col-6 col-xl-3">
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
        <div class="col-6 col-xl-3">
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
        <div class="col-6 col-xl-3">
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

    {{-- ── Filters ──────────────────────────────────────────── --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('products.gallery') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search name or SKU…" value="{{ request('search') }}">
                    </div>
                    <div class="col-6 col-sm-3 col-lg-2">
                        <select name="brand_id" class="form-select form-select-sm">
                            <option value="">All Brands</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-2">
                        <select name="main_category_id" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ request('main_category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-2">
                        <select name="stock_filter" class="form-select form-select-sm">
                            <option value="">All Stock</option>
                            <option value="ok" {{ request('stock_filter') === 'ok' ? 'selected' : '' }}>In Stock
                            </option>
                            <option value="low" {{ request('stock_filter') === 'low' ? 'selected' : '' }}>Low Stock
                            </option>
                            <option value="out" {{ request('stock_filter') === 'out' ? 'selected' : '' }}>Out of Stock
                            </option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-1 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill"><i
                                class="bx bx-search"></i></button>
                        <a href="{{ route('products.gallery') }}" class="btn btn-outline-secondary btn-sm"><i
                                class="bx bx-reset"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Toolbar ──────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <p class="text-muted small mb-0">
            Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
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

    {{-- ── Grid ─────────────────────────────────────────────── --}}
    @if ($products->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bx bx-package" style="font-size:4rem;opacity:.12;display:block;"></i>
            <p class="mt-3 fw-semibold mb-1">No products found.</p>
            <p class="small mb-0">Try adjusting your filters.</p>
        </div>
    @else
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 mb-2">
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

                        {{-- Image --}}
                        <div class="pg-img-wrap">
                            @if ($product->image)
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}"
                                    loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'pg-no-img\'><i class=\'bx bx-package\'></i><span>No Image</span></div>'">
                            @else
                                <div class="pg-no-img"><i class="bx bx-package"></i><span>No Image</span></div>
                            @endif

                            <div class="pg-tl">
                                <span class="badge {{ $sBadge }}"
                                    style="font-size:.6rem;">{{ $sLabel }}</span>
                            </div>
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

                        {{-- Body --}}
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

                            {{-- Name --}}
                            <h6 class="fw-bold mb-0 lh-sm" title="{{ $product->name }}"
                                style="font-size:.8rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $product->name }}
                            </h6>
                            <p class="text-muted mb-2" style="font-size:.65rem;">
                                <code style="font-size:.65rem;">{{ $product->code }}</code>
                            </p>

                            {{-- Price --}}
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

                            {{-- Stock --}}
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

                            {{-- Actions --}}
                            <div class="d-flex gap-1 mt-auto" onclick="event.stopPropagation()">
                                @can('products.view')
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="btn btn-sm btn-outline-info rounded-pill flex-fill py-1"
                                        style="font-size:.68rem;">
                                        <i class="bx bx-show me-1"></i>View
                                    </a>
                                @endcan
                                @can('products.update')
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill flex-fill py-1"
                                        style="font-size:.68rem;">
                                        <i class="bx bx-edit me-1"></i>Edit
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Pagination ───────────────────────────────────────── --}}
        @if ($products->hasPages())
            <div class="d-flex flex-column align-items-center gap-2 py-3 pg-pagination">
                <p class="text-muted small mb-0">
                    Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
                    of {{ $products->total() }} products
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
    </script>
@endpush
