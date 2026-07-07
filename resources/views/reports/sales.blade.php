@extends('layouts.admin')
@section('title', 'Sales Report')

@section('content')

    {{-- Page Header --}}
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
        <div class="d-flex gap-2">
            <a href="{{ route('reports.sales.export', request()->query()) }}" class="btn btn-outline-success btn-sm">
                <i class="bx bx-download me-1"></i> Export CSV
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> Print
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> All Reports
            </a>
        </div>
        <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i>Filter Sales</h6>
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
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Payment Method</label>
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="">All Methods</option>
                        <option value="Cash" {{ request('payment_method') === 'Cash' ? 'selected' : '' }}>Cash
                        </option>
                        <option value="Card" {{ request('payment_method') === 'Card' ? 'selected' : '' }}>Card
                        </option>
                        <option value="UPI" {{ request('payment_method') === 'UPI' ? 'selected' : '' }}>UPI</option>
                        <option value="Bank" {{ request('payment_method') === 'Bank' ? 'selected' : '' }}>Bank
                            Transfer</option>
                        <option value="Credit" {{ request('payment_method') === 'Credit' ? 'selected' : '' }}>Credit
                        </option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bx bx-search"></i>
                    </button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-reset"></i>
                    </a>
                </div>
            </div>
            {{-- Quick date shortcuts --}}
            <div class="d-flex gap-2 mt-3 flex-wrap">
                <span class="text-muted small fw-semibold me-1 align-self-center">Quick:</span>
                <a href="?date_from={{ now()->toDateString() }}&date_to={{ now()->toDateString() }}"
                    class="btn btn-outline-secondary btn-sm py-0">Today</a>
                <a href="?date_from={{ now()->startOfWeek()->toDateString() }}&date_to={{ now()->toDateString() }}"
                    class="btn btn-outline-secondary btn-sm py-0">This Week</a>
                <a href="?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}"
                    class="btn btn-outline-secondary btn-sm py-0">This Month</a>
                <a href="?date_from={{ now()->subMonth()->startOfMonth()->toDateString() }}&date_to={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                    class="btn btn-outline-secondary btn-sm py-0">Last Month</a>
                <a href="?date_from={{ now()->startOfYear()->toDateString() }}&date_to={{ now()->toDateString() }}"
                    class="btn btn-outline-secondary btn-sm py-0">This Year</a>
            </div>
        </form>
    </div>
    </div>

    {{-- Summary KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Invoices</p>
                        <h5 class="mb-0 fw-bold text-primary">{{ $totals['count'] }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-receipt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Grand Total</p>
                        <h5 class="mb-0 fw-bold text-success">{{ format_currency($totals['grand_total']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Paid Amount</p>
                        <h5 class="mb-0 fw-bold text-info">{{ format_currency($totals['paid_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Due Amount</p>
                        <h5 class="mb-0 fw-bold {{ $totals['due_amount'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ format_currency($totals['due_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-time"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Tax Collected</p>
                        <h5 class="mb-0 fw-bold text-secondary">{{ format_currency($totals['tax_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-secondary p-3" style="font-size:1rem;">
                        <i class="bx bx-percent"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Discounts</p>
                        <h5 class="mb-0 fw-bold text-warning">{{ format_currency($totals['discount_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-tag"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-list-ul me-2 text-primary"></i>Sales Invoices
                <span class="badge bg-label-primary ms-1">{{ $totals['count'] }}</span>
            </h6>
            @if (request()->hasAny(['date_from', 'date_to', 'customer_id', 'status', 'payment_method']))
                <span class="badge bg-label-secondary small fw-normal">
                    <i class="bx bx-filter-alt me-1"></i>Filtered results
                </span>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="salesReportTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
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
                                <td class="text-muted small ps-3">{{ $i + 1 }}</td>
                                <td>
                                    <a href="{{ route('sales.show', $sale->id) }}" class="fw-semibold text-primary">
                                        <code>{{ $sale->invoice_no }}</code>
                                    </a>
                                </td>
                                <td class="text-muted small">{{ $sale->invoice_date }}</td>
                                <td class="fw-semibold small">{{ $sale->customer->name ?? '—' }}</td>
                                <td>
                                    @if ($sale->payment_method)
                                        <span class="badge bg-label-secondary">{{ $sale->payment_method }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-end small">{{ format_currency($sale->sub_total) }}</td>
                                <td class="text-end small text-warning">{{ format_currency($sale->tax_amount) }}</td>
                                <td class="text-end small text-danger">{{ format_currency($sale->discount_amount) }}</td>
                                <td class="text-end fw-bold">{{ format_currency($sale->grand_total) }}</td>
                                <td class="text-end small text-success fw-semibold">
                                    {{ format_currency($sale->paid_amount) }}</td>
                                <td
                                    class="text-end small {{ $sale->due_amount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                    {{ format_currency($sale->due_amount) }}
                                </td>
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
                                <td colspan="12" class="text-center py-5 text-muted">
                                    <i class="bx bx-receipt d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                    No sales found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($sales->count())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end ps-3 fw-bold">Totals ({{ $totals['count'] }} invoices)
                                </td>
                                <td class="text-end">{{ format_currency($totals['sub_total']) }}</td>
                                <td class="text-end text-warning">{{ format_currency($totals['tax_amount']) }}</td>
                                <td class="text-end text-danger">{{ format_currency($totals['discount_amount']) }}</td>
                                <td class="text-end text-primary">{{ format_currency($totals['grand_total']) }}</td>
                                <td class="text-end text-success">{{ format_currency($totals['paid_amount']) }}</td>
                                <td class="text-end text-danger">{{ format_currency($totals['due_amount']) }}</td>
                                <td></td>
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
            $('#salesReportTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [
                    [2, 'desc']
                ],
                columnDefs: [{
                    targets: [11],
                    orderable: false
                }],
                dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search invoices...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    emptyTable: '<div class="text-center py-5 text-muted"><i class="bx bx-inbox" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:8px;"></i>{{ __('messages.no_records') }}</div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });
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
            form,
            #salesReportTable_wrapper .row:first-child,
            #salesReportTable_wrapper .row:last-child {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
