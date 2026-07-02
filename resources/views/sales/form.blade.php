@csrf

{{-- Validation Errors --}}
@if ($errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
        <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
        <span>{{ $errors->first('stock_error') }}</span>
    </div>
@endif
@if ($errors->any() && !$errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-start mb-4 gap-2 px-3 py-2">
        <i class="bx bx-error-circle fs-5 mt-1 flex-shrink-0"></i>
        <ul class="mb-0 ps-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">

    {{-- ── Left Column: Invoice Info + Payment ── --}}
    <div class="col-lg-3">

        {{-- Invoice Info Card --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="bx bx-file text-primary me-2"></i>Invoice Info</h6>
            </div>
            <div class="card-body p-4">

                {{-- Invoice Number --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Invoice Number</label>
                    @if (isset($sale) && $sale->exists)
                        <input class="form-control fw-bold text-primary bg-light" id="invoice_no" name="invoice_no"
                            readonly type="text" value="{{ $sale->invoice_no }}">
                    @else
                        <div class="input-group">
                            <input class="form-control fw-semibold" id="invoice_no" name="invoice_no"
                                placeholder="e.g. INV-20260701-00001" type="text" value="{{ old('invoice_no') }}">
                            <button class="btn btn-outline-primary" id="generateInvoiceNoBtn"
                                title="Auto-generate Invoice No" type="button">
                                <i class="bx bx-revision"></i>
                            </button>
                        </div>
                        <div class="form-text">Leave blank to auto-generate, or enter manually.</div>
                    @endif
                </div>

                {{-- Invoice Date --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Invoice Date <span class="text-danger">*</span></label>
                    <input class="form-control flatpickr-date @error('invoice_date') is-invalid @enderror"
                        name="invoice_date" required type="date"
                        value="{{ old('invoice_date', $sale->invoice_date ?? date('Y-m-d')) }}">
                    @error('invoice_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Customer --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" name="customer_id" required>
                        <option value="">Select Customer</option>
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
                        <label class="form-label fw-semibold">Sales Person</label>
                        <select class="form-select @error('sales_person_id') is-invalid @enderror"
                            name="sales_person_id">
                            <option value="">Select Sales Person</option>
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
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
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
                <h6 class="fw-semibold mb-0"><i class="bx bx-credit-card text-success me-2"></i>Payment Details</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                    <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method"
                        required>
                        <option value="">Select Method</option>
                        @foreach (['Cash', 'Bank Transfer', 'Card' => 'Credit/Debit Card', 'UPI / QR' => 'UPI / QR Code', 'Cheque'] as $val => $label)
                            @php
                                $optVal = is_string($val) ? $val : $label;
                                $optLabel = $label;
                            @endphp
                            <option
                                {{ old('payment_method', $sale->payment_method ?? 'Cash') === $optVal ? 'selected' : '' }}
                                value="{{ $optVal }}">
                                {{ $optLabel }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
                    <input class="form-control @error('paid_amount') is-invalid @enderror" id="paid_amount"
                        name="paid_amount" required step="0.01" type="number"
                        value="{{ old('paid_amount', $sale->paid_amount ?? '0.00') }}">
                    @error('paid_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    {{-- ── Right Column: Products + Totals ── --}}
    <div class="col-lg-9">

        {{-- Product Search & Line Items --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="bx bx-search text-primary me-2"></i>Add Products to Invoice</h6>
            </div>
            <div class="card-body p-4">

                @error('items')
                    <div class="alert alert-danger d-flex align-items-center mb-3 gap-2 px-3 py-2">
                        <i class="bx bx-error-circle"></i><span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="position-relative mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input class="form-control" id="productSearchInput"
                            placeholder="Type Product Name, SKU, or Scan Barcode..." type="text">
                    </div>
                    <div class="position-absolute w-100 d-none rounded border bg-white shadow-lg"
                        id="autocompleteResults" style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-hover mb-0 table align-middle" id="saleItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-muted text-center">Discount</th>
                                <th class="text-muted text-center">Tax</th>
                                <th class="text-end">Row Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsContainer"></tbody>
                    </table>
                    <div class="text-muted py-5 text-center" id="emptyTableMsg">
                        <i class="bx bx-cart" style="font-size:2.5rem;opacity:.3;"></i>
                        <p class="mb-0 mt-2">No products added to invoice.</p>
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
                        <textarea class="form-control" name="notes" placeholder="Payment notes, delivery schedules..." rows="6">{{ old('notes', $sale->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Calculation Summary</h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Subtotal (read-only) --}}
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Subtotal (rows)</span>
                            <span class="fw-bold"
                                id="sum_subtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>

                        {{-- Global Discount --}}
                        <div class="border-bottom py-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-semibold">Global Discount (−)</span>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check form-check-inline mb-0">
                                        <input
                                            {{ old('discount_type', $sale->discount_type ?? 'fixed') === 'fixed' ? 'checked' : '' }}
                                            class="form-check-input" id="discTypeFixed" name="discount_type"
                                            type="radio" value="fixed">
                                        <label class="form-check-label small" for="discTypeFixed">Fixed</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input
                                            {{ old('discount_type', $sale->discount_type ?? 'fixed') === 'percentage' ? 'checked' : '' }}
                                            class="form-check-input" id="discTypePct" name="discount_type"
                                            type="radio" value="percentage">
                                        <label class="form-check-label small" for="discTypePct">%</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small" id="discountLabel">Amount</span>
                                <input class="form-control form-control-sm text-end" id="discount_value"
                                    min="0" name="discount_value" step="0.01" style="width:120px;"
                                    type="number"
                                    value="{{ old('discount_value', $sale->discount_value ?? '0.00') }}">
                            </div>
                            <input id="discount_amount" name="discount_amount" type="hidden" value="0.00">
                            <div class="mt-1 text-end">
                                <small class="text-danger fw-semibold"
                                    id="lbl_discount_amount">−{{ optional(current_currency())->symbol ?? '₹' }}0.00</small>
                            </div>
                        </div>

                        {{-- Global Tax --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Global Tax % (+)</span>
                            <div class="d-flex align-items-center gap-2">
                                <input class="form-control form-control-sm text-end" id="tax_percentage"
                                    max="100" min="0" name="tax_percentage" step="0.01"
                                    style="width:80px;" type="number"
                                    value="{{ old('tax_percentage', $sale->tax_percentage ?? '0.00') }}">
                                <span class="text-muted small">%</span>
                            </div>
                        </div>
                        <input id="tax_amount" name="tax_amount" type="hidden" value="0.00">
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span class="text-muted small">Tax Amount</span>
                            <span class="fw-semibold text-warning"
                                id="lbl_tax_amount">+{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>

                        {{-- Shipping --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Shipping (+)</span>
                            <input class="form-control form-control-sm text-end" id="shipping_amount" min="0"
                                name="shipping_amount" step="0.01" style="width:120px;" type="number"
                                value="{{ old('shipping_amount', $sale->shipping_amount ?? '0.00') }}">
                        </div>

                        {{-- Grand Total --}}
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="fw-bold">Grand Total</span>
                            <span class="fw-bold text-primary fs-6"
                                id="sum_grandtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                        </div>

                        {{-- Due / Change --}}
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
                                    <div class="text-success small fw-semibold">Change Ret.</div>
                                    <div class="text-success fw-bold" id="sum_change">
                                        {{ optional(current_currency())->symbol ?? '₹' }}0.00</div>
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
                <i class="bx bx-x me-1"></i> Cancel
            </a>
            <button class="btn btn-primary" type="submit">
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
            const sym = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';

            function fmt(n) {
                return sym + parseFloat(n).toFixed(2);
            }

            // ── Auto-generate Invoice No on page load ─────────────────────────
            @if (!isset($sale) || !$sale->exists)
                (function autoFillInvoiceNo() {
                    $.get("{{ route('sales.generate-invoice-no') }}", function(res) {
                        if (!$('#invoice_no').val()) $('#invoice_no').val(res.invoice_no);
                    });
                })();
            @endif

            // ── Refresh button ────────────────────────────────────────────────
            $('#generateInvoiceNoBtn').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i>');
                $.get("{{ route('sales.generate-invoice-no') }}", function(res) {
                    $('#invoice_no').val(res.invoice_no).focus();
                }).always(function() {
                    btn.prop('disabled', false).html('<i class="bx bx-revision"></i>');
                });
            });

            // ── Restore existing items on edit ────────────────────────────────
            @if (isset($sale) && $sale->items->count() > 0)
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
                        unit: "{{ $item->product->unit->short_name ?? 'PCS' }}",
                        image_url: "{{ $item->product->image ? asset('uploads/products/' . $item->product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}"
                    });
                @endforeach
            @endif

            // ── Product autocomplete ──────────────────────────────────────────
            const searchInput = $('#productSearchInput');
            const resultsDiv = $('#autocompleteResults');
            let searchTimeout = null;

            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const q = $(this).val().trim();
                if (q.length < 1) {
                    resultsDiv.addClass('d-none').empty();
                    return;
                }
                searchTimeout = setTimeout(function() {
                    $.get("{{ route('products.search') }}", {
                        query: q
                    }, function(data) {
                        resultsDiv.empty();
                        if (data.length) {
                            data.forEach(function(p) {
                                resultsDiv.append(
                                    `<div class="autocomplete-item d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="cursor:pointer;"
                                  data-id="${p.id}" data-name="${p.name}" data-sku="${p.sku}" data-stock="${p.stock}"
                                  data-price="${p.price}" data-tax="${p.tax}" data-discount="${p.discount}"
                                  data-unit="${p.unit}" data-image="${p.image_url}">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="${p.image_url}" class="rounded" style="width:36px;height:36px;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold small">${p.name}</div>
                                        <div class="text-muted" style="font-size:11px;">SKU: ${p.sku}</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary small">${p.currency_symbol}${parseFloat(p.price).toFixed(2)}</div>
                                    <div class="text-muted" style="font-size:11px;">Stock: ${parseFloat(p.stock).toFixed(2)}</div>
                                </div>
                            </div>`
                                );
                            });
                            resultsDiv.removeClass('d-none');
                        } else {
                            resultsDiv.html(
                                '<div class="px-3 py-3 text-muted small text-center">No products found.</div>'
                                ).removeClass('d-none');
                        }
                    });
                }, 250);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearchInput, #autocompleteResults').length)
                    resultsDiv.addClass('d-none');
            });

            $(document).on('click', '.autocomplete-item', function() {
                const p = {
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    sku: $(this).data('sku'),
                    stock: parseFloat($(this).data('stock')),
                    price: parseFloat($(this).data('price')),
                    taxPct: parseFloat($(this).data('tax')),
                    discPct: parseFloat($(this).data('discount')),
                    unit: $(this).data('unit'),
                    image_url: $(this).data('image'),
                };
                let isDuplicate = false;
                $('#invoiceItemsContainer tr').each(function() {
                    if ($(this).data('product-id') == p.id) {
                        isDuplicate = true;
                        return false;
                    }
                });
                if (isDuplicate) {
                    showAdminToast(`"${p.name}" already added.`, 'warning');
                    resultsDiv.addClass('d-none').empty();
                    searchInput.val('');
                    return;
                }
                // Convert percentage to per-unit amount
                p.taxAmt = parseFloat(((p.taxPct / 100) * p.price).toFixed(2));
                p.discAmt = parseFloat(((p.discPct / 100) * p.price).toFixed(2));
                p.qty = 1;
                addProductRow(p);
                resultsDiv.addClass('d-none').empty();
                searchInput.val('');
            });

            // ── Build row ─────────────────────────────────────────────────────
            function addProductRow(p) {
                $('#emptyTableMsg').addClass('d-none');
                const taxAmt = typeof p.taxAmt !== 'undefined' ? p.taxAmt : (p.tax || 0);
                const discAmt = typeof p.discAmt !== 'undefined' ? p.discAmt : (p.disc || 0);
                // Row discount & tax are READ-ONLY display (locked per spec)
                $('#invoiceItemsContainer').append(`
        <tr class="item-row" data-product-id="${p.id}">
            <td><img src="${p.image_url}" class="tbl-img rounded" onerror="imgError(this)"></td>
            <td class="fw-semibold">
                ${p.name}
                <input type="hidden" name="items[${rowCount}][product_id]" value="${p.id}">
                <input type="hidden" name="items[${rowCount}][discount_amount]" class="disc-hidden" value="${discAmt.toFixed(2)}">
                <input type="hidden" name="items[${rowCount}][tax_amount]" class="tax-hidden" value="${taxAmt.toFixed(2)}">
            </td>
            <td><code class="small">${p.sku}</code></td>
            <td class="stock-cell fw-semibold text-muted" data-max="${p.stock}">${parseInt(p.stock)}</td>
            <td class="text-center">
                <input type="number" step="1" min="1" name="items[${rowCount}][quantity]"
                    value="${parseInt(p.qty || 1)}"
                    class="qty-input form-control form-control-sm text-center" style="width:80px;margin:auto;">
            </td>
            <td class="text-center">
                <input type="number" step="0.01" min="0" name="items[${rowCount}][unit_price]"
                    value="${p.price.toFixed(2)}"
                    class="price-input form-control form-control-sm text-center" style="width:100px;margin:auto;">
            </td>
            <td class="text-center text-muted small disc-display">${fmt(discAmt)}/unit</td>
            <td class="text-center text-muted small tax-display">${fmt(taxAmt)}/unit</td>
            <td class="text-end fw-bold subtotal-cell">0.00</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-row-btn"
                    style="width:28px;height:28px;padding:0;">
                    <i class="bx bx-trash" style="font-size:13px;"></i>
                </button>
            </td>
        </tr>`);
                rowCount++;
                calculateTotals();
            }

            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if ($('#invoiceItemsContainer tr').length === 0) $('#emptyTableMsg').removeClass('d-none');
                calculateTotals();
            });

            // ── Recalculate on any input change ──────────────────────────────
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
                $('#discountLabel').text($(this).val() === 'percentage' ? 'Percentage (%)' : 'Amount');
                calculateTotals();
            });
            // Set initial label
            $('#discountLabel').text($('input[name="discount_type"]:checked').val() === 'percentage' ?
                'Percentage (%)' : 'Amount');

            // ── Core calculation engine ───────────────────────────────────────
            // 1. Compute row totals  → subtotal
            // 2. Apply global discount (fixed or %) on subtotal
            // 3. Apply global tax (%) on (subtotal - discount)
            // 4. Add shipping → grand total
            function calculateTotals() {
                let subtotal = 0;

                $('#invoiceItemsContainer tr').each(function() {
                    const row = $(this);
                    const qty = parseFloat(row.find('.qty-input').val()) || 0;
                    const price = parseFloat(row.find('.price-input').val()) || 0;
                    const discU = parseFloat(row.find('.disc-hidden').val()) || 0;
                    const taxU = parseFloat(row.find('.tax-hidden').val()) || 0;
                    const rowTot = (price + taxU - discU) * qty;
                    row.find('.subtotal-cell').text(fmt(rowTot));
                    subtotal += price * qty; // subtotal = sum of (qty × unit_price), raw
                });

                $('#sum_subtotal').text(fmt(subtotal));

                // Global discount
                const discType = $('input[name="discount_type"]:checked').val() || 'fixed';
                const discVal = parseFloat($('#discount_value').val()) || 0;
                const discAmt = discType === 'percentage' ? subtotal * discVal / 100 : discVal;
                const afterDisc = Math.max(0, subtotal - discAmt);

                $('#discount_amount').val(discAmt.toFixed(2));
                $('#lbl_discount_amount').text('−' + fmt(discAmt));

                // Global tax on (subtotal − discount)
                const taxPct = parseFloat($('#tax_percentage').val()) || 0;
                const taxAmt = afterDisc * taxPct / 100;
                $('#tax_amount').val(taxAmt.toFixed(2));
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

            // Run once to initialise display
            calculateTotals();
        });
    </script>
@endpush
