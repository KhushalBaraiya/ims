@extends('layouts.admin')
@section('title', 'Return Exchange Policy')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.return_exchange_policy') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.return-exchange-policy.index') }}">{{ __('admin.return_exchange_policy') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $policy->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.return-exchange-policy.edit', $policy->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.return-exchange-policy.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-transfer me-2 text-primary"></i>{{ __('admin.return_exchange_policy') }}</h6>
                    <span
                        class="badge rounded-pill {{ $policy->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $policy->status }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 p-3 rounded-3" style="background:#f0f4ff;border-left:4px solid #696cff;">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.title') }}</div>
                        <h5 class="fw-bold mb-0">{{ $policy->title }}</h5>
                    </div>
                    @if ($policy->product)
                        <div class="p-3 rounded-3 bg-light">
                            <div class="text-muted small mb-2 fw-semibold">{{ __('admin.product') }}</div>
                            <div class="d-flex align-items-center gap-3">
                                @if (!empty($policy->product->images))
                                    <img src="{{ asset('uploads/products/' . $policy->product->images[0]) }}"
                                        class="tbl-img rounded-3" onerror="imgError(this)">
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0">{{ $policy->product->name }}</h6>
                                    <p class="text-muted small mb-0">₹{{ number_format($policy->product->price, 0) }}</p>
                                </div>
                                <a href="{{ route('admin.product.show', $policy->product->id) }}"
                                    class="btn btn-sm btn-outline-info"><i
                                        class="bx bx-show me-1"></i>{{ __('admin.view') }}</a>
                            </div>
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
                                class="fw-bold">#{{ $policy->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.title') }}</span><span
                                class="small fw-bold">{{ Str::limit($policy->title, 25) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.product') }}</span><span
                                class="small">{{ Str::limit($policy->product->name ?? '—', 20) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $policy->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $policy->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $policy->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $policy->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.return-exchange-policy.edit', $policy->id) }}"
                        class="btn btn-primary btn-lg"><i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($policy->product)
                        <a href="{{ route('admin.product.show', $policy->product->id) }}"
                            class="btn btn-outline-info btn-lg"><i class="bx bx-package me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.product') }}</a>
                    @endif
                    <form action="{{ route('admin.return-exchange-policy.destroy', $policy->id) }}" method="POST"
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
