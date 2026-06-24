@extends('layouts.admin')
@section('title', 'Purchase Report')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Purchase Report</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Purchases</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-info"></i>Filters</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Supplier</label>
                        <select name="supplier_id" class="form-select form-select-sm">
                            <option value="">All Suppliers</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}"
                                    {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
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
                            <option value="Bank" {{ request('payment_method') === 'Bank' ? 'selected' : '' }}>Bank
                            </option>
                            <option value="Credit" {{ request('payment_method') === 'Credit' ? 'selected' : '' }}>Credit
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-info btn-sm flex-fill text-white"><i
                                class="bx bx-search"></i></button>
                        <a href="{{ route('reports.purchases') }}" class="btn btn-outline-secondary btn-sm"><i
                                class="bx bx-reset"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Totals --}}
    <div class="row g-3 mb-4">
        @php $cards = [['label' => 'Total Orders', 'value' => $totals['count'], 'icon' => 'bx-receipt', 'color' => 'info'], ['label' => 'Grand Total', 'value' => format_currency($totals['grand_total']), 'icon' => 'bx-rupee', 'color' => 'primary'], ['label' => 'Paid Amount', 'value' => format_currency($totals['paid_amount']), 'icon' => 'bx-check-circle', 'color' => 'success'], ['label' => 'Due Amount', 'value' => format_currency($totals['due_amount']), 'icon' => 'bx-time', 'color' => 'warning'], ['label' => 'Tax Paid', 'value' => format_currency($totals['tax_amount']), 'icon' => 'bx-percent', 'color' => 'secondary'], ['label' => 'Discounts', 'value' => format_currency($totals['discount_amount']), 'icon' => 'bx-tag', 'color' => 'danger']]; @endphp
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
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-list-ul me-2 text-info"></i>Purchase Orders
                ({{ $totals['count'] }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="purReportTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Purchase No</th>
                            <th>Date</th>
                            <th>Supplier</th>
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
                        @forelse($purchases as $i => $p)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td><a href="{{ route('purchases.show', $p->id) }}"
                                        class="fw-semibold text-info">{{ $p->purchase_no }}</a></td>
                                <td class="small text-muted">{{ $p->purchase_date }}</td>
                                <td class="small">{{ $p->supplier->name ?? '—' }}</td>
                                <td class="small">{{ $p->payment_method ?? '—' }}</td>
                                <td class="text-end small">{{ format_currency($p->sub_total) }}</td>
                                <td class="text-end small text-warning">{{ format_currency($p->tax_amount) }}</td>
                                <td class="text-end small text-danger">{{ format_currency($p->discount_amount) }}</td>
                                <td class="text-end fw-bold">{{ format_currency($p->grand_total) }}</td>
                                <td class="text-end small text-success">{{ format_currency($p->paid_amount) }}</td>
                                <td
                                    class="text-end small {{ $p->due_amount > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    {{ format_currency($p->due_amount) }}</td>
                                <td class="text-center">
                                    @if ($p->status === 'Completed')
                                        <span class="badge bg-success rounded-pill">Completed</span>
                                    @elseif($p->status === 'Pending')
                                        <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">{{ $p->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">No purchases found for selected
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
                            @if ($purchases->count())
                                <th class="text-end small">Total ({{ $totals['count'] }})</th>
                                <th class="text-end">{{ format_currency($totals['sub_total']) }}</th>
                                <th class="text-end text-warning">{{ format_currency($totals['tax_amount']) }}</th>
                                <th class="text-end text-danger">{{ format_currency($totals['discount_amount']) }}</th>
                                <th class="text-end text-info">{{ format_currency($totals['grand_total']) }}</th>
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
            $('#purReportTable').DataTable({
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
