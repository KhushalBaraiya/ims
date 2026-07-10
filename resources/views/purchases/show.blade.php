@extends('layouts.admin')
@section('title', 'Purchase Order — ' . $purchase->purchase_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Purchase Order Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('purchases.index') }}">{{ __('messages.purchase_orders') }}</a></li>
                    <li class="breadcrumb-item active">{{ $purchase->purchase_no }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('purchases.view')
                <a class="btn btn-outline-success" href="{{ route('purchases.print', $purchase->id) }}" target="_blank">
                    <i class="bx bx-printer me-1"></i> Print
                </a>
            @endcan
            @can('purchases.update')
                <a class="btn btn-primary" href="{{ route('purchases.edit', $purchase->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit Order
                </a>
            @endcan
            <a class="btn btn-outline-secondary" href="{{ route('purchases.index') }}">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-cart text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">Purchase Order</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-hash me-1"></i>{{ $purchase->purchase_no }}</span>
                    <span>· {{ $purchase->supplier->name ?? '—' }}</span>
                    <span>· {{ $purchase->purchase_date }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($purchase->status === 'received')
                    <span class="badge bg-success fw-semibold">Received</span>
                @elseif($purchase->status === 'pending')
                    <span class="badge bg-warning text-dark fw-semibold">Pending</span>
                @elseif($purchase->status === 'ordered')
                    <span class="badge bg-white text-primary fw-semibold">Ordered</span>
                @else
                    <span class="badge bg-white text-secondary fw-semibold">{{ ucfirst($purchase->status) }}</span>
                @endif
                @if ($purchase->payment_status === 'Paid')
                    <span class="badge bg-success fw-semibold">Paid</span>
                @elseif($purchase->payment_status === 'Partial')
                    <span class="badge bg-warning text-dark fw-semibold">Partial</span>
                @else
                    <span class="badge bg-danger fw-semibold">Unpaid</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- -- Left Column --------------------------------------- --}}
        <div class="col-lg-3 show-sidebar">

            {{-- Order Summary --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle text-primary me-2"></i>Order Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Purchase No</span>
                            <code class="fw-bold text-primary">{{ $purchase->purchase_no }}</code>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Date</span>
                            <span class="fw-semibold">{{ $purchase->purchase_date }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Supplier</span>
                            <span class="fw-bold text-end">{{ $purchase->supplier->name ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Reference No</span>
                            <span class="small">{{ $purchase->reference_no ?: '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Payment Method</span>
                            <span class="small fw-semibold">{{ $purchase->payment_method ?: '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Status</span>
                            @if ($purchase->status === 'received')
                                <span class="badge bg-success rounded-pill">Received</span>
                            @elseif ($purchase->status === 'pending')
                                <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                            @elseif ($purchase->status === 'ordered')
                                <span class="badge bg-primary rounded-pill">Ordered</span>
                            @elseif ($purchase->status === 'draft')
                                <span class="badge bg-secondary text-dark rounded-pill">Draft</span>
                            @else
                                <span class="badge bg-danger rounded-pill">{{ $purchase->status }}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">Payment Status</span>
                            @if ($purchase->payment_status === 'Paid')
                                <span class="badge bg-success rounded-pill">Paid</span>
                            @elseif ($purchase->payment_status === 'Partial')
                                <span class="badge bg-warning text-dark rounded-pill">Partial</span>
                            @else
                                <span class="badge bg-danger rounded-pill">Unpaid</span>
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
                            <span class="text-muted small fw-semibold">Subtotal</span>
                            <span class="fw-semibold">{{ format_currency($purchase->sub_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Discount (-)</span>
                            <span class="fw-semibold text-danger">-
                                {{ format_currency($purchase->discount_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Tax (+)</span>
                            <span class="fw-semibold text-warning">+ {{ format_currency($purchase->tax_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Shipping (+)</span>
                            <span class="fw-semibold">+ {{ format_currency($purchase->shipping_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom bg-label-primary rounded px-2 py-2">
                            <span class="fw-bold small">Grand Total</span>
                            <span class="fw-bold text-primary">{{ format_currency($purchase->grand_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">Paid Amount</span>
                            <span class="fw-bold text-success">{{ format_currency($purchase->paid_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">Balance Due</span>
                            <span class="fw-bold {{ $purchase->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                {{ format_currency($purchase->due_amount) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            @if ($purchase->notes)
                {{-- Notes --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note text-warning me-2"></i>Notes
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-0" style="white-space:pre-line;">{{ $purchase->notes }}</p>
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
                    @can('purchases.update')
                        <a class="btn btn-primary" href="{{ route('purchases.edit', $purchase->id) }}">
                            <i class="bx bx-edit me-1"></i> Edit Order
                        </a>
                    @endcan
                    @can('purchases.view')
                        <a class="btn btn-outline-success" href="{{ route('purchases.print', $purchase->id) }}"
                            target="_blank">
                            <i class="bx bx-printer me-1"></i> Print Order
                        </a>
                    @endcan
                    @if ($purchase->status === 'received')
                        @if ($purchase->returns->count() > 0)
                            @can('purchase_returns.view')
                                <a class="btn btn-outline-warning"
                                    href="{{ route('purchase-returns.show', $purchase->returns->first()->id) }}">
                                    <i class="bx bx-undo me-1"></i> View Return
                                </a>
                            @endcan
                        @else
                            @can('purchase_returns.create')
                                <a class="btn btn-outline-warning"
                                    href="{{ route('purchase-returns.create', ['purchase_id' => $purchase->id]) }}">
                                    <i class="bx bx-undo me-1"></i> Create Return
                                </a>
                            @endcan
                        @endif
                    @endif
                    @can('purchases.delete')
                        <form action="{{ route('purchases.destroy', $purchase->id) }}" id="deleteForm" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-100 delete-btn" data-no="{{ $purchase->purchase_no }}"
                                type="button">
                                <i class="bx bx-trash me-1"></i> Delete Order
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>

        {{-- -- Right Column --------------------------------------- --}}
        <div class="col-lg-9 show-main">

            {{-- Purchased Items --}}
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-package text-primary me-2"></i>Purchased Items
                    </h6>
                    <span class="badge bg-label-primary">{{ $purchase->items->count() }} product(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Tax</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->items as $index => $item)
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
                                                        <i class="bx bx-package text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <small
                                                        class="d-block text-muted">{{ $item->product->unit_code ?? 'PCS' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code class="small text-primary">{{ $item->product->code }}</code></td>
                                        <td class="fw-bold text-center">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="fw-semibold text-end">{{ format_currency($item->purchase_price) }}</td>
                                        <td class="text-danger text-end">- {{ format_currency($item->discount_amount) }}
                                        </td>
                                        <td class="text-warning text-end">+ {{ format_currency($item->tax_amount) }}</td>
                                        <td class="fw-bold text-end">{{ format_currency($item->total_amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="fw-bold text-end" colspan="7">Grand Total</td>
                                    <td class="fw-bold text-primary fs-6 text-end">
                                        {{ format_currency($purchase->grand_total) }}</td>
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
                        <i class="bx bx-user text-secondary me-2"></i>Order Meta
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Created By</p>
                            <p class="fw-bold mb-0">{{ $purchase->user->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Created At</p>
                            <p class="fw-semibold mb-0">{{ $purchase->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Last Updated</p>
                            <p class="fw-semibold mb-0">{{ $purchase->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                const no = $(this).data('no');
                const form = $('#deleteForm');
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
                                    setTimeout(() => window.location.href =
                                        "{{ route('purchases.index') }}", 1200);
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
        });
    </script>
@endpush
