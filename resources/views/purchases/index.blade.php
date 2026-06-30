@extends('layouts.admin')
@section('title', __('messages.purchase_orders'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.purchase_orders') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_purchases') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i> {{ __('messages.filters') }}
                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            @can('purchases.create')
                <a href="{{ route('purchases.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
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
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.th_total') }}</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $totalPurchases }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-cart"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.completed') }}</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $completedPurchases }}</h4>
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
                        <p class="mb-0 text-muted small">Total Amount</p>
                        <h4 class="mb-0 fw-bold text-info">{{ format_currency($totalAmount) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Total Due</p>
                        <h4 class="mb-0 fw-bold {{ $totalDue > 0 ? 'text-danger' : 'text-success' }}">
                            {{ format_currency($totalDue) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i
                        class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filter_purchases') }}</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('purchases.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.purchase_no_label') }}</label>
                            <input type="text" name="purchase_no" class="form-control form-control-sm"
                                value="{{ request('purchase_no') }}" placeholder="PUR-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.supplier') }}</label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_suppliers') }}</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>
                                    {{ __('messages.draft') }}</option>
                                <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>
                                    {{ __('messages.completed') }}</option>
                                <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>
                                    {{ __('messages.cancelled') }}</option>
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
                        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary"><i
                                class="bx bx-search me-1"></i>{{ __('messages.apply_filters') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="purchasesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.th_purchase_no') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.th_supplier') }}</th>
                            <th>{{ __('messages.th_items') }}</th>
                            <th class="text-end">{{ __('messages.th_total') }}</th>
                            <th class="text-end">{{ __('messages.th_paid') }}</th>
                            <th class="text-end">{{ __('messages.th_due') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $index => $purchase)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $purchase->id }}</td>
                                <td><code class="fw-bold">{{ $purchase->purchase_no }}</code></td>
                                <td class="text-muted">{{ $purchase->purchase_date }}</td>
                                <td><strong>{{ $purchase->supplier->name ?? '-' }}</strong></td>
                                <td class="text-muted">{{ $purchase->items->count() }} {{ __('messages.items_count') }}
                                </td>
                                <td class="text-end fw-bold">{{ format_currency($purchase->grand_total) }}</td>
                                <td class="text-end text-success fw-semibold">
                                    {{ format_currency($purchase->paid_amount) }}
                                </td>
                                <td class="text-end text-danger fw-semibold">{{ format_currency($purchase->due_amount) }}
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
                                        {{-- View --}}
                                        @can('purchases.view')
                                            <a href="{{ route('purchases.show', $purchase->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                title="{{ __('messages.print') }}">
                                                <i class="bx bx-printer"></i>
                                            </a>
                                        @endcan
                                        {{-- Create/Edit Return --}}
                                        @if ($purchase->status === 'Completed')
                                            @if ($purchase->returns->count() > 0)
                                                @can('purchase_returns.update')
                                                    <a href="{{ route('purchase-returns.edit', $purchase->returns->first()->id) }}"
                                                        class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                        title="Edit Return">
                                                        <i class="bx bx-undo"></i>
                                                    </a>
                                                @endcan
                                            @else
                                                @can('purchase_returns.create')
                                                    <a href="{{ route('purchase-returns.create', ['purchase_id' => $purchase->id]) }}"
                                                        class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                        title="Create Return">
                                                        <i class="bx bx-undo"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                        @endif
                                        {{-- Edit --}}
                                        @can('purchases.update')
                                            <a href="{{ route('purchases.edit', $purchase->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan
                                        {{-- Delete --}}
                                        @can('purchases.delete')
                                            <form id="delete-form-{{ $purchase->id }}"
                                                action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $purchase->id }}" data-no="{{ $purchase->purchase_no }}"
                                                    title="{{ __('messages.delete') }}">
                                                    <i class="bx bx-trash"></i>
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
        });
    </script>
@endpush
