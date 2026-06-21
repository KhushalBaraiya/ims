@extends('layouts.admin')
@section('title', 'Wishlist')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.wishlists') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.wishlist.index') }}">{{ __('admin.wishlists') }}</a>
                    </li>
                    <li class="breadcrumb-item active">#{{ $wishlist->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.wishlist.edit', $wishlist->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.wishlist.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-heart me-2 text-danger"></i>{{ __('admin.wishlists') }}</h6>
                    <span
                        class="badge rounded-pill {{ $wishlist->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $wishlist->status }}</span>
                </div>
                <div class="card-body p-4">
                    {{-- User --}}
                    <div class="mb-4 p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-2 fw-semibold">{{ __('admin.user') }}</div>
                        <div class="d-flex align-items-center gap-3">
                            @if ($wishlist->user && $wishlist->user->image)
                                <img src="{{ asset($wishlist->user->image) }}" class="rounded-circle flex-shrink-0"
                                    style="width:44px;height:44px;object-fit:cover;">
                            @else<div
                                    class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                                    style="width:44px;height:44px;font-size:1.1rem;font-weight:700;color:#696cff;">
                                    {{ strtoupper(substr($wishlist->user->name ?? 'U', 0, 1)) }}</div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0">{{ $wishlist->user->name ?? '—' }}</h6>
                                <p class="text-muted small mb-0">{{ $wishlist->user->email ?? '' }}</p>
                            </div>
                            @if ($wishlist->user)
                                <a href="{{ route('admin.user.show', $wishlist->user->id) }}"
                                    class="btn btn-sm btn-outline-primary"><i
                                        class="bx bx-show me-1"></i>{{ __('admin.view') }}</a>
                            @endif
                        </div>
                    </div>
                    {{-- Product --}}
                    @if ($wishlist->product)
                        <div class="p-3 rounded-3 bg-light">
                            <div class="text-muted small mb-2 fw-semibold">{{ __('admin.product') }}</div>
                            <div class="d-flex align-items-center gap-3">
                                @if (!empty($wishlist->product->images))
                                    <img src="{{ asset('uploads/products/' . $wishlist->product->images[0]) }}"
                                    class="tbl-img rounded-3" onerror="imgError(this)">@else<div
                                        class="tbl-img d-flex align-items-center justify-content-center bg-light rounded"><i
                                            class="bx bx-package text-muted"></i></div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0">{{ $wishlist->product->name }}</h6>
                                    <p class="text-success fw-bold mb-0">₹{{ number_format($wishlist->product->price, 0) }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.product.show', $wishlist->product->id) }}"
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
                                class="fw-bold">#{{ $wishlist->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.user') }}</span><span
                                class="small">{{ $wishlist->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.product') }}</span><span
                                class="small">{{ Str::limit($wishlist->product->name ?? '—', 20) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $wishlist->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $wishlist->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $wishlist->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $wishlist->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.wishlist.edit', $wishlist->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($wishlist->user)
                        <a href="{{ route('admin.user.show', $wishlist->user->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-user me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.user') }}</a>
                    @endif
                    @if ($wishlist->product)
                        <a href="{{ route('admin.product.show', $wishlist->product->id) }}"
                            class="btn btn-outline-info btn-lg"><i class="bx bx-package me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.product') }}</a>
                    @endif
                    <form action="{{ route('admin.wishlist.destroy', $wishlist->id) }}" method="POST" class="delete-form">
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


