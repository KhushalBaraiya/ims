@extends('layouts.admin')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($payment) ? __('admin.edit_payment') : __('admin.add_payment') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.payment.index') }}">{{ __('admin.payment_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($payment) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-secondary btn-lg">
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

    <form id="paymentForm"
        action="{{ isset($payment) ? route('admin.payment.update', $payment->id) : route('admin.payment.store') }}"
        method="POST">
        @csrf
        @if (isset($payment))
            @method('PUT')
        @endif

        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-8">

                {{-- PAYMENT DETAILS --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-credit-card me-2 text-primary"></i>{{ __('admin.payment_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- USER --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.user') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="user_id"
                                        class="select2-user form-select form-select-lg w-100 @error('user_id') is-invalid @elseif(old('user_id')) is-valid @enderror">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $payment->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ORDER --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.order') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="order_id"
                                        class="select2-order form-select form-select-lg w-100 @error('order_id') is-invalid @elseif(old('order_id')) is-valid @enderror">
                                        <option value="">{{ __('admin.select_order') }}</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}"
                                                {{ old('order_id', $payment->order_id ?? '') == $order->id ? 'selected' : '' }}>
                                                {{ $order->order_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('order_id')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- AMOUNT --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.amount') }} (₹) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">₹</span>
                                    <input type="number" name="amount" step="0.01"
                                        class="form-control @error('amount') is-invalid @elseif(old('amount')) is-valid @enderror"
                                        placeholder="{{ __('admin.ph_amount') }}"
                                        value="{{ old('amount', $payment->amount ?? '') }}">
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CURRENCY --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.currency') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="currency"
                                        class="select2-currency form-select form-select-lg w-100 @error('currency') is-invalid @elseif(old('currency')) is-valid @enderror">
                                        <option value="">{{ __('admin.select_currency') }}</option>
                                        @foreach (['INR' => 'INR', 'USD' => 'USD', 'EUR' => 'EUR'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('currency', $payment->currency ?? '') == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('currency')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- TRANSACTION ID --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.transaction_id') }}</label>
                                <input type="text" name="transaction_id"
                                    class="form-control form-control-lg @error('transaction_id') is-invalid @elseif(old('transaction_id')) is-valid @enderror"
                                    placeholder="{{ __('admin.ph_transaction_id') }}"
                                    value="{{ old('transaction_id', $payment->transaction_id ?? '') }}">
                                @error('transaction_id')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PAYMENT METHOD --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.payment_method') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="payment_method"
                                        class="select2-pay-method form-select form-select-lg w-100 @error('payment_method') is-invalid @elseif(old('payment_method')) is-valid @enderror">
                                        <option value="">{{ __('admin.select_payment_method') }}</option>
                                        @foreach (['card' => 'Card', 'upi' => 'UPI', 'netbanking' => 'Net Banking', 'cod' => 'COD'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('payment_method', $payment->payment_method ?? '') == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- GATEWAY --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.gateway') }}</label>
                                <div class="select2-lg">
                                    <select name="gateway"
                                        class="select2-gateway form-select form-select-lg w-100 @error('gateway') is-invalid @elseif(old('gateway')) is-valid @enderror">
                                        <option value="">{{ __('admin.no_option') }}</option>
                                        @foreach (['razorpay' => __('admin.pm_razorpay'), 'stripe' => __('admin.pm_stripe'), 'paypal' => __('admin.pm_paypal')] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('gateway', $payment->gateway ?? '') == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('gateway')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PAID AT --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.paid_at') }}</label>
                                <input type="datetime-local" name="paid_at"
                                    class="form-control form-control-lg @error('paid_at') is-invalid @elseif(old('paid_at')) is-valid @enderror"
                                    value="{{ old('paid_at', isset($payment->paid_at) ? \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d\TH:i') : '') }}">
                                @error('paid_at')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                {{-- PUBLISH --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-send me-2 text-primary"></i>{{ __('admin.publish') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- PAYMENT STATUS --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('admin.payment_status') }} <span
                                    class="text-danger">*</span></label>
                            <div class="select2-lg">
                                <select name="payment_status"
                                    class="select2-pay-status form-select form-select-lg w-100 @error('payment_status') is-invalid @elseif(old('payment_status')) is-valid @enderror">
                                    <option value="">{{ __('admin.select_payment_status') }}</option>
                                    @foreach (['pending' => __('admin.pending'), 'success' => __('admin.status_success'), 'failed' => __('admin.failed'), 'refunded' => __('admin.refunded')] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('payment_status', $payment->payment_status ?? '') == $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('payment_status')
                                <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS TOGGLE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-switch-input" type="checkbox" role="switch"
                                        id="statusToggle" name="status" value="active"
                                        {{ old('status', $payment->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold fs-6 {{ old('status', $payment->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ old('status', $payment->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                </span>
                            </div>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($payment) ? __('admin.update') : __('admin.save') }}
                            </button>
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            // Initialize Select2 for user and order
            $('.select2-user, .select2-order').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-currency, .select2-pay-method, .select2-gateway, .select2-pay-status').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });

            $('#paymentForm').validate({
                rules: {
                    user_id: {
                        required: true
                    },
                    order_id: {
                        required: true
                    },
                    amount: {
                        required: true,
                        number: true,
                        min: 0.01
                    },
                    currency: {
                        required: true
                    },
                    transaction_id: {
                        maxlength: 255
                    },
                    payment_method: {
                        required: true
                    },
                    payment_status: {
                        required: true
                    }
                },
                messages: {
                    user_id: {
                        required: "{{ __('admin.val_user_required') }}"
                    },
                    order_id: {
                        required: "{{ __('admin.val_order_required') }}"
                    },
                    amount: {
                        required: "{{ __('admin.val_amount_required') }}",
                        number: "{{ __('admin.val_amount_number') }}",
                        min: "{{ __('admin.val_amount_min') }}"
                    },
                    currency: {
                        required: "{{ __('admin.val_currency_required') }}"
                    },
                    transaction_id: {
                        maxlength: "{{ __('admin.val_max_255') }}"
                    },
                    payment_method: {
                        required: "{{ __('admin.val_payment_method_required') }}"
                    },
                    payment_status: {
                        required: "{{ __('admin.val_payment_status_required') }}"
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
