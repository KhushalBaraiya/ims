@extends('layouts.admin')
@section('title', 'Stock Alert Report')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Stock Alert Report</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Stock Alert</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.stock-alert.export', request()->query()) }}" class="btn btn-outline-success btn-sm">
                <i class="bx bx-download me-1"></i> Export CSV
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> Print
            </button>
            @can('stocks.create')
                <a href="{{ route('stocks.adjust') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-store-alt me-1"></i> Manage Stock
                </a>
            @endcan
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> All Reports
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Total Alerts</p>
                        <h4 class="fw-bold text-danger mb-0">{{ $summary['total'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-error"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Out of Stock</p>
                        <h4 class="fw-bold text-danger mb-0">{{ $summary['out_of_stock'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Low Stock</p>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['low_stock'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Restock Value</p>
                        <h4 class="fw-bold text-primary mb-0">{{ format_currency($summary['restock_value']) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-danger"></i>Filter Alerts</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Alert Type</label>
                        <select name="filter" class="form-select form-select-sm">
                            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Alerts (Low + Out)</option>
                            <option value="out" {{ $filter === 'out' ? 'selected' : '' }}>Out of Stock Only</option>
                            <option value="low" {{ $filter === 'low' ? 'selected' : '' }}>Low Stock Only</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Brand</label>
                        <select name="brand_id" class="form-select form-select-sm">
                            <option value="">All Brands</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Category</label>
                        <select name="main_category_id" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ request('main_category_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-1">
                        <button type="submit" class="btn btn-danger btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i>Apply
                        </button>
                        <a href="{{ route('reports.stock-alert') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-error me-2 text-danger"></i>Alert Products
                <span class="badge bg-danger ms-1">{{ $summary['total'] }}</span>
            </h6>
            <div class="d-flex gap-2">
                @if ($summary['out_of_stock'] > 0)
                    <span class="badge bg-label-danger"><i class="bx bx-x-circle me-1"></i>{{ $summary['out_of_stock'] }}
                        Out of Stock</span>
                @endif
                @if ($summary['low_stock'] > 0)
                    <span class="badge bg-label-warning"><i class="bx bx-error-circle me-1"></i>{{ $summary['low_stock'] }}
                        Low Stock</span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if ($products->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="stockAlertTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Current Qty</th>
                                <th class="text-end">Alert Level</th>
                                <th class="text-end">Qty Needed</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Restock Value</th>
                                <th class="text-center no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $i => $p)
                                <tr>
                                    <td class="text-muted small ps-3">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($p->image)
                                                <img src="{{ asset('uploads/products/' . $p->image) }}"
                                                    class="tbl-img rounded" onerror="imgError(this)">
                                            @else
                                                <div
                                                    class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
                                                    <i class="bx bx-package text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold small">{{ $p->name }}</div>
                                                <code style="font-size:.65rem;">{{ $p->code }}</code>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $p->brand?->name ?? '—' }}</td>
                                    <td class="small text-muted">{{ $p->mainCategory?->name ?? '—' }}</td>
                                    <td class="text-center">
                                        @if ($p->stock_status === 'out')
                                            <span class="badge bg-danger rounded-pill">
                                                <i class="bx bx-x-circle me-1"></i>Out of Stock
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill">
                                                <i class="bx bx-error-circle me-1"></i>Low Stock
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="text-end fw-bold {{ $p->stock_status === 'out' ? 'text-danger' : 'text-warning' }}">
                                        {{ number_format($p->current_qty, 0) }}
                                    </td>
                                    <td class="text-end small text-muted">{{ number_format($p->alert_qty, 0) }}</td>
                                    <td class="text-end small fw-semibold text-primary">
                                        {{ $p->qty_needed > 0 ? '+' . number_format($p->qty_needed, 0) : '—' }}
                                    </td>
                                    <td class="text-end small">{{ format_currency($p->purchase_price) }}</td>
                                    <td
                                        class="text-end fw-bold {{ $p->restock_value > 0 ? 'text-primary' : 'text-muted' }}">
                                        {{ $p->restock_value > 0 ? format_currency($p->restock_value) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        @can('stocks.create')
                                            <a href="{{ route('stocks.adjust') }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2"
                                                style="font-size:.7rem;">
                                                <i class="bx bx-plus-medical me-1"></i>Restock
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="9" class="text-end ps-3">Total Restock Value</td>
                                <td class="text-end text-primary">{{ format_currency($summary['restock_value']) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bx bx-check-circle text-success d-block mb-2" style="font-size:4rem;opacity:.4;"></i>
                    <p class="mt-2 fw-semibold text-success mb-1">All Stock Healthy!</p>
                    <p class="text-muted small mb-0">No products are below their alert threshold.</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#stockAlertTable').length) {
                $('#stockAlertTable').DataTable({
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
                        searchPlaceholder: "Search products...",
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
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        @media print {

            .layout-menu,
            .layout-navbar,
            .breadcrumb,
            .card-header .d-flex .btn,
            form {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
