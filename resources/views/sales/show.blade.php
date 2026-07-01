@extends('layouts.admin')
@section('title', 'Sales Invoice — ' . $sale->invoice_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sales Invoice Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">{{ __('messages.menu_sales') }}</a></li>
                    <li class="breadcrumb-item active">{{ $sale->invoice_no }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('sales.view')
                <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-outline-success">
                    <i class="bx bx-printer me-1"></i> Print
                </a>
            @endcan
            @can('sales.update')
                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit Invoice
                </a>
            @endcan
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Left Column ─────────────────────────────────────── --}}
        <div class="col-lg-3">

            {{-- Invoice Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle me-2 text-primary"></i>Invoice Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Invoice No</span>
                            <code class="fw-bold text-primary">{{ $sale->invoice_no }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Date</span>
                            <span class="fw-semibold">{{ $sale->invoice_date }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Customer</span>
                            <div class="text-end">
                                <span class="fw-bold d-block">{{ $sale->customer->name }}</span>
                                @if ($sale->customer->phone)
                                    <small class="text-muted">{{ $sale->customer->phone }}</small>
                                @endif
                            </div>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Sales Person</span>
                            <span class="fw-semibold">{{ $sale->salesPerson->name ?? '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Reference No</span>
                            <span class="small">{{ $sale->reference_no ?: '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Payment Method</span>
                            <span class="small fw-semibold">{{ $sale->payment_method ?: '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">Status</span>
                            @if ($sale->status === 'Completed')
                                <span class="badge bg-success rounded-pill">Completed</span>
                            @elseif ($sale->status === 'Draft')
                                <span class="badge bg-warning text-dark rounded-pill">Draft</span>
                            @else
                                <span class="badge bg-danger rounded-pill">Cancelled</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-credit-card me-2 text-success"></i>Payment Details
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Subtotal</span>
                            <span class="fw-semibold">{{ format_currency($sale->sub_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Discount (-)</span>
                            <span class="fw-semibold text-danger">- {{ format_currency($sale->discount_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Tax (+)</span>
                            <span class="fw-semibold text-warning">+ {{ format_currency($sale->tax_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Shipping (+)</span>
                            <span class="fw-semibold">+ {{ format_currency($sale->shipping_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom bg-label-primary rounded px-2">
                            <span class="fw-bold small">Grand Total</span>
                            <span class="fw-bold text-primary">{{ format_currency($sale->grand_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">Paid Amount</span>
                            <span class="fw-bold text-success">{{ format_currency($sale->paid_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">Balance Due</span>
                            <span class="fw-bold {{ $sale->due_amount > 0 ? 'text-danger' : 'text-success' }}">
                                {{ format_currency($sale->due_amount) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            @if ($sale->notes)
                {{-- Notes --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note me-2 text-warning"></i>Notes
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0 small text-muted" style="white-space:pre-line;">{{ $sale->notes }}</p>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('sales.update')
                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> Edit Invoice
                        </a>
                    @endcan
                    @can('sales.view')
                        <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-outline-success">
                            <i class="bx bx-printer me-1"></i> Print Invoice
                        </a>
                    @endcan
                    @if ($sale->status === 'Completed')
                        @if ($sale->returns && $sale->returns->count() > 0)
                            @can('sale_returns.view')
                                <a href="{{ route('sale-returns.show', $sale->returns->first()->id) }}"
                                    class="btn btn-outline-warning">
                                    <i class="bx bx-undo me-1"></i> View Return
                                </a>
                            @endcan
                        @else
                            @can('sale_returns.create')
                                <a href="{{ route('sale-returns.create', ['sale_id' => $sale->id]) }}"
                                    class="btn btn-outline-warning">
                                    <i class="bx bx-undo me-1"></i> Create Return
                                </a>
                            @endcan
                        @endif
                    @endif
                    @can('sales.delete')
                        <form id="deleteForm" action="{{ route('sales.destroy', $sale->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-invoice="{{ $sale->invoice_no }}">
                                <i class="bx bx-trash me-1"></i> Delete Invoice
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>

        {{-- ── Right Column ─────────────────────────────────────── --}}
        <div class="col-lg-9">

            {{-- Invoice Items --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-receipt me-2 text-primary"></i>Invoice Items
                    </h6>
                    <span class="badge bg-label-primary">{{ $sale->items->count() }} product(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                @foreach ($sale->items as $index => $item)
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($item->product->image)
                                                    <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                        class="tbl-img rounded" onerror="imgError(this)">
                                                @else
                                                    <div
                                                        class="tbl-img img-fallback d-flex align-items-center justify-content-center bg-light rounded">
                                                        <i class="bx bx-receipt text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <small class="d-block text-muted">
                                                        {{ $item->product->unit->short_name ?? 'PCS' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code class="small text-primary">{{ $item->product->code }}</code></td>
                                        <td class="text-center fw-bold">
                                            {{ number_format($item->quantity, 2) }}
                                        </td>
                                        <td class="text-end fw-semibold">{{ format_currency($item->unit_price) }}</td>
                                        <td class="text-end text-danger">- {{ format_currency($item->discount_amount) }}
                                        </td>
                                        <td class="text-end text-warning">+ {{ format_currency($item->tax_amount) }}</td>
                                        <td class="text-end fw-bold">{{ format_currency($item->total_amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="7" class="text-end fw-bold">Grand Total</td>
                                    <td class="text-end fw-bold text-primary fs-6">
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
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-user me-2 text-secondary"></i>Invoice Meta
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Created By</p>
                            <p class="fw-bold mb-0">{{ $sale->user->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Created At</p>
                            <p class="fw-semibold mb-0">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">Last Updated</p>
                            <p class="fw-semibold mb-0">{{ $sale->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
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
    </script>
@endpush
