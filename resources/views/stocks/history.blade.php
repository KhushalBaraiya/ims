@extends('layouts.admin')
@section('title', __('messages.stock_adjustments'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.stock_adjustments') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.stock_adjustments') }}</li>
                </ol>
            </nav>
        </div>
        @can('stocks.create')
            <a href="{{ route('stocks.adjust') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.create_adjustment') }}
            </a>
        @endcan
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm border-0"
            role="alert">
            <i class="bx bx-error-circle fs-4 text-danger"></i>
            <div>{{ $errors->first('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-slider fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.total_vouchers') }}</div>
                        <div class="fw-bold fs-4">{{ $adjustmentsGrouped->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-error-circle fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.low_stock_badge') }}</div>
                        <div class="fw-bold fs-4 text-warning">{{ $lowStock }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-danger flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-x-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.out_of_stock') }}</div>
                        <div class="fw-bold fs-4 text-danger">{{ $outOfStock }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-success flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-package fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">{{ __('messages.total_products') }}</div>
                        <div class="fw-bold fs-4 text-success">{{ $totalProducts }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <label class="form-label fw-semibold small">{{ __('messages.adj_directions') }}</label>
                        <select name="adjustment_type" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_directions') }}</option>
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
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Adjustments Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold text-primary">
                <i class="bx bx-slider me-2"></i>{{ __('messages.adjustment_vouchers') }}
            </h6>
            <span class="badge bg-label-primary">{{ $adjustmentsGrouped->count() }} {{ __('messages.vouchers') }}</span>
        </div>
        <div class="card-body p-0">

            @if ($adjustmentsGrouped->isEmpty())
                {{-- Empty state —  no DataTable, no column-count warning --}}
                <div style="width:100%;text-align:center;padding:2.5rem 0;">
                    <div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                        <i class="bx bx-slider" style="font-size:3rem;opacity:.3;line-height:1;"></i>
                        {{ __('messages.no_records') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="historyTable" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>{{ __('messages.th_date') }}</th>
                                <th>{{ __('messages.voucher_no') }}</th>
                                <th>{{ __('messages.adjusted_products') }}</th>
                                <th>{{ __('messages.th_created_by') }}</th>
                                <th>{{ __('messages.notes') }}</th>
                                <th class="text-center no-sort" style="width:90px;">{{ __('messages.th_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adjustmentsGrouped as $voucherNo => $group)
                                @php
                                    $firstAdj = $group->first();
                                    $date = $firstAdj->transaction_date
                                        ? \Carbon\Carbon::parse($firstAdj->transaction_date)
                                        : $firstAdj->created_at;
                                    $user = $firstAdj->user->name ?? 'System';
                                    $notes = $firstAdj->notes;
                                    $slug = \Str::slug($voucherNo);
                                @endphp
                                <tr>
                                    <td class="text-muted fw-semibold">{{ $loop->iteration }}</td>
                                    <td class="text-muted small">{{ $date->format('d M Y') }}</td>
                                    <td>
                                        <code class="fw-bold text-primary">{{ $voucherNo ?: '—' }}</code>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0" style="padding-left:0;">
                                            @foreach ($group as $adj)
                                                @php
                                                    $isPositive = $adj->quantity_change >= 0;
                                                    $qtySign = $isPositive ? '+' : '';
                                                    $colorClass = $isPositive ? 'text-success' : 'text-danger';
                                                @endphp
                                                <li class="mb-1" style="font-size:0.85rem;">
                                                    <i class="bx bx-subdirectory-right text-muted me-1"></i>
                                                    <strong>{{ $adj->product->name ?? 'Deleted Product' }}</strong>
                                                    (<code
                                                        class="small text-muted">{{ $adj->product->code ?? '-' }}</code>)
                                                    &rarr;
                                                    <span class="fw-bold {{ $colorClass }}">
                                                        {{ $qtySign }}{{ number_format($adj->quantity_change, 2) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="fw-semibold small">{{ $user }}</td>
                                    <td class="text-muted small" title="{{ $notes }}">
                                        {{ $notes ? \Str::limit($notes, 40) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            @can('stocks.create')
                                                <a href="{{ route('stocks.edit_adjustment', $voucherNo) }}"
                                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                    title="{{ __('messages.edit_adjustment') }}"
                                                    style="width:30px;height:30px;padding:0;">
                                                    <i class="bx bx-edit" style="font-size:1rem;"></i>
                                                </a>
                                                <form id="delete-form-{{ $slug }}"
                                                    action="{{ route('stocks.destroy_adjustment', $voucherNo) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="{{ $slug }}" data-no="{{ $voucherNo }}"
                                                        title="{{ __('messages.delete') }}"
                                                        style="width:30px;height:30px;padding:0;">
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
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Only init DataTable when records exist
            if ($('#historyTable').length) {
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
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "{{ __('messages.search') }}...",
                        lengthMenu: "{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}",
                        info: "{{ __('messages.showing') }} _START_ {{ __('messages.to') }} _END_ {{ __('messages.of') }} _TOTAL_ {{ __('messages.entries') }}",
                        infoEmpty: "{{ __('messages.no_entries') }}",
                        infoFiltered: "({{ __('messages.filtered_from') }} _MAX_ {{ __('messages.total_entries') }})",
                        emptyTable: '<div class="text-center py-5 text-muted"><i class="bx bx-inbox" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:8px;"></i>{{ __('messages.no_records') }}</div>',
                        paginate: {
                            previous: '<i class="bx bx-chevron-left"></i>',
                            next: '<i class="bx bx-chevron-right"></i>'
                        }
                    }
                });
            }

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    no = $(this).data('no'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '{{ __('messages.delete_voucher_title') }}',
                    text: `{{ __('messages.delete_voucher_text') }} "${no}". {{ __('messages.confirm') }}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush
