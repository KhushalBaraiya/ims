@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($return) ? __('admin.view_return_order') : __('admin.add_return_order') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.return-order.index') }}">{{ __('admin.return_order_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($return) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.return-order.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="returnOrderForm"
        action="{{ isset($return) ? route('admin.return-order.update', $return->id) : route('admin.return-order.store') }}"
        method="POST">
        @csrf
        @if (isset($return))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-revision me-2 text-primary"></i>{{ __('admin.return_order_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.order') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="order_id" class="select2-order form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_order') }}</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}"
                                                {{ old('order_id', $return->order_id ?? '') == $order->id ? 'selected' : '' }}>
                                                {{ $order->order_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.user') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="user_id" class="select2-user form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $return->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.product') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="product_id" id="productSelect"
                                        class="select2-product form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_product') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-images='@json($product->images ?? [])'
                                                {{ old('product_id', $return->product_id ?? '') == $product->id ? 'selected' : '' }}>
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
                                <label class="form-label fw-semibold">{{ __('admin.quantity') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control form-control-lg" min="1"
                                    placeholder="{{ __('admin.ph_return_qty') }}"
                                    value="{{ old('quantity', $return->quantity ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.return_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="return_date" class="form-control form-control-lg"
                                    value="{{ old('return_date', $return->return_date ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.refund') }} (₹) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="refund_amount" class="form-control" step="0.01"
                                        placeholder="{{ __('admin.ph_refund_amount') }}"
                                        value="{{ old('refund_amount', $return->refund_amount ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.reason') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control form-control-lg" rows="3"
                                    placeholder="{{ __('admin.ph_return_reason') }}">{{ old('reason', $return->reason ?? '') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.admin_note') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="admin_note" class="form-control form-control-lg" rows="2"
                                    placeholder="{{ __('admin.ph_admin_note') }}">{{ old('admin_note', $return->admin_note ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.return_type') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="return_type" class="select2-return-type form-select form-select-lg w-100">
                                    @foreach (['return' => __('admin.return_label'), 'exchange' => __('admin.exchange_label'), 'refund' => __('admin.refund_label')] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('return_type', $return->return_type ?? '') == $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
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
                                        {{ old('status', $return->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $return->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $return->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($return) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.return-order.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-user, .select2-order').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-return-type').select2({
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

            $('#returnOrderForm').validate({
                rules: {
                    order_id: {
                        required: true
                    },
                    user_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    quantity: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    return_date: {
                        required: true
                    },
                    refund_amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    reason: {
                        required: true
                    },
                    admin_note: {
                        required: true
                    },
                    return_type: {
                        required: true
                    }
                },
                messages: {
                    order_id: {
                        required: "{{ __('admin.val_order_required') }}"
                    },
                    user_id: {
                        required: "{{ __('admin.val_user_required') }}"
                    },
                    product_id: {
                        required: "{{ __('admin.val_product_required') }}"
                    },
                    quantity: {
                        required: "{{ __('admin.val_quantity_required') }}",
                        digits: "{{ __('admin.val_whole_number') }}",
                        min: "{{ __('admin.val_min_1') }}"
                    },
                    return_date: {
                        required: "{{ __('admin.val_return_date_required') }}"
                    },
                    refund_amount: {
                        required: "{{ __('admin.val_refund_amount_required') }}",
                        number: "{{ __('admin.val_number_valid') }}",
                        min: "{{ __('admin.val_not_negative') }}"
                    },
                    reason: {
                        required: "{{ __('admin.val_reason_required') }}"
                    },
                    admin_note: {
                        required: "{{ __('admin.val_admin_note_required') }}"
                    },
                    return_type: {
                        required: "{{ __('admin.val_return_type_required') }}"
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
