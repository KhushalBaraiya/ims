@extends('layouts.admin')
@section('title', __('admin.sub_in_category') . ' — ' . $subInCategory->name)
@section('content')

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.sub_in_category') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.subincategory.index') }}">{{ __('admin.sub_in_category') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $subInCategory->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.subincategory.edit', $subInCategory->id) }}" class="btn btn-primary btn-lg">
                <i class="bx bx-edit me-1"></i> {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.subincategory.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bx bx-arrow-back me-1"></i> {{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ══════════════════════════════
             LEFT COLUMN
        ══════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- ── Hero Info Card ── --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">

                    {{-- Top row: icon + name + status --}}
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                            style="width:80px;height:80px;">
                            <i class="bx bx-git-branch text-warning" style="font-size:2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                <h3 class="fw-bold mb-0">{{ $subInCategory->name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $subInCategory->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                    {{ $subInCategory->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </span>
                            </div>

                            {{-- Breadcrumb trail: Main → Sub → SubIn --}}
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                @if ($subInCategory->category)
                                    <a href="{{ route('admin.category.show', $subInCategory->category->id) }}"
                                        class="badge bg-label-primary px-2 py-1 text-decoration-none">
                                        <i class="bx bx-folder me-1"></i>{{ $subInCategory->category->name }}
                                    </a>
                                    <i class="bx bx-chevron-right text-muted"></i>
                                @endif
                                @if ($subInCategory->subcategory)
                                    <a href="{{ route('admin.subcategory.show', $subInCategory->subcategory->id) }}"
                                        class="badge bg-label-info px-2 py-1 text-decoration-none">
                                        <i class="bx bx-category me-1"></i>{{ $subInCategory->subcategory->name }}
                                    </a>
                                    <i class="bx bx-chevron-right text-muted"></i>
                                @endif
                                <span class="badge bg-label-warning px-2 py-1">
                                    <i class="bx bx-git-branch me-1"></i>{{ $subInCategory->name }}
                                </span>
                            </div>

                            <p class="text-muted mb-0 small mt-2">
                                <i class="bx bx-calendar me-1"></i>
                                {{ __('admin.created') }}: {{ $subInCategory->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- ── Stats Row ── --}}
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <div class="rounded-3 p-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary mb-0">{{ $totalProducts }}</div>
                                <div class="text-muted small">{{ __('admin.total_products') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="rounded-3 p-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-2 text-success mb-0">{{ $activeProducts }}</div>
                                <div class="text-muted small">{{ __('admin.active') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="rounded-3 p-3 text-center" style="background:#fff0f0;">
                                <div class="fw-bold fs-2 text-danger mb-0">{{ $lowStockCount }}</div>
                                <div class="text-muted small">{{ __('admin.low_stock_label') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="rounded-3 p-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-2 text-warning mb-0">
                                    ₹{{ number_format($totalValue, 0) }}
                                </div>
                                <div class="text-muted small">{{ __('admin.total') }} {{ __('admin.price') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Products Table ── --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-package me-2 text-info"></i>{{ __('admin.products') }}
                        <span class="badge bg-label-info ms-2">{{ $totalProducts }}</span>
                    </h6>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn-outline-info">
                        {{ __('admin.view_all') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($products->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="subInCatProductTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.product') }}</th>
                                        <th>{{ __('admin.brand') }}</th>
                                        <th>{{ __('admin.price') }}</th>
                                        <th class="text-center">{{ __('admin.qty') }}</th>
                                        <th class="text-center">{{ __('admin.rating') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if (!empty($product->images) && count($product->images))
                                                        <img src="{{ asset('uploads/products/' . $product->images[0]) }}"
                                                            class="tbl-img" onerror="imgError(this)">
                                                    @else
                                                        <div
                                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                                            <i class="bx bx-package text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong
                                                            class="d-block">{{ Str::limit($product->name, 28) }}</strong>
                                                        <small
                                                            class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-secondary px-2 py-1">
                                                    {{ $product->brand->name ?? __('admin.na') }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong
                                                    class="text-success d-block">₹{{ number_format($product->price, 0) }}</strong>
                                                @if ($product->original_price && $product->original_price > $product->price)
                                                    <small
                                                        class="text-muted text-decoration-line-through">₹{{ number_format($product->original_price, 0) }}</small>
                                                    <span class="badge bg-label-success ms-1"
                                                        style="font-size:.65rem;">{{ $product->discount_percent }}%
                                                        off</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $product->quantity <= 5 ? 'bg-danger' : ($product->quantity <= 20 ? 'bg-warning text-dark' : 'bg-label-primary') }} px-2 py-1">
                                                    {{ $product->quantity }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <i class="bx bxs-star text-warning" style="font-size:.85rem;"></i>
                                                    <span
                                                        class="small fw-semibold">{{ number_format($product->rating, 1) }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-1">
                                                    {{ $product->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.product.show', $product->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-1 btn-action"
                                                    title="{{ __('admin.view') }}">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                    title="{{ __('admin.edit') }}">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-package" style="font-size:3.5rem;opacity:.25;"></i>
                            <p class="mt-2 mb-0 fw-semibold">{{ __('admin.no_data') }}</p>
                            <p class="small">{{ __('admin.no_products_in_category') }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════
             RIGHT COLUMN
        ══════════════════════════════ --}}
        <div class="col-lg-4">

            {{-- ── Info Card ── --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.id') }}</span>
                            <span class="fw-bold">#{{ $subInCategory->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.name') }}</span>
                            <span class="fw-bold">{{ $subInCategory->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-start py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.main_category') }}</span>
                            @if ($subInCategory->category)
                                <a href="{{ route('admin.category.show', $subInCategory->category->id) }}"
                                    class="badge bg-label-primary px-2 py-1 text-decoration-none">
                                    <i class="bx bx-folder me-1"></i>{{ $subInCategory->category->name }}
                                </a>
                            @else
                                <span class="text-muted small">{{ __('admin.na') }}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between align-items-start py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.sub_category') }}</span>
                            @if ($subInCategory->subcategory)
                                <a href="{{ route('admin.subcategory.show', $subInCategory->subcategory->id) }}"
                                    class="badge bg-label-info px-2 py-1 text-decoration-none">
                                    <i class="bx bx-category me-1"></i>{{ $subInCategory->subcategory->name }}
                                </a>
                            @else
                                <span class="text-muted small">{{ __('admin.na') }}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.status') }}</span>
                            <span
                                class="badge rounded-pill {{ $subInCategory->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-1">
                                {{ $subInCategory->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.total_products') }}</span>
                            <span class="badge bg-label-info px-3 py-1 fw-bold">{{ $totalProducts }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.active') }}
                                {{ __('admin.products') }}</span>
                            <span class="badge bg-label-success px-3 py-1 fw-bold">{{ $activeProducts }}</span>
                        </li>
                        @if ($lowStockCount > 0)
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted small fw-semibold">{{ __('admin.low_stock_label') }}</span>
                                <span class="badge bg-danger px-3 py-1 fw-bold">{{ $lowStockCount }}</span>
                            </li>
                        @endif
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.created') }}</span>
                            <span class="small">{{ $subInCategory->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted small fw-semibold">{{ __('admin.updated') }}</span>
                            <span class="small">{{ $subInCategory->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- ── Category Hierarchy Card ── --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-sitemap me-2 text-info"></i>{{ __('admin.category') }}
                        {{ __('admin.hierarchy') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    {{-- Level 1: Main Category --}}
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                            style="width:38px;height:38px;">
                            <i class="bx bx-folder text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted" style="font-size:.7rem;letter-spacing:.06em;">
                                {{ strtoupper(__('admin.main_category')) }}</div>
                            @if ($subInCategory->category)
                                <a href="{{ route('admin.category.show', $subInCategory->category->id) }}"
                                    class="fw-semibold text-primary text-decoration-none">
                                    {{ $subInCategory->category->name }}
                                </a>
                            @else
                                <span class="text-muted small">{{ __('admin.na') }}</span>
                            @endif
                        </div>
                        @if ($subInCategory->category)
                            <span
                                class="badge rounded-pill {{ $subInCategory->category->status === 'active' ? 'bg-success' : 'bg-danger' }}"
                                style="font-size:.65rem;">
                                {{ $subInCategory->category->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        @endif
                    </div>

                    {{-- Connector --}}
                    <div class="ms-4 ps-2 border-start border-2 border-primary mb-3" style="height:16px;"></div>

                    {{-- Level 2: Sub Category --}}
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-info flex-shrink-0"
                            style="width:38px;height:38px;">
                            <i class="bx bx-category text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted" style="font-size:.7rem;letter-spacing:.06em;">
                                {{ strtoupper(__('admin.sub_category')) }}</div>
                            @if ($subInCategory->subcategory)
                                <a href="{{ route('admin.subcategory.show', $subInCategory->subcategory->id) }}"
                                    class="fw-semibold text-info text-decoration-none">
                                    {{ $subInCategory->subcategory->name }}
                                </a>
                            @else
                                <span class="text-muted small">{{ __('admin.na') }}</span>
                            @endif
                        </div>
                        @if ($subInCategory->subcategory)
                            <span
                                class="badge rounded-pill {{ $subInCategory->subcategory->status === 'active' ? 'bg-success' : 'bg-danger' }}"
                                style="font-size:.65rem;">
                                {{ $subInCategory->subcategory->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        @endif
                    </div>

                    {{-- Connector --}}
                    <div class="ms-4 ps-2 border-start border-2 border-info mb-3" style="height:16px;"></div>

                    {{-- Level 3: Sub In Category (current) --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                            style="width:38px;height:38px;">
                            <i class="bx bx-git-branch text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted" style="font-size:.7rem;letter-spacing:.06em;">
                                {{ strtoupper(__('admin.sub_in_category')) }}</div>
                            <span class="fw-bold text-warning">{{ $subInCategory->name }}</span>
                            <span class="badge bg-label-warning ms-1" style="font-size:.65rem;">
                                {{ __('admin.current') }}
                            </span>
                        </div>
                        <span
                            class="badge rounded-pill {{ $subInCategory->status === 'active' ? 'bg-success' : 'bg-danger' }}"
                            style="font-size:.65rem;">
                            {{ $subInCategory->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Quick Actions ── --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.subincategory.edit', $subInCategory->id) }}"
                        class="btn btn-primary btn-lg">
                        <i class="bx bx-edit me-1"></i> {{ __('admin.edit') }} {{ __('admin.sub_in_category') }}
                    </a>
                    @if ($subInCategory->subcategory)
                        <a href="{{ route('admin.subcategory.show', $subInCategory->subcategory->id) }}"
                            class="btn btn-outline-info btn-lg">
                            <i class="bx bx-category me-1"></i> {{ __('admin.view') }} {{ __('admin.sub_category') }}
                        </a>
                    @endif
                    @if ($subInCategory->category)
                        <a href="{{ route('admin.category.show', $subInCategory->category->id) }}"
                            class="btn btn-outline-primary btn-lg">
                            <i class="bx bx-folder me-1"></i> {{ __('admin.view') }} {{ __('admin.main_category') }}
                        </a>
                    @endif
                    <a href="{{ route('admin.product.create') }}" class="btn btn-outline-success btn-lg">
                        <i class="bx bx-plus me-1"></i> {{ __('admin.add_product') }}
                    </a>
                    <hr class="my-1">
                    <form action="{{ route('admin.subincategory.destroy', $subInCategory->id) }}" method="POST"
                        class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete">
                            <i class="bx bx-trash me-1"></i> {{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#subInCatProductTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                order: [
                    [0, 'asc']
                ]
            });
        });

        $(document).on('click', '.btn-delete', function() {
            const form = $(this).closest('.delete-form');
            Swal.fire({
                title: '{{ __('admin.swal_are_you_sure') }}',
                text: '{{ __('admin.swal_delete_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('admin.swal_yes_delete') }}',
                cancelButtonText: '{{ __('admin.swal_cancel') }}',
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    </script>
@endpush
