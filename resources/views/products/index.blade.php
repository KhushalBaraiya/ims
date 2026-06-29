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
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-filter-alt me-1"></i>{{ __('messages.filters') }}
                <i id="filtersChevron" class="bx bx-chevron-down ms-1"></i>
            </button>
            <a href="{{ route('products.gallery') }}" class="btn btn-outline-info btn-sm">
                <i class="bx bx-grid-alt me-1"></i> Gallery
            </a>
            <a href="{{ route('products.by-category') }}" class="btn btn-outline-success btn-sm">
                <i class="bx bx-category me-1"></i> By Category
            </a>
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_product') }}
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
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Products</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $total }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;"><i
                            class="bx bx-package"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Active</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $active }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;"><i
                            class="bx bx-check-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Low Stock</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $lowStock }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;"><i
                            class="bx bx-error-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Out of Stock</p>
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
                                <option value="">All Brands</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.category') }}</label>
                            <select name="main_category_id" id="filter_main_category_id" class="form-select form-select-sm">
                                <option value="">All Categories</option>
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
                                <option value="">All Sub-cats</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold small">Min ₹</label>
                            <input type="number" step="0.01" name="price_min" class="form-control form-control-sm"
                                value="{{ request('price_min') }}" placeholder="0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold small">Max ₹</label>
                            <input type="number" step="0.01" name="price_max" class="form-control form-control-sm"
                                value="{{ request('price_max') }}" placeholder="∞">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('products.index') }}"
                            class="btn btn-outline-secondary btn-sm">{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i
                                class="bx bx-search me-1"></i>{{ __('messages.apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th class="no-sort" style="width:80px">Image</th>
                            <th>{{ __('messages.th_name') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th>Brand / Category</th>
                            <th class="text-end">Cost</th>
                            <th class="text-end">Sell</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort" style="width:130px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $i => $product)
                            @php
                                $sq = (float) ($product->stock->quantity ?? 0);
                                $sal = (float) ($product->minimum_stock_alert ?? 0);
                                $sOut = $sq <= 0;
                                $sLow = !$sOut && $sq <= $sal;
                                $sCls = $sOut ? 'text-danger' : ($sLow ? 'text-warning' : 'text-success');
                                $sBg = $sOut
                                    ? 'rgba(234,84,85,.1)'
                                    : ($sLow
                                        ? 'rgba(255,171,0,.1)'
                                        : 'rgba(40,199,111,.1)');
                            @endphp
                            <tr>
                                <td class="text-muted small fw-semibold">{{ $i + 1 }}</td>
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
                                    <span
                                        class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('products.view')
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @endcan
                                        @can('products.update')
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan
                                        @can('products.create')
                                            <a href="{{ route('products.copy', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                title="Copy">
                                                <i class="bx bx-copy"></i>
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
                                                    title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($products->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <p class="text-muted small mb-0">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
            </p>
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif

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
                                        title: 'Deleted!',
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
        });
    </script>
@endpush
