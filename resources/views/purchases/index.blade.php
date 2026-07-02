@extends('layouts.admin')
@section('title', __('messages.purchase_orders'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.purchase_orders') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_purchases') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-1" id="toggleFiltersBtn" type="button">
                <i class="bx bx-filter-alt"></i> {{ __('messages.filters') }}
                <i class="bx bx-chevron-down" id="filtersChevron"></i>
            </button>
            @can('purchases.delete')
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> Delete Multiples
                </button>
            @endcan
            @can('purchases.create')
                <a class="btn btn-outline-primary d-flex align-items-center gap-1" href="{{ route('purchases.create') }}">
                    <i class="bx bx-plus"></i> {{ __('messages.add_purchase') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Summary Stats --}}
    @php
        use App\Models\Purchase;
        $totalPurchases = Purchase::count();
        $completedPurchases = Purchase::where('status', 'Completed')->count();
        $draftPurchases = Purchase::where('status', 'Draft')->count();
        $cancelledPurchases = Purchase::where('status', 'Cancelled')->count();
        $totalAmount = Purchase::where('status', 'Completed')->sum('grand_total');
        $totalDue = Purchase::where('status', 'Completed')->sum('due_amount');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.th_total') }}</p>
                        <h4 class="fw-bold text-primary mb-0">{{ $totalPurchases }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-cart"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0">{{ __('messages.completed') }}</p>
                        <h4 class="fw-bold text-success mb-0">{{ $completedPurchases }}</h4>
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
                        <p class="text-muted small mb-0">Total Amount</p>
                        <h4 class="fw-bold text-info mb-0">{{ format_currency($totalAmount) }}</h4>
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
                        <h4 class="fw-bold {{ $totalDue > 0 ? 'text-danger' : 'text-success' }} mb-0">
                            {{ format_currency($totalDue) }}</h4>
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
                        class="bx bx-filter-alt text-primary me-2"></i>{{ __('messages.filter_purchases') }}</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('purchases.index') }}" id="filterForm" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.purchase_no_label') }}</label>
                            <input class="form-control form-control-sm" name="purchase_no" placeholder="PUR-YYYYMMDD-XXXXX"
                                type="text" value="{{ request('purchase_no') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.supplier') }}</label>
                            <select class="form-select form-select-sm" name="supplier_id">
                                <option value="">{{ __('messages.all_suppliers') }}</option>
                                @foreach ($suppliers as $s)
                                    <option {{ request('supplier_id') == $s->id ? 'selected' : '' }}
                                        value="{{ $s->id }}">{{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option {{ request('status') === 'Draft' ? 'selected' : '' }} value="Draft">
                                    {{ __('messages.draft') }}</option>
                                <option {{ request('status') === 'Completed' ? 'selected' : '' }} value="Completed">
                                    {{ __('messages.completed') }}</option>
                                <option {{ request('status') === 'Cancelled' ? 'selected' : '' }} value="Cancelled">
                                    {{ __('messages.cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                            <input class="form-control form-control-sm flatpickr-filter-date" name="start_date"
                                type="date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                            <input class="form-control form-control-sm flatpickr-filter-date" name="end_date" type="date"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a class="btn btn-outline-secondary" href="{{ route('purchases.index') }}"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button class="btn btn-primary" type="submit"><i
                                class="bx bx-search me-1"></i>{{ __('messages.apply_filters') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table-hover mb-0 table align-middle" id="purchasesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.th_purchase_no') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.th_supplier') }}</th>
                            <th>{{ __('messages.th_items') }}</th>
                            <th class="text-end">{{ __('messages.th_total') }}</th>
                            <th class="text-end">{{ __('messages.th_paid') }}</th>
                            <th class="text-end">{{ __('messages.th_due') }}</th>
                            <th class="text-center">Payment Status</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="no-sort text-center">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $index => $purchase)
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="{{ $purchase->id }}"></td>
                                <td class="text-muted fw-semibold">{{ $purchase->id }}</td>
                                <td><code class="fw-bold">{{ $purchase->purchase_no }}</code></td>
                                <td class="text-muted">{{ $purchase->purchase_date }}</td>
                                <td><strong>{{ $purchase->supplier->name ?? '-' }}</strong></td>
                                <td class="text-muted">{{ $purchase->items->count() }} {{ __('messages.items_count') }}
                                </td>
                                <td class="fw-bold text-end">{{ format_currency($purchase->grand_total) }}</td>
                                <td class="text-success fw-semibold text-end">
                                    {{ format_currency($purchase->paid_amount) }}
                                </td>
                                <td class="text-danger fw-semibold text-end">{{ format_currency($purchase->due_amount) }}
                                </td>
                                <td class="text-center">
                                    @if ($purchase->payment_status === 'Paid')
                                        <span class="badge rounded-pill bg-success">Paid</span>
                                    @elseif($purchase->payment_status === 'Partial')
                                        <span class="badge rounded-pill bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">Unpaid</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($purchase->status === 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                    @elseif($purchase->status === 'Draft')
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.draft') }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">{{ __('messages.cancelled') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('purchases.view')
                                            <a class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                href="{{ route('purchases.show', $purchase->id) }}"
                                                style="width:30px;height:30px;padding:0;" title="{{ __('messages.view') }}">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                href="{{ route('purchases.print', $purchase->id) }}"
                                                style="width:30px;height:30px;padding:0;" target="_blank"
                                                title="{{ __('messages.print') }}">
                                                <i class="bx bx-printer" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @if ($purchase->status === 'Completed')
                                            @if ($purchase->returns->count() > 0)
                                                @can('purchase_returns.update')
                                                    <a class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                        href="{{ route('purchase-returns.edit', $purchase->returns->first()->id) }}"
                                                        style="width:30px;height:30px;padding:0;" title="Edit Return">
                                                        <i class="bx bx-undo" style="font-size:1rem;"></i>
                                                    </a>
                                                @endcan
                                            @else
                                                @can('purchase_returns.create')
                                                    <a class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                        href="{{ route('purchase-returns.create', ['purchase_id' => $purchase->id]) }}"
                                                        style="width:30px;height:30px;padding:0;" title="Create Return">
                                                        <i class="bx bx-undo" style="font-size:1rem;"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                        @endif
                                        @can('purchases.update')
                                            <a class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action btn-payment-modal"
                                                href="#" style="width:30px;height:30px;padding:0;" title="Payment"
                                                data-id="{{ $purchase->id }}" data-no="{{ $purchase->purchase_no }}"
                                                data-grand-total="{{ $purchase->grand_total }}"
                                                data-paid-amount="{{ $purchase->paid_amount }}"
                                                data-due-amount="{{ $purchase->due_amount }}"
                                                data-payment-method="{{ $purchase->payment_method }}"
                                                data-action="{{ route('purchases.update-payment', $purchase->id) }}">
                                                <i class="bx bx-credit-card" style="font-size:1rem;"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                href="{{ route('purchases.edit', $purchase->id) }}"
                                                style="width:30px;height:30px;padding:0;" title="{{ __('messages.edit') }}">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('purchases.delete')
                                            <form action="{{ route('purchases.destroy', $purchase->id) }}" class="d-inline"
                                                id="delete-form-{{ $purchase->id }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $purchase->id }}" data-no="{{ $purchase->purchase_no }}"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="{{ __('messages.delete') }}" type="button">
                                                    <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Payment Management Modal --}}
    <div aria-hidden="true" aria-labelledby="paymentModalLabel" class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3 text-white">
                    <h5 class="modal-title fw-bold" id="paymentModalLabel"><i class="bx bx-credit-card me-2"></i>Manage
                        Payment</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold uppercase">Purchase No</label>
                            <input class="form-control bg-light fw-bold text-dark border-0" id="modal_purchase_no"
                                readonly type="text">
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
                                <div
                                    class="bg-danger border-danger rounded border border-opacity-25 bg-opacity-10 p-3 text-center">
                                    <div class="text-danger small-div fw-semibold mb-1">Balance Due</div>
                                    <div class="text-danger fw-bold fs-5" id="modal_balance_due_text">₹0.00</div>
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
            $('#purchasesTable').DataTable({
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
            let filtersOpen = localStorage.getItem('purchases_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('purchases_filters_open', isOpen);
            });
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    no = $(this).data('no'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `{{ __('messages.delete') }} "${no}"?`,
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
                                    showAdminToast(res.message, 'success');
                                    setTimeout(() => window.location.reload(), 1200);
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                let msg = '{{ __('messages.error_occurred') }}';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                showAdminToast(msg, 'error');
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
                    title: 'Delete ' + ids.length + ' purchase(s)?',
                    text: 'Stock will be reversed for Completed orders. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete all!',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route('purchases.bulk-destroy') }}',
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

            // ── Payment Modal Event Handler ──────────────────────────────
            $(document).on('click', '.btn-payment-modal', function(e) {
                e.preventDefault();
                const btn = $(this);
                const id = btn.data('id');
                const no = btn.data('no');
                const grandTotal = parseFloat(btn.data('grand-total')) || 0;
                const paidAmount = parseFloat(btn.data('paid-amount')) || 0;
                const paymentMethod = btn.data('payment-method') || 'Cash';
                const action = btn.data('action');

                $('#modal_purchase_no').val(no);
                $('#modal_grand_total').val(grandTotal);
                $('#modal_grand_total_text').text('₹' + grandTotal.toFixed(2));
                $('#modal_paid_amount').val(paidAmount.toFixed(2));
                $('#modal_payment_method').val(paymentMethod);
                $('#paymentForm').attr('action', action);

                updateModalDue();
                $('#paymentModal').modal('show');
            });

            $('#modal_paid_amount').on('input change', updateModalDue);

            function updateModalDue() {
                const total = parseFloat($('#modal_grand_total').val()) || 0;
                const paid = parseFloat($('#modal_paid_amount').val()) || 0;
                const due = Math.max(0, total - paid);
                $('#modal_balance_due_text').text('₹' + due.toFixed(2));
                if (due > 0) {
                    $('#modal_balance_due_text').parent().removeClass(
                        'bg-success bg-opacity-10 border-success border-opacity-25 text-success').addClass(
                        'bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger');
                    $('#modal_balance_due_text').parent().find('.small-div').removeClass('text-success').addClass(
                        'text-danger');
                } else {
                    $('#modal_balance_due_text').parent().removeClass(
                        'bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger').addClass(
                        'bg-success bg-opacity-10 border-success border-opacity-25 text-success');
                    $('#modal_balance_due_text').parent().find('.small-div').removeClass('text-danger').addClass(
                        'text-success');
                }
            }

            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = $('#btnSavePayment');
                const originalHtml = btn.html();

                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...'
                );

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
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'An error occurred while updating payment.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showAdminToast(msg, 'error');
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>
@endpush
