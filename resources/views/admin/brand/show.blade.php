@extends('layouts.admin')
@section('title', 'Brand — ' . $brand->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.brands') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.brand.index') }}">{{ __('admin.brands') }}</a></li>
                    <li class="breadcrumb-item active">{{ $brand->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.brand.edit', $brand->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.brand.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        @if ($brand->image)
                            <img src="{{ asset('uploads/brand/' . $brand->image) }}" class="rounded-3 shadow-sm"
                                style="width:100px;height:100px;object-fit:contain;background:#f8f9fa;"
                                onerror="imgError(this)">
                        @else
                            <div class="rounded-3 d-flex align-items-center justify-content-center bg-label-primary"
                                style="width:100px;height:100px;"><i class="bx bx-award text-primary"
                                    style="font-size:3rem;"></i></div>
                        @endif
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold mb-0">{{ $brand->name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $brand->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $brand->status }}</span>
                            </div>
                            <p class="text-muted small mb-0"><i class="bx bx-package me-1"></i>{{ $brand->products_count }}
                                {{ __('admin.products') }}</p>
                            <p class="text-muted small mb-0"><i
                                    class="bx bx-calendar me-1"></i>{{ $brand->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-package me-2 text-info"></i>{{ __('admin.products') }} <span
                            class="badge bg-label-info ms-2">{{ $brand->products_count }}</span></h6>
                    <a href="{{ route('admin.product.index') }}"
                        class="btn btn-sm btn-outline-info">{{ __('admin.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    @if ($products->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.product') }}</th>
                                        <th>{{ __('admin.price') }}</th>
                                        <th class="text-center">{{ __('admin.qty') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $p)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if (!empty($p->images))
                                                        <img src="{{ asset('uploads/products/' . $p->images[0]) }}"
                                                        class="tbl-img" onerror="imgError(this)">@else<div
                                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                                            <i class="bx bx-package text-muted"></i></div>
                                                    @endif
                                                    <strong>{{ Str::limit($p->name, 30) }}</strong>
                                                </div>
                                            </td>
                                            <td><strong class="text-success">₹{{ number_format($p->price, 0) }}</strong>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge {{ $p->quantity <= 5 ? 'bg-danger' : 'bg-label-primary' }}">{{ $p->quantity }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge rounded-pill {{ $p->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $p->status }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.product.show', $p->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-1 btn-action"><i
                                                        class="bx bx-show"></i></a>
                                                <a href="{{ route('admin.product.edit', $p->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"><i
                                                        class="bx bx-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else<div class="text-center py-5 text-muted"><i class="bx bx-package"
                                style="font-size:3rem;opacity:.3;"></i>
                            <p class="mt-2">{{ __('admin.no_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">ID</span><span
                                class="fw-bold">#{{ $brand->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.name') }}</span><span
                                class="fw-bold">{{ $brand->name }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $brand->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $brand->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.total_products') }}</span><span
                                class="badge bg-label-info">{{ $brand->products_count }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $brand->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $brand->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.brand.edit', $brand->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <form action="{{ route('admin.brand.destroy', $brand->id) }}" method="POST" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete"><i
                                class="bx bx-trash me-1"></i>{{ __('admin.delete') }}</button>
                    </form>
                </div>
            </div>
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
                cancelButtonText: '{{ __('admin.swal_cancel') }}'
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        });
    </script>
@endpush
