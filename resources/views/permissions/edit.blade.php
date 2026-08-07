@extends('layouts.admin')
@section('title', __('messages.edit_permission') . ' — ' . $permission->name)

@section('content')

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_permission') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('permissions.index') }}">{{ __('messages.menu_permissions') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $permission->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    {{-- ── Hero ribbon ──────────────────────────────────────────────────── --}}
    <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg,#f59e0b,#d97706);">
        <div class="card-body d-flex align-items-center gap-3 px-4 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:46px;height:46px;background:rgba(255,255,255,.2);">
                <i class="bx bx-key text-white" style="font-size:1.35rem;"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="fw-bold text-white">
                    Editing: <code class="ms-1 text-white fw-bold"
                        style="background:rgba(255,255,255,.2);padding:2px 10px;border-radius:6px;font-size:.9rem;"
                        id="heroPreview">{{ $permission->name }}</code>
                </div>
                <div class="text-white small opacity-75 mt-1">
                    Permission ID #{{ $permission->id }} &nbsp;&middot;&nbsp;
                    {{ $permission->roles()->count() }} {{ __('messages.roles_count') }}
                </div>
            </div>
            <span class="badge text-warning fw-semibold"
                style="background:rgba(255,255,255,.18);font-size:.78rem;padding:.45rem .9rem;">
                <i class="bx bx-edit me-1"></i>{{ __('messages.edit') }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('permissions.update', $permission->id) }}" id="editPermForm">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- ══════════════════════════════════════════════════════════════
                 LEFT COLUMN
            ══════════════════════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- ── Permission Details card ──────────────────────────── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-key me-2 text-warning"></i>
                            {{ __('messages.permission_details_card') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Rename warning --}}
                        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3 py-2 px-3"
                            style="border-radius:10px;">
                            <i class="bx bx-error-circle fs-5 flex-shrink-0 mt-1"></i>
                            <div class="small fw-semibold">
                                {{ __('messages.permission_rename_warning') }}
                            </div>
                        </div>

                        {{-- Live preview --}}
                        <div class="alert alert-primary d-flex align-items-center gap-2 mb-4 py-2 px-3"
                            style="border-radius:10px;">
                            <i class="bx bx-info-circle fs-5 flex-shrink-0"></i>
                            <div class="small">
                                {{ __('messages.permission_name_hint') }}:
                                <code id="previewName" class="fw-bold ms-1 px-2 py-1 rounded" style="font-size:.85rem;">
                                    {{ $permission->name }}
                                </code>
                            </div>
                        </div>

                        {{-- Module + Action inputs --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">
                                    {{ __('messages.module_label') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="moduleInput" name="module" list="moduleList"
                                    class="form-control @error('module') is-invalid @enderror"
                                    placeholder="{{ __('messages.ph_permission_module_eg') }}"
                                    value="{{ old('module', $currentModule) }}" required autocomplete="off">
                                <datalist id="moduleList">
                                    @foreach ($allModules as $mod)
                                        <option value="{{ $mod }}">
                                    @endforeach
                                </datalist>
                                <div class="form-text text-muted">
                                    {{ __('messages.module_hint') }}
                                </div>
                                @error('module')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">
                                    {{ __('messages.action_label') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="actionInput" name="action" list="actionList"
                                    class="form-control @error('action') is-invalid @enderror"
                                    placeholder="{{ __('messages.ph_permission_action_eg') }}"
                                    value="{{ old('action', $currentAction) }}" required autocomplete="off">
                                <datalist id="actionList">
                                    <option value="view">
                                    <option value="create">
                                    <option value="update">
                                    <option value="delete">
                                    <option value="own">
                                </datalist>
                                <div class="form-text text-muted">
                                    {{ __('messages.action_hint') }}
                                </div>
                                @error('action')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Assigned Roles card ───────────────────────────────── --}}
                <div class="card shadow-sm">
                    <div
                        class="card-header py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-shield me-2 text-primary"></i>{{ __('messages.menu_roles') }}
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-label-warning" id="attachedBadge">
                                {{ count($assignedRoleIds) }} {{ __('messages.roles_count') }}
                            </span>
                            <button type="button" class="btn btn-xs btn-outline-primary" id="btnSelectAll">
                                <i class="bx bx-check-square me-1"></i>All
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" id="btnClearAll">
                                <i class="bx bx-square me-1"></i>None
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">
                            {{ __('messages.perm_instant_hint') }}
                        </p>

                        <div class="row g-3">
                            @foreach ($allRoles as $role)
                                @php
                                    $isSuper = $role->name === 'super_admin';
                                    $isMgr = in_array($role->name, ['manager', 'admin']);
                                    $checked = in_array($role->id, old('roles', $assignedRoleIds));
                                    $badgeCls = $isSuper
                                        ? 'bg-label-warning'
                                        : ($isMgr
                                            ? 'bg-label-primary'
                                            : 'bg-label-secondary');
                                    $icon = $isSuper ? 'bx-crown' : ($isMgr ? 'bx-shield' : 'bx-user');
                                    $label = $isSuper ? 'Super Admin' : ucwords(str_replace('_', ' ', $role->name));
                                @endphp
                                <div class="col-md-4 col-sm-6">
                                    <label
                                        class="role-card d-flex align-items-center gap-3 p-3 border rounded-3 cursor-pointer w-100
                                                  {{ $checked ? 'role-card--checked' : '' }}"
                                        for="role_{{ $role->id }}" style="transition:all .2s;cursor:pointer;">
                                        <input class="form-check-input flex-shrink-0 role-chk" type="checkbox"
                                            name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                            @checked($checked)>
                                        <span
                                            class="badge {{ $badgeCls }} d-inline-flex align-items-center gap-1 px-2 py-1"
                                            style="font-size:.8rem;">
                                            <i class="bx {{ $icon }}"></i>
                                            {{ $label }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══════════════════════════════════════════════════════════════
                 RIGHT COLUMN (sticky sidebar)
            ══════════════════════════════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- ── Publish card ──────────────────────────────────────── --}}
                <div class="card shadow-sm mb-4 sticky-top" style="top:90px;">
                    <div class="card-header py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i>
                            {{ __('messages.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">
                                <i class="bx bx-save me-1" id="saveBtnIcon"></i>
                                <span id="saveBtnText">
                                    {{ __('messages.update') }} {{ __('messages.permission') }}
                                </span>
                            </button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ── Information card ──────────────────────────────────── --}}
                <div class="card shadow-sm">
                    <div class="card-header py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle me-2 text-secondary"></i>
                            {{ __('messages.information') }}
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                                <span class="text-muted fw-semibold">ID</span>
                                <span class="fw-bold text-primary">#{{ $permission->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                                <span class="text-muted fw-semibold">{{ __('messages.roles_count') }}</span>
                                <span class="badge bg-label-warning" id="infoRoleCount">
                                    {{ $permission->roles()->count() }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                                <span class="text-muted fw-semibold">{{ __('messages.th_created') }}</span>
                                <span>{{ $permission->created_at->format('d M Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">{{ __('messages.updated') }}</span>
                                <span>{{ $permission->updated_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>{{-- /col-lg-4 --}}

        </div>{{-- /row --}}
    </form>

    <style>
        /* ── Role cards — fully theme-aware ─────────────────────────────── */
        .role-card {
            background: transparent;
            border-color: var(--bs-border-color) !important;
            transition: border-color .18s, background .18s;
        }

        .role-card:hover {
            border-color: #696cff !important;
            background: rgba(105, 108, 255, .07) !important;
        }

        .role-card--checked {
            border-color: #696cff !important;
            background: rgba(105, 108, 255, .12) !important;
        }

        /* card-header: neutralise bg-white in dark mode */
        [data-bs-theme="dark"] .card-header.bg-white {
            background-color: transparent !important;
        }

        /* alert overrides for dark mode */
        [data-bs-theme="dark"] .alert-warning {
            background-color: rgba(255, 171, 0, .12) !important;
            border-color: rgba(255, 171, 0, .30) !important;
            color: #ffc107 !important;
        }

        [data-bs-theme="dark"] .alert-primary {
            background-color: rgba(105, 108, 255, .12) !important;
            border-color: rgba(105, 108, 255, .30) !important;
            color: #a5a8ff !important;
        }

        [data-bs-theme="dark"] .alert-primary code {
            background: rgba(105, 108, 255, .22) !important;
            color: #b8bbff !important;
        }

        [data-bs-theme="dark"] #previewName {
            background: rgba(105, 108, 255, .22) !important;
            color: #b8bbff !important;
        }

        /* Tight btn-xs */
        .btn-xs {
            padding: .2rem .55rem;
            font-size: .75rem;
            border-radius: 6px;
        }

        /* Preview code tag — theme aware */
        #previewName,
        #heroPreview {
            background: rgba(105, 108, 255, .12);
            color: #696cff;
            font-size: .85rem;
        }

        [data-bs-theme="dark"] #previewName,
        [data-bs-theme="dark"] #heroPreview {
            background: rgba(105, 108, 255, .25) !important;
            color: #b8bbff !important;
        }
    </style>

@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            /* ── Live preview ───────────────────────────────────────────── */
            const moduleInput = document.getElementById('moduleInput');
            const actionInput = document.getElementById('actionInput');
            const previewName = document.getElementById('previewName');
            const heroPreview = document.getElementById('heroPreview');

            function sanitize(v) {
                return v.trim().toLowerCase().replace(/[^a-z0-9_]/g, '');
            }

            function updatePreview() {
                const m = sanitize(moduleInput.value) || 'module';
                const a = sanitize(actionInput.value) || 'action';
                const nm = m + '.' + a;
                previewName.textContent = nm;
                heroPreview.textContent = nm;
            }

            moduleInput.addEventListener('input', updatePreview);
            actionInput.addEventListener('input', updatePreview);

            /* ── Role card checked state ────────────────────────────────── */
            function refreshCards() {
                document.querySelectorAll('.role-chk').forEach(chk => {
                    chk.closest('.role-card')
                        .classList.toggle('role-card--checked', chk.checked);
                });
                // update counts
                const checked = document.querySelectorAll('.role-chk:checked').length;
                document.getElementById('attachedBadge').textContent = checked + ' {{ __('messages.roles_count') }}';
                document.getElementById('infoRoleCount').textContent = checked;
            }

            document.querySelectorAll('.role-chk').forEach(chk => {
                chk.addEventListener('change', refreshCards);
            });

            /* ── Select All / None ──────────────────────────────────────── */
            document.getElementById('btnSelectAll').addEventListener('click', function() {
                document.querySelectorAll('.role-chk').forEach(c => c.checked = true);
                refreshCards();
            });
            document.getElementById('btnClearAll').addEventListener('click', function() {
                document.querySelectorAll('.role-chk').forEach(c => c.checked = false);
                refreshCards();
            });

            /* ── Loading state on save ──────────────────────────────────── */
            document.getElementById('editPermForm').addEventListener('submit', function() {
                const btn = document.getElementById('saveBtn');
                const icon = document.getElementById('saveBtnIcon');
                const text = document.getElementById('saveBtnText');
                btn.disabled = true;
                btn.style.opacity = '.75';
                icon.className = 'bx bx-loader-alt bx-spin me-1';
                text.textContent = '{{ __('messages.saving_lbl') }}';
            });

            /* ── Initial sync ───────────────────────────────────────────── */
            refreshCards();
        })();
    </script>
@endpush
