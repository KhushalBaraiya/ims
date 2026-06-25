@extends('layouts.admin')
@section('title', 'Create Purchase Return')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Create Purchase Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Purchase Returns</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('purchase-returns.store') }}" id="returnForm" novalidate>
        @csrf
        <div class="row g-4">

            {{-- Left: Details --}}
            <div class="col-lg-3">

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-info-circle me-2 text-primary"></i>Return Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Purchase Order <span class="text-danger">*</span></label>
                            <select name="purchase_id" id="purchase_id"
                                class="form-select @error('purchase_id') is-invalid @enderror" required>
                                <option value="">Select Purchase Order</option>
                                @foreach ($purchases as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('purchase_id', request('purchase_id')) == $p->id ? 'selected' : '' }}>
                                        {{ $p->purchase_no }} ({{ $p->supplier->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('purchase_id')
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
                        <div class="form-text">Amount refunded to company / credited from supplier.</div>
                        @error('refunded_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Right: Items --}}
            <div class="col-lg-9">

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2 text-info"></i>Purchase Return Items</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Purchased Qty</th>
                                        <th class="text-center">Already Ret.</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Max Returnable</th>
                                        <th class="text-center" style="width:110px">Return Qty</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer"></tbody>
                            </table>
                        </div>
                        <div id="noInvoiceMsg" class="text-center py-5 text-muted">
                            <i class="bx bx-cart-download" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="mt-2 mb-0">Select a purchase order to load its items.</p>
                        </div>
                        <div id="emptyReturnMsg" class="text-center py-5 text-success d-none">
                            <i class="bx bx-check-circle" style="font-size:2.5rem;"></i>
                            <p class="mt-2 mb-0">All items in this purchase have already been returned.</p>
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
                                    <span class="fw-bold" id="sum_subtotal">0 units selected</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="fw-bold">Grand Refund Total</span>
                                    <span class="fw-bold text-primary" id="sum_grandtotal">0 units</span>
                                </div>
                                <div class="bg-light rounded p-3 mt-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-semibold">Refunded to Company</span>
                                    <span class="fw-bold text-success" id="summary_refunded">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
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
            const purchaseSelect = $('#purchase_id');
            const itemsContainer = $('#returnItemsContainer');
            const noInvoiceMsg = $('#noInvoiceMsg');
            const emptyMsg = $('#emptyReturnMsg');
            const submitBtn = $('#submitBtn');
            const refundedInput = $('#refunded_amount');

            function loadPurchaseItems(purchaseId) {
                if (!purchaseId) {
                    itemsContainer.empty();
                    noInvoiceMsg.removeClass('d-none');
                    emptyMsg.addClass('d-none');
                    submitBtn.attr('disabled', true);
                    return;
                }
                noInvoiceMsg.addClass('d-none');
                emptyMsg.addClass('d-none');
                itemsContainer.html(
                    '<tr><td colspan="7" class="text-center py-4 text-muted"><i class="bx bx-loader-alt bx-spin me-2"></i>Loading items...</td></tr>'
                );

                $.ajax({
                    url: `/purchases/${purchaseId}/return-data`,
                    type: 'GET',
                    success: function(data) {
                        itemsContainer.empty();
                        let rowCount = 0,
                            hasReturnable = false;
                        data.forEach(item => {
                            if (item.max_returnable > 0) {
                                hasReturnable = true;
                                itemsContainer.append(`
                            <tr class="item-row" data-product-id="${item.product_id}">
                                <td class="ps-3 fw-semibold">
                                    ${item.name}
                                    <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                                </td>
                                <td class="text-center text-muted">${parseFloat(item.purchased_qty).toFixed(2)}</td>
                                <td class="text-center text-warning">${parseFloat(item.already_returned).toFixed(2)}</td>
                                <td class="text-center text-muted">${parseFloat(item.stock).toFixed(2)}</td>
                                <td class="text-center fw-bold text-success max-returnable-cell" data-max="${item.max_returnable}">${parseFloat(item.max_returnable).toFixed(2)}</td>
                                <td class="text-center">
                                    <input type="number" step="0.01" min="0" max="${item.max_returnable}" name="items[${rowCount}][quantity]" value="0.00" class="qty-input form-control form-control-sm text-center" style="width:90px;margin:auto;">
                                </td>
                                <td>
                                    <input type="text" name="items[${rowCount}][reason]" class="form-control form-control-sm" placeholder="Reason...">
                                </td>
                            </tr>`);
                                rowCount++;
                            }
                        });
                        if (!hasReturnable) {
                            emptyMsg.removeClass('d-none');
                            submitBtn.attr('disabled', true);
                        } else {
                            submitBtn.attr('disabled', false);
                        }
                        calculateTotals();
                    },
                    error: function() {
                        itemsContainer.html(
                            '<tr><td colspan="7" class="text-center text-danger py-4">Failed to load items.</td></tr>'
                        );
                    }
                });
            }

            purchaseSelect.on('change', function() {
                loadPurchaseItems($(this).val());
            });
            if (purchaseSelect.val()) loadPurchaseItems(purchaseSelect.val());

            $(document).on('input change', '.qty-input', function() {
                const val = parseFloat($(this).val()) || 0;
                const max = parseFloat($(this).closest('tr').find('.max-returnable-cell').data('max'));
                if (val > max) {
                    $(this).val(max.toFixed(2));
                    showAdminToast(`Cannot return more than ${max.toFixed(2)} units.`, 'error');
                }
                if (val < 0) {
                    $(this).val(0);
                }
                calculateTotals();
            });

            refundedInput.on('input change', function() {
                $('#summary_refunded').text('₹' + (parseFloat($(this).val()) || 0).toFixed(2));
            });

            function calculateTotals() {
                let totalQty = 0;
                $('#returnItemsContainer tr.item-row').each(function() {
                    totalQty += parseFloat($(this).find('.qty-input').val()) || 0;
                });
                $('#sum_subtotal').text(totalQty.toFixed(2) + ' units selected');
                $('#sum_grandtotal').text(totalQty.toFixed(2) + ' units to return');
                $('#summary_refunded').text('₹' + (parseFloat(refundedInput.val()) || 0).toFixed(2));
            }

            $('#returnForm').on('submit', function(e) {
                let total = 0;
                $('.qty-input').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                if (total <= 0) {
                    e.preventDefault();
                    showAdminToast('Please specify return quantity for at least one item.', 'error');
                }
            });
        });
    </script>
@endpush
