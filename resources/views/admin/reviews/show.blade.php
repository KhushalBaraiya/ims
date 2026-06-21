@extends('layouts.admin')
@section('title', 'Review')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.reviews') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">{{ __('admin.reviews') }}</a>
                    </li>
                    <li class="breadcrumb-item active">#{{ $review->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-4">
                        @if ($review->user && $review->user->image)
                            <img src="{{ asset($review->user->image) }}" class="rounded-circle flex-shrink-0"
                                style="width:60px;height:60px;object-fit:cover;">
                        @else<div
                                class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                                style="width:60px;height:60px;font-size:1.5rem;font-weight:700;color:#696cff;">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h5 class="fw-bold mb-0">{{ $review->user->name ?? '—' }}</h5>
                                <span
                                    class="badge rounded-pill {{ $review->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-1">{{ $review->status }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 mb-2">
                                @for ($s = 1; $s <= 5; $s++)
                                    <i class="bx {{ $s <= $review->rating ? 'bxs-star text-warning' : 'bx-star text-muted' }}"
                                        style="font-size:1.1rem;"></i>
                                @endfor
                                <span class="fw-bold ms-1">{{ $review->rating }}/5</span>
                            </div>
                            @if ($review->title)
                                <h6 class="fw-semibold mb-2">{{ $review->title }}</h6>
                            @endif
                            @if ($review->comment)
                                <p class="text-muted mb-3">{{ $review->comment }}</p>
                            @endif
                            @if (!empty($review->images))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($review->images as $img)
                                        <img src="{{ asset('uploads/reviews/' . $img) }}" class="rounded-3"
                                            style="width:80px;height:80px;object-fit:cover;" onerror="imgError(this)">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($review->product)
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-package me-2 text-info"></i>{{ __('admin.product') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            @if (!empty($review->product->images))
                                <img src="{{ asset('uploads/products/' . $review->product->images[0]) }}"
                                    class="tbl-img rounded-3" onerror="imgError(this)">
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1">{{ $review->product->name }}</h6>
                                <p class="text-muted small mb-0">₹{{ number_format($review->product->price, 0) }}</p>
                            </div>
                            <a href="{{ route('admin.product.show', $review->product->id) }}"
                                class="btn btn-sm btn-outline-info ms-auto"><i
                                    class="bx bx-show me-1"></i>{{ __('admin.view') }}</a>
                        </div>
                    </div>
                </div>
            @endif
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
                                class="fw-bold">#{{ $review->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.rating') }}</span><span class="fw-bold">⭐
                                {{ $review->rating }}/5</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.user') }}</span><span
                                class="small">{{ $review->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.product') }}</span><span
                                class="small">{{ Str::limit($review->product->name ?? '—', 20) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $review->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $review->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $review->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $review->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($review->user)
                        <a href="{{ route('admin.user.show', $review->user->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-user me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.user') }}</a>
                    @endif
                    @if ($review->product)
                        <a href="{{ route('admin.product.show', $review->product->id) }}"
                            class="btn btn-outline-info btn-lg"><i class="bx bx-package me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.product') }}</a>
                    @endif
                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="delete-form">
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
