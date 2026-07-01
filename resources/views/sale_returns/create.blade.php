@extends('layouts.admin')
@section('title', 'Create Sales Return')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Sales Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

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

    <form method="POST" action="{{ route('sale-returns.store') }}" id="returnForm" novalidate>
        @csrf

        <div class="row g-4">

            {{-- ══ LEFT COLUMN ══ --}}
            <div class="col-lg-3">

                {{-- Return Details --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-info-circle me-2 text-primary"></i>Return Details
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sales Invoice <span class="text-danger">*</span></label>
                            <select name="sale_id" id="sale_id" class="form-select @error('sale_id') is-invalid @enderror"
                                required>
                                <option value="">Select Invoice</option>
                                @foreach ($sales as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('sale_id', request('sale_id')) == $s->id ? 'selected' : '' }}>
                                        {{ $s->invoice_no }} — {{ $s->customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sale_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="customerInfoBox" class="alert alert-light border py-2 px-3 mb-3 d-none">
                            <div class="small fw-semibold text-muted">Customer</div>
                            <div class="fw-bold" id="customerInfoName">—</div>
                        </div>

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
                                    {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Refund Details --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-money me-2 text-success"></i>Refund Details
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Refunded Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="refunded_amount" id="refunded_amount"
                                class="form-control @error('refunded_amount') is-invalid @enderror"
                                value="{{ old('refunded_amount', '0.00') }}" required>
                            <div class="form-text">Total amount paid back to the customer.</div>
                            @error('refunded_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /col-lg-3 --}}

            {{-- ══ RIGHT COLUMN ══ --}}
            <div class="col-lg-9">

                {{-- Invoice Return Items --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-list-ul me-2 text-info"></i>Invoice Return Items
                        </h6>
                        <span class="badge bg-label-secondary" id="itemCountBadge">No invoice selected</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" id="itemsTableWrapper" style="display:none;">
                            <table class="table table-hover align-middle mb-0" id="returnItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px;">Image</th>
                                        <th>Product</th>
                                        <th class="text-center">Sold Qty</th>
                                        <th class="text-center">Already Ret.</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-center">Avail. Return</th>
                                        <th class="text-center" style="width:110px">Return Qty</th>
                                        <th>Reason</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer"></tbody>
                            </table>
                        </div>

                        {{-- States --}}
                        <div id="noInvoiceMsg" class="text-center py-5 text-muted">
                            <i class="bx bx-file-blank d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mb-0 small">Select a sales invoice to load line items.</p>
                        </div>
                        <div id="loadingMsg" class="text-center py-5 text-muted d-none">
                            <i class="bx bx-loader-alt bx-spin d-block mb-2" style="font-size:2.5rem;opacity:.5;"></i>
                            <p class="mb-0 small">Loading invoice items...</p>
                        </div>
                        <div id="emptyReturnMsg" class="text-center py-5 text-success d-none">
                            <i class="bx bx-check-circle d-block mb-2" style="font-size:2.5rem;"></i>
                            <p class="mb-0 small">All items from this invoice have already been returned.</p>
                        </div>
                        <div id="errorMsg" class="text-center py-4 text-danger d-none">
                            <i class="bx bx-error-circle d-block mb-2" style="font-size:2rem;"></i>
                            <p class="mb-0 small">Failed to load invoice items. Please try again.</p>
                        </div>
                    </div>
                </div>

                {{-- Notes + Summary --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bx bx-note me-2 text-warning"></i>Return Notes
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea name="notes" rows="5" class="form-control" placeholder="Return reasons, item conditions..."
                                    style="resize:vertical;">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bx bx-receipt me-2 text-info"></i>Refund Summary
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted small fw-semibold">Refund Subtotal</span>
                                        <span class="fw-bold" id="sum_subtotal">{{ format_currency(0) }}</span>
                                    </li>
                                    <li
                                        class="d-flex justify-content-between py-2 border-bottom bg-label-primary rounded px-2">
                                        <span class="fw-bold small">Grand Total Refund</span>
                                        <span class="fw-bold text-primary"
                                            id="sum_grandtotal">{{ format_currency(0) }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between py-2">
                                        <span class="text-muted small fw-semibold">Customer Refund</span>
                                        <span class="fw-bold text-success"
                                            id="summary_refunded">{{ format_currency(0) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
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

            const saleIdSelect = $('#sale_id');
            const itemsContainer = $('#returnItemsContainer');
            const itemsTableWrapper = $('#itemsTableWrapper');
            const noInvoiceMsg = $('#noInvoiceMsg');
            const loadingMsg = $('#loadingMsg');
            const emptyReturnMsg = $('#emptyReturnMsg');
            const errorMsg = $('#errorMsg');
            const submitBtn = $('#submitBtn');
            const refundedInput = $('#refunded_amount');
            const itemCountBadge = $('#itemCountBadge');
            const currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';

            const fmt = v => currencySymbol + parseFloat(v || 0).toFixed(2);

            function showState(state) {
                noInvoiceMsg.addClass('d-none');
                loadingMsg.addClass('d-none');
                emptyReturnMsg.addClass('d-none');
                errorMsg.addClass('d-none');
                itemsTableWrapper.hide();
                if (state === 'noInvoice') noInvoiceMsg.removeClass('d-none');
                else if (state === 'loading') loadingMsg.removeClass('d-none');
                else if (state === 'empty') emptyReturnMsg.removeClass('d-none');
                else if (state === 'error') errorMsg.removeClass('d-none');
                else if (state === 'table') itemsTableWrapper.show();
            }

            function loadSaleItems(saleId) {
                if (!saleId) {
                    showState('noInvoice');
                    itemsContainer.empty();
                    submitBtn.attr('disabled', true);
                    itemCountBadge.text('No invoice selected').removeClass('bg-label-primary').addClass(
                        'bg-label-secondary');
                    $('#customerInfoBox').addClass('d-none');
                    calcTotals();
                    return;
                }

                showState('loading');
                itemsContainer.empty();
                submitBtn.attr('disabled', true);

                $.ajax({
                    url: `/sales/${saleId}/return-data`,
                    type: 'GET',
                    success: function(response) {
                        if (!response.success || !response.items || response.items.length === 0) {
                            showState('error');
                            return;
                        }

                        // Show customer info
                        $('#customerInfoName').text(response.customer_name || '—');
                        $('#customerInfoBox').removeClass('d-none');

                        let rowCount = 0,
                            returnableCount = 0;

                        response.items.forEach(function(item) {
                            if (item.available_quantity <= 0) return;

                            returnableCount++;
                            const max = parseInt(item.available_quantity);
                            const imgHtml =
                                `<img src="${item.image_url}" class="tbl-img rounded" onerror="imgError(this)">`;

                            itemsContainer.append(`
                            <tr class="item-row" data-product-id="${item.product_id}">
                                <td>${imgHtml}</td>
                                <td class="fw-semibold">
                                    ${item.name}
                                    <small class="d-block text-muted">${item.sku}</small>
                                    <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                                </td>
                                <td class="text-center text-muted">${parseInt(item.sold_quantity)} <small class="text-muted">${item.unit}</small></td>
                                <td class="text-center text-warning fw-semibold">${parseInt(item.returned_quantity)}</td>
                                <td class="text-end fw-semibold price-cell" data-price="${item.unit_price}">${fmt(item.unit_price)}</td>
                                <td class="text-center fw-bold text-success max-returnable-cell" data-max="${max}">${max} <small class="text-muted fw-normal">${item.unit}</small></td>
                                <td class="text-center">
                                    <input type="number" step="1" min="0" max="${max}"
                                        name="items[${rowCount}][quantity]"
                                        value="0"
                                        class="qty-input form-control form-control-sm text-center"
                                        style="width:88px;margin:auto;">
                                </td>
                                <td>
                                    <input type="text" name="items[${rowCount}][reason]"
                                        class="form-control form-control-sm"
                                        placeholder="Reason...">
                                </td>
                                <td class="text-end fw-bold subtotal-cell">${fmt(0)}</td>
                            </tr>`);
                            rowCount++;
                        });

                        if (returnableCount === 0) {
                            showState('empty');
                            submitBtn.attr('disabled', true);
                            itemCountBadge.text('All items returned').removeClass(
                                'bg-label-secondary bg-label-primary').addClass('bg-label-success');
                        } else {
                            showState('table');
                            submitBtn.attr('disabled', false);
                            itemCountBadge.text(returnableCount + ' returnable item(s)').removeClass(
                                'bg-label-secondary bg-label-success').addClass('bg-label-primary');
                        }

                        calcTotals();
                    },
                    error: function() {
                        showState('error');
                    }
                });
            }

            saleIdSelect.on('change', function() {
                loadSaleItems($(this).val());
            });

            // Auto-load if sale_id pre-selected (old value after validation fail)
            if (saleIdSelect.val()) {
                loadSaleItems(saleIdSelect.val());
            }

            // Qty input validation
            $(document).on('input change', '.qty-input', function() {
                let val = parseInt($(this).val()) || 0;
                const max = parseInt($(this).closest('tr').find('.max-returnable-cell').data('max'));
                if (val > max) {
                    $(this).val(max);
                    showAdminToast(`Max returnable: ${max} unit(s).`, 'error');
                    val = max;
                }
                if (val < 0) $(this).val(0);
                calcTotals();
            });

            // Refunded amount live update
            refundedInput.on('input change', function() {
                $('#summary_refunded').text(fmt(parseFloat($(this).val()) || 0));
            });

            function calcTotals() {
                let subtotal = 0;
                itemsContainer.find('tr.item-row').each(function() {
                    const qty = parseInt($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat($(this).find('.price-cell').data('price')) || 0;
                    const rowSub = price * qty;
                    $(this).find('.subtotal-cell').text(fmt(rowSub));
                    subtotal += rowSub;
                });

                $('#sum_subtotal').text(fmt(subtotal));
                $('#sum_grandtotal').text(fmt(subtotal));

                // Auto-set refunded amount to grand total if 0
                const currentRefunded = parseFloat(refundedInput.val()) || 0;
                if (currentRefunded === 0 || currentRefunded > subtotal) {
                    refundedInput.val(subtotal.toFixed(2));
                }
                $('#summary_refunded').text(fmt(parseFloat(refundedInput.val()) || 0));
            }

            // Form submit guard
            $('#returnForm').on('submit', function(e) {
                let total = 0;
                $('.qty-input').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                if (total <= 0) {
                    e.preventDefault();
                    showAdminToast('Please specify a return quantity for at least one item.', 'error');
                }
            });
        });
    </script>
@endpush
