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
                <h6 class="fw-semibold mb-0"><i class="bx bx-info-circle text-primary me-2"></i>{{ __("messages.order_info") }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Purchase No <span class="text-danger">*</span></label>
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
                    <label class="form-label fw-semibold">Purchase Date <span class="text-danger">*</span></label>
                    <input {{ $isReturned ? 'disabled' : '' }}
                        class="form-control flatpickr-date @error('purchase_date') is-invalid @enderror"
                        name="purchase_date" required type="date"
                        value="{{ old('purchase_date', $purchase->purchase_date ?? date('Y-m-d')) }}">
                    @error('purchase_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('supplier_id') is-invalid @enderror" name="supplier_id" required>
                        <option value="">{{ __('messages.select_supplier') }}</option>
                        @foreach ($suppliers as $s)
                            <option {{ old('supplier_id', $purchase->supplier_id ?? '') == $s->id ? 'selected' : '' }}
                                value="{{ $s->id }}">
                                {{ $s->name }} {{ $s->phone ? '(' . $s->phone . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Reference / PO No</label>
                    <input {{ $isReturned ? 'disabled' : '' }} class="form-control" name="reference_no"
                        placeholder="Optional reference..." type="text"
                        value="{{ old('reference_no', $purchase->reference_no ?? '') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
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
                <h6 class="fw-semibold mb-0"><i class="bx bx-credit-card text-success me-2"></i>{{ __("messages.payment_details") }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                    <select {{ $isReturned ? 'disabled' : '' }}
                        class="form-select @error('payment_method') is-invalid @enderror" name="payment_method"
                        required>
                        <option value="">{{ __('messages.select_payment_method') }}</option>
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? 'Cash') === 'Cash' ? 'selected' : '' }}
                            value="Cash">
                            Cash</option>
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Bank Transfer' ? 'selected' : '' }}
                            value="Bank Transfer">
                            Bank Transfer</option>
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Card' ? 'selected' : '' }}
                            value="Card">
                            Credit/Debit Card</option>
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'UPI / QR' ? 'selected' : '' }}
                            value="UPI / QR">
                            UPI / QR Code</option>
                        <option
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Cheque' ? 'selected' : '' }}
                            value="Cheque">
                            Cheque</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">{{ __('messages.paid_amount_field') }} <span class="text-danger">*</span></label>
                    <input {{ $isReturned ? 'disabled' : '' }}
                        class="form-control @error('paid_amount') is-invalid @enderror" id="paid_amount"
                        name="paid_amount" required step="0.01" type="number"
                        value="{{ old('paid_amount', $purchase->paid_amount ?? '0.00') }}">
                    @error('paid_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    {{-- Right: Products & Totals --}}
    <div class="col-lg-9">

        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="bx bx-search text-primary me-2"></i>{{ __("messages.add_products_to_order") }}</h6>
            </div>
            <div class="card-body p-4">
                {{-- Products validation error --}}
                @error('items')
                    <div class="alert alert-danger d-flex align-items-center mb-3 gap-2 px-3 py-2">
                        <i class="bx bx-error-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="position-relative mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input {{ $isReturned ? 'disabled' : '' }} class="form-control" id="productSearchInput"
                            placeholder="{{ __('messages.type_product_sku_barcode') }}" type="text">
                    </div>
                    <div class="position-absolute w-100 d-none rounded border bg-white shadow-lg"
                        id="autocompleteResults" style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;">
                    </div>
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
                    <p class="small mb-0">No products added yet. Search above to add products.</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">{{ __("messages.order_notes") }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <textarea {{ $isReturned ? 'disabled' : '' }} class="form-control" name="notes"
                            placeholder="Delivery instructions, warranty terms..." rows="6">{{ old('notes', $purchase->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">{{ __("messages.calculation_summary") }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.subtotal') }}</span>
                            <span class="fw-bold"
                                id="sum_subtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Total Discount (-)</span>
                            <input id="discount_amount" name="discount_amount" type="hidden"
                                value="{{ old('discount_amount', $purchase->discount_amount ?? '0.00') }}">
                            <span class="fw-bold text-danger"
                                id="lbl_discount_amount">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Total Tax (+)</span>
                            <input id="tax_amount" name="tax_amount" type="hidden"
                                value="{{ old('tax_amount', $purchase->tax_amount ?? '0.00') }}">
                            <span class="fw-bold text-success"
                                id="lbl_tax_amount">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Shipping (+)</span>
                            <input {{ $isReturned ? 'disabled' : '' }} class="form-control form-control-sm text-end"
                                id="shipping_amount" min="0" name="shipping_amount" step="0.01"
                                style="width:120px;" type="number"
                                value="{{ old('shipping_amount', $purchase->shipping_amount ?? '0.00') }}">
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="fw-bold">Grand Total</span>
                            <span class="fw-bold text-primary fs-6"
                                id="sum_grandtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <div
                                    class="bg-danger border-danger rounded border border-opacity-25 bg-opacity-10 p-2 text-center">
                                    <div class="text-danger small fw-semibold">Balance Due</div>
                                    <div class="text-danger fw-bold" id="sum_due">
                                        {{ optional(current_currency())->symbol ?? '₹' }}0.00</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="bg-success border-success rounded border border-opacity-25 bg-opacity-10 p-2 text-center">
                                    <div class="text-success small fw-semibold">Overpaid</div>
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
                <i class="bx bx-x me-1"></i> Cancel
            </a>
            @if ((!isset($purchase) || !$purchase->exists) && !$isReturned)
                <button class="btn btn-outline-primary" id="btnSaveDraft" type="button">
                    <i class="bx bx-file me-1"></i> Save As Draft
                </button>
            @endif
            <button {{ $isReturned ? 'disabled' : '' }} class="btn btn-primary" type="submit">
                <i class="bx bx-save me-1"></i>
                {{ isset($purchase) && $purchase->exists ? 'Update Purchase Order' : 'Save Purchase Order' }}
            </button>
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

            // ── Refresh button ────────────────────────────────────────────────
            $('#generatePurchaseNoBtn').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i>');
                $.ajax({
                    url: "{{ route('purchases.generate-purchase-no') }}",
                    type: 'GET',
                    success: function(res) {
                        $('#purchase_no').val(res.purchase_no).focus();
                        btn.prop('disabled', false).html('<i class="bx bx-revision"></i>');
                    },
                    error: function() {
                        showAdminToast('Could not generate purchase number.', 'error');
                        btn.prop('disabled', false).html('<i class="bx bx-revision"></i>');
                    }
                });
            });

            // ── Active currency symbol from server ───────────────────────────
            const currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';

            // ── Helper: format a number with the active currency symbol ──────
            function fmtCurrency(amount) {
                return currencySymbol + parseFloat(amount).toFixed(2);
            }

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
                    addProductRow({
                        id: "{{ $item->product_id }}",
                        name: "{{ addslashes($item->product->name) }}",
                        sku: "{{ $item->product->code }}",
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
                @endforeach
            @endif

            const searchInput = $('#productSearchInput');
            const resultsContainer = $('#autocompleteResults');
            let searchTimeout = null;

            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();
                if (query.length < 1) {
                    resultsContainer.addClass('d-none').empty();
                    return;
                }
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('purchases.search-products') }}",
                        type: 'GET',
                        data: {
                            query: query
                        },
                        success: function(data) {
                            resultsContainer.empty();
                            if (data.length > 0) {
                                data.forEach(p => {
                                    const sym = p.currency_symbol ||
                                        currencySymbol;
                                    resultsContainer.append(`
                                    <div class="autocomplete-item d-flex align-items-center gap-3 px-3 py-2"
                                         style="cursor:pointer;"
                                         data-id="${p.id}" data-name="${p.name}" data-sku="${p.sku}"
                                         data-purchase-price="${p.purchase_price}" data-tax="${p.tax}"
                                         data-discount="${p.discount}" data-unit="${p.unit}" data-image="${p.image_url}">
                                        <img src="${p.image_url}" class="ac-img" onerror="imgError(this)">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="ac-name text-truncate">${p.name}</div>
                                            <div class="ac-sku">SKU: ${p.sku}</div>
                                        </div>
                                        <div class="text-end flex-shrink-0">
                                            <div class="ac-price">${sym}${parseFloat(p.purchase_price).toFixed(2)}</div>
                                            <div class="ac-stock mt-1"><i class="bx bx-box" style="font-size:10px;"></i> ${parseFloat(p.stock).toFixed(0)} in stock</div>
                                        </div>
                                    </div>`);
                                });
                                resultsContainer.removeClass('d-none');
                            } else {
                                resultsContainer.html(
                                    `<div class="px-3 py-4 text-center ac-no-results">
                                        <i class="bx bx-search-alt d-block mb-1" style="font-size:1.8rem;opacity:.4;"></i>
                                        <span class="small">No products found.</span>
                                    </div>`
                                ).removeClass('d-none');
                            }
                        }
                    });
                }, 250);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearchInput, #autocompleteResults').length)
                    resultsContainer.addClass('d-none');
            });

            $(document).on('click', '.autocomplete-item', function() {
                const p = {
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    sku: $(this).data('sku'),
                    purchase_price: parseFloat($(this).data('purchase-price')),
                    tax: parseFloat($(this).data('tax')),
                    discount: parseFloat($(this).data('discount')),
                    unit: $(this).data('unit'),
                    image_url: $(this).data('image'),
                    qty: 1
                };
                let isDuplicate = false;
                $('#purchaseItemsContainer tr').each(function() {
                    if ($(this).data('product-id') == p.id) {
                        isDuplicate = true;
                        return false;
                    }
                });
                if (isDuplicate) {
                    showAdminToast(`"${p.name}" is already in the order.`, 'warning');
                    resultsContainer.addClass('d-none').empty();
                    searchInput.val('');
                    return;
                }
                addProductRow(p);
                resultsContainer.addClass('d-none').empty();
                searchInput.val('');
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
                const grandTotal = totalSubtotal + globalTax + shipping - globalDisc;
                $('#sum_grandtotal').text(fmtCurrency(grandTotal));
                const paid = parseFloat($('#paid_amount').val()) || 0;
                $('#sum_due').text(fmtCurrency(Math.max(0, grandTotal - paid)));
                $('#sum_change').text(fmtCurrency(Math.max(0, paid - grandTotal)));
            }

            $('#btnSaveDraft').on('click', function(e) {
                e.preventDefault();
                $('select[name="status"]').val('draft');
                $(this).closest('form').submit();
            });

            // Initial calculation after any old items are loaded
            calculateTotals();
        });
    </script>
@endpush




