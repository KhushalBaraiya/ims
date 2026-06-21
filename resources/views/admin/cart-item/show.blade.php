@extends('layouts.admin')
@section('title', 'Cart Item #' . $cartItem->id)
@section('content')

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.cart_items') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.cart-item.index') }}">{{ __('admin.cart_items') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $cartItem->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cart-item.edit', $cartItem->id) }}" class="btn btn-primary btn-lg">
                <i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.cart-item.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== LEFT col-lg-8 ===== --}}
        <div class="col-lg-8">

            {{-- Cart Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                            style="width:80px;height:80px;">
                            <i class="bx bx-cart text-warning" style="font-size:2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0 text-primary">#{{ $cartItem->id }}</h3>
                                @if ($cartItem->status === 'active')
                                    <span class="badge rounded-pill bg-success px-3 py-2">{{ __('admin.active') }}</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3 py-2">{{ __('admin.inactive') }}</span>
                                @endif
                            </div>
                            <p class="text-muted mb-1 small"><i
                                    class="bx bx-user me-1"></i>{{ $cartItem->user->name ?? __('admin.na') }}</p>
                            <p class="text-muted mb-0 small"><i
                                    class="bx bx-box me-1"></i>{{ $cartItem->product->name ?? __('admin.na') }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary">{{ $cartItem->quantity }}</div>
                                <div class="text-muted small">{{ __('admin.quantity') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-5 text-success">
                                    ₹{{ number_format($cartItem->product->price ?? 0, 2) }}</div>
                                <div class="text-muted small">{{ __('admin.price') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-5 text-warning">
                                    ₹{{ number_format(($cartItem->product->price ?? 0) * $cartItem->quantity, 2) }}</div>
                                <div class="text-muted small">{{ __('admin.total') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Info --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i class="bx bx-user me-2 text-primary"></i>{{ __('admin.user') }}
                        {{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.name') }}</div>
                                <div class="fw-bold">{{ $cartItem->user->name ?? __('admin.dash') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.email') }}</div>
                                <div class="fw-bold">{{ $cartItem->user->email ?? __('admin.dash') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i class="bx bx-box me-2 text-success"></i>{{ __('admin.product') }}
                        {{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.name') }}</div>
                                <div class="fw-bold">{{ $cartItem->product->name ?? __('admin.dash') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.price') }}</div>
                                <div class="fw-bold text-success">
                                    {{ $cartItem->product->price ? '₹' . number_format($cartItem->product->price, 2) : __('admin.dash') }}
                                </div>
                            </div>
                        </div>
                        @if ($cartItem->product)
                            <div class="col-12">
                                <a href="{{ route('admin.product.show', $cartItem->product->id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bx bx-link-external me-1"></i>{{ __('admin.view') }}
                                    {{ __('admin.product') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT col-lg-4 ===== --}}
        <div class="col-lg-4">

            {{-- Information --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $cartItem->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.user') }}</span>
                            <span class="small">{{ $cartItem->user->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.product') }}</span>
                            <span class="small">{{ Str::limit($cartItem->product->name ?? '—', 20) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.quantity') }}</span>
                            <span class="badge bg-label-primary fs-6">{{ $cartItem->quantity }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.status') }}</span>
                            <span
                                class="badge rounded-pill {{ $cartItem->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $cartItem->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.created') }}</span>
                            <span class="small">{{ $cartItem->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('admin.updated') }}</span>
                            <span class="small">{{ $cartItem->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.cart-item.edit', $cartItem->id) }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}
                    </a>
                    @if ($cartItem->user)
                        <a href="{{ route('admin.user.show', $cartItem->user->id) }}"
                            class="btn btn-outline-primary btn-lg">
                            <i class="bx bx-user me-1"></i>{{ __('admin.view') }} {{ __('admin.user') }}
                        </a>
                    @endif
                    <form action="{{ route('admin.cart-item.destroy', $cartItem->id) }}" method="POST"
                        class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete">
                            <i class="bx bx-trash me-1"></i>{{ __('admin.delete') }}
                        </button>
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
