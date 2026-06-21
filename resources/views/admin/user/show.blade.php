@extends('layouts.admin')
@section('title', 'User — ' . $user->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.users') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('admin.users') }}</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        @if ($user->image)
                            <img src="{{ asset($user->image) }}" class="rounded-circle flex-shrink-0"
                                style="width:90px;height:90px;object-fit:cover;" onerror="imgError(this)">
                        @else<div
                                class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                                style="width:90px;height:90px;font-size:2rem;font-weight:700;color:#696cff;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h3 class="fw-bold mb-0">{{ $user->name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $user->status }}</span>
                                @if ($user->role)
                                    <span class="badge bg-label-info px-2 py-1">{{ $user->role->name }}</span>
                                @endif
                            </div>
                            <p class="text-muted mb-1 small"><i class="bx bx-envelope me-1"></i>{{ $user->email }}</p>
                            <p class="text-muted mb-0 small"><i class="bx bx-phone me-1"></i>{{ $user->phone ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary">{{ $user->orders_count }}</div>
                                <div class="text-muted small">{{ __('admin.orders') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-2 text-success">{{ $user->reviews_count }}</div>
                                <div class="text-muted small">{{ __('admin.reviews') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-2 text-warning">{{ $user->addresses->count() }}</div>
                                <div class="text-muted small">{{ __('admin.user_addresses') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-user-detail me-2 text-primary"></i>{{ __('admin.profile') }}</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.gender') }}</div>
                                <div class="fw-bold">{{ $user->gender ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.dob') }}</div>
                                <div class="fw-bold">{{ $user->dob ? $user->dob->format('d M Y') : '—' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.address') }}</div>
                                <div class="fw-bold">{{ $user->address ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($user->orders->count())
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-cart me-2 text-info"></i>{{ __('admin.recent_orders') }} <span
                                class="badge bg-label-info ms-2">{{ $user->orders_count }}</span></h6>
                        <a href="{{ route('admin.order.index') }}"
                            class="btn btn-sm btn-outline-info">{{ __('admin.view_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.order_number') }}</th>
                                        <th>{{ __('admin.amount') }}</th>
                                        <th>{{ __('admin.payment_status') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->orders->take(5) as $order)
                                        <tr>
                                            <td class="ps-4"><strong
                                                    class="text-primary">{{ $order->order_number }}</strong><br><small
                                                    class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td><strong>₹{{ number_format($order->total_amount, 0) }}</strong></td>
                                            <td><span
                                                    class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : ($order->payment_status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ admin_label($order->payment_status, 'ps') }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge rounded-pill {{ $order->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $order->status }}</span>
                                            </td>
                                            <td class="text-center"><a href="{{ route('admin.order.show', $order->id) }}"
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
                                class="fw-bold">#{{ $user->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.role') }}</span><span
                                class="badge bg-label-info">{{ $user->role->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $user->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.orders') }}</span><span
                                class="badge bg-label-primary">{{ $user->orders_count }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.reviews') }}</span><span
                                class="badge bg-label-success">{{ $user->reviews_count }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $user->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $user->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <a href="{{ route('admin.user-address.index') }}" class="btn btn-outline-info btn-lg"><i
                            class="bx bx-map me-1"></i>{{ __('admin.user_addresses') }}</a>
                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="delete-form">@csrf
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
