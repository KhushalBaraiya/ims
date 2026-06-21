@extends('layouts.admin')
@section('title', 'Notification')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.notifications') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.notification.index') }}">{{ __('admin.notifications') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $notification->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.notification.edit', $notification->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.notification.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bell me-2 text-warning"></i>{{ __('admin.notifications') }}</h6>
                    <div class="d-flex gap-2">
                        <span
                            class="badge {{ $notification->is_read ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">{{ $notification->is_read ? __('admin.read') : __('admin.unread') }}</span>
                        <span
                            class="badge rounded-pill {{ $notification->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $notification->status }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if ($notification->user && $notification->user->image)
                            <img src="{{ asset($notification->user->image) }}" class="rounded-circle flex-shrink-0"
                                style="width:50px;height:50px;object-fit:cover;">
                        @else<div
                                class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                                style="width:50px;height:50px;font-size:1.2rem;font-weight:700;color:#696cff;">
                                {{ strtoupper(substr($notification->user->name ?? 'U', 0, 1)) }}</div>
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0">{{ $notification->user->name ?? '—' }}</h6>
                            <p class="text-muted mb-0 small">{{ $notification->user->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 p-3 rounded-3" style="background:#fff8f0;border-left:4px solid #ffab00;">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.type') }}</div><span
                            class="badge bg-label-warning px-3 py-2">{{ $notification->type }}</span>
                    </div>
                    <div class="p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.message') }}</div>
                        <p class="mb-0" style="white-space:pre-line;line-height:1.7;">{{ $notification->message }}</p>
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
                                class="fw-bold">#{{ $notification->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.user') }}</span><span
                                class="small">{{ $notification->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.type') }}</span><span
                                class="badge bg-label-warning">{{ $notification->type }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.read') }}</span><span
                                class="badge {{ $notification->is_read ? 'bg-success' : 'bg-warning text-dark' }}">{{ $notification->is_read ? 'Yes' : 'No' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $notification->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $notification->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $notification->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $notification->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.notification.edit', $notification->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($notification->user)
                        <a href="{{ route('admin.user.show', $notification->user->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-user me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.user') }}</a>
                    @endif
                    <form action="{{ route('admin.notification.destroy', $notification->id) }}" method="POST"
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
