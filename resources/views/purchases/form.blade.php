@csrf
@php
    $isReturned = isset($purchase) && $purchase->returns->isNotEmpty();
@endphp

<style>
    /* Scoped compact styles — purchase form product table */
    #purchaseItemsTable th,
    #purchaseItemsTable td {
        padding: 8px 14px !important;
        font-size: 13px !important;
    }

    .pur-prod-img {
        width: 32px !important;
        height: 32px !important;
        object-fit: cover !important;
        border-radius: 4px !important;
    }

    .pur-prod-name {
        font-size: 12.5px !important;
        line-height: 1.2 !important;
    }

    .pur-prod-sku {
        font-size: 10.5px !important;
        line-height: 1.1 !important;
    }

    .pur-qty-input {
        width: 60px !important;
        height: 28px !important;
        font-size: 12.5px !important;
        padding: 2px 4px !important;
        margin: auto !important;
    }

    .pur-price-input {
        width: 90px !important;
        height: 28px !important;
        font-size: 12.5px !important;
        padding: 2px 4px !important;
        margin: auto !important;
    }

    .pur-sm-input {
        width: 72px !important;
        height: 28px !important;
        font-size: 12px !important;
        padding: 2px 4px !important;
        margin: auto !important;
    }

    .pur-subtotal-cell {
        font-size: 13px !important;
    }
</style>

{{-- Show stock errors prominently --}}
@if ($errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
        <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
        <span>{{ $errors->first('stock_error') }}</span>
    </div>
@endif

{{-- Show general validation errors summary --}}
@if ($errors->any() && !$errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
        <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<div class="row g-4">

    {{-- Left: Order Info --}}
    <div class="col-lg-3">

        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-info-circle text-primary me-2"></i>{{ __('messages.order_info') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.purchase_no_label') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input {{ $isReturned ? 'disabled' : '' }} {{ isset($purchase) ? 'readonly' : '' }}
                            class="form-control fw-bold @error('purchase_no') is-invalid @enderror" id="purchase_no"
                            name="purchase_no" required type="text"
                            value="{{ old('purchase_no', $purchase->purchase_no ?? ($purchaseNo ?? '')) }}">
                        @if (!isset($purchase))
                            <button class="btn btn-outline-secondary" id="btnGeneratePurchaseNo" type="button">
                                <i class="bx bx-refresh"></i>
                            </button>
                        @endif
                        @error('purchase_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.purchase_date_field') }} <span class="text-danger">*</span></label>
                    <input {{ $isReturned ? 'disabled' : '' }}
                        class="form-control flatpickr-date @error('purchase_date') is-invalid @enderror"
                        name="purchase_date" required type="date"
                        value="{{ old('purchase_date', $purchase->purchase_date ?? date('Y-m-d')) }}">
                    @error('purchase_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.supplier_field') }} <span class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('supplier_id') is-invalid @enderror" id="supplierSelect"
                        name="supplier_id" required>
                        <option value="">{{ __('messages.select_supplier') }}</option>
                        @foreach ($suppliers as $s)
                            <option {{ old('supplier_id', $purchase->supplier_id ?? '') == $s->id ? 'selected' : '' }}
                                value="{{ $s->id }}"
                                data-currency-symbol="{{ optional($s->currency)->symbol ?? '' }}"
                                data-currency-code="{{ optional($s->currency)->code ?? '' }}"
                                data-currency-name="{{ optional($s->currency)->name ?? '' }}"
                                data-currency-rate="{{ optional($s->currency)->exchange_rate ?? '' }}"
                                data-currency-id="{{ optional($s->currency)->id ?? '' }}">
                                {{ $s->name }} {{ $s->phone ? '(' . $s->phone . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    {{-- Currency badge — updated via JS when supplier changes --}}
                    <div class="mt-1" id="supplierCurrencyBadge" style="display:none;">
                        <span
                            class="badge bg-warning-subtle text-warning border border-warning-subtle d-inline-flex align-items-center gap-1"
                            style="font-size:11px;padding:4px 8px;">
                            <i class="bx bx-coin"></i>
                            <span id="supplierCurrencyLabel">—</span>
                        </span>
                        <span class="text-muted ms-1" id="supplierCurrencyNote" style="font-size:11px;"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.reference_po_field') }}</label>
                    <input {{ $isReturned ? 'disabled' : '' }} class="form-control" name="reference_no"
                        placeholder="{{ __('messages.ph_optional_ref') }}" type="text"
                        value="{{ old('reference_no', $purchase->reference_no ?? '') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">{{ __('messages.status') }} <span
                            class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('status') is-invalid @enderror" name="status" required>
                        <option value="">{{ __('messages.select_status') }}</option>
                        <option {{ old('status', $purchase->status ?? 'received') === 'received' ? 'selected' : '' }}
                            value="received">
                            Received</option>
                        <option {{ old('status', $purchase->status ?? '') === 'pending' ? 'selected' : '' }}
                            value="pending">
                            Pending</option>
                        <option {{ old('status', $purchase->status ?? '') === 'ordered' ? 'selected' : '' }}
                            value="ordered">
                            Ordered</option>
                        <option {{ old('status', $purchase->status ?? '') === 'draft' ? 'selected' : '' }}
                            value="draft">
                            Draft</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-credit-card text-success me-2"></i>{{ __('messages.payment_details') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.payment_method_field') }} <span class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('payment_method') is-invalid @enderror" name="payment_method"
                        required>
                        <option value="">{{ __('messages.select_payment_method') }}</option>
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? 'Cash') === 'Cash' ? 'selected' : '' }}
                            value="Cash">
                            Cash</option>
                        {{-- <option
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Cheque' ? 'selected' : '' }}
                            value="Cheque">
                            Cheque</option> --}}
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Razorpay' ? 'selected' : '' }}
                            value="Razorpay">
                            ⚡ Razorpay (Online Payment)
                        </option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">{{ __('messages.paid_amount_field') }} <span
                            class="text-danger">*</span></label>
                    <input {{ $isReturned ? 'disabled' : '' }}
                        class="form-control @error('paid_amount') is-invalid @enderror" id="paid_amount"
                        name="paid_amount" required step="0.01" type="number"
                        value="{{ old('paid_amount', $purchase->paid_amount ?? '0.00') }}">
                    @error('paid_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Razorpay info box (shown only when Razorpay is selected) --}}
                <div class="mt-3 d-none" id="razorpay_info_box">
                    <div class="rounded-3 p-3"
                        style="background:linear-gradient(135deg,#eef2ff,#f5f0ff);border:1.5px solid #c7d2fe;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span
                                class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:30px;height:30px;background:linear-gradient(135deg,#696cff,#9c3fe4);">
                                <i class="bx bx-lock-alt text-white" style="font-size:.9rem;"></i>
                            </span>
                            <strong style="color:#4f46e5;font-size:.85rem;">{{ __('messages.secure_razorpay') }}</strong>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.5;">
                            Click <strong style="color:#696cff;">"Pay via Razorpay"</strong> below to open the
                            secure payment gateway. Your purchase will be saved automatically after successful payment.
                        </p>
                        <div class="d-flex align-items-center gap-3 mt-2 pt-2" style="border-top:1px dashed #c7d2fe;">
                            <span class="d-flex align-items-center gap-1" style="font-size:.75rem;color:#6c757d;">
                                <i class="bx bx-shield-check" style="color:#696cff;"></i> {{ __('messages.ssl_badge') }}
                            </span>
                            <span class="d-flex align-items-center gap-1" style="font-size:.75rem;color:#6c757d;">
                                <i class="bx bx-check-circle" style="color:#696cff;"></i> {{ __('messages.pci_badge') }}
                            </span>
                            <span class="d-flex align-items-center gap-1" style="font-size:.75rem;color:#6c757d;">
                                <i class="bx bx-credit-card" style="color:#696cff;"></i> {{ __('messages.cards_upi_badge') }}
                            </span>
                        </div>
                    </div>
                </div>

                @error('razorpay')
                    <div class="alert alert-danger d-flex align-items-center mt-2 gap-2 px-3 py-2">
                        <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                {{-- Hidden Razorpay payment response fields --}}
                <input type="hidden" id="razorpay_order_id" name="razorpay_order_id">
                <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id">
                <input type="hidden" id="razorpay_signature" name="razorpay_signature">
                {{-- Supplier currency exchange rate (updated via JS when supplier changes) --}}
                <input type="hidden" id="purchase_exchange_rate" name="exchange_rate"
                    value="{{ old('exchange_rate', $purchase->exchange_rate ?? 1) }}">
                <input type="hidden" id="purchase_currency_id" name="currency_id"
                    value="{{ old('currency_id', $purchase->currency_id ?? '') }}">
            </div>
        </div>

    </div>

    {{-- Right: Products & Totals --}}
    <div class="col-lg-9">

        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-search text-primary me-2"></i>{{ __('messages.add_products_to_order') }}</h6>
            </div>
            <div class="card-body p-4">
                {{-- Products validation error --}}
                @error('items')
                    <div class="alert alert-danger d-flex align-items-center mb-3 gap-2 px-3 py-2">
                        <i class="bx bx-error-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="mb-4">
                    <select id="productSelect" data-no-select2="1" {{ $isReturned ? 'disabled' : '' }}
                        class="form-select" style="width:100%;">
                        <option value=""></option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table-hover mb-0 table align-middle" id="purchaseItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('messages.product') }}</th>
                                <th class="text-center">{{ __('messages.qty') }}</th>
                                <th class="text-center">Purchase Price</th>
                                <th class="text-muted text-center">Discount</th>
                                <th class="text-muted text-center">Tax</th>
                                <th class="text-end">Row Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchaseItemsContainer"></tbody>
                    </table>
                </div>
                <div class="text-muted d-flex flex-column align-items-center justify-content-center py-5 text-center w-100"
                    id="emptyTableMsg">
                    <i class="bx bx-package mb-2 d-block mx-auto" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="small mb-0">{{ __('messages.no_products_added_msg') }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">{{ __('messages.order_notes') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <textarea {{ $isReturned ? 'disabled' : '' }} class="form-control" name="notes"
                            placeholder="{{ __('messages.ph_delivery_instructions') }}" rows="6">{{ old('notes', $purchase->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">{{ __('messages.calculation_summary') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.subtotal') }}</span>
                            <span class="fw-bold"
                                id="sum_subtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.total_discount_lbl') }}</span>
                            <input id="discount_amount" name="discount_amount" type="hidden"
                                value="{{ old('discount_amount', $purchase->discount_amount ?? '0.00') }}">
                            <span class="fw-bold text-danger"
                                id="lbl_discount_amount">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.total_tax_lbl') }}</span>
                            <input id="tax_amount" name="tax_amount" type="hidden"
                                value="{{ old('tax_amount', $purchase->tax_amount ?? '0.00') }}">
                            <span class="fw-bold text-success"
                                id="lbl_tax_amount">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.shipping_plus') }}</span>
                            <input {{ $isReturned ? 'disabled' : '' }} class="form-control form-control-sm text-end"
                                id="shipping_amount" min="0" name="shipping_amount" step="0.01"
                                style="width:120px;" type="number"
                                value="{{ old('shipping_amount', $purchase->shipping_amount ?? '0.00') }}">
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="fw-bold">{{ __('messages.grand_total') }}</span>
                            <span class="fw-bold text-primary fs-6"
                                id="sum_grandtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <div
                                    class="bg-danger border-danger rounded border border-opacity-25 bg-opacity-10 p-2 text-center">
                                    <div class="text-danger small fw-semibold">{{ __('messages.balance_due') }}</div>
                                    <div class="text-danger fw-bold" id="sum_due">
                                        {{ optional(current_currency())->symbol ?? '₹' }}0.00</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="bg-success border-success rounded border border-opacity-25 bg-opacity-10 p-2 text-center">
                                    <div class="text-success small fw-semibold">{{ __('messages.overpaid_lbl') }}</div>
                                    <div class="text-success fw-bold" id="sum_change">
                                        {{ optional(current_currency())->symbol ?? '₹' }}0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('purchases.index') }}">
                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
            </a>
            @if ((!isset($purchase) || !$purchase->exists) && !$isReturned)
                <button class="btn btn-outline-primary" id="btnSaveDraft" type="button">
                    <i class="bx bx-file me-1"></i> {{ __('messages.save_as_draft') }}
                </button>
            @endif
            {{-- Normal save button (hidden when Razorpay is selected) --}}
            <button {{ $isReturned ? 'disabled' : '' }} class="btn btn-primary" id="btnSavePurchase" type="submit">
                <i class="bx bx-save me-1"></i>
                {{ isset($purchase) && $purchase->exists ? 'Update Purchase Order' : 'Save Purchase Order' }}
            </button>
            {{-- Razorpay pay button (shown only when Razorpay payment method selected, create form only) --}}
            @if (!isset($purchase) || !$purchase->exists)
                <button class="btn d-none position-relative overflow-hidden" id="btnPayRazorpay" type="button"
                    style="background:linear-gradient(135deg,#696cff,#9c3fe4);
                           border:none;color:#fff;padding:.6rem 2rem;font-weight:600;
                           box-shadow:0 4px 12px rgba(105,108,255,.3);
                           transition:all .3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(105,108,255,.4)';"
                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(105,108,255,.3)';">
                    <i class="bx bx-bolt-circle me-2" style="font-size:1.1rem;"></i>
                    {{ __('messages.pay_via_razorpay') }}
                    <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-success"
                        style="font-size:.6rem;padding:.25rem .5rem;">
                        {{ __('messages.secured_badge') }}
                    </span>
                </button>
            @endif
        </div>

    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowCount = 0;
            const isReturned = {{ $isReturned ? 'true' : 'false' }};

            // ── Auto-generate Purchase No on page load ────────────────────────
            @if (!isset($purchase) || !$purchase->exists)
                function autoFillPurchaseNo() {
                    $.ajax({
                        url: "{{ route('purchases.generate-purchase-no') }}",
                        type: 'GET',
                        success: function(res) {
                            if (!$('#purchase_no').val()) {
                                $('#purchase_no').val(res.purchase_no);
                            }
                        }
                    });
                }
                autoFillPurchaseNo();
            @endif

            // ── Active currency symbol from server ───────────────────────────
            // currencySymbol / activeCurrencyExchangeRate are updated when supplier changes.
            // All product prices are entered/displayed in the SUPPLIER's currency on screen,
            // but amounts stored to DB are always in the BASE (default) currency.
            let currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';
            // On edit, initialize from stored exchange rate so re-pricing math is correct
            let activeCurrencyExchangeRate =
                {{ (float) ($purchase->exchange_rate ?? (optional(current_currency())->exchange_rate ?? 1)) }};
            // Track the previous rate so we can re-price existing rows when supplier changes
            let prevExchangeRate = activeCurrencyExchangeRate;

            // ── Helper: format a number with the active currency symbol ──────
            function fmtCurrency(amount) {
                return currencySymbol + parseFloat(amount).toFixed(2);
            }

            // ── Auto-load supplier currency on page load if supplier already selected ──
            function applyNewCurrency(symbol, code, name, rate, isDefault, currencyId) {
                const newRate = parseFloat(rate) || 1;

                // Re-price existing product rows when the supplier's currency changes
                if (prevExchangeRate !== newRate && prevExchangeRate > 0) {
                    $('#purchaseItemsContainer tr').each(function() {
                        const row = $(this);
                        const oldPrice = parseFloat(row.find('.price-input').val()) || 0;
                        const oldDisc = parseFloat(row.find('.discount-input').val()) || 0;
                        const oldTax = parseFloat(row.find('.tax-input').val()) || 0;
                        // Re-scale: screen values were in old supplier currency, convert to new
                        row.find('.price-input').val((oldPrice / prevExchangeRate * newRate).toFixed(2));
                        row.find('.discount-input').val((oldDisc / prevExchangeRate * newRate).toFixed(2));
                        row.find('.tax-input').val((oldTax / prevExchangeRate * newRate).toFixed(2));
                    });
                    // Also rescale shipping
                    const oldShipping = parseFloat($('#shipping_amount').val()) || 0;
                    if (oldShipping > 0) {
                        $('#shipping_amount').val((oldShipping / prevExchangeRate * newRate).toFixed(2));
                    }
                }

                currencySymbol = symbol;
                activeCurrencyExchangeRate = newRate;
                prevExchangeRate = newRate;

                // Keep hidden fields in sync for form submission
                $('#purchase_exchange_rate').val(newRate);
                $('#purchase_currency_id').val(currencyId || '');

                const label = symbol + ' ' + name + ' (' + code + ')' + (isDefault ? ' — default' : '');
                $('#supplierCurrencyLabel').text(label);
                const defaultCode =
                    '{{ optional(\App\Models\Currency::where('is_default', true)->first())->code ?? 'INR' }}';
                if (newRate !== 1) {
                    $('#supplierCurrencyNote').text('1 ' + defaultCode + ' = ' + newRate.toFixed(4) + ' ' + code);
                } else {
                    $('#supplierCurrencyNote').text('Same as base currency');
                }
                $('#supplierCurrencyBadge').show();
            }

            function loadSupplierCurrency(supplierId, onDone) {
                if (!supplierId) {
                    $('#supplierCurrencyBadge').hide();
                    if (typeof onDone === 'function') onDone();
                    return;
                }

                // First: read currency from the <option> data attributes (instant, no AJAX)
                const $option = $('#supplierSelect option[value="' + supplierId + '"]');
                const dataSymbol = $option.data('currency-symbol');
                const dataCode = $option.data('currency-code');
                const dataName = $option.data('currency-name');
                const dataRate = $option.data('currency-rate');
                const dataCurrId = $option.data('currency-id');

                if (dataSymbol && dataCode) {
                    applyNewCurrency(dataSymbol, dataCode, dataName, dataRate, false, dataCurrId);
                    if (typeof onDone === 'function') onDone();
                    return;
                }

                // Fallback AJAX: supplier has no currency set → fetch system default
                $.ajax({
                    url: '/suppliers/' + supplierId + '/currency',
                    type: 'GET',
                    success: function(res) {
                        if (res.success) {
                            applyNewCurrency(res.symbol, res.code, res.name, res.exchange_rate, true,
                                res.currency_id);
                        } else {
                            $('#supplierCurrencyBadge').hide();
                        }
                        if (typeof onDone === 'function') onDone();
                    },
                    error: function() {
                        $('#supplierCurrencyBadge').hide();
                        if (typeof onDone === 'function') onDone();
                    }
                });
            }

            // ── When supplier changes, fetch and apply their currency ─────────
            @if (!$isReturned)
                $('#supplierSelect').on('change', function() {
                    const supplierId = $(this).val();
                    loadSupplierCurrency(supplierId, function() {
                        // Refresh all displayed amounts with the new currency symbol & rate
                        calculateTotals();
                    });
                });

                // Auto-trigger on page load if a supplier is already selected (edit form / validation fail)
                const initialSupplierId = $('#supplierSelect').val();
                if (initialSupplierId) {
                    loadSupplierCurrency(initialSupplierId);
                }
            @endif

            $('#btnGeneratePurchaseNo').on('click', function() {
                const btn = $(this);
                const originalHtml = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                );
                $.ajax({
                    url: "{{ route('purchases.generate-no') }}",
                    type: 'GET',
                    success: function(data) {
                        $('#purchase_no').val(data.purchase_no);
                    },
                    error: function() {
                        showAdminToast('Failed to generate purchase number.', 'danger');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // ── Restore old products after validation failure ────────────────
            @if (old('items'))
                @foreach (old('items', []) as $oldIndex => $oldItem)
                    @php
                        $oldProduct = \App\Models\Product::with(['stock'])->find($oldItem['product_id'] ?? null);
                    @endphp
                    @if ($oldProduct)
                        addProductRow({
                            id: "{{ $oldProduct->id }}",
                            name: "{{ addslashes($oldProduct->name) }}",
                            sku: "{{ $oldProduct->code }}",
                            purchase_price: parseFloat(
                                "{{ old('items.' . $oldIndex . '.purchase_price', $oldProduct->purchase_price) }}"
                            ),
                            tax: 0,
                            discount: 0,
                            qty: parseInt("{{ old('items.' . $oldIndex . '.quantity', 1) }}"),
                            unit: "{{ $oldProduct->unit_code ?? 'PCS' }}",
                            image_url: "{{ $oldProduct->image ? asset('uploads/products/' . $oldProduct->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}",
                            discount_amount_raw: parseFloat(
                                "{{ old('items.' . $oldIndex . '.discount_amount', 0) }}"),
                            tax_amount_raw: parseFloat(
                                "{{ old('items.' . $oldIndex . '.tax_amount', 0) }}")
                        });
                    @endif
                @endforeach
            @elseif (isset($purchase) && $purchase->items->count() > 0)
                @foreach ($purchase->items as $item)
                    @if ($item->product)
                        addProductRow({
                            id: "{{ $item->product_id }}",
                            name: "{{ addslashes($item->product->name) }}",
                            sku: "{{ $item->product->code ?? '' }}",
                            purchase_price: parseFloat("{{ $item->purchase_price }}"),
                            tax: parseFloat(
                                "{{ $item->quantity > 0 ? $item->tax_amount / $item->quantity : 0 }}"),
                            discount: parseFloat(
                                "{{ $item->quantity > 0 ? $item->discount_amount / $item->quantity : 0 }}"
                            ),
                            qty: parseInt("{{ $item->quantity }}"),
                            unit: "{{ $item->product->unit_code ?? 'PCS' }}",
                            image_url: "{{ $item->product->image ? asset('uploads/products/' . $item->product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}"
                        });
                    @endif
                @endforeach
            @endif

            // ── Select2 AJAX Product Search ──────────────────────────────────
            $('#productSelect').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '{{ __('messages.type_product_sku_barcode') }}',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "{{ route('purchases.search-products') }}",
                    dataType: 'json',
                    delay: 200,
                    data: function(params) {
                        return {
                            query: params.term || '',
                            currency_rate: activeCurrencyExchangeRate || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(p) {
                                return {
                                    id: p.id,
                                    text: p.name,
                                    name: p.name,
                                    sku: p.sku,
                                    barcode: p.barcode,
                                    purchase_price: p.purchase_price,
                                    tax: p.tax,
                                    discount: p.discount,
                                    unit: p.unit,
                                    stock: p.stock,
                                    image_url: p.image_url,
                                    currency_symbol: p.currency_symbol
                                };
                            })
                        };
                    },
                    cache: false
                },
                templateResult: function(p) {
                    if (p.loading) {
                        return $(
                            '<span><i class="bx bx-loader-alt bx-spin me-2"></i>Searching…</span>');
                    }
                    if (!p.id) return p.text;
                    const sym = p.currency_symbol || currencySymbol;
                    const img = p.image_url || 'https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Image';
                    const stockBadge = parseFloat(p.stock) > 0 ?
                        `<span class="badge bg-success-subtle text-success">${parseFloat(p.stock).toFixed(0)} in stock</span>` :
                        `<span class="badge bg-danger-subtle text-danger">Out of stock</span>`;
                    return $(`
                        <div class="d-flex align-items-center gap-3 py-1">
                            <img src="${img}" onerror="this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Img'"
                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid rgba(0,0,0,.1);flex-shrink:0;">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:13px;">${p.name}</div>
                                <div class="text-muted" style="font-size:11px;">SKU: ${p.sku}${p.barcode ? ' &bull; ' + p.barcode : ''}</div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold text-primary" style="font-size:13px;">${sym}${parseFloat(p.purchase_price).toFixed(2)}</div>
                                <div style="font-size:11px;">${stockBadge}</div>
                            </div>
                        </div>`);
                },
                templateSelection: function(p) {
                    if (!p.id) return p.text || '{{ __('messages.type_product_sku_barcode') }}';
                    return $(
                        `<span><i class="bx bx-package me-1"></i>${p.name} <span class="text-muted small">(${p.sku})</span></span>`
                    );
                }
            });

            // Load all products immediately when dropdown opens
            $('#productSelect').on('select2:open', function() {
                setTimeout(function() {
                    var $search = $('.select2-container--open .select2-search__field');
                    if ($search.length) {
                        $search.val('').trigger('input');
                    }
                }, 50);
            });

            $('#productSelect').on('select2:select', function(e) {
                const p = e.params.data;
                if (!p || !p.id) return;

                // Duplicate check
                let isDuplicate = false;
                $('#purchaseItemsContainer tr').each(function() {
                    if ($(this).data('product-id') == p.id) {
                        isDuplicate = true;
                        return false;
                    }
                });
                if (isDuplicate) {
                    showAdminToast(`"${p.name}" is already in the order.`, 'warning');
                } else {
                    addProductRow({
                        id: p.id,
                        name: p.name,
                        sku: p.sku,
                        purchase_price: parseFloat(p.purchase_price),
                        tax: parseFloat(p.tax) || 0,
                        discount: parseFloat(p.discount) || 0,
                        unit: p.unit,
                        image_url: p.image_url,
                        qty: 1
                    });
                }

                // Reset select2 after selection
                $(this).val(null).trigger('change');
            });

            function addProductRow(p) {
                $('#emptyTableMsg').addClass('d-none');
                // Support both percentage-based tax/discount and raw amount (for old-input restore)
                let itemTax, itemDisc;
                if (typeof p.discount_amount_raw !== 'undefined') {
                    itemDisc = p.discount_amount_raw;
                    itemTax = p.tax_amount_raw;
                } else {
                    itemTax = (p.tax / 100) * p.purchase_price;
                    itemDisc = (p.discount / 100) * p.purchase_price;
                }
                const price = p.purchase_price || 0;
                $('#purchaseItemsContainer').append(`
                <tr class="item-row" data-product-id="${p.id}">
                    <td style="min-width:200px !important;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="${p.image_url}" class="pur-prod-img rounded" onerror="imgError(this)">
                            <div>
                                <div class="pur-prod-name fw-bold text-primary mb-0">${p.name}</div>
                                <div class="pur-prod-sku text-muted">SKU: ${p.sku}</div>
                            </div>
                        </div>
                        <input type="hidden" name="items[${rowCount}][product_id]" value="${p.id}">
                    </td>
                    <td class="text-center">
                        <input type="number" step="1" min="1" name="items[${rowCount}][quantity]"
                               value="${parseInt(p.qty || 1)}"
                               class="qty-input form-control form-control-sm text-center pur-qty-input"
                               ${isReturned ? 'disabled' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][purchase_price]"
                               value="${price.toFixed(2)}"
                               class="price-input form-control form-control-sm text-center pur-price-input"
                               ${isReturned ? 'disabled' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][discount_amount]"
                               value="${itemDisc.toFixed(2)}"
                               class="discount-input form-control form-control-sm text-center pur-sm-input"
                               ${isReturned ? 'disabled' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][tax_amount]"
                               value="${itemTax.toFixed(2)}"
                               class="tax-input form-control form-control-sm text-center pur-sm-input"
                               ${isReturned ? 'disabled' : ''}>
                    </td>
                    <td class="text-end fw-bold pur-subtotal-cell subtotal-cell">${fmtCurrency(0)}</td>
                    <td class="text-center">
                        ${isReturned ? '' : `
                                                                                                                                                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-row-btn"
                                                                                                                                                                                    style="width:28px;height:28px;padding:0;">
                                                                                                                                                                                <i class="bx bx-trash" style="font-size:13px;"></i>
                                                                                                                                                                            </button>`}
                    </td>
                </tr>`);
                rowCount++;
                calculateTotals();
            }

            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if ($('#purchaseItemsContainer tr').length === 0) $('#emptyTableMsg').removeClass('d-none');
                calculateTotals();
            });

            $(document).on('input change', '.qty-input, .price-input, .discount-input, .tax-input',
                calculateTotals);
            $('#discount_amount, #tax_amount, #shipping_amount, #paid_amount').on('input change', calculateTotals);

            function calculateTotals() {
                let totalSubtotal = 0,
                    sumItemTax = 0,
                    sumItemDiscount = 0;
                $('#purchaseItemsContainer tr').each(function() {
                    const row = $(this);
                    const qty = parseFloat(row.find('.qty-input').val()) || 0;
                    const price = parseFloat(row.find('.price-input').val()) || 0;
                    const disc = parseFloat(row.find('.discount-input').val()) || 0;
                    const tax = parseFloat(row.find('.tax-input').val()) || 0;
                    const rowTotal = (price + tax - disc) * qty;
                    row.find('.subtotal-cell').text(fmtCurrency(rowTotal));
                    totalSubtotal += price * qty;
                    sumItemTax += tax * qty;
                    sumItemDiscount += disc * qty;
                });
                $('#sum_subtotal').text(fmtCurrency(totalSubtotal));
                $('#discount_amount').val(sumItemDiscount.toFixed(2));
                $('#lbl_discount_amount').text(fmtCurrency(sumItemDiscount));
                $('#tax_amount').val(sumItemTax.toFixed(2));
                $('#lbl_tax_amount').text(fmtCurrency(sumItemTax));
                const globalDisc = parseFloat($('#discount_amount').val()) || 0;
                const globalTax = parseFloat($('#tax_amount').val()) || 0;
                const shipping = parseFloat($('#shipping_amount').val()) || 0;
                // Grand total in supplier's currency (for display)
                const grandTotalDisplay = totalSubtotal + globalTax + shipping - globalDisc;
                $('#sum_grandtotal').text(fmtCurrency(grandTotalDisplay));
                const paid = parseFloat($('#paid_amount').val()) || 0;
                $('#sum_due').text(fmtCurrency(Math.max(0, grandTotalDisplay - paid)));
                $('#sum_change').text(fmtCurrency(Math.max(0, paid - grandTotalDisplay)));
            }

            $('#btnSaveDraft').on('click', function(e) {
                e.preventDefault();
                $('select[name="status"]').val('draft');
                $(this).closest('form').submit();
            });

            // ── Razorpay Integration ─────────────────────────────────────────
            @if (!isset($purchase) || !$purchase->exists)
                function getCurrentGrandTotal() {
                    let totalSubtotal = 0,
                        sumItemTax = 0,
                        sumItemDiscount = 0;
                    $('#purchaseItemsContainer tr').each(function() {
                        const row = $(this);
                        const qty = parseFloat(row.find('.qty-input').val()) || 0;
                        const price = parseFloat(row.find('.price-input').val()) || 0;
                        const disc = parseFloat(row.find('.discount-input').val()) || 0;
                        const tax = parseFloat(row.find('.tax-input').val()) || 0;
                        totalSubtotal += price * qty;
                        sumItemTax += tax * qty;
                        sumItemDiscount += disc * qty;
                    });
                    const shipping = parseFloat($('#shipping_amount').val()) || 0;
                    // Return grand total in SUPPLIER currency (for display / Razorpay amount in supplier's currency)
                    return totalSubtotal + sumItemTax + shipping - sumItemDiscount;
                }

                // Toggle Razorpay button visibility based on payment method
                $('select[name="payment_method"]').on('change', function() {
                    const isRazorpay = $(this).val() === 'Razorpay';
                    $('#btnSavePurchase').toggleClass('d-none', isRazorpay);
                    $('#btnPayRazorpay').toggleClass('d-none', !isRazorpay);
                    $('#razorpay_info_box').toggleClass('d-none', !isRazorpay);
                    // When Razorpay selected, disable manual paid_amount (will be set to grand total)
                    $('#paid_amount').prop('readonly', isRazorpay);
                    if (isRazorpay) {
                        const gt = getCurrentGrandTotal();
                        $('#paid_amount').val(gt.toFixed(2));
                        calculateTotals();
                    }
                }).trigger('change'); // run on page load for old-input restore

                // When grand total changes (items/shipping) and Razorpay is selected, sync paid_amount
                const _origCalculateTotals = calculateTotals;
                calculateTotals = function() {
                    _origCalculateTotals();
                    if ($('select[name="payment_method"]').val() === 'Razorpay') {
                        const gt = getCurrentGrandTotal();
                        $('#paid_amount').val(gt.toFixed(2));
                    }
                };

                // Pay via Razorpay button click
                $('#btnPayRazorpay').on('click', function() {
                    const grandTotal = getCurrentGrandTotal();
                    if (grandTotal <= 0) {
                        showAdminToast('Please add products before paying.', 'warning');
                        return;
                    }
                    if ($('#purchaseItemsContainer tr').length === 0) {
                        showAdminToast('Please add at least one product.', 'warning');
                        return;
                    }

                    const btn = $(this);
                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Processing…');

                    // Step 1: Create Razorpay order on backend
                    $.ajax({
                        url: "{{ route('razorpay.create-order') }}",
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            amount: grandTotal
                        },
                        success: function(res) {
                            // Step 2: Open Razorpay checkout
                            const options = {
                                key: res.key_id,
                                amount: res.amount,
                                currency: res.currency,
                                name: '{{ addslashes(config('app.name')) }}',
                                description: 'Purchase Payment — ' + ($('#purchase_no')
                                    .val() || ''),
                                order_id: res.order_id,
                                prefill: {
                                    name: '{{ addslashes(auth()->user()->name ?? '') }}',
                                    email: '{{ addslashes(auth()->user()->email ?? '') }}',
                                },
                                theme: {
                                    color: '#696cff'
                                },
                                handler: function(response) {
                                    // Step 3: Store payment IDs and submit form to verify-and-store
                                    $('#razorpay_order_id').val(response
                                        .razorpay_order_id);
                                    $('#razorpay_payment_id').val(response
                                        .razorpay_payment_id);
                                    $('#razorpay_signature').val(response
                                        .razorpay_signature);
                                    $('#paid_amount').val(grandTotal.toFixed(2));

                                    // Change form action to Razorpay verify-and-store route
                                    const $form = btn.closest('form');
                                    $form.attr('action',
                                        "{{ route('razorpay.verify-and-store') }}"
                                    );
                                    $form.removeAttr('novalidate');
                                    $form.submit();
                                },
                                modal: {
                                    ondismiss: function() {
                                        btn.prop('disabled', false)
                                            .html(
                                                '<i class="bx bx-rupee me-1"></i> Pay via Razorpay'
                                            );
                                        showAdminToast('Payment cancelled.', 'warning');
                                    }
                                }
                            };
                            const rzp = new Razorpay(options);
                            rzp.on('payment.failed', function(response) {
                                showAdminToast('Payment failed: ' + response.error
                                    .description, 'danger');
                                btn.prop('disabled', false)
                                    .html(
                                        '<i class="bx bx-rupee me-1"></i> Pay via Razorpay'
                                    );
                            });
                            rzp.open();
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message ||
                                'Could not create Razorpay order. Check credentials.';
                            showAdminToast(msg, 'danger');
                            btn.prop('disabled', false)
                                .html('<i class="bx bx-rupee me-1"></i> Pay via Razorpay');
                        }
                    });
                });
            @endif
            // ── End Razorpay Integration ─────────────────────────────────────

            // Initial calculation after any old items are loaded
            calculateTotals();
        });
    </script>

    {{-- Load Razorpay JS SDK only on create form --}}
    @if (!isset($purchase) || !$purchase->exists)
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
@endpush
