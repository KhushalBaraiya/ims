@extends('layouts.admin')
@section('title', 'Shipping — ' . $shipping->title)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.shipping_methods') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.product-shipping.index') }}">{{ __('admin.shipping_methods') }}</a></li>
                    <li class="breadcrumb-item active">{{ $shipping->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.product-shipping.edit', $shipping->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.product-shipping.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-package me-2 text-primary"></i>{{ __('admin.shipping_methods') }}</h6>
                    <span
                        class="badge rounded-pill {{ $shipping->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $shipping->status }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-info flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bx-package text-info"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <h3 class="fw-bold mb-1">{{ $shipping->title }}</h3>
                            <p class="text-muted mb-0 small"><i class="bx bx-time me-1"></i>{{ $shipping->delivery_time }}
                            </p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-3 text-primary">₹{{ number_format($shipping->charge, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.charge') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-5 text-success">
                                    {{ $shipping->free_above ? '₹' . number_format($shipping->free_above, 0) : '—' }}</div>
                                <div class="text-muted small">{{ __('admin.free_above') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center"
                                style="background:{{ $shipping->cod_available ? '#f0fff4' : '#fff0f0' }};">
                                <div class="fw-bold fs-5 text-{{ $shipping->cod_available ? 'success' : 'danger' }}">
                                    {{ $shipping->cod_available ? 'Yes' : 'No' }}</div>
                                <div class="text-muted small">COD</div>
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
                                class="fw-bold">#{{ $shipping->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.title') }}</span><span
                                class="fw-bold">{{ $shipping->title }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.delivery_time') }}</span><span
                                class="small">{{ $shipping->delivery_time }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.charge') }}</span><span
                                class="fw-bold text-primary">₹{{ number_format($shipping->charge, 2) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.free_above') }}</span><span
                                class="small">{{ $shipping->free_above ? '₹' . number_format($shipping->free_above, 2) : '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">COD</span><span
                                class="badge {{ $shipping->cod_available ? 'bg-success' : 'bg-danger' }}">{{ $shipping->cod_available ? 'Yes' : 'No' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $shipping->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $shipping->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $shipping->created_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.product-shipping.edit', $shipping->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <form action="{{ route('admin.product-shipping.destroy', $shipping->id) }}" method="POST"
                        class="delete-form">@csrf @method('DELETE')
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
