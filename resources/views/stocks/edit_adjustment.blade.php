@extends('layouts.admin')
@section('title', __('messages.adjust_stock'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Stock Adjustment</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('stocks.index') }}">{{ __('messages.stock_overview') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('stocks.history') }}">{{ __('messages.stock_history') }}</a></li>
                    <li class="breadcrumb-item active">Edit Adjustment</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary">
                <i class="bx bx-history me-1"></i> {{ __('messages.stock_history') }}
            </a>
            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Error messages --}}
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 mb-4 py-2 px-3 shadow-sm border-0">
            <i class="bx bx-error-circle fs-5 mt-1 flex-shrink-0 text-danger"></i>
            <div>
                <strong class="text-danger">Please fix the following errors:</strong>
                <ul class="mb-0 mt-1 ps-3 text-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('stocks.update_adjustment', $voucherNo) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Left Side: Voucher Info --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                        <h6 class="mb-0 fw-semibold text-primary">
                            <i class="bx bx-info-circle me-2"></i>Voucher Details
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Voucher No</label>
                            <input type="text" class="form-control bg-light fw-bold" value="{{ $voucherNo }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', $transactionDate) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Global Remarks / Notes</label>
                            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="Reason for adjustment, e.g. Year-end inventory audit...">{{ old('notes', $notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Products Multi-select & List --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                        <h6 class="mb-0 fw-semibold text-primary">
                            <i class="bx bx-list-ol me-2"></i>Adjust Products
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        {{-- Product selector --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select Product to Add</label>
                            <div class="d-flex gap-2">
                                <select id="productSelectDropdown" class="form-select select2">
                                    <option value="">Choose a product...</option>
                                    @foreach ($allProducts as $p)
                                        <option value="{{ $p->id }}" 
                                                data-name="{{ $p->name }}" 
                                                data-sku="{{ $p->code }}" 
                                                data-stock="{{ $p->stock->quantity ?? 0 }}"
                                                data-unit="{{ $p->unit_code ?? 'Units' }}"
                                                data-image="{{ $p->image ? asset('uploads/products/' . $p->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}">
                                            {{ $p->name }} ({{ $p->code }}) — Stock: {{ number_format($p->stock->quantity ?? 0, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" id="addProductBtn" class="btn btn-primary d-flex align-items-center gap-1">
                                    <i class="bx bx-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        {{-- Selected products table --}}
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="adjustmentItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th class="text-center" style="width: 190px;">Type (+ / -)</th>
                                        <th class="text-center" style="width: 140px;">Quantity</th>
                                        <th class="text-center" style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="adjustmentItemsContainer">
                                    {{-- Rows appended dynamically --}}
                                </tbody>
                            </table>
                            <div id="emptyTableMsg" class="text-center py-5 text-muted d-none">
                                <i class="bx bx-package" style="font-size:2.5rem;opacity:.3;"></i>
                                <p class="mt-2 mb-0">No products added yet. Select a product above to add.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action & Save buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save me-1"></i> Update Stock Adjustment
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowCount = 0;

            // Handle Add Product click
            $('#addProductBtn').on('click', function() {
                const select = $('#productSelectDropdown');
                const selectedVal = select.val();
                if (!selectedVal) {
                    return;
                }

                // Check if already added
                let isDuplicate = false;
                $('#adjustmentItemsContainer tr').each(function() {
                    if ($(this).data('product-id') == selectedVal) {
                        isDuplicate = true;
                        return false;
                    }
                });

                if (isDuplicate) {
                    showAdminToast('Product already added to adjustment list.', 'warning');
                    select.val('').trigger('change');
                    return;
                }

                const option = select.find('option:selected');
                const name = option.data('name');
                const sku = option.data('sku');
                const stock = parseFloat(option.data('stock')) || 0;
                const unit = option.data('unit') || 'Units';
                const image = option.data('image');

                addProductRow({
                    id: selectedVal,
                    name: name,
                    sku: sku,
                    stock: stock,
                    unit: unit,
                    image: image,
                    qty: 1,
                    type: 'Plus'
                });

                select.val('').trigger('change');
            });

            function addProductRow(p) {
                $('#emptyTableMsg').addClass('d-none');

                const rowHtml = `
                    <tr class="item-row" data-product-id="${p.id}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="${p.image}" class="rounded" style="width:36px;height:36px;object-fit:cover;">
                                <div>
                                    <div class="fw-semibold small">${p.name}</div>
                                    <div class="text-muted" style="font-size:11px;">SKU: ${p.sku}</div>
                                    <input type="hidden" name="items[${rowCount}][product_id]" value="${p.id}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold small text-muted">${p.stock.toFixed(2)} ${p.unit}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group w-100" role="group" aria-label="Adjustment Type toggle">
                                <input type="radio" class="btn-check" name="items[${rowCount}][type]" id="type_plus_${rowCount}" value="Plus" ${p.type === 'Plus' ? 'checked' : ''} autocomplete="off">
                                <label class="btn btn-outline-success btn-sm px-2 py-1 d-flex align-items-center justify-content-center gap-1" for="type_plus_${rowCount}" style="font-size:11px; font-weight:600; cursor:pointer;">
                                    <i class="bx bx-plus-circle"></i> Plus (+)
                                </label>
                                <input type="radio" class="btn-check" name="items[${rowCount}][type]" id="type_minus_${rowCount}" value="Minus" ${p.type === 'Minus' ? 'checked' : ''} autocomplete="off">
                                <label class="btn btn-outline-danger btn-sm px-2 py-1 d-flex align-items-center justify-content-center gap-1" for="type_minus_${rowCount}" style="font-size:11px; font-weight:600; cursor:pointer;">
                                    <i class="bx bx-minus-circle"></i> Minus (-)
                                </label>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0.01" name="items[${rowCount}][quantity]" class="form-control text-center py-1 qty-input" value="${p.qty}" required>
                                <span class="input-group-text px-1 small" style="font-size:10px;">${p.unit}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-row-btn" style="width:28px;height:28px;padding:0;">
                                <i class="bx bx-trash" style="font-size:13px;"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#adjustmentItemsContainer').append(rowHtml);
                rowCount++;
            }

            // Remove row action
            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if ($('#adjustmentItemsContainer tr').length === 0) {
                    $('#emptyTableMsg').removeClass('d-none');
                }
            });

            // Populate existing rows from controller load
            @if (!old('items'))
                @foreach ($adjustments as $adj)
                    @php
                        $qty = abs($adj->quantity_change);
                        $type = $adj->quantity_change >= 0 ? 'Plus' : 'Minus';
                    @endphp
                    addProductRow({
                        id: "{{ $adj->product_id }}",
                        name: "{{ addslashes($adj->product->name) }}",
                        sku: "{{ $adj->product->code }}",
                        stock: parseFloat("{{ ($adj->product->stock->quantity ?? 0) - $adj->quantity_change }}"),
                        unit: "{{ $adj->product->unit_code ?? 'Units' }}",
                        image: "{{ $adj->product->image ? asset('uploads/products/' . $adj->product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}",
                        qty: parseFloat("{{ $qty }}"),
                        type: "{{ $type }}"
                    });
                @endforeach
            @else
                // Populate old items on validation failure (if any)
                @foreach (old('items') as $oldItem)
                    @php
                        $oldProd = \App\Models\Product::with('stock')->find($oldItem['product_id'] ?? null);
                    @endphp
                    @if ($oldProd)
                        addProductRow({
                            id: "{{ $oldProd->id }}",
                            name: "{{ addslashes($oldProd->name) }}",
                            sku: "{{ $oldProd->code }}",
                            stock: parseFloat("{{ $oldProd->stock->quantity ?? 0 }}"),
                            unit: "{{ $oldProd->unit_code ?? 'Units' }}",
                            image: "{{ $oldProd->image ? asset('uploads/products/' . $oldProd->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}",
                            qty: parseFloat("{{ $oldItem['quantity'] ?? 1 }}"),
                            type: "{{ $oldItem['type'] ?? 'Plus' }}"
                        });
                    @endif
                @endforeach
            @endif
        });
    </script>
@endpush
