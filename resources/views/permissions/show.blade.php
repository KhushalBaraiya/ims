@extends('layouts.admin')
@section('title', __('messages.permission_details_card') . ' — ' . $permission->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.permission_details_card') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('permissions.index') }}">{{ __('messages.menu_permissions') }}</a></li>
                    <li class="breadcrumb-item active">{{ $permission->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('permissions.update')
                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Hero Banner --}}
    @php
        [$mod, $act] = str_contains($permission->name, '.')
            ? explode('.', $permission->name, 2)
            : [$permission->name, ''];
        $bannerColor = match ($act) {
            'view' => 'linear-gradient(135deg, #03c3ec, #0077b6)',
            'create' => 'linear-gradient(135deg, #71dd37, #1d8348)',
            'update' => 'linear-gradient(135deg, #ffab00, #c77c00)',
            'delete' => 'linear-gradient(135deg, #ff3e1d, #9b1c0a)',
            'own' => 'linear-gradient(135deg, #8592a3, #4a5568)',
            default => 'linear-gradient(135deg, #696cff, #9c3fe4)',
        };
    @endphp
    <div class="card border-0 shadow-sm mb-4" style="background: {{ $bannerColor }};">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-key text-white" style="font-size:1.6rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">
                    <code class="text-white">{{ $permission->name }}</code>
                </div>
                <div class="text-white opacity-75 small mt-1 d-flex flex-wrap gap-3">
                    <span><i class="bx bx-cube me-1"></i>{{ ucwords(str_replace('_', ' ', $mod)) }}</span>
                    <span><i class="bx bx-play me-1"></i>{{ ucfirst($act) }}</span>
                    <span><i class="bx bx-shield me-1"></i>{{ $permission->roles->count() }}
                        {{ __('messages.menu_roles') }}</span>
                    <span><i class="bx bx-calendar me-1"></i>{{ $permission->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <div>
                <span class="badge bg-white fw-semibold" style="color: #333;">
                    {{ ucfirst($act) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left: Roles that have this permission --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-shield me-2 text-primary"></i>{{ __('messages.menu_roles') }}
                        <span class="badge bg-label-primary ms-1">{{ $permission->roles->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if ($permission->roles->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-shield" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mb-0 mt-2 small">{{ __('messages.no_records') }}</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($permission->roles as $role)
                                <li class="list-group-item d-flex align-items-center justify-content-between px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-warning">
                                                <i class="bx bx-shield" style="font-size:1rem;"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">{{ $role->name }}</div>
                                            <div class="text-muted" style="font-size:.75rem;">
                                                {{ $role->permissions()->count() }} {{ __('messages.permissions') }}
                                            </div>
                                        </div>
                                    </div>
                                    @can('roles.view')
                                        <a href="{{ route('roles.show', $role->id) }}"
                                            class="btn btn-sm btn-icon btn-outline-secondary rounded-circle"
                                            style="width:28px;height:28px;padding:0;" title="{{ __('messages.view') }}">
                                            <i class="bx bx-show" style="font-size:.9rem;"></i>
                                        </a>
                                    @endcan
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Info + Quick Actions --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $permission->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.permission_name') }}</span>
                            <code class="small">{{ $permission->name }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.module_label') }}</span>
                            <span class="badge bg-label-info">{{ ucwords(str_replace('_', ' ', $mod)) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.action_label') }}</span>
                            <span class="badge bg-label-warning">{{ ucfirst($act) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.roles_count') }}</span>
                            <span class="badge bg-label-primary">{{ $permission->roles->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $permission->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $permission->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('permissions.update')
                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_permission') }}
                        </a>
                    @endcan
                    @can('permissions.delete')
                        <form id="deleteForm" action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $permission->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_permission') }}
                            </button>
                        </form>
                    @endcan
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-list-ul me-1"></i> {{ __('messages.menu_permissions') }}
                    </a>
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
