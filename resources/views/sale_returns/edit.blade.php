@extends('layouts.admin')
@section('title', 'Edit Sales Return — ' . $saleReturn->return_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Sales Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
                    <li class="breadcrumb-item active">{{ $saleReturn->return_no }}</li>
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

    <form method="POST" action="{{ route('sale-returns.update', $saleReturn->id) }}" id="returnForm" novalidate>
        @csrf @method('PUT')

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
                            <label class="form-label fw-semibold">Return No</label>
                            <input type="text" class="form-control bg-light fw-bold" value="{{ $saleReturn->return_no }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Invoice No</label>
                            <input type="text" class="form-control bg-light fw-bold text-primary"
                                value="{{ $saleReturn->sale->invoice_no }}" readonly>
                            <input type="hidden" name="sale_id" value="{{ $saleReturn->sale_id }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Customer</label>
                            <input type="text" class="form-control bg-light fw-bold"
                                value="{{ $saleReturn->customer->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                            <input type="date" name="return_date"
                                class="form-control flatpickr-date @error('return_date') is-invalid @enderror"
                                value="{{ old('return_date', $saleReturn->return_date) }}" required>
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference No</label>
                            <input type="text" name="reference_no" class="form-control"
                                value="{{ old('reference_no', $saleReturn->reference_no) }}"
                                placeholder="Optional reference...">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Completed"
                                    {{ old('status', $saleReturn->status) === 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="Pending"
                                    {{ old('status', $saleReturn->status) === 'Pending' ? 'selected' : '' }}>Pending
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
                                value="{{ old('refunded_amount', $saleReturn->refunded_amount) }}" required>
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
                        <span class="badge bg-label-primary">{{ $saleReturn->sale->items->count() }} product(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px;">Image</th>
                                        <th>Product</th>
                                        <th class="text-center">Sold Qty</th>
                                        <th class="text-center">Other Returns</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-center">Avail. Return</th>
                                        <th class="text-center" style="width:110px">Return Qty</th>
                                        <th>Reason</th>
                                        <th class="text-end">Subtotal</th>
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
                                        @endphp
                                        @if ($maxReturnable > 0 || $currentQty > 0)
                                            <tr class="item-row" data-product-id="{{ $item->product_id }}">
                                                <td>
                                                    @if ($item->product->image)
                                                        <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                            class="tbl-img rounded" onerror="imgError(this)">
                                                    @else
                                                        <div
                                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
                                                            <i class="bx bx-package text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <small class="d-block text-muted">{{ $item->product->code }}</small>
                                                    <input type="hidden" name="items[{{ $rowCount }}][product_id]"
                                                        value="{{ $item->product_id }}">
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ (int) $item->quantity }}
                                                    <small
                                                        class="text-muted">{{ $item->product->unit_code ?? 'PCS' }}</small>
                                                </td>
                                                <td class="text-center text-warning fw-semibold">
                                                    {{ (int) $otherReturned }}
                                                </td>
                                                <td class="text-end fw-semibold price-cell"
                                                    data-price="{{ $item->unit_price }}">
                                                    {{ format_currency($item->unit_price) }}
                                                </td>
                                                <td class="text-center fw-bold text-success max-returnable-cell"
                                                    data-max="{{ $maxReturnable }}">
                                                    {{ $maxReturnable }}
                                                    <small
                                                        class="text-muted fw-normal">{{ $item->product->unit_code ?? 'PCS' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" step="1" min="0"
                                                        max="{{ $maxReturnable }}"
                                                        name="items[{{ $rowCount }}][quantity]"
                                                        value="{{ old("items.{$rowCount}.quantity", $currentQty) }}"
                                                        class="qty-input form-control form-control-sm text-center"
                                                        style="width:88px;margin:auto;">
                                                </td>
                                                <td>
                                                    <input type="text" name="items[{{ $rowCount }}][reason]"
                                                        value="{{ old("items.{$rowCount}.reason", $currentReason) }}"
                                                        class="form-control form-control-sm" placeholder="Reason...">
                                                </td>
                                                <td class="text-end fw-bold subtotal-cell">
                                                    {{ format_currency($currentQty * $item->unit_price) }}
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
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bx bx-note me-2 text-warning"></i>Return Notes
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea name="notes" rows="5" class="form-control" placeholder="Return reasons, item conditions..."
                                    style="resize:vertical;">{{ old('notes', $saleReturn->notes) }}</textarea>
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
                                        <span class="fw-bold"
                                            id="sum_subtotal">{{ format_currency($saleReturn->sub_total) }}</span>
                                    </li>
                                    <li
                                        class="d-flex justify-content-between py-2 border-bottom bg-label-primary rounded px-2">
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
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
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
                $('#summary_refunded').text(fmt(parseFloat($(this).val()) || 0));
            });

            function calcTotals() {
                let subtotal = 0;
                $('#returnItemsContainer tr.item-row').each(function() {
                    const qty = parseInt($(this).find('.qty-input').val()) || 0;
                    const price = parseFloat($(this).find('.price-cell').data('price')) || 0;
                    const rowSub = price * qty;
                    $(this).find('.subtotal-cell').text(fmt(rowSub));
                    subtotal += rowSub;
                });
                $('#sum_subtotal').text(fmt(subtotal));
                $('#sum_grandtotal').text(fmt(subtotal));
                const currentRefunded = parseFloat(refundedInput.val()) || 0;
                if (currentRefunded > subtotal) {
                    refundedInput.val(subtotal.toFixed(2));
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

