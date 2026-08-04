@extends('layouts.admin')
@section('title', 'Purchase Return � ' . $purchaseReturn->return_no)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.purchase_return_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('purchase-returns.index') }}">{{ __('messages.menu_purchase_returns') }}</a></li>
                    <li class="breadcrumb-item active">{{ $purchaseReturn->return_no }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('purchase_returns.view')
                <a class="btn btn-outline-success" href="{{ route('purchase-returns.print', $purchaseReturn->id) }}"
                    target="_blank">
                    <i class="bx bx-printer me-1"></i> {{ __('messages.print_return') }}
                </a>
            @endcan
            @can('purchase_returns.update')
                <a class="btn btn-primary" href="{{ route('purchase-returns.edit', $purchaseReturn->id) }}">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit_return') }}
                </a>
            @endcan
            <a class="btn btn-outline-secondary" href="{{ route('purchase-returns.index') }}">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-cart-download text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ __('messages.purchase_return_badge') }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-hash me-1"></i>{{ $purchaseReturn->return_no }}</span>
                    <span>� {{ $purchaseReturn->supplier->name ?? '�' }}</span>
                    <span>� {{ $purchaseReturn->return_date }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $purchaseReturn->status === 'Completed' ? 'text-success' : 'text-warning' }}">
                    <i
                        class="bx {{ $purchaseReturn->status === 'Completed' ? 'bx-check' : 'bx-time' }} me-1"></i>{{ $purchaseReturn->status }}
                </span>
                <span
                    class="badge bg-white text-primary fw-semibold">{{ format_currency($purchaseReturn->grand_total) }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- -- Left Column ---------------------------------------- --}}
        <div class="col-lg-3 show-sidebar">

            {{-- Return Summary --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle text-primary me-2"></i>{{ __('messages.return_summary') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.return_no_label') }}</span>
                            <code class="fw-bold text-primary">{{ $purchaseReturn->return_no }}</code>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.return_date') }}</span>
                            <span class="fw-semibold">{{ $purchaseReturn->return_date }}</span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.purchase_order_label') }}</span>
                            @if ($purchaseReturn->purchase)
                                <a href="{{ route('purchases.show', $purchaseReturn->purchase_id) }}"
                                    class="text-muted text-decoration-none">
                                    <code class="fw-bold">{{ $purchaseReturn->purchase->purchase_no }}</code>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.supplier') }}</span>
                            <div class="text-end">
                                <span class="fw-bold d-block">{{ $purchaseReturn->supplier->name ?? '-' }}</span>
                                @if ($purchaseReturn->supplier?->phone)
                                    <small class="text-muted">{{ $purchaseReturn->supplier->phone }}</small>
                                @endif
                            </div>
                        </li>
                        @if ($purchaseReturn->reference_no)
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small fw-semibold">{{ __('messages.reference_no') }}</span>
                                <span class="fw-semibold">{{ $purchaseReturn->reference_no }}</span>
                            </li>
                        @endif
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.status') }}</span>
                            @if ($purchaseReturn->status === 'Completed')
                                <span class="badge bg-success rounded-pill">{{ __('messages.completed_label') }}</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill">{{ __('messages.pending') }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Refund Details --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-credit-card text-success me-2"></i>{{ __('messages.refund_details') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom bg-label-primary rounded px-2 py-2 mb-2">
                            <span class="fw-bold small">{{ __('messages.grand_total_label') }}</span>
                            <span class="fw-bold text-primary">{{ format_currency($purchaseReturn->grand_total) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.refunded_amount_meta') }}</span>
                            <span
                                class="fw-bold text-success">{{ format_currency($purchaseReturn->refunded_amount) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if ($purchaseReturn->notes)
                {{-- Notes --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note text-warning me-2"></i>{{ __('messages.notes') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-0" style="white-space:pre-line;">{{ $purchaseReturn->notes }}</p>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-bolt-circle text-warning me-2"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body d-grid gap-2 p-4">
                    @can('purchase_returns.update')
                        <a class="btn btn-primary" href="{{ route('purchase-returns.edit', $purchaseReturn->id) }}">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_return') }}
                        </a>
                    @endcan
                    @can('purchase_returns.view')
                        <a class="btn btn-outline-success" href="{{ route('purchase-returns.print', $purchaseReturn->id) }}"
                            target="_blank">
                            <i class="bx bx-printer me-1"></i> {{ __('messages.print_return') }}
                        </a>
                    @endcan
                    @can('purchase_returns.delete')
                        <form action="{{ route('purchase-returns.destroy', $purchaseReturn->id) }}" id="deleteForm"
                            method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-100 delete-btn"
                                data-no="{{ $purchaseReturn->return_no }}" type="button">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_return') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>

        {{-- -- Right Column --------------------------------------- --}}
        <div class="col-lg-9 show-main">

            {{-- Returned Items Table --}}
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-undo text-primary me-2"></i>{{ __('messages.returned_items') }}
                    </h6>
                    <span class="badge bg-label-primary">{{ $purchaseReturn->items->count() }} {{ __('messages.item_s') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.sku') }}</th>
                                    <th class="text-center">{{ __('messages.unit_col') }}</th>
                                    <th class="text-center">{{ __('messages.return_qty') }}</th>
                                    <th>{{ __('messages.reason') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseReturn->items as $index => $item)
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->product->name }}</strong>
                                            <small
                                                class="d-block text-muted">{{ $item->product->mainCategory->name ?? '' }}</small>
                                        </td>
                                        <td><code class="small text-primary">{{ $item->product->code }}</code></td>
                                        <td class="text-center text-muted small">{{ $item->product->unit_code ?? 'PCS' }}
                                        </td>
                                        <td class="text-center fw-bold">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="text-muted small fst-italic">{{ $item->reason ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Meta Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-user text-secondary me-2"></i>{{ __('messages.return_meta') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.th_created_by') }}</p>
                            <p class="fw-bold mb-0">{{ $purchaseReturn->user->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.created_at') }}</p>
                            <p class="fw-semibold mb-0">{{ $purchaseReturn->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.last_updated') }}</p>
                            <p class="fw-semibold mb-0">{{ $purchaseReturn->updated_at->format('d M Y, h:i A') }}</p>
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
                    if (r.isConfirmed) document.getElementById('deleteForm').submit();
                });
            });
        });
    </script>
@endpush




