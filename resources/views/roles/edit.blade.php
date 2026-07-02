@extends('layouts.admin')
@section('title', __('messages.edit_role') . ' — ' . $role->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.edit_role') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('messages.menu_roles') }}</a></li>
                    <li class="breadcrumb-item active">{{ $role->name }}</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('roles.index') }}">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-4">

            {{-- Left: Role Details + Permissions --}}
            <div class="col-lg-8">

                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-shield text-primary me-2"></i>{{ __('messages.role_details_card') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.display_name') }} <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('display_name') is-invalid @enderror"
                                    id="displayNameInput" name="display_name" required type="text"
                                    value="{{ old('display_name', $role->name) }}">
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('messages.system_name') }} <span class="text-danger">*</span>
                                </label>
                                <input {{ $role->name === 'super_admin' ? 'readonly' : '' }}
                                    class="form-control @error('name') is-invalid @enderror" id="nameInput" name="name"
                                    type="text" value="{{ old('name', $role->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-key text-warning me-2"></i>{{ __('messages.permissions') }}
                            <span class="badge bg-label-warning ms-1">{{ count($selectedPermissions) }}</span>
                        </h6>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" id="selectAllPermissions" role="switch" type="checkbox">
                            <label class="form-check-label fw-semibold small" for="selectAllPermissions">
                                {{ __('messages.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-bordered mb-0 table align-middle">
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
                                            <td class="fw-semibold ps-3">{{ $module }}</td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center mb-0">
                                                    <input class="form-check-input module-checkbox"
                                                        data-module="{{ $moduleSlug }}" type="checkbox">
                                                </div>
                                            </td>
                                            @foreach (['view', 'own', 'create', 'update', 'delete'] as $action)
                                                <td class="text-center">
                                                    @if (isset($actions[$action]))
                                                        <div class="form-check d-flex justify-content-center mb-0">
                                                            <input @checked(in_array($actions[$action]->name, $selectedPermissions))
                                                                class="form-check-input permission-checkbox"
                                                                data-action="{{ $action }}"
                                                                data-module="{{ $moduleSlug }}" name="permissions[]"
                                                                type="checkbox" value="{{ $actions[$action]->name }}">
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

            {{-- Right: Publish + Info --}}
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-send text-primary me-2"></i>{{ __('messages.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1"></i> {{ __('messages.update') }} {{ __('messages.role') }}
                            </button>
                            <a class="btn btn-outline-secondary" href="{{ route('roles.index') }}">
                                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-info-circle text-secondary me-2"></i>{{ __('messages.information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">ID</span>
                                <span class="fw-bold">#{{ $role->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.permissions') }}</span>
                                <span class="badge bg-label-warning">{{ count($selectedPermissions) }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.th_created') }}</span>
                                <span>{{ $role->created_at->format('d M Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted fw-semibold">{{ __('messages.updated') }}</span>
                                <span>{{ $role->updated_at->format('d M Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
    @include('roles.scripts')
@endpush
