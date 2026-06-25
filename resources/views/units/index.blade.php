@extends('layouts.admin')
@section('title', __('messages.units'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.units') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.units') }}</li>
                </ol>
            </nav>
        </div>
        @can('units.create')
            <a href="{{ route('units.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.add_unit') }}
            </a>
        @endcan
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="unitsTable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('messages.th_no') }}</th>
                        <th>{{ __('messages.unit_name') }}</th>
                        <th>{{ __('messages.short_name') }}</th>
                        <th class="text-center">{{ __('messages.th_status') }}</th>
                        <th>{{ __('messages.th_created') }}</th>
                        <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $index => $unit)
                        <tr>
                            <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-sm flex-shrink-0">
                                        <span class="avatar-initial rounded-circle bg-label-warning">
                                            <i class="bx bx-ruler" style="font-size:1rem;"></i>
                                        </span>
                                    </div>
                                    <strong>{{ $unit->name }}</strong>
                                </div>
                            </td>
                            <td><code class="text-warning">{{ $unit->short_name }}</code></td>
                            <td class="text-center">
                                @can('units.update')
                                    <button type="button"
                                        class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1 {{ $unit->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                        style="background:transparent;cursor:pointer;" data-id="{{ $unit->id }}"
                                        data-status="{{ $unit->status }}" title="{{ __('messages.click_to_toggle') }}">
                                        {{ $unit->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                    </button>
                                @else
                                    <span
                                        class="badge rounded-pill border fw-semibold px-3 py-1 {{ $unit->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                        style="background:transparent;">
                                        {{ $unit->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                @endcan
                            </td>
                            <td class="text-muted small">{{ $unit->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    @can('units.view')
                                        <a href="{{ route('units.show', $unit->id) }}"
                                            class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                            title="{{ __('messages.view') }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    @endcan
                                    @can('units.update')
                                        <a href="{{ route('units.edit', $unit->id) }}"
                                            class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                            title="{{ __('messages.edit') }}">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    @endcan
                                    @can('units.delete')
                                        <form id="delete-form-{{ $unit->id }}"
                                            action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"
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

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const dt = $('#unitsTable').DataTable({
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

            // Status toggle
            $(document).on('click', '.status-toggle-btn', function() {
                const btn = $(this);
                const id = btn.data('id');
                const currentStatus = btn.data('status');

                $.ajax({
                    url: `/units/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status"></span>'
                        );
                    },
                    success: function(res) {
                        if (res.success) {
                            const newStatus = res.status;
                            btn.data('status', newStatus);
                            if (newStatus === 'active') {
                                btn.removeClass('border-danger text-danger').addClass(
                                    'border-success text-success');
                                btn.text('{{ __('messages.active') }}');
                            } else {
                                btn.removeClass('border-success text-success').addClass(
                                    'border-danger text-danger');
                                btn.text('{{ __('messages.inactive') }}');
                            }
                            showAdminToast(res.message, 'success');
                        } else {
                            showAdminToast(res.message ||
                                '{{ __('messages.error_occurred') }}', 'error');
                        }
                        btn.prop('disabled', false);
                    },
                    error: function() {
                        showAdminToast('{{ __('messages.error_occurred') }}', 'error');
                        btn.prop('disabled', false);
                        btn.text(currentStatus === 'active' ? '{{ __('messages.active') }}' :
                            '{{ __('messages.inactive') }}');
                    }
                });
            });

            // Delete
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `{{ __('messages.delete') }} "${name}"?`,
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
        });
    </script>
@endpush
