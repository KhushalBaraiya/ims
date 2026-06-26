@extends('layouts.admin')
@section('title', 'Create Sales Return')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Sales Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('sale-returns.store') }}" id="returnForm" novalidate>
        @csrf

        {{-- Flash error (qty / stock validation from controller) --}}
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

            <div class="col-lg-3">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-info-circle me-2 text-primary"></i>Return Details</h6>
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
                                        {{ $s->invoice_no }} ({{ $s->customer->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sale_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed
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
                        <div class="form-text">Total amount paid back to the customer.</div>
                        @error('refunded_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2 text-info"></i>Invoice Return Items</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="returnItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th class="text-center">Sold Qty</th>
                                        <th class="text-center">Already Ret.</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Avail. Return</th>
                                        <th class="text-center" style="width:110px">Return Qty</th>
                                        <th>Reason</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer"></tbody>
                            </table>
                        </div>
                        <div id="noInvoiceMsg" class="text-center py-5 text-muted">
                            <i class="bx bx-file-blank" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mt-2 mb-0">Select a sales invoice to load line items.</p>
                        </div>
                        <div id="emptyReturnMsg" class="text-center py-5 text-success d-none">
                            <i class="bx bx-check-circle" style="font-size:2.5rem;"></i>
                            <p class="mt-2 mb-0">All items have already been returned.</p>
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
                                <textarea name="notes" rows="5" class="form-control" placeholder="Return reasons, item conditions...">{{ old('notes') }}</textarea>
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
                                    <span class="text-muted small fw-semibold">Refund Subtotal</span>
                                    <span class="fw-bold" id="sum_subtotal">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-bold">Grand Total Refund</span>
                                    <span class="fw-bold text-primary" id="sum_grandtotal">₹0.00</span>
                                </div>
                                <div class="bg-light rounded p-3 mt-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-semibold">Customer Refund</span>
                                    <span class="fw-bold text-success" id="summary_refunded">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
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
            const saleIdSelect = $('#sale_id');
            const itemsContainer = $('#returnItemsContainer');
            const noInvoiceMsg = $('#noInvoiceMsg');
            const emptyReturnMsg = $('#emptyReturnMsg');
            const submitBtn = $('#submitBtn');
            const refundedInput = $('#refunded_amount');

            function loadSaleItems(saleId) {
                if (!saleId) {
                    itemsContainer.empty();
                    noInvoiceMsg.removeClass('d-none');
                    emptyReturnMsg.addClass('d-none');
                    submitBtn.attr('disabled', true);
                    calculateRefundTotals();
                    return;
                }
                noInvoiceMsg.addClass('d-none');
                emptyReturnMsg.addClass('d-none');
                itemsContainer.html(
                    '<tr><td colspan="9" class="text-center py-4 text-muted"><i class="bx bx-loader-alt bx-spin me-2"></i>Loading items...</td></tr>'
                );

                $.ajax({
                    url: `/sales/${saleId}/return-data`,
                    type: 'GET',
                    success: function(response) {
                        itemsContainer.empty();
                        if (!response.success || response.items.length === 0) {
                            itemsContainer.html(
                                '<tr><td colspan="9" class="text-center text-danger py-4">Error loading invoice data.</td></tr>'
                            );
                            return;
                        }
                        let rowCount = 0,
                            hasReturnable = false;
                        response.items.forEach(item => {
                            if (item.available_quantity > 0) {
                                hasReturnable = true;
                                const maxInt = parseInt(item.available_quantity);
                                itemsContainer.append(`
                            <tr class="item-row" data-product-id="${item.product_id}">
                                <td><img src="${item.image_url}" class="tbl-img rounded" onerror="imgError(this)"></td>
                                <td class="fw-semibold">
                                    ${item.name}
                                    <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                                </td>
                                <td class="text-center text-muted">${parseInt(item.sold_quantity)} ${item.unit}</td>
                                <td class="text-center text-muted">${parseInt(item.returned_quantity)} ${item.unit}</td>
                                <td class="text-center price-cell fw-semibold" data-price="${item.unit_price}">₹${item.unit_price.toFixed(2)}</td>
                                <td class="text-center fw-bold text-success max-returnable-cell" data-max="${maxInt}">
                                    ${maxInt} ${item.unit}
                                </td>
                                <td class="text-center">
                                    <input type="number" step="1" min="0" max="${maxInt}" name="items[${rowCount}][quantity]" value="0" class="qty-input form-control form-control-sm text-center" style="width:90px;margin:auto;">
                                </td>
                                <td>
                                    <input type="text" name="items[${rowCount}][reason]" class="form-control form-control-sm" placeholder="Reason...">
                                </td>
                                <td class="text-end fw-bold subtotal-cell">₹0.00</td>
                            </tr>`);
                                rowCount++;
                            }
                        });
                        if (!hasReturnable) {
                            emptyReturnMsg.removeClass('d-none');
                            submitBtn.attr('disabled', true);
                        } else {
                            submitBtn.attr('disabled', false);
                        }
                        calculateRefundTotals();
                    },
                    error: function() {
                        itemsContainer.html(
                            '<tr><td colspan="9" class="text-center text-danger py-4">Failed to fetch invoice items.</td></tr>'
                        );
                    }
                });
            }

            saleIdSelect.on('change', function() {
                loadSaleItems($(this).val());
            });
            if (saleIdSelect.val()) loadSaleItems(saleIdSelect.val());

            $(document).on('input change', '.qty-input', function() {
                const qtyVal = parseInt($(this).val()) || 0;
                const maxVal = parseInt($(this).closest('tr').find('.max-returnable-cell').data('max'));
                if (qtyVal > maxVal) {
                    $(this).val(maxVal);
                    showAdminToast(`Cannot return more than ${maxVal} units.`, 'error');
                }
                if (qtyVal < 0) {
                    $(this).val(0);
                }
                calculateRefundTotals();
            });

            refundedInput.on('input change', function() {
                $('#summary_refunded').text('₹' + (parseFloat($(this).val()) || 0).toFixed(2));
            });

            function calculateRefundTotals() {
                let refundSubtotal = 0;
                $('#returnItemsContainer tr.item-row').each(function() {
                    const qty = parseInt($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat($(this).find('.price-cell').data('price')) || 0;
                    const rowSub = price * qty;
                    $(this).find('.subtotal-cell').text('₹' + rowSub.toFixed(2));
                    refundSubtotal += rowSub;
                });
                $('#sum_subtotal').text('₹' + refundSubtotal.toFixed(2));
                $('#sum_grandtotal').text('₹' + refundSubtotal.toFixed(2));
                const currentRefunded = parseFloat(refundedInput.val()) || 0;
                if (currentRefunded === 0 || currentRefunded > refundSubtotal) {
                    refundedInput.val(refundSubtotal.toFixed(2));
                }
                $('#summary_refunded').text('₹' + (parseFloat(refundedInput.val()) || 0).toFixed(2));
            }

            $('#returnForm').on('submit', function(e) {
                let total = 0;
                $('.qty-input').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                if (total <= 0) {
                    e.preventDefault();
                    showAdminToast('Please specify return quantity for at least one item.', 'error');
                }
            });
        });
    </script>
@endpush
