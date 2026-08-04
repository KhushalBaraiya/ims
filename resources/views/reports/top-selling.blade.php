@extends('layouts.admin')
@section('title', __('messages.top_selling_title'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.top_selling') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.top_selling') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> Print
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> All Reports
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-filter-alt me-2 text-warning"></i>{{ __('messages.filter_sort_lbl') }}</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.sort_by_label') }}</label>
                        <select name="sort_by" class="form-select form-select-sm">
                            <option value="quantity" {{ $sortBy === 'quantity' ? 'selected' : '' }}>
                                {{ __('messages.most_sold_qty') }}
                            </option>
                            <option value="revenue" {{ $sortBy === 'revenue' ? 'selected' : '' }}>
                                {{ __('messages.highest_revenue') }}</option>
                            <option value="profit" {{ $sortBy === 'profit' ? 'selected' : '' }}>
                                {{ __('messages.highest_profit') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.show_top') }}</label>
                        <select name="limit" class="form-select form-select-sm">
                            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>{{ __('messages.show_top') }} 10
                            </option>
                            <option value="20" {{ $limit == 20 ? 'selected' : '' }}>{{ __('messages.show_top') }} 20
                            </option>
                            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>{{ __('messages.show_top') }} 50
                            </option>
                            <option value="100" {{ $limit == 100 ? 'selected' : '' }}>{{ __('messages.show_top') }} 100
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-warning btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('reports.top-selling') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <a href="?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}&sort_by={{ $sortBy }}&limit={{ $limit }}"
                            class="btn btn-outline-secondary btn-sm flex-fill">{{ __('messages.rpt_this_month') }}</a>
                        <a href="?date_from={{ now()->startOfYear()->toDateString() }}&date_to={{ now()->toDateString() }}&sort_by={{ $sortBy }}&limit={{ $limit }}"
                            class="btn btn-outline-secondary btn-sm flex-fill">{{ __('messages.rpt_this_year') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Grand Total KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.products_ranked') }}</p>
                        <h5 class="mb-0 fw-bold text-warning">{{ $topProducts->count() }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-trophy"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.total_qty_sold') }}</p>
                        <h5 class="mb-0 fw-bold text-info">{{ number_format($grandTotal['qty']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;">
                        <i class="bx bx-cube"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.total_revenue') }}</p>
                        <h5 class="mb-0 fw-bold text-primary">{{ format_currency($grandTotal['revenue']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.total_cost') }}</p>
                        <h5 class="mb-0 fw-bold text-danger">{{ format_currency($grandTotal['cost']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-cart"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.rpt_net_profit') }}</p>
                        <h5 class="mb-0 fw-bold {{ $grandTotal['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ format_currency($grandTotal['profit']) }}</h5>
                    </div>
                    <span
                        class="avatar-initial rounded-circle {{ $grandTotal['profit'] >= 0 ? 'bg-label-success' : 'bg-label-danger' }} p-3"
                        style="font-size:1rem;">
                        <i class="bx bx-trending-up"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-trophy me-2 text-warning"></i>
                {{ __('messages.show_top') }} {{ $limit }} {{ __('messages.products') }}
                @if ($dateFrom || $dateTo)
                    <span class="badge bg-label-secondary ms-2 fw-normal">
                        {{ $dateFrom ?? 'All time' }} → {{ $dateTo ?? 'Today' }}
                    </span>
                @endif
            </h6>
            <span class="badge bg-label-warning">{{ __('messages.sorted_by_lbl') }}: {{ ucfirst($sortBy) }}</span>
        </div>
        <div class="card-body p-0">
            @if ($topProducts->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="topSellingTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-center" style="width:60px;">{{ __('messages.rank_label') }}</th>
                                <th>{{ __('messages.product') }}</th>
                                <th>{{ __('messages.brand') }}</th>
                                <th>{{ __('messages.category') }}</th>
                                <th class="text-end">{{ __('messages.qty_sold') }}</th>
                                <th class="text-end">{{ __('messages.rpt_orders') }}</th>
                                <th class="text-end">{{ __('messages.revenue_label') }}</th>
                                <th class="text-end">{{ __('messages.prod_cost') }}</th>
                                <th class="text-end">{{ __('messages.rpt_net_profit') }}</th>
                                <th class="text-center">{{ __('messages.margin_label') }}</th>
                                <th class="text-end">{{ __('messages.th_stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $i => $item)
                                @php
                                    $rank = $i + 1;
                                    $medalColor =
                                        $rank === 1
                                            ? 'text-warning'
                                            : ($rank === 2
                                                ? 'text-secondary'
                                                : ($rank === 3
                                                    ? 'text-danger'
                                                    : 'text-muted'));
                                    $stock = $item->product?->stock?->quantity ?? 0;
                                    $minAlert = $item->product?->minimum_stock_alert ?? 0;
                                    $stockClass =
                                        $stock <= 0
                                            ? 'text-danger fw-bold'
                                            : ($stock <= $minAlert
                                                ? 'text-warning fw-semibold'
                                                : 'text-success');
                                @endphp
                                <tr>
                                    <td class="text-center ps-3">
                                        @if ($rank <= 3)
                                            <i class="bx bx-medal {{ $medalColor }}" style="font-size:1.3rem;"></i>
                                            <div class="fw-bold {{ $medalColor }} small">{{ $rank }}</div>
                                        @else
                                            <span class="text-muted fw-semibold">{{ $rank }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($item->product?->image)
                                                <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                    class="tbl-img rounded" onerror="imgError(this)">
                                            @else
                                                <div
                                                    class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
                                                    <i class="bx bx-package text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold small">
                                                    {{ $item->product?->name ?? 'Deleted Product' }}</div>
                                                <code style="font-size:.65rem;">{{ $item->product?->code }}</code>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $item->product?->brand?->name ?? '—' }}</td>
                                    <td class="small text-muted">{{ $item->product?->mainCategory?->name ?? '—' }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->total_qty, 0) }}</td>
                                    <td class="text-end small text-muted">{{ $item->order_count }}</td>
                                    <td class="text-end fw-bold text-primary">{{ format_currency($item->total_revenue) }}
                                    </td>
                                    <td class="text-end small text-danger">{{ format_currency($item->total_cost) }}</td>
                                    <td
                                        class="text-end fw-bold {{ $item->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ format_currency($item->total_profit) }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $item->profit_pct >= 30 ? 'bg-success' : ($item->profit_pct >= 10 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ $item->profit_pct }}%
                                        </span>
                                    </td>
                                    <td class="text-end small {{ $stockClass }}">
                                        {{ number_format($stock, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end ps-3">{{ __('messages.totals_label') }}</td>
                                <td class="text-end">{{ number_format($grandTotal['qty'], 0) }}</td>
                                <td></td>
                                <td class="text-end text-primary">{{ format_currency($grandTotal['revenue']) }}</td>
                                <td class="text-end text-danger">{{ format_currency($grandTotal['cost']) }}</td>
                                <td class="text-end text-success">{{ format_currency($grandTotal['profit']) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-trophy d-block mb-2" style="font-size:3.5rem;opacity:.12;"></i>
                    <p class="mt-2 fw-semibold mb-1">{{ __('messages.no_sales_data') }}</p>
                    <p class="small mb-0">{{ __('messages.add_sales_for_top') }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#topSellingTable').length) {
                $('#topSellingTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    columnDefs: [{
                        targets: [0, 9, 10],
                        orderable: false
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "{{ __('messages.search_products_ph') }}",
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
