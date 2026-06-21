@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($address) ? __('admin.edit_user_address') : __('admin.add_user_address') }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.user-address.index') }}">{{ __('admin.user_address_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($address) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.user-address.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="addressForm"
        action="{{ isset($address) ? route('admin.user-address.update', $address->id) : route('admin.user-address.store') }}"
        method="POST">
        @csrf
        @if (isset($address))
            @method('PUT')
        @endif

        <div class="row g-4">

            {{-- Left Column --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-map me-2 text-primary"></i>{{ __('admin.address_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- User --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.user') }} <span class="text-danger">*</span>
                                </label>
                                <div class="select2-lg">
                                    <select name="user_id"
                                        class="select2-user form-select form-select-lg w-100 @error('user_id') is-invalid @enderror">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $address->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- First Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.first_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="first_name"
                                    class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_first_name') }}"
                                    value="{{ old('first_name', $address->first_name ?? '') }}">
                                @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.last_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="last_name"
                                    class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_last_name') }}"
                                    value="{{ old('last_name', $address->last_name ?? '') }}">
                                @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Country Code + Mobile --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.country_code') }}</label>
                                <input type="text" name="mobile_country_code"
                                    class="form-control form-control-lg @error('mobile_country_code') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_country_code') }}"
                                    value="{{ old('mobile_country_code', $address->mobile_country_code ?? '+91') }}">
                                @error('mobile_country_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.mobile_no') }} <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="mobile_no"
                                    class="form-control form-control-lg @error('mobile_no') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_phone') }}"
                                    value="{{ old('mobile_no', $address->mobile_no ?? '') }}">
                                @error('mobile_no')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.email') }}</label>
                                <input type="email" name="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_email') }}"
                                    value="{{ old('email', $address->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- House No --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.house_no') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="house_no"
                                    class="form-control form-control-lg @error('house_no') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_house_no') }}"
                                    value="{{ old('house_no', $address->house_no ?? '') }}">
                                @error('house_no')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Landmark --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.landmark') }}</label>
                                <input type="text" name="landmark"
                                    class="form-control form-control-lg @error('landmark') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_street') }}"
                                    value="{{ old('landmark', $address->landmark ?? '') }}">
                                @error('landmark')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Locality / Area --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.locality_area') }}</label>
                                <input type="text" name="locality_area"
                                    class="form-control form-control-lg @error('locality_area') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_area') }}"
                                    value="{{ old('locality_area', $address->locality_area ?? '') }}">
                                @error('locality_area')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.pincode') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="pincode"
                                    class="form-control form-control-lg @error('pincode') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_pincode') }}" maxlength="6"
                                    value="{{ old('pincode', $address->pincode ?? '') }}">
                                @error('pincode')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.city') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="city"
                                    class="form-control form-control-lg @error('city') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_city') }}"
                                    value="{{ old('city', $address->city ?? '') }}">
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- State --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.state') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="state"
                                    class="form-control form-control-lg @error('state') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_state') }}"
                                    value="{{ old('state', $address->state ?? '') }}">
                                @error('state')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Country --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.country') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="country"
                                    class="form-control form-control-lg @error('country') is-invalid @enderror"
                                    placeholder="{{ __('admin.ph_country') }}"
                                    value="{{ old('country', $address->country ?? 'India') }}">
                                @error('country')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address Type --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('admin.address_type') }} <span class="text-danger">*</span>
                                </label>
                                <div class="select2-lg">
                                    <select name="address_type"
                                        class="select2-address-type form-select form-select-lg w-100 @error('address_type') is-invalid @enderror">
                                        @foreach (['home' => __('admin.addr_type_home'), 'work' => __('admin.addr_type_work'), 'other' => __('admin.addr_type_other')] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('address_type', $address->address_type ?? 'home') == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('address_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $address->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $address->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $address->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($address) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.user-address.index') }}" class="btn btn-outline-secondary btn-lg"><i
                                    class="bx bx-x me-1"></i>{{ __('admin.cancel') }}</a>
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
            // Initialize Select2 for user
            $('.select2-user').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_user') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-address-type').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // Custom: digits only with length check
            $.validator.addMethod('digitsRange', function(value, element, param) {
                if (!value) return true;
                return /^\d+$/.test(value) && value.length >= param[0] && value.length <= param[1];
            }, '{{ __('admin.val_number_valid') }}');

            $('#addressForm').validate({
                rules: {
                    user_id: {
                        required: true,
                    },
                    first_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 100,
                    },
                    last_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 100,
                    },
                    mobile_country_code: {
                        maxlength: 10,
                    },
                    mobile_no: {
                        required: true,
                        digits: true,
                        minlength: 7,
                        maxlength: 15,
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                    },
                    house_no: {
                        required: true,
                        maxlength: 50,
                    },
                    landmark: {
                        required: true,
                        maxlength: 255,
                    },
                    locality_area: {
                        required: true,
                        maxlength: 255,
                    },
                    pincode: {
                        required: true,
                        digits: true,
                        minlength: 6,
                        maxlength: 6,
                    },
                    city: {
                        required: true,
                        maxlength: 100,
                    },
                    state: {
                        required: true,
                        maxlength: 100,
                    },
                    country: {
                        required: true,
                        maxlength: 100,
                    },
                    address_type: {
                        required: true,
                    },
                    card_brand: {
                        required: true,
                    },
                },
                messages: {
                    user_id: {
                        required: '{{ __('admin.val_user_required') }}',
                    },
                    first_name: {
                        required: '{{ __('admin.val_first_name_required') }}',
                        minlength: '{{ __('admin.val_min_2') }}',
                        maxlength: '{{ __('admin.val_max_100') }}',
                    },
                    last_name: {
                        required: '{{ __('admin.val_last_name_required') }}',
                        minlength: '{{ __('admin.val_min_2') }}',
                        maxlength: '{{ __('admin.val_max_100') }}',
                    },
                    mobile_no: {
                        required: '{{ __('admin.val_mobile_required') }}',
                        digits: '{{ __('admin.val_mobile_digits') }}',
                        minlength: '{{ __('admin.val_phone_min') }}',
                        maxlength: '{{ __('admin.val_mobile_max') }}',
                    },
                    email: {
                        required: '{{ __('admin.val_email_required') }}',
                        email: '{{ __('admin.val_email_valid') }}',
                    },
                    house_no: {
                        required: '{{ __('admin.val_house_required') }}',
                        maxlength: '{{ __('admin.val_max_50') }}',
                    },
                    pincode: {
                        required: '{{ __('admin.val_pincode_required') }}',
                        digits: '{{ __('admin.val_whole_number') }}',
                        minlength: '{{ __('admin.val_pincode_exact') }}',
                        maxlength: '{{ __('admin.val_pincode_exact') }}',
                    },
                    city: {
                        required: '{{ __('admin.val_city_required') }}',
                        maxlength: '{{ __('admin.val_max_100') }}',
                    },
                    state: {
                        required: '{{ __('admin.val_state_required') }}',
                        maxlength: '{{ __('admin.val_max_100') }}',
                    },
                    country: {
                        required: '{{ __('admin.val_country_required') }}',
                        maxlength: '{{ __('admin.val_max_100') }}',
                    },
                    address_type: {
                        required: '{{ __('admin.val_address_type_required') }}',
                    },
                    card_brand: {
                        required: '{{ __('admin.val_card_brand_required') }}',
                    },
                    landmark: {
                        required: '{{ __('admin.val_landmark_required') }}',
                    },
                    locality_area: {
                        required: '{{ __('admin.val_locality_required') }}',
                    },
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
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

        // Status toggle
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
