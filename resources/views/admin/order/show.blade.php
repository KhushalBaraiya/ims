@extends('layouts.admin')
@section('title', 'Order — ' . $order->order_number)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.orders') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">{{ __('admin.orders') }}</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Order Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                            style="width:80px;height:80px;">
                            <i class="bx bx-cart-alt text-primary" style="font-size:2.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0 text-primary">{{ $order->order_number }}</h3>
                                @php
                                    $pBadge = [
                                        'paid' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'failed' => 'bg-danger',
                                    ];
                                    $sBadge = ['active' => 'bg-success', 'inactive' => 'bg-secondary'];
                                @endphp
                                <span
                                    class="badge rounded-pill {{ $pBadge[$order->payment_status] ?? 'bg-secondary' }} px-3 py-2">{{ admin_label($order->payment_status, 'ps') }}</span>
                                <span
                                    class="badge rounded-pill {{ $sBadge[$order->status] ?? 'bg-secondary' }} px-3 py-2">{{ admin_label($order->status, 'ps') }}</span>
                            </div>
                            <p class="text-muted mb-1 small"><i
                                    class="bx bx-user me-1"></i>{{ $order->user->name ?? __('admin.na') }}</p>
                            <p class="text-muted mb-0 small"><i class="bx bx-calendar me-1"></i>{{ $order->order_date }}
                            </p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary">{{ $order->quantity }}</div>
                                <div class="text-muted small">{{ __('admin.qty') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-4 text-success">₹{{ number_format($order->price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.price') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-4 text-warning">₹{{ number_format($order->total_amount, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.total') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-6 text-info">{{ admin_label($order->payment_method, 'pm') }}</div>
                                <div class="text-muted small">{{ __('admin.payment_method') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            @if ($order->items->count())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-list-ul me-2 text-info"></i>{{ __('admin.order_items') }} <span
                                class="badge bg-label-info ms-2">{{ $order->items->count() }}</span></h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.product') }}</th>
                                        <th class="text-center">{{ __('admin.qty') }}</th>
                                        <th>{{ __('admin.price') }}</th>
                                        <th>{{ __('admin.total') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($item->product_image)
                                                        <img src="{{ asset('uploads/products/' . $item->product_image) }}"
                                                        class="tbl-img" onerror="imgError(this)">@else<div
                                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                                            <i class="bx bx-package text-muted"></i></div>
                                                    @endif
                                                    <strong>{{ $item->product_name }}</strong>
                                                </div>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge bg-label-primary">{{ $item->quantity }}</span></td>
                                            <td>₹{{ number_format($item->price, 0) }}</td>
                                            <td><strong
                                                    class="text-success">₹{{ number_format($item->total_price, 0) }}</strong>
                                            </td>
                                            <td class="text-center"><a
                                                    href="{{ route('admin.order-item.show', $item->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"><i
                                                        class="bx bx-show"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Shipping Address --}}
            @if ($order->shipping_address)
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-map me-2 text-warning"></i>{{ __('admin.shipping_address') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0" style="white-space:pre-line;">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Info Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">ID</span><span
                                class="fw-bold">#{{ $order->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.order_number') }}</span><span
                                class="fw-bold text-primary">{{ $order->order_number }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.customer') }}</span><span
                                class="small">{{ $order->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.payment_method') }}</span><span
                                class="small">{{ admin_label($order->payment_method, 'pm') }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.payment_status') }}</span><span
                                class="badge {{ $pBadge[$order->payment_status] ?? 'bg-secondary' }}">{{ admin_label($order->payment_status, 'ps') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $sBadge[$order->status] ?? 'bg-secondary' }}">{{ admin_label($order->status, 'ps') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.total') }}</span><span
                                class="fw-bold text-success">₹{{ number_format($order->total_amount, 2) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.date') }}</span><span
                                class="small">{{ $order->order_date }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $order->created_at->format('d M Y') }}</span></li>
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
                    <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($order->user)
                        <a href="{{ route('admin.user.show', $order->user->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-user me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.user') }}</a>
                    @endif
                    <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST" class="delete-form">
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
