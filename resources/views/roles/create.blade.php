@extends('layouts.admin')
@section('title', __('messages.add_role_title'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_role_title') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.menu_roles') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="row g-4">

            {{-- Left: Role Details + Permissions --}}
            <div class="col-lg-8">

                {{-- Role Name Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-shield me-2 text-primary"></i>{{ __('messages.role_details_card') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.display_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="displayNameInput" name="display_name"
                                    class="form-control @error('display_name') is-invalid @enderror"
                                    placeholder="{{ __('messages.ph_role_name') }}" value="{{ old('display_name') }}"
                                    required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.system_name') }} <span class="text-danger">*</span>
                                    <small class="text-muted fw-normal">({{ __('messages.auto_generated') }})</small>
                                </label>
                                <input type="text" id="nameInput" name="name"
                                    class="form-control bg-light @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" readonly>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Permissions Matrix Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-key me-2 text-warning"></i>{{ __('messages.permissions') }}
                        </h6>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="selectAllPermissions" role="switch">
                            <label class="form-check-label fw-semibold small" for="selectAllPermissions">
                                {{ __('messages.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.module_label') }}</th>
                                        <th class="text-center" style="width:70px;">{{ __('messages.select_all') }}</th>
                                        <th class="text-center" style="width:70px;">{{ __('messages.view') }}</th>
                                        <th class="text-center" style="width:70px;">{{ __('messages.own') }}</th>
                                        <th class="text-center" style="width:70px;">{{ __('messages.create') }}</th>
                                        <th class="text-center" style="width:70px;">{{ __('messages.update') }}</th>
                                        <th class="text-center" style="width:70px;">{{ __('messages.delete') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($crudPermissions as $module => $actions)
                                        @php $moduleSlug = \Illuminate\Support\Str::slug($module); @endphp
                                        <tr>
                                            <td class="fw-semibold ps-3">{{ $module }}</td>
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
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox" name="permissions[]"
                                                                value="{{ $actions[$action]->name }}"
                                                                data-module="{{ $moduleSlug }}"
                                                                data-action="{{ $action }}">
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
                </div>

            </div>

            {{-- Right: Publish --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('messages.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            <i class="bx bx-info-circle me-1"></i>
                            {{ __('messages.role_publish_hint') ?? 'Review permissions carefully before saving the role.' }}
                        </p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save') }} {{ __('messages.role') }}
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
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
    @include('roles.scripts')
@endpush
