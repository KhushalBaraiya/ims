@extends('layouts.admin')
@section('title', 'Edit Purchase Return')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Purchase Return</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase-returns.index') }}">Purchase Returns</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('purchase-returns.update', $purchaseReturn->id) }}" id="returnForm" novalidate>
        @csrf @method('PUT')

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
                            <label class="form-label fw-semibold">Return No</label>
                            <input type="text" class="form-control bg-light fw-bold"
                                value="{{ $purchaseReturn->return_no }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Purchase Order</label>
                            <input type="hidden" name="purchase_id" value="{{ $purchaseReturn->purchase_id }}">
                            <input type="text" class="form-control bg-light fw-bold"
                                value="{{ $purchaseReturn->purchase->purchase_no ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                            <input type="date" name="return_date"
                                class="form-control flatpickr-date @error('return_date') is-invalid @enderror"
                                value="{{ old('return_date', $purchaseReturn->return_date) }}" required>
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference No</label>
                            <input type="text" name="reference_no" class="form-control"
                                value="{{ old('reference_no', $purchaseReturn->reference_no ?? '') }}"
                                placeholder="Optional...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Completed"
                                    {{ old('status', $purchaseReturn->status) === 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="Pending"
                                    {{ old('status', $purchaseReturn->status) === 'Pending' ? 'selected' : '' }}>Pending
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
                        <input type="number" step="0.01" name="refunded_amount"
                            class="form-control @error('refunded_amount') is-invalid @enderror"
                            value="{{ old('refunded_amount', $purchaseReturn->refunded_amount) }}" required>
                        @error('refunded_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2 text-info"></i>Return Items</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Product</th>
                                        <th class="text-center">Orig. Qty Returned</th>
                                        <th class="text-center" style="width:110px">Return Qty</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseReturn->items as $index => $item)
                                        <tr>
                                            <td class="ps-3 fw-semibold">
                                                {{ $item->product->name }}
                                                <input type="hidden" name="items[{{ $index }}][product_id]"
                                                    value="{{ $item->product_id }}">
                                            </td>
                                            <td class="text-center text-muted fw-semibold">
                                                {{ (int) $item->quantity }}</td>
                                            <td class="text-center">
                                                <input type="number" step="1" min="0"
                                                    name="items[{{ $index }}][quantity]"
                                                    value="{{ old("items.{$index}.quantity", (int) $item->quantity) }}"
                                                    class="qty-input form-control form-control-sm text-center"
                                                    style="width:90px;margin:auto;">
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{ $index }}][reason]"
                                                    value="{{ old("items.{$index}.reason", $item->reason ?? '') }}"
                                                    class="form-control form-control-sm" placeholder="Reason...">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">Notes</h6>
                            </div>
                            <div class="card-body p-3">
                                <textarea name="notes" rows="5" class="form-control" placeholder="Return reason, conditions...">{{ old('notes', $purchaseReturn->notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">Refund Summary</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="bg-light rounded p-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted fw-semibold">Refunded Amount</span>
                                    <span
                                        class="fw-bold text-success fs-5">₹{{ number_format($purchaseReturn->refunded_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Update Return
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('input change', '.qty-input', function() {
                const val = parseInt($(this).val()) || 0;
                if (val < 0) $(this).val(0);
            });

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
