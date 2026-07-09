@extends('layouts.admin')
@section('title', __('messages.purchase_returns'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.purchase_returns') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_purchase_returns') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i> {{ __('messages.filters') }}
                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            @can('purchase_returns.delete')
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> Delete Multiples
                </button>
            @endcan
            @can('purchase_returns.create')
                <a href="{{ route('purchase-returns.create') }}"
                    class="btn btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> {{ __('messages.new_return') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Summary Stats --}}
    @php
        use App\Models\PurchaseReturn;
        $totalReturns = PurchaseReturn::count();
        $completedReturns = PurchaseReturn::where('status', 'Completed')->count();
        $pendingReturns = PurchaseReturn::where('status', 'Pending')->count();
        $totalRefunded = PurchaseReturn::where('status', 'Completed')->sum('refunded_amount');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.th_total') }}</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $totalReturns }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-undo"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.completed') }}</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $completedReturns }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.pending') }}</p>
                        <h4 class="mb-0 fw-bold text-warning">{{ $pendingReturns }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Refunded</p>
                        <h4 class="mb-0 fw-bold text-info">{{ format_currency($totalRefunded) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filter_pur_returns') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('purchase-returns.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.return_no_label') }}</label>
                            <input type="text" name="return_no" class="form-control form-control-sm"
                                value="{{ request('return_no') }}" placeholder="PRET-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.supplier') }}</label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_suppliers') }}</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>
                                    {{ __('messages.completed') }}</option>
                                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                            <input type="date" name="start_date"
                                class="form-control form-control-sm flatpickr-filter-date"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                            <input type="date" name="end_date" class="form-control form-control-sm flatpickr-filter-date"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-reset me-1"></i>{{ __('messages.reset') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="purchaseReturnsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.th_return_no') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.th_purchase_no') }}</th>
                            <th>{{ __('messages.th_supplier') }}</th>
                            <th class="text-end">{{ __('messages.th_total') }}</th>
                            <th class="text-end">{{ __('messages.th_refunded') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th>{{ __('messages.th_created_by') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returns as $index => $return)
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="{{ $return->id }}"></td>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><code class="fw-bold">{{ $return->return_no }}</code></td>
                                <td class="text-muted">{{ $return->return_date }}</td>
                                <td>
                                    @if ($return->purchase)
                                        <a href="{{ route('purchases.show', $return->purchase_id) }}" class="text-muted">
                                            <code>{{ $return->purchase->purchase_no }}</code>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><strong>{{ $return->supplier->name ?? '-' }}</strong></td>
                                <td class="text-end fw-bold">{{ format_currency($return->grand_total) }}</td>
                                <td class="text-end text-success fw-semibold">
                                    {{ format_currency($return->refunded_amount) }}</td>
                                <td class="text-center">
                                    @if ($return->status === 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.pending') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $return->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('purchase_returns.view')
                                            <a href="{{ route('purchase-returns.show', $return->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                            <a href="{{ route('purchase-returns.print', $return->id) }}" target="_blank"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                title="{{ __('messages.print') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-printer" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('purchase_returns.update')
                                            <a href="{{ route('purchase-returns.edit', $return->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('purchase_returns.delete')
                                            <form id="delete-form-{{ $return->id }}"
                                                action="{{ route('purchase-returns.destroy', $return->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $return->id }}" data-no="{{ $return->return_no }}"
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
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#purchaseReturnsTable').DataTable({
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-cart-download" style="font-size:3rem;opacity:.3;line-height:1;"></i>{{ __('messages.no_records') }}</div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            // Filters toggle — persist state
            let filtersOpen = localStorage.getItem('pur_returns_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('pur_returns_filters_open', isOpen);
            });

            // Delete with AJAX
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const no = $(this).data('no');
                const form = $(`#delete-form-${id}`);
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
                    title: 'Delete ' + ids.length + ' return(s)?',
                    text: 'Stock will be reversed for Completed returns. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete all!',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route('purchase-returns.bulk-destroy') }}',
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
        });
    </script>
@endpush



