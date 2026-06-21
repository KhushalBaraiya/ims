@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($order) ? __('admin.edit_order') : __('admin.add_order') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">{{ __('admin.order_list') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ isset($order) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="orderForm" action="{{ isset($order) ? route('admin.order.update', $order->id) : route('admin.order.store') }}"
        method="POST">
        @csrf
        @if (isset($order))
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Left Column --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-cart me-2 text-primary"></i>{{ __('admin.order_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- User --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.user') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="user_id" class="select2-user form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $order->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Product --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.product') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="product_id" id="productSelect"
                                        class="select2-product form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_product') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-images='@json($product->images ?? [])'
                                                {{ old('product_id', $order->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="productImagePreview" class="mt-2 d-none">
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-light"></div>
                                </div>
                            </div>

                            {{-- Order Number --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.order_number') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="order_number" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_order_no') }}"
                                    value="{{ old('order_number', $order->order_number ?? '') }}">
                            </div>

                            {{-- Order Date --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.order_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="order_date" class="form-control form-control-lg"
                                    value="{{ old('order_date', $order->order_date ?? '') }}">
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.quantity') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="qty" class="form-control form-control-lg"
                                    min="1" placeholder="{{ __('admin.ph_qty_short') }}"
                                    value="{{ old('quantity', $order->quantity ?? '') }}">
                            </div>

                            {{-- Price --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.price') }} (₹) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="price" id="price" class="form-control" step="0.01"
                                        placeholder="{{ __('admin.ph_price_short') }}"
                                        value="{{ old('price', $order->price ?? '') }}">
                                </div>
                            </div>

                            {{-- Total Amount --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.total_amount') }} (₹) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="total_amount" id="totalAmount" class="form-control"
                                        step="0.01" placeholder="{{ __('admin.auto_calculated') }}"
                                        value="{{ old('total_amount', $order->total_amount ?? '') }}">
                                </div>
                            </div>

                            {{-- Shipping Address --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">{{ __('admin.shipping_address') }}</label>
                                <textarea name="shipping_address" class="form-control form-control-lg" rows="2"
                                    placeholder="{{ __('admin.ph_shipping_address') }}">{{ old('shipping_address', $order->shipping_address ?? '') }}</textarea>
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

                        {{-- Payment Method --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.payment_method') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="payment_method" class="select2-pay-method form-select form-select-lg w-100">
                                    @foreach (['razorpay' => __('admin.pm_razorpay'), 'cod' => __('admin.payment_cod'), 'upi' => __('admin.payment_upi'), 'card' => __('admin.payment_card')] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('payment_method', $order->payment_method ?? '') == $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Payment Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.payment_status') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="payment_status" class="select2-pay-status form-select form-select-lg w-100">
                                    @foreach (['pending' => __('admin.pending'), 'paid' => __('admin.paid'), 'failed' => __('admin.failed')] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('payment_status', $order->payment_status ?? '') == $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Status Toggle --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $order->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $order->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $order->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($order) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
        {{-- Pre-assign all Blade translation strings to JS variables --}}
        var jsLang = {
            processing: "{{ __('admin.processing') }}",
            statusActive: "{{ __('admin.status_active') }}",
            statusInactive: "{{ __('admin.status_inactive') }}",
            swalFixErrors: "{{ __('admin.swal_fix_errors') }}",
            valUserRequired: "{{ __('admin.val_user_required') }}",
            valProductRequired: "{{ __('admin.val_product_required') }}",
            valOrderNoRequired: "{{ __('admin.val_order_no_required') }}",
            valOrderDateRequired: "{{ __('admin.val_order_date_required') }}",
            valDateValid: "{{ __('admin.val_date_valid') }}",
            valQtyRequired: "{{ __('admin.val_quantity_required') }}",
            valWholeNumber: "{{ __('admin.val_whole_number') }}",
            valPriceRequired: "{{ __('admin.val_price_required') }}",
            valPriceNumber: "{{ __('admin.val_price_number') }}",
            valNotNegative: "{{ __('admin.val_not_negative') }}",
            valTotalRequired: "{{ __('admin.val_total_amount_required') }}",
            valNumberValid: "{{ __('admin.val_number_valid') }}",
            valPaymentMethod: "{{ __('admin.val_payment_method_required') }}",
            valPaymentStatus: "{{ __('admin.val_payment_status_required') }}",
            valShipping: "{{ __('admin.val_shipping_address_required') }}",
            uploadsPath: "{{ asset('uploads/products') }}"
        };

        $(document).ready(function() {
            $('.select2-user, .select2-product').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_user') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-pay-method, .select2-pay-status').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // ── jQuery Validate ──────────────────────────────────────────────
            $('#orderForm').validate({
                rules: {
                    user_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    order_number: {
                        required: true,
                        minlength: 2
                    },
                    order_date: {
                        required: true,
                        date: true
                    },
                    quantity: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    total_amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    payment_method: {
                        required: true
                    },
                    payment_status: {
                        required: true
                    },
                    shipping_address: {
                        required: true
                    }
                },
                messages: {
                    user_id: {
                        required: jsLang.valUserRequired
                    },
                    product_id: {
                        required: jsLang.valProductRequired
                    },
                    order_number: {
                        required: jsLang.valOrderNoRequired
                    },
                    order_date: {
                        required: jsLang.valOrderDateRequired,
                        date: jsLang.valDateValid
                    },
                    quantity: {
                        required: jsLang.valQtyRequired,
                        digits: jsLang.valWholeNumber,
                        min: 'Min 1'
                    },
                    price: {
                        required: jsLang.valPriceRequired,
                        number: jsLang.valPriceNumber,
                        min: jsLang.valNotNegative
                    },
                    total_amount: {
                        required: jsLang.valTotalRequired,
                        number: jsLang.valNumberValid,
                        min: jsLang.valNotNegative
                    },
                    payment_method: {
                        required: jsLang.valPaymentMethod
                    },
                    payment_status: {
                        required: jsLang.valPaymentStatus
                    },
                    shipping_address: {
                        required: jsLang.valShipping
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
                    if ($(el).val()) {
                        $(el).addClass('is-valid');
                    } else {
                        $(el).removeClass('is-valid');
                    }
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<i class="bx bx-loader-alt bx-spin me-1"></i> ' + jsLang.processing);
                    form.submit();
                }
            });
        });

        // ── Auto-calculate total ─────────────────────────────────────────────
        var qtyEl = document.getElementById('qty');
        var priceEl = document.getElementById('price');
        var totalEl = document.getElementById('totalAmount');

        function calcTotal() {
            var q = parseFloat(qtyEl.value) || 0;
            var p = parseFloat(priceEl.value) || 0;
            totalEl.value = (q * p).toFixed(2);
        }
        qtyEl.addEventListener('input', calcTotal);
        priceEl.addEventListener('input', calcTotal);

        // ── Status toggle label ──────────────────────────────────────────────
        var statusToggle = document.getElementById('statusToggle');
        if (statusToggle) {
            statusToggle.addEventListener('change', function() {
                var lbl = document.getElementById('statusLabel');
                if (this.checked) {
                    lbl.textContent = jsLang.statusActive;
                    lbl.className = 'fw-semibold fs-6 text-success';
                } else {
                    lbl.textContent = jsLang.statusInactive;
                    lbl.className = 'fw-semibold fs-6 text-danger';
                }
            });
        }

        // ── Product image preview ────────────────────────────────────────────
        var productSelect = document.getElementById('productSelect');
        var previewBox = document.getElementById('productImagePreview');

        function showProductImages(select) {
            var images = [];
            try {
                images = JSON.parse(select.options[select.selectedIndex].dataset.images || '[]');
            } catch (e) {}

            var wrap = previewBox.querySelector('div');
            wrap.innerHTML = '';

            if (images.length) {
                images.forEach(function(img) {
                    var el = document.createElement('img');
                    el.src = jsLang.uploadsPath + '/' + img;
                    el.alt = 'product';
                    el.style =
                        'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;';
                    wrap.appendChild(el);
                });
                previewBox.classList.remove('d-none');
            } else {
                previewBox.classList.add('d-none');
            }
        }

        productSelect.addEventListener('change', function() {
            showProductImages(this);
        });
        if (productSelect.value) {
            showProductImages(productSelect);
        }

        // ── SweetAlert on validation errors ─────────────────────────────────
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: jsLang.swalFixErrors,
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
    </script>
@endpush
