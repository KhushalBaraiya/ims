@extends('layouts.admin')
@section('title', 'Product — ' . $product->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Product Profile</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('products.update')
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit
                </a>
            @endcan
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left: Image + Description --}}
        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="rounded-3 overflow-hidden border bg-light d-flex align-items-center justify-content-center"
                        style="height:280px;">
                        <img id="primaryViewer"
                            src="{{ $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/400x400/e2e8f0/94a3b8?text=No+Image' }}"
                            alt="{{ $product->name }}" class="img-fluid" style="max-height:100%;object-fit:contain;">
                    </div>
                    @if ($product->gallery && count($product->gallery) > 0)
                        <div class="row g-2 mt-2">
                            <div class="col-3 thumbnail-item"
                                data-src="{{ $product->image ? asset('uploads/products/' . $product->image) : '' }}"
                                style="cursor:pointer;">
                                <img src="{{ $product->image ? asset('uploads/products/' . $product->image) : '' }}"
                                    class="img-fluid rounded border border-primary"
                                    style="height:60px;object-fit:cover;width:100%;">
                            </div>
                            @foreach ($product->gallery as $g)
                                <div class="col-3 thumbnail-item" data-src="{{ asset('uploads/products/' . $g) }}"
                                    style="cursor:pointer;">
                                    <img src="{{ asset('uploads/products/' . $g) }}" class="img-fluid rounded border"
                                        style="height:60px;object-fit:cover;width:100%;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">Description</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted fw-semibold mb-1">Short Summary</p>
                    <p class="small">{{ $product->short_description ?: 'No short description available.' }}</p>
                    <p class="small text-muted fw-semibold mb-1 mt-3">Full Overview</p>
                    <p class="small mb-0" style="white-space:pre-line;">
                        {{ $product->full_description ?: 'No detailed specs listed.' }}</p>
                </div>
            </div>

        </div>

        {{-- Right: Details --}}
        <div class="col-lg-8">

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between border-bottom pb-3 mb-3">
                        <div>
                            <span class="badge bg-label-primary small mb-1">{{ $product->brand->name ?? 'Generic' }}</span>
                            <h4 class="fw-bold mb-1">{{ $product->name }}</h4>
                            <p class="text-muted small mb-0">SKU: <code>{{ $product->code }}</code> | Barcode:
                                <code>{{ $product->barcode ?: '-' }}</code>
                            </p>
                        </div>
                        <span
                            class="badge rounded-pill {{ $product->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($product->status) }}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><small class="text-muted fw-semibold d-block">Main
                                Category</small><span>{{ $product->mainCategory->name ?? '-' }}</span></div>
                        <div class="col-md-4"><small class="text-muted fw-semibold d-block">Sub
                                Category</small><span>{{ $product->subCategory->name ?? '-' }}</span></div>
                        <div class="col-md-4"><small
                                class="text-muted fw-semibold d-block">Unit</small><span>{{ $product->unit_name ?? '-' }}
                                ({{ $product->unit_code ?? '-' }})</span></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="mb-0 fw-semibold">Pricing</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-4 py-3"><span
                                        class="text-muted small">Purchase Price</span><span
                                        class="fw-semibold">{{ format_currency($product->purchase_price) }}</span></li>
                                <li class="list-group-item d-flex justify-content-between px-4 py-3"><span
                                        class="text-muted small fw-bold">Selling Price</span><span
                                        class="fw-bold text-primary">{{ format_currency($product->selling_price) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-4 py-3"><span
                                        class="text-muted small">Tax %</span><span
                                        class="fw-semibold">{{ number_format($product->tax_percentage ?? 0, 2) }}%</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-4 py-3"><span
                                        class="text-muted small">Discount %</span><span
                                        class="fw-semibold">{{ number_format($product->discount_percentage ?? 0, 2) }}%</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="mb-0 fw-semibold">Stock & Alerts</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-4 py-3">
                                    <span class="text-muted small">Current Stock</span>
                                    <span
                                        class="fw-bold {{ ($product->stock->quantity ?? 0) <= $product->minimum_stock_alert ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($product->stock->quantity ?? 0, 2) }}
                                        {{ $product->unit_code ?? 'Units' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-4 py-3"><span
                                        class="text-muted small">Min Alert Qty</span><span
                                        class="fw-semibold">{{ number_format($product->minimum_stock_alert, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">Hardware Specifications</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Manufacturer</span><span
                                class="small">{{ $product->manufacturer ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Model Number</span><code
                                class="small">{{ $product->model_number ?: '-' }}</code></li>
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Part Number / MPN</span><code
                                class="small">{{ $product->part_number ?: '-' }}</code></li>
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Warranty</span><span
                                class="small">{{ $product->warranty ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Color</span><span
                                class="small">{{ $product->color ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Weight</span><span
                                class="small">{{ $product->weight ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between px-4 py-2"><span
                                class="text-muted small">Country of Origin</span><span
                                class="small">{{ $product->country_of_origin ?: '-' }}</span></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('.thumbnail-item').on('click', function() {
            $('#primaryViewer').attr('src', $(this).data('src'));
            $('.thumbnail-item img').removeClass('border-primary').addClass('border');
            $(this).find('img').removeClass('border').addClass('border-primary');
        });
    </script>
@endpush
