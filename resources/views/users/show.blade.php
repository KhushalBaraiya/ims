@extends('layouts.admin')
@section('title', __('messages.user_details') . ' — ' . $user->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.user_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('messages.menu_users') }}</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('users.update')
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        @if ($user->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}"
                                class="rounded-circle shadow-sm"
                                style="width:80px;height:80px;object-fit:cover;flex-shrink:0;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary"
                                style="width:80px;height:80px;flex-shrink:0;">
                                <span class="fw-bold text-primary"
                                    style="font-size:2rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                                <span
                                    class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                            <p class="text-muted small mb-1"><i class="bx bx-envelope me-1"></i>{{ $user->email }}</p>
                            <p class="text-muted small mb-1"><i class="bx bx-phone me-1"></i>{{ $user->phone ?: 'N/A' }}
                            </p>
                            <span
                                class="badge bg-label-primary">{{ $user->roles->pluck('name')->implode(', ') ?: 'Staff' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $user->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_name') }}</span>
                            <span class="fw-bold">{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.role') }}</span>
                            <span
                                class="badge bg-label-primary">{{ $user->roles->pluck('name')->implode(', ') ?: 'Staff' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.status') }}</span>
                            <span class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $user->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.update') }}</span>
                            <span class="small">{{ $user->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('users.update')
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_user') }}
                        </a>
                    @endcan
                    @can('users.delete')
                        @if (auth()->id() !== $user->id)
                            <form id="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                    data-name="{{ $user->name }}">
                                    <i class="bx bx-trash me-1"></i> {{ __('messages.delete') }}
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).on('click', '.delete-btn', function() {
            const name = $(this).data('name');
            Swal.fire({
                title: '{{ __('messages.confirm_delete') }}',
                text: `{{ __('messages.delete') }} "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('messages.yes_delete') }}',
                cancelButtonText: '{{ __('messages.cancel') }}',
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endpush
