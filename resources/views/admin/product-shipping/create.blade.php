@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ isset($shipping) ? __('admin.edit_product_shipping') : __('admin.add_product_shipping') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.product-shipping.index') }}">{{ __('admin.product_shipping_list') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ isset($shipping) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.product-shipping.index') }}" class="btn btn-outline-secondary btn-lg">
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
    <form id="shippingForm"
        action="{{ isset($shipping) ? route('admin.product-shipping.update', $shipping->id) : route('admin.product-shipping.store') }}"
        method="POST">
        @csrf
        @if (isset($shipping))
            @method('PUT')
        @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-package me-2 text-primary"></i>{{ __('admin.shipping_details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_shipping_title') }}"
                                    value="{{ old('title', $shipping->title ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.delivery_time') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="delivery_time"
                                    class="form-control form-control-lg @error('delivery_time') is-invalid @elseif(old('delivery_time')) is-valid @enderror"
                                    placeholder="{{ __('admin.ph_delivery_time') }}"
                                    value="{{ old('delivery_time', $shipping->delivery_time ?? '') }}">
                                @error('delivery_time')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.shipping_charge') }} <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="charge" class="form-control"
                                        placeholder="{{ __('admin.ph_shipping_charge') }}" min="0"
                                        value="{{ old('charge', $shipping->charge ?? 0) }}">
                                </div>
                                <div class="form-text">{{ __('admin.enter_0_free_shipping') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.free_delivery_above') }}</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="free_above" class="form-control"
                                        placeholder="{{ __('admin.ph_free_above') }}"
                                        value="{{ old('free_above', $shipping->free_above ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.cod') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="cod_available" class="select2-cod form-select form-select-lg w-100">
                                        <option value="1"
                                            {{ old('cod_available', $shipping->cod_available ?? 1) == 1 ? 'selected' : '' }}>
                                            {{ __('admin.available') }}</option>
                                        <option value="0"
                                            {{ old('cod_available', $shipping->cod_available ?? 1) == 0 ? 'selected' : '' }}>
                                            {{ __('admin.not_available') }}</option>
                                    </select>
                                </div>
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
                                        {{ old('status', $shipping->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $shipping->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $shipping->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bx bx-save me-1"></i>
                                {{ isset($shipping) ? __('admin.update') : __('admin.save') }}</button>
                            <a href="{{ route('admin.product-shipping.index') }}"
                                class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-cod').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('#shippingForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    delivery_time: {
                        required: true,
                        maxlength: 255
                    },
                    charge: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    cod_available: {
                        required: true
                    },
                    free_above: {
                        number: true,
                        min: 0
                    }
                },
                messages: {
                    title: {
                        required: "{{ __('admin.val_title_required') }}",
                        minlength: "{{ __('admin.val_min_2') }}"
                    },
                    delivery_time: {
                        required: "{{ __('admin.val_delivery_time_required') }}",
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    charge: {
                        required: "{{ __('admin.val_shipping_charge_required') }}",
                        number: "{{ __('admin.val_number_valid') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    cod_available: {
                        required: "{{ __('admin.val_cod_required') }}"
                    },
                    free_above: {
                        number: "{{ __('admin.val_number_valid') }}",
                        min: "{{ __('admin.val_not_negative') }}"
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
