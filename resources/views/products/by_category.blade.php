@extends('layouts.admin')
@section('title', 'Products by Category')

@push('styles')
    <style>
        /* ── Category Section ─────────────────────────── */
        .cat-section {
            margin-bottom: 2.5rem;
        }

        .cat-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1rem;
            border-radius: 12px 12px 0 0;
            background: linear-gradient(135deg, #696cff 0%, #9c3fe4 100%);
            color: #fff;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .cat-section-header .cat-title {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .02em;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .cat-section-header .cat-meta {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        /* Sub-category tabs */
        .subcat-tabs {
            display: flex;
            align-items: center;
            gap: .35rem;
            padding: .6rem 1rem;
            background: rgba(105, 108, 255, .06);
            border-left: 1px solid rgba(105, 108, 255, .15);
            border-right: 1px solid rgba(105, 108, 255, .15);
            flex-wrap: wrap;
            overflow-x: auto;
        }

        .subcat-tab-btn {
            padding: .28rem .75rem;
            border-radius: 20px;
            border: 1.5px solid rgba(105, 108, 255, .25);
            background: #fff;
            color: #697a8d;
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
            outline: none;
        }

        .subcat-tab-btn:hover,
        .subcat-tab-btn.active {
            background: linear-gradient(135deg, #696cff, #9c3fe4);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 3px 8px rgba(105, 108, 255, .35);
        }

        /* Products wrapper within a category */
        .cat-products-wrap {
            border: 1px solid rgba(105, 108, 255, .15);
            border-top: none;
            border-radius: 0 0 12px 12px;
            padding: 1rem;
            background: #fff;
        }

        /* ── Product Card ─────────────────────────────── */
        .pc-card {
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, .07);
            overflow: hidden;
            transition: transform .18s, box-shadow .18s;
            cursor: pointer;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(105, 108, 255, .16) !important;
            border-color: rgba(105, 108, 255, .25);
        }

        .pc-img-wrap {
            position: relative;
            height: 150px;
            overflow: hidden;
            background: #f4f5f8;
            flex-shrink: 0;
        }

        .pc-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
        }

        .pc-card:hover .pc-img-wrap img {
            transform: scale(1.06);
        }

        .pc-no-img {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #c8d0da;
            gap: .25rem;
        }

        .pc-no-img i {
            font-size: 2.2rem;
        }

        .pc-no-img span {
            font-size: .58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .pc-tl {
            position: absolute;
            top: 7px;
            left: 7px;
            z-index: 2;
        }

        .pc-tr {
            position: absolute;
            top: 7px;
            right: 7px;
            z-index: 2;
        }

        .pc-body {
            padding: .7rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .pc-price-box {
            background: linear-gradient(135deg, #f0f1ff, #f5f0ff);
            border-radius: 7px;
            padding: .45rem .6rem;
            margin-bottom: .5rem;
        }

        /* Empty state */
        .cat-empty {
            padding: 2rem;
            text-align: center;
            color: #adb5bd;
        }

        .cat-empty i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: .5rem;
        }

        /* Product row hidden via tab filter */
        .pc-item.hidden-subcat {
            display: none !important;
        }

        /* ── Dark mode ────────────────────────────────── */
        [data-bs-theme="dark"] .pc-card {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .07);
        }

        [data-bs-theme="dark"] .pc-img-wrap {
            background: #1e1e2e;
        }

        [data-bs-theme="dark"] .cat-products-wrap {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .1);
        }

        [data-bs-theme="dark"] .subcat-tabs {
            background: rgba(255, 255, 255, .04);
        }

        [data-bs-theme="dark"] .subcat-tab-btn {
            background: #2b2c40;
            color: #a3aed0;
        }

        [data-bs-theme="dark"] .pc-price-box {
            background: linear-gradient(135deg, #25264a, #2e1a44);
        }

        /* ── Jump to pills ────────────────────────────── */
        .jump-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            text-decoration: none;
            padding: .3rem .85rem;
            border-radius: 99px;
            border: 1.5px solid rgba(105, 108, 255, .3);
            background: rgba(105, 108, 255, .07);
            color: #696cff;
            font-size: .75rem;
            font-weight: 600;
            transition: all .15s;
            white-space: nowrap;
        }

        .jump-pill:hover {
            background: linear-gradient(135deg, #696cff, #9c3fe4);
            border-color: transparent;
            color: #fff !important;
        }

        .jump-pill:hover .jump-pill-badge {
            background: rgba(255, 255, 255, .25) !important;
            color: #fff !important;
        }

        .jump-pill-badge {
            background: rgba(105, 108, 255, .18);
            color: #696cff;
            font-size: .65rem;
            padding: .18em .5em;
            border-radius: 99px;
            font-weight: 700;
            transition: all .15s;
        }

        .jump-pill-warning {
            border-color: rgba(253, 159, 60, .3);
            background: rgba(253, 159, 60, .07);
            color: #fd9f3c;
        }

        .jump-pill-warning:hover {
            background: linear-gradient(135deg, #fd9f3c, #e57c1b);
        }

        .jump-pill-warning .jump-pill-badge {
            background: rgba(253, 159, 60, .18);
            color: #fd9f3c;
        }

        [data-bs-theme="dark"] .jump-pill {
            background: rgba(105, 108, 255, .12);
            border-color: rgba(105, 108, 255, .25);
            color: #9b9fff;
        }

        [data-bs-theme="dark"] .jump-pill-warning {
            background: rgba(253, 159, 60, .12);
            border-color: rgba(253, 159, 60, .25);
            color: #ffb74d;
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ───────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Products by Category</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">By Category</li>
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
            <a href="{{ route('products.gallery') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-grid-alt"></i> Gallery View
            </a>
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> Add Product
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Summary Stats ──────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Products</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $totalProducts }}</h4>
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
                        <p class="mb-0 text-muted small">Categories</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $totalCategories }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-category"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Showing</p>
                        <h4 class="mb-0 fw-bold text-info">
                            {{ $categories->sum(fn($c) => $c->products->count()) + $uncategorized->count() }}
                        </h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-filter-alt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.uncategorized') }}</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $uncategorized->count() }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;">
                        <i class="bx bx-question-mark"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Sticky Search / Filter Bar ─────────────────── --}}
    <div id="filtersCard" class="{{ $search || $statusFilter || $stockFilter ? '' : 'd-none' }} mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filter_products') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('products.by-category') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search product name or SKU…" value="{{ $search }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.th_status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">Stock</label>
                            <select name="stock_filter" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_stock') }}</option>
                                <option value="ok" {{ $stockFilter === 'ok' ? 'selected' : '' }}>In Stock</option>
                                <option value="low" {{ $stockFilter === 'low' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out" {{ $stockFilter === 'out' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('products.by-category') }}" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Category Quick-Jump ─────────────────────────── --}}
    @if ($categories->count() > 2)
        <div class="d-flex gap-2 flex-wrap mb-4 align-items-center">
            <span class="text-muted small fw-semibold d-flex align-items-center gap-1 me-1">
                <i class="bx bx-link-alt"></i> Jump to:
            </span>
            @foreach ($categories as $cat)
                @if ($cat->products->count() > 0)
                    <a href="#cat-{{ $cat->id }}" class="jump-pill">
                        {{ $cat->name }}
                        <span class="jump-pill-badge">{{ $cat->products->count() }}</span>
                    </a>
                @endif
            @endforeach
            @if ($uncategorized->count() > 0)
                <a href="#cat-uncategorized" class="jump-pill jump-pill-warning">
                    {{ __('messages.uncategorized') }}
                    <span class="jump-pill-badge">{{ $uncategorized->count() }}</span>
                </a>
            @endif
        </div>
    @endif

    {{-- ── Categories Loop ─────────────────────────────── --}}
    @php
        $totalShown = $categories->sum(fn($c) => $c->products->count()) + $uncategorized->count();
    @endphp

    @if ($totalShown === 0)
        <div class="text-center py-5 text-muted">
            <i class="bx bx-package" style="font-size:4rem;opacity:.15;display:block;"></i>
            <p class="mt-3 fw-semibold mb-1">No products found.</p>
            <p class="small mb-3">Try adjusting your filters or add products to a category.</p>
            <a href="{{ route('products.by-category') }}" class="btn btn-outline-secondary">
                <i class="bx bx-reset me-1"></i>Clear Filters
            </a>
        </div>
    @else
        @forelse($categories as $cat)
            @php $catProducts = $cat->products; @endphp
            @if ($catProducts->count() === 0)
                @continue
            @endif

            <div class="cat-section" id="cat-{{ $cat->id }}">

                {{-- Category Header --}}
                <div class="cat-section-header">
                    <div class="cat-title">
                        <i class="bx bx-category" style="font-size:1.1rem;opacity:.85;"></i>
                        {{ $cat->name }}
                    </div>
                    <div class="cat-meta">
                        <span class="badge text-white border border-white border-opacity-50"
                            style="font-size:.72rem;background:rgba(255,255,255,.2);">
                            {{ $catProducts->count() }} Product{{ $catProducts->count() !== 1 ? 's' : '' }}
                        </span>
                        @if ($cat->description)
                            <span class="text-white opacity-75" style="font-size:.72rem;">
                                {{ Str::limit($cat->description, 60) }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Sub-category filter tabs --}}
                @if ($cat->subCategories->count() > 0)
                    <div class="subcat-tabs" id="subcat-tabs-{{ $cat->id }}">
                        <button class="subcat-tab-btn active" data-cat="{{ $cat->id }}" data-subcat="all"
                            onclick="filterSubcat({{ $cat->id }}, 'all', this)">
                            All
                            <span style="opacity:.7;">({{ $catProducts->count() }})</span>
                        </button>
                        @foreach ($cat->subCategories as $sub)
                            @php $subCount = $catProducts->where('sub_category_id', $sub->id)->count(); @endphp
                            @if ($subCount > 0)
                                <button class="subcat-tab-btn" data-cat="{{ $cat->id }}"
                                    data-subcat="{{ $sub->id }}"
                                    onclick="filterSubcat({{ $cat->id }}, {{ $sub->id }}, this)">
                                    {{ $sub->name }}
                                    <span style="opacity:.7;">({{ $subCount }})</span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Products Grid --}}
                <div class="cat-products-wrap">
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3"
                        id="cat-grid-{{ $cat->id }}">
                        @foreach ($catProducts as $product)
                            @include('products._category_card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            {{-- No categories found --}}
        @endforelse
    @endif {{-- end totalShown check --}}

    {{-- ── Uncategorized ───────────────────────────────── --}}
    @if ($uncategorized->count() > 0)
        <div class="cat-section" id="cat-uncategorized">
            <div class="cat-section-header" style="background: linear-gradient(135deg, #fd9f3c 0%, #e57c1b 100%);">
                <div class="cat-title">
                    <i class="bx bx-question-mark" style="font-size:1.1rem;opacity:.85;"></i>
                    {{ __('messages.uncategorized') }}
                </div>
                <div class="cat-meta">
                    <span class="badge bg-white bg-opacity-25 text-white" style="font-size:.72rem;">
                        {{ $uncategorized->count() }} Product{{ $uncategorized->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
            <div class="cat-products-wrap">
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                    @foreach ($uncategorized as $product)
                        @include('products._category_card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        // ── Filters toggle ────────────────────────────────────────────────
        $(document).ready(function() {
            // Auto-open chevron if filter is active
            @if ($search || $statusFilter || $stockFilter)
                $('#filtersChevron').removeClass('bx-chevron-down').addClass('bx-chevron-up');
            @endif

            $('#toggleFiltersBtn').on('click', function() {
                const $card = $('#filtersCard');
                const $chevron = $('#filtersChevron');
                $card.toggleClass('d-none');
                const hidden = $card.hasClass('d-none');
                $chevron.toggleClass('bx-chevron-down', hidden).toggleClass('bx-chevron-up', !hidden);
            });
        });
        /**
         * Sub-category tab filter — show/hide product cards within a category section.
         */
        function filterSubcat(catId, subcatId, btn) {
            // Update active button
            document.querySelectorAll(`[data-cat="${catId}"]`).forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Show/hide product cards
            const grid = document.getElementById(`cat-grid-${catId}`);
            grid.querySelectorAll('.pc-item').forEach(item => {
                if (subcatId === 'all') {
                    item.classList.remove('hidden-subcat');
                } else {
                    const itemSubcat = item.dataset.subcat;
                    if (String(itemSubcat) === String(subcatId)) {
                        item.classList.remove('hidden-subcat');
                    } else {
                        item.classList.add('hidden-subcat');
                    }
                }
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#cat-"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endpush
