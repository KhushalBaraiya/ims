@extends('layouts.admin')
@section('title', 'Stock Management')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Stock Management</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stock</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Inventory Overview --}}
    <div class="card shadow-sm mb-4">
        <div
            class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-store-alt me-2 text-primary"></i>Inventory Overview</h6>
            <form method="GET" action="{{ route('stocks.index') }}" class="d-flex flex-wrap gap-2" id="filterForm">
                <div class="input-group input-group-sm" style="width:220px;">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search by name or SKU…">
                </div>
                <select name="main_category_id" onchange="this.form.submit()" class="form-select form-select-sm"
                    style="width:180px;">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('main_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
                @if (request('search') || request('main_category_id'))
                    <a href="{{ route('stocks.index') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bx bx-x me-1"></i>Clear</a>
                @endif
                <button type="submit" class="btn btn-sm btn-primary"><i class="bx bx-search me-1"></i>Search</button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="inventoryTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th class="text-end">Stock</th>
                            <th class="text-end">Alert Level</th>
                            <th class="text-end">Selling Price</th>
                            <th class="text-end">Inventory Value</th>
                            <th class="text-center no-sort">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            @php
                                $qty = $product->stock->quantity ?? 0;
                                $alert = $product->minimum_stock_alert ?? 0;
                                $isLow = $qty <= $alert;
                                $inventoryValue = $qty * $product->selling_price;
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if ($product->is_featured)
                                        <i class="bx bxs-star text-warning ms-1" title="Featured"
                                            style="font-size:11px;"></i>
                                    @endif
                                </td>
                                <td><code>{{ $product->code }}</code></td>
                                <td class="text-muted">{{ $product->mainCategory->name ?? '-' }}</td>
                                <td class="text-muted">{{ $product->unit_code ?? '-' }}</td>
                                <td class="text-end fw-bold {{ $isLow ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($qty, 2) }}</td>
                                <td class="text-end text-muted">{{ number_format($alert, 2) }}</td>
                                <td class="text-end text-primary fw-semibold">
                                    {{ format_currency($product->selling_price) }}</td>
                                <td class="text-end fw-bold">{{ format_currency($inventoryValue) }}</td>
                                <td class="text-center">
                                    @if ($qty <= 0)
                                        <span class="badge rounded-pill bg-danger">Out of Stock</span>
                                    @elseif($isLow)
                                        <span class="badge rounded-pill bg-warning text-dark">Low Stock</span>
                                    @else
                                        <span class="badge rounded-pill bg-success">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bx bx-package" style="font-size:2rem;opacity:.3;"></i>
                                    <p class="mt-2 mb-0">No products found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($products->isNotEmpty())
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="8" class="text-end">Total Inventory Value:</td>
                                <td class="text-end text-primary">
                                    {{ format_currency($products->sum(fn($p) => ($p->stock->quantity ?? 0) * $p->selling_price)) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Adjustment + History --}}
    <div class="row g-4">
        @can('stocks.create')
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bx bx-slider me-2 text-info"></i>Record Stock Adjustment</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('stocks.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="product_id"
                                    class="form-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">Select a product…</option>
                                    @foreach ($allProducts as $p)
                                        <option value="{{ $p->id }}" data-stock="{{ $p->stock->quantity ?? 0 }}"
                                            data-unit="{{ $p->unit_code ?? 'Units' }}"
                                            {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="currentStockDisplay"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Adjustment Type <span class="text-danger">*</span></label>
                                <select name="adjustment_type"
                                    class="form-select @error('adjustment_type') is-invalid @enderror" required>
                                    <option value="">Select type…</option>
                                    <option value="Restock" {{ old('adjustment_type') === 'Restock' ? 'selected' : '' }}>
                                        Restock (Add Stock)</option>
                                    <option value="Damage" {{ old('adjustment_type') === 'Damage' ? 'selected' : '' }}>
                                        Damage (Remove Stock)</option>
                                    <option value="Return" {{ old('adjustment_type') === 'Return' ? 'selected' : '' }}>
                                        Return (Add Stock)</option>
                                    <option value="Write-Off" {{ old('adjustment_type') === 'Write-Off' ? 'selected' : '' }}>
                                        Write-Off (Remove Stock)</option>
                                    <option value="Correction"
                                        {{ old('adjustment_type') === 'Correction' ? 'selected' : '' }}>Correction</option>
                                    <option value="Other" {{ old('adjustment_type') === 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                                @error('adjustment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Quantity Change <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="quantity_change" step="0.01"
                                    value="{{ old('quantity_change') }}"
                                    class="form-control @error('quantity_change') is-invalid @enderror"
                                    placeholder="e.g. +10 to add, -3 to remove" required>
                                <div class="form-text">Use positive to add, negative to remove.</div>
                                @error('quantity_change')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Notes (Optional)</label>
                                <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="Reason for adjustment...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-save me-1"></i> Save Adjustment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        <div class="{{ auth()->user()->can('stocks.create') ? 'col-lg-7' : 'col-12' }}">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold"><i class="bx bx-history me-2 text-success"></i>Stock Adjustment History
                    </h6>
                    <small class="text-muted">Latest 50 records</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="adjustmentsTable" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th class="text-end">Change</th>
                                    <th>Adjusted By</th>
                                    <th>Notes</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adjustments as $index => $adj)
                                    @php $isPositive = $adj->quantity_change >= 0; @endphp
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $adj->product->name ?? 'Deleted' }}</strong>
                                            <small
                                                class="d-block text-muted"><code>{{ $adj->product->code ?? '-' }}</code></small>
                                        </td>
                                        <td>
                                            @php $typeColors = ['Restock'=>'bg-success','Damage'=>'bg-danger','Return'=>'bg-info','Write-Off'=>'bg-warning','Correction'=>'bg-primary','Other'=>'bg-secondary']; @endphp
                                            <span
                                                class="badge bg-label-{{ ['Restock' => 'success', 'Damage' => 'danger', 'Return' => 'info', 'Write-Off' => 'warning', 'Correction' => 'primary', 'Other' => 'secondary'][$adj->adjustment_type] ?? 'secondary' }}">
                                                {{ $adj->adjustment_type }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold {{ $isPositive ? 'text-success' : 'text-danger' }}">
                                            {{ $isPositive ? '+' : '' }}{{ number_format($adj->quantity_change, 2) }}
                                        </td>
                                        <td class="fw-semibold">{{ $adj->user->name ?? 'System' }}</td>
                                        <td class="text-muted small" title="{{ $adj->notes }}">
                                            {{ $adj->notes ? \Str::limit($adj->notes, 30) : '—' }}</td>
                                        <td class="text-muted small">{{ $adj->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bx bx-history" style="font-size:2rem;opacity:.3;"></i>
                                            <p class="mt-2 mb-0">No adjustments recorded yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#inventoryTable').DataTable({
                responsive: true,
                order: [
                    [5, 'asc']
                ],
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }],
                pageLength: 25
            });

            $('#adjustmentsTable').DataTable({
                responsive: true,
                order: [
                    [6, 'desc']
                ],
                pageLength: 10
            });

            function updateStockDisplay() {
                const sel = $('#product_id option:selected');
                const stock = sel.data('stock'),
                    unit = sel.data('unit') || 'Units';
                const $d = $('#currentStockDisplay');
                if (sel.val()) {
                    const low = parseFloat(stock) <= 0;
                    $d.html(
                        `Current stock: <strong class="${low ? 'text-danger' : 'text-success'}">${parseFloat(stock).toFixed(2)} ${unit}</strong>`);
                } else {
                    $d.text('');
                }
            }

            $('#product_id').on('change', updateStockDisplay);
            if ($('#product_id').val()) updateStockDisplay();
        });
    </script>
@endpush