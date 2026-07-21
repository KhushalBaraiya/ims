@extends('layouts.admin')
@section('title', __('messages.edit_permission') . ' — ' . $permission->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_permission') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('permissions.index') }}">{{ __('messages.menu_permissions') }}</a></li>
                    <li class="breadcrumb-item active">{{ $permission->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
        @csrf @method('PUT')
        <div class="row g-4">

            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Permission Name Form --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-key me-2 text-warning"></i>{{ __('messages.permission_details_card') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Preview --}}
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3 py-2">
                            <i class="bx bx-error-circle fs-5"></i>
                            <div class="small">
                                {{ __('messages.permission_rename_warning') }}
                            </div>
                        </div>

                        <div class="alert alert-primary d-flex align-items-center gap-2 mb-4 py-2">
                            <i class="bx bx-info-circle fs-5"></i>
                            <div>
                                {{ __('messages.permission_name_hint') }}:
                                <code id="previewName" class="fw-bold ms-1">{{ $permission->name }}</code>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.module_label') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="moduleInput" name="module" list="moduleList"
                                    class="form-control @error('module') is-invalid @enderror" placeholder="e.g. products"
                                    value="{{ old('module', $currentModule) }}" required autocomplete="off">
                                <datalist id="moduleList">
                                    @foreach ($allModules as $mod)
                                        <option value="{{ $mod }}">
                                    @endforeach
                                </datalist>
                                <div class="form-text">{{ __('messages.module_hint') }}</div>
                                @error('module')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.action_label') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="actionInput" name="action" list="actionList"
                                    class="form-control @error('action') is-invalid @enderror" placeholder="e.g. view"
                                    value="{{ old('action', $currentAction) }}" required autocomplete="off">
                                <datalist id="actionList">
                                    <option value="view">
                                    <option value="create">
                                    <option value="update">
                                    <option value="delete">
                                    <option value="own">
                                </datalist>
                                <div class="form-text">{{ __('messages.action_hint') }}</div>
                                @error('action')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Role Assignment Section --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-shield me-2 text-primary"></i>Assigned Roles
                        </h6>
                        <span class="badge bg-label-warning">{{ count($assignedRoleIds) }} Roles Attached</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Select which roles should have this permission:</p>
                        <div class="row g-3">
                            @foreach ($allRoles as $role)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check card-body p-3 border rounded">
                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                            value="{{ $role->id }}" id="role_{{ $role->id }}"
                                            @checked(in_array($role->id, old('roles', $assignedRoleIds)))>
                                        <label class="form-check-label fw-semibold ms-1" for="role_{{ $role->id }}">
                                            @if ($role->name === 'super_admin')
                                                <span class="badge bg-label-warning"><i class="bx bx-crown me-1"></i> Super Admin</span>
                                            @elseif (in_array($role->name, ['manager', 'admin']))
                                                <span class="badge bg-label-primary"><i class="bx bx-shield me-1"></i> {{ ucfirst($role->name) }}</span>
                                            @else
                                                <span class="badge bg-label-secondary"><i class="bx bx-user me-1"></i> {{ ucfirst($role->name) }}</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('messages.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i> {{ __('messages.update') }} {{ __('messages.permission') }}
                            </button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle me-2 text-secondary"></i>{{ __('messages.information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">ID</span>
                                <span class="fw-bold">#{{ $permission->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.roles_count') }}</span>
                                <span class="badge bg-label-warning">{{ $permission->roles()->count() }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.th_created') }}</span>
                                <span>{{ $permission->created_at->format('d M Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.updated') }}</span>
                                <span>{{ $permission->updated_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        (function() {
            const moduleInput = document.getElementById('moduleInput');
            const actionInput = document.getElementById('actionInput');
            const previewName = document.getElementById('previewName');

            function updatePreview() {
                const m = moduleInput.value.trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
                const a = actionInput.value.trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
                previewName.textContent = (m || 'module') + '.' + (a || 'action');
            }

            moduleInput.addEventListener('input', updatePreview);
            actionInput.addEventListener('input', updatePreview);
        })();
    </script>
@endpush
