@csrf

@php
    $displayName = old('display_name', $role->name ?? '');
    $name = old('name', $role->name ?? '');
@endphp

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="displayNameInput">
            {{ __('messages.display_name') }} <span class="text-danger">*</span>
        </label>
        <input type="text" id="displayNameInput" name="display_name"
            class="form-control @error('display_name') is-invalid @enderror" placeholder="e.g. HR Manager"
            value="{{ $displayName }}" required>
        @error('display_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="nameInput">
            {{ __('messages.system_name') }} <span class="text-danger">*</span>
            <small class="text-muted fw-normal">({{ __('messages.auto_generated') }})</small>
        </label>
        <input type="text" id="nameInput" name="name"
            class="form-control bg-light @error('name') is-invalid @enderror" value="{{ $name }}" readonly>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Permissions Matrix --}}
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h6 class="fw-bold mb-0">{{ __('messages.permissions') }}</h6>
            <small class="text-muted">{{ __('messages.permissions') }}</small>
        </div>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" id="selectAllPermissions" role="switch">
            <label class="form-check-label fw-semibold"
                for="selectAllPermissions">{{ __('messages.select_all') }}</label>
        </div>
    </div>

    <div class="table-responsive border rounded">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('messages.module_label') }}</th>
                    <th class="text-center" style="width:70px;">{{ __('messages.select_all') }}</th>
                    <th class="text-center" style="width:70px;">{{ __('messages.view') }}</th>
                    <th class="text-center" style="width:70px;">Own</th>
                    <th class="text-center" style="width:70px;">{{ __('messages.create') }}</th>
                    <th class="text-center" style="width:70px;">{{ __('messages.update') }}</th>
                    <th class="text-center" style="width:70px;">{{ __('messages.delete') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($crudPermissions as $module => $actions)
                    @php $moduleSlug = \Illuminate\Support\Str::slug($module); @endphp
                    <tr>
                        <td class="fw-semibold">{{ $module }}</td>
                        <td class="text-center">
                            <div class="form-check d-flex justify-content-center mb-0">
                                <input class="form-check-input module-checkbox" type="checkbox"
                                    data-module="{{ $moduleSlug }}">
                            </div>
                        </td>
                        @foreach (['view', 'own', 'create', 'update', 'delete'] as $action)
                            <td class="text-center">
                                @if (isset($actions[$action]))
                                    <div class="form-check d-flex justify-content-center mb-0">
                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                            name="permissions[]" value="{{ $actions[$action]->name }}"
                                            data-module="{{ $moduleSlug }}" data-action="{{ $action }}"
                                            @checked(in_array($actions[$action]->name, $selectedPermissions ?? []))>
                                    </div>
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

<div class="d-flex justify-content-end gap-2 pt-3 border-top">
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i>{{ isset($role) ? __('messages.update') : __('messages.save') }}
        {{ __('messages.role') }}
    </button>
</div>
