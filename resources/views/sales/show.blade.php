@extends('layouts.admin')
@section('title', 'Sales Invoice � ' . $sale->invoice_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.sales_invoice_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">{{ __('messages.menu_sales') }}</a></li>
                    <li class="breadcrumb-item active">{{ $sale->invoice_no }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('sales.view')
                <a class="btn btn-outline-success" href="{{ route('sales.print', $sale->id) }}" target="_blank">
                    <i class="bx bx-printer me-1"></i> Print
                </a>
            @endcan
            @can('sales.update')
                <a class="btn btn-primary" href="{{ route('sales.edit', $sale->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit Invoice
                </a>
            @endcan
            <a class="btn btn-outline-secondary" href="{{ route('sales.index') }}">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>
    10*12
    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
./.             <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-receipt text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ __('messages.sales_invoice_badge') }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-hash me-1"></i>{{ $sale->invoice_no }}</span>
                    <span>� {{ $sale->customer->name ?? '�' }}</span>
                    <span>� {{ $sale->invoice_date }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($sale->status === 'Completed')
                    <span class="badge bg-success fw-semibold">{{ __('messages.completed_label') }}</span>
                @elseif($sale->status === 'Pending')
                    <span class="badge bg-white text-warning fw-semibold">{{ __('messages.pending') }}</span>
                @elseif($sale->status === 'Draft')
                    <span class="badge bg-white text-secondary fw-semibold">{{ __('messages.draft') }}</span>
                @else
                    <span class="badge bg-white text-secondary fw-semibold">{{ $sale->status }}</span>
                @endif
                @if ($sale->payment_status === 'Paid')
                    <span class="badge bg-success fw-semibold">{{ __('messages.paid') }}</span>
                @elseif($sale->payment_status === 'Partial')
                    <span class="badge bg-warning text-dark fw-semibold">{{ __('messages.partial') }}</span>
                @else
                    <span class="badge bg-danger fw-semibold">{{ __('messages.unpaid') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- -- Left Column --------------------------------------- --}}
        <div class="col-lg-3 show-sidebar">

            {{-- Invoice Summary --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle text-primary me-2"></i>Invoice Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.invoice_no_short') }}</span>
                            <code class="fw-bold text-primary">{{ $sale->invoice_no }}</code>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.date_label') }}</span>
                            <span class="fw-semibold">{{ $sale->invoice_date }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.customer') }}</span>
                            <div class="text-end">
                                <span class="fw-bold d-block">{{ $sale->customer->name }}</span>
                                @if ($sale->customer->phone)
                                    <small class="text-muted">{{ $sale->customer->phone }}</small>
                                @endif
                            </div>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.sales_person') }}</span>
                            <span class="fw-semibold">{{ $sale->salesPerson->name ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.payment_method_meta') }}</span>
                            <span class="small fw-semibold">{{ $sale->payment_method ?: '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.status') }}</span>
                            @if ($sale->status === 'Completed')
                                <span class="badge bg-success rounded-pill">{{ __('messages.completed_label') }}</span>
                            @elseif ($sale->status === 'Pending')
                                <span class="badge bg-info text-dark rounded-pill">{{ __('messages.pending') }}</span>
                            @elseif ($sale->status === 'Draft')
                                <span class="badge bg-warning text-dark rounded-pill">{{ __('messages.draft') }}</span>
                            @elseif ($sale->status === 'Repair')
                                <span class="badge bg-secondary rounded-pill">{{ __('messages.repair_badge') }}</span>
                            @elseif ($sale->status === 'Ordered')
                                <span class="badge bg-primary rounded-pill">{{ __('messages.ordered_badge') }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">{{ $sale->status }}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.payment_status') }}</span>
                            @if ($sale->payment_status === 'Paid')
                                <span class="badge bg-success rounded-pill">{{ __('messages.paid') }}</span>
                            @elseif ($sale->payment_status === 'Partial')
                                <span class="badge bg-warning text-dark rounded-pill">{{ __('messages.partial') }}</span>
                            @else
                                <span class="badge bg-danger rounded-pill">{{ __('messages.unpaid') }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-credit-card text-success me-2"></i>Payment Details
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.subtotal') }}</span>
                            <span class="fw-semibold">{{ format_currency($sale->sub_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.discount_label') }}</span>
                            <span class="fw-semibold text-danger">- {{ format_currency($sale->discount_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.tax_plus') }}</span>
                            <span class="fw-semibold text-warning">+ {{ format_currency($sale->tax_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.shipping_plus') }}</span>
                            <span class="fw-semibold">+ {{ format_currency($sale->shipping_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom bg-label-primary rounded px-2 py-2">
                            <span class="fw-bold small">{{ __('messages.grand_total_label') }}</span>
                            <span class="fw-bold text-primary">{{ format_currency($sale->grand_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.paid_amount_meta') }}</span>
                            <span class="fw-bold text-success">{{ format_currency($sale->paid_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.balance_due_label') }}</span>
                            <span class="fw-bold {{ $sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                {{ format_currency($sale->due_amount) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            @if ($sale->notes)
                {{-- Notes --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note text-warning me-2"></i>Notes
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-0" style="white-space:pre-line;">{{ $sale->notes }}</p>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-bolt-circle text-warning me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body d-grid gap-2 p-4">
                    @can('sales.update')
                        <a class="btn btn-primary" href="{{ route('sales.edit', $sale->id) }}">
                            <i class="bx bx-edit me-1"></i> Edit Invoice
                        </a>
                        <button class="btn btn-outline-success btn-payment-modal"
                            data-action="{{ route('sales.update-payment', $sale->id) }}"
                            data-due-amount="{{ $sale->due_amount }}" data-grand-total="{{ $sale->grand_total }}"
                            data-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_no }}"
                            data-paid-amount="{{ $sale->paid_amount }}" data-payment-method="{{ $sale->payment_method }}"
                            type="button">
                            <i class="bx bx-credit-card me-1"></i> Update Payment
                        </button>
                    @endcan
                    @can('sales.view')
                        <a class="btn btn-outline-success" href="{{ route('sales.print', $sale->id) }}" target="_blank">
                            <i class="bx bx-printer me-1"></i> Print Invoice
                        </a>
                    @endcan
                    @if ($sale->status === 'Completed')
                        @if ($sale->returns && $sale->returns->count() > 0)
                            @can('sale_returns.view')
                                <a class="btn btn-outline-warning"
                                    href="{{ route('sale-returns.show', $sale->returns->first()->id) }}">
                                    <i class="bx bx-undo me-1"></i> View Return
                                </a>
                            @endcan
                        @else
                            @can('sale_returns.create')
                                <a class="btn btn-outline-warning"
                                    href="{{ route('sale-returns.create', ['sale_id' => $sale->id]) }}">
                                    <i class="bx bx-undo me-1"></i> Create Return
                                </a>
                            @endcan
                        @endif
                    @endif
                    @can('sales.delete')
                        <form action="{{ route('sales.destroy', $sale->id) }}" id="deleteForm" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-100 delete-btn" data-invoice="{{ $sale->invoice_no }}"
                                type="button">
                                <i class="bx bx-trash me-1"></i> Delete Invoice
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>

        {{-- -- Right Column --------------------------------------- --}}
        <div class="col-lg-9 show-main">

            {{-- Invoice Items --}}
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-receipt text-primary me-2"></i>Invoice Items
                    </h6>
                    <span class="badge bg-label-primary">{{ $sale->items->count() }} product(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.sku') }}</th>
                                    <th class="text-center">{{ __('messages.qty') }}</th>
                                    <th class="text-end">{{ __('messages.unit_price') }}</th>
                                    <th class="text-end">{{ __('messages.discount') }}</th>
                                    <th class="text-end">{{ __('messages.tax_label') }}</th>
                                    <th class="text-end">{{ __('messages.th_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->items as $index => $item)
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($item->product->image)
                                                    <img class="tbl-img rounded" onerror="imgError(this)"
                                                        src="{{ asset('uploads/products/' . $item->product->image) }}">
                                                @else
                                                    <div
                                                        class="tbl-img img-fallback d-flex align-items-center justify-content-center bg-light rounded">
                                                        <i class="bx bx-receipt text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <small class="d-block text-muted">
                                                        {{ $item->product->unit_code ?? 'PCS' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code class="small text-primary">{{ $item->product->code }}</code></td>
                                        <td class="fw-bold text-center">
                                            {{ number_format($item->quantity, 2) }}
                                        </td>
                                        <td class="fw-semibold text-end">{{ format_currency($item->unit_price) }}</td>
                                        <td class="text-danger text-end">- {{ format_currency($item->discount_amount) }}
                                        </td>
                                        <td class="text-warning text-end">+ {{ format_currency($item->tax_amount) }}</td>
                                        <td class="fw-bold text-end">{{ format_currency($item->total_amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="fw-bold text-end" colspan="7">{{ __('messages.grand_total_label') }}
                                    </td>
                                    <td class="fw-bold text-primary fs-6 text-end">
                                        {{ format_currency($sale->grand_total) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Created By / Meta --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-user text-secondary me-2"></i>Invoice Meta
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.th_created_by') }}</p>
                            <p class="fw-bold mb-0">{{ $sale->user->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.created_at') }}</p>
                            <p class="fw-semibold mb-0">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.last_updated') }}</p>
                            <p class="fw-semibold mb-0">{{ $sale->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- -- Payment Management Modal --------------------------------------- --}}
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
                            <label
                                class="form-label text-muted small fw-bold">{{ __('messages.invoice_no_short') }}</label>
                            <input class="form-control bg-light fw-bold text-dark border-0" id="modal_invoice_no" readonly
                                type="text">
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-light rounded border p-3 text-center">
                                    <div class="text-muted small fw-semibold mb-1">{{ __('messages.grand_total_label') }}
                                    </div>
                                    <div class="fw-bold text-primary fs-5" id="modal_grand_total_text">
                                        {{ optional(current_currency())->symbol ?? '?' }}0.00</div>
                                    <input id="modal_grand_total" type="hidden">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-danger border-danger rounded border border-opacity-25 bg-opacity-10 p-3 text-center"
                                    id="modal_due_box">
                                    <div class="small fw-semibold text-danger mb-1" id="modal_due_label">
                                        {{ __('messages.balance_due_label') }}</div>
                                    <div class="fw-bold fs-5 text-danger" id="modal_balance_due_text">
                                        {{ optional(current_currency())->symbol ?? '?' }}0.00</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('messages.paid_amount_field') }} <span
                                    class="text-danger">*</span></label>
                            <input class="form-control form-control-lg fw-bold" id="modal_paid_amount" min="0"
                                name="paid_amount" required step="0.01" type="number">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="modal_payment_method" name="payment_method"
                                data-no-select2="1" required>
                                <option value="Cash">{{ __('messages.pm_cash') }}</option>
                                <option value="Bank Transfer">{{ __('messages.pm_bank') }}</option>
                                <option value="Card">{{ __('messages.pm_card') }}</option>
                                <option value="UPI / QR">{{ __('messages.pm_upi') }}</option>
                                <option value="Cheque">{{ __('messages.pm_cheque') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top p-3">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">
                            <i class="bx bx-x me-1"></i> Close
                        </button>
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
            const sym = '{{ addslashes(optional(current_currency())->symbol ?? '?') }}';
            // Delete confirm
            $(document).on('click', '.delete-btn', function() {
                const invoice = $(this).data('invoice');
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
                    if (r.isConfirmed) document.getElementById('deleteForm').submit();
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
                $('#paymentForm').attr('action', btn.data('action'));

                updateModalDue();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('paymentModal')).show();
            });

            $('#modal_paid_amount').on('input change', updateModalDue);

            function updateModalDue() {
                const total = parseFloat($('#modal_grand_total').val()) || 0;
                const paid = parseFloat($('#modal_paid_amount').val()) || 0;
                const due = Math.max(0, total - paid);
                $('#modal_balance_due_text').text(sym + due.toFixed(2));
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
                        const msg = xhr.responseJSON?.message ?? 'An error occurred.';
                        showAdminToast(msg, 'error');
                        btn.prop('disabled', false).html(orig);
                    }
                });
            });
        });
    </script>
@endpush
