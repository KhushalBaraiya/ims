@extends('layouts.admin')
@section('title', 'Offer — ' . $offer->offer_name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.offers') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.offer.index') }}">{{ __('admin.offers') }}</a></li>
                    <li class="breadcrumb-item active">{{ $offer->offer_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.offer.edit', $offer->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.offer.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bxs-offer text-warning"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0">{{ $offer->offer_name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $offer->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $offer->status }}</span>
                                <span
                                    class="badge {{ $offer->is_active ? 'bg-success' : 'bg-secondary' }} px-2 py-1">{{ $offer->is_active ? __('admin.active') : __('admin.inactive') }}</span>
                            </div>
                            <p class="text-muted mb-0 small"><i class="bx bx-tag me-1"></i>Code:
                                <code>{{ $offer->offer_code }}</code></p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-3 text-warning">
                                    {{ $offer->offer_type === 'percentage' ? $offer->discount_value . '%' : '₹' . $offer->discount_value }}
                                </div>
                                <div class="text-muted small">{{ __('admin.discount') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-4 text-success">₹{{ number_format($offer->offer_value, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.offer_value') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-6 text-primary">{{ $offer->start_date }}</div>
                                <div class="text-muted small">{{ __('admin.start_date') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#fff0f0;">
                                <div class="fw-bold fs-6 text-danger">{{ $offer->end_date }}</div>
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
                                    class="badge bg-label-warning px-3 py-2">{{ admin_label($offer->offer_type, 'dt') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.usage_count') }}</div>
                                <div class="fw-bold">{{ $offer->current_usage_count ?? 0 }}</div>
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
                                class="fw-bold">#{{ $offer->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.code') }}</span><code>{{ $offer->offer_code }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.is_active') }}</span><span
                                class="badge {{ $offer->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $offer->is_active ? 'Yes' : 'No' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $offer->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $offer->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $offer->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $offer->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.offer.edit', $offer->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <form action="{{ route('admin.offer.destroy', $offer->id) }}" method="POST" class="delete-form">@csrf
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
