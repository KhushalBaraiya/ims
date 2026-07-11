@extends('layouts.admin')
@section('title', 'Sales Return � ' . $saleReturn->return_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.sales_return_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Sale Returns</a></li>
                    <li class="breadcrumb-item active">{{ $saleReturn->return_no }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('sale_returns.view')
                <a href="{{ route('sale-returns.print', $saleReturn->id) }}" target="_blank" class="btn btn-outline-success">
                    <i class="bx bx-printer me-1"></i> Print
                </a>
            @endcan
            @can('sale_returns.update')
                <a href="{{ route('sale-returns.edit', $saleReturn->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit Return
                </a>
            @endcan
            <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-undo text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ __('messages.sale_return_badge') }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-hash me-1"></i>{{ $saleReturn->return_no }}</span>
                    <span>� {{ $saleReturn->customer->name ?? '�' }}</span>
                    <span>� {{ $saleReturn->return_date }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $saleReturn->status === 'Completed' ? 'text-success' : 'text-warning' }}">
                    <i
                        class="bx {{ $saleReturn->status === 'Completed' ? 'bx-check' : 'bx-time' }} me-1"></i>{{ $saleReturn->status }}
                </span>
                <span
                    class="badge bg-white text-primary fw-semibold">{{ format_currency($saleReturn->grand_total) }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- -- Left Column --------------------------------------- --}}
        <div class="col-lg-3 show-sidebar">

            {{-- Return Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle me-2 text-primary"></i>Return Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.return_no_label') }}</span>
                            <code class="fw-bold text-primary">{{ $saleReturn->return_no }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.return_date') }}</span>
                            <span class="fw-semibold">{{ $saleReturn->return_date }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.original_invoice') }}</span>
                            @if ($saleReturn->sale)
                                <a href="{{ route('sales.show', $saleReturn->sale_id) }}" class="fw-bold text-primary">
                                    <code>{{ $saleReturn->sale->invoice_no }}</code>
                                </a>
                            @else
                                <span class="text-muted">�</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.customer') }}</span>
                            <div class="text-end">
                                <span class="fw-bold d-block">{{ $saleReturn->customer->name }}</span>
                                @if ($saleReturn->customer->phone)
                                    <small class="text-muted">{{ $saleReturn->customer->phone }}</small>
                                @endif
                            </div>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.reference_no') }}</span>
                            <span class="small">{{ $saleReturn->reference_no ?: '�' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.processed_by') }}</span>
                            <span class="fw-semibold">{{ $saleReturn->user->name ?? '�' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.status') }}</span>
                            @if ($saleReturn->status === 'Completed')
                                <span class="badge bg-success rounded-pill">{{ __('messages.completed_label') }}</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill">{{ __('messages.pending') }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Refund Summary --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-credit-card me-2 text-success"></i>Refund Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.return_value') }}</span>
                            <span class="fw-semibold">{{ format_currency($saleReturn->sub_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.tax_adjusted') }}</span>
                            <span class="fw-semibold">{{ format_currency($saleReturn->tax_amount) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom bg-label-primary rounded px-2">
                            <span class="fw-bold small">{{ __('messages.grand_refund_total') }}</span>
                            <span class="fw-bold text-primary">{{ format_currency($saleReturn->grand_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.refunded_to_customer') }}</span>
                            <span class="fw-bold text-success">{{ format_currency($saleReturn->refunded_amount) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if ($saleReturn->notes)
                {{-- Notes --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note me-2 text-warning"></i>Notes
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0 small text-muted" style="white-space:pre-line;">{{ $saleReturn->notes }}</p>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('sale_returns.update')
                        <a href="{{ route('sale-returns.edit', $saleReturn->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> Edit Return
                        </a>
                    @endcan
                    @can('sale_returns.view')
                        <a href="{{ route('sale-returns.print', $saleReturn->id) }}" target="_blank"
                            class="btn btn-outline-success">
                            <i class="bx bx-printer me-1"></i> Print Return Sheet
                        </a>
                    @endcan
                    @if ($saleReturn->sale)
                        <a href="{{ route('sales.show', $saleReturn->sale_id) }}" class="btn btn-outline-info">
                            <i class="bx bx-receipt me-1"></i> View Original Invoice
                        </a>
                    @endif
                    @can('sale_returns.delete')
                        <form id="deleteForm" action="{{ route('sale-returns.destroy', $saleReturn->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-return="{{ $saleReturn->return_no }}">
                                <i class="bx bx-trash me-1"></i> Delete Return
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>

        {{-- -- Right Column --------------------------------------- --}}
        <div class="col-lg-9 show-main">

            {{-- Returned Items --}}
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-undo me-2 text-primary"></i>Returned Items
                    </h6>
                    <span class="badge bg-label-primary">{{ $saleReturn->items->count() }} item(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.sku') }}</th>
                                    <th class="text-end">{{ __('messages.unit_price') }}</th>
                                    <th class="text-center">{{ __('messages.return_qty') }}</th>
                                    <th>{{ __('messages.reason') }}</th>
                                    <th class="text-end">{{ __('messages.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saleReturn->items as $index => $item)
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($item->product->image)
                                                    <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                        class="tbl-img rounded" onerror="imgError(this)">
                                                @else
                                                    <div
                                                        class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
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
                                        <td class="text-end fw-semibold">{{ format_currency($item->unit_price) }}</td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ number_format($item->quantity, 0) }}
                                            <small
                                                class="text-muted fw-normal">{{ $item->product->unit_code ?? 'PCS' }}</small>
                                        </td>
                                        <td class="text-muted fst-italic small">
                                            {{ $item->reason ?: '�' }}
                                        </td>
                                        <td class="text-end fw-bold">{{ format_currency($item->total_amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">{{ __('messages.grand_refund_total') }}</td>
                                    <td class="text-end fw-bold text-primary fs-6">
                                        {{ format_currency($saleReturn->grand_total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-user me-2 text-secondary"></i>Return Meta
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.processed_by') }}</p>
                            <p class="fw-bold mb-0">{{ $saleReturn->user->name ?? '�' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.created_at') }}</p>
                            <p class="fw-semibold mb-0">{{ $saleReturn->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.last_updated') }}</p>
                            <p class="fw-semibold mb-0">{{ $saleReturn->updated_at->format('d M Y, h:i A') }}</p>
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
            const returnNo = $(this).data('return');
            Swal.fire({
                title: '{{ __('messages.confirm_delete') }}',
                text: `{{ __('messages.delete') }} "${returnNo}"?`,
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




