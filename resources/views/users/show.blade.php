@extends('layouts.admin')
@section('title', __('messages.user_details') . ' — ' . $user->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
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

    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            @if ($user->profile_photo)
                <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}"
                    class="rounded-circle border border-2 border-white flex-shrink-0"
                    style="width:54px;height:54px;object-fit:cover;"
                    onerror="this.outerHTML='<div class=\'rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center\' style=\'width:54px;height:54px;background:rgba(255,255,255,.2)\'><span class=\'text-white fw-bold fs-5\'>{{ strtoupper(substr($user->name, 0, 1)) }}</span></div>'">
            @else
                <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                    style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                    <span class="text-white fw-bold fs-5">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $user->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-envelope me-1"></i>{{ $user->email }}</span>
                    @if ($user->phone)
                        <span>· {{ $user->phone }}</span>
                    @endif
                    <span>· {{ $user->roles->pluck('name')->implode(', ') ?: '{{ __("messages.role") }}' }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $user->status === 'active' ? 'text-success' : 'text-secondary' }}">
                    <i
                        class="bx {{ $user->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($user->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-user me-2 text-primary"></i>{{ __('messages.user_details') }}
                    </h6>
                    <span class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $user->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="flex-shrink-0">
                            @if ($user->profile_photo)
                                <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}"
                                    class="rounded-circle shadow-sm"
                                    style="width:80px;height:80px;object-fit:cover;border:3px solid #e0e0e0;"
                                    onerror="imgError(this)">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary"
                                    style="width:80px;height:80px;">
                                    <span class="fw-bold text-primary" style="font-size:2rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-1 small"><i class="bx bx-envelope me-1"></i>{{ $user->email }}</p>
                            @if ($user->phone)
                                <p class="text-muted mb-1 small"><i class="bx bx-phone me-1"></i>{{ $user->phone }}</p>
                            @endif
                            <span class="badge bg-label-primary">
                                {{ $user->roles->pluck('name')->implode(', ') ?: '{{ __("messages.role") }}' }}
                            </span>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">
                                    {{ $user->roles->count() }}
                                </div>
                                <div class="text-muted small">{{ __('messages.th_role') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-4 text-success">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '—' }}
                                </div>
                                <div class="text-muted small">{{ __('messages.last_login') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-info">
                                <div class="fw-bold fs-4 text-info">
                                    {{ $user->created_at->format('d M Y') }}
                                </div>
                                <div class="text-muted small">{{ __('messages.th_created') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permissions / Role Details --}}
            @if ($user->roles->isNotEmpty())
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-shield me-2 text-warning"></i>{{ __('messages.role') }} &amp;
                            {{ __('messages.th_permissions') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @foreach ($user->roles as $role)
                            <div class="mb-3">
                                <h6 class="fw-bold mb-2">
                                    <span class="badge bg-label-primary me-2">{{ $role->name }}</span>
                                </h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($role->permissions->take(20) as $perm)
                                        <span class="badge bg-label-secondary small">{{ $perm->name }}</span>
                                    @endforeach
                                    @if ($role->permissions->count() > 20)
                                        <span class="badge bg-label-info">+{{ $role->permissions->count() - 20 }}
                                            more</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right --}}
        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $user->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_name') }}</span>
                            <span class="fw-bold">{{ $user->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.role') }}</span>
                            <span class="badge bg-label-primary">
                                {{ $user->roles->pluck('name')->implode(', ') ?: '—' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $user->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $user->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $user->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
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
                                    <i class="bx bx-trash me-1"></i> {{ __('messages.delete_user') }}
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
                cancelButtonText: '{{ __('messages.cancel') }}'
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endpush

