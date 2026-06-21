@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($offer) ? __('admin.edit_offer') : __('admin.add_offer') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.offer.index') }}">{{ __('admin.offer_list') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ isset($offer) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.offer.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="offerForm" action="{{ isset($offer) ? route('admin.offer.update', $offer->id) : route('admin.offer.store') }}"
        method="POST">
        @csrf
        @if (isset($offer))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bxs-offer me-2 text-primary"></i>{{ __('admin.offer_details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.offer_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="offer_name" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_offer_name') }}"
                                    value="{{ old('offer_name', $offer->offer_name ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.offer_code') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="offer_code" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_offer_code') }}"
                                    value="{{ old('offer_code', $offer->offer_code ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.offer_type') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="offer_type" class="select2-offer-type form-select form-select-lg w-100">
                                        <option value="percentage"
                                            {{ old('offer_type', $offer->offer_type ?? '') == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                        <option value="fixed"
                                            {{ old('offer_type', $offer->offer_type ?? '') == 'fixed' ? 'selected' : '' }}>
                                            Fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.discount_value') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="discount_value" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_discount_value') }}"
                                    value="{{ old('discount_value', $offer->discount_value ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.offer_value') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="offer_value" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_offer_value') }}"
                                    value="{{ old('offer_value', $offer->offer_value ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.is_active') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="is_active" class="select2-is-active form-select form-select-lg w-100">
                                        <option value="1"
                                            {{ old('is_active', $offer->is_active ?? 1) == 1 ? 'selected' : '' }}>
                                            {{ __('admin.status_active') }}
                                        </option>
                                        <option value="0"
                                            {{ old('is_active', $offer->is_active ?? 1) == 0 ? 'selected' : '' }}>
                                            {{ __('admin.status_inactive') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.start_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.date_placeholder') }}"
                                    value="{{ old('start_date', $offer->start_date ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.end_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.date_placeholder') }}"
                                    value="{{ old('end_date', $offer->end_date ?? '') }}">
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
                                        {{ old('status', $offer->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $offer->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $offer->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($offer) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.offer.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-offer-type, .select2-is-active').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('#offerForm').validate({
                rules: {
                    offer_name: {
                        required: true,
                        maxlength: 255
                    },
                    offer_code: {
                        required: true,
                        maxlength: 255
                    },
                    offer_type: {
                        required: true
                    },
                    discount_value: {
                        required: true,
                        number: true
                    },
                    offer_value: {
                        required: true,
                        number: true
                    },
                    start_date: {
                        required: true,
                        date: true
                    },
                    end_date: {
                        required: true,
                        date: true
                    },
                    is_active: {
                        required: true
                    }
                },
                messages: {
                    offer_name: {
                        required: "{{ __('admin.val_offer_name_required') }}"
                    },
                    offer_code: {
                        required: "{{ __('admin.val_offer_code_required') }}"
                    },
                    offer_type: {
                        required: "{{ __('admin.val_offer_type_required') }}"
                    },
                    discount_value: {
                        required: "{{ __('admin.val_discount_value_required') }}",
                        number: "{{ __('admin.val_number_valid') }}"
                    },
                    offer_value: {
                        required: "{{ __('admin.val_offer_value_required') }}",
                        number: "{{ __('admin.val_number_valid') }}"
                    },
                    start_date: {
                        required: "{{ __('admin.val_start_date_required') }}",
                        date: "{{ __('admin.val_date_valid') }}"
                    },
                    end_date: {
                        required: "{{ __('admin.val_end_date_required') }}",
                        date: "{{ __('admin.val_date_valid') }}"
                    },
                    is_active: {
                        required: "{{ __('admin.val_active_status_required') }}"
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
