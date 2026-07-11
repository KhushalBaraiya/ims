@extends('layouts.admin')
@section('title', 'Create Sales Return')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Create Sales Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('sale-returns.index') }}">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

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

    <form action="{{ route('sale-returns.store') }}" id="returnForm" method="POST" novalidate>
        @csrf

        <div class="row g-4">

            {{-- ══ LEFT COLUMN ══ --}}
            <div class="col-lg-3">

                {{-- Return Details --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-info-circle text-primary me-2"></i>Return Details
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sales Invoice <span class="text-danger">*</span></label>
                            <select class="form-select @error('sale_id') is-invalid @enderror" id="sale_id" name="sale_id"
                                required>
                                <option value="">Select Invoice</option>
                                @foreach ($sales as $s)
                                    <option {{ old('sale_id', request('sale_id')) == $s->id ? 'selected' : '' }}
                                        value="{{ $s->id }}">
                                        {{ $s->invoice_no }} — {{ $s->customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sale_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-light d-none mb-3 border px-3 py-2" id="customerInfoBox">
                            <div class="small fw-semibold text-muted">Customer</div>
                            <div class="fw-bold" id="customerInfoName">—</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                            <input class="form-control flatpickr-date @error('return_date') is-invalid @enderror"
                                name="return_date" required type="date" value="{{ old('return_date', date('Y-m-d')) }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference No</label>
                            <input class="form-control" name="reference_no" placeholder="Optional reference..."
                                type="text" value="{{ old('reference_no') }}">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}
                                    value="Completed">Completed</option>
                                <option {{ old('status') === 'Pending' ? 'selected' : '' }} value="Pending">Pending
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Refund Details --}}
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-money text-success me-2"></i>Refund Details
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Refunded Amount <span class="text-danger">*</span></label>
                            <input class="form-control @error('refunded_amount') is-invalid @enderror" id="refunded_amount"
                                min="0" name="refunded_amount" required step="0.01" type="number"
                                value="{{ old('refunded_amount', '0.00') }}">
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
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-list-ul text-info me-2"></i>Invoice Return Items
                        </h6>
                        <span class="badge bg-label-secondary" id="itemCountBadge">No invoice selected</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" id="itemsTableWrapper" style="display:none;">
                            <table class="table-hover mb-0 table align-middle" id="returnItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center" style="min-width:90px;">Return Qty</th>
                                        <th class="text-center" style="min-width:80px;">Unit Price</th>
                                        <th class="text-center" style="min-width:75px;">Discount</th>
                                        <th class="text-center" style="min-width:75px;">Tax</th>
                                        <th class="text-end" style="min-width:85px;">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer"></tbody>
                            </table>
                        </div>

                        {{-- States --}}
                        <div class="text-muted d-flex flex-column align-items-center justify-content-center py-5 text-center w-100"
                            id="noInvoiceMsg">
                            <i class="bx bx-file-blank mb-2 d-block mx-auto" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="small mb-0">Select a sales invoice to load line items.</p>
                        </div>
                        <div class="text-muted d-none d-flex flex-column align-items-center justify-content-center py-5 text-center"
                            id="loadingMsg">
                            <i class="bx bx-loader-alt bx-spin mb-2" style="font-size:2.5rem;opacity:.5;"></i>
                            <p class="small mb-0">Loading invoice items...</p>
                        </div>
                        <div class="text-success d-none d-flex flex-column align-items-center justify-content-center py-5 text-center"
                            id="emptyReturnMsg">
                            <i class="bx bx-check-circle mb-2" style="font-size:2.5rem;"></i>
                            <p class="small mb-0">All items from this invoice have already been returned.</p>
                        </div>
                        <div class="text-danger d-none d-flex flex-column align-items-center justify-content-center py-5 text-center"
                            id="errorMsg">
                            <i class="bx bx-error-circle d-block mb-2" style="font-size:2rem;"></i>
                            <p class="small mb-0">Failed to load invoice items. Please try again.</p>
                        </div>
                    </div>
                </div>

                {{-- Notes + Summary --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom bg-white py-3">
                                <h6 class="fw-semibold mb-0">
                                    <i class="bx bx-note text-warning me-2"></i>Return Notes
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea class="form-control" name="notes" placeholder="Return reasons, item conditions..." rows="5"
                                    style="resize:vertical;">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom bg-white py-3">
                                <h6 class="fw-semibold mb-0">
                                    <i class="bx bx-receipt text-info me-2"></i>Refund Summary
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted small fw-semibold">Refund Subtotal</span>
                                        <span class="fw-bold" id="sum_subtotal">{{ format_currency(0) }}</span>
                                    </li>
                                    <li
                                        class="d-flex justify-content-between border-bottom bg-label-primary rounded px-2 py-2">
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
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('sale-returns.index') }}">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button class="btn btn-primary" disabled id="submitBtn" type="submit">
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
                                `<img src="${item.image_url}" class="rounded" style="width:32px;height:32px;object-fit:cover;" onerror="imgError(this)">`;

                            itemsContainer.append(`
                             <tr class="item-row" data-product-id="${item.product_id}">
                                 <td style="min-width:200px;">
                                     <div class="d-flex align-items-center gap-2">
                                         ${imgHtml}
                                         <div>
                                             <div class="fw-bold text-primary mb-0" style="font-size:12.5px;">${item.name}</div>
                                             <div class="text-muted" style="font-size:10.5px;">SKU: ${item.sku}</div>
                                         </div>
                                     </div>
                                     <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                                 </td>
                                 <td class="text-center">
                                     <div class="text-muted small mb-1" style="font-size:10.5px;">Sold: ${parseInt(item.sold_quantity)}</div>
                                     <input type="number" step="1" min="0" max="${parseInt(item.sold_quantity)}"
                                         name="items[${rowCount}][quantity]"
                                         value="${parseInt(item.sold_quantity)}"
                                         class="qty-input form-control form-control-sm text-center"
                                         style="width:72px;margin:auto;">
                                 </td>
                                 <td class="text-center price-cell fw-semibold text-muted" style="font-size:12.5px;"
                                     data-price="${item.unit_price}">
                                     ${fmt(item.unit_price)}
                                 </td>
                                 <td class="text-center text-danger small disc-cell"
                                     data-disc="${item.discount_amount}" style="font-size:11px;">
                                     ${fmt(item.discount_amount)}
                                 </td>
                                 <td class="text-center text-warning small tax-cell"
                                     data-tax="${item.tax_amount}" style="font-size:11px;">
                                     ${fmt(item.tax_amount)}
                                 </td>
                                 <td class="text-end fw-bold subtotal-cell" style="font-size:13px;">${fmt(0)}</td>
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
                const max = parseInt($(this).attr('max')) || 0;
                if (max > 0 && val > max) {
                    $(this).val(max);
                    showAdminToast(`Max returnable: ${max} unit(s).`, 'error');
                    val = max;
                }
                if (val < 0) $(this).val(0);
                calcTotals();
            });

            // Refunded amount live update
            refundedInput.on('input change', function() {
                let val = parseFloat($(this).val()) || 0;
                const maxRefund = parseFloat($('#sum_grandtotal').text().replace(/[^\d.]/g, '')) || 0;
                if (val > maxRefund) {
                    $(this).val(maxRefund.toFixed(2));
                    showAdminToast(`Refund amount cannot exceed Grand Total Refund (${fmt(maxRefund)}).`,
                        'error');
                    val = maxRefund;
                }
                if (val < 0) $(this).val(0);
                $('#summary_refunded').text(fmt(val));
            });

            function calcTotals() {
                let subtotal = 0;
                let totalTax = 0;
                let totalDisc = 0;
                let refundTotal = 0;

                itemsContainer.find('tr.item-row').each(function() {
                    const row = $(this);
                    const qty = parseInt(row.find('.qty-input').val()) || 0;
                    const price = parseFloat(row.find('.price-cell').data('price')) || 0;
                    const unitDisc = parseFloat(row.find('.disc-cell').data('disc')) || 0;
                    const unitTax = parseFloat(row.find('.tax-cell').data('tax')) || 0;

                    const rowDisc = unitDisc * qty;
                    const rowTax = unitTax * qty;
                    const rowSubtotal = (price + unitTax - unitDisc) * qty;

                    row.find('.disc-cell').text(fmt(rowDisc));
                    row.find('.tax-cell').text(fmt(rowTax));
                    row.find('.subtotal-cell').text(fmt(rowSubtotal));

                    subtotal += price * qty;
                    totalTax += rowTax;
                    totalDisc += rowDisc;
                    refundTotal += rowSubtotal;
                });

                $('#sum_subtotal').text(fmt(subtotal));
                $('#sum_grandtotal').text(fmt(refundTotal));

                // Auto-set refunded amount to grand total if 0
                const currentRefunded = parseFloat(refundedInput.val()) || 0;
                if (currentRefunded === 0 || currentRefunded > refundTotal) {
                    refundedInput.val(refundTotal.toFixed(2));
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
