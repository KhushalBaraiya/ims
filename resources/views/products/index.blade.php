@extends('layouts.admin')
@section('title', __('messages.products_catalog'))

@push('styles')
    <style>
        .prod-img-cell {
            position: relative;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            line-height: 0;
        }

        .prod-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            border: 1.5px solid #dee2e6;
            border-bottom: none;
            display: block;
            transition: filter .2s, transform .2s;
        }

        .prod-img-cell:hover .prod-thumb {
            filter: brightness(1.04);
            transform: translateY(-2px);
        }

        .prod-thumb.img-fallback {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f0f2f5;
            color: #adb5bd;
            font-size: 1.3rem;
            border-radius: 10px 10px 0 0;
            border: 1.5px solid #dee2e6;
            border-bottom: none;
        }

        .prod-price-tag {
            display: block;
            width: 56px;
            text-align: center;
            background: linear-gradient(90deg, #696cff, #9c3fe4);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            line-height: 1;
            padding: 3px 3px 4px;
            border-radius: 0 0 7px 7px;
            letter-spacing: .3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 3px 8px rgba(105, 108, 255, .3);
        }

        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .stock-pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .55rem;
            border-radius: 99px;
            font-size: .73rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.products_catalog') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_products') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i>
                {{ __('messages.filters') }}
                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            <a href="{{ route('products.gallery') }}" class="btn btn-outline-info d-flex align-items-center gap-1">
                <i class="bx bx-grid-alt"></i> {{ __('messages.prod_gallery') }}
            </a>
            <a href="{{ route('products.by-category') }}" class="btn btn-outline-success d-flex align-items-center gap-1">
                <i class="bx bx-category"></i> {{ __('messages.prod_by_category') }}
            </a>
            @can('products.delete')
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> {{ __('messages.delete_multiples') }}
                </button>
            @endcan
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> {{ __('messages.add_product') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        @php
            // Use DB aggregates — $products is now paginated, not a full collection
            $total = \App\Models\Product::count();
            $active = \App\Models\Product::where('status', 'active')->count();
            $lowStock = \App\Models\Product::whereHas(
                'stock',
                fn($q) => $q->where('quantity', '>', 0)->whereRaw('stocks.quantity <= products.minimum_stock_alert'),
            )->count();
            $outStock = \App\Models\Product::where(
                fn($q) => $q->whereHas('stock', fn($sq) => $sq->where('quantity', '<=', 0))->orWhereDoesntHave('stock'),
            )->count();
        @endphp
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.prod_total_products') }}</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $total }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;"><i
                            class="bx bx-package"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.prod_active') }}</p>
                        <h4 class="mb-0 fw-bold text-success" id="statActiveCount">{{ $active }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;"><i
                            class="bx bx-check-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.prod_low_stock') }}</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $lowStock }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;"><i
                            class="bx bx-error-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.prod_out_of_stock') }}</p>
                        <h4 class="mb-0 fw-bold text-danger">{{ $outStock }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;"><i
                            class="bx bx-x-circle"></i></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i
                        class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filter_products') }}</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                value="{{ request('search') }}" placeholder="Name, SKU, Barcode…">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.brand') }}</label>
                            <select name="brand_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.prod_all_brands') }}</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.category') }}</label>
                            <select name="main_category_id" id="filter_main_category_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.prod_all_categories') }}</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('main_category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.sub_category') }}</label>
                            <select name="sub_category_id" id="filter_sub_category_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.prod_all_subcats') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">{{ __('messages.prod_all_statuses') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.stock_alert_menu') }}</label>
                            <select name="stock_filter" class="form-select form-select-sm">
                                <option value="">All Stock</option>
                                <option value="low" {{ request('stock_filter') === 'low' ? 'selected' : '' }}>Low Stock Only</option>
                                <option value="out" {{ request('stock_filter') === 'out' ? 'selected' : '' }}>Out of Stock Only</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-1">
                            <label class="form-label fw-semibold small">{{ __('messages.prod_price_min') }}
                                {{ optional(current_currency())->symbol ?? '₹' }}</label>
                            <input type="number" step="0.01" name="price_min" class="form-control form-control-sm"
                                value="{{ request('price_min') }}" placeholder="0">
                        </div>
                        <div class="col-6 col-md-1">
                            <label class="form-label fw-semibold small">{{ __('messages.prod_price_max') }}
                                {{ optional(current_currency())->symbol ?? '₹' }}</label>
                            <input type="number" step="0.01" name="price_max" class="form-control form-control-sm"
                                value="{{ request('price_max') }}" placeholder="∞">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary"><i
                                class="bx bx-search me-1"></i>{{ __('messages.apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            {{-- DataTable-style controls row --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 border-bottom">
                {{-- Show entries --}}
                <form method="GET" action="{{ route('products.index') }}" id="perPageForm"
                    class="d-flex align-items-center gap-2 mb-0">
                    @foreach (request()->except('per_page', 'page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <label class="text-muted small mb-0">{{ __('messages.show') }}</label>
                    <select name="per_page" class="form-select form-select-sm" style="width:75px;" data-no-select2
                        onchange="document.getElementById('perPageForm').submit()">
                        @foreach ([10, 20, 50, 100] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>
                                {{ $n }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted small">{{ __('messages.entries') }}</span>
                </form>

                {{-- Quick Search --}}
                <form method="GET" action="{{ route('products.index') }}" class="d-flex align-items-center gap-1">
                    @foreach (request()->except('search', 'page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div class="input-group input-group-sm" style="width:220px;">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bx bx-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}">
                        @if (request('search'))
                            <a href="{{ route('products.index', request()->except('search', 'page')) }}"
                                class="btn btn-outline-secondary" title="Clear search">
                                <i class="bx bx-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th class="no-sort" style="width:80px">{{ __('messages.prod_image_col') }}</th>
                            <th>{{ __('messages.th_name') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th>{{ __('messages.prod_brand_category') }}</th>
                            <th class="text-end">{{ __('messages.prod_cost') }}</th>
                            <th class="text-end">{{ __('messages.prod_sell') }}</th>
                            <th class="text-center">{{ __('messages.prod_stock_col') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort" style="width:130px">{{ __('messages.prod_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $i => $product)
                            @php
                                $sq = (float) ($product->stock->quantity ?? 0);
                                $sal = (float) ($product->minimum_stock_alert ?? 0);
                                $sOut = $sq <= 0;
                                $sLow = !$sOut && $sal > 0 && $sq <= $sal;
                                $sCls = $sOut ? 'text-danger' : ($sLow ? 'text-warning' : 'text-success');
                                $sBg = $sOut
                                    ? 'rgba(234,84,85,.1)'
                                    : ($sLow
                                        ? 'rgba(255,171,0,.1)'
                                        : 'rgba(40,199,111,.1)');
                            @endphp
                            <tr>
                                <td class="text-muted small fw-semibold">{{ $products->firstItem() + $i }}</td>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="{{ $product->id }}"></td>
                                <td>
                                    <div class="prod-img-cell">
                                        @if ($product->image)
                                            <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                class="prod-thumb"
                                                onerror="this.outerHTML='<div class=\'prod-thumb img-fallback\'><i class=\'bx bx-package\'></i></div>'">
                                        @else
                                            <div class="prod-thumb img-fallback"><i class="bx bx-package"></i></div>
                                        @endif
                                        <span class="prod-price-tag">{{ format_currency($product->selling_price) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="max-width:200px;">{{ $product->name }}</div>
                                    @if ($product->subCategory)
                                        <small class="text-muted">{{ $product->subCategory->name }}</small>
                                    @endif
                                </td>
                                <td><code class="small">{{ $product->code }}</code></td>
                                <td>
                                    <div class="small fw-semibold">{{ $product->brand->name ?? '—' }}</div>
                                    <small class="text-muted">{{ $product->mainCategory->name ?? '—' }}</small>
                                </td>
                                <td class="text-end fw-semibold small">{{ format_currency($product->purchase_price) }}
                                </td>
                                <td class="text-end fw-bold text-primary">{{ format_currency($product->selling_price) }}
                                </td>
                                <td class="text-center">
                                    @if ($sq > 0 || $product->stock)
                                        <span class="stock-pill" style="background:{{ $sBg }};">
                                            <i class="bx bx-cube {{ $sCls }}" style="font-size:.8rem;"></i>
                                            <span class="{{ $sCls }}">{{ number_format($sq, 0) }}</span>
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('products.update')
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1 {{ $product->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;cursor:pointer;" data-id="{{ $product->id }}"
                                            data-status="{{ $product->status }}" title="Click to toggle status">
                                            {{ ucfirst($product->status) }}
                                        </button>
                                    @else
                                        <span
                                            class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('products.view')
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('products.update')
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('products.create')
                                            <a href="{{ route('products.copy', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                title="{{ __('messages.copy') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-copy" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('products.delete')
                                            <form id="del-{{ $product->id }}"
                                                action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    title="{{ __('messages.delete') }}"
                                                    style="width:30px;height:30px;padding:0;">
                                                    <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="text-center py-5">
                                        <div class="mb-3"
                                            style="width:72px;height:72px;border-radius:50%;background:rgba(105,108,255,.08);display:inline-flex;align-items:center;justify-content:center;">
                                            <i class="bx bx-package text-primary" style="font-size:2rem;opacity:.5;"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-body">
                                            @if (request()->hasAny([
                                                    'search',
                                                    'brand_id',
                                                    'main_category_id',
                                                    'sub_category_id',
                                                    'status',
                                                    'price_min',
                                                    'price_max',
                                                    'stock_filter',
                                                ]))
                                                {{ __('messages.prod_no_match_filters') }}
                                            @else
                                                {{ __('messages.prod_no_products_yet') }}
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-3">
                                            @if (request()->hasAny([
                                                    'search',
                                                    'brand_id',
                                                    'main_category_id',
                                                    'sub_category_id',
                                                    'status',
                                                    'price_min',
                                                    'price_max',
                                                    'stock_filter',
                                                ]))
                                                {{ __('messages.prod_clear_filters_hint') }}
                                            @else
                                                {{ __('messages.prod_get_started_hint') }}
                                            @endif
                                        </p>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            @if (request()->hasAny([
                                                    'search',
                                                    'brand_id',
                                                    'main_category_id',
                                                    'sub_category_id',
                                                    'status',
                                                    'price_min',
                                                    'price_max',
                                                    'stock_filter',
                                                ]))
                                                <a href="{{ route('products.index') }}"
                                                    class="btn btn-outline-secondary">
                                                    <i class="bx bx-reset me-1"></i>
                                                    {{ __('messages.prod_clear_filters_btn') }}
                                                </a>
                                            @endif
                                            @can('products.create')
                                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus me-1"></i> {{ __('messages.prod_add_product_btn') }}
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination footer inside card --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 border-top">
                <p class="text-muted small mb-0">
                    @if ($products->total() > 0)
                        {{ __('messages.prod_showing_results') }}
                        <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong>
                        {{ __('messages.prod_of') }} <strong>{{ $products->total() }}</strong>
                        {{ __('messages.prod_results') }}
                    @else
                        {{ __('messages.prod_no_results') }}
                    @endif
                </p>
                {{ $products->appends(request()->query())->links() }}
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ── Filter toggle ─────────────────────────────────────────────────
            let open = localStorage.getItem('prod_filters_open') === 'true';
            if (open) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('prod_filters_open', isOpen);
            });

            // ── Sub-category filter ───────────────────────────────────────────
            const subs = @json($subCategories);
            const selSub = "{{ request('sub_category_id') }}";

            function loadSubs(catId, pre = '') {
                const $s = $('#filter_sub_category_id');
                if ($s.hasClass('select2-hidden-accessible')) $s.select2('destroy');
                $s.html('<option value="">All Sub-cats</option>');
                if (!catId) {
                    $s.select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });
                    return;
                }
                subs.filter(s => s.main_category_id == catId).forEach(s => {
                    $s.append(
                        `<option value="${s.id}" ${s.id == pre ? 'selected' : ''}>${s.name}</option>`);
                });
                $s.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: 'All Sub-cats'
                });
            }

            $('#filter_main_category_id').on('change', function() {
                loadSubs($(this).val());
            });

            // On page load: if sub_category_id is pre-selected, also infer main cat to populate dropdown
            const initCat = "{{ request('main_category_id') }}";
            if (initCat) loadSubs(initCat, selSub);
            else if (selSub) {
                // sub selected but no main selected — find the matching main and pre-load
                const matchedSub = subs.find(s => s.id == selSub);
                if (matchedSub) {
                    // Set main category dropdown value then load subs
                    $('#filter_main_category_id').val(matchedSub.main_category_id).trigger('change');
                    loadSubs(matchedSub.main_category_id, selSub);
                }
            }

            // ── AJAX Status Toggle ─────────────────────────────────────────
            $(document).on('click', '.status-toggle-btn', function() {
                const btn = $(this);
                const id = btn.data('id');
                const cur = btn.data('status');
                $.ajax({
                    url: `/products/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: () => btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span>'),
                    success: (res) => {
                        btn.prop('disabled', false);
                        if (res.success) {
                            btn.data('status', res.status);
                            btn.removeClass(
                                'border-success text-success border-danger text-danger');
                            btn.addClass(res.status === 'active' ?
                                'border-success text-success' : 'border-danger text-danger');
                            btn.text(res.status === 'active' ? 'Active' : 'Inactive');
                            showAdminToast(res.message, 'success');
                            // ── Update Active stat card live ──────────────
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                        } else {
                            btn.text(cur === 'active' ? 'Active' : 'Inactive');
                            showAdminToast(res.message ||
                                '{{ __('messages.error_occurred') }}', 'error');
                        }
                    },
                    error: () => {
                        btn.prop('disabled', false).text(cur === 'active' ? 'Active' :
                            'Inactive');
                        showAdminToast('{{ __('messages.error_occurred') }}', 'error');
                    }
                });
            });

            // ── AJAX Delete with SweetAlert2 ──────────────────────────────────
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const form = $(`#del-${id}`);

                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `Delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then(r => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: res => {
                                if (res.success) {
                                    Swal.fire({
                                        title: '{{ __('messages.deleted_title') }}',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#696cff'
                                    }).then(() => location.reload());
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: () => showAdminToast(
                                '{{ __('messages.error_occurred') }}', 'error')
                        });
                    }
                });
            });

            // ── Bulk Select ──────────────────────────────────────────────
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleBulkBtn();
            });
            $(document).on('change', '.row-checkbox', function() {
                $('#selectAll').prop('checked', $('.row-checkbox:not(:checked)').length === 0);
                toggleBulkBtn();
            });

            function toggleBulkBtn() {
                const count = $('.row-checkbox:checked').length;
                count > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            // ── Bulk Delete ──────────────────────────────────────────────
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: '{{ __('messages.confirm_delete') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route('products.bulk-destroy') }}',
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: ids
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                            title: '{{ __('messages.deleted_title') }}',
                                            text: res.message,
                                            icon: 'success',
                                            confirmButtonColor: '#696cff'
                                        })
                                        .then(() => window.location.reload());
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
