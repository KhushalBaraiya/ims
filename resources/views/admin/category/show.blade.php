@extends('layouts.admin')
@section('title', __('admin.category_details'))
@section('content')

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.category_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.category.index') }}">{{ __('admin.categories') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-primary btn-lg">
                <i class="bx bx-edit me-1"></i> {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bx bx-arrow-back me-1"></i> {{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── LEFT COLUMN ── --}}
        <div class="col-lg-8">

            {{-- Category Info Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-category me-2 text-primary"></i>{{ __('admin.category_details') }}
                    </h6>
                    <span
                        class="badge rounded-pill px-3 py-2 {{ $category->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $category->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        {{-- Category Icon --}}
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                            style="width:80px;height:80px;">
                            <i class="bx bx-category text-primary" style="font-size:2.5rem;"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">{{ $category->name }}</h3>
                            <p class="text-muted mb-0 small">
                                <i class="bx bx-calendar me-1"></i>
                                {{ __('admin.created') }}: {{ $category->created_at->format('d M Y, h:i A') }}
                            </p>
                            @if ($category->updated_at != $category->created_at)
                                <p class="text-muted mb-0 small">
                                    <i class="bx bx-edit-alt me-1"></i>
                                    {{ __('admin.updated') }}: {{ $category->updated_at->format('d M Y, h:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="rounded-3 p-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-3 text-primary">{{ $category->products_count }}</div>
                                <div class="text-muted small">{{ __('admin.total_products') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="rounded-3 p-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-3 text-success">{{ $subCategories->count() }}</div>
                                <div class="text-muted small">{{ __('admin.sub_category') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="rounded-3 p-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-3 text-warning">{{ $subInCategories->count() }}</div>
                                <div class="text-muted small">{{ __('admin.sub_in_category') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sub Categories Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-sitemap me-2 text-success"></i>{{ __('admin.sub_category') }}
                        <span class="badge bg-label-success ms-2">{{ $subCategories->count() }}</span>
                    </h6>
                    <a href="{{ route('admin.subcategory.create') }}" class="btn btn-sm btn-outline-success">
                        <i class="bx bx-plus me-1"></i>{{ __('admin.add') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($subCategories->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>{{ __('admin.name') }}</th>
                                        <th class="text-center">{{ __('admin.products') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subCategories as $i => $sub)
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-success flex-shrink-0"
                                                        style="width:34px;height:34px;">
                                                        <i class="bx bx-folder text-success" style="font-size:1rem;"></i>
                                                    </div>
                                                    <strong>{{ $sub->name }}</strong>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-label-primary px-3 py-1">
                                                    {{ $sub->products_count }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $sub->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-1">
                                                    {{ $sub->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.subcategory.edit', $sub->id) }}"
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
                            <i class="bx bx-folder-open" style="font-size:3rem;opacity:.3;"></i>
                            <p class="mt-2 mb-0">{{ __('admin.no_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sub In Categories Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-git-branch me-2 text-warning"></i>{{ __('admin.sub_in_category') }}
                        <span class="badge bg-label-warning ms-2">{{ $subInCategories->count() }}</span>
                    </h6>
                    <a href="{{ route('admin.subincategory.create') }}" class="btn btn-sm btn-outline-warning">
                        <i class="bx bx-plus me-1"></i>{{ __('admin.add') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($subInCategories->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>{{ __('admin.name') }}</th>
                                        <th>{{ __('admin.sub_category') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subInCategories as $i => $sub)
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                                                        style="width:34px;height:34px;">
                                                        <i class="bx bx-git-branch text-warning"
                                                            style="font-size:1rem;"></i>
                                                    </div>
                                                    <strong>{{ $sub->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-label-success px-2 py-1">
                                                    {{ $sub->subcategory->name ?? __('admin.na') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $sub->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-1">
                                                    {{ $sub->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.subincategory.edit', $sub->id) }}"
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
                            <i class="bx bx-git-branch" style="font-size:3rem;opacity:.3;"></i>
                            <p class="mt-2 mb-0">{{ __('admin.no_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Products Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-package me-2 text-info"></i>{{ __('admin.recent_products') }}
                        <span class="badge bg-label-info ms-2">{{ $category->products_count }}</span>
                    </h6>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn-outline-info">
                        {{ __('admin.view_all') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($category->products->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.product') }}</th>
                                        <th>{{ __('admin.price') }}</th>
                                        <th>{{ __('admin.qty') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->products as $product)
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
                                                            class="d-block">{{ Str::limit($product->name, 30) }}</strong>
                                                        <small
                                                            class="text-muted">{{ Str::limit($product->description, 35) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong
                                                    class="text-success">₹{{ number_format($product->price, 0) }}</strong>
                                                @if ($product->original_price && $product->original_price > $product->price)
                                                    <br>
                                                    <small
                                                        class="text-muted text-decoration-line-through">₹{{ number_format($product->original_price, 0) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $product->quantity <= 5 ? 'bg-danger' : 'bg-label-primary' }} px-2 py-1">
                                                    {{ $product->quantity }}
                                                </span>
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
                            <i class="bx bx-package" style="font-size:3rem;opacity:.3;"></i>
                            <p class="mt-2 mb-0">{{ __('admin.no_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="col-lg-4">

            {{-- Quick Info Card --}}
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
                            <span class="fw-bold">#{{ $category->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.category_name') }}</span>
                            <span class="fw-bold">{{ $category->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.status') }}</span>
                            <span
                                class="badge rounded-pill {{ $category->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-1">
                                {{ $category->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.total_products') }}</span>
                            <span class="badge bg-label-primary px-3 py-1 fw-bold">{{ $category->products_count }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.sub_category') }}</span>
                            <span class="badge bg-label-success px-3 py-1 fw-bold">{{ $subCategories->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.sub_in_category') }}</span>
                            <span class="badge bg-label-warning px-3 py-1 fw-bold">{{ $subInCategories->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.created') }}</span>
                            <span class="small">{{ $category->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted small fw-semibold">{{ __('admin.updated') }}</span>
                            <span class="small">{{ $category->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-edit me-1"></i> {{ __('admin.edit_category') }}
                    </a>
                    <a href="{{ route('admin.subcategory.create') }}" class="btn btn-outline-success btn-lg">
                        <i class="bx bx-plus me-1"></i> {{ __('admin.add') }} {{ __('admin.sub_category') }}
                    </a>
                    <a href="{{ route('admin.subincategory.create') }}" class="btn btn-outline-warning btn-lg">
                        <i class="bx bx-plus me-1"></i> {{ __('admin.add') }} {{ __('admin.sub_in_category') }}
                    </a>
                    <a href="{{ route('admin.product.create') }}" class="btn btn-outline-info btn-lg">
                        <i class="bx bx-plus me-1"></i> {{ __('admin.add_product') }}
                    </a>
                    <hr class="my-1">
                    <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                        class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete">
                            <i class="bx bx-trash me-1"></i> {{ __('admin.delete') }} {{ __('admin.category') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sub Categories Summary --}}
            @if ($subCategories->count())
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-pie-chart me-2 text-info"></i>{{ __('admin.sub_category') }}
                            {{ __('admin.overview') }}
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        @foreach ($subCategories as $sub)
                            <div
                                class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-label-success d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:30px;height:30px;">
                                        <i class="bx bx-folder text-success" style="font-size:.85rem;"></i>
                                    </div>
                                    <span class="fw-semibold small">{{ $sub->name }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-label-primary small">{{ $sub->products_count }}
                                        {{ __('admin.products') }}</span>
                                    <span
                                        class="badge rounded-pill {{ $sub->status === 'active' ? 'bg-success' : 'bg-danger' }}"
                                        style="font-size:.7rem;">
                                        {{ $sub->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
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
