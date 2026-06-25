@csrf

{{-- Show stock errors prominently --}}
@if ($errors->has('stock_error'))
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
        <i class="bx bx-error-circle fs-5 mt-1 flex-shrink-0"></i>
        <div>
            <strong>Stock Error — Cannot save purchase.</strong><br>
            {{ $errors->first('stock_error') }}
        </div>
    </div>
@endif

<div class="row g-4">

    {{-- Left: Order Info --}}
    <div class="col-lg-3">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-info-circle me-2 text-primary"></i>Order Info</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Purchase No</label>
                    <input type="text" class="form-control bg-light fw-bold"
                        value="{{ $purchase->purchase_no ?? 'AUTO-GENERATED' }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" name="purchase_date"
                        class="form-control flatpickr-date @error('purchase_date') is-invalid @enderror"
                        value="{{ old('purchase_date', $purchase->purchase_date ?? date('Y-m-d')) }}" required>
                    @error('purchase_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}"
                                {{ old('supplier_id', $purchase->supplier_id ?? '') == $s->id ? 'selected' : '' }}>
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
                    <input type="text" name="reference_no" class="form-control"
                        value="{{ old('reference_no', $purchase->reference_no ?? '') }}"
                        placeholder="Optional reference...">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Completed"
                            {{ old('status', $purchase->status ?? 'Completed') === 'Completed' ? 'selected' : '' }}>
                            Completed</option>
                        <option value="Draft"
                            {{ old('status', $purchase->status ?? '') === 'Draft' ? 'selected' : '' }}>
                            Draft</option>
                        <option value="Cancelled"
                            {{ old('status', $purchase->status ?? '') === 'Cancelled' ? 'selected' : '' }}>
                            Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-credit-card me-2 text-success"></i>Payment Details</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="Cash"
                            {{ old('payment_method', $purchase->payment_method ?? 'Cash') === 'Cash' ? 'selected' : '' }}>
                            Cash</option>
                        <option value="Bank Transfer"
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Bank Transfer' ? 'selected' : '' }}>
                            Bank Transfer</option>
                        <option value="Card"
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Card' ? 'selected' : '' }}>
                            Credit/Debit Card</option>
                        <option value="UPI / QR"
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'UPI / QR' ? 'selected' : '' }}>
                            UPI / QR Code</option>
                        <option value="Cheque"
                            {{ old('payment_method', $purchase->payment_method ?? '') === 'Cheque' ? 'selected' : '' }}>
                            Cheque</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                        class="form-control @error('paid_amount') is-invalid @enderror"
                        value="{{ old('paid_amount', $purchase->paid_amount ?? '0.00') }}" required>
                    @error('paid_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    {{-- Right: Products & Totals --}}
    <div class="col-lg-9">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-search me-2 text-primary"></i>Add Products to Order</h6>
            </div>
            <div class="card-body p-4">
                <div class="position-relative mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" id="productSearchInput" class="form-control"
                            placeholder="Type Product Name, SKU, or Scan Barcode...">
                    </div>
                    <div id="autocompleteResults"
                        class="position-absolute w-100 bg-white border rounded shadow-lg d-none"
                        style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="purchaseItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Purchase Price</th>
                                <th class="text-center">Discount</th>
                                <th class="text-center">Tax</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchaseItemsContainer"></tbody>
                    </table>
                    <div id="emptyTableMsg" class="text-center py-5 text-muted">
                        <i class="bx bx-package" style="font-size:2.5rem;opacity:.3;"></i>
                        <p class="mt-2 mb-0">No products added yet.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">Order Notes</h6>
                    </div>
                    <div class="card-body p-3">
                        <textarea name="notes" rows="6" class="form-control" placeholder="Delivery instructions, warranty terms...">{{ old('notes', $purchase->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">Calculation Summary</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Subtotal</span>
                            <span class="fw-bold" id="sum_subtotal">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Total Discount (-)</span>
                            <input type="number" step="0.01" min="0" name="discount_amount"
                                id="discount_amount" class="form-control form-control-sm text-end"
                                style="width:120px;"
                                value="{{ old('discount_amount', $purchase->discount_amount ?? '0.00') }}">
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Total Tax (+)</span>
                            <input type="number" step="0.01" min="0" name="tax_amount" id="tax_amount"
                                class="form-control form-control-sm text-end" style="width:120px;"
                                value="{{ old('tax_amount', $purchase->tax_amount ?? '0.00') }}">
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span class="text-muted small fw-semibold">Shipping (+)</span>
                            <input type="number" step="0.01" min="0" name="shipping_amount"
                                id="shipping_amount" class="form-control form-control-sm text-end"
                                style="width:120px;"
                                value="{{ old('shipping_amount', $purchase->shipping_amount ?? '0.00') }}">
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="fw-bold">Grand Total</span>
                            <span class="fw-bold text-primary fs-6" id="sum_grandtotal">₹0.00</span>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <div
                                    class="bg-danger bg-opacity-10 rounded p-2 text-center border border-danger border-opacity-25">
                                    <div class="text-danger small fw-semibold">Balance Due</div>
                                    <div class="text-danger fw-bold" id="sum_due">₹0.00</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    class="bg-success bg-opacity-10 rounded p-2 text-center border border-success border-opacity-25">
                                    <div class="text-success small fw-semibold">Overpaid</div>
                                    <div class="text-success fw-bold" id="sum_change">₹0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-x me-1"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
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

            @if (isset($purchase) && $purchase->items->count() > 0)
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
                        qty: parseFloat("{{ $item->quantity }}"),
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
                                    resultsContainer.append(`
                                    <div class="autocomplete-item d-flex justify-content-between align-items-center px-3 py-2 border-bottom"
                                         style="cursor:pointer;"
                                         data-id="${p.id}" data-name="${p.name}" data-sku="${p.sku}"
                                         data-purchase-price="${p.purchase_price}" data-tax="${p.tax}"
                                         data-discount="${p.discount}" data-unit="${p.unit}" data-image="${p.image_url}">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="${p.image_url}" class="rounded" style="width:36px;height:36px;object-fit:cover;">
                                            <div>
                                                <div class="fw-semibold small">${p.name}</div>
                                                <div class="text-muted" style="font-size:11px;">SKU: ${p.sku}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-primary small">${p.currency_symbol}${parseFloat(p.purchase_price).toFixed(2)}</div>
                                            <div class="text-muted" style="font-size:11px;">Stock: ${parseFloat(p.stock).toFixed(2)}</div>
                                        </div>
                                    </div>`);
                                });
                                resultsContainer.removeClass('d-none');
                            } else {
                                resultsContainer.html(
                                    '<div class="px-3 py-3 text-muted small text-center">No products found.</div>'
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
                const itemTax = (p.tax / 100) * p.purchase_price;
                const itemDisc = (p.discount / 100) * p.purchase_price;
                const price = p.purchase_price || 0;
                $('#purchaseItemsContainer').append(`
                <tr class="item-row" data-product-id="${p.id}">
                    <td><img src="${p.image_url}" class="tbl-img rounded" onerror="imgError(this)"></td>
                    <td class="fw-semibold">
                        ${p.name}
                        <input type="hidden" name="items[${rowCount}][product_id]" value="${p.id}">
                    </td>
                    <td><code class="small">${p.sku}</code></td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0.01" name="items[${rowCount}][quantity]"
                               value="${p.qty}" class="qty-input form-control form-control-sm text-center"
                               style="width:80px;margin:auto;">
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][purchase_price]"
                               value="${price.toFixed(2)}" class="price-input form-control form-control-sm text-center"
                               style="width:100px;margin:auto;">
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][discount_amount]"
                               value="${itemDisc.toFixed(2)}" class="discount-input form-control form-control-sm text-center"
                               style="width:80px;margin:auto;">
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="items[${rowCount}][tax_amount]"
                               value="${itemTax.toFixed(2)}" class="tax-input form-control form-control-sm text-center"
                               style="width:80px;margin:auto;">
                    </td>
                    <td class="text-end fw-bold subtotal-cell">₹0.00</td>
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
                    row.find('.subtotal-cell').text('₹' + rowTotal.toFixed(2));
                    totalSubtotal += price * qty;
                    sumItemTax += tax * qty;
                    sumItemDiscount += disc * qty;
                });
                $('#sum_subtotal').text('₹' + totalSubtotal.toFixed(2));
                $('#discount_amount').val(sumItemDiscount.toFixed(2));
                $('#tax_amount').val(sumItemTax.toFixed(2));
                const globalDisc = parseFloat($('#discount_amount').val()) || 0;
                const globalTax = parseFloat($('#tax_amount').val()) || 0;
                const shipping = parseFloat($('#shipping_amount').val()) || 0;
                const grandTotal = totalSubtotal + globalTax + shipping - globalDisc;
                $('#sum_grandtotal').text('₹' + grandTotal.toFixed(2));
                const paid = parseFloat($('#paid_amount').val()) || 0;
                $('#sum_due').text('₹' + Math.max(0, grandTotal - paid).toFixed(2));
                $('#sum_change').text('₹' + Math.max(0, paid - grandTotal).toFixed(2));
            }
        });
    </script>
@endpush
