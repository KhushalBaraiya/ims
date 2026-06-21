@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ isset($card) ? __('admin.edit_save_card') : __('admin.add_save_card') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.save-card.index') }}">{{ __('admin.save_card_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ isset($card) ? __('admin.edit') : __('admin.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.save-card.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bx bx-arrow-back me-1"></i> {{ __('admin.back') }}
        </a>
    </div>



    <form id="saveCardForm"
        action="{{ isset($card) ? route('admin.save-card.update', $card->id) : route('admin.save-card.store') }}"
        method="POST">
        @csrf
        @if (isset($card))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">

                {{-- Card Preview --}}
                <div class="mb-4 p-4 rounded-4 text-white position-relative overflow-hidden card-preview-bg"
                    id="cardPreview"
                    style="min-height:170px; display:flex; flex-direction:column; justify-content:space-between;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="opacity-75 small mb-1">{{ __('admin.card_brand_label') }}</div>
                            <div class="fw-bold fs-5" id="prevBrand">{{ __('admin.card') }}</div>
                        </div>
                        <i class="bx bx-chip fs-1 opacity-50"></i>
                    </div>
                    <div class="fw-bold fs-4 letter-spacing mb-2" id="prevNumber">•••• •••• •••• ••••</div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <div class="opacity-75 small">{{ __('admin.card_holder') }}</div>
                            <div class="fw-semibold" id="prevHolder">{{ __('admin.card_preview_holder') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="opacity-75 small">{{ __('admin.expiry') }}</div>
                            <div class="fw-semibold" id="prevExpiry">{{ __('admin.card_expiry_format') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold fs-5">
                            <i class="bx bx-credit-card me-2 text-primary"></i>{{ __('admin.card_information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.user') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="user_id" class="select2-user form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $card->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.card_holder_name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="card_holder_name" id="holderInput"
                                    class="form-control form-control-lg" placeholder="{{ __('admin.ph_card_holder') }}"
                                    value="{{ old('card_holder_name', $card->card_holder_name ?? '') }}">
                                @error('card_holder_name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.last_four_digits') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="last_four_digits" id="lastFourInput"
                                    class="form-control form-control-lg" placeholder="{{ __('admin.ph_card_last4') }}"
                                    maxlength="4" value="{{ old('last_four_digits', $card->last_four_digits ?? '') }}">
                                @error('last_four_digits')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('admin.card_brand') }}</label>
                                <div class="select2-lg">
                                    <select name="card_brand" id="brandInput"
                                        class="select2-brand form-select form-select-lg w-100">
                                        <option value="">{{ __('admin.select_option') }}</option>
                                        @foreach (['Visa', 'Mastercard', 'RuPay', 'Amex', 'Discover'] as $brand)
                                            <option value="{{ $brand }}"
                                                {{ old('card_brand', $card->card_brand ?? '') == $brand ? 'selected' : '' }}>
                                                {{ $brand }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('card_brand')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.expiry_month') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="expiry_month" id="monthInput"
                                        class="select2-month form-select form-select-lg w-100">
                                        <option value="">MM</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}"
                                                {{ (string) old('expiry_month', isset($card->expiry_month) ? (int) $card->expiry_month : '') === (string) $m ? 'selected' : '' }}>
                                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('expiry_month')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">{{ __('admin.expiry_year') }} <span
                                        class="text-danger">*</span></label>
                                <div class="select2-lg">
                                    <select name="expiry_year" id="yearInput"
                                        class="select2-year form-select form-select-lg w-100">
                                        <option value="">YYYY</option>
                                        @for ($y = date('Y'); $y <= date('Y') + 10; $y++)
                                            <option value="{{ $y }}"
                                                {{ old('expiry_year', $card->expiry_year ?? '') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                @error('expiry_year')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">{{ __('admin.status') }}</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="hidden" name="status" value="inactive">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input toggle-switch-input" type="checkbox"
                                            role="switch" id="statusToggle" name="status" value="active"
                                            {{ old('status', $card->status ?? 'active') == 'active' ? 'checked' : '' }}>
                                    </div>
                                    <span id="statusLabel"
                                        class="fw-semibold fs-6 {{ old('status', $card->status ?? 'active') == 'active' ? 'text-success' : 'text-danger' }}">
                                        {{ old('status', $card->status ?? 'active') == 'active' ? __('admin.status_active') : __('admin.status_inactive') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('admin.gateway_token') }}</label>
                                <input type="text" name="gateway_token" class="form-control form-control-lg"
                                    placeholder="{{ __('admin.ph_gateway_token') }}"
                                    value="{{ old('gateway_token', $card->gateway_token ?? '') }}">
                                <div class="form-text">{{ __('admin.gateway_token_hint') }}</div>
                                @error('gateway_token')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="bx bx-save me-1"></i>
                                {{ isset($card) ? __('admin.update') : __('admin.save') }}
                            </button>
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
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.save-card.index') }}" class="btn btn-outline-secondary btn-lg"><i
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
            $('.select2-brand').select2({
                theme: 'bootstrap-5',
                placeholder: '{{ __('admin.select_option') }}',
                allowClear: true,
                width: '100%'
            });
            $('.select2-month, .select2-year').select2({
                theme: 'bootstrap-5',
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // Re-bind change events after Select2 wraps the elements
            $('.select2-brand, .select2-month, .select2-year').on('change', function() {
                updatePreview();
            });
            return this.optional(element) || parseInt(value) >= {{ date('Y') }};
        }, "{{ __('admin.val_card_expired') }}");

        $('#saveCardForm').validate({
        rules: {
            user_id: {
                required: true
            },
            card_holder_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            last_four_digits: {
                required: true,
                digits: true,
                minlength: 4,
                maxlength: 4
            },
            expiry_month: {
                required: true,
                digits: true,
                range: [1, 12]
            },
            expiry_year: {
                required: true,
                digits: true,
                range: [{{ date('Y') }}, {{ date('Y') + 10 }}]
            },
            card_brand: {
                maxlength: 50
            },
            gateway_token: {
                maxlength: 255
            },
            status: {
                required: true
            }
        },
        messages: {
            user_id: {
                required: "{{ __('admin.val_user_required') }}"
            },
            card_holder_name: {
                required: "{{ __('admin.val_card_holder_required') }}",
                minlength: "{{ __('admin.val_min_2') }}",
                maxlength: "{{ __('admin.val_max_255') }}"
            },
            last_four_digits: {
                required: "{{ __('admin.val_card_digits_required') }}",
                digits: "{{ __('admin.val_card_digits_exact') }}",
                minlength: "{{ __('admin.val_card_digits_exact') }}",
                maxlength: "{{ __('admin.val_card_digits_exact') }}"
            },
            expiry_month: {
                required: "{{ __('admin.val_expiry_month_required') }}",
                digits: "{{ __('admin.val_expiry_month_digits') ?? __('admin.val_expiry_month_range') }}",
                range: "{{ __('admin.val_expiry_month_range') }}"
            },
            expiry_year: {
                required: "{{ __('admin.val_expiry_year_required') }}",
                digits: "{{ __('admin.val_expiry_year_digits') }}",
                range: "{{ __('admin.val_card_expired') }}"
            },
            card_brand: {
                maxlength: "{{ __('admin.val_max_50') }}"
            },
            gateway_token: {
                maxlength: "{{ __('admin.val_max_255') }}"
            },
            status: {
                required: "{{ __('admin.val_status_required') }}"
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
        const brandGradients = {
            'Visa': 'linear-gradient(135deg,#1a1f71,#0d6efd)',
            'Mastercard': 'linear-gradient(135deg,#eb001b,#f79e1b)',
            'RuPay': 'linear-gradient(135deg,#006a4e,#00a86b)',
            'Amex': 'linear-gradient(135deg,#007bc1,#00bcd4)',
            'Discover': 'linear-gradient(135deg,#ff6600,#ffcc00)',
        };

        const DEFAULT_CARD_HOLDER = @json(__('admin.card_preview_holder')) || 'YOUR NAME';
        const DEFAULT_CARD_NUMBER = '•••• •••• •••• ••••';

        function safeById(id) {
            return document.getElementById(id);
        }

        function updatePreview() {
            const holderEl = safeById('holderInput');
            const last4El = safeById('lastFourInput');
            const brandEl = safeById('brandInput');
            const monthEl = safeById('monthInput');
            const yearEl = safeById('yearInput');

            const holder = holderEl && holderEl.value ? holderEl.value.toUpperCase() : (DEFAULT_CARD_HOLDER.toUpperCase());
            const last4raw = last4El && last4El.value ? last4El.value.replace(/[^0-9]/g, '') : '';
            const last4 = last4raw.length >= 4 ? last4raw.slice(-4) : '';
            const brand = brandEl && brandEl.value ? brandEl.value : '';
            const month = monthEl && monthEl.value ? monthEl.value : '';
            const displayMonth = month ? String(month).padStart(2, '0') : 'MM';
            const year = yearEl && yearEl.value ? yearEl.value : 'YYYY';

            const prevHolder = safeById('prevHolder');
            const prevNumber = safeById('prevNumber');
            const prevBrand = safeById('prevBrand');
            const prevExpiry = safeById('prevExpiry');
            const cardPreview = safeById('cardPreview');

            if (prevHolder) prevHolder.textContent = holder;
            if (prevNumber) prevNumber.textContent = last4 ? `•••• •••• •••• ${last4}` : DEFAULT_CARD_NUMBER;
            if (prevBrand) prevBrand.textContent = brand || '{{ __('admin.card') }}';
            if (prevExpiry) prevExpiry.textContent = `${displayMonth}/${year}`;

            if (cardPreview) {
                if (brandGradients[brand]) {
                    cardPreview.style.background = brandGradients[brand];
                } else {
                    cardPreview.style.background = 'linear-gradient(135deg,#545454,#6c757d)';
                }
            }
        }

        ['holderInput', 'lastFourInput', 'brandInput', 'monthInput', 'yearInput'].forEach(id => {
            const el = safeById(id);
            if (!el) return;
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        });

        // init on edit
        updatePreview();

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
                    }
                });
            });
        @endif
    </script>
@endpush
