@extends('layouts.admin')
@section('title', __('messages.products_catalog'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.products_catalog') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_products') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-filter-alt me-1"></i> {{ __('messages.filters') }} <i id="filtersChevron"
                    class="bx bx-chevron-down ms-1"></i>
            </button>
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_product') }}
                </a>
            @endcan
        </div>
    </div>

    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2"></i>{{ __('messages.filter_products') }}</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                value="{{ request('search') }}"
                                placeholder="{{ __('messages.sku') }}, {{ __('messages.product_name') }}...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.brand') }}</label>
                            <select name="brand_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_brands') }}</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.category') }}</label>
                            <select name="main_category_id" id="filter_main_category_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_categories') }}</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('main_category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.sub_category') }}</label>
                            <select name="sub_category_id" id="filter_sub_category_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_categories') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.price_min') }}</label>
                            <input type="number" step="0.01" name="price_min" class="form-control form-control-sm"
                                value="{{ request('price_min') }}" placeholder="{{ __('messages.price_min') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.price_max') }}</label>
                            <input type="number" step="0.01" name="price_max" class="form-control form-control-sm"
                                value="{{ request('price_max') }}" placeholder="{{ __('messages.price_max') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('products.index') }}"
                            class="btn btn-outline-secondary btn-sm">{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-search me-1"></i>
                            {{ __('messages.apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="productsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th class="no-sort">{{ __('messages.th_image') }}</th>
                            <th>{{ __('messages.th_name') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th>{{ __('messages.th_brand') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.sub_category') }}</th>
                            <th class="text-end">{{ __('messages.th_buy_price') }}</th>
                            <th class="text-end">{{ __('messages.th_sell_price') }}</th>
                            <th class="text-end">{{ __('messages.th_stock') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset('uploads/products/' . $product->image) }}" class="tbl-img"
                                            onerror="imgError(this)">
                                    @else
                                        <div
                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback tbl-img">
                                            <i class="bx bx-package text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><code>{{ $product->code }}</code></td>
                                <td class="text-muted">{{ $product->brand->name ?? '-' }}</td>
                                <td class="text-muted">{{ $product->mainCategory->name ?? '-' }}</td>
                                <td class="text-muted">{{ $product->subCategory->name ?? '-' }}</td>
                                <td class="text-end fw-semibold">{{ format_currency($product->purchase_price) }}</td>
                                <td class="text-end fw-bold text-primary">{{ format_currency($product->selling_price) }}
                                </td>
                                <td
                                    class="text-end fw-bold {{ ($product->stock->quantity ?? 0) <= $product->minimum_stock_alert ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($product->stock->quantity ?? 0, 2) }}
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('products.view')
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}"><i class="bx bx-show"></i></a>
                                        @endcan
                                        @can('products.update')
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('products.create')
                                            <a href="{{ route('products.copy', $product->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                title="Copy product"><i class="bx bx-copy"></i></a>
                                        @endcan
                                        @can('products.delete')
                                            <form id="delete-form-{{ $product->id }}"
                                                action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                    title="{{ __('messages.delete') }}"><i class="bx bx-trash"></i></button>
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

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }]
            });
            let filtersOpen = localStorage.getItem('products_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('products_filters_open', isOpen);
            });
            const subCategories = @json($subCategories);
            const selectedSubCategoryId = "{{ request('sub_category_id') }}";

            function loadFilterSubcategories(mainCategoryId, preselectedId = '') {
                const subSelect = $('#filter_sub_category_id');
                subSelect.html('<option value="">{{ __('messages.all_categories') }}</option>');
                if (!mainCategoryId) return;
                subCategories.filter(sub => sub.main_category_id == mainCategoryId).forEach(sub => {
                    const selected = sub.id == preselectedId ? 'selected' : '';
                    subSelect.append(`<option value="${sub.id}" ${selected}>${sub.name}</option>`);
                });
            }
            $('#filter_main_category_id').on('change', function() {
                loadFilterSubcategories($(this).val());
            });
            const initialMainCatId = $('#filter_main_category_id').val();
            if (initialMainCatId) loadFilterSubcategories(initialMainCatId, selectedSubCategoryId);
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name'),
                    form = $(`#delete-form-${id}`);
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
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(res) {
                                if (res.success) Swal.fire({
                                    title: '{{ __('messages.deleted_title') }}',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => window.location.reload());
                                else showAdminToast(res.message, 'error');
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
