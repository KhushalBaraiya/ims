@extends('layouts.admin')
@section('title', 'Create Purchase Return')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Purchase Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('purchase-returns.index') }}">{{ __('messages.purchase_returns') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bx bx-arrow-back"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('purchase-returns.store') }}" id="returnForm" novalidate>
        @csrf

        {{-- purchase_id hidden: only set when coming from Purchase table --}}
        @if (isset($selectedPurchase))
            <input type="hidden" name="purchase_id" id="purchase_id" value="{{ $selectedPurchase->id }}">
        @endif

        {{-- Flash / validation errors --}}
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 py-2 px-3">
                <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4 py-2 px-3">
                <i class="bx bx-error-circle fs-5 flex-shrink-0 mt-1"></i>
                <ul class="mb-0 ps-2">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">

            {{-- Left: Return Details --}}
            <div class="col-lg-3">
                <div style="position:sticky;top:80px;">

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-info-circle me-2 text-primary"></i>Return Details
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            {{-- If linked to a purchase, show read-only info badge --}}
                            @if (isset($selectedPurchase))
                                <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2">
                                    <i class="bx bx-link-alt flex-shrink-0"></i>
                                    <div>
                                        <div class="fw-semibold small">Linked Purchase</div>
                                        <div class="small">{{ $selectedPurchase->purchase_no }}
                                            @if ($selectedPurchase->supplier)
                                                ({{ $selectedPurchase->supplier->name }})
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                                <input type="date" name="return_date"
                                    class="form-control flatpickr-date @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date', date('Y-m-d')) }}" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Reference No</label>
                                <input type="text" name="reference_no" class="form-control"
                                    value="{{ old('reference_no') }}" placeholder="Optional reference...">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="Completed"
                                        {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}>Completed
                                    </option>
                                    <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="mb-0 fw-semibold"><i class="bx bx-money me-2 text-success"></i>Refund Details</h6>
                        </div>
                        <div class="card-body p-4">
                            <label class="form-label fw-semibold">Refunded Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="refunded_amount" id="refunded_amount"
                                class="form-control @error('refunded_amount') is-invalid @enderror"
                                value="{{ old('refunded_amount', '0.00') }}" required>
                            <div class="form-text">Amount refunded to company / credited from supplier.</div>
                            @error('refunded_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>{{-- /sticky --}}
            </div>{{-- /col-lg-3 --}}

            {{-- Right: Product Search + Items Table --}}
            <div class="col-lg-9">

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-search me-2 text-primary"></i>
                            @if (isset($selectedPurchase))
                                Products from Purchase #{{ $selectedPurchase->purchase_no }}
                            @else
                                Search Products to Return
                            @endif
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Product search input --}}
                        <div class="position-relative mb-4" id="searchWrapper">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" id="productSearchInput" class="form-control"
                                    placeholder="Type Product Name, SKU, or Scan Barcode...">
                            </div>
                            <div id="autocompleteResults"
                                class="position-absolute w-100 bg-white border rounded shadow-lg d-none"
                                style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;"></div>
                        </div>

                        {{-- Items table --}}
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="returnItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Product</th>
                                        <th class="text-center">Unit Price</th>
                                        @if (isset($selectedPurchase))
                                            <th class="text-center">Purchased Qty</th>
                                            <th class="text-center">Already Ret.</th>
                                            <th class="text-center">Max Returnable</th>
                                        @else
                                            <th class="text-center">In Stock</th>
                                        @endif
                                        <th class="text-center" style="width:110px">Return Qty</th>
                                        <th>Reason</th>
                                        <th class="text-end">Subtotal</th>
                                        <th style="width:40px"></th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer"></tbody>
                            </table>
                        </div>
                        <div id="noItemsMsg" class="text-center py-5 text-muted">
                            <i class="bx bx-search-alt d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mb-0 small">Search and add products to return above.</p>
                        </div>

                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">Return Notes</h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea name="notes" rows="5" class="form-control"
                                    placeholder="Describe return reason, item conditions...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">Refund Summary</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="text-muted small fw-semibold">Return Subtotal</span>
                                    <span class="fw-bold" id="sum_subtotal">₹0.00 (0 units)</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-bold">Grand Refund Total</span>
                                    <span class="fw-bold text-primary fs-6" id="sum_grandtotal">₹0.00</span>
                                </div>
                                <div class="rounded p-3 mt-3 d-flex justify-content-between align-items-center"
                                    style="background:rgba(105,108,255,.07);border:1px solid rgba(105,108,255,.15);">
                                    <span class="text-muted small fw-semibold">Refunded to Company</span>
                                    <span class="fw-bold text-success fs-6" id="summary_refunded">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                        <i class="bx bx-save me-1"></i> Process Return
                    </button>
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ── Config ──────────────────────────────────────────────────────────────
            const IS_LINKED = {{ isset($selectedPurchase) ? 'true' : 'false' }};
            const PURCHASE_ID = {{ isset($selectedPurchase) ? $selectedPurchase->id : 'null' }};
            const currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';

            const searchInput = $('#productSearchInput');
            const resultsContainer = $('#autocompleteResults');
            const itemsContainer = $('#returnItemsContainer');
            const noItemsMsg = $('#noItemsMsg');
            const submitBtn = $('#submitBtn');
            const refundedInput = $('#refunded_amount');

            // product_id => item data map (used both in linked and free mode)
            let purchaseItemsMap = {};
            let rowCount = 0;

            function fmt(amount) {
                return currencySymbol + parseFloat(amount).toFixed(2);
            }

            // ── LINKED MODE: Load purchase items on startup ──────────────────────
            if (IS_LINKED) {
                loadPurchaseItems(function() {
                    // If old() items exist (validation failed), restore them; else auto-populate all items
                    @if (old('items'))
                        restoreOldItems();
                    @else
                        autoPopulateLinkedItems();
                    @endif
                });
            }

            function loadPurchaseItems(callback) {
                $.ajax({
                    url: `/purchases/${PURCHASE_ID}/return-data`,
                    type: 'GET',
                    success: function(data) {
                        purchaseItemsMap = {};
                        data.forEach(item => {
                            purchaseItemsMap[item.product_id] = item;
                        });
                        if (callback) callback();
                    },
                    error: function() {
                        noItemsMsg.html('<p class="text-danger">Failed to load purchase items.</p>')
                            .removeClass('d-none');
                    }
                });
            }

            // ── AUTO-POPULATE: Add all returnable purchase items to the table ─────
            function autoPopulateLinkedItems() {
                Object.values(purchaseItemsMap).forEach(item => {
                    const maxR = parseInt(item.max_returnable);
                    if (maxR > 0) {
                        addReturnItemRow(item, 1, '', true);
                    }
                });
            }

            // ── PRODUCT SEARCH ───────────────────────────────────────────────────
            let searchTimeout = null;

            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();
                resultsContainer.addClass('d-none').empty();

                if (query.length < 1) return;

                if (IS_LINKED) {
                    // Filter in-memory from loaded purchase items
                    const q = query.toLowerCase();
                    const matches = Object.values(purchaseItemsMap).filter(item =>
                        item.name.toLowerCase().includes(q) || (item.sku || '').toLowerCase().includes(
                            q)
                    );
                    renderSearchResults(matches, 'purchase');
                } else {
                    // Free mode: AJAX search all active products
                    searchTimeout = setTimeout(function() {
                        $.ajax({
                            url: '{{ route('purchase-returns.search-products') }}',
                            type: 'GET',
                            data: {
                                query: query
                            },
                            success: function(data) {
                                renderSearchResults(data, 'free');
                            }
                        });
                    }, 250);
                }
            });

            function renderSearchResults(items, mode) {
                resultsContainer.empty();
                if (!items || items.length === 0) {
                    resultsContainer.html(
                            '<div class="px-3 py-3 text-muted small text-center">No products found.</div>')
                        .removeClass('d-none');
                    return;
                }
                items.forEach(item => {
                    const id = mode === 'purchase' ? item.product_id : item.id;
                    const price = mode === 'purchase' ? item.unit_price : item.purchase_price;
                    const stock = mode === 'purchase' ? item.stock : item.stock;
                    const maxR = mode === 'purchase' ? parseInt(item.max_returnable) : null;
                    const sku = item.sku || '-';

                    let badge = '';
                    if (mode === 'purchase') {
                        badge = maxR > 0 ?
                            `<div class="text-muted" style="font-size:11px;">Max Returnable: ${maxR}</div>` :
                            `<div class="text-danger" style="font-size:11px;">Already fully returned</div>`;
                    } else {
                        badge =
                            `<div class="text-muted" style="font-size:11px;">Stock: ${parseFloat(stock).toFixed(0)}</div>`;
                    }

                    resultsContainer.append(`
                <div class="autocomplete-item d-flex justify-content-between align-items-center px-3 py-2 border-bottom"
                     style="cursor:pointer;" data-id="${id}" data-mode="${mode}">
                    <div>
                        <div class="fw-semibold small">${item.name}</div>
                        <div class="text-muted" style="font-size:11px;">SKU: ${sku}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary small">${fmt(price)}</div>
                        ${badge}
                    </div>
                </div>
            `);
                });
                resultsContainer.removeClass('d-none');
            }

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearchInput, #autocompleteResults').length) {
                    resultsContainer.addClass('d-none');
                }
            });

            $(document).on('click', '.autocomplete-item', function() {
                const id = $(this).data('id');
                const mode = $(this).data('mode');

                let itemData;
                if (mode === 'purchase') {
                    itemData = purchaseItemsMap[id];
                    if (!itemData || parseInt(itemData.max_returnable) <= 0) {
                        showAdminToast('This product has already been fully returned.', 'error');
                        resultsContainer.addClass('d-none').empty();
                        searchInput.val('');
                        return;
                    }
                } else {
                    // Build item from free-mode result data attributes
                    const el = $(this);
                    itemData = {
                        product_id: id,
                        name: el.find('.fw-semibold.small').text().trim(),
                        sku: null,
                        stock: null,
                        unit_price: null, // will re-fetch
                        purchased_qty: null,
                        already_returned: null,
                        max_returnable: null,
                        _freeMode: true,
                        _rawEl: el
                    };
                }

                // Duplicate check
                let isDupe = false;
                itemsContainer.find('tr.item-row').each(function() {
                    if ($(this).data('product-id') == id) {
                        isDupe = true;
                        return false;
                    }
                });
                if (isDupe) {
                    showAdminToast('This product is already in the return list.', 'warning');
                    resultsContainer.addClass('d-none').empty();
                    searchInput.val('');
                    return;
                }

                if (mode === 'free') {
                    // Fetch fresh data for the clicked free-mode result
                    $.ajax({
                        url: '{{ route('purchase-returns.search-products') }}',
                        type: 'GET',
                        data: {
                            query: id
                        }, // won't match by id, so re-search
                        success: function() {}
                    });
                    // We grab the price from the already rendered result html
                    const priceText = $(this).find('.fw-bold.text-primary.small').text().replace(/[^0-9.]/g,
                        '');
                    const stockText = $(this).find('.text-muted[style]').last().text().replace(/[^0-9.]/g,
                        '');
                    addReturnItemRow({
                        product_id: id,
                        name: $(this).find('.fw-semibold.small').text().trim(),
                        sku: $(this).find('.text-muted').first().text().replace('SKU: ', '').trim(),
                        unit_price: parseFloat(priceText) || 0,
                        stock: parseFloat(stockText) || 0,
                        purchased_qty: null,
                        already_returned: null,
                        max_returnable: parseFloat(stockText) || 9999,
                    }, 1, '', false);
                } else {
                    addReturnItemRow(itemData, 1, '', true);
                }

                resultsContainer.addClass('d-none').empty();
                searchInput.val('');
            });

            // ── ADD ROW ────────────────────────────────────────────────────────────
            function addReturnItemRow(item, qty, reason, isPurchaseLinked) {
                noItemsMsg.addClass('d-none');
                const maxInt = item.max_returnable !== null ? parseInt(item.max_returnable) : 99999;
                const price = parseFloat(item.unit_price) || 0;

                let extraCols = '';
                if (isPurchaseLinked) {
                    extraCols = `
                <td class="text-center text-muted">${parseInt(item.purchased_qty)}</td>
                <td class="text-center text-warning">${parseInt(item.already_returned)}</td>
                <td class="text-center fw-bold text-success max-returnable-cell" data-max="${maxInt}">${maxInt}</td>
            `;
                } else {
                    extraCols = `
                <td class="text-center text-muted stock-cell">${parseInt(item.stock)}</td>
            `;
                }

                itemsContainer.append(`
            <tr class="item-row" data-product-id="${item.product_id}">
                <td class="ps-3 fw-semibold">
                    ${item.name}
                    <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${rowCount}][unit_price]" class="unit-price-input" value="${price.toFixed(2)}">
                </td>
                <td class="text-center text-primary fw-bold unit-price-cell" data-price="${price}">
                    ${fmt(price)}
                </td>
                ${extraCols}
                <td class="text-center">
                    <input type="number" step="1" min="1" max="${maxInt}"
                           name="items[${rowCount}][quantity]"
                           value="${qty}"
                           class="qty-input form-control form-control-sm text-center"
                           style="width:90px;margin:auto;">
                </td>
                <td>
                    <input type="text" name="items[${rowCount}][reason]"
                           value="${reason}"
                           class="form-control form-control-sm" placeholder="Reason...">
                </td>
                <td class="text-end fw-bold subtotal-cell">${fmt(price * qty)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-row-btn"
                            style="width:28px;height:28px;padding:0;">
                        <i class="bx bx-trash" style="font-size:13px;"></i>
                    </button>
                </td>
            </tr>
        `);
                rowCount++;
                calculateTotals();
            }

            // ── RESTORE OLD ITEMS (validation failed) ─────────────────────────────
            function restoreOldItems() {
                @if (old('items'))
                    const oldItems = [
                        @foreach (old('items', []) as $oldIndex => $oldItem)
                            @if (!empty($oldItem['product_id']))
                                {
                                    product_id: {{ $oldItem['product_id'] }},
                                    quantity: {{ (int) ($oldItem['quantity'] ?? 0) }},
                                    reason: "{{ addslashes($oldItem['reason'] ?? '') }}",
                                    unit_price: {{ (float) ($oldItem['unit_price'] ?? 0) }}
                                },
                            @endif
                        @endforeach
                    ];
                    oldItems.forEach(oi => {
                        const item = purchaseItemsMap[oi.product_id];
                        if (item && oi.quantity > 0) {
                            if (oi.unit_price) item.unit_price = oi.unit_price;
                            addReturnItemRow(item, oi.quantity, oi.reason, IS_LINKED);
                        }
                    });
                @endif
            }

            // For free mode old-item restore on validation failure
            @if (!isset($selectedPurchase) && old('items'))
                @foreach (old('items', []) as $oldIndex => $oldItem)
                    @if (!empty($oldItem['product_id']))
                        @php $oldProduct = \App\Models\Product::with('stock')->find($oldItem['product_id'] ?? null); @endphp
                        @if ($oldProduct)
                            addReturnItemRow({
                                    product_id: {{ $oldProduct->id }},
                                    name: "{{ addslashes($oldProduct->name) }}",
                                    sku: "{{ $oldProduct->code }}",
                                    unit_price: {{ (float) ($oldItem['unit_price'] ?? $oldProduct->purchase_price) }},
                                    stock: {{ (float) ($oldProduct->stock->quantity ?? 0) }},
                                    purchased_qty: null,
                                    already_returned: null,
                                    max_returnable: {{ (float) ($oldProduct->stock->quantity ?? 0) }},
                                }, {{ (int) ($oldItem['quantity'] ?? 1) }},
                                "{{ addslashes($oldItem['reason'] ?? '') }}", false);
                        @endif
                    @endif
                @endforeach
            @endif

            // ── REMOVE ROW ────────────────────────────────────────────────────────
            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if (itemsContainer.find('tr.item-row').length === 0) noItemsMsg.removeClass('d-none');
                calculateTotals();
            });

            // ── QTY VALIDATION ────────────────────────────────────────────────────
            $(document).on('input change', '.qty-input', function() {
                let val = parseInt($(this).val()) || 0;
                const maxCell = $(this).closest('tr').find('.max-returnable-cell');
                if (maxCell.length) {
                    const max = parseInt(maxCell.data('max'));
                    if (val > max) {
                        $(this).val(max);
                        showAdminToast(`Max returnable: ${max}`, 'error');
                        val = max;
                    }
                }
                if (val < 1) $(this).val(1);
                calculateTotals();
            });

            // ── TOTALS ────────────────────────────────────────────────────────────
            refundedInput.on('input change', function() {
                $('#summary_refunded').text(fmt(parseFloat($(this).val()) || 0));
            });

            function calculateTotals() {
                let totalAmt = 0,
                    totalQty = 0;
                itemsContainer.find('tr.item-row').each(function() {
                    const qty = parseInt($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat($(this).find('.unit-price-cell').data('price')) || 0;
                    const sub = price * qty;
                    $(this).find('.subtotal-cell').text(fmt(sub));
                    totalAmt += sub;
                    totalQty += qty;
                });
                $('#sum_subtotal').text(fmt(totalAmt) + ' (' + totalQty + ' units)');
                $('#sum_grandtotal').text(fmt(totalAmt));
                $('#summary_refunded').text(fmt(parseFloat(refundedInput.val()) || 0));
                // Button always enabled — validated on submit
            }

            // ── SUBMIT GUARD ──────────────────────────────────────────────────────
            $('#returnForm').on('submit', function(e) {
                let total = 0;
                $('.qty-input').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                if (total <= 0) {
                    e.preventDefault();
                    showAdminToast('Please add at least one product with a return quantity.', 'error');
                }
            });
        });
    </script>
@endpush
