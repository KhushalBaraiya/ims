@extends('layouts.admin')
@section('title', 'Create Purchase Return')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Create Purchase Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('purchase-returns.index') }}">{{ __('messages.purchase_returns') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add') }}</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('purchase-returns.index') }}">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <form action="{{ route('purchase-returns.store') }}" id="returnForm" method="POST" novalidate>
        @csrf

        <style>
            /* Scoped compact styles � purchase return product table */
            #returnItemsTable th,
            #returnItemsTable td {
                padding: 8px 14px !important;
                font-size: 13px !important;
            }

            .ret-prod-img {
                width: 32px !important;
                height: 32px !important;
                object-fit: cover !important;
                border-radius: 4px !important;
            }

            .ret-prod-name {
                font-size: 12.5px !important;
                line-height: 1.2 !important;
            }

            .ret-prod-sku {
                font-size: 10.5px !important;
                line-height: 1.1 !important;
            }

            .ret-qty-input {
                width: 70px !important;
                height: 28px !important;
                font-size: 12.5px !important;
                padding: 2px 4px !important;
                margin: auto !important;
            }

            .ret-subtotal-cell {
                font-size: 13px !important;
            }
        </style>

        @if (isset($selectedPurchase))
            <input id="purchase_id" name="purchase_id" type="hidden" value="{{ $selectedPurchase->id }}">
        @endif

        {{-- Alerts --}}
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
                <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center mb-4 gap-2 px-3 py-2">
                <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="row g-4">

            {{-- -- LEFT COLUMN -- --}}
            <div class="col-lg-3 col-md-4">

                {{-- Return Details Card --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0"><i class="bx bx-info-circle text-primary me-2"></i>Return Details</h6>
                    </div>
                    <div class="card-body p-4">

                        @if (isset($selectedPurchase))
                            <div class="alert alert-primary d-flex align-items-center mb-3 gap-2 px-3 py-2">
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
                            <label class="form-label fw-semibold">{{ __('messages.return_date') }} <span
                                    class="text-danger">*</span></label>
                            <input class="form-control flatpickr-date @error('return_date') is-invalid @enderror"
                                name="return_date" required type="date" value="{{ old('return_date', date('Y-m-d')) }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.reference_no') }}</label>
                            <input class="form-control" name="reference_no"
                                placeholder="{{ __('messages.ph_optional_ref') }}" type="text"
                                value="{{ old('reference_no') }}">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">{{ __('messages.status') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}
                                    value="Completed">
                                    Completed</option>
                                <option {{ old('status') === 'Pending' ? 'selected' : '' }} value="Pending">Pending
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Refund Details Card --}}
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0"><i class="bx bx-money text-success me-2"></i>Refund Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold">{{ __('messages.refunded_amount_field') }} <span
                                    class="text-danger">*</span></label>
                            <input class="form-control @error('refunded_amount') is-invalid @enderror" id="refunded_amount"
                                min="0" name="refunded_amount" required step="0.01" type="number"
                                value="{{ old('refunded_amount', '0.00') }}">
                            <div class="form-text">Amount refunded to company / credited from supplier.</div>
                            @error('refunded_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /col-lg-3 --}}

            {{-- -- RIGHT COLUMN -- --}}
            <div class="col-lg-9 col-md-8">

                {{-- Search / Products Card --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-search text-primary me-2"></i>
                            @if (isset($selectedPurchase))
                                Products from Purchase #{{ $selectedPurchase->purchase_no }}
                            @else
                                {{ __('messages.search_products_return') }}
                            @endif
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Search Input --}}
                        <div class="position-relative mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input autocomplete="off" class="form-control" id="productSearchInput"
                                    placeholder="{{ __('messages.ph_type_sku_barcode') }}" type="text">
                            </div>
                            <div class="position-absolute w-100 d-none rounded" id="autocompleteResults"
                                style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;">
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table align-middle" id="returnItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.product') }}</th>
                                        <th class="text-center">Unit Price</th>
                                        @if (isset($selectedPurchase))
                                            <th class="text-center">{{ __('messages.purchased_qty') }}</th>
                                            <th class="text-center">{{ __('messages.already_returned') }}</th>
                                            <th class="text-center">{{ __('messages.max_returnable') }}</th>
                                        @else
                                            <th class="text-center">{{ __('messages.in_stock') }}</th>
                                        @endif
                                        <th class="text-center" style="width:110px;">{{ __('messages.return_qty') }}</th>
                                        <th>{{ __('messages.reason') }}</th>
                                        <th class="text-end">{{ __('messages.subtotal') }}</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer"></tbody>
                            </table>
                        </div>

                        <div class="text-muted py-5 text-center" id="noItemsMsg">
                            <i class="bx bx-search-alt d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="small mb-0">Search and add products to return above.</p>
                        </div>

                    </div>
                </div>

                {{-- Notes + Refund Summary --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom bg-white py-3">
                                <h6 class="fw-semibold mb-0"><i class="bx bx-note text-warning me-2"></i>Return Notes</h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea class="form-control" name="notes" placeholder="{{ __('messages.ph_return_reason_desc') }}"
                                    rows="5" style="resize:vertical;">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom bg-white py-3">
                                <h6 class="fw-semibold mb-0"><i class="bx bx-receipt text-info me-2"></i>Refund Summary
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="text-muted small fw-semibold">Return Subtotal</span>
                                    <span class="fw-bold"
                                        id="sum_subtotal">{{ optional(current_currency())->symbol ?? '?' }}0.00 <span
                                            class="text-muted fw-normal small">(0 units)</span></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-bold">Grand Refund Total</span>
                                    <span class="fw-bold text-primary fs-6"
                                        id="sum_grandtotal">{{ optional(current_currency())->symbol ?? '?' }}0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3 rounded p-3"
                                    style="background:rgba(105,108,255,.07);border:1px solid rgba(105,108,255,.15);">
                                    <span class="text-muted small fw-semibold">Refunded to Company</span>
                                    <span class="fw-bold text-success fs-6"
                                        id="summary_refunded">{{ optional(current_currency())->symbol ?? '?' }}0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('purchase-returns.index') }}">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button class="btn btn-primary" id="submitBtn" type="submit">
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
            const currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '?') }}';

            const searchInput = $('#productSearchInput');
            const resultsBox = $('#autocompleteResults');
            const itemsContainer = $('#returnItemsContainer');
            const noItemsMsg = $('#noItemsMsg');
            const refundedInput = $('#refunded_amount');

            let purchaseItemsMap = {};
            let rowCount = 0;

            const fmt = v => currencySymbol + parseFloat(v).toFixed(2);

            // -- LINKED MODE: Load purchase items on startup -------------------
            if (IS_LINKED) {
                loadPurchaseItems(function() {
                    @if (old('items'))
                        restoreOldItems();
                    @else
                        autoPopulate();
                    @endif
                });
            }

            // -- FREE MODE: restore old items after validation failure ---------
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
                                }, {{ (int) ($oi['quantity'] ?? 1) }},
                                "{{ addslashes($oi['reason'] ?? '') }}",
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

            // -- PRODUCT SEARCH ------------------------------------------------
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
                    resultsBox.html(
                        '<div class="px-3 py-4 text-center ac-no-results">' +
                        '<i class="bx bx-search-alt d-block mb-1" style="font-size:1.8rem;opacity:.4;"></i>' +
                        '<span class="small">No products found.</span></div>'
                    ).removeClass('d-none');
                    return;
                }
                items.forEach(function(item) {
                    var id = mode === 'purchase' ? item.product_id : item.id;
                    var price = mode === 'purchase' ? item.unit_price : item.purchase_price;
                    var maxR = mode === 'purchase' ? parseInt(item.max_returnable) : null;
                    var stock = parseFloat(item.stock || 0);
                    var sku = item.sku || '-';
                    var imgUrl = item.image_url || 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image';

                    var stockLine = '';
                    if (mode === 'purchase') {
                        stockLine = maxR > 0 ?
                            '<div class="ac-stock"><i class="bx bx-undo" style="font-size:10px;"></i> Max ret: ' +
                            maxR + '</div>' :
                            '<div class="ac-stock text-danger" style="font-size:10px;">Fully returned</div>';
                    } else {
                        stockLine =
                            '<div class="ac-stock"><i class="bx bx-box" style="font-size:10px;"></i> ' +
                            stock.toFixed(0) + ' in stock</div>';
                    }

                    resultsBox.append(
                        '<div class="autocomplete-item d-flex align-items-center gap-3 px-3 py-2" style="cursor:pointer;"' +
                        ' data-id="' + id + '" data-mode="' + mode + '" data-image="' + imgUrl +
                        '" data-sku="' + sku + '">' +
                        '<img src="' + imgUrl + '" class="ac-img" onerror="imgError(this)">' +
                        '<div class="flex-grow-1 overflow-hidden">' +
                        '<div class="ac-name text-truncate">' + item.name + '</div>' +
                        '<div class="ac-sku">SKU: ' + sku + '</div>' +
                        '</div>' +
                        '<div class="text-end flex-shrink-0">' +
                        '<div class="ac-price">' + fmt(price) + '</div>' +
                        stockLine +
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
                        sku: $(this).data('sku'),
                        unit_price: parseFloat(priceText) || 0,
                        stock: parseFloat(stockText) || 0,
                        purchased_qty: null,
                        already_returned: null,
                        max_returnable: parseFloat(stockText) || 9999,
                        image_url: $(this).data('image')
                    }, 1, '', false);
                }
                resultsBox.addClass('d-none').empty();
                searchInput.val('');
            });

            // -- ADD ROW -------------------------------------------------------
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
                var imgUrl = item.image_url || 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image';
                itemsContainer.append(
                    '<tr class="item-row" data-product-id="' + item.product_id + '">' +
                    '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                    '<img src="' + imgUrl + '" class="ret-prod-img rounded" onerror="imgError(this)">' +
                    '<div>' +
                    '<div class="ret-prod-name fw-bold text-primary mb-0">' + item.name + '</div>' +
                    '<div class="ret-prod-sku text-muted">SKU: ' + (item.sku || '-') + '</div>' +
                    '</div>' +
                    '</div>' +
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
                    ' class="qty-input form-control form-control-sm text-center ret-qty-input">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="items[' + rowCount + '][reason]" value="' + reason + '"' +
                    ' class="form-control form-control-sm" placeholder="{{ __('messages.ph_reason_short') }}">' +
                    '</td>' +
                    '<td class="text-end fw-bold subtotal-cell ret-subtotal-cell">' + fmt(price * qty) +
                    '</td>' +
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

            // -- REMOVE ROW ----------------------------------------------------
            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if (itemsContainer.find('tr.item-row').length === 0) noItemsMsg.removeClass('d-none');
                calcTotals();
            });

            // -- QTY VALIDATION ------------------------------------------------
            $(document).on('input change', '.qty-input', function() {
                var val = parseInt($(this).val()) || 0;
                var maxCell = $(this).closest('tr').find('.max-returnable-cell');
                if (maxCell.length) {
                    var max = parseInt(maxCell.data('max'));
                    if (val > max) {
                        $(this).val(max);
                        showAdminToast('{{ __('messages.max_returnable') }}: ' + max, 'error');
                        val = max;
                    }
                }
                if (val < 1) $(this).val(1);
                calcTotals();
            });

            // -- TOTALS --------------------------------------------------------
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

            // -- SUBMIT GUARD --------------------------------------------------
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
