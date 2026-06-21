@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($item) ? __('admin.edit_order_item') : __('admin.add_order_item') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.order-item.index') }}">{{ __('admin.order_item_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($item) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.order-item.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="orderItemForm"
        action="{{ isset($item) ? route('admin.order-item.update', $item->id) : route('admin.order-item.store') }}"
        method="POST">
        @csrf
        @if (isset($item))
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Left Column --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-list-ul me-2 text-primary"></i>{{ __('admin.order_item_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Order --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.order') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="order_id" class="select2-order form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_order') }}</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}"
                                                {{ old('order_id', $item->order_id ?? '') == $order->id ? 'selected' : '' }}>
                                                {{ $order->order_number }}
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
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}"
                                                data-images='@json($product->images ?? [])'
                                                {{ old('product_id', $item->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Product Image Preview --}}
                                <div id="productImagePreview" class="mt-2 d-none">
                                    <div class="d-flex flex-wrap gap-2 p-2 border rounded bg-light"></div>
                                </div>
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.quantity') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="qty" class="form-control form-control-lg"
                                    min="1" placeholder="{{ __('admin.qty_placeholder') }}"
                                    value="{{ old('quantity', $item->quantity ?? '') }}">
                            </div>

                            {{-- Price --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.price') }} (₹) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="price" id="price" class="form-control" step="0.01"
                                        placeholder="{{ __('admin.ph_price_short') }}"
                                        value="{{ old('price', $item->price ?? '') }}">
                                </div>
                            </div>

                            {{-- Total Price --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.total_price') }} (₹) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="total_price" id="totalPrice" class="form-control"
                                        step="0.01" placeholder="{{ __('admin.auto_calculated') }}"
                                        value="{{ old('total_price', $item->total_price ?? '') }}">
                                </div>
                            </div>

                            {{-- Product snapshot info (read-only, for reference) --}}
                            @if (isset($item))
                                <div class="col-md-12">
                                    <div class="alert alert-light border mb-0 py-2 px-3">
                                        <small class="text-muted">
                                            <i class="bx bx-info-circle me-1"></i>
                                            {{ __('admin.saved_product_label') }}
                                            <strong>{{ $item->product_name }}</strong>
                                            — {{ __('admin.product_snapshot_hint') }}
                                        </small>
                                    </div>
                                </div>
                            @endif

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
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($item) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.order-item.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-order').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_order') }}',
                allowClear: true,
                width: '100%'
            });

            // ✅ Select2 change event - auto fill price & show images
            $('.select2-product').on('change', function() {
                const val = $(this).val();
                const opt = $('#productSelect option[value="' + val + '"]');

                // Auto-fill price
                const price = opt.attr('data-price');
                if (price) {
                    $('#price').val(parseFloat(price).toFixed(2));
                    calcTotal();
                } else {
                    $('#price').val('');
                    $('#totalPrice').val('');
                }

                // Show product images
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
                        el.alt = 'product';
                        el.style =
                            'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;';
                        wrap.appendChild(el);
                    });
                    preview.classList.remove('d-none');
                } else {
                    preview.classList.add('d-none');
                }
            });

            // On page load (edit mode) - show images if already selected
            if ($('.select2-product').val()) {
                $('.select2-product').trigger('change');
            }

            $('#orderItemForm').validate({
                rules: {
                    order_id: {
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
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    total_price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                },
                messages: {
                    order_id: {
                        required: '{{ __('admin.val_order_required') }}'
                    },
                    product_id: {
                        required: '{{ __('admin.val_product_required') }}'
                    },
                    quantity: {
                        required: '{{ __('admin.val_quantity_required') }}',
                        digits: '{{ __('admin.val_whole_number') }}',
                        min: '{{ __('admin.val_min_1') }}'
                    },
                    price: {
                        required: '{{ __('admin.val_price_required') }}',
                        number: '{{ __('admin.val_price_number') }}',
                        min: '{{ __('admin.val_not_negative') }}'
                    },
                    total_price: {
                        required: '{{ __('admin.val_total_price_required') }}',
                        number: '{{ __('admin.val_total_price_number') }}',
                        min: '{{ __('admin.val_not_negative') }}'
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
                        element.after(error);
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
                    $(form).find('button[type="submit"]').prop('disabled', true)
                        .html(
                            '<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __('admin.processing') }}'
                        );
                    form.submit();
                }
            });
        });

        // Qty & Price calculate total
        const qtyInput = document.getElementById('qty');
        const priceInput = document.getElementById('price');
        const totalInput = document.getElementById('totalPrice');

        function calcTotal() {
            const q = parseFloat(qtyInput.value) || 0;
            const p = parseFloat(priceInput.value) || 0;
            totalInput.value = (q * p).toFixed(2);
        }

        qtyInput.addEventListener('input', calcTotal);
        priceInput.addEventListener('input', calcTotal);

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
    </script>
@endpush
