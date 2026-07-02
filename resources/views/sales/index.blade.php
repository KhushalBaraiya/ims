@extends('layouts.admin')
@section('title', __('messages.sales_invoices'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
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
                <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button">
                    <i class="bx bx-trash me-1"></i> Delete Multiples
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
        <div class="col-6 col-xl-3">
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
        <div class="col-6 col-xl-3">
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
        <div class="col-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">Total Revenue</p>
                        <h4 class="fw-bold text-info mb-0">{{ format_currency($totalSaleAmount) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">Total Due</p>
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
                            <label class="form-label fw-semibold small">Payment Status</label>
                            <select class="form-select form-select-sm" name="payment_status">
                                <option value="">All</option>
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
                            <th class="text-center">Payment</th>
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
                                        <span class="badge rounded-pill bg-success">Completed</span>
                                    @elseif($sale->status === 'Pending')
                                        <span class="badge rounded-pill bg-info text-dark">Pending</span>
                                    @elseif($sale->status === 'Draft')
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.draft') }}</span>
                                    @elseif($sale->status === 'Repair')
                                        <span class="badge rounded-pill bg-secondary">Repair</span>
                                    @elseif($sale->status === 'Ordered')
                                        <span class="badge rounded-pill bg-primary">Ordered</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">{{ $sale->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($sale->payment_status === 'Paid')
                                        <span class="badge rounded-pill bg-success">Paid</span>
                                    @elseif($sale->payment_status === 'Partial')
                                        <span class="badge rounded-pill bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">Unpaid</span>
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
                                            <a class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action btn-payment-modal"
                                                data-action="{{ route('sales.update-payment', $sale->id) }}"
                                                data-due-amount="{{ $sale->due_amount }}"
                                                data-grand-total="{{ $sale->grand_total }}" data-id="{{ $sale->id }}"
                                                data-invoice="{{ $sale->invoice_no }}"
                                                data-paid-amount="{{ $sale->paid_amount }}"
                                                data-payment-method="{{ $sale->payment_method }}" href="#"
                                                style="width:30px;height:30px;padding:0;" title="Update Payment">
                                                <i class="bx bx-credit-card" style="font-size:1rem;"></i>
                                            </a>
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

    {{-- ── Payment Management Modal ─────────────────────────────────────── --}}
    <div aria-hidden="true" aria-labelledby="paymentModalLabel" class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3 text-white">
                    <h5 class="modal-title fw-bold" id="paymentModalLabel">
                        <i class="bx bx-credit-card me-2"></i>Manage Payment
                    </h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Invoice No</label>
                            <input class="form-control bg-light fw-bold text-dark border-0" id="modal_invoice_no" readonly
                                type="text">
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-light rounded border p-3 text-center">
                                    <div class="text-muted small fw-semibold mb-1">Grand Total</div>
                                    <div class="fw-bold text-primary fs-5" id="modal_grand_total_text">₹0.00</div>
                                    <input id="modal_grand_total" type="hidden">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-danger border-danger rounded border border-opacity-25 bg-opacity-10 p-3 text-center"
                                    id="modal_due_box">
                                    <div class="small fw-semibold text-danger mb-1" id="modal_due_label">Balance Due</div>
                                    <div class="fw-bold fs-5 text-danger" id="modal_balance_due_text">₹0.00</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
                            <input class="form-control form-control-lg fw-bold" id="modal_paid_amount" min="0"
                                name="paid_amount" required step="0.01" type="number">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="modal_payment_method" name="payment_method"
                                required>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Card">Credit/Debit Card</option>
                                <option value="UPI / QR">UPI / QR Code</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top p-3">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" id="btnSavePayment" type="submit">
                            <i class="bx bx-save me-1"></i> Update Payment
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

            // ── Bulk Select ──────────────────────────────────────────────
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

            // ── Bulk Delete ──────────────────────────────────────────────
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: 'Delete ' + ids.length + ' sale(s)?',
                    text: 'Stock will be restored for Completed invoices. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete all!',
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
                                            title: 'Deleted!',
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

            // ── Payment Modal ─────────────────────────────────────────────
            $(document).on('click', '.btn-payment-modal', function(e) {
                e.preventDefault();
                const btn = $(this);
                const grandTotal = parseFloat(btn.data('grand-total')) || 0;
                const paidAmount = parseFloat(btn.data('paid-amount')) || 0;
                const paymentMethod = btn.data('payment-method') || 'Cash';

                $('#modal_invoice_no').val(btn.data('invoice'));
                $('#modal_grand_total').val(grandTotal);
                $('#modal_grand_total_text').text('₹' + grandTotal.toFixed(2));
                $('#modal_paid_amount').val(paidAmount.toFixed(2));
                // Restore exact saved payment method; only fall back to Cash if truly blank
                const pmSelect = $('#modal_payment_method');
                pmSelect.val(paymentMethod || '');
                pmSelect.trigger('change');
                $('#paymentForm').attr('action', btn.data('action'));

                updateModalDue();
                $('#paymentModal').modal('show');
            });

            $('#modal_paid_amount').on('input change', updateModalDue);

            function updateModalDue() {
                const total = parseFloat($('#modal_grand_total').val()) || 0;
                const paid = parseFloat($('#modal_paid_amount').val()) || 0;
                const due = Math.max(0, total - paid);
                $('#modal_balance_due_text').text('₹' + due.toFixed(2));
                const box = $('#modal_due_box');
                const label = $('#modal_due_label');
                if (due > 0) {
                    box.removeClass('bg-success bg-opacity-10 border-success border-opacity-25')
                        .addClass('bg-danger bg-opacity-10 border-danger border-opacity-25');
                    label.removeClass('text-success').addClass('text-danger');
                    $('#modal_balance_due_text').removeClass('text-success').addClass('text-danger');
                } else {
                    box.removeClass('bg-danger bg-opacity-10 border-danger border-opacity-25')
                        .addClass('bg-success bg-opacity-10 border-success border-opacity-25');
                    label.removeClass('text-danger').addClass('text-success');
                    $('#modal_balance_due_text').removeClass('text-danger').addClass('text-success');
                }
            }

            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
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
                            $('#paymentModal').modal('hide');
                            showAdminToast(res.message, 'success');
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            showAdminToast(res.message, 'error');
                            btn.prop('disabled', false).html(orig);
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message ??
                            'An error occurred while updating payment.';
                        showAdminToast(msg, 'error');
                        btn.prop('disabled', false).html(orig);
                    }
                });
            });
        });
    </script>
@endpush
