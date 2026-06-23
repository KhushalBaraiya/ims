@extends('layouts.admin')
@section('title', __('messages.sale_returns'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.sale_returns') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_sale_returns') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-filter-alt me-1"></i> {{ __('messages.filters') }} <i id="filtersChevron"
                    class="bx bx-chevron-down ms-1"></i>
            </button>
            @can('sale_returns.create')
                <a href="{{ route('sale-returns.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.new_return') }}
                </a>
            @endcan
        </div>
    </div>

    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2"></i>{{ __('messages.filter_sale_returns') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('sale-returns.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.return_no_label') }}</label>
                            <input type="text" name="return_no" class="form-control form-control-sm"
                                value="{{ request('return_no') }}" placeholder="RET-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">{{ __('messages.sale_invoice_no') }}</label>
                            <input type="text" name="sale_invoice" class="form-control form-control-sm"
                                value="{{ request('sale_invoice') }}" placeholder="INV-YYYYMMDD-XXXXX">
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
                            <label class="form-label fw-semibold">{{ __('messages.date_from') }}</label>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold">{{ __('messages.date_to') }}</label>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('sale-returns.index') }}"
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
                <table class="table table-hover align-middle mb-0" id="returnsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.th_return_no') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.th_sale_invoice') }}</th>
                            <th>{{ __('messages.th_customer') }}</th>
                            <th class="text-end">{{ __('messages.th_total') }}</th>
                            <th class="text-end">{{ __('messages.th_refunded') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th>{{ __('messages.th_created_by') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($returns as $index => $ret)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><code class="fw-bold">{{ $ret->return_no }}</code></td>
                                <td class="text-muted">{{ $ret->return_date }}</td>
                                <td>
                                    @if ($ret->sale)
                                        <a href="{{ route('sales.show', $ret->sale_id) }}" class="text-primary">
                                            <code>{{ $ret->sale->invoice_no }}</code>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><strong>{{ $ret->customer->name }}</strong></td>
                                <td class="text-end fw-bold">{{ format_currency($ret->grand_total) }}</td>
                                <td class="text-end text-success fw-semibold">{{ format_currency($ret->refunded_amount) }}
                                </td>
                                <td class="text-center">
                                    @if ($ret->status === 'Completed')
                                        <span class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-warning text-dark">{{ __('messages.pending') }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $ret->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('sale_returns.view')
                                            <a href="{{ route('sale-returns.show', $ret->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}"><i class="bx bx-show"></i></a>
                                            <a href="{{ route('sale-returns.print', $ret->id) }}" target="_blank"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                title="{{ __('messages.print') }}"><i class="bx bx-printer"></i></a>
                                        @endcan
                                        @can('sale_returns.update')
                                            <a href="{{ route('sale-returns.edit', $ret->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('sale_returns.delete')
                                            <form id="delete-form-{{ $ret->id }}"
                                                action="{{ route('sale-returns.destroy', $ret->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $ret->id }}" data-return="{{ $ret->return_no }}"
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
            $('#returnsTable').DataTable({
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
            let filtersOpen = localStorage.getItem('returns_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('returns_filters_open', isOpen);
            });
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    returnNo = $(this).data('return'),
                    form = $(`#delete-form-${id}`);
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
