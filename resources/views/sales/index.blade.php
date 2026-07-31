@extends('layouts.admin')
@section('title', __('messages.sales_invoices'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.sales_invoices') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_sales') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-1" id="toggleFiltersBtn" type="button">
                <i class="bx bx-filter-alt"></i> {{ __('messages.filters') }}
                <i class="bx bx-chevron-down" id="filtersChevron"></i>
            </button>
            @can('sales.delete')
                <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button"><i class="bx bx-trash me-1"></i>
                    {{ __('messages.delete_multiples') }}
                </button>
            @endcan
            @can('sales.create')
                <a class="btn btn-outline-primary d-flex align-items-center gap-1" href="{{ route('sales.create') }}">
                    <i class="bx bx-plus"></i> {{ __('messages.add_sale') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Summary Stats --}}
    @php
        use App\Models\Sale;
        $totalSales = Sale::count();
        $completedSales = Sale::where('status', 'Completed')->count();
        $draftSales = Sale::where('status', 'Draft')->count();
        $totalSaleAmount = Sale::where('status', 'Completed')->sum('grand_total');
        $totalSaleDue = Sale::where('status', 'Completed')->sum('due_amount');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.th_total') }}</p>
                        <h4 class="fw-bold text-primary mb-0">{{ $totalSales }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-receipt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.completed') }}</p>
                        <h4 class="fw-bold text-success mb-0">{{ $completedSales }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.total_revenue') }}</p>
                        <h4 class="fw-bold text-info mb-0">{{ format_currency($totalSaleAmount) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.total_due') }}</p>
                        <h4 class="fw-bold {{ $totalSaleDue > 0 ? 'text-danger' : 'text-success' }} mb-0">
                            {{ format_currency($totalSaleDue) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none mb-4" id="filtersCard">
        <div class="card shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-filter-alt text-primary me-2"></i>{{ __('messages.filter_sales') }}</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sales.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.invoice_no_label') }}</label>
                            <input class="form-control form-control-sm" name="invoice_no" placeholder="INV-YYYYMMDD-XXXXX"
                                type="text" value="{{ request('invoice_no') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.customer') }}</label>
                            <select class="form-select form-select-sm" name="customer_id">
                                <option value="">{{ __('messages.all_customers') }}</option>
                                @foreach ($customers as $c)
                                    <option {{ request('customer_id') == $c->id ? 'selected' : '' }}
                                        value="{{ $c->id }}">{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                @foreach (['Completed', 'Pending', 'Draft', 'Repair', 'Ordered'] as $statusOpt)
                                    <option {{ request('status') === $statusOpt ? 'selected' : '' }}
                                        value="{{ $statusOpt }}">
                                        {{ $statusOpt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.payment_status_label') }}</label>
                            <select class="form-select form-select-sm" name="payment_status">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option {{ request('payment_status') === 'Unpaid' ? 'selected' : '' }} value="Unpaid">
                                    Unpaid</option>
                                <option {{ request('payment_status') === 'Partial' ? 'selected' : '' }} value="Partial">
                                    Partial</option>
                                <option {{ request('payment_status') === 'Paid' ? 'selected' : '' }} value="Paid">Paid
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                            <input class="form-control form-control-sm flatpickr-filter-date" name="start_date"
                                type="date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                            <input class="form-control form-control-sm flatpickr-filter-date" name="end_date"
                                type="date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a class="btn btn-outline-secondary" href="{{ route('sales.index') }}"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button class="btn btn-primary" type="submit"><i class="bx bx-search me-1"></i>
                            {{ __('messages.apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table-hover mb-0 table align-middle" id="salesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input class="form-check-input" id="selectAll" type="checkbox"></th>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.th_invoice') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.th_customer') }}</th>
                            <th>{{ __('messages.th_items') }}</th>
                            <th class="text-end">{{ __('messages.th_total') }}</th>
                            <th class="text-end">{{ __('messages.th_paid') }}</th>
                            <th class="text-end">{{ __('messages.th_due') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center">{{ __('messages.payment_label') }}</th>
                            <th>{{ __('messages.th_created_by') }}</th>
                            <th class="no-sort text-center">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $index => $sale)
                            <tr>
                                <td><input class="form-check-input row-checkbox" type="checkbox"
                                        value="{{ $sale->id }}"></td>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><code class="fw-bold">{{ $sale->invoice_no }}</code></td>
                                <td class="text-muted">{{ $sale->invoice_date }}</td>
                                <td><strong>{{ $sale->customer->name }}</strong></td>
                                <td class="text-muted">{{ $sale->items->count() }} {{ __('messages.items_count') }}</td>
                                <td class="fw-bold text-end">{{ format_currency($sale->grand_total) }}</td>
                                <td class="text-success fw-semibold text-end">{{ format_currency($sale->paid_amount) }}
                                </td>
                                <td class="text-danger fw-semibold text-end">{{ format_currency($sale->due_amount) }}</td>
                                <td class="text-center">
                                    @if ($sale->status === 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                    @elseif($sale->status === 'Pending')
                                        <span
                                            class="badge rounded-pill bg-info text-dark">{{ __('messages.pending') }}</span>
                                    @elseif($sale->status === 'Draft')
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.draft') }}</span>
                                    @elseif($sale->status === 'Repair')
                                        <span class="badge rounded-pill bg-secondary">{{ __('messages.repair') }}</span>
                                    @elseif($sale->status === 'Ordered')
                                        <span
                                            class="badge rounded-pill bg-primary">{{ __('messages.ordered_badge') }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">{{ $sale->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($sale->payment_status === 'Paid')
                                        <span class="badge rounded-pill bg-success">{{ __('messages.paid') }}</span>
                                    @elseif($sale->payment_status === 'Partial')
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.partial') }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">{{ __('messages.unpaid') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $sale->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('sales.view')
                                            <a class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                href="{{ route('sales.show', $sale->id) }}"
                                                style="width:30px;height:30px;padding:0;" title="{{ __('messages.view') }}">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                href="{{ route('sales.print', $sale->id) }}"
                                                style="width:30px;height:30px;padding:0;" target="_blank"
                                                title="{{ __('messages.print') }}">
                                                <i class="bx bx-printer" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('sales.update')
                                            @if ($sale->status === 'Completed' && $sale->returns->isEmpty())
                                                @can('sale_returns.create')
                                                    <a class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                        href="{{ route('sale-returns.create', ['sale_id' => $sale->id]) }}"
                                                        style="width:30px;height:30px;padding:0;" title="Sale Return">
                                                        <i class="bx bx-undo" style="font-size:1rem;"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action btn-payment-modal"
                                                data-action="{{ route('sales.update-payment', $sale->id) }}"
                                                data-due-amount="{{ $sale->due_amount }}"
                                                data-grand-total="{{ $sale->grand_total }}" data-id="{{ $sale->id }}"
                                                data-invoice="{{ $sale->invoice_no }}"
                                                data-paid-amount="{{ $sale->paid_amount }}"
                                                data-payment-method="{{ $sale->payment_method }}"
                                                style="width:30px;height:30px;padding:0;" title="{{ __('messages.set_update_payment') }}">
                                                <i class="bx bx-credit-card" style="font-size:1rem;"></i>
                                            </button>
                                            <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                href="{{ route('sales.edit', $sale->id) }}"
                                                style="width:30px;height:30px;padding:0;" title="{{ __('messages.edit') }}">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('sales.delete')
                                            <form action="{{ route('sales.destroy', $sale->id) }}" class="d-inline"
                                                id="delete-form-{{ $sale->id }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_no }}"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="{{ __('messages.delete') }}" type="button">
                                                    <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- -- Payment Management Modal --------------------------------------- --}}
    <div aria-hidden="true" aria-labelledby="paymentModalLabel" class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius:16px;">

                {{-- Gradient Header --}}
                <div class="modal-header border-0 text-white py-4 px-4"
                    style="background:linear-gradient(135deg,#696cff 0%,#9c3fe4 100%);position:relative;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:42px;height:42px;background:rgba(255,255,255,.2);">
                            <i class="bx bx-credit-card text-white" style="font-size:1.3rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title fw-bold mb-0" id="paymentModalLabel">Manage Payment</h5>
                            <input class="bg-transparent border-0 text-white opacity-75 small p-0 w-100"
                                id="modal_invoice_no" readonly style="outline:none;" type="text">
                        </div>
                        <button aria-label="Close" data-bs-dismiss="modal" type="button"
                            style="width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.2);
                                   border:none;color:#fff;display:flex;align-items:center;justify-content:center;
                                   flex-shrink:0;transition:background .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,.35)'"
                            onmouseout="this.style.background='rgba(255,255,255,.2)'">
                            <i class="bx bx-x" style="font-size:1.2rem;"></i>
                        </button>
                    </div>
                </div>

                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Grand Total / Balance Due Cards --}}
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="rounded-3 p-3 text-center h-100"
                                    style="background:#f0f4ff;border:1.5px solid #d0d8ff;">
                                    <div class="text-muted small fw-semibold mb-1">
                                        <i class="bx bx-receipt me-1"></i>Grand Total
                                    </div>
                                    <div class="fw-bold text-primary" style="font-size:1.4rem;"
                                        id="modal_grand_total_text">
                                        {{ optional(current_currency())->symbol ?? '₹' }}0.00
                                    </div>
                                    <input id="modal_grand_total" type="hidden">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-3 p-3 text-center h-100 balance-due-card"
                                    style="background:#fff0f0;border:1.5px solid #ffd0d0;">
                                    <div class="small fw-semibold mb-1 text-danger">
                                        <i class="bx bx-time-five me-1"></i>Balance Due
                                    </div>
                                    <div class="fw-bold text-danger" style="font-size:1.4rem;"
                                        id="modal_balance_due_text">
                                        {{ optional(current_currency())->symbol ?? '₹' }}0.00
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Paid Amount --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">
                                {{ __('messages.paid_amount_field') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 fw-bold text-primary"
                                    style="font-size:1rem;">
                                    {{ optional(current_currency())->symbol ?? '₹' }}
                                </span>
                                <input class="form-control border-start-0 fw-bold ps-0" id="modal_paid_amount"
                                    min="0" name="paid_amount" required step="0.01" type="number"
                                    style="font-size:1.15rem;">
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">
                                Payment Method <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="modal_payment_method" name="payment_method"
                                data-no-select2="1" required style="height:46px;">
                                <option value="Cash">💵 {{ __('messages.pm_cash') }}</option>
                                <option value="Razorpay">⚡ Razorpay (Online Payment)</option>
                            </select>
                        </div>

                        {{-- Razorpay info banner --}}
                        <div class="rounded-3 p-3 d-none" id="modal_razorpay_info"
                            style="background:linear-gradient(135deg,#eef2ff,#f5f0ff);border:1.5px solid #c7d2fe;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span
                                    class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:28px;height:28px;background:linear-gradient(135deg,#696cff,#9c3fe4);">
                                    <i class="bx bx-lock-alt text-white" style="font-size:.85rem;"></i>
                                </span>
                                <strong class="text-primary small">{{ __('messages.set_secure_razorpay') }}</strong>
                            </div>
                            <p class="text-muted small mb-0 ps-1">
                                Click <strong style="color:#696cff;">"Pay via Razorpay"</strong> to open the secure payment
                                gateway.
                                {{ __('messages.set_payment_auto_recorded') }}
                            </p>
                        </div>

                        {{-- Hidden Razorpay fields --}}
                        <input type="hidden" id="modal_razorpay_order_id" name="razorpay_order_id">
                        <input type="hidden" id="modal_razorpay_payment_id" name="razorpay_payment_id">
                        <input type="hidden" id="modal_razorpay_signature" name="razorpay_signature">
                        <input type="hidden" id="modal_sale_id" name="sale_id">
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer border-top px-4 py-3 gap-2 bg-light" style="border-radius:0 0 16px 16px;">
                        <button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal"
                            type="button">
                            <i class="bx bx-x me-1"></i> Cancel
                        </button>
                        <button class="btn btn-primary rounded-pill px-4 ms-auto" id="btnSavePayment" type="submit">
                            <i class="bx bx-save me-1"></i> Update Payment
                        </button>
                        <button class="btn rounded-pill px-4 ms-auto d-none" id="btnModalPayRazorpay" type="button"
                            style="background:linear-gradient(135deg,#696cff,#9c3fe4);color:#fff;border:none;">
                            <i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const sym = '{{ optional(current_currency())->symbol ?? '?' }}';
            $('#salesTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-receipt" style="font-size:3rem;opacity:.3;line-height:1;"></i>{{ __('messages.no_records') }}</div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            // Filters toggle
            let filtersOpen = localStorage.getItem('sales_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('sales_filters_open', isOpen);
            });

            // Delete handler
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    invoice = $(this).data('invoice'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `{{ __('messages.delete') }} "${invoice}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        title: '{{ __('messages.deleted_title') }}',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#696cff'
                                    }).then(() => window.location.reload());
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: function() {
                                showAdminToast('{{ __('messages.error_occurred') }}',
                                    'error');
                            }
                        });
                    }
                });
            });

            // -- Bulk Select ----------------------------------------------
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleBulkBtn();
            });
            $(document).on('change', '.row-checkbox', function() {
                $('#selectAll').prop('checked', $('.row-checkbox:not(:checked)').length === 0);
                toggleBulkBtn();
            });

            function toggleBulkBtn() {
                const count = $('.row-checkbox:checked').length;
                count > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            // -- Bulk Delete ----------------------------------------------
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: '{{ __('messages.confirm_delete') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route('sales.bulk-destroy') }}',
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: ids
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                            title: '{{ __('messages.deleted_title') }}',
                                            text: res.message,
                                            icon: 'success',
                                            confirmButtonColor: '#696cff'
                                        })
                                        .then(() => window.location.reload());
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                const msg = xhr.responseJSON?.message ||
                                    '{{ __('messages.error_occurred') }}';
                                showAdminToast(msg, 'error');
                            }
                        });
                    }
                });
            });

            // -- Payment Modal ---------------------------------------------
            $(document).on('click', '.btn-payment-modal', function() {
                const btn = $(this);
                const grandTotal = parseFloat(btn.data('grand-total')) || 0;
                const paidAmount = parseFloat(btn.data('paid-amount')) || 0;
                const paymentMethod = btn.data('payment-method') || 'Cash';

                $('#modal_invoice_no').val(btn.data('invoice'));
                $('#modal_grand_total').val(grandTotal);
                $('#modal_grand_total_text').text(sym + grandTotal.toFixed(2));
                $('#modal_paid_amount').val(paidAmount.toFixed(2));
                $('#modal_payment_method').val(paymentMethod || 'Cash');
                $('#modal_sale_id').val(btn.data('id'));
                $('#paymentForm').attr('action', btn.data('action'));

                // Clear previous Razorpay fields
                $('#modal_razorpay_order_id, #modal_razorpay_payment_id, #modal_razorpay_signature').val(
                    '');

                updateModalDue();
                toggleModalRazorpay();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('paymentModal')).show();
            });

            $('#modal_paid_amount').on('input change', updateModalDue);

            $('#modal_payment_method').on('change', function() {
                toggleModalRazorpay();
                if ($(this).val() === 'Razorpay') {
                    const gt = parseFloat($('#modal_grand_total').val()) || 0;
                    $('#modal_paid_amount').val(gt.toFixed(2)).prop('readonly', true);
                    updateModalDue();
                } else {
                    $('#modal_paid_amount').prop('readonly', false);
                }
            });

            function toggleModalRazorpay() {
                const isRazorpay = $('#modal_payment_method').val() === 'Razorpay';
                $('#btnSavePayment').toggleClass('d-none', isRazorpay);
                $('#btnModalPayRazorpay').toggleClass('d-none', !isRazorpay);
                $('#modal_razorpay_info').toggleClass('d-none', !isRazorpay);
            }

            function updateModalDue() {
                const total = parseFloat($('#modal_grand_total').val()) || 0;
                const paid = parseFloat($('#modal_paid_amount').val()) || 0;
                const due = Math.max(0, total - paid);
                $('#modal_balance_due_text').text(sym + due.toFixed(2));
                const $card = $('.balance-due-card');
                if (due > 0) {
                    $card.css({
                        'background': '#fff0f0',
                        'border': '1.5px solid #ffd0d0'
                    });
                    $card.find('.small').removeClass('text-success').addClass('text-danger');
                    $('#modal_balance_due_text').removeClass('text-success').addClass('text-danger');
                } else {
                    $card.css({
                        'background': '#f0fff4',
                        'border': '1.5px solid #c6f6d5'
                    });
                    $card.find('.small').removeClass('text-danger').addClass('text-success');
                    $('#modal_balance_due_text').removeClass('text-danger').addClass('text-success');
                }
            }

            // -- Razorpay Pay button in modal ----------------------------
            $('#btnModalPayRazorpay').on('click', function() {
                const grandTotal = parseFloat($('#modal_grand_total').val()) || 0;
                if (grandTotal <= 0) {
                    showAdminToast('Grand total must be greater than 0.', 'warning');
                    return;
                }
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Processing…');

                $.ajax({
                    url: "{{ route('razorpay.create-order') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        amount: grandTotal
                    },
                    success: function(res) {
                        const options = {
                            key: res.key_id,
                            amount: res.amount,
                            currency: res.currency,
                            name: '{{ addslashes(config('app.name')) }}',
                            description: 'Sale Payment — ' + $('#modal_invoice_no').val(),
                            order_id: res.order_id,
                            prefill: {
                                name: '{{ addslashes(auth()->user()->name ?? '') }}',
                                email: '{{ addslashes(auth()->user()->email ?? '') }}',
                            },
                            theme: {
                                color: '#696cff'
                            },
                            handler: function(response) {
                                const saleId = $('#modal_sale_id').val();
                                $.ajax({
                                    url: '/sales/' + saleId + '/payment',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]')
                                            .attr('content'),
                                        paid_amount: grandTotal.toFixed(2),
                                        payment_method: 'Razorpay',
                                        razorpay_order_id: response
                                            .razorpay_order_id,
                                        razorpay_payment_id: response
                                            .razorpay_payment_id,
                                        razorpay_signature: response
                                            .razorpay_signature,
                                    },
                                    success: function(res) {
                                        bootstrap.Modal.getOrCreateInstance(
                                            document.getElementById(
                                                'paymentModal')).hide();
                                        showAdminToast(res.message ||
                                            'Payment received via Razorpay.',
                                            'success');
                                        setTimeout(() => window.location
                                            .reload(), 1400);
                                    },
                                    error: function(xhr) {
                                        showAdminToast(xhr.responseJSON
                                            ?.message ||
                                            'Payment verified but update failed.',
                                            'danger');
                                        btn.prop('disabled', false).html(
                                            '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay'
                                        );
                                    }
                                });
                            },
                            modal: {
                                ondismiss: function() {
                                    btn.prop('disabled', false).html(
                                        '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay'
                                    );
                                    showAdminToast('Payment cancelled.', 'warning');
                                }
                            }
                        };
                        const rzp = new Razorpay(options);
                        rzp.on('payment.failed', function(r) {
                            showAdminToast('Payment failed: ' + r.error.description,
                                'danger');
                            btn.prop('disabled', false).html(
                                '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay'
                            );
                        });
                        rzp.open();
                    },
                    error: function(xhr) {
                        showAdminToast(xhr.responseJSON?.message ||
                            'Could not create Razorpay order.', 'danger');
                        btn.prop('disabled', false).html(
                            '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay');
                    }
                });
            });

            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
                if ($('#modal_payment_method').val() === 'Razorpay') return;
                const form = $(this);
                const btn = $('#btnSavePayment');
                const orig = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.success) {
                            bootstrap.Modal.getOrCreateInstance(document.getElementById(
                                'paymentModal')).hide();
                            showAdminToast(res.message, 'success');
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            showAdminToast(res.message, 'error');
                            btn.prop('disabled', false).html(orig);
                        }
                    },
                    error: function(xhr) {
                        showAdminToast(xhr.responseJSON?.message ?? 'An error occurred.',
                            'error');
                        btn.prop('disabled', false).html(orig);
                    }
                });
            });
        });
    </script>

    {{-- Razorpay JS SDK --}}
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endpush
