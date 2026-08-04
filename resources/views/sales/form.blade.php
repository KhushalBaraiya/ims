@php
    $isReturned = isset($sale) && $sale->returns->isNotEmpty();
    $showOutOfStock = $showOutOfStock ?? false;
@endphp

<style>
    /* --------------------------------------------------------------
       SALES FORM � Product Table  (light + dark)
    -------------------------------------------------------------- */
    #saleItemsTable th,
    #saleItemsTable td {
        padding: 8px 14px !important;
        font-size: 13px !important;
    }

    .sale-prod-img {
        width: 32px !important;
        height: 32px !important;
        object-fit: cover !important;
        border-radius: 6px !important;
        border: 1px solid rgba(0, 0, 0, .08);
    }

    [data-bs-theme="dark"] .sale-prod-img {
        border-color: rgba(255, 255, 255, .1);
    }

    .sale-prod-name {
        font-size: 12.5px !important;
        line-height: 1.2 !important;
    }

    .sale-prod-sku {
        font-size: 10.5px !important;
        line-height: 1.1 !important;
    }

    .sale-stock-badge {
        font-size: 10.5px !important;
        padding: 4px 7px !important;
    }

    .sale-qty-input {
        width: 60px !important;
        height: 28px !important;
        font-size: 12.5px !important;
        padding: 2px 4px !important;
        margin: auto !important;
    }

    /* Hide native number input spinners so users can type multi-digit values easily */
    .sale-qty-input::-webkit-outer-spin-button,
    .sale-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .sale-qty-input {
        -moz-appearance: textfield;
    }

    .sale-price-cell {
        font-size: 12.5px !important;
    }

    .sale-compact-text {
        font-size: 10.5px !important;
        line-height: 1.3 !important;
    }

    .sale-subtotal-cell {
        font-size: 13px !important;
    }

    /* -- Autocomplete Container ----------------------------------- */
    #autocompleteResults {
        background: #fff;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        box-shadow: 0 10px 32px rgba(0, 0, 0, .12) !important;
        overflow: hidden !important;
    }

    [data-bs-theme="dark"] #autocompleteResults {
        background: #2b2c40 !important;
        border-color: rgba(255, 255, 255, .12) !important;
        box-shadow: 0 10px 32px rgba(0, 0, 0, .4) !important;
    }

    /* -- Each Item ------------------------------------------------ */
    .autocomplete-item {
        transition: background .13s ease;
        border-bottom: 1px solid #f1f5f9 !important;
        background: transparent;
    }

    .autocomplete-item:last-child {
        border-bottom: none !important;
    }

    [data-bs-theme="dark"] .autocomplete-item {
        border-bottom-color: rgba(255, 255, 255, .07) !important;
    }

    /* Hover � in-stock only */
    .autocomplete-item:not(.oos-item):hover {
        background: rgba(105, 108, 255, .08) !important;
    }

    [data-bs-theme="dark"] .autocomplete-item:not(.oos-item):hover {
        background: rgba(105, 108, 255, .15) !important;
    }

    /* -- Product Image -------------------------------------------- */
    .autocomplete-item .ac-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 7px;
        flex-shrink: 0;
        border: 1px solid rgba(0, 0, 0, .08);
    }

    [data-bs-theme="dark"] .autocomplete-item .ac-img {
        border-color: rgba(255, 255, 255, .12);
    }

    /* -- Product Name --------------------------------------------- */
    .autocomplete-item .ac-name {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.3;
        color: #3d4150;
    }

    [data-bs-theme="dark"] .autocomplete-item .ac-name {
        color: #cfd3ec;
    }

    /* -- SKU ------------------------------------------------------ */
    .autocomplete-item .ac-sku {
        font-size: 11px;
        color: #94a3b8;
        line-height: 1.2;
    }

    [data-bs-theme="dark"] .autocomplete-item .ac-sku {
        color: #7983bb;
    }

    /* -- Price ---------------------------------------------------- */
    .autocomplete-item .ac-price {
        font-size: 13px;
        font-weight: 700;
        color: #696cff;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .autocomplete-item .ac-price {
        color: #8b8fff;
    }

    /* -- Stock text ----------------------------------------------- */
    .autocomplete-item .ac-stock {
        font-size: 11px;
        color: #94a3b8;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .autocomplete-item .ac-stock {
        color: #7983bb;
    }

    /* -- Out-of-Stock Item ---------------------------------------- */
    .autocomplete-item.oos-item {
        background: #fafafa !important;
        cursor: not-allowed !important;
        pointer-events: none;
    }

    [data-bs-theme="dark"] .autocomplete-item.oos-item {
        background: rgba(255, 255, 255, .03) !important;
    }

    .autocomplete-item.oos-item .ac-img {
        opacity: .4;
        filter: grayscale(70%);
    }

    .autocomplete-item.oos-item .ac-name {
        color: #aab0c0 !important;
    }

    .autocomplete-item.oos-item .ac-price {
        color: #aab0c0 !important;
    }

    [data-bs-theme="dark"] .autocomplete-item.oos-item .ac-name {
        color: #4f5570 !important;
    }

    [data-bs-theme="dark"] .autocomplete-item.oos-item .ac-price {
        color: #4f5570 !important;
    }

    /* -- Out-of-Stock Badge --------------------------------------- */
    .oos-badge {
        font-size: 10px !important;
        padding: 2px 7px !important;
        border-radius: 20px !important;
        letter-spacing: .3px;
    }

    /* -- No-results message --------------------------------------- */
    .ac-no-results {
        color: #94a3b8;
    }

    [data-bs-theme="dark"] .ac-no-results {
        color: #7983bb;
    }
</style>

@csrf

{{-- Validation Errors --}}
@if ($errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
        <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
        <span>{{ $errors->first('stock_error') }}</span>
    </div>
@endif
@if ($errors->any() && !$errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
        <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<div class="row g-4">

    {{-- -- Left Column: Invoice Info + Payment -- --}}
    <div class="col-lg-3">

        {{-- Invoice Info Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-file text-primary me-2"></i>{{ __('messages.invoice_info') }}</h6>
            </div>
            <div class="card-body p-4">

                {{-- Invoice Number --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.set_invoice_number') }}</label>
                    @if (isset($sale) && $sale->exists)
                        <input class="form-control fw-bold text-primary bg-light" id="invoice_no" name="invoice_no"
                            readonly type="text" value="{{ $sale->invoice_no }}">
                    @else
                        <div class="input-group">
                            <input {{ $isReturned ? 'disabled' : '' }} class="form-control fw-semibold" id="invoice_no"
                                name="invoice_no" placeholder="{{ __('messages.set_invoice_number') }}" type="text"
                                value="{{ old('invoice_no') }}">
                            <button {{ $isReturned ? 'disabled' : '' }} class="btn btn-outline-primary"
                                id="generateInvoiceNoBtn" title="{{ __('messages.set_invoice_number_hint') }}"
                                type="button">
                                <i class="bx bx-revision"></i>
                            </button>
                        </div>
                        <div class="form-text">{{ __('messages.set_invoice_number_hint') }}</div>
                    @endif
                </div>

                {{-- Invoice Date --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.set_invoice_date') }} <span
                            class="text-danger">*</span></label>
                    <input {{ $isReturned ? 'disabled' : '' }}
                        class="form-control flatpickr-date @error('invoice_date') is-invalid @enderror"
                        name="invoice_date" required type="date"
                        value="{{ old('invoice_date', $sale->invoice_date ?? date('Y-m-d')) }}">
                    @error('invoice_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Customer --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.customer') }} <span
                            class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('customer_id') is-invalid @enderror" name="customer_id" required>
                        <option value="">{{ __('messages.select_customer') }}</option>
                        @foreach ($customers as $c)
                            <option {{ old('customer_id', $sale->customer_id ?? '') == $c->id ? 'selected' : '' }}
                                value="{{ $c->id }}">
                                {{ $c->name }}{{ $c->phone ? ' (' . $c->phone . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sales Person: hidden + auto-bound for sales.own users --}}
                @if ($isSalesOwn ?? false)
                    <input name="sales_person_id" type="hidden" value="{{ auth()->id() }}">
                @else
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('messages.set_sales_person') }}</label>
                        <select {{ $isReturned ? 'disabled' : '' }}
                            class="form-select @error('sales_person_id') is-invalid @enderror" name="sales_person_id">
                            <option value="">{{ __('messages.select_sales_person') }}</option>
                            @foreach ($salesPersons as $sp)
                                <option
                                    {{ old('sales_person_id', $sale->sales_person_id ?? auth()->id()) == $sp->id ? 'selected' : '' }}
                                    value="{{ $sp->id }}">
                                    {{ $sp->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sales_person_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- Status --}}
                <div class="mb-0">
                    <label class="form-label fw-semibold">{{ __('messages.status') }} <span
                            class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('status') is-invalid @enderror" name="status" required>
                        @foreach (['Completed', 'Pending', 'Draft', 'Repair', 'Ordered'] as $statusOpt)
                            <option {{ old('status', $sale->status ?? 'Completed') === $statusOpt ? 'selected' : '' }}
                                value="{{ $statusOpt }}">
                                {{ $statusOpt }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Payment Details Card --}}
        <div class="card shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-credit-card text-success me-2"></i>{{ __('messages.payment_details') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.payment_method') }} <span
                            class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('payment_method') is-invalid @enderror" name="payment_method"
                        required>
                        <option value="">{{ __('messages.select_method') }}</option>
                        <option
                            {{ old('payment_method', $sale->payment_method ?? 'Cash') === 'Cash' ? 'selected' : '' }}
                            value="Cash">
                            ?? Cash
                        </option>
                        <option
                            {{ old('payment_method', $sale->payment_method ?? '') === 'Razorpay' ? 'selected' : '' }}
                            value="Razorpay">
                            ? Razorpay (Online Payment)
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
                        value="{{ old('paid_amount', $sale->paid_amount ?? '0.00') }}">
                    @error('paid_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    {{-- -- Right Column: Products + Totals -- --}}
    <div class="col-lg-9">

        {{-- Product Search & Line Items --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="bx bx-search text-primary me-2"></i>Add Products to Invoice
                </h6>
            </div>
            <div class="card-body p-4">

                @error('items')
                    <div class="alert alert-danger d-flex align-items-center mb-3 gap-2 px-3 py-2">
                        <i class="bx bx-error-circle"></i><span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="mb-4">
                    <select id="productSelect" data-no-select2="1" {{ $isReturned ? 'disabled' : '' }}
                        class="form-select" style="width:100%;">
                        <option value=""></option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table-hover mb-0 table align-middle" id="saleItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('messages.product') }}</th>
                                <th class="text-center" style="width:110px;">Qty</th>
                                <th class="text-center" style="width:160px;">Price / Disc / Tax</th>
                                <th class="text-end" style="width:110px;">Row Total</th>
                                <th style="width:40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsContainer"></tbody>
                    </table>
                    <div class="text-muted d-flex flex-column align-items-center justify-content-center py-5 text-center"
                        id="emptyTableMsg">
                        <i class="bx bx-cart mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                        <p class="small mb-0">{{ __('messages.set_no_products_added') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes + Calculation Summary --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Invoice Notes</h6>
                    </div>
                    <div class="card-body p-3">
                        <textarea {{ $isReturned ? 'disabled' : '' }} class="form-control" name="notes"
                            placeholder="{{ __('messages.ph_payment_notes') }}" rows="6">{{ old('notes', $sale->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">{{ __('messages.calculation_summary') }}</h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Subtotal (read-only) --}}
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.set_subtotal_rows') }}</span>
                            <span class="fw-bold"
                                id="sum_subtotal">{{ optional(current_currency())->symbol ?? '?' }}0.00</span>
                        </div>

                        {{-- Global Discount --}}
                        <div class="border-bottom py-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-semibold">Global Discount (-)</span>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check form-check-inline mb-0">
                                        <input {{ $isReturned ? 'disabled' : '' }}
                                            {{ old('discount_type', $sale->discount_type ?? 'fixed') === 'fixed' ? 'checked' : '' }}
                                            class="form-check-input" id="discType{{ __('messages.set_fixed') }}"
                                            name="discount_type" type="radio" value="fixed">
                                        <label class="form-check-label small"
                                            for="discType{{ __('messages.set_fixed') }}">{{ __('messages.set_fixed') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input {{ $isReturned ? 'disabled' : '' }}
                                            {{ old('discount_type', $sale->discount_type ?? 'fixed') === 'percentage' ? 'checked' : '' }}
                                            class="form-check-input" id="discTypePct" name="discount_type"
                                            type="radio" value="percentage">
                                        <label class="form-check-label small" for="discTypePct">%</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small"
                                    id="discountLabel">{{ __('messages.set_amount') }}</span>
                                <input {{ $isReturned ? 'disabled' : '' }}
                                    class="form-control form-control-sm text-end" id="discount_value" min="0"
                                    name="discount_value" step="0.01" style="width:120px;" type="number"
                                    value="{{ old('discount_value', $sale->discount_value ?? '0.00') }}">
                            </div>
                            <input id="discount_amount" name="discount_amount" type="hidden" value="0.00">
                            <div class="mt-1 text-end">
                                <small class="text-danger fw-semibold"
                                    id="lbl_discount_amount">-{{ optional(current_currency())->symbol ?? '?' }}0.00</small>
                            </div>
                        </div>

                        {{-- Global Tax --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Global Tax % (+)</span>
                            <div class="d-flex align-items-center gap-2">
                                <input {{ $isReturned ? 'disabled' : '' }}
                                    class="form-control form-control-sm text-end" id="tax_percentage" max="100"
                                    min="0" name="tax_percentage" step="0.01" style="width:80px;"
                                    type="number"
                                    value="{{ old('tax_percentage', $sale->tax_percentage ?? '0.00') }}">
                                <span class="text-muted small">%</span>
                            </div>
                        </div>
                        <input id="tax_amount" name="tax_amount" type="hidden" value="0.00">
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="text-muted small">Tax {{ __('messages.set_amount') }}</span>
                            <span class="fw-semibold text-warning"
                                id="lbl_tax_amount">+{{ optional(current_currency())->symbol ?? '?' }}0.00</span>
                        </div>

                        {{-- Shipping --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.shipping_plus') }}</span>
                            <input {{ $isReturned ? 'disabled' : '' }} class="form-control form-control-sm text-end"
                                id="shipping_amount" min="0" name="shipping_amount" step="0.01"
                                style="width:120px;" type="number"
                                value="{{ old('shipping_amount', $sale->shipping_amount ?? '0.00') }}">
                        </div>

                        {{-- {{ __('messages.grand_total') }} --}}
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="fw-bold">{{ __('messages.grand_total') }}</span>
                            <span class="fw-bold text-primary fs-6"
                                id="sum_grandtotal">{{ optional(current_currency())->symbol ?? '?' }}0.00</span>
                        </div>

                        {{-- Due / Change --}}
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <div
                                    class="bg-danger border-danger rounded border border-opacity-25 bg-opacity-10 p-2 text-center">
                                    <div class="text-danger small fw-semibold">{{ __('messages.due_amount') }}</div>
                                    <div class="text-danger fw-bold" id="sum_due">
                                        {{ optional(current_currency())->symbol ?? '?' }}0.00</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="bg-success border-success rounded border border-opacity-25 bg-opacity-10 p-2 text-center">
                                    <div class="text-success small fw-semibold">{{ __('messages.set_change_return') }}
                                    </div>
                                    <div class="text-success fw-bold" id="sum_change">
                                        {{ optional(current_currency())->symbol ?? '?' }}0.00</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="d-flex justify-content-end mt-4 gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('sales.index') }}">
                <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
            </a>
            @can('stocks.create')
                <button class="btn btn-outline-warning" id="btnAdjustStock"
                    title="Open Stock Adjustment with current invoice products" type="button">
                    <i class="bx bx-slider me-1"></i> Adjust Stock
                </button>
            @endcan
            @if (!isset($sale) || !$sale->exists)
                <button {{ $isReturned ? 'disabled' : '' }} class="btn btn-outline-primary" id="btnSaveDraft"
                    type="button">
                    <i class="bx bx-file me-1"></i> {{ __('messages.set_save_as_draft') }}
                </button>
            @endif
            <button {{ $isReturned ? 'disabled' : '' }} class="btn btn-primary" type="submit">
                <i class="bx bx-save me-1"></i>
                {{ isset($sale) ? 'Update Invoice' : 'Generate Invoice' }}
            </button>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowCount = 0;
            const isReturned = {{ $isReturned ? 'true' : 'false' }};
            const showOutOfStock = {{ $showOutOfStock ? 'true' : 'false' }};
            const sym = '{{ addslashes(optional(current_currency())->symbol ?? '?') }}';

            function fmt(n) {
                return sym + parseFloat(n).to{{ __('messages.set_fixed') }}(2);
            }

            // -- Auto-generate Invoice No on page load -------------------------
            @if (!isset($sale) || !$sale->exists)
                (function autoFillInvoiceNo() {
                    $.get("{{ route('sales.generate-invoice-no') }}", function(res) {
                        if (!$('#invoice_no').val()) $('#invoice_no').val(res.invoice_no);
                    });
                })();
            @endif

            // -- Refresh button ------------------------------------------------
            $('#generateInvoiceNoBtn').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i>');
                $.get("{{ route('sales.generate-invoice-no') }}", function(res) {
                    $('#invoice_no').val(res.invoice_no).focus();
                }).always(function() {
                    btn.prop('disabled', false).html('<i class="bx bx-revision"></i>');
                });
            });

            // -- Restore items: old() after validation fail ? edit mode ? nothing --
            @if (old('items'))
                {{-- Validation failed: re-hydrate from old() input --}}
                @foreach (old('items', []) as $oldIndex => $oldItem)
                    @php
                        $oldProduct = \App\Models\Product::with('stock')->find($oldItem['product_id'] ?? null);
                    @endphp
                    @if ($oldProduct)
                        addProductRow({
                            id: "{{ $oldProduct->id }}",
                            name: "{{ addslashes($oldProduct->name) }}",
                            sku: "{{ $oldProduct->code }}",
                            stock: parseFloat("{{ $oldProduct->stock->quantity ?? 0 }}"),
                            price: parseFloat(
                                "{{ old('items.' . $oldIndex . '.unit_price', $oldProduct->selling_price) }}"
                            ),
                            taxAmt: parseFloat("{{ old('items.' . $oldIndex . '.tax_amount', 0) }}"),
                            discAmt: parseFloat(
                                "{{ old('items.' . $oldIndex . '.discount_amount', 0) }}"),
                            qty: parseInt("{{ old('items.' . $oldIndex . '.quantity', 1) }}"),
                            unit: "{{ $oldProduct->unit_code ?? 'PCS' }}",
                            image_url: "{{ $oldProduct->image ? asset('uploads/products/' . $oldProduct->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}"
                        });
                    @endif
                @endforeach
            @elseif (isset($sale) && $sale->items->count() > 0)
                {{-- Edit mode: populate from saved sale items --}}
                @foreach ($sale->items as $item)
                    addProductRow({
                        id: "{{ $item->product_id }}",
                        name: "{{ addslashes($item->product->name) }}",
                        sku: "{{ $item->product->code }}",
                        stock: parseFloat(
                            "{{ ($item->product->stock->quantity ?? 0) + $item->quantity }}"),
                        price: parseFloat("{{ $item->unit_price }}"),
                        taxAmt: parseFloat("{{ $item->tax_amount }}"),
                        discAmt: parseFloat("{{ $item->discount_amount }}"),
                        qty: parseInt("{{ $item->quantity }}"),
                        unit: "{{ $item->product->unit_code ?? 'PCS' }}",
                        image_url: "{{ $item->product->image ? asset('uploads/products/' . $item->product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}"
                    });
                @endforeach
            @endif

            // -- Select2 AJAX Product Search ----------------------------------
            $('#productSelect').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Type Product Name, SKU, or Scan Barcode...',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "{{ route('products.search') }}",
                    dataType: 'json',
                    delay: 200,
                    data: function(params) {
                        return {
                            query: params.term || ''
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
                                    stock: p.stock,
                                    out_of_stock: p.out_of_stock,
                                    price: p.price,
                                    tax_percent: p.tax_percent,
                                    discount_amount: p.discount_amount,
                                    unit: p.unit,
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
                            '<span><i class="bx bx-loader-alt bx-spin me-2"></i>Searching�</span>');
                    }
                    if (!p.id) return p.text;
                    const img = p.image_url || 'https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Img';
                    const itemSym = p.currency_symbol || sym;
                    const stockBadge = p.out_of_stock ?
                        `<span class="badge bg-danger-subtle text-danger">{{ __('messages.out_of_stock') }}</span>` :
                        `<span class="badge bg-success-subtle text-success">${parseFloat(p.stock).toFixed(0)} {{ __('messages.in_stock') }}</span>`;
                    const nameStyle = p.out_of_stock ? 'color:#aab0c0;' : '';
                    return $(`
                        <div class="d-flex align-items-center gap-3 py-1" style="${p.out_of_stock ? 'opacity:.6;' : ''}">
                            <img src="${img}" onerror="this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Img'"
                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid rgba(0,0,0,.1);flex-shrink:0;">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:13px;${nameStyle}">${p.name}</div>
                                <div class="text-muted" style="font-size:11px;">SKU: ${p.sku}${p.barcode ? ' &bull; ' + p.barcode : ''}</div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold text-primary" style="font-size:13px;">${itemSym}${parseFloat(p.price).to{{ __('messages.set_fixed') }}(2)}</div>
                                <div style="font-size:11px;">${stockBadge}</div>
                            </div>
                        </div>`);
                },
                templateSelection: function(p) {
                    if (!p.id) return p.text || 'Type Product Name, SKU, or Scan Barcode...';
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

                // Guard: out of stock
                if (p.out_of_stock) {
                    showAdminToast(`"${p.name}" {{ __('messages.out_of_stock') }}.`, 'warning');
                    $(this).val(null).trigger('change');
                    return;
                }

                // Duplicate check
                let isDuplicate = false;
                $('#invoiceItemsContainer tr').each(function() {
                    if ($(this).data('product-id') == p.id) {
                        isDuplicate = true;
                        return false;
                    }
                });
                if (isDuplicate) {
                    showAdminToast(`"${p.name}" already added.`, 'warning');
                    $(this).val(null).trigger('change');
                    return;
                }

                const priceAfterDiscount = parseFloat(p.price) - parseFloat(p.discount_amount || 0);
                const taxAmt = parseFloat(((parseFloat(p.tax_percent) / 100) * priceAfterDiscount)
                    .to{{ __('messages.set_fixed') }}(
                        2));
                addProductRow({
                    id: p.id,
                    name: p.name,
                    sku: p.sku,
                    stock: parseFloat(p.stock),
                    price: parseFloat(p.price),
                    taxPercent: parseFloat(p.tax_percent),
                    taxAmt: taxAmt,
                    discAmt: parseFloat(p.discount_amount || 0),
                    unit: p.unit,
                    image_url: p.image_url,
                    qty: 1
                });

                $(this).val(null).trigger('change');
            });

            // -- Build row -----------------------------------------------------
            function addProductRow(p) {
                $('#emptyTableMsg').addClass('d-none');
                const taxAmt = typeof p.taxAmt !== 'undefined' ? p.taxAmt : (p.tax || 0);
                const discAmt = typeof p.discAmt !== 'undefined' ? p.discAmt : (p.disc || 0);
                const netPrice = parseFloat(p.price) + taxAmt - discAmt;
                const stockQty = parseInt(p.stock) || 0;
                const qty = parseInt(p.qty || 1);

                $('#invoiceItemsContainer').append(
                    '<tr class="item-row" data-product-id="' + p.id + '">' +

                    // -- Col 1: Product (img + name + SKU) --
                    '<td style="min-width:200px;">' +
                    '<div class="d-flex align-items-center gap-2">' +
                    '<img src="' + p.image_url + '" class="sale-prod-img rounded" onerror="imgError(this)">' +
                    '<div class="overflow-hidden">' +
                    '<div class="sale-prod-name fw-semibold text-dark text-truncate">' + p.name + '</div>' +
                    '<div class="sale-prod-sku text-muted">SKU: ' + p.sku + '</div>' +
                    '</div>' +
                    '</div>' +
                    '<input type="hidden" name="items[' + rowCount + '][product_id]"  value="' + p.id + '">' +
                    '<input type="hidden" name="items[' + rowCount +
                    '][discount_amount]" class="disc-hidden" value="' + discAmt
                    .to{{ __('messages.set_fixed') }}(2) + '">' +
                    '<input type="hidden" name="items[' + rowCount +
                    '][tax_amount]"      class="tax-hidden"  value="' + taxAmt
                    .to{{ __('messages.set_fixed') }}(2) + '">' +
                    '</td>' +

                    // -- Col 2: Qty (stock badge above, input below) --
                    '<td class="text-center stock-cell" data-max="' + stockQty + '">' +
                    '<div class="mb-1">' +
                    '<span class="badge ' + (stockQty > 0 ? 'bg-label-success' : 'bg-label-danger') +
                    ' sale-stock-badge">' +
                    (stockQty > 0 ? stockQty + ' {{ __('messages.avail') }}.' :
                        '{{ __('messages.out_of_stock') }}') +
                    '</span>' +
                    '</div>' +
                    '<input type="number" inputmode="numeric" pattern="[0-9]*" step="1" min="1" ' +
                    'name="items[' + rowCount + '][quantity]" value="' + qty + '" ' +
                    'class="qty-input form-control form-control-sm text-center sale-qty-input" ' +
                    (isReturned ? 'disabled' : '') + '>' +
                    '</td>' +

                    // -- Col 3: Net Price / Disc / Tax (read-only) --
                    '<td class="text-center">' +
                    '<div class="fw-semibold text-primary sale-price-cell">' + fmt(p.price) + '</div>' +
                    '<input type="hidden" name="items[' + rowCount +
                    '][unit_price]" class="price-input" value="' + parseFloat(p.price)
                    .to{{ __('messages.set_fixed') }}(2) + '">' +
                    '<div class="text-danger sale-compact-text">-' + fmt(discAmt) + ' disc</div>' +
                    '<div class="text-success sale-compact-text">+' + fmt(taxAmt) + ' tax</div>' +
                    '</td>' +

                    // -- Col 4: Row Total --
                    '<td class="text-end fw-bold subtotal-cell sale-subtotal-cell">' + fmt(netPrice * qty) +
                    '</td>' +

                    // -- Col 5: Remove --
                    '<td class="text-center">' +
                    (isReturned ? '' :
                        '<button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-row-btn" style="width:28px;height:28px;padding:0;">' +
                        '<i class="bx bx-trash" style="font-size:13px;"></i></button>'
                    ) +
                    '</td>' +
                    '</tr>'
                );
                rowCount++;
                calculateTotals();
            }

            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if ($('#invoiceItemsContainer tr').length === 0) $('#emptyTableMsg').removeClass('d-none');
                calculateTotals();
            });

            // -- Recalculate on any input change ------------------------------
            $(document).on('input change', '.qty-input, .price-input', function() {
                const row = $(this).closest('tr');
                const qtyInp = row.find('.qty-input');
                let qty = parseInt(qtyInp.val()) || 0;
                const maxSt = parseInt(row.find('.stock-cell').data('max'));
                if (qty > maxSt) {
                    qtyInp.val(maxSt);
                    qty = maxSt;
                    showAdminToast(`Cannot sell more than stock (${maxSt}).`, 'error');
                }
                if (qty <= 0) qtyInp.val(1);
                calculateTotals();
            });

            $('#discount_value, #tax_percentage, #shipping_amount, #paid_amount').on('input change',
                calculateTotals);
            $('input[name="discount_type"]').on('change', function() {
                $('#discountLabel').text($(this).val() === 'percentage' ? 'Percentage (%)' :
                    '{{ __('messages.set_amount') }}');
                calculateTotals();
            });
            // Set initial label
            $('#discountLabel').text($('input[name="discount_type"]:checked').val() === 'percentage' ?
                'Percentage (%)' : '{{ __('messages.set_amount') }}');

            // -- Core calculation engine ---------------------------------------
            // 1. Compute row totals: price - discount, then + tax on discounted price
            // 2. Apply global discount (fixed or %) on subtotal
            // 3. Apply global tax (%) on (subtotal - discount)
            // 4. Add shipping ? grand total
            function calculateTotals() {
                let subtotal = 0;

                $('#invoiceItemsContainer tr').each(function() {
                    const row = $(this);
                    const qty = parseFloat(row.find('.qty-input').val()) || 0;
                    const price = parseFloat(row.find('.price-input').val()) || 0;
                    const discU = parseFloat(row.find('.disc-hidden').val()) || 0;
                    const taxU = parseFloat(row.find('.tax-hidden').val()) || 0;

                    // Row calculation: (price - discount + tax) � qty
                    const priceAfterDiscount = price - discU;
                    const rowTot = (priceAfterDiscount + taxU) * qty;
                    row.find('.subtotal-cell').text(fmt(rowTot));
                    subtotal += price * qty; // subtotal = sum of (qty � unit_price), raw
                });

                $('#sum_subtotal').text(fmt(subtotal));

                // Global discount
                const discType = $('input[name="discount_type"]:checked').val() || 'fixed';
                let discVal = parseFloat($('#discount_value').val()) || 0;
                if (discType === 'percentage' && discVal > 100) {
                    $('#discount_value').val(100);
                    discVal = 100;
                    showAdminToast('Discount percentage cannot exceed 100%.', 'error');
                } else if (discType === 'fixed' && discVal > subtotal) {
                    $('#discount_value').val(subtotal.to{{ __('messages.set_fixed') }}(2));
                    discVal = subtotal;
                    showAdminToast('Discount cannot exceed the subtotal.', 'error');
                }
                const discAmt = discType === 'percentage' ? subtotal * discVal / 100 : discVal;
                const afterDisc = Math.max(0, subtotal - discAmt);

                $('#discount_amount').val(discAmt.to{{ __('messages.set_fixed') }}(2));
                $('#lbl_discount_amount').text('-' + fmt(discAmt));

                // Global tax on (subtotal - discount)
                const taxPct = parseFloat($('#tax_percentage').val()) || 0;
                const taxAmt = afterDisc * taxPct / 100;
                $('#tax_amount').val(taxAmt.to{{ __('messages.set_fixed') }}(2));
                $('#lbl_tax_amount').text('+' + fmt(taxAmt));

                // Shipping + grand total
                const shipping = parseFloat($('#shipping_amount').val()) || 0;
                const grandTotal = afterDisc + taxAmt + shipping;
                $('#sum_grandtotal').text(fmt(grandTotal));

                // Due / Change
                const paid = parseFloat($('#paid_amount').val()) || 0;
                const due = Math.max(0, grandTotal - paid);
                const change = Math.max(0, paid - grandTotal);
                $('#sum_due').text(fmt(due));
                $('#sum_change').text(fmt(change));
            }

            $('#btnSaveDraft').on('click', function(e) {
                e.preventDefault();
                $('select[name="status"]').val('Draft');
                $(this).closest('form').submit();
            });

            // -- Adjust Stock: collect current invoice product IDs ? open adjust page --
            $('#btnAdjustStock').on('click', function() {
                const ids = [];
                $('#invoiceItemsContainer tr[data-product-id]').each(function() {
                    const id = $(this).data('product-id');
                    if (id) ids.push(id);
                });

                if (ids.length === 0) {
                    showAdminToast('Please add at least one product to the invoice first.', 'warning');
                    return;
                }

                const params = ids.map(id => `products[]=${encodeURIComponent(id)}`).join('&');
                window.open(`{{ route('stocks.adjust') }}?${params}`, '_blank');
            });

            // Run once to initialise display
            calculateTotals();
        });
    </script>
@endpush
