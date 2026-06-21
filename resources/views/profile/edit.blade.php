@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.my_profile_title') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin.profile_breadcrumb') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Success Toast --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ===== LEFT: Avatar Card ===== --}}
        <div class="col-lg-4">

            {{-- Avatar --}}
            <div class="card shadow-sm mb-4 text-center">
                <div class="card-body p-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if ($user->image)
                            <img id="avatarPreview" src="{{ asset($user->image) }}" class="rounded-circle border shadow"
                                style="width:120px;height:120px;object-fit:cover;"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=696cff&color=fff'">
                        @else
                            <img id="avatarPreview"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=696cff&color=fff"
                                class="rounded-circle border shadow" style="width:120px;height:120px;object-fit:cover;">
                        @endif

                        {{-- Camera icon overlay --}}
                        <label for="avatarInput"
                            class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center"
                            style="width:32px;height:32px;cursor:pointer;" title="{{ __('admin.change_photo') }}">
                            <i class="bx bx-camera" style="font-size:16px;"></i>
                        </label>
                    </div>

                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ($user->status ?? 'active') === 'active' ? __('admin.active') : __('admin.inactive') }}
                    </span>

                    @if ($user->role)
                        <div class="mt-2">
                            <span class="badge bg-label-primary">{{ $user->role->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Change Password --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-lock me-2 text-warning"></i>{{ __('admin.change_password') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form id="passwordForm" action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.current_password') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="{{ __('admin.current_password_ph') }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('current_password', this)">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.new_password') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="new_password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="{{ __('admin.new_password_ph2') }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('new_password', this)">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('admin.confirm_password') }} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="confirm_password" name="password_confirmation"
                                    class="form-control" placeholder="{{ __('admin.repeat_password_ph') }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('confirm_password', this)">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bx bx-lock-open me-1"></i> {{ __('admin.update_password_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT: Profile Info ===== --}}
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5">
                        <i class="bx bx-user me-2 text-primary"></i>{{ __('admin.personal_information') }}
                    </h6>
                </div>
                <div class="card-body p-4">

                    <form id="profileForm" action="{{ route('profile.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Hidden file input for avatar --}}
                        <input type="file" id="avatarInput" name="image"
                            accept="image/jpg,image/jpeg,image/png,image/webp" style="display:none;">

                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.full_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" placeholder="{{ __('admin.full_name_ph') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.email_address') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" placeholder="{{ __('admin.email_address_ph') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.phone_label') }}</label>
                                <input type="tel" name="phone"
                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" placeholder="{{ __('admin.phone_ph') }}">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.gender_label') }}</label>
                                <select name="gender"
                                    class="form-select form-select-lg @error('gender') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_gender') }}</option>
                                    <option value="Male"
                                        {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>{{ __('admin.gender_male') }}</option>
                                    <option value="Female"
                                        {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>{{ __('admin.gender_female') }}</option>
                                    <option value="Other"
                                        {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>{{ __('admin.gender_other') }}</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.dob_label') }}</label>
                                <input type="date" name="dob"
                                    class="form-control form-control-lg @error('dob') is-invalid @enderror"
                                    value="{{ old('dob', isset($user->dob) ? $user->dob->format('Y-m-d') : '') }}">
                                @error('dob')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.address_label') }}</label>
                                <textarea name="address" rows="3" class="form-control form-control-lg @error('address') is-invalid @enderror"
                                    placeholder="{{ __('admin.address_ph') }}">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Image preview (shown after camera click) --}}
                        <div id="imgChangeInfo" class="mt-3" style="display:none;">
                            <div class="alert alert-info py-2 mb-0 d-flex align-items-center gap-2">
                                <i class="bx bx-info-circle"></i>
                                <span>{{ __('admin.photo_selected') }}</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-auto"
                                    id="cancelImgChange">
                                    {{ __('admin.cancel') }}
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bx bx-save me-1"></i> {{ __('admin.save_changes') }}
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-lg">{{ __('admin.cancel') }}</a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card shadow-sm mt-4 border-danger">
                <div class="card-header bg-white py-3 border-bottom border-danger">
                    <h6 class="mb-0 fw-semibold fs-5 text-danger">
                        <i class="bx bx-trash me-2"></i>{{ __('admin.danger_zone') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">{{ __('admin.danger_zone_text') }}</p>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteAccountModal">
                        <i class="bx bx-user-x me-1"></i> {{ __('admin.delete_my_account') }}
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger"><i class="bx bx-warning me-2"></i>{{ __('admin.delete_account_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>{{ __('admin.delete_account_confirm') }}</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.password') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="delete_password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="{{ __('admin.current_password_ph') }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('delete_password', this)">
                                    <i class="bx bx-hide"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-trash me-1"></i> {{ __('admin.yes_delete_account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ── Avatar live preview ────────────────────────────────────────
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const imgChangeInfo = document.getElementById('imgChangeInfo');
        const cancelImgBtn = document.getElementById('cancelImgChange');

        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            if (file.size > 2097152) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin.file_too_large') }}',
                    text: '{{ __('admin.file_too_large_text') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
                this.value = '';
                return;
            }
            const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin.invalid_file') }}',
                    text: '{{ __('admin.invalid_file_text') }}',
                    timer: 2500,
                    showConfirmButton: false
                });
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                avatarPreview.src = e.target.result;
                imgChangeInfo.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        cancelImgBtn.addEventListener('click', function() {
            avatarInput.value = '';
            imgChangeInfo.style.display = 'none';
            // Restore original avatar
            @if ($user->image)
                avatarPreview.src = '{{ asset($user->image) }}';
            @else
                avatarPreview.src =
                    'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=696cff&color=fff';
            @endif
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

        // ── jQuery Validate: Profile form ──────────────────────────────
        $(document).ready(function() {
            $.validator.addMethod('filesize', function(value, element, param) {
                return element.files.length === 0 || element.files[0].size <= param;
            }, '{{ __('admin.val_image_size') }}');

            $('#profileForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    phone: {
                        digits: true,
                        minlength: 7,
                        maxlength: 15
                    },
                    image: {
                        filesize: 2097152,
                        accept: 'image/jpg,image/jpeg,image/png,image/webp'
                    },
                },
                messages: {
                    name: {
                        required: '{{ __('admin.val_name_required') }}',
                        minlength: '{{ __('admin.val_min_2') }}'
                    },
                    email: {
                        required: '{{ __('admin.val_email_required') }}',
                        email: '{{ __('admin.val_enter_valid_email') }}'
                    },
                    phone: {
                        digits: '{{ __('admin.val_digits_only') }}',
                        minlength: '{{ __('admin.val_phone_min_7') }}',
                        maxlength: '{{ __('admin.val_phone_max_15') }}'
                    },
                    image: {
                        accept: '{{ __('admin.val_image_accept') }}',
                        filesize: '{{ __('admin.val_image_size') }}'
                    },
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
                highlight: el => $(el).addClass('is-invalid'),
                unhighlight: el => $(el).removeClass('is-invalid'),
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.saving_text') }}');
                    form.submit();
                },
            });

            // ── jQuery Validate: Password form ─────────────────────────
            $('#passwordForm').validate({
                rules: {
                    current_password: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 64
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '#new_password'
                    },
                },
                messages: {
                    current_password: {
                        required: '{{ __('admin.val_current_password_required') }}'
                    },
                    password: {
                        required: '{{ __('admin.val_new_password_required_profile') }}',
                        minlength: '{{ __('admin.val_password_min_8') }}'
                    },
                    password_confirmation: {
                        required: '{{ __('admin.val_confirm_password_required') }}',
                        equalTo: '{{ __('admin.val_passwords_match') }}'
                    },
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
                highlight: el => $(el).addClass('is-invalid'),
                unhighlight: el => $(el).removeClass('is-invalid'),
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.updating_text') }}');
                    form.submit();
                },
            });
        });

        // ── Success toast ──────────────────────────────────────────────
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    backdrop: false,
                });
            });
        @endif
    </script>
@endpush
