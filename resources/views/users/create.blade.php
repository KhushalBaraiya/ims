@extends('layouts.admin')
@section('title', __('messages.add_user_title'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.add_user_title') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('messages.menu_users') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form id="userForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data"
        data-validate="true">
        @csrf
        <div class="row g-4">

            {{-- Left: User Details --}}
            <div class="col-lg-8 col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-user me-2 text-primary"></i>{{ __('messages.user_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        {{-- Profile Photo --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('messages.profile_photo') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <div id="photoPreviewWrap">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary"
                                        style="width:64px;height:64px;flex-shrink:0;">
                                        <i class="bx bx-user fs-4 text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <input type="file" name="profile_photo" id="profilePhotoInput"
                                        class="form-control @error('profile_photo') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/gif">
                                    <div class="form-text">{{ __('messages.image_hint') }}</div>
                                    @error('profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.name_label') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="{{ __('messages.ph_user_name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.email_address') }} <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="{{ __('messages.ph_user_email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.phone_label') }}</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    placeholder="{{ __('messages.ph_phone_india') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.role') }} <span
                                        class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.select_role') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role') === $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.password') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ __('messages.ph_password_dots') }}" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('password', this)">
                                        <i class="bx bx-hide"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('messages.confirm_password') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="{{ __('messages.ph_password_dots') }}" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('password_confirmation', this)">
                                        <i class="bx bx-hide"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Publish --}}
            <div class="col-lg-4 col-md-4">
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
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
        // Status toggle
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                lbl.textContent = this.checked ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}';
                lbl.className = 'fw-semibold ' + (this.checked ? 'text-success' : 'text-danger');
            });
        }

        // Photo preview
        document.getElementById('profilePhotoInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('photoPreviewWrap').innerHTML =
                    `<img src="${e.target.result}" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;border:2px solid #e0e0e0;flex-shrink:0;">`;
            };
            reader.readAsDataURL(file);
        });
    </script>
@endpush
