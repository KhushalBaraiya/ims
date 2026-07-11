@extends('layouts.admin')
@section('title', __('messages.purchase_report'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.purchase_report') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">{{ __('messages.all_reports') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('messages.purchase_report') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.purchases.export', request()->query()) }}" class="btn btn-outline-success btn-sm">
                <i class="bx bx-download me-1"></i> {{ __('messages.export') }}
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> {{ __('messages.print') }}
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.all_reports') }}
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-filter-alt me-2 text-info"></i>{{ __('messages.filter_purchases') }}</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.supplier') }}</label>
                        <select name="supplier_id" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_suppliers') }}</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}"
                                    {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_statuses') }}</option>
                            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>
                                {{ __('messages.completed') }}</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>
                                {{ __('messages.pending') }}</option>
                            <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>
                                {{ __('messages.cancelled') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.payment_method') }}</label>
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="">{{ __('messages.rpt_all_methods') }}</option>
                            <option value="Cash" {{ request('payment_method') === 'Cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="Card" {{ request('payment_method') === 'Card' ? 'selected' : '' }}>Card
                            </option>
                            <option value="Bank" {{ request('payment_method') === 'Bank' ? 'selected' : '' }}>Bank
                                Transfer</option>
                            <option value="Credit" {{ request('payment_method') === 'Credit' ? 'selected' : '' }}>Credit
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-info btn-sm flex-fill text-white"><i
                                class="bx bx-search"></i></button>
                        <a href="{{ route('reports.purchases') }}" class="btn btn-outline-secondary btn-sm"><i
                                class="bx bx-reset"></i></a>
                    </div>
                </div>
                {{-- Quick date shortcuts --}}
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <span
                        class="text-muted small fw-semibold me-1 align-self-center">{{ __('messages.rpt_quick') }}:</span>
                    <a href="?date_from={{ now()->toDateString() }}&date_to={{ now()->toDateString() }}"
                        class="btn btn-outline-secondary btn-sm py-0">{{ __('messages.today') }}</a>
                    <a href="?date_from={{ now()->startOfWeek()->toDateString() }}&date_to={{ now()->toDateString() }}"
                        class="btn btn-outline-secondary btn-sm py-0">{{ __('messages.rpt_this_week') }}</a>
                    <a href="?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}"
                        class="btn btn-outline-secondary btn-sm py-0">{{ __('messages.rpt_this_month') }}</a>
                    <a href="?date_from={{ now()->subMonth()->startOfMonth()->toDateString() }}&date_to={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                        class="btn btn-outline-secondary btn-sm py-0">{{ __('messages.rpt_last_month') }}</a>
                    <a href="?date_from={{ now()->startOfYear()->toDateString() }}&date_to={{ now()->toDateString() }}"
                        class="btn btn-outline-secondary btn-sm py-0">{{ __('messages.rpt_this_year') }}</a>
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
                        <p class="mb-0 text-muted small">{{ __('messages.rpt_total_orders') }}</p>
                        <h5 class="mb-0 fw-bold text-info">{{ $totals['count'] }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;"><i
                            class="bx bx-receipt"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.grand_total') }}</p>
                        <h5 class="mb-0 fw-bold text-primary">{{ format_currency($totals['grand_total']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;"><i
                            class="bx bx-rupee"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.paid_amount') }}</p>
                        <h5 class="mb-0 fw-bold text-success">{{ format_currency($totals['paid_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1rem;"><i
                            class="bx bx-check-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.due_amount') }}</p>
                        <h5 class="mb-0 fw-bold {{ $totals['due_amount'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ format_currency($totals['due_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;"><i
                            class="bx bx-time"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.rpt_tax_paid') }}</p>
                        <h5 class="mb-0 fw-bold text-secondary">{{ format_currency($totals['tax_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-secondary p-3" style="font-size:1rem;"><i
                            class="bx bx-percent"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.rpt_discounts') }}</p>
                        <h5 class="mb-0 fw-bold text-warning">{{ format_currency($totals['discount_amount']) }}</h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;"><i
                            class="bx bx-tag"></i></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-list-ul me-2 text-info"></i>{{ __('messages.purchase_orders') }}
                <span class="badge bg-label-info ms-1">{{ $totals['count'] }}</span>
            </h6>
            @if (request()->hasAny(['date_from', 'date_to', 'supplier_id', 'status', 'payment_method']))
                <span class="badge bg-label-secondary small fw-normal">
                    <i class="bx bx-filter-alt me-1"></i>{{ __('messages.rpt_filtered_results') }}
                </span>
            @endif
        </div>
        <div class="card-body p-0">

            @if ($purchases->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-cart-download" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="purReportTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>{{ __('messages.th_purchase_no') }}</th>
                                <th>{{ __('messages.th_date') }}</th>
                                <th>{{ __('messages.supplier') }}</th>
                                <th>{{ __('messages.payment_method') }}</th>
                                <th class="text-end">{{ __('messages.subtotal') }}</th>
                                <th class="text-end">{{ __('messages.tax') }}</th>
                                <th class="text-end">{{ __('messages.discount') }}</th>
                                <th class="text-end">{{ __('messages.grand_total') }}</th>
                                <th class="text-end">{{ __('messages.th_paid') }}</th>
                                <th class="text-end">{{ __('messages.th_due') }}</th>
                                <th class="text-center no-sort">{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $i => $p)
                                <tr>
                                    <td class="text-muted small ps-3">{{ $i + 1 }}</td>
                                    <td>
                                        <a href="{{ route('purchases.show', $p->id) }}" class="fw-semibold text-info">
                                            <code>{{ $p->purchase_no }}</code>
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($p->purchase_date)->format('d M Y') }}
                                    </td>
                                    <td class="fw-semibold small">{{ $p->supplier->name ?? '—' }}</td>
                                    <td>
                                        @if ($p->payment_method)
                                            <span class="badge bg-label-secondary">{{ $p->payment_method }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end small">{{ format_currency($p->sub_total) }}</td>
                                    <td class="text-end small text-warning">{{ format_currency($p->tax_amount) }}</td>
                                    <td class="text-end small text-danger">-{{ format_currency($p->discount_amount) }}
                                    </td>
                                    <td class="text-end fw-bold">{{ format_currency($p->grand_total) }}</td>
                                    <td class="text-end small text-success fw-semibold">
                                        {{ format_currency($p->paid_amount) }}</td>
                                    <td
                                        class="text-end small {{ $p->due_amount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                        {{ format_currency($p->due_amount) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($p->status === 'received' || $p->status === 'Completed')
                                            <span class="badge bg-success rounded-pill">{{ __('messages.received_badge') }}</span>
                                        @elseif ($p->status === 'pending' || $p->status === 'Pending')
                                            <span class="badge bg-warning text-dark rounded-pill">{{ __('messages.pending') }}</span>
                                        @elseif ($p->status === 'ordered')
                                            <span class="badge bg-primary rounded-pill">{{ __('messages.ordered_badge') }}</span>
                                        @elseif ($p->status === 'draft')
                                            <span class="badge bg-secondary rounded-pill">{{ __('messages.draft') }}</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">{{ $p->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end ps-3">
                                    {{ __('messages.rpt_totals') }} <span
                                        class="text-muted fw-normal">({{ $totals['count'] }}
                                        {{ __('messages.rpt_orders') }})</span>
                                </td>
                                <td class="text-end">{{ format_currency($totals['sub_total']) }}</td>
                                <td class="text-end text-warning">{{ format_currency($totals['tax_amount']) }}</td>
                                <td class="text-end text-danger">-{{ format_currency($totals['discount_amount']) }}</td>
                                <td class="text-end text-primary">{{ format_currency($totals['grand_total']) }}</td>
                                <td class="text-end text-success">{{ format_currency($totals['paid_amount']) }}</td>
                                <td class="text-end text-danger">{{ format_currency($totals['due_amount']) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#purReportTable').length) {
                $('#purReportTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [2, 'desc']
                    ],
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search purchases...",
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
            form,
            #purReportTable_wrapper .row:first-child,
            #purReportTable_wrapper .row:last-child {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

