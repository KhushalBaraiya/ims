@extends('layouts.admin')
@section('title', __('messages.adjust_stock'))

@section('content')

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.adjust_stock') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('stocks.index') }}">{{ __('messages.stock_overview') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('messages.adjust_stock') }}</li>
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

    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
                    <i class="bx bx-error-circle fs-5 mt-1 flex-shrink-0"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-slider me-2 text-primary"></i>{{ __('messages.adjust_stock_card') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('stocks.store') }}">
                        @csrf

                        {{-- Product --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('messages.product') }} <span class="text-danger">*</span>
                            </label>
                            <select name="product_id" id="product_id"
                                class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_product') }}</option>
                                @foreach ($allProducts as $p)
                                    <option value="{{ $p->id }}" data-stock="{{ $p->stock->quantity ?? 0 }}"
                                        data-unit="{{ $p->unit_code ?? 'Units' }}"
                                        data-alert="{{ $p->minimum_stock_alert ?? 0 }}"
                                        {{ old('product_id', $preselected) == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- Current stock display --}}
                            <div id="stockInfoBox" class="mt-2 d-none">
                                <div class="rounded p-3 border d-flex align-items-center justify-content-between"
                                    style="background:#f8f9fa;">
                                    <div>
                                        <div class="text-muted small fw-semibold">Current Stock</div>
                                        <div id="currentStockVal" class="fw-bold fs-5"></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small fw-semibold">Min Alert Level</div>
                                        <div id="alertLevelVal" class="fw-semibold text-warning"></div>
                                    </div>
                                    <div id="stockStatusBadge"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Adjustment Type --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('messages.adjustment_type') }} <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2" id="typeButtons">
                                @php
                                    $types = [
                                        'Restock' => ['icon' => 'bx-plus-circle', 'color' => 'success', 'sign' => '+'],
                                        'Return' => ['icon' => 'bx-revision', 'color' => 'info', 'sign' => '+'],
                                        'Damage' => ['icon' => 'bx-error', 'color' => 'danger', 'sign' => '-'],
                                        'Write-Off' => ['icon' => 'bx-trash', 'color' => 'warning', 'sign' => '-'],
                                        'Correction' => ['icon' => 'bx-pencil', 'color' => 'primary', 'sign' => '±'],
                                        'Other' => [
                                            'icon' => 'bx-dots-horizontal',
                                            'color' => 'secondary',
                                            'sign' => '±',
                                        ],
                                    ];
                                @endphp
                                @foreach ($types as $type => $meta)
                                    <div class="col-4 col-md-2">
                                        <input type="radio" class="btn-check" name="adjustment_type"
                                            id="type_{{ $type }}" value="{{ $type }}"
                                            {{ old('adjustment_type') === $type ? 'checked' : '' }}>
                                        <label for="type_{{ $type }}"
                                            class="btn btn-outline-{{ $meta['color'] }} w-100 py-2 px-1 d-flex flex-column align-items-center gap-1"
                                            style="font-size:11px;">
                                            <i class="bx {{ $meta['icon'] }} fs-5"></i>
                                            <span>{{ $type }}</span>
                                            <span
                                                class="badge bg-{{ $meta['color'] }} bg-opacity-75">{{ $meta['sign'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('adjustment_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Quantity --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('messages.quantity_change') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" id="qtySign">±</span>
                                <input type="number" name="quantity_change" id="quantity_change" step="0.01"
                                    value="{{ old('quantity_change') }}"
                                    class="form-control form-control-lg @error('quantity_change') is-invalid @enderror"
                                    placeholder="0.00" required>
                                <span class="input-group-text" id="qtyUnit">Units</span>
                            </div>
                            <div class="form-text" id="newQtyPreview"></div>
                            @error('quantity_change')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('messages.notes_optional') }}</label>
                            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Reason for adjustment, reference number…">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bx bx-save me-1"></i> {{ __('messages.save_adjustment') }}
                            </button>
                            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- Side info panel --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-light mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3"><i class="bx bx-info-circle me-2 text-info"></i>Adjustment Types</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><span class="badge bg-success me-1">Restock</span> Add stock received from
                            supplier</li>
                        <li class="mb-2"><span class="badge bg-info me-1">Return</span> Customer returned item added
                            back</li>
                        <li class="mb-2"><span class="badge bg-danger me-1">Damage</span> Remove damaged / broken units
                        </li>
                        <li class="mb-2"><span class="badge bg-warning text-dark me-1">Write-Off</span> Permanently
                            remove expired/lost items</li>
                        <li class="mb-2"><span class="badge bg-primary me-1">Correction</span> Fix a data entry mistake
                        </li>
                        <li class="mb-0"><span class="badge bg-secondary me-1">Other</span> Any other reason</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3"><i class="bx bx-bulb me-2 text-warning"></i>Quick Tips</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Use <strong>positive</strong> numbers for Restock/Return</li>
                        <li class="mb-1">Use <strong>negative</strong> numbers for Damage/Write-Off</li>
                        <li class="mb-1">Correction can be + or − to fix balance</li>
                        <li class="mb-0">Every adjustment is logged in history</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const addTypes = ['Restock', 'Return'];
            const removeTypes = ['Damage', 'Write-Off'];

            function updateQtySign() {
                const type = $('input[name="adjustment_type"]:checked').val();
                if (addTypes.includes(type)) {
                    $('#qtySign').text('+').removeClass('text-danger').addClass('text-success');
                } else if (removeTypes.includes(type)) {
                    $('#qtySign').text('−').removeClass('text-success').addClass('text-danger');
                } else {
                    $('#qtySign').text('±').removeClass('text-success text-danger');
                }
                updatePreview();
            }

            function updatePreview() {
                const sel = $('#product_id option:selected');
                const stock = parseFloat(sel.data('stock')) || 0;
                const unit = sel.data('unit') || 'Units';
                const type = $('input[name="adjustment_type"]:checked').val();
                let qty = parseFloat($('#quantity_change').val()) || 0;

                if (removeTypes.includes(type)) qty = -Math.abs(qty);
                else if (addTypes.includes(type)) qty = Math.abs(qty);

                const newQty = stock + qty;
                const $p = $('#newQtyPreview');
                if (sel.val() && qty !== 0) {
                    const color = newQty < 0 ? 'text-danger' : (newQty === 0 ? 'text-warning' : 'text-success');
                    $p.html(
                        `New stock after adjustment: <strong class="${color}">${newQty.toFixed(2)} ${unit}</strong>`);
                } else {
                    $p.text('');
                }
            }

            function updateStockInfo() {
                const sel = $('#product_id option:selected');
                const stock = parseFloat(sel.data('stock')) || 0;
                const unit = sel.data('unit') || 'Units';
                const alert = parseFloat(sel.data('alert')) || 0;

                if (!sel.val()) {
                    $('#stockInfoBox').addClass('d-none');
                    $('#qtyUnit').text('Units');
                    return;
                }
                $('#qtyUnit').text(unit);
                $('#stockInfoBox').removeClass('d-none');
                const isOut = stock <= 0;
                const isLow = stock > 0 && stock <= alert;
                const color = isOut ? 'text-danger' : (isLow ? 'text-warning' : 'text-success');
                $('#currentStockVal').html(`<span class="${color}">${stock.toFixed(2)} ${unit}</span>`);
                $('#alertLevelVal').text(`${alert.toFixed(2)} ${unit}`);
                if (isOut) {
                    $('#stockStatusBadge').html('<span class="badge bg-danger">Out of Stock</span>');
                } else if (isLow) {
                    $('#stockStatusBadge').html('<span class="badge bg-warning text-dark">Low Stock</span>');
                } else {
                    $('#stockStatusBadge').html('<span class="badge bg-success">In Stock</span>');
                }
                updatePreview();
            }

            $('#product_id').on('change', updateStockInfo);
            $('input[name="adjustment_type"]').on('change', updateQtySign);
            $('#quantity_change').on('input', updatePreview);

            // Init on page load (preselected product)
            if ($('#product_id').val()) updateStockInfo();
        });
    </script>
@endpush
