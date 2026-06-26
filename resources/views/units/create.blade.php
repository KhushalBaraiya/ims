@extends('layouts.admin')
@section('title', __('messages.add_unit'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_unit') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">{{ __('messages.units') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form id="unitForm" method="POST" action="{{ route('units.store') }}" data-validate="true">
        @csrf
        <div class="row g-4">

            {{-- Left: Unit Details --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-ruler me-2 text-primary"></i>{{ __('messages.unit_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('messages.unit_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="{{ __('messages.ph_unit_name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">
                                {{ __('messages.short_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="short_name"
                                class="form-control @error('short_name') is-invalid @enderror"
                                value="{{ old('short_name') }}" placeholder="{{ __('messages.ph_short_name') }}" required>
                            <div class="form-text">{{ __('messages.short_name_hint') }}</div>
                            @error('short_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block">{{ __('messages.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="statusToggle"
                                        name="status" value="active"
                                        {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold {{ old('status', 'active') === 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', 'active') === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save') }}
                            </button>
                            <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
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
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                if (this.checked) {
                    lbl.textContent = '{{ __('messages.active') }}';
                    lbl.className = 'fw-semibold text-success';
                } else {
                    lbl.textContent = '{{ __('messages.inactive') }}';
                    lbl.className = 'fw-semibold text-danger';
                }
            });
        }
    </script>
@endpush
