@extends('layouts.admin')
@section('title', 'Payment')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.payments') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payment.index') }}">{{ __('admin.payments') }}</a>
                    </li>
                    <li class="breadcrumb-item active">#{{ $payment->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.payment.edit', $payment->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-success flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bx-credit-card text-success"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0">₹{{ number_format($payment->amount, 2) }} <span
                                        class="text-muted small">{{ $payment->currency }}</span></h3>
                                @php $pBadge=['paid'=>'bg-success','pending'=>'bg-warning text-dark','failed'=>'bg-danger']; @endphp
                                <span
                                    class="badge rounded-pill {{ $pBadge[$payment->payment_status] ?? 'bg-secondary' }} px-3 py-2">{{ admin_label($payment->payment_status, 'ps') }}</span>
                            </div>
                            <p class="text-muted mb-1 small"><i
                                    class="bx bx-user me-1"></i>{{ $payment->user->name ?? '—' }}</p>
                            @if ($payment->transaction_id)
                                <p class="text-muted mb-0 small"><i class="bx bx-hash me-1"></i>TXN:
                                    <code>{{ $payment->transaction_id }}</code></p>
                            @endif
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.payment_method') }}</div>
                                <div class="fw-bold">{{ admin_label($payment->payment_method, 'pm') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.gateway') }}</div>
                                <div class="fw-bold">{{ $payment->gateway ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.paid_at') }}</div>
                                <div class="fw-bold">
                                    {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, h:i A') : '—' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.order') }}</div>
                                @if ($payment->order)
                                    <a href="{{ route('admin.order.show', $payment->order->id) }}"
                                    class="fw-bold text-primary text-decoration-none">{{ $payment->order->order_number }}</a>@else<span
                                        class="text-muted">—</span>
                                @endif
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
                                class="fw-bold">#{{ $payment->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.amount') }}</span><span
                                class="fw-bold text-success">₹{{ number_format($payment->amount, 2) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.payment_status') }}</span><span
                                class="badge {{ $pBadge[$payment->payment_status] ?? 'bg-secondary' }}">{{ admin_label($payment->payment_status, 'ps') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $payment->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $payment->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.user') }}</span><span
                                class="small">{{ $payment->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $payment->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $payment->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.payment.edit', $payment->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($payment->order)
                        <a href="{{ route('admin.order.show', $payment->order->id) }}"
                            class="btn btn-outline-info btn-lg"><i class="bx bx-cart me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.order') }}</a>
                    @endif
                    @if ($payment->user)
                        <a href="{{ route('admin.user.show', $payment->user->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-user me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.user') }}</a>
                    @endif
                    <form action="{{ route('admin.payment.destroy', $payment->id) }}" method="POST" class="delete-form">
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
