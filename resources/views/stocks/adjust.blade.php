@extends('layouts.admin')
@section('title', __('messages.create_adjustment'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.create_adjustment') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('stocks.history') }}">{{ __('messages.stock_adjustments') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.create_adjustment') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    {{-- Error messages --}}
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 mb-4 py-2 px-3 shadow-sm border-0">
            <i class="bx bx-error-circle fs-5 mt-1 flex-shrink-0 text-danger"></i>
            <div>
                <strong class="text-danger">{{ __('messages.fix_errors') }}</strong>
                <ul class="mb-0 mt-1 ps-3 text-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('stocks.store_adjustment') }}">
        @csrf

        <div class="row g-4">

            {{-- ── Left: Voucher Details ── --}}
            <div class="col-lg-4 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold text-primary">
                            <i class="bx bx-info-circle me-2"></i>{{ __('messages.voucher_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.voucher_no') }}</label>
                            <div class="form-control bg-light d-flex align-items-center gap-2 text-muted small fw-semibold"
                                style="cursor:default;">
                                <i class="bx bx-revision text-success"></i>
                                {{ __('messages.auto_generated') }} (ADJ-YYYYMMDD-XXXX)
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.transaction_date') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="transaction_date"
                                class="form-control @error('transaction_date') is-invalid @enderror"
                                value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">{{ __('messages.global_remarks') }}</label>
                            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="{{ __('messages.adj_reason_ph') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right: Product Search & Table ── --}}
            <div class="col-lg-8 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold text-primary">
                            <i class="bx bx-list-ol me-2"></i>{{ __('messages.adjust_products') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Select2 AJAX product search --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('messages.select_product_to_add') }}</label>
                            <select id="adjProductSelect" class="form-select" style="width:100%;">
                                <option value=""></option>
                            </select>
                        </div>

                        {{-- Products table --}}
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="adjustmentItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.adj_product_col') }}</th>
                                        <th>{{ __('messages.current_stock') }}</th>
                                        <th class="text-center" style="width:190px;">{{ __('messages.adj_type') }}</th>
                                        <th class="text-center" style="width:140px;">{{ __('messages.adj_quantity') }}</th>
                                        <th class="text-center" style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="adjustmentItemsContainer"></tbody>
                            </table>
                            <div id="emptyTableMsg" class="text-center py-5 text-muted">
                                <i class="bx bx-package" style="font-size:2.5rem;opacity:.3;"></i>
                                <p class="mt-2 mb-0">{{ __('messages.no_products_adj') }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-save me-1"></i> {{ __('messages.save_stock_adj') }}
                    </button>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('styles')
    <style>
        /* ── Select2 overrides — theme aware ──────────────────────────────── */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border-radius: 8px;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 10px;
            box-shadow: 0 10px 32px rgba(0, 0, 0, .14);
            border-color: rgba(105, 108, 255, .25);
            overflow: hidden;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 8px 14px;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background: rgba(105, 108, 255, .12) !important;
            color: inherit !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown {
            box-shadow: 0 10px 32px rgba(0, 0, 0, .45);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowCount = 0;

            // ── Select2 AJAX Product Search ──────────────────────────────────
            $('#adjProductSelect').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '{{ __('messages.type_product_sku_barcode') }}',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('products.search') }}',
                    dataType: 'json',
                    delay: 200,
                    data: function(params) {
                        return {
                            query: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(p) {
                                return {
                                    id: p.id,
                                    text: p.name,
                                    name: p.name,
                                    sku: p.sku,
                                    barcode: p.barcode,
                                    stock: p.stock,
                                    out_of_stock: p.out_of_stock,
                                    unit: p.unit,
                                    image_url: p.image_url
                                };
                            })
                        };
                    },
                    cache: false
                },
                templateResult: function(p) {
                    if (p.loading) {
                        return $(
                        '<span><i class="bx bx-loader-alt bx-spin me-2"></i>Searching…</span>');
                    }
                    if (!p.id) return p.text;

                    const img = p.image_url || 'https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Img';
                    const stockVal = parseFloat(p.stock) || 0;
                    const stockBadge = p.out_of_stock ?
                        `<span class="badge bg-danger-subtle text-danger" style="font-size:10px;">Out of stock</span>` :
                        `<span class="badge bg-success-subtle text-success" style="font-size:10px;">${stockVal.toFixed(0)} ${p.unit || 'PCS'} in stock</span>`;

                    return $(`
                        <div class="d-flex align-items-center gap-3 py-1">
                            <img src="${img}"
                                 onerror="this.src='https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Img'"
                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;
                                        border:1px solid rgba(0,0,0,.1);flex-shrink:0;">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:13px;">${p.name}</div>
                                <div class="text-muted" style="font-size:11px;">
                                    SKU: ${p.sku}${p.barcode ? ' &bull; ' + p.barcode : ''}
                                </div>
                            </div>
                            <div class="text-end flex-shrink-0" style="font-size:11px;">${stockBadge}</div>
                        </div>`);
                },
                templateSelection: function(p) {
                    if (!p.id) return p.text || '{{ __('messages.type_product_sku_barcode') }}';
                    return $(`<span><i class="bx bx-package me-1"></i>${p.name}
                               <span class="text-muted small">(${p.sku})</span></span>`);
                }
            });

            // Load all products on dropdown open (no typing required)
            $('#adjProductSelect').on('select2:open', function() {
                setTimeout(function() {
                    var $s = $('.select2-container--open .select2-search__field');
                    if ($s.length) $s.val('').trigger('input');
                }, 50);
            });

            // On product selected → add row
            $('#adjProductSelect').on('select2:select', function(e) {
                const p = e.params.data;
                if (!p || !p.id) return;

                // Duplicate check
                let dup = false;
                $('#adjustmentItemsContainer tr').each(function() {
                    if ($(this).data('product-id') == p.id) {
                        dup = true;
                        return false;
                    }
                });
                if (dup) {
                    showAdminToast('{{ __('messages.product_already_added') }}', 'warning');
                } else {
                    addProductRow({
                        id: p.id,
                        name: p.name,
                        sku: p.sku,
                        stock: parseFloat(p.stock) || 0,
                        unit: p.unit || 'PCS',
                        image: p.image_url ||
                            'https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Img',
                        qty: 1,
                        type: 'Plus'
                    });
                }

                // Reset select2
                $(this).val(null).trigger('change');
            });

            // ── Add Row ──────────────────────────────────────────────────────
            function addProductRow(p) {
                $('#emptyTableMsg').addClass('d-none');

                const rowHtml = `
                <tr class="item-row" data-product-id="${p.id}">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="${p.image}"
                                 onerror="this.src='https://placehold.co/36x36/e2e8f0/94a3b8?text=No+Img'"
                                 class="rounded flex-shrink-0"
                                 style="width:36px;height:36px;object-fit:cover;border:1px solid rgba(0,0,0,.08);">
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
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="items[${rowCount}][type]"
                                   id="type_plus_${rowCount}" value="Plus"
                                   ${p.type === 'Plus' ? 'checked' : ''} autocomplete="off">
                            <label class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center gap-1"
                                   for="type_plus_${rowCount}" style="font-size:11px;font-weight:600;">
                                <i class="bx bx-plus-circle"></i> Plus (+)
                            </label>
                            <input type="radio" class="btn-check" name="items[${rowCount}][type]"
                                   id="type_minus_${rowCount}" value="Minus"
                                   ${p.type === 'Minus' ? 'checked' : ''} autocomplete="off">
                            <label class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center gap-1"
                                   for="type_minus_${rowCount}" style="font-size:11px;font-weight:600;">
                                <i class="bx bx-minus-circle"></i> Minus (-)
                            </label>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm">
                            <input type="number" step="1" min="1"
                                   name="items[${rowCount}][quantity]"
                                   class="form-control text-center qty-input"
                                   value="${Math.round(p.qty)}" required>
                            <span class="input-group-text px-1" style="font-size:10px;">${p.unit}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-sm btn-icon btn-outline-danger rounded-circle remove-row-btn"
                                style="width:28px;height:28px;padding:0;" title="Remove">
                            <i class="bx bx-trash" style="font-size:13px;"></i>
                        </button>
                    </td>
                </tr>`;

                $('#adjustmentItemsContainer').append(rowHtml);
                rowCount++;
            }

            // ── Remove Row ───────────────────────────────────────────────────
            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if ($('#adjustmentItemsContainer tr').length === 0) {
                    $('#emptyTableMsg').removeClass('d-none');
                }
            });

            // ── Pre-populate on validation fail / preselected products ───────
            @if (old('items'))
                @foreach (old('items') as $oldItem)
                    @php $oldProd = \App\Models\Product::with('stock')->find($oldItem['product_id'] ?? null); @endphp
                    @if ($oldProd)
                        addProductRow({
                            id: "{{ $oldProd->id }}",
                            name: "{{ addslashes($oldProd->name) }}",
                            sku: "{{ $oldProd->code }}",
                            stock: parseFloat("{{ $oldProd->stock->quantity ?? 0 }}"),
                            unit: "{{ $oldProd->unit_code ?? 'PCS' }}",
                            image: "{{ $oldProd->image ? asset('uploads/products/' . $oldProd->image) : 'https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Image' }}",
                            qty: parseFloat("{{ $oldItem['quantity'] ?? 1 }}"),
                            type: "{{ $oldItem['type'] ?? 'Plus' }}"
                        });
                    @endif
                @endforeach
            @elseif (!empty($preselectedProducts) && $preselectedProducts->count() > 0)
                @foreach ($preselectedProducts as $preProd)
                    addProductRow({
                        id: "{{ $preProd->id }}",
                        name: "{{ addslashes($preProd->name) }}",
                        sku: "{{ $preProd->code }}",
                        stock: parseFloat("{{ $preProd->stock->quantity ?? 0 }}"),
                        unit: "{{ $preProd->unit_code ?? 'PCS' }}",
                        image: "{{ $preProd->image ? asset('uploads/products/' . $preProd->image) : 'https://placehold.co/40x40/e2e8f0/94a3b8?text=No+Image' }}",
                        qty: 1,
                        type: 'Minus'
                    });
                @endforeach
            @endif
        });
    </script>
@endpush
