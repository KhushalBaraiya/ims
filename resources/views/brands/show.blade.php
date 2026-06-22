@extends('layouts.admin')
@section('title', 'Brand — ' . $brand->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Brand Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Brands</a></li>
                    <li class="breadcrumb-item active">{{ $brand->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('brands.update')
                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit
                </a>
            @endcan
            <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left --}}
        <div class="col-lg-8">

            {{-- Brand Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center justify-content-center bg-label-primary rounded-3"
                            style="width:80px;height:80px;flex-shrink:0;">
                            <i class="bx bx-award text-primary" style="font-size:2.2rem;"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold mb-0">{{ $brand->name }}</h4>
                                <span
                                    class="badge rounded-pill {{ $brand->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3">
                                    {{ $brand->status }}
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bx bx-code me-1"></i> Code: <code>{{ $brand->slug }}</code>
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bx bx-package me-1"></i> {{ $brand->products->count() }} Products
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bx bx-calendar me-1"></i> {{ $brand->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    @if ($brand->description)
                        <hr class="my-3">
                        <p class="text-muted mb-0" style="white-space:pre-line;">{{ $brand->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Products Table --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-package me-2 text-info"></i>Products
                        <span class="badge bg-label-info ms-1">{{ $brand->products->count() }}</span>
                    </h6>
                    @can('products.view')
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-info">View All</a>
                    @endcan
                </div>
                <div class="card-body p-0">
                    @if ($brand->products->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-center">Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brand->products as $product)
                                        <tr>
                                            <td class="ps-4 fw-semibold">{{ $product->name }}</td>
                                            <td class="text-end text-primary fw-bold">
                                                {{ format_currency($product->selling_price) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->status }}
                                                </span>
                                            </td>
                                            <td class="text-muted small">{{ $product->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-package" style="font-size:3rem;opacity:.2;"></i>
                            <p class="mt-2 mb-0">No products found for this brand.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right --}}
        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bx bx-info-circle me-2 text-primary"></i>Information</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $brand->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">Name</span>
                            <span class="fw-bold">{{ $brand->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">Code</span>
                            <code>{{ $brand->slug }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">Status</span>
                            <span
                                class="badge rounded-pill {{ $brand->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $brand->status }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">Total Products</span>
                            <span class="badge bg-label-info">{{ $brand->products->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">Created</span>
                            <span class="small">{{ $brand->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">Updated</span>
                            <span class="small">{{ $brand->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bx bx-bolt-circle me-2 text-warning"></i>Quick Actions</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('brands.update')
                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> Edit Brand
                        </a>
                    @endcan
                    @can('brands.delete')
                        <form id="deleteForm" action="{{ route('brands.destroy', $brand->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $brand->name }}">
                                <i class="bx bx-trash me-1"></i> Delete Brand
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).on('click', '.delete-btn', function() {
            const name = $(this).data('name');
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete brand "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!'
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endpush
