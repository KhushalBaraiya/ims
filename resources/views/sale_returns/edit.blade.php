@extends('layouts.admin')
@section('title', 'Edit Sales Return — ' . $saleReturn->return_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Sales Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
                    <li class="breadcrumb-item active">{{ $saleReturn->return_no }}</li>
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

    <form action="{{ route('sale-returns.update', $saleReturn->id) }}" id="returnForm" method="POST" novalidate>
        @csrf @method('PUT')

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
                            <label class="form-label fw-semibold">Return No</label>
                            <input class="form-control bg-light fw-bold" readonly type="text"
                                value="{{ $saleReturn->return_no }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Invoice No</label>
                            <input class="form-control bg-light fw-bold text-primary" readonly type="text"
                                value="{{ $saleReturn->sale->invoice_no }}">
                            <input name="sale_id" type="hidden" value="{{ $saleReturn->sale_id }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Customer</label>
                            <input class="form-control bg-light fw-bold" readonly type="text"
                                value="{{ $saleReturn->customer->name }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                            <input class="form-control flatpickr-date @error('return_date') is-invalid @enderror"
                                name="return_date" required type="date"
                                value="{{ old('return_date', $saleReturn->return_date) }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference No</label>
                            <input class="form-control" name="reference_no" placeholder="Optional reference..."
                                type="text" value="{{ old('reference_no', $saleReturn->reference_no) }}">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option {{ old('status', $saleReturn->status) === 'Completed' ? 'selected' : '' }}
                                    value="Completed">Completed
                                </option>
                                <option {{ old('status', $saleReturn->status) === 'Pending' ? 'selected' : '' }}
                                    value="Pending">Pending
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
                                value="{{ old('refunded_amount', $saleReturn->refunded_amount) }}">
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
                        <span class="badge bg-label-primary">{{ $saleReturn->sale->items->count() }} product(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px;">Image</th>
                                        <th>Product</th>
                                        <th class="text-center" style="width:130px;">Quantity</th>
                                        <th class="text-center" style="width:180px;">Pricing</th>
                                        <th class="text-end" style="width:120px;">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody id="returnItemsContainer">
                                    @php $rowCount = 0; @endphp
                                    @foreach ($saleReturn->sale->items as $item)
                                        @php
                                            $otherReturned = \App\Models\SaleReturnItem::whereHas(
                                                'saleReturn',
                                                fn($q) => $q
                                                    ->where('sale_id', $saleReturn->sale_id)
                                                    ->where('id', '!=', $saleReturn->id)
                                                    ->where('status', 'Completed'),
                                            )
                                                ->where('product_id', $item->product_id)
                                                ->sum('quantity');
                                            $currentItem = $saleReturn->items->firstWhere(
                                                'product_id',
                                                $item->product_id,
                                            );
                                            $currentQty = $currentItem ? (int) $currentItem->quantity : 0;
                                            $currentReason = $currentItem?->reason ?? '';
                                            $maxReturnable = max(0, (int) ($item->quantity - $otherReturned));

                                            $origQty = max(1.0, (float) $item->quantity);
                                            $unitDisc = (float) ($item->discount_amount / $origQty);
                                            $unitTax = (float) ($item->tax_amount / $origQty);

                                            $rowDisc = round($unitDisc * $currentQty, 2);
                                            $rowTax = round($unitTax * $currentQty, 2);
                                            $rowTotal = $currentQty * $item->unit_price + $rowTax - $rowDisc;
                                        @endphp
                                        @if ($maxReturnable > 0 || $currentQty > 0)
                                            <tr class="item-row" data-product-id="{{ $item->product_id }}">
                                                <td>
                                                    @if ($item->product->image)
                                                        <img class="tbl-img rounded" onerror="imgError(this)"
                                                            src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                            style="width:36px;height:36px;object-fit:cover;">
                                                    @else
                                                        <div class="tbl-img d-flex align-items-center justify-content-center bg-light img-fallback rounded"
                                                            style="width:36px;height:36px;">
                                                            <i class="bx bx-package text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary mb-0" style="font-size:13px;">
                                                        {{ $item->product->name }}</div>
                                                    <div class="text-muted small" style="font-size:11px;">SKU:
                                                        {{ $item->product->code }}</div>
                                                    <input name="items[{{ $rowCount }}][product_id]" type="hidden"
                                                        value="{{ $item->product_id }}">
                                                </td>
                                                <td class="text-center">
                                                    <div class="small text-muted mb-1">Sold Qty:
                                                        {{ (int) $item->quantity }}</div>
                                                    <input class="qty-input form-control form-control-sm text-center"
                                                        max="{{ (int) $item->quantity }}" min="0"
                                                        name="items[{{ $rowCount }}][quantity]" step="1"
                                                        style="width:80px;margin:auto;" type="number"
                                                        value="{{ old("items.{$rowCount}.quantity", $currentQty) }}">
                                                </td>
                                                <td class="text-muted small text-center">
                                                    <div>Net Price: <span class="fw-semibold text-dark price-cell"
                                                            data-price="{{ $item->unit_price }}">{{ format_currency($item->unit_price) }}</span>
                                                    </div>
                                                    <div>Discount: <span class="disc-cell"
                                                            data-disc="{{ $unitDisc }}">{{ format_currency($rowDisc) }}</span>
                                                    </div>
                                                    <div>Tax: <span class="tax-cell"
                                                            data-tax="{{ $unitTax }}">{{ format_currency($rowTax) }}</span>
                                                    </div>
                                                </td>
                                                <td class="fw-bold subtotal-cell text-end">
                                                    {{ format_currency($rowTotal) }}
                                                </td>
                                            </tr>
                                            @php $rowCount++; @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
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
                                    style="resize:vertical;">{{ old('notes', $saleReturn->notes) }}</textarea>
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
                                        <span class="fw-bold"
                                            id="sum_subtotal">{{ format_currency($saleReturn->sub_total) }}</span>
                                    </li>
                                    <li
                                        class="d-flex justify-content-between border-bottom bg-label-primary rounded px-2 py-2">
                                        <span class="fw-bold small">Grand Total Refund</span>
                                        <span class="fw-bold text-primary"
                                            id="sum_grandtotal">{{ format_currency($saleReturn->grand_total) }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between py-2">
                                        <span class="text-muted small fw-semibold">Customer Refund</span>
                                        <span class="fw-bold text-success"
                                            id="summary_refunded">{{ format_currency($saleReturn->refunded_amount) }}</span>
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
                    <button class="btn btn-primary" id="submitBtn" type="submit">
                        <i class="bx bx-save me-1"></i> Update Return
                    </button>
                </div>

            </div>{{-- /col-lg-9 --}}
        </div>{{-- /row --}}
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            const refundedInput = $('#refunded_amount');
            const currencySymbol = '{{ addslashes(optional(current_currency())->symbol ?? '₹') }}';
            const fmt = v => currencySymbol + parseFloat(v || 0).toFixed(2);

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

                $('#returnItemsContainer tr.item-row').each(function() {
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

                // Auto-set refunded amount to grand total if 0 or exceeds grand total
                const currentRefunded = parseFloat(refundedInput.val()) || 0;
                if (currentRefunded === 0 || currentRefunded > refundTotal) {
                    refundedInput.val(refundTotal.toFixed(2));
                }
                $('#summary_refunded').text(fmt(parseFloat(refundedInput.val()) || 0));
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

            calcTotals();
        });
    </script>
@endpush
