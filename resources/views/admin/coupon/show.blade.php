@extends('layouts.admin')
@section('title', 'Coupon — ' . $coupon->code)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.coupons') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupon.index') }}">{{ __('admin.coupons') }}</a></li>
                    <li class="breadcrumb-item active">{{ $coupon->code }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-success flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bxs-coupon text-success"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0"><code>{{ $coupon->code }}</code></h3>
                                <span
                                    class="badge rounded-pill {{ $coupon->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $coupon->status }}</span>
                            </div>
                            <p class="text-muted mb-0">{{ $coupon->name }}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-3 text-success">
                                    {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '₹' . $coupon->discount_value }}
                                </div>
                                <div class="text-muted small">{{ __('admin.discount') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-5 text-primary">{{ $coupon->start_date }}</div>
                                <div class="text-muted small">{{ __('admin.start_date') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-5 text-warning">{{ $coupon->end_date }}</div>
                                <div class="text-muted small">{{ __('admin.end_date') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-detail me-2 text-primary"></i>{{ __('admin.details') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.type') }}</div><span
                                    class="badge bg-label-secondary px-3 py-2">{{ admin_label($coupon->discount_type, 'dt') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.product') }}</div>
                                <div class="fw-bold">{{ $coupon->product->name ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.minimum_order') }}</div>
                                <div class="fw-bold">
                                    {{ $coupon->minimum_order_amount ? '₹' . $coupon->minimum_order_amount : '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.max_discount') }}</div>
                                <div class="fw-bold">
                                    {{ $coupon->maximum_discount_value ? '₹' . $coupon->maximum_discount_value : '—' }}</div>
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
                                class="fw-bold">#{{ $coupon->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.code') }}</span><code>{{ $coupon->code }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $coupon->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $coupon->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $coupon->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $coupon->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST" class="delete-form">@csrf
                        @method('DELETE')
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
