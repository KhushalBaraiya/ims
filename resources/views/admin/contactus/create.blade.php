@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($contact) ? __('admin.view_contact') : __('admin.add_contact') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.contactus.index') }}">{{ __('admin.contactus_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($contact) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.contactus.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="contactForm"
        action="{{ isset($contact) ? route('admin.contactus.update', $contact->id) : route('admin.contactus.store') }}"
        method="POST">
        @csrf
        @if (isset($contact))
            @method('PUT')
        @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-envelope me-2 text-primary"></i>{{ __('admin.contact_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_contact_name') }}"
                                    value="{{ old('name', $contact->name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_email') }}"
                                    value="{{ old('email', $contact->email ?? '') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.subject') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_contact_subject') }}"
                                    value="{{ old('subject', $contact->subject ?? '') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.message') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_contact_message') }}">{{ old('message', $contact->message ?? '') }}</textarea>
                            </div>
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
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $contact->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $contact->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $contact->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bx bx-save me-1"></i>
                                {{ isset($contact) ? __('admin.update') : __('admin.save') }}</button>
                            <a href="{{ route('admin.contactus.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('#contactForm').validate({
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
                    subject: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    message: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    name: {
                        required: "{{ __('admin.val_name_required') }}",
                        minlength: "{{ __('admin.val_min_2') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    email: {
                        required: "{{ __('admin.val_email_required') }}",
                        email: "{{ __('admin.val_email_valid') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    subject: {
                        required: "{{ __('admin.val_subject_required') }}",
                        minlength: "{{ __('admin.val_min_3') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    message: {
                        required: "{{ __('admin.val_message_required') }}",
                        minlength: "{{ __('admin.val_min_10') }}"
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
