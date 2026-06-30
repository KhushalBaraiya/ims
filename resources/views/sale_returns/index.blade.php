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
        <div class="d-flex gap-2 align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i> {{ __('messages.filters') }}
                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            @can('sale_returns.create')
                <a href="{{ route('sale-returns.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> {{ __('messages.new_return') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Summary Stats --}}
    @php
        use App\Models\SaleReturn;
        $totalSaleReturns = SaleReturn::count();
        $completedSaleReturns = SaleReturn::where('status', 'Completed')->count();
        $pendingSaleReturns = SaleReturn::where('status', 'Pending')->count();
        $totalSaleRefunded = SaleReturn::where('status', 'Completed')->sum('refunded_amount');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">{{ __('messages.th_total') }}</p>
                        <h4 class="mb-0 fw-bold text-primary">{{ $totalSaleReturns }}</h4>
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
                        <h4 class="mb-0 fw-bold text-success">{{ $completedSaleReturns }}</h4>
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
                        <h4 class="mb-0 fw-bold text-warning">{{ $pendingSaleReturns }}</h4>
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
                        <h4 class="mb-0 fw-bold text-info">{{ format_currency($totalSaleRefunded) }}</h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i
                        class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filter_sale_returns') }}</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('sale-returns.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.return_no_label') }}</label>
                            <input type="text" name="return_no" class="form-control form-control-sm"
                                value="{{ request('return_no') }}" placeholder="RET-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">{{ __('messages.sale_invoice_no') }}</label>
                            <input type="text" name="sale_invoice" class="form-control form-control-sm"
                                value="{{ request('sale_invoice') }}" placeholder="INV-YYYYMMDD-XXXXX">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small">{{ __('messages.customer') }}</label>
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
                        <a href="{{ route('sale-returns.index') }}" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i>{{ __('messages.reset') }}</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-search me-1"></i>
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
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-outline-secondary rounded-circle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="width:32px;height:32px;padding:0;">
                                            <i class="bx bx-dots-vertical-rounded" style="font-size:1.1rem;"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                            style="min-width:180px;border-radius:10px;">
                                            @can('sale_returns.view')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('sale-returns.show', $ret->id) }}">
                                                        <i class="bx bx-show text-info" style="font-size:1rem;"></i>
                                                        <span>{{ __('messages.view') }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('sale-returns.print', $ret->id) }}" target="_blank">
                                                        <i class="bx bx-printer text-success" style="font-size:1rem;"></i>
                                                        <span>{{ __('messages.print') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('sale_returns.update')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('sale-returns.edit', $ret->id) }}">
                                                        <i class="bx bx-edit text-primary" style="font-size:1rem;"></i>
                                                        <span>{{ __('messages.edit') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('sale_returns.delete')
                                                <li>
                                                    <hr class="dropdown-divider my-1">
                                                </li>
                                                <li>
                                                    <form id="delete-form-{{ $ret->id }}"
                                                        action="{{ route('sale-returns.destroy', $ret->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger delete-btn"
                                                            data-id="{{ $ret->id }}"
                                                            data-return="{{ $ret->return_no }}">
                                                            <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                            <span>{{ __('messages.delete') }}</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
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
