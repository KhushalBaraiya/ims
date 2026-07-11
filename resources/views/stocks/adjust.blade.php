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

                        {{-- Live search input --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('messages.select_product_to_add') }}</label>
                            <div class="position-relative">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                                    <input type="text" id="adjProductSearch" class="form-control"
                                        placeholder="{{ __('messages.type_product_barcode') }}" autocomplete="off">
                                </div>
                                <div id="adjAutocompleteResults" class="position-absolute w-100 d-none rounded"
                                    style="z-index:1050;max-height:280px;overflow-y:auto;top:100%;left:0;">
                                </div>
                            </div>
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
        /* ── Autocomplete container ── */
        #adjAutocompleteResults {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 32px rgba(0, 0, 0, .12);
            overflow: hidden;
        }

        [data-bs-theme="dark"] #adjAutocompleteResults {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .12);
            box-shadow: 0 10px 32px rgba(0, 0, 0, .4);
        }

        /* ── Each result item ── */
        .adj-ac-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s;
        }

        .adj-ac-item:last-child {
            border-bottom: none;
        }

        .adj-ac-item:hover {
            background: rgba(105, 108, 255, .08);
        }

        [data-bs-theme="dark"] .adj-ac-item {
            border-bottom-color: rgba(255, 255, 255, .07);
        }

        [data-bs-theme="dark"] .adj-ac-item:hover {
            background: rgba(105, 108, 255, .15);
        }

        .adj-ac-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 7px;
            flex-shrink: 0;
            border: 1px solid rgba(0, 0, 0, .08);
        }

        [data-bs-theme="dark"] .adj-ac-item img {
            border-color: rgba(255, 255, 255, .12);
        }

        .adj-ac-name {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            color: #3d4150;
        }

        [data-bs-theme="dark"] .adj-ac-name {
            color: #cfd3ec;
        }

        .adj-ac-sku {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.2;
        }

        [data-bs-theme="dark"] .adj-ac-sku {
            color: #7983bb;
        }

        .adj-ac-stock {
            font-size: 11px;
            white-space: nowrap;
            color: #94a3b8;
        }

        [data-bs-theme="dark"] .adj-ac-stock {
            color: #7983bb;
        }

        .adj-ac-no-results {
            padding: 14px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowCount = 0;

            /* ══════════════════════════════════════════
               AUTOCOMPLETE
            ══════════════════════════════════════════ */
            const searchInput = $('#adjProductSearch');
            const resultsBox = $('#adjAutocompleteResults');
            let searchTimer = null;

            searchInput.on('input', function() {
                clearTimeout(searchTimer);
                const q = $(this).val().trim();
                if (q.length < 1) {
                    resultsBox.addClass('d-none').empty();
                    return;
                }

                searchTimer = setTimeout(function() {
                    $.get('{{ route('products.search') }}', {
                        query: q
                    }, function(data) {
                        resultsBox.empty();
                        if (!data.length) {
                            resultsBox.append(
                                '<div class="adj-ac-no-results">{{ __('messages.no_products_found') }}</div>'
                            );
                            resultsBox.removeClass('d-none');
                            return;
                        }

                        data.forEach(function(p) {
                            const stockLabel = p.out_of_stock ?
                                '<span class="badge bg-danger" style="font-size:10px;">{{ __('messages.out_of_stock') }}</span>' :
                                `<span class="adj-ac-stock">${parseFloat(p.stock).toFixed(2)} ${p.unit || '{{ __('messages.units') }}'} {{ __('messages.avail') }}.</span>`;

                            const $item = $(`
                        <div class="adj-ac-item" data-id="${p.id}" data-name="${$('<div>').text(p.name).html()}"
                             data-sku="${p.sku}" data-stock="${p.stock}" data-unit="${p.unit || 'Units'}"
                             data-image="${p.image_url}">
                            <img src="${p.image_url}" alt="">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="adj-ac-name text-truncate">${$('<div>').text(p.name).html()}</div>
                                <div class="adj-ac-sku">SKU: ${p.sku}</div>
                            </div>
                            <div class="text-end flex-shrink-0">${stockLabel}</div>
                        </div>
                    `);

                            $item.on('click', function() {
                                const $el = $(this);
                                const pid = $el.data('id');

                                // Duplicate check
                                let dup = false;
                                $('#adjustmentItemsContainer tr').each(
                                    function() {
                                        if ($(this).data(
                                                'product-id') == pid) {
                                            dup = true;
                                            return false;
                                        }
                                    });
                                if (dup) {
                                    showAdminToast(
                                        '{{ __('messages.product_already_added') }}',
                                        'warning');
                                    searchInput.val('').focus();
                                    resultsBox.addClass('d-none').empty();
                                    return;
                                }

                                addProductRow({
                                    id: pid,
                                    name: $el.data('name'),
                                    sku: $el.data('sku'),
                                    stock: parseFloat($el.data(
                                        'stock')) || 0,
                                    unit: $el.data('unit') ||
                                        'Units',
                                    image: $el.data('image'),
                                    qty: 1,
                                    type: 'Plus'
                                });

                                searchInput.val('').focus();
                                resultsBox.addClass('d-none').empty();
                            });

                            resultsBox.append($item);
                        });

                        resultsBox.removeClass('d-none');
                    });
                }, 250);
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#adjProductSearch, #adjAutocompleteResults').length) {
                    resultsBox.addClass('d-none').empty();
                }
            });

            // Keyboard: Escape closes
            searchInput.on('keydown', function(e) {
                if (e.key === 'Escape') {
                    resultsBox.addClass('d-none').empty();
                }
            });

            /* ══════════════════════════════════════════
               ADD ROW
            ══════════════════════════════════════════ */
            function addProductRow(p) {
                $('#emptyTableMsg').addClass('d-none');

                const rowHtml = `
            <tr class="item-row" data-product-id="${p.id}">
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <img src="${p.image}" class="rounded" style="width:36px;height:36px;object-fit:cover;border:1px solid rgba(0,0,0,.08);">
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
                        <label class="btn btn-outline-success btn-sm px-2 py-1 d-flex align-items-center justify-content-center gap-1"
                               for="type_plus_${rowCount}" style="font-size:11px;font-weight:600;cursor:pointer;">
                            <i class="bx bx-plus-circle"></i> Plus (+)
                        </label>
                        <input type="radio" class="btn-check" name="items[${rowCount}][type]"
                               id="type_minus_${rowCount}" value="Minus"
                               ${p.type === 'Minus' ? 'checked' : ''} autocomplete="off">
                        <label class="btn btn-outline-danger btn-sm px-2 py-1 d-flex align-items-center justify-content-center gap-1"
                               for="type_minus_${rowCount}" style="font-size:11px;font-weight:600;cursor:pointer;">
                            <i class="bx bx-minus-circle"></i> Minus (-)
                        </label>
                    </div>
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm">
                        <input type="number" step="1" min="1"
                               name="items[${rowCount}][quantity]"
                               class="form-control text-center py-1 qty-input"
                               value="${Math.round(p.qty)}" required>
                        <span class="input-group-text px-1 small" style="font-size:10px;">${p.unit}</span>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger rounded-circle remove-row-btn"
                            style="width:28px;height:28px;padding:0;" title="Remove">
                        <i class="bx bx-trash" style="font-size:13px;"></i>
                    </button>
                </td>
            </tr>`;

                $('#adjustmentItemsContainer').append(rowHtml);
                rowCount++;
            }

            /* ══════════════════════════════════════════
               REMOVE ROW
            ══════════════════════════════════════════ */
            $(document).on('click', '.remove-row-btn', function() {
                $(this).closest('tr').remove();
                if ($('#adjustmentItemsContainer tr').length === 0) {
                    $('#emptyTableMsg').removeClass('d-none');
                }
            });

            /* ══════════════════════════════════════════
               PRE-POPULATE (validation fail / from Sales)
            ══════════════════════════════════════════ */
            @if (old('items'))
                @foreach (old('items') as $oldItem)
                    @php $oldProd = \App\Models\Product::with('stock')->find($oldItem['product_id'] ?? null); @endphp
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
            @elseif (!empty($preselectedProducts) && $preselectedProducts->count() > 0)
                {{-- Pre-loaded from Sales Invoice --}}
                @foreach ($preselectedProducts as $preProd)
                    addProductRow({
                        id: "{{ $preProd->id }}",
                        name: "{{ addslashes($preProd->name) }}",
                        sku: "{{ $preProd->code }}",
                        stock: parseFloat("{{ $preProd->stock->quantity ?? 0 }}"),
                        unit: "{{ $preProd->unit_code ?? 'Units' }}",
                        image: "{{ $preProd->image ? asset('uploads/products/' . $preProd->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}",
                        qty: 1,
                        type: 'Minus'
                    });
                @endforeach
            @endif
        });
    </script>
@endpush
