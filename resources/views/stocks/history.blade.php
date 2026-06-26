@extends('layouts.admin')
@section('title', __('messages.stock_history'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.stock_history') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('stocks.index') }}">{{ __('messages.stock_overview') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.stock_history') }}</li>
                </ol>
            </nav>
        </div>
        @can('stocks.create')
            <a href="{{ route('stocks.adjust') }}" class="btn btn-primary">
                <i class="bx bx-slider me-1"></i> {{ __('messages.adjust_stock') }}
            </a>
        @endcan
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm border-0" role="alert">
            <i class="bx bx-check-circle fs-4 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm border-0" role="alert">
            <i class="bx bx-error-circle fs-4 text-danger"></i>
            <div>{{ $errors->first('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold text-primary">
                <i class="bx bx-filter-alt me-2"></i>{{ __('messages.filters') }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('stocks.history') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.product') }}</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_products') }}</option>
                            @foreach ($allProducts as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Adjustment Direction</label>
                        <select name="adjustment_type" class="form-select form-select-sm">
                            <option value="">All Directions</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}"
                                    {{ request('adjustment_type') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                        <input type="date" name="start_date" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="end_date" class="form-control form-control-sm flatpickr-filter-date"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-x"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- History Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold text-primary">
                <i class="bx bx-history me-2"></i>Stock Adjustment Vouchers
            </h6>
            <span class="badge bg-label-primary">{{ $adjustmentsGrouped->count() }} vouchers</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="historyTable" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>Voucher No</th>
                            <th>Adjusted Products</th>
                            <th>{{ __('messages.th_created_by') }}</th>
                            <th>{{ __('messages.notes') }}</th>
                            <th class="text-center no-sort" style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustmentsGrouped as $voucherNo => $group)
                            @php
                                $firstAdj = $group->first();
                                $date = $firstAdj->transaction_date ? \Carbon\Carbon::parse($firstAdj->transaction_date) : $firstAdj->created_at;
                                $user = $firstAdj->user->name ?? 'System';
                                $notes = $firstAdj->notes;
                                $slug = \Str::slug($voucherNo);
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">{{ $loop->iteration }}</td>
                                <td class="text-muted small">
                                    {{ $date->format('d M Y') }}
                                </td>
                                <td>
                                    <code class="fw-bold fs-6 text-primary">{{ $voucherNo ?: '—' }}</code>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0" style="padding-left:0;">
                                        @foreach ($group as $adj)
                                            @php
                                                $isPositive = $adj->quantity_change >= 0;
                                                $qtySign = $isPositive ? '+' : '';
                                                $colorClass = $isPositive ? 'text-success' : 'text-danger';
                                            @endphp
                                            <li class="mb-1" style="font-size: 0.85rem;">
                                                <i class="bx bx-subdirectory-right text-muted me-1"></i>
                                                <strong>{{ $adj->product->name ?? 'Deleted Product' }}</strong> 
                                                (<code class="small text-muted">{{ $adj->product->code ?? '-' }}</code>) 
                                                &rarr; <span class="fw-bold {{ $colorClass }}">{{ $qtySign }}{{ number_format($adj->quantity_change, 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="fw-semibold small">{{ $user }}</td>
                                <td class="text-muted small" title="{{ $notes }}">
                                    {{ $notes ? \Str::limit($notes, 40) : '—' }}
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-outline-secondary rounded-circle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            title="More options" style="width:32px;height:32px;padding:0;">
                                            <i class="bx bx-dots-vertical-rounded" style="font-size:1.1rem;"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                            style="min-width:140px;border-radius:10px;">
                                            @can('stocks.create')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('stocks.edit_adjustment', $voucherNo) }}">
                                                        <i class="bx bx-edit text-primary" style="font-size:1rem;"></i>
                                                        <span>Edit Voucher</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider my-1">
                                                </li>
                                                <li>
                                                    <form id="delete-form-{{ $slug }}"
                                                        action="{{ route('stocks.destroy_adjustment', $voucherNo) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger delete-btn"
                                                            data-id="{{ $slug }}"
                                                            data-no="{{ $voucherNo }}">
                                                            <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                            <span>Delete Voucher</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bx bx-history" style="font-size:2.5rem;opacity:.3;"></i>
                                    <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }]
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    no = $(this).data('no'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: 'Delete Voucher?',
                    text: `Are you sure you want to delete and revert stock for voucher "${no}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel'
                }).then((r) => {
                    if (r.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
