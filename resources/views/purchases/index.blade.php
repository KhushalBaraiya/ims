@extends('layouts.admin')
@section('title', 'Purchase Orders')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Purchase Orders</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Purchases</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-filter-alt me-1"></i> Filters <i id="filtersChevron" class="bx bx-chevron-down ms-1"></i>
            </button>
            @can('purchases.create')
                <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> New Purchase
                </a>
            @endcan
        </div>
    </div>

    {{-- Filters --}}
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2"></i>Filter Purchases</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('purchases.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Purchase No</label>
                            <input type="text" name="purchase_no" class="form-control form-control-sm"
                                value="{{ request('purchase_no') }}" placeholder="PUR-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                                <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Date From</label>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Date To</label>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-search me-1"></i>Apply
                            Filters</button>
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
                            <th>#</th>
                            <th>Purchase No</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Items</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Due</th>
                            <th class="text-center">Status</th>
                            <th>Created By</th>
                            <th class="text-center no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $index => $purchase)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><code class="fw-bold">{{ $purchase->purchase_no }}</code></td>
                                <td class="text-muted">{{ $purchase->purchase_date }}</td>
                                <td><strong>{{ $purchase->supplier->name ?? '-' }}</strong></td>
                                <td class="text-muted">{{ $purchase->items->count() }} items</td>
                                <td class="text-end fw-bold">{{ format_currency($purchase->grand_total) }}</td>
                                <td class="text-end text-success fw-semibold">{{ format_currency($purchase->paid_amount) }}
                                </td>
                                <td class="text-end text-danger fw-semibold">{{ format_currency($purchase->due_amount) }}
                                </td>
                                <td class="text-center">
                                    @if ($purchase->status === 'Completed')
                                        <span class="badge rounded-pill bg-success">Completed</span>
                                    @elseif($purchase->status === 'Draft')
                                        <span class="badge rounded-pill bg-warning text-dark">Draft</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $purchase->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('purchases.view')
                                            <a href="{{ route('purchases.show', $purchase->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="View"><i class="bx bx-show"></i></a>
                                            <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                title="Print"><i class="bx bx-printer"></i></a>
                                        @endcan
                                        @can('purchases.update')
                                            <a href="{{ route('purchases.edit', $purchase->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Edit"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('purchases.delete')
                                            <form id="delete-form-{{ $purchase->id }}"
                                                action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $purchase->id }}" data-no="{{ $purchase->purchase_no }}"
                                                    title="Delete"><i class="bx bx-trash"></i></button>
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
                }]
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
                        title: 'Delete Purchase?',
                        text: `Deleting "${no}" will reverse stock changes.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete!'
                    })
                    .then((r) => {
                        if (r.isConfirmed) {
                            $.ajax({
                                url: form.attr('action'),
                                type: 'POST',
                                data: form.serialize(),
                                success: function(res) {
                                    if (res.success) Swal.fire({
                                        title: 'Deleted!',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#696cff'
                                    }).then(() => window.location.reload());
                                    else showAdminToast(res.message, 'error');
                                },
                                error: function() {
                                    showAdminToast('An error occurred.', 'error');
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endpush
