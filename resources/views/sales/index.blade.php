@extends('layouts.admin')
@section('title', __('messages.sales_invoices'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.sales_invoices') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_sales') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-filter-alt me-1"></i> {{ __('messages.filters') }} <i id="filtersChevron"
                    class="bx bx-chevron-down ms-1"></i>
            </button>
            @can('sales.create')
                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_sale') }}
                </a>
            @endcan
        </div>
    </div>

    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2"></i>{{ __('messages.filter_sales') }}</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('sales.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.invoice_no_label') }}</label>
                            <input type="text" name="invoice_no" class="form-control form-control-sm"
                                value="{{ request('invoice_no') }}" placeholder="INV-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.customer') }}</label>
                            <select name="customer_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.all_customers') }}</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">{{ __('messages.status') }}</label>
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
                            <label class="form-label fw-semibold">{{ __('messages.date_from') }}</label>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">{{ __('messages.date_to') }}</label>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('sales.index') }}"
                            class="btn btn-outline-secondary btn-sm">{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-search me-1"></i>
                            {{ __('messages.apply') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="salesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.th_invoice') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.th_customer') }}</th>
                            <th>{{ __('messages.th_items') }}</th>
                            <th class="text-end">{{ __('messages.th_total') }}</th>
                            <th class="text-end">{{ __('messages.th_paid') }}</th>
                            <th class="text-end">{{ __('messages.th_due') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th>{{ __('messages.th_created_by') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $index => $sale)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><code class="fw-bold">{{ $sale->invoice_no }}</code></td>
                                <td class="text-muted">{{ $sale->invoice_date }}</td>
                                <td><strong>{{ $sale->customer->name }}</strong></td>
                                <td class="text-muted">{{ $sale->items->count() }} {{ __('messages.items_count') }}</td>
                                <td class="text-end fw-bold">{{ format_currency($sale->grand_total) }}</td>
                                <td class="text-end text-success fw-semibold">{{ format_currency($sale->paid_amount) }}
                                </td>
                                <td class="text-end text-danger fw-semibold">{{ format_currency($sale->due_amount) }}</td>
                                <td class="text-center">
                                    @if ($sale->status === 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                    @elseif($sale->status === 'Draft')
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.draft') }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">{{ __('messages.cancelled') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $sale->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('sales.view')
                                            <a href="{{ route('sales.show', $sale->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}"><i class="bx bx-show"></i></a>
                                            <a href="{{ route('sales.print', $sale->id) }}" target="_blank"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                title="{{ __('messages.print') }}"><i class="bx bx-printer"></i></a>
                                        @endcan
                                        @can('sales.update')
                                            <a href="{{ route('sales.edit', $sale->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('sales.delete')
                                            <form id="delete-form-{{ $sale->id }}"
                                                action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_no }}"
                                                    title="{{ __('messages.delete') }}"><i class="bx bx-trash"></i></button>
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
            $('#salesTable').DataTable({
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
            let filtersOpen = localStorage.getItem('sales_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('sales_filters_open', isOpen);
            });
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    invoice = $(this).data('invoice'),
                    form = $(`#delete-form-${id}`);
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
                    if (r.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(res) {
                                if (res.success) Swal.fire({
                                    title: '{{ __('messages.deleted_title') }}',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => window.location.reload());
                                else showAdminToast(res.message, 'error');
                            },
                            error: function() {
                                showAdminToast('{{ __('messages.error_occurred') }}',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
