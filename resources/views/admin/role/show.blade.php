@extends('layouts.admin')
@section('title', 'Role — ' . $role->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.roles') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">{{ __('admin.roles') }}</a></li>
                    <li class="breadcrumb-item active">{{ $role->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.role.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bx-shield text-primary"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold mb-0">{{ $role->name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $role->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $role->status }}</span>
                            </div>
                            <p class="text-muted mb-0 small"><i class="bx bx-group me-1"></i>{{ $role->users_count }}
                                {{ __('admin.users') }}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary">{{ $role->users_count }}</div>
                                <div class="text-muted small">{{ __('admin.total_users') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-2 text-success">
                                    {{ $role->users->where('status', 'active')->count() }}</div>
                                <div class="text-muted small">{{ __('admin.active') }} {{ __('admin.users') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-2 text-warning">{{ $role->created_at->format('Y') }}</div>
                                <div class="text-muted small">{{ __('admin.created') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($role->users->count())
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold fs-5"><i class="bx bx-group me-2 text-info"></i>{{ __('admin.users') }}
                            <span class="badge bg-label-info ms-2">{{ $role->users_count }}</span></h6>
                        <a href="{{ route('admin.user.index') }}"
                            class="btn btn-sm btn-outline-info">{{ __('admin.view_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.user') }}</th>
                                        <th>{{ __('admin.email') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($role->users->take(10) as $user)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($user->image)
                                                        <img src="{{ asset($user->image) }}" class="tbl-img-round"
                                                            onerror="imgError(this)">
                                                    @else<div
                                                            class="tbl-img-round d-flex align-items-center justify-content-center fw-bold text-white avatar-initials">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                                    @endif
                                                    <strong>{{ $user->name }}</strong>
                                                </div>
                                            </td>
                                            <td><small class="text-muted">{{ $user->email }}</small></td>
                                            <td class="text-center"><span
                                                    class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $user->status }}</span>
                                            </td>
                                            <td class="text-center"><a href="{{ route('admin.user.show', $user->id) }}"
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
                                class="fw-bold">#{{ $role->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.name') }}</span><span
                                class="fw-bold">{{ $role->name }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.total_users') }}</span><span
                                class="badge bg-label-primary">{{ $role->users_count }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $role->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $role->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $role->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $role->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-outline-primary btn-lg"><i
                            class="bx bx-plus me-1"></i>{{ __('admin.add_user') }}</a>
                    <form action="{{ route('admin.role.destroy', $role->id) }}" method="POST" class="delete-form">@csrf
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
