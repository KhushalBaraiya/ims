@extends('layouts.admin')
@section('title', 'Order Item')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.order_items') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.order-item.index') }}">{{ __('admin.order_items') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $item->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.order-item.edit', $item->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.order-item.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-list-ul me-2 text-primary"></i>{{ __('admin.order_items') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        @if ($item->product_image)
                            <img src="{{ asset('uploads/products/' . $item->product_image) }}"
                                class="rounded-3 flex-shrink-0" style="width:90px;height:90px;object-fit:cover;"
                                onerror="imgError(this)">
                        @else<div
                                class="rounded-3 d-flex align-items-center justify-content-center bg-label-info flex-shrink-0"
                                style="width:90px;height:90px;"><i class="bx bx-package text-info"
                                    style="font-size:2.5rem;"></i></div>
                        @endif
                        <div>
                            <h4 class="fw-bold mb-1">{{ $item->product_name }}</h4>
                            @if ($item->order)
                                <p class="text-muted mb-0 small"><i class="bx bx-cart me-1"></i>{{ __('admin.order') }}:
                                    <strong class="text-primary">{{ $item->order->order_number }}</strong></p>
                            @endif
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary">{{ $item->quantity }}</div>
                                <div class="text-muted small">{{ __('admin.qty') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-3 text-success">₹{{ number_format($item->price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.price') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-3 text-warning">₹{{ number_format($item->total_price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.total') }}</div>
                            </div>
                        </div>
                    </div>
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
                                class="fw-bold">#{{ $item->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.order') }}</span>
                            @if ($item->order)
                                <a href="{{ route('admin.order.show', $item->order->id) }}"
                                class="badge bg-label-primary text-decoration-none">{{ $item->order->order_number }}</a>@else<span
                                    class="text-muted">—</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.product') }}</span><span
                                class="small">{{ $item->product->name ?? $item->product_name }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.qty') }}</span><span
                                class="badge bg-label-primary">{{ $item->quantity }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.price') }}</span><span
                                class="fw-bold text-success">₹{{ number_format($item->price, 2) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.total') }}</span><span
                                class="fw-bold text-warning">₹{{ number_format($item->total_price, 2) }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $item->created_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.order-item.edit', $item->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($item->order)
                        <a href="{{ route('admin.order.show', $item->order->id) }}" class="btn btn-outline-info btn-lg"><i
                                class="bx bx-cart me-1"></i>{{ __('admin.view') }} {{ __('admin.order') }}</a>
                    @endif
                    @if ($item->product)
                        <a href="{{ route('admin.product.show', $item->product->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-package me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.product') }}</a>
                    @endif
                    <form action="{{ route('admin.order-item.destroy', $item->id) }}" method="POST" class="delete-form">
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
