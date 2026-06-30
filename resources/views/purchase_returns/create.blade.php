@extends('layouts.admin')
@section('title', 'Create Purchase Return')

@section('content')

    {{-- Page Header --}}
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
        <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('purchase-returns.store') }}" id="returnForm" novalidate>
        @csrf

        @if (isset($selectedPurchase))
            <input type="hidden" name="purchase_id" id="purchase_id" value="{{ $selectedPurchase->id }}">
        @endif

        {{-- Alerts --}}
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

            {{-- ══ LEFT COLUMN ══ --}}
            <div class="col-lg-3">

                {{-- Return Details Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-info-circle me-2 text-primary"></i>Return Details</h6>
                    </div>
                    <div class="card-body p-4">

                        @if (isset($selectedPurchase))
                            <div class="alert alert-primary py-2 px-3 mb-3 d-flex align-items-center gap-2">
                                <i class="bx bx-link-alt flex-shrink-0"></i>
                                <div>
                                    <div class="fw-semibold small">Linked Purchase</div>
                                    <div class="small">{{ $selectedPurchase->purchase_no }}
                                        @if ($selectedPurchase->supplier)
                                            &bull; {{ $selectedPurchase->supplier->name }}
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
                                    {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Refund Details Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-money me-2 text-success"></i>Refund Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Refunded Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="refunded_amount" id="refunded_amount"
                                class="form-control @error('refunded_amount') is-invalid @enderror"
                                value="{{ old('refunded_amount', '0.00') }}" required>
                            <div class="form-text">Amount refunded to company / credited from supplier.</div>
                            @error('refunded_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /col-lg-3 --}}

            {{-- ══ RIGHT COLUMN ══ --}}
            <div class="col-lg-9">

                {{-- Search / Products Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-search me-2 text-primary"></i>
                            @if (isset($selectedPurchase))
                                Products from Purchase #{{ $selectedPurchase->purchase_no }}
                            @else
                                Search Products to Return
                            @endif
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Search Input --}}
                        <div class="position-relative mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" id="productSearchInput" class="form-control"
                                    placeholder="Type Product Name, SKU, or Scan Barcode..." autocomplete="off">
                            </div>
                            <div id="autocompleteResults"
                                class="position-absolute w-100 bg-white border rounded shadow-lg d-none"
                                style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;"></div>
                        </div>

                        {{-- Items Table --}}
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
                                        <th class="text-center" style="width:110px;">Return Qty</th>
                                        <th>Reason</th>
                                        <th class="text-end">Subtotal</th>
                                        <th style="width:40px;"></th>
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

                {{-- Notes + Refund Summary --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold"><i class="bx bx-note me-2 text-warning"></i>Return Notes</h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea name="notes" rows="5" class="form-control"
                                    placeholder="Describe return reason, item conditions..." style="resize:vertical;">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold"><i class="bx bx-receipt me-2 text-info"></i>Refund Summary
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="text-muted small fw-semibold">Return Subtotal</span>
                                    <span class="fw-bold"
                                        id="sum_subtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00 <span
                                            class="text-muted fw-normal small">(0 units)</span></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-bold">Grand Refund Total</span>
                                    <span class="fw-bold text-primary fs-6"
                                        id="sum_grandtotal">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                                </div>
                                <div class="rounded p-3 mt-3 d-flex justify-content-between align-items-center"
                                    style="background:rgba(105,108,255,.07);border:1px solid rgba(105,108,255,.15);">
                                    <span class="text-muted small fw-semibold">Refunded to Company</span>
                                    <span class="fw-bold text-success fs-6"
                                        id="summary_refunded">{{ optional(current_currency())->symbol ?? '₹' }}0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bx bx-save me-1"></i> Process Return
                    </button>
                </div>

            </div>{{-- /col-lg-9 --}}
        </div>{{-- /row --}}
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            const IS_LINKED = {{ isset($selectedPurchase) ? 'true' : 'false' }};
            const PURCHASE_ID = {{ isset($selectedPurchase) ? $selectedPurchase->id : 'null' }};
            const currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';

            const searchInput = $('#productSearchInput');
            const resultsBox = $('#autocompleteResults');
            const itemsContainer = $('#returnItemsContainer');
            const noItemsMsg = $('#noItemsMsg');
            const refundedInput = $('#refunded_amount');

            let purchaseItemsMap = {};
            let rowCount = 0;

            const fmt = v => currencySymbol + parseFloat(v).toFixed(2);

            // ── LINKED MODE: Load purchase items on startup ───────────────────
            if (IS_LINKED) {
                loadPurchaseItems(function() {
                    @if (old('items'))
                        restoreOldItems();
                    @else
                        autoPopulate();
                    @endif
                });
            }

            // ── FREE MODE: restore old items after validation failure ─────────
            @if (!isset($selectedPurchase) && old('items'))
                @foreach (old('items', []) as $oi)
                    @if (!empty($oi['product_id']))
                        @php $op = \App\Models\Product::with('stock')->find($oi['product_id']??null); @endphp
                        @if ($op)
                            addRow({
                                    product_id: {{ $op->id }},
                                    name: "{{ addslashes($op->name) }}",
                                    sku: "{{ $op->code }}",
                                    unit_price: {{ (float) ($oi['unit_price'] ?? $op->purchase_price) }},
                                    stock: {{ (float) ($op->stock->quantity ?? 0) }},
                                    purchased_qty: null,
                                    already_returned: null,
                                    max_returnable: {{ (float) ($op->stock->quantity ?? 0) }},
                                }, {{ (int) ($oi['quantity'] ?? 1) }}, "{{ addslashes($oi['reason'] ?? '') }}",
                                false);
                        @endif
                    @endif
                @endforeach
            @endif

            function loadPurchaseItems(cb) {
                $.ajax({
                    url: '/purchases/' + PURCHASE_ID + '/return-data',
                    type: 'GET',
                    success: function(data) {
                        purchaseItemsMap = {};
                        data.forEach(function(i) {
                            purchaseItemsMap[i.product_id] = i;
                        });
                        if (cb) cb();
                    },
                    error: function() {
                        noItemsMsg.html(
                            '<p class="text-danger text-center py-3">Failed to load purchase items.</p>'
                            ).removeClass('d-none');
                    }
                });
            }

            function autoPopulate() {
                Object.values(purchaseItemsMap).forEach(function(item) {
                    if (parseInt(item.max_returnable) > 0) addRow(item, 1, '', true);
                });
            }

            function restoreOldItems() {
                @if (old('items'))
                    var oldItems = [
                        @foreach (old('items', []) as $oi)
                            @if (!empty($oi['product_id']))
                                {
                                    product_id: {{ $oi['product_id'] }},
                                    quantity: {{ (int) ($oi['quantity'] ?? 0) }},
                                    reason: "{{ addslashes($oi['reason'] ?? '') }}",
                                    unit_price: {{ (float) ($oi['unit_price'] ?? 0) }}
                                },
                            @endif
                        @endforeach
                    ];
                    oldItems.forEach(function(oi) {
                        var item = purchaseItemsMap[oi.product_id];
                        if (item && oi.quantity > 0) {
                            if (oi.unit_price) item.unit_price = oi.unit_price;
                            addRow(item, oi.quantity, oi.reason, true);
                        }
                    });
                @endif
            }

            // ── PRODUCT SEARCH ────────────────────────────────────────────────
            var searchTimer = null;
            searchInput.on('input', function() {
                clearTimeout(searchTimer);
                var q = $(this).val().trim();
                resultsBox.addClass('d-none').empty();
                if (q.length < 1) return;

                if (IS_LINKED) {
                    var ql = q.toLowerCase();
                    renderResults(
                        Object.values(purchaseItemsMap).filter(function(i) {
                            return i.name.toLowerCase().indexOf(ql) >= 0 || (i.sku || '')
                                .toLowerCase().indexOf(ql) >= 0;
                        }), 'purchase');
                } else {
                    searchTimer = setTimeout(function() {
                        $.ajax({
                            url: '{{ route('purchase-returns.search-products') }}',
                            type: 'GET',
                            data: {
                                query: q
                            },
                            success: function(d) {
                                renderResults(d, 'free');
                            }
                        });
                    }, 250);
                }
            });

            function renderResults(items, mode) {
                resultsBox.empty();
                if (!items || items.length === 0) {
                    resultsBox.html('<div class="px-3 py-3 text-muted small text-center">No products found.</div>')
                        .removeClass('d-none');
                    return;
                }
                items.forEach(function(item) {
                    var id = mode === 'purchase' ? item.product_id : item.id;
                    var price = mode === 'purchase' ? item.unit_price : item.purchase_price;
                    var maxR = mode === 'purchase' ? parseInt(item.max_returnable) : null;
                    var stock = parseFloat(item.stock || 0);
                    var sku = item.sku || '-';
                    var badge = '';
                    if (mode === 'purchase') {
                        badge = maxR > 0 ?
                            '<div class="text-muted" style="font-size:11px;">Max Returnable: ' + maxR +
                            '</div>' :
                            '<div class="text-danger" style="font-size:11px;">Already fully returned</div>';
                    } else {
                        badge = '<div class="text-muted" style="font-size:11px;">Stock: ' + stock.toFixed(
                            0) + '</div>';
                    }
                    resultsBox.append(
                        '<div class="autocomplete-item d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="cursor:pointer;" data-id="' +
                        id + '" data-mode="' + mode + '">' +
                        '<div>' +
                        '<div class="fw-semibold small">' + item.name + '</div>' +
                        '<div class="text-muted" style="font-size:11px;">SKU: ' + sku + '</div>' +
                        '</div>' +
                        '<div class="text-end">' +
                        '<div class="fw-bold text-primary small">' + fmt(price) + '</div>' +
                        badge +
                        '</div>' +
                        '</div>'
                    );
                });
                resultsBox.removeClass('d-none');
            }

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#productSearchInput, #autocompleteResults').length)
                    resultsBox.addClass('d-none');
            });

            $(document).on('click', '.autocomplete-item', function() {
                var id = $(this).data('id');
                var mode = $(this).data('mode');
                var isDupe = false;
                itemsContainer.find('tr.item-row').each(function() {
                    if ($(this).data('product-id') == id) {
                        isDupe = true;
                        return false;
                    }
                });
                if (isDupe) {
                    showAdminToast('Product already in return list.', 'warning');
                    resultsBox.addClass('d-none').empty();
                    searchInput.val('');
                    return;
                }
                if (mode === 'purchase') {
                    var item = purchaseItemsMap[id];
                    if (!item || parseInt(item.max_returnable) <= 0) {
                        showAdminToast('This product has already been fully returned.', 'error');
                        resultsBox.addClass('d-none').empty();
                        searchInput.val('');
                        return;
                    }
                    addRow(item, 1, '', true);
                } else {
                    var priceText = $(this).find('.fw-bold.text-primary.small').text().replace(/[^0-9.]/g,
                        '');
                    var stockText = $(this).find('.text-muted[style]').last().text().replace(/[^0-9.]/g,
                    '');
                    addRow({
                        product_id: id,
                        name: $(this).find('.fw-semibold.small').text().trim(),
                        sku: $(this).find('.text-muted').first().text().replace('SKU: ', '').trim(),
                        unit_price: parseFloat(priceText) || 0,
                        stock: parseFloat(stockText) || 0,
                        purchased_qty: null,
                        already_returned: null,
                        max_returnable: parseFloat(stockText) || 9999,
                    }, 1, '', false);
                }
                resultsBox.addClass('d-none').empty();
                searchInput.val('');
            });

            // ── ADD ROW ───────────────────────────────────────────────────────
            function addRow(item, qty, reason, isLinked) {
                noItemsMsg.addClass('d-none');
                var maxInt = item.max_returnable !== null ? parseInt(item.max_returnable) : 99999;
                var price = parseFloat(item.unit_price) || 0;
                var extraCols = '';
                if (isLinked) {
                    extraCols =
                        '<td class="text-center text-muted">' + parseInt(item.purchased_qty) + '</td>' +
                        '<td class="text-center text-warning">' + parseInt(item.already_returned) + '</td>' +
                        '<td class="text-center fw-bold text-success max-returnable-cell" data-max="' + maxInt +
                        '">' + maxInt + '</td>';
                } else {
                    extraCols = '<td class="text-center text-muted">' + parseInt(item.stock) + '</td>';
                }
                itemsContainer.append(
                    '<tr class="item-row" data-product-id="' + item.product_id + '">' +
                    '<td class="ps-3 fw-semibold">' + item.name +
                    '<input type="hidden" name="items[' + rowCount + '][product_id]" value="' + item
                    .product_id + '">' +
                    '<input type="hidden" name="items[' + rowCount +
                    '][unit_price]" class="unit-price-input" value="' + price.toFixed(2) + '">' +
                    '</td>' +
                    '<td class="text-center text-primary fw-bold unit-price-cell" data-price="' + price + '">' +
                    fmt(price) + '</td>' +
                    extraCols +
                    '<td class="text-center">' +
                    '<input type="number" step="1" min="1" max="' + maxInt + '"' +
                    ' name="items[' + rowCount + '][quantity]" value="' + qty + '"' +
                    ' class="qty-input form-control form-control-sm text-center" style="width:88px;margin:auto;">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="items[' + rowCount + '][reason]" value="' + reason + '"' +
                    ' class="form-control form-control-sm" placeholder="Reason...">' +
                    '</td>' +
                    '<td class="text-end fw-bold subtotal-cell">' + fmt(price * qty) + '</td>' +
                    '<td class="text-center">' +
                    '<button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-row-btn"' +
                    ' style="width:28px;height:28px;padding:0;">' +
                    '<i class="bx bx-trash" style="font-size:13px;"></i>' +
                    '</button>' +
                    '</td>' +
                    '</tr>'
                );
                rowCount++;
                calcTotals();
            }

            // ── REMOVE ROW ────────────────────────────────────────────────────
            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if (itemsContainer.find('tr.item-row').length === 0) noItemsMsg.removeClass('d-none');
                calcTotals();
            });

            // ── QTY VALIDATION ────────────────────────────────────────────────
            $(document).on('input change', '.qty-input', function() {
                var val = parseInt($(this).val()) || 0;
                var maxCell = $(this).closest('tr').find('.max-returnable-cell');
                if (maxCell.length) {
                    var max = parseInt(maxCell.data('max'));
                    if (val > max) {
                        $(this).val(max);
                        showAdminToast('Max returnable: ' + max, 'error');
                        val = max;
                    }
                }
                if (val < 1) $(this).val(1);
                calcTotals();
            });

            // ── TOTALS ────────────────────────────────────────────────────────
            refundedInput.on('input change', function() {
                $('#summary_refunded').text(fmt(parseFloat($(this).val()) || 0));
            });

            function calcTotals() {
                var amt = 0,
                    qty = 0;
                itemsContainer.find('tr.item-row').each(function() {
                    var q = parseInt($(this).find('.qty-input').val()) || 0;
                    var p = parseFloat($(this).find('.unit-price-cell').data('price')) || 0;
                    var s = p * q;
                    $(this).find('.subtotal-cell').text(fmt(s));
                    amt += s;
                    qty += q;
                });
                $('#sum_subtotal').html(fmt(amt) + ' <span class="text-muted fw-normal small">(' + qty +
                    ' units)</span>');
                $('#sum_grandtotal').text(fmt(amt));
                $('#summary_refunded').text(fmt(parseFloat(refundedInput.val()) || 0));
            }

            // ── SUBMIT GUARD ──────────────────────────────────────────────────
            $('#returnForm').on('submit', function(e) {
                var total = 0;
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
