@extends('layouts.admin')
@section('title', 'Stock Alert Report')

@section('content')

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
        <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-store-alt me-1"></i> Manage Stock
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #ff3e1d!important;">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Total Alerts</p>
                        <h4 class="fw-bold text-danger mb-0">{{ $summary['total'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger"
                        style="font-size:1.1rem;width:38px;height:38px;"><i class="bx bx-error"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #ff3e1d!important;">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Out of Stock</p>
                        <h4 class="fw-bold text-danger mb-0">{{ $summary['out_of_stock'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger"
                        style="font-size:1.1rem;width:38px;height:38px;"><i class="bx bx-x-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #ffab00!important;">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Low Stock</p>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['low_stock'] }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning"
                        style="font-size:1.1rem;width:38px;height:38px;"><i class="bx bx-error-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #696cff!important;">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0">Restock Value</p>
                        <h4 class="fw-bold text-primary mb-0">{{ format_currency($summary['restock_value']) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary"
                        style="font-size:1.1rem;width:38px;height:38px;"><i class="bx bx-rupee"></i></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <select name="filter" class="form-select form-select-sm">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Alerts (Low + Out)</option>
                        <option value="out" {{ $filter === 'out' ? 'selected' : '' }}>Out of Stock Only</option>
                        <option value="low" {{ $filter === 'low' ? 'selected' : '' }}>Low Stock Only</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="brand_id" class="form-select form-select-sm">
                        <option value="">All Brands</option>
                        @foreach ($brands as $b)
                            <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
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
                    <button type="submit" class="btn btn-danger btn-sm flex-fill"><i
                            class="bx bx-search me-1"></i>Apply</button>
                    <a href="{{ route('reports.stock-alert') }}" class="btn btn-outline-secondary btn-sm"><i
                            class="bx bx-reset"></i></a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-error me-2 text-danger"></i>
                Alert Products
                <span class="badge bg-danger ms-1">{{ $summary['total'] }}</span>
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($products->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="stockAlertTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Current Qty</th>
                                <th class="text-end">Alert Level</th>
                                <th class="text-end">Qty Needed</th>
                                <th class="text-end">Purchase Price</th>
                                <th class="text-end">Restock Value</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $i => $p)
                                <tr class="{{ $p->stock_status === 'out' ? 'table-danger' : 'table-warning' }}"
                                    style="--bs-table-bg:{{ $p->stock_status === 'out' ? 'rgba(255,62,29,.05)' : 'rgba(255,171,0,.05)' }};">
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($p->image)
                                                <img src="{{ asset('uploads/products/' . $p->image) }}"
                                                    style="width:36px;height:36px;object-fit:cover;border-radius:6px;"
                                                    onerror="this.style.display='none'">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded text-muted"
                                                    style="width:36px;height:36px;flex-shrink:0;border-radius:6px;">
                                                    <i class="bx bx-package"></i>
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
                                            <span class="badge bg-danger rounded-pill"><i
                                                    class="bx bx-x-circle me-1"></i>Out of Stock</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill"><i
                                                    class="bx bx-error-circle me-1"></i>Low Stock</span>
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
                                    <td class="text-end fw-bold text-primary">
                                        {{ $p->restock_value > 0 ? format_currency($p->restock_value) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        @can('stocks.create')
                                            <a href="{{ route('stocks.adjust') }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2"
                                                style="font-size:.7rem;" title="Adjust Stock">
                                                <i class="bx bx-plus-medical me-1"></i>Restock
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-center">Total Restock</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-end text-primary">{{ format_currency($summary['restock_value']) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bx bx-check-circle text-success" style="font-size:4rem;display:block;opacity:.3;"></i>
                    <p class="mt-3 fw-semibold text-success mb-1">All Stock Healthy!</p>
                    <p class="text-muted small">No products are below their alert threshold.</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#stockAlertTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [5, 'asc']
                ],
                columnDefs: [{
                    targets: [4, 10],
                    orderable: false
                }]
            });
        });
    </script>
@endpush
