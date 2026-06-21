@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($user) ? __('admin.edit_user') : __('admin.add_user') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('admin.users') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($user) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bx bx-arrow-back me-1"></i> {{ __('admin.back') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="userForm" action="{{ isset($user) ? route('admin.user.update', $user->id) : route('admin.user.store') }}"
        method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @if (isset($user))
            @method('PUT')
        @endif
        {{-- Fake fields to trick browser autofill --}}
        <input type="text" name="fake_user" class="d-none" tabindex="-1" aria-hidden="true">
        <input type="email" name="fake_email" class="d-none" tabindex="-1" aria-hidden="true">
        <input type="password" name="fake_pass" class="d-none" tabindex="-1" aria-hidden="true">

        <div class="row g-4">

            {{-- ===== LEFT ===== --}}
            <div class="col-lg-8">

                {{-- Personal Information --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-user me-2 text-primary"></i>{{ __('admin.personal_info') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" autocomplete="name"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_user_name') }}"
                                    value="{{ old('name', $user->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.email') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" autocomplete="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_email') }}"
                                    value="{{ old('email', $user->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.phone') }} <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="phone" autocomplete="tel"
                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_phone') }}"
                                    value="{{ old('phone', $user->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.gender') }} <span class="text-danger">*</span>
                                </label>
                                <div class="select2-lg">
                                    <select name="gender"
                                        class="select2-gender form-select form-select-lg w-100 @error('gender') is-invalid @enderror">
                                        <option value="">{{ __('admin.select_gender') }}</option>
                                        <option value="Male"
                                            {{ old('gender', $user->gender ?? '') == 'Male' ? 'selected' : '' }}>
                                            {{ __('admin.gender_male') }}</option>
                                        <option value="Female"
                                            {{ old('gender', $user->gender ?? '') == 'Female' ? 'selected' : '' }}>
                                            {{ __('admin.gender_female') }}</option>
                                        <option value="Other"
                                            {{ old('gender', $user->gender ?? '') == 'Other' ? 'selected' : '' }}>
                                            {{ __('admin.gender_other') }}</option>
                                    </select>
                                </div>
                                @error('gender')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.dob') }}</label>
                                <input type="date" name="dob"
                                    class="form-control form-control-lg @error('dob') is-invalid @enderror"
                                    value="{{ old('dob', isset($user->dob) ? $user->dob->format('Y-m-d') : '') }}">
                                @error('dob')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- OTP --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.otp') }}</label>
                                <input type="text" name="otp"
                                    class="form-control form-control-lg @error('otp') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_otp') }}" maxlength="6"
                                    value="{{ old('otp', $user->otp ?? '') }}">
                                @error('otp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.address') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="address" rows="3" class="form-control form-control-lg @error('address') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_user_address') }}">{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Security --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-lock me-2 text-warning"></i>{{ __('admin.security') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold">
                                {{ __('admin.password') }}
                                @if (!isset($user))
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="password" id="user_password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="{{ isset($user) ? __('admin.leave_blank_pwd') : __('admin.min_8_chars') }}">
                                <button type="button" class="btn btn-outline-secondary px-3"
                                    onclick="togglePwd('user_password', this)">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if (isset($user))
                                <div class="form-text">{{ __('admin.leave_blank_pwd') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Social Accounts --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-link me-2 text-info"></i>{{ __('admin.social_accounts') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.google_id') }}</label>
                                <input type="text" name="google_id"
                                    class="form-control form-control-lg @error('google_id') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_google_id') }}"
                                    value="{{ old('google_id', $user->google_id ?? '') }}">
                                @error('google_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.facebook_id') }}</label>
                                <input type="text" name="facebook_id"
                                    class="form-control form-control-lg @error('facebook_id') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_facebook_id') }}"
                                    value="{{ old('facebook_id', $user->facebook_id ?? '') }}">
                                @error('facebook_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== RIGHT ===== --}}
            <div class="col-lg-4">

                {{-- Publish --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Role --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('admin.role') }} <span class="text-danger">*</span>
                            </label>
                            <div class="select2-lg">
                                <select name="role_id"
                                    class="select2-role form-select form-select-lg w-100 @error('role_id') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_role') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $user->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $user->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $user->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($user) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-lg"><i
                                    class="bx bx-x me-1"></i>{{ __('admin.cancel') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Profile Image --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-image me-2 text-info"></i>{{ __('admin.profile_image') }}
                            @if (!isset($user))
                                <span class="text-danger">*</span>
                            @endif
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <input type="file" name="image" id="userImgInput"
                            class="form-control form-control-lg @error('image') is-invalid @enderror"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        <div class="form-text">{{ __('admin.jpg_png_webp') }}</div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        {{-- Profile Image Preview --}}
                        <div class="mt-3" id="userImgWrapper"
                            style="display:{{ isset($user) && $user->image ? 'block' : 'none' }}">
                            <div class="position-relative d-inline-block">
                                <img id="userImgPreview"
                                    src="{{ isset($user) && $user->image ? asset($user->image) : '' }}"
                                    class="rounded-circle border img-preview"
                                    onerror="this.closest('#userImgWrapper').style.display='none'">
                                <button type="button" id="userImgRemove"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center img-remove-btn"
                                    title="{{ __('admin.remove') }}">
                                    <i class="bx bx-x icon-sm"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for role
            $('.select2-role').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_role') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-gender').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_gender') }}',
                minimumResultsForSearch: Infinity,
                allowClear: true,
                width: '100%'
            });

            // Custom rule: max file size
            $.validator.addMethod('filesize', function(value, element, param) {
                return element.files.length === 0 || element.files[0].size <= param;
            }, 'File size must not exceed 2MB.');

            const isEdit = {{ isset($user) ? 'true' : 'false' }};

            $('#userForm').validate({
                rules: {
                    role_id: {
                        required: true,
                    },
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255,
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 7,
                        maxlength: 15,
                    },
                    gender: {
                        required: true,
                    },
                    dob: {
                        date: true,
                    },
                    otp: {
                        required: true,
                        digits: true,
                        minlength: 6,
                        maxlength: 6,
                    },
                    address: {
                        required: true,
                        minlength: 5,
                        maxlength: 500,
                    },
                    password: {
                        required: !isEdit,
                        minlength: 8,
                        maxlength: 64,
                    },
                    image: {
                        required: !isEdit,
                        filesize: 2097152,
                        accept: 'image/jpg,image/jpeg,image/png,image/webp',
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    role_id: {
                        required: '{{ __('admin.val_role_required') }}',
                    },
                    name: {
                        required: '{{ __('admin.val_name_required') }}',
                        minlength: '{{ __('admin.val_min_2') }}',
                        maxlength: '{{ __('admin.val_max_255') }}',
                    },
                    email: {
                        required: '{{ __('admin.val_email_required') }}',
                        email: '{{ __('admin.val_email_valid') }}',
                        maxlength: '{{ __('admin.val_max_255') }}',
                    },
                    phone: {
                        required: '{{ __('admin.val_phone_required') }}',
                        digits: '{{ __('admin.val_mobile_digits') }}',
                        minlength: '{{ __('admin.val_phone_min') }}',
                        maxlength: '{{ __('admin.val_mobile_max') }}',
                    },
                    gender: {
                        required: '{{ __('admin.val_gender_required') }}',
                    },
                    dob: {
                        date: '{{ __('admin.val_dob_valid') }}',
                    },
                    otp: {
                        required: '{{ __('admin.val_otp_required') }}',
                        digits: '{{ __('admin.val_whole_number') }}',
                        minlength: '{{ __('admin.val_otp_digits') }}',
                        maxlength: '{{ __('admin.val_otp_digits') }}',
                    },
                    address: {
                        required: '{{ __('admin.val_address_required') }}',
                        minlength: '{{ __('admin.val_min_5') }}',
                        maxlength: '{{ __('admin.val_max_500') }}',
                    },
                    password: {
                        required: '{{ __('admin.val_password_required') }}',
                        minlength: '{{ __('admin.min_8_chars') }}',
                        maxlength: '{{ __('admin.val_max_64') }}',
                    },
                    image: {
                        required: '{{ __('admin.val_profile_image_required') }}',
                        filesize: '{{ __('admin.val_image_size') }}',
                        accept: '{{ __('admin.val_image_accept') }}',
                    },
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
                onfocusout: function(element) {
                    if (element.name !== 'image') {
                        this.element(element);
                    }
                },
                onkeyup: false,
                errorPlacement: function(error, element) {
                    if (element.closest('.select2-lg').length) {
                        element.closest('.select2-lg').after(error);
                    } else if (element.closest('.input-group').length) {
                        element.closest('.input-group').after(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(el) {
                    $(el).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(el) {
                    $(el).removeClass('is-invalid');
                    if ($(el).val()) $(el).addClass('is-valid');
                    else $(el).removeClass('is-valid');
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]')
                        .prop('disabled', true)
                        .html(
                            '<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.processing') }}'
                        );
                    form.submit();
                },
            });
        });

        // ── Image preview ──────────────────────────────────────────────
        document.getElementById('userImgInput').addEventListener('change', function() {
            const file = this.files[0];
            const wrapper = document.getElementById('userImgWrapper');
            const preview = document.getElementById('userImgPreview');
            if (!file) return;
            if (file.size > 2097152) {
                wrapper.style.display = 'none';
                return;
            }
            const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                wrapper.style.display = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                wrapper.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        document.getElementById('userImgRemove').addEventListener('click', function() {
            document.getElementById('userImgInput').value = '';
            document.getElementById('userImgPreview').src = '';
            document.getElementById('userImgWrapper').style.display = 'none';
        });

        // ── Password show/hide ─────────────────────────────────────────
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bx bx-show';
            } else {
                input.type = 'password';
                icon.className = 'bx bx-hide';
            }
        }

        // ── Status toggle label ────────────────────────────────────────
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                if (this.checked) {
                    lbl.textContent = '{{ __('admin.status_active') }}';
                    lbl.className = 'fw-semibold fs-6 text-success';
                } else {
                    lbl.textContent = '{{ __('admin.status_inactive') }}';
                    lbl.className = 'fw-semibold fs-6 text-danger';
                }
            });
        }

        // ── Server-side error toast ────────────────────────────────────
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: '{{ __('admin.swal_fix_errors') }}',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    backdrop: false,
                    customClass: {
                        container: 'swal-top-toast'
                    },
                });
            });
        @endif
    </script>
@endpush
