@extends('layouts.admin')
@section('title', __('messages.role_details_card') . ' — ' . $role->name)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.role_details_card') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.menu_roles') }}</a></li>
                    <li class="breadcrumb-item active">{{ $role->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @can('roles.update')
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Hero Banner --}}
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #696cff, #9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-shield text-white" style="font-size:1.6rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $role->name }}</div>
                <div class="text-white opacity-75 small mt-1 d-flex flex-wrap gap-3">
                    <span><i class="bx bx-key me-1"></i>{{ $role->permissions->count() }}
                        {{ __('messages.permissions') }}</span>
                    <span><i class="bx bx-group me-1"></i>{{ $role->users->count() }} {{ __('messages.users') }}</span>
                    <span><i class="bx bx-calendar me-1"></i>{{ $role->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <div>
                @if ($role->name === 'super_admin')
                    <span class="badge bg-white text-warning fw-semibold">
                        <i class="bx bx-crown me-1"></i> Super Admin
                    </span>
                @else
                    <span class="badge bg-white text-primary fw-semibold">
                        <i class="bx bx-shield me-1"></i> {{ __('messages.role') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Permissions Matrix Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-key me-2 text-warning"></i>{{ __('messages.permissions') }}
                        <span class="badge bg-label-warning ms-1">{{ $role->permissions->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.module_label') }}</th>
                                    <th class="text-center" style="width:70px;">{{ __('messages.view') }}</th>
                                    <th class="text-center" style="width:70px;">{{ __('messages.own') }}</th>
                                    <th class="text-center" style="width:70px;">{{ __('messages.create') }}</th>
                                    <th class="text-center" style="width:70px;">{{ __('messages.update') }}</th>
                                    <th class="text-center" style="width:70px;">{{ __('messages.delete') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($crudPermissions as $module => $actions)
                                    @php
                                        $hasAny = collect($actions)->contains(
                                            fn($p) => in_array($p->name, $selectedPermissions),
                                        );
                                    @endphp
                                    <tr class="{{ $hasAny ? '' : 'text-muted opacity-50' }}">
                                        <td class="fw-semibold ps-3">{{ $module }}</td>
                                        @foreach (['view', 'own', 'create', 'update', 'delete'] as $action)
                                            <td class="text-center">
                                                @if (isset($actions[$action]))
                                                    @if (in_array($actions[$action]->name, $selectedPermissions))
                                                        <i class="bx bx-check-circle text-success" style="font-size:1.2rem;"
                                                            title="{{ $actions[$action]->name }}"></i>
                                                    @else
                                                        <i class="bx bx-x-circle text-danger opacity-25"
                                                            style="font-size:1.2rem;"></i>
                                                    @endif
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Assigned Users Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-group me-2 text-primary"></i>{{ __('messages.users') }}
                        <span class="badge bg-label-primary ms-1">{{ $role->users->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if ($role->users->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-group" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mb-0 mt-2 small">{{ __('messages.no_records') }}</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($role->users as $user)
                                <li class="list-group-item d-flex align-items-center justify-content-between px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($user->profile_photo)
                                            <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}"
                                                class="rounded-circle flex-shrink-0"
                                                style="width:36px;height:36px;object-fit:cover;">
                                        @else
                                            <div class="rounded-circle bg-label-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:36px;height:36px;">
                                                <span
                                                    class="fw-bold text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold small">{{ $user->name }}</div>
                                            <div class="text-muted" style="font-size:.75rem;">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="badge rounded-pill {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </span>
                                        @can('users.view')
                                            <a href="{{ route('users.show', $user->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-secondary rounded-circle"
                                                style="width:28px;height:28px;padding:0;" title="{{ __('messages.view') }}">
                                                <i class="bx bx-show" style="font-size:.9rem;"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Info Card --}}
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
                            <span class="fw-bold">#{{ $role->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.system_name') }}</span>
                            <code class="small">{{ $role->name }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.permissions') }}</span>
                            <span class="badge bg-label-warning">{{ $role->permissions->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.users') }}</span>
                            <span class="badge bg-label-primary">{{ $role->users->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $role->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $role->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Permissions Summary Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bar-chart-alt-2 me-2 text-info"></i>{{ __('messages.permissions') }}
                        {{ __('messages.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $modulesWithAccess = collect($crudPermissions)
                            ->filter(
                                fn($actions) => collect($actions)->contains(
                                    fn($p) => in_array($p->name, $selectedPermissions),
                                ),
                            )
                            ->count();
                        $totalModules = count($crudPermissions);
                        $pct = $totalModules > 0 ? round(($modulesWithAccess / $totalModules) * 100) : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted fw-semibold">{{ __('messages.module_label') }}
                                {{ __('messages.view') }}</span>
                            <span class="small fw-bold">{{ $modulesWithAccess }} / {{ $totalModules }}</span>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width:{{ $pct }}%">
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        @foreach (['view', 'create', 'update', 'delete'] as $act)
                            @php
                                $count = collect($selectedPermissions)
                                    ->filter(fn($p) => str_ends_with($p, '.' . $act))
                                    ->count();
                            @endphp
                            <div class="col-6">
                                <div
                                    class="rounded-3 p-2 text-center bg-label-{{ $act === 'view' ? 'info' : ($act === 'create' ? 'success' : ($act === 'update' ? 'warning' : 'danger')) }}">
                                    <div
                                        class="fw-bold fs-5 text-{{ $act === 'view' ? 'info' : ($act === 'create' ? 'success' : ($act === 'update' ? 'warning' : 'danger')) }}">
                                        {{ $count }}</div>
                                    <div class="small text-muted">{{ ucfirst($act) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('roles.update')
                        @if ($role->name !== 'super_admin')
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i> {{ __('messages.edit_role') }}
                            </a>
                        @endif
                    @endcan
                    @can('roles.delete')
                        @if ($role->name !== 'super_admin')
                            <form id="deleteForm" action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                    data-name="{{ $role->name }}">
                                    <i class="bx bx-trash me-1"></i> {{ __('messages.delete_role') }}
                                </button>
                            </form>
                        @endif
                    @endcan
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-list-ul me-1"></i> {{ __('messages.menu_roles') }}
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
