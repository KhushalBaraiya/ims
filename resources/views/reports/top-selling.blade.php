@extends('layouts.admin')
@section('title', 'Top Selling Products')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Top Selling Products</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Top Selling</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Sort By</label>
                    <select name="sort_by" class="form-select form-select-sm">
                        <option value="quantity" {{ $sortBy === 'quantity' ? 'selected' : '' }}>Most Sold (Qty)</option>
                        <option value="revenue" {{ $sortBy === 'revenue' ? 'selected' : '' }}>Highest Revenue</option>
                        <option value="profit" {{ $sortBy === 'profit' ? 'selected' : '' }}>Highest Profit</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Show Top</label>
                    <select name="limit" class="form-select form-select-sm">
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
                        <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option>
                        <option value="100" {{ $limit == 100 ? 'selected' : '' }}>Top 100</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1 align-items-end">
                    <button type="submit" class="btn btn-warning btn-sm flex-fill text-dark"><i
                            class="bx bx-search me-1"></i>Apply</button>
                    <a href="{{ route('reports.top-selling') }}" class="btn btn-outline-secondary btn-sm"><i
                            class="bx bx-reset"></i></a>
                </div>
                <div class="col-md-2 d-flex gap-1 align-items-end">
                    <a href="?date_from={{ now()->startOfMonth()->format('Y-m-d') }}&date_to={{ now()->format('Y-m-d') }}&sort_by={{ $sortBy }}&limit={{ $limit }}"
                        class="btn btn-outline-secondary btn-sm flex-fill">This Month</a>
                    <a href="?date_from={{ now()->startOfYear()->format('Y-m-d') }}&date_to={{ now()->format('Y-m-d') }}&sort_by={{ $sortBy }}&limit={{ $limit }}"
                        class="btn btn-outline-secondary btn-sm flex-fill">This Year</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Grand Total Cards --}}
    <div class="row g-3 mb-4">
        @php $gtCards = [['label' => 'Total Products', 'value' => $topProducts->count(), 'icon' => 'bx-package', 'color' => 'warning'], ['label' => 'Total Qty Sold', 'value' => number_format($grandTotal['qty']), 'icon' => 'bx-cube', 'color' => 'info'], ['label' => 'Total Revenue', 'value' => format_currency($grandTotal['revenue']), 'icon' => 'bx-rupee', 'color' => 'primary'], ['label' => 'Total Cost', 'value' => format_currency($grandTotal['cost']), 'icon' => 'bx-cart', 'color' => 'danger'], ['label' => 'Total Profit', 'value' => format_currency($grandTotal['profit']), 'icon' => 'bx-trending-up', 'color' => 'success']]; @endphp
        @foreach ($gtCards as $c)
            <div class="col-6 col-xl">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">{{ $c['label'] }}</p>
                            <h5 class="mb-0 fw-bold text-{{ $c['color'] }}">{{ $c['value'] }}</h5>
                        </div>
                        <span class="avatar-initial rounded-circle bg-label-{{ $c['color'] }}"
                            style="font-size:1.1rem;width:38px;height:38px;">
                            <i class="bx {{ $c['icon'] }}"></i>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-trophy me-2 text-warning"></i>
                Top {{ $limit }} Products
                @if ($dateFrom || $dateTo)
                    <span class="badge bg-label-secondary ms-2 small fw-normal">
                        {{ $dateFrom ?? 'All time' }} → {{ $dateTo ?? 'Today' }}
                    </span>
                @endif
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($topProducts->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="topSellingTable">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th class="text-end">Qty Sold</th>
                                <th class="text-end">Orders</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Profit</th>
                                <th class="text-end">Margin</th>
                                <th class="text-end">Current Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxRev = $topProducts->max('total_revenue') ?: 1; @endphp
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
                                    $medalIcon = $rank <= 3 ? 'bx-medal' : 'bx-hash';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bx {{ $medalIcon }} {{ $medalColor }}"
                                                style="font-size:1.1rem;"></i>
                                            <span class="fw-bold {{ $medalColor }}">{{ $rank }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($item->product?->image)
                                                <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                    style="width:36px;height:36px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;"
                                                    onerror="this.style.display='none'">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded text-muted"
                                                    style="width:36px;height:36px;flex-shrink:0;">
                                                    <i class="bx bx-package" style="font-size:.9rem;"></i>
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
                                    <td class="text-end">
                                        <span
                                            class="badge {{ $item->profit_pct >= 30 ? 'bg-success' : ($item->profit_pct >= 10 ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill">
                                            {{ $item->profit_pct }}%
                                        </span>
                                    </td>
                                    <td class="text-end small">
                                        @php $stock = $item->product?->stock?->quantity ?? 0; @endphp
                                        <span
                                            class="{{ $stock <= 0 ? 'text-danger fw-bold' : ($stock <= ($item->product?->minimum_stock_alert ?? 0) ? 'text-warning fw-semibold' : 'text-success') }}">
                                            {{ number_format($stock, 0) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-end">Total</th>
                                <th class="text-end">{{ number_format($grandTotal['qty'], 0) }}</th>
                                <th></th>
                                <th class="text-end text-primary">{{ format_currency($grandTotal['revenue']) }}</th>
                                <th class="text-end text-danger">{{ format_currency($grandTotal['cost']) }}</th>
                                <th class="text-end text-success">{{ format_currency($grandTotal['profit']) }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-trophy" style="font-size:3.5rem;opacity:.12;display:block;"></i>
                    <p class="mt-3 mb-0 fw-semibold">No sales data found.</p>
                    <p class="small">Add completed sales to see top selling products.</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#topSellingTable').DataTable({
                responsive: true,
                pageLength: 25,
                columnDefs: [{
                    targets: [0, 9, 10],
                    orderable: false
                }]
            });
        });
    </script>
@endpush
