@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($coupon) ? __('admin.edit_coupon') : __('admin.add_coupon') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupon.index') }}">{{ __('admin.coupon_list') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ isset($coupon) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="couponForm"
        action="{{ isset($coupon) ? route('admin.coupon.update', $coupon->id) : route('admin.coupon.store') }}"
        method="POST">
        @csrf
        @if (isset($coupon))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bxs-coupon me-2 text-primary"></i>{{ __('admin.coupon_details') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.coupon_code') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_coupon_code') }}"
                                    value="{{ old('code', $coupon->code ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.coupon_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_coupon_name') }}"
                                    value="{{ old('name', $coupon->name ?? '') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.product') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="product_id" id="productSelect"
                                        class="select2-product form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_product') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-images='@json($product->images ?? [])'
                                                {{ old('product_id', $coupon->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="productImagePreview" class="mt-2 d-none">
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-light"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.discount_type') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="discount_type"
                                        class="select2-discount-type form-select form-select-lg w-100">
                                        <option value="percentage"
                                            {{ old('discount_type', $coupon->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                        <option value="fixed"
                                            {{ old('discount_type', $coupon->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.discount_value') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="discount_value"
                                    class="form-control form-control-lg" placeholder="{{ __('admin.ph_coupon_discount') }}"
                                    value="{{ old('discount_value', $coupon->discount_value ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.start_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.date_placeholder') }}"
                                    value="{{ old('start_date', $coupon->start_date ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.end_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.date_placeholder') }}"
                                    value="{{ old('end_date', $coupon->end_date ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.min_order_amount') }}</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="minimum_order_amount" class="form-control"
                                        placeholder="{{ __('admin.ph_min_order') }}"
                                        value="{{ old('minimum_order_amount', $coupon->minimum_order_amount ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.max_discount_value') }}</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" step="0.01" name="maximum_discount_value"
                                        class="form-control" placeholder="{{ __('admin.ph_max_discount') }}"
                                        value="{{ old('maximum_discount_value', $coupon->maximum_discount_value ?? '') }}">
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
                                        {{ old('status', $coupon->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $coupon->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $coupon->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($coupon) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-product').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_product') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-discount-type').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // Product image preview function
            function showProductImages(val) {
                const opt = $('#productSelect option[value="' + val + '"]');
                let images = [];
                try {
                    images = JSON.parse(opt.attr('data-images') || '[]');
                } catch (e) {}
                const wrap = document.querySelector('#productImagePreview div');
                const preview = document.getElementById('productImagePreview');
                wrap.innerHTML = '';
                if (images.length) {
                    images.forEach(function(img) {
                        const el = document.createElement('img');
                        el.src = '{{ asset('uploads/products') }}/' + img;
                        el.style =
                            'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;';
                        wrap.appendChild(el);
                    });
                    preview.classList.remove('d-none');
                } else {
                    preview.classList.add('d-none');
                }
            }

            // Trigger image preview on product selection change
            $('.select2-product').on('change', function() {
                showProductImages($(this).val());
            });

            // Show images if product is already selected (edit mode)
            if ($('.select2-product').val()) {
                showProductImages($('.select2-product').val());
            }

            $('#couponForm').validate({
                rules: {
                    code: {
                        required: true,
                        maxlength: 255
                    },
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    product_id: {
                        required: true
                    },
                    discount_type: {
                        required: true
                    },
                    discount_value: {
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
                    minimum_order_amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    maximum_discount_value: {
                        required: true,
                        number: true,
                        min: 0
                    }
                },
                messages: {
                    code: {
                        required: "{{ __('admin.val_coupon_code_required') }}"
                    },
                    name: {
                        required: "{{ __('admin.val_coupon_name_required') }}"
                    },
                    product_id: {
                        required: "{{ __('admin.val_product_required') }}"
                    },
                    discount_type: {
                        required: "{{ __('admin.val_discount_type_required') }}"
                    },
                    discount_value: {
                        required: "{{ __('admin.val_discount_value_required') }}",
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
                    minimum_order_amount: {
                        required: "{{ __('admin.val_min_order_required') }}",
                        number: "{{ __('admin.val_number_valid') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    maximum_discount_value: {
                        required: "{{ __('admin.val_max_discount_required') }}",
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
