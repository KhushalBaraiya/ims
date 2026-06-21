@extends('layouts.admin')
@section('title', 'Return Order')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.return_orders') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.return-order.index') }}">{{ __('admin.return_orders') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $return->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.return-order.edit', $return->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.return-order.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-danger flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bx-revision text-danger"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h4 class="fw-bold mb-0">{{ __('admin.return_orders') }} #{{ $return->id }}</h4>
                                @php $sBadge=['pending'=>'bg-warning text-dark','approved'=>'bg-success','rejected'=>'bg-danger','completed'=>'bg-info']; @endphp
                                <span
                                    class="badge rounded-pill {{ $sBadge[$return->status] ?? 'bg-secondary' }} px-3 py-2">{{ admin_label($return->status, 'ps') }}</span>
                            </div>
                            <p class="text-muted mb-1 small"><i
                                    class="bx bx-user me-1"></i>{{ $return->user->name ?? '—' }}</p>
                            <p class="text-muted mb-0 small"><i
                                    class="bx bx-calendar me-1"></i>{{ $return->return_date ?? $return->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff0f0;">
                                <div class="fw-bold fs-2 text-danger">{{ $return->quantity }}</div>
                                <div class="text-muted small">{{ __('admin.qty') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-3 text-success">₹{{ number_format($return->refund_amount, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.refund_amount') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-5 text-primary">{{ admin_label($return->return_type, 'rt') }}</div>
                                <div class="text-muted small">{{ __('admin.type') }}</div>
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
                    @if ($return->reason)
                        <div class="mb-3 p-3 rounded-3 bg-light">
                            <div class="text-muted small mb-1 fw-semibold">{{ __('admin.reason') }}</div>
                            <p class="mb-0">{{ $return->reason }}</p>
                        </div>
                    @endif
                    @if ($return->admin_note)
                        <div class="p-3 rounded-3" style="background:#f0f4ff;">
                            <div class="text-muted small mb-1 fw-semibold">{{ __('admin.admin_note') }}</div>
                            <p class="mb-0">{{ $return->admin_note }}</p>
                        </div>
                    @endif
                    @if ($return->product)
                        <div class="mt-3 p-3 rounded-3 bg-light">
                            <div class="text-muted small mb-2 fw-semibold">{{ __('admin.product') }}</div>
                            <div class="d-flex align-items-center gap-3">
                                @if (!empty($return->product->images))
                                    <img src="{{ asset('uploads/products/' . $return->product->images[0]) }}"
                                        class="tbl-img rounded-3" onerror="imgError(this)">
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $return->product->name }}</h6>
                                    <p class="text-muted small mb-0">₹{{ number_format($return->product->price, 0) }}</p>
                                </div>
                                <a href="{{ route('admin.product.show', $return->product->id) }}"
                                    class="btn btn-sm btn-outline-info ms-auto"><i
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
                                class="fw-bold">#{{ $return->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.order') }}</span>
                            @if ($return->order)
                                <a href="{{ route('admin.order.show', $return->order->id) }}"
                                class="badge bg-label-primary text-decoration-none">{{ $return->order->order_number }}</a>@else<span
                                    class="text-muted">—</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.user') }}</span><span
                                class="small">{{ $return->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.type') }}</span><span
                                class="badge bg-label-info">{{ admin_label($return->return_type, 'rt') }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge {{ $sBadge[$return->status] ?? 'bg-secondary' }}">{{ admin_label($return->status, 'ps') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.refund_amount') }}</span><span
                                class="fw-bold text-success">₹{{ number_format($return->refund_amount, 2) }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $return->created_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.return-order.edit', $return->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($return->order)
                        <a href="{{ route('admin.order.show', $return->order->id) }}"
                            class="btn btn-outline-info btn-lg"><i class="bx bx-cart me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.order') }}</a>
                    @endif
                    @if ($return->user)
                        <a href="{{ route('admin.user.show', $return->user->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-user me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.user') }}</a>
                    @endif
                    <form action="{{ route('admin.return-order.destroy', $return->id) }}" method="POST"
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
