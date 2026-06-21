@extends('layouts.admin')
@section('title', isset($notification) ? __('admin.edit_notification') : __('admin.add_notification'))
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ isset($notification) ? __('admin.edit_notification') : __('admin.add_notification') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.notification.index') }}">{{ __('admin.notification_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($notification) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.notification.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="notificationForm"
        action="{{ isset($notification) ? route('admin.notification.update', $notification) : route('admin.notification.store') }}"
        method="POST">
        @csrf
        @if (isset($notification))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-bell me-2 text-primary"></i>{{ __('admin.notification_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.user') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="user_id"
                                    class="select2-user form-select form-select-lg w-100 @error('user_id') is-invalid @enderror">
                                    <option value="">{{ __('admin.select_user') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $notification->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.message') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="message" rows="4" class="form-control form-control-lg @error('message') is-invalid @enderror"
                                placeholder="{{ __('admin.ph_notification_message') }}">{{ old('message', $notification->message ?? '') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.type') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="type"
                                    class="select2-type form-select form-select-lg w-100 @error('type') is-invalid @enderror">
                                    @foreach (['general' => 'General', 'order' => 'Order', 'promo' => 'Promo', 'alert' => 'Alert'] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('type', $notification->type ?? 'general') == $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.read_status') }}</label>
                            <div class="select2-lg">
                                <select name="is_read" class="select2-read form-select form-select-lg w-100">
                                    <option value="0"
                                        {{ old('is_read', $notification->is_read ?? 0) == 0 ? 'selected' : '' }}>
                                        {{ __('admin.unread') }}
                                    </option>
                                    <option value="1"
                                        {{ old('is_read', $notification->is_read ?? 0) == 1 ? 'selected' : '' }}>
                                        {{ __('admin.read') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $notification->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $notification->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $notification->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-send me-1"></i>
                                {{ isset($notification) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.notification.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-user').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_user') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-type').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });
            $('.select2-read').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('#notificationForm').validate({
                rules: {
                    user_id: {
                        required: true
                    },
                    message: {
                        required: true,
                        minlength: 5
                    },
                    type: {
                        required: true,
                        maxlength: 100
                    }
                },
                messages: {
                    user_id: {
                        required: "{{ __('admin.val_user_required') }}"
                    },
                    message: {
                        required: "{{ __('admin.val_message_required') }}",
                        minlength: "{{ __('admin.val_min_5') }}"
                    },
                    type: {
                        required: "{{ __('admin.val_notification_type_required') }}",
                        maxlength: "{{ __('admin.val_max_100') }}"
                    }
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback d-block text-danger mt-1',
                errorPlacement: function(error, element) {
                    if (element.closest('.select2-lg').length) {
                        element.closest('.select2-lg').after(error);
                    } else if (element.closest('.input-group').length) {
                        element.closest('.input-group').after(error);
                    } else {
                        element.after(error);
                    }
                },
                highlight: function(el) {
                    $(el).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(el) {
                    $(el).removeClass('is-invalid');
                    var val = $(el).val();
                    if (val && val.length > 0) {
                        $(el).addClass('is-valid');
                    } else {
                        $(el).removeClass('is-valid');
                    }
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]').prop('disabled', true).html(
                        '<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.processing') }}'
                    );
                    form.submit();
                }
            });
        });
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
                    }
                });
            });
        @endif
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
    </script>
@endpush
{{-- MANE  --}}