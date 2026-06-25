@extends('layouts.admin')
@section('title', 'Sales Report')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sales Report</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Sales</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i>Filters</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Customer</label>
                        <select name="customer_id" class="form-select form-select-sm">
                            <option value="">All Customers</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}"
                                    {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Payment</label>
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="Cash" {{ request('payment_method') === 'Cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="Card" {{ request('payment_method') === 'Card' ? 'selected' : '' }}>Card
                            </option>
                            <option value="UPI" {{ request('payment_method') === 'UPI' ? 'selected' : '' }}>UPI</option>
                            <option value="Bank" {{ request('payment_method') === 'Bank' ? 'selected' : '' }}>Bank
                            </option>
                            <option value="Credit" {{ request('payment_method') === 'Credit' ? 'selected' : '' }}>Credit
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill"><i
                                class="bx bx-search"></i></button>
                        <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary btn-sm"><i
                                class="bx bx-reset"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Totals --}}
    <div class="row g-3 mb-4">
        @php $cards = [['label' => 'Total Invoices', 'value' => $totals['count'], 'icon' => 'bx-receipt', 'color' => 'primary'], ['label' => 'Grand Total', 'value' => format_currency($totals['grand_total']), 'icon' => 'bx-rupee', 'color' => 'success'], ['label' => 'Paid Amount', 'value' => format_currency($totals['paid_amount']), 'icon' => 'bx-check-circle', 'color' => 'info'], ['label' => 'Due Amount', 'value' => format_currency($totals['due_amount']), 'icon' => 'bx-time', 'color' => 'warning'], ['label' => 'Tax Collected', 'value' => format_currency($totals['tax_amount']), 'icon' => 'bx-percent', 'color' => 'secondary'], ['label' => 'Discounts', 'value' => format_currency($totals['discount_amount']), 'icon' => 'bx-tag', 'color' => 'danger']]; @endphp
        @foreach ($cards as $card)
            <div class="col-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted" style="font-size:.75rem;">{{ $card['label'] }}</p>
                            <h5 class="mb-0 fw-bold text-{{ $card['color'] }}">{{ $card['value'] }}</h5>
                        </div>
                        <span class="avatar-initial rounded-circle bg-label-{{ $card['color'] }}"
                            style="font-size:1.1rem;width:38px;height:38px;">
                            <i class="bx {{ $card['icon'] }}"></i>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2 text-primary"></i>Sales Invoices
                ({{ $totals['count'] }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="salesReportTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Payment</th>
                            <th class="text-end">Sub Total</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Grand Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Due</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $i => $sale)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td><a href="{{ route('sales.show', $sale->id) }}"
                                        class="fw-semibold text-primary">{{ $sale->invoice_no }}</a></td>
                                <td class="small text-muted">{{ $sale->invoice_date }}</td>
                                <td class="small">{{ $sale->customer->name ?? '—' }}</td>
                                <td class="small">{{ $sale->payment_method ?? '—' }}</td>
                                <td class="text-end small">{{ format_currency($sale->sub_total) }}</td>
                                <td class="text-end small text-warning">{{ format_currency($sale->tax_amount) }}</td>
                                <td class="text-end small text-danger">{{ format_currency($sale->discount_amount) }}</td>
                                <td class="text-end fw-bold">{{ format_currency($sale->grand_total) }}</td>
                                <td class="text-end small text-success">{{ format_currency($sale->paid_amount) }}</td>
                                <td
                                    class="text-end small {{ $sale->due_amount > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    {{ format_currency($sale->due_amount) }}</td>
                                <td class="text-center">
                                    @if ($sale->status === 'Completed')
                                        <span class="badge bg-success rounded-pill">Completed</span>
                                    @elseif($sale->status === 'Draft')
                                        <span class="badge bg-warning text-dark rounded-pill">Draft</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">{{ $sale->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">No sales found for selected
                                    filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            @if ($sales->count())
                                <th class="text-end small">Total ({{ $totals['count'] }})</th>
                                <th class="text-end">{{ format_currency($totals['sub_total']) }}</th>
                                <th class="text-end text-warning">{{ format_currency($totals['tax_amount']) }}</th>
                                <th class="text-end text-danger">{{ format_currency($totals['discount_amount']) }}</th>
                                <th class="text-end text-primary">{{ format_currency($totals['grand_total']) }}</th>
                                <th class="text-end text-success">{{ format_currency($totals['paid_amount']) }}</th>
                                <th class="text-end text-danger">{{ format_currency($totals['due_amount']) }}</th>
                            @else
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            @endif
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#salesReportTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [2, 'desc']
                ],
                columnDefs: [{
                    targets: [11],
                    orderable: false
                }]
            });
        });
    </script>
@endpush
