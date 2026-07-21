@extends('layouts.admin')
@section('title', __('messages.add_permission_title'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_permission_title') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('permissions.index') }}">{{ __('messages.menu_permissions') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('permissions.store') }}" id="createPermissionForm">
        @csrf
        <div class="row g-4">

            {{-- Left Column --}}
            <div class="col-lg-8">

                {{-- 1. Module & Actions Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-key me-2 text-warning"></i>1. Module & Actions Selection
                        </h6>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="selectAllActionsSwitch" role="switch">
                            <label class="form-check-label fw-semibold small" for="selectAllActionsSwitch">Select All Actions</label>
                        </div>
                    </div>
                    <div class="card-body p-4">

                        {{-- Preview Badge --}}
                        <div class="alert alert-primary d-flex align-items-center gap-2 mb-4 py-2">
                            <i class="bx bx-info-circle fs-5"></i>
                            <div>
                                <span class="fw-semibold">Generated Permissions:</span>
                                <div id="previewBadgeList" class="d-inline-flex flex-wrap gap-1 ms-2">
                                    <code class="bg-white px-2 py-1 rounded text-primary border">module.action</code>
                                </div>
                            </div>
                        </div>

                        {{-- Module Input --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('messages.module_label') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="moduleInput" name="module" list="moduleList"
                                class="form-control form-control-lg @error('module') is-invalid @enderror" placeholder="e.g. products, sales, reports"
                                value="{{ old('module') }}" required autocomplete="off">
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

                        {{-- Action Checkboxes (Batch Selection) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Select Actions to Create <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                @php
                                    $standardActions = [
                                        'view'   => ['label' => 'View / Index', 'badge' => 'bg-label-info', 'desc' => 'view listing & details'],
                                        'create' => ['label' => 'Create / Add', 'badge' => 'bg-label-success', 'desc' => 'add new record'],
                                        'update' => ['label' => 'Update / Edit', 'badge' => 'bg-label-warning', 'desc' => 'edit existing record'],
                                        'delete' => ['label' => 'Delete / Remove', 'badge' => 'bg-label-danger', 'desc' => 'delete record'],
                                        'own'    => ['label' => 'Own Only', 'badge' => 'bg-label-secondary', 'desc' => 'own records filter'],
                                    ];
                                @endphp
                                @foreach ($standardActions as $actKey => $actMeta)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card border h-100 action-card" style="cursor:pointer;">
                                            <div class="card-body p-3 d-flex align-items-center gap-2">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input action-checkbox" type="checkbox"
                                                        name="actions[]" value="{{ $actKey }}" id="act_{{ $actKey }}"
                                                        @checked(in_array($actKey, old('actions', ['view', 'create', 'update', 'delete'])))>
                                                </div>
                                                <div>
                                                    <label class="form-check-label fw-semibold mb-0" for="act_{{ $actKey }}">
                                                        <span class="badge {{ $actMeta['badge'] }} me-1">{{ ucfirst($actKey) }}</span>
                                                    </label>
                                                    <div class="text-muted" style="font-size:0.75rem;">{{ $actMeta['desc'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Optional Custom Action --}}
                        <div class="mt-4 pt-3 border-top">
                            <label class="form-label fw-semibold small text-muted">Or Add Custom Action (Optional)</label>
                            <div class="input-group input-group-merge style-input-group">
                                <span class="input-group-text"><i class="bx bx-plus-circle"></i></span>
                                <input type="text" id="customActionInput" name="custom_action" class="form-control"
                                    placeholder="e.g. export, print, approve" value="{{ old('custom_action') }}">
                            </div>
                            <div class="form-text">If typed, this custom action will be included in the batch.</div>
                        </div>

                    </div>
                </div>

                {{-- 2. Role Assignment Matrix Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-shield me-2 text-primary"></i>2. Assign to Roles Directly
                            </h6>
                            <small class="text-muted">Select which actions are assigned to each role upon creation</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-xs btn-outline-primary" id="presetSuperAdminAll">
                                <i class="bx bx-crown me-1"></i>Super Admin All
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" id="presetClearAll">
                                Clear All
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:140px;">Role Name</th>
                                        <th class="text-center" style="width:70px;">All</th>
                                        @foreach (['view', 'create', 'update', 'delete', 'own'] as $actHeader)
                                            <th class="text-center text-capitalize action-col-header" data-action="{{ $actHeader }}" style="width:80px;">
                                                {{ $actHeader }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allRoles as $role)
                                        @php
                                            $isSuper = $role->name === 'super_admin';
                                            $isManager = in_array($role->name, ['manager', 'admin']);
                                        @endphp
                                        <tr data-role-id="{{ $role->id }}">
                                            <td class="fw-semibold">
                                                @if ($isSuper)
                                                    <span class="badge bg-label-warning me-1"><i class="bx bx-crown"></i> {{ $role->name }}</span>
                                                @elseif ($isManager)
                                                    <span class="badge bg-label-primary me-1"><i class="bx bx-shield"></i> {{ $role->name }}</span>
                                                @else
                                                    <span class="badge bg-label-secondary me-1"><i class="bx bx-user"></i> {{ $role->name }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center mb-0">
                                                    <input class="form-check-input role-row-all-switch" type="checkbox"
                                                        data-role-id="{{ $role->id }}" title="Toggle all actions for {{ $role->name }}"
                                                        @checked($isSuper)>
                                                </div>
                                            </td>
                                            @foreach (['view', 'create', 'update', 'delete', 'own'] as $act)
                                                @php
                                                    // Default presets: Super admin gets all, Manager gets view/create/update, Staff gets view
                                                    $defaultChecked = $isSuper || ($isManager && in_array($act, ['view', 'create', 'update'])) || (! $isSuper && ! $isManager && $act === 'view');
                                                @endphp
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center mb-0">
                                                        <input class="form-check-input role-action-checkbox" type="checkbox"
                                                            name="role_actions[{{ $role->id }}][]" value="{{ $act }}"
                                                            data-role-id="{{ $role->id }}" data-action="{{ $act }}"
                                                            @checked(old("role_actions.{$role->id}") ? in_array($act, old("role_actions.{$role->id}")) : $defaultChecked)>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Publish --}}
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top:90px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i>Publish & Save
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 py-2">
                            <i class="bx bx-check-shield fs-4"></i>
                            <div class="small">
                                Created permissions will be assigned to selected roles <strong>instantly</strong> without running seeders.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i> Save Permissions & Roles
                            </button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            const moduleInput = document.getElementById('moduleInput');
            const customActionInput = document.getElementById('customActionInput');
            const previewBadgeList = document.getElementById('previewBadgeList');
            const actionCheckboxes = document.querySelectorAll('.action-checkbox');
            const selectAllActionsSwitch = document.getElementById('selectAllActionsSwitch');

            // ── Update Live Preview Badges ─────────────────────────────────────
            function updatePreview() {
                const module = (moduleInput.value || 'module').trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
                const selectedActions = [];

                actionCheckboxes.forEach(chk => {
                    if (chk.checked) selectedActions.push(chk.value);
                });

                if (customActionInput && customActionInput.value.trim()) {
                    const customAct = customActionInput.value.trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
                    if (customAct && !selectedActions.includes(customAct)) {
                        selectedActions.push(customAct);
                    }
                }

                if (selectedActions.length === 0) {
                    previewBadgeList.innerHTML = `<code class="bg-white px-2 py-1 rounded text-muted border">${module}.action</code>`;
                    return;
                }

                let html = '';
                selectedActions.forEach(act => {
                    html += `<code class="bg-white px-2 py-1 rounded text-primary border me-1 mb-1">${module}.${act}</code>`;
                });
                previewBadgeList.innerHTML = html;

                syncRoleMatrixColumns(selectedActions);
            }

            // ── Sync Role Matrix Columns with Selected Actions ─────────────────
            function syncRoleMatrixColumns(selectedActions) {
                document.querySelectorAll('.action-col-header').forEach(header => {
                    const act = header.dataset.action;
                    if (selectedActions.includes(act)) {
                        header.classList.remove('opacity-25');
                    } else {
                        header.classList.add('opacity-25');
                    }
                });

                document.querySelectorAll('.role-action-checkbox').forEach(chk => {
                    const act = chk.dataset.action;
                    if (selectedActions.includes(act)) {
                        chk.disabled = false;
                        chk.closest('td').classList.remove('bg-light', 'opacity-25');
                    } else {
                        chk.disabled = true;
                        chk.closest('td').classList.add('bg-light', 'opacity-25');
                    }
                });
            }

            // Event Listeners for Module & Actions
            moduleInput.addEventListener('input', updatePreview);
            if (customActionInput) customActionInput.addEventListener('input', updatePreview);

            actionCheckboxes.forEach(chk => {
                chk.addEventListener('change', function() {
                    syncSelectAllActionsState();
                    updatePreview();
                });
            });

            // Action card click helper
            document.querySelectorAll('.action-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        const chk = this.querySelector('.action-checkbox');
                        chk.checked = !chk.checked;
                        chk.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Select All Actions Switch
            if (selectAllActionsSwitch) {
                selectAllActionsSwitch.addEventListener('change', function() {
                    const checked = this.checked;
                    actionCheckboxes.forEach(chk => chk.checked = checked);
                    updatePreview();
                });
            }

            function syncSelectAllActionsState() {
                if (!selectAllActionsSwitch) return;
                const allChecked = Array.from(actionCheckboxes).every(c => c.checked);
                selectAllActionsSwitch.checked = allChecked;
            }

            // ── Role Row "All" Switch ──────────────────────────────────────────
            document.querySelectorAll('.role-row-all-switch').forEach(rowSwitch => {
                rowSwitch.addEventListener('change', function() {
                    const roleId = this.dataset.roleId;
                    const checked = this.checked;

                    document.querySelectorAll(`.role-action-checkbox[data-role-id="${roleId}"]`).forEach(chk => {
                        if (!chk.disabled) {
                            chk.checked = checked;
                        }
                    });
                });
            });

            // Sync row switch when individual role checkboxes change
            document.querySelectorAll('.role-action-checkbox').forEach(chk => {
                chk.addEventListener('change', function() {
                    const roleId = this.dataset.roleId;
                    const roleChecks = Array.from(document.querySelectorAll(`.role-action-checkbox[data-role-id="${roleId}"]:not(:disabled)`));
                    const rowSwitch = document.querySelector(`.role-row-all-switch[data-role-id="${roleId}"]`);
                    if (rowSwitch && roleChecks.length > 0) {
                        rowSwitch.checked = roleChecks.every(c => c.checked);
                    }
                });
            });

            // ── Presets ─────────────────────────────────────────────────────────
            const presetSuperAdminAll = document.getElementById('presetSuperAdminAll');
            if (presetSuperAdminAll) {
                presetSuperAdminAll.addEventListener('click', function() {
                    const superRowSwitch = document.querySelector('tr[data-role-id="1"] .role-row-all-switch') || document.querySelector('.role-row-all-switch');
                    if (superRowSwitch) {
                        superRowSwitch.checked = true;
                        superRowSwitch.dispatchEvent(new Event('change'));
                    }
                });
            }

            const presetClearAll = document.getElementById('presetClearAll');
            if (presetClearAll) {
                presetClearAll.addEventListener('click', function() {
                    document.querySelectorAll('.role-action-checkbox').forEach(c => c.checked = false);
                    document.querySelectorAll('.role-row-all-switch').forEach(s => s.checked = false);
                });
            }

            // Initial Sync
            syncSelectAllActionsState();
            updatePreview();

        })();
    </script>
@endpush
