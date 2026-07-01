@extends('layouts.admin')
@section('title', __('messages.stock_overview'))

@section('content')

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.stock_overview') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.stock_overview') }}</li>
                </ol>
            </nav>
        </div>
        @can('stocks.create')
            <a href="{{ route('stocks.adjust') }}" class="btn btn-outline-primary">
                <i class="bx bx-slider me-1"></i> {{ __('messages.adjust_stock') }}
            </a>
        @endcan
    </div>

    {{-- ── Summary Cards ── --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-package fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.total_products') }}</div>
                        <div class="fw-bold fs-4">{{ $totalProducts }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-danger flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-x-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.out_of_stock') }}</div>
                        <div class="fw-bold fs-4 text-danger">{{ $outOfStock }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-error-circle fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.low_stock_badge') }}</div>
                        <div class="fw-bold fs-4 text-warning">{{ $lowStock }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-success flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-rupee fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.th_inv_value') }}</div>
                        <div class="fw-bold fs-5 text-success">{{ format_currency($totalInvValue) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" action="{{ route('stocks.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small mb-1">{{ __('messages.search') }}</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="{{ __('messages.product_name') }}, SKU…">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small mb-1">{{ __('messages.category') }}</label>
                    <select name="main_category_id" class="form-select form-select-sm">
                        <option value="">{{ __('messages.all_categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('main_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small mb-1">Stock Status</label>
                    <select name="stock_status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="ok" {{ request('stock_status') === 'ok' ? 'selected' : '' }}>✅ In Stock
                        </option>
                        <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>⚠️ Low Stock
                        </option>
                        <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>🔴 Out of Stock
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bx bx-search me-1"></i>{{ __('messages.search') }}
                    </button>
                    <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-x"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Inventory Table ── --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-list-ul me-2 text-primary"></i>{{ __('messages.inventory_overview') }}
            </h6>
            <span class="badge bg-label-primary">{{ $products->count() }} {{ __('messages.total_products') }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="inventoryTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.product_name') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.th_unit') }}</th>
                            <th class="text-end">{{ __('messages.th_stock') }}</th>
                            <th class="text-end">{{ __('messages.th_alert_level') }}</th>
                            <th class="text-end">{{ __('messages.th_buy_price') }}</th>
                            <th class="text-end">{{ __('messages.th_inv_value') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            @php
                                $qty = $product->stock->quantity ?? 0;
                                $alert = $product->minimum_stock_alert ?? 0;
                                $isLow = $qty > 0 && $qty <= $alert;
                                $isOut = $qty <= 0;
                                $invVal = $qty * $product->purchase_price;
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($product->image)
                                            <img src="{{ asset('uploads/products/' . $product->image) }}" class="rounded"
                                                style="width:34px;height:34px;object-fit:cover;" onerror="imgError(this)">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center bg-light"
                                                style="width:34px;height:34px;">
                                                <i class="bx bx-package text-muted small"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $product->name }}</strong>
                                    </div>
                                </td>
                                <td><code class="small">{{ $product->code }}</code></td>
                                <td class="text-muted small">{{ $product->mainCategory->name ?? '-' }}</td>
                                <td class="text-muted small">{{ $product->unit_code ?? '-' }}</td>
                                <td class="text-end">
                                    @if ($product->hasTransactions())
                                        <span
                                            class="fw-bold {{ $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success') }}">
                                            {{ number_format($qty, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-end text-muted small">{{ number_format($alert, 2) }}</td>
                                <td class="text-end fw-semibold">{{ format_currency($product->purchase_price) }}</td>
                                <td class="text-end">
                                    @if ($product->hasTransactions())
                                        <span class="fw-bold">{{ format_currency($invVal) }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($product->hasTransactions())
                                        @if ($isOut)
                                            <span
                                                class="badge rounded-pill bg-danger">{{ __('messages.out_of_stock') }}</span>
                                        @elseif($isLow)
                                            <span
                                                class="badge rounded-pill bg-warning text-dark">{{ __('messages.low_stock_badge') }}</span>
                                        @else
                                            <span
                                                class="badge rounded-pill bg-success">{{ __('messages.in_stock') }}</span>
                                        @endif
                                    @else
                                        <span class="badge rounded-pill bg-secondary bg-opacity-75">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('stocks.create')
                                            <a href="{{ route('stocks.adjust', ['product_id' => $product->id]) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Adjust Stock" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-slider" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-5">
                                    <i class="bx bx-package" style="font-size:2.5rem;opacity:.3;"></i>
                                    <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($products->isNotEmpty())
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="8" class="text-end">Total Inventory Value:</td>
                                <td class="text-end text-success">{{ format_currency($totalInvValue) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#inventoryTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [5, 'asc']
                ],
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }],
                dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "{{ __('messages.search') }}...",
                    lengthMenu: "{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}",
                    info: "{{ __('messages.showing') }} _START_ {{ __('messages.to') }} _END_ {{ __('messages.of') }} _TOTAL_ {{ __('messages.entries') }}",
                    infoEmpty: "{{ __('messages.no_entries') }}",
                    infoFiltered: "({{ __('messages.filtered_from') }} _MAX_ {{ __('messages.total_entries') }})",
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });
        });
    </script>
@endpush
