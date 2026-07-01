@extends('layouts.admin')
@section('title', __('messages.default_currency'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.default_currency') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_currencies') }}</li>
                </ol>
            </nav>
        </div>
        @can('currencies.create')
            <button type="button" id="openCreateModalBtn" class="btn btn-outline-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.add_currency') }}
            </button>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="currenciesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.currency_name') }}</th>
                            <th>{{ __('messages.th_code') }}</th>
                            <th>{{ __('messages.th_symbol') }}</th>
                            <th>{{ __('messages.th_exchange_rate') }}</th>
                            <th>{{ __('messages.th_default') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- AJAX populated --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bootstrap Modal --}}
    <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold mb-0" id="currencyModalLabel">{{ __('messages.add_new_currency') }}
                    </h6>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <form id="currencyForm" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="mb-3 form-group-container">
                            <label class="form-label fw-semibold">{{ __('messages.currency_name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="{{ __('messages.currency_placeholder_name') }}" required>
                            <span class="error-msg text-danger small d-none"><span class="msg-content"></span></span>
                        </div>
                        <div class="mb-3 form-group-container">
                            <label class="form-label fw-semibold">{{ __('messages.currency_code') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control"
                                placeholder="{{ __('messages.currency_placeholder_code') }}" required>
                            <span class="error-msg text-danger small d-none"><span class="msg-content"></span></span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 form-group-container">
                                <label class="form-label fw-semibold">{{ __('messages.currency_symbol_label') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="symbol" id="symbol" class="form-control"
                                    placeholder="{{ __('messages.currency_placeholder_sym') }}" required>
                                <span class="error-msg text-danger small d-none"><span class="msg-content"></span></span>
                            </div>
                            <div class="col-md-6 form-group-container">
                                <label class="form-label fw-semibold">{{ __('messages.exchange_rate') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="exchange_rate" id="exchange_rate"
                                    class="form-control" placeholder="{{ __('messages.currency_placeholder_rate') }}"
                                    required>
                                <span class="error-msg text-danger small d-none"><span class="msg-content"></span></span>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6 form-group-container">
                                <label class="form-label fw-semibold">{{ __('messages.status') }} <span
                                        class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="active">{{ __('messages.active') }}</option>
                                    <option value="inactive">{{ __('messages.inactive') }}</option>
                                </select>
                                <span class="error-msg text-danger small d-none"><span class="msg-content"></span></span>
                            </div>
                            <div class="col-md-6 d-flex align-items-end pb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_default"
                                        id="is_default" value="1">
                                    <label class="form-check-label fw-semibold"
                                        for="is_default">{{ __('messages.set_as_default_switch') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>{{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>{{ __('messages.save_currency') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const canUpdate = {{ auth()->user()->can('currencies.update') ? 'true' : 'false' }};
            const canDelete = {{ auth()->user()->can('currencies.delete') ? 'true' : 'false' }};

            const table = $('#currenciesTable').DataTable({
                processing: true,
                ajax: "{{ route('currencies.index') }}",
                responsive: true,
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
                },
                columns: [{
                        data: null,
                        render: (d, t, r, m) => m.row + 1
                    },
                    {
                        data: 'name',
                        className: 'fw-bold'
                    },
                    {
                        data: 'code',
                        render: d => `<code>${d}</code>`
                    },
                    {
                        data: 'symbol',
                        className: 'fw-semibold'
                    },
                    {
                        data: 'exchange_rate',
                        render: d => parseFloat(d).toFixed(4)
                    },
                    {
                        data: 'is_default',
                        render: (d, t, r) => {
                            if (d)
                                return '<span class="badge rounded-pill bg-label-primary fw-semibold px-3 py-1">{{ __('messages.th_default') }}</span>';
                            if (canUpdate)
                                return `<button type="button" class="btn btn-sm btn-outline-secondary set-default-btn rounded-pill" data-id="${r.id}" style="font-size:11px;padding:2px 12px;">{{ __('messages.set_default') }}</button>`;
                            return '<span class="text-muted">—</span>';
                        }
                    },
                    {
                        data: 'status',
                        render: (d, t, r) => {
                            const isActive = d === 'active';
                            const cls = isActive ? 'border-success text-success' :
                                'border-danger text-danger';
                            const txt = isActive ? '{{ __('messages.active') }}' :
                                '{{ __('messages.inactive') }}';
                            if (canUpdate)
                                return `<button type="button" class="toggle-status-btn badge rounded-pill border fw-semibold px-3 py-1 ${cls}" data-id="${r.id}" data-status="${d}" style="background:transparent;cursor:pointer;" title="{{ __('messages.click_to_toggle') }}">${txt}</button>`;
                            return `<span class="badge rounded-pill border fw-semibold px-3 py-1 ${cls}" style="background:transparent;">${txt}</span>`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: (d, t, r) => {
                            if (!canUpdate && !canDelete)
                                return '<span class="text-muted small">—</span>';

                            let editBtn = canUpdate ?
                                `<a href="#" class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action edit-btn" data-id="${r.id}" title="{{ __('messages.edit') }}" style="width:30px;height:30px;padding:0;"><i class="bx bx-edit" style="font-size:1rem;"></i></a>` :
                                '';

                            let deleteBtn = canDelete ?
                                `<button type="button" class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn" data-id="${r.id}" data-name="${r.name}" title="{{ __('messages.delete') }}" style="width:30px;height:30px;padding:0;"><i class="bx bx-trash" style="font-size:1rem;"></i></button>` :
                                '';

                            return `<div class="d-flex align-items-center justify-content-center gap-1">${editBtn}${deleteBtn}</div>`;
                        }
                    }
                ]
            });

            const modal = new bootstrap.Modal(document.getElementById('currencyModal'));
            const form = $('#currencyForm');

            $('#openCreateModalBtn').on('click', function() {
                resetForm();
                $('#currencyModalLabel').text('{{ __('messages.add_new_currency') }}');
                $('#formMethod').val('POST');
                form.attr('action', "{{ route('currencies.store') }}");
                modal.show();
            });

            form.on('submit', function(e) {
                e.preventDefault();
                clearErrors();
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.success) {
                            modal.hide();
                            table.ajax.reload(null, false);
                            showAdminToast(res.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                const input = $(`#${field}`);
                                const container = input.closest('.form-group-container');
                                container.find('.error-msg').removeClass('d-none').find(
                                    '.msg-content').text(errors[field][0]);
                                input.addClass('is-invalid');
                            }
                        } else {
                            showAdminToast('An error occurred.', 'error');
                        }
                    }
                });
            });

            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                resetForm();
                $.ajax({
                    url: `/currencies/${id}/edit`,
                    type: 'GET',
                    success: function(res) {
                        if (res.success) {
                            const d = res.data;
                            $('#name').val(d.name);
                            $('#code').val(d.code);
                            $('#symbol').val(d.symbol);
                            $('#exchange_rate').val(d.exchange_rate);
                            $('#status').val(d.status);
                            $('#is_default').prop('checked', d.is_default);
                            $('#currencyModalLabel').text('Edit Currency');
                            $('#formMethod').val('PUT');
                            form.attr('action', `/currencies/${id}`);
                            modal.show();
                        }
                    },
                    error: function() {
                        showAdminToast('Could not fetch currency details.', 'error');
                    }
                });
            });

            $(document).on('click', '.set-default-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Set Default?',
                    text: 'Make this the default transaction currency?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#696cff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes!'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: `/currencies/${id}/edit`,
                            type: 'GET',
                            success: function(g) {
                                if (g.success) {
                                    const d = {
                                        ...g.data,
                                        _token: "{{ csrf_token() }}",
                                        _method: 'PUT',
                                        is_default: 1
                                    };
                                    $.ajax({
                                        url: `/currencies/${id}`,
                                        type: 'POST',
                                        data: d,
                                        success: function(u) {
                                            if (u.success) {
                                                table.ajax.reload(null,
                                                    false);
                                                showAdminToast(
                                                    'Default currency changed.',
                                                    'success');
                                            }
                                        },
                                        error: function() {
                                            showAdminToast('Failed.',
                                                'error');
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.toggle-status-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `/currencies/${id}/toggle-status`,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            table.ajax.reload(null, false);
                            showAdminToast(res.message, 'success');
                        }
                    },
                    error: function() {
                        showAdminToast('Could not toggle status.', 'error');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name');
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete!'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: `/currencies/${id}`,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(res) {
                                if (res.success) Swal.fire({
                                    title: 'Deleted!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => table.ajax.reload(null, false));
                            },
                            error: function() {
                                showAdminToast('Failed.', 'error');
                            }
                        });
                    }
                });
            });

            function resetForm() {
                form[0].reset();
                $('#is_default').prop('checked', false);
                clearErrors();
            }

            function clearErrors() {
                $('.error-msg').addClass('d-none').find('.msg-content').text('');
                $('input, select').removeClass('is-invalid');
            }
        });
    </script>
@endpush
