@extends('layouts.admin')
@section('title', __('messages.role_management'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.role_management') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_roles') }}</li>
                </ol>
            </nav>
        </div>
        @can('roles.create')
            <div class="d-flex align-items-center gap-2">
                @can('roles.delete')
                    <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button">
                        <i class="bx bx-trash me-1"></i> Delete Multiples
                    </button>
                @endcan
                <a class="btn btn-outline-primary" href="{{ route('roles.create') }}">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_role') }}
                </a>
            </div>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-hover mb-0 table align-middle" id="rolesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input class="form-check-input" id="selectAll" type="checkbox"></th>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.role') }}</th>
                            <th class="text-center">{{ __('messages.permissions') }}</th>
                            <th>{{ __('messages.th_created') }}</th>
                            <th class="no-sort text-center">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $index => $role)
                            <tr>
                                <td><input class="form-check-input row-checkbox" type="checkbox"
                                        value="{{ $role->id }}"></td>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-warning">
                                                <i class="bx bx-shield" style="font-size:1rem;"></i>
                                            </span>
                                        </div>
                                        <strong>{{ $role->name }}</strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-primary">
                                        <i class="bx bx-key me-1"></i>{{ $role->permissions_count }}
                                        {{ __('messages.permissions') }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $role->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('roles.update')
                                            <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                href="{{ route('roles.edit', $role->id) }}"
                                                style="width:30px;height:30px;padding:0;" title="{{ __('messages.edit') }}">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('roles.delete')
                                            @if ($role->name !== 'super_admin')
                                                <form action="{{ route('roles.destroy', $role->id) }}" class="d-inline"
                                                    id="delete-form-{{ $role->id }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="{{ $role->id }}" data-name="{{ $role->name }}"
                                                        style="width:30px;height:30px;padding:0;"
                                                        title="{{ __('messages.delete') }}" type="button">
                                                        <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted py-5 text-center" colspan="5">
                                    <i class="bx bx-shield" style="font-size:2.5rem;opacity:.3;"></i>
                                    <p class="mb-0 mt-2">{{ __('messages.no_records') }}</p>
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
            $('#rolesTable').DataTable({
                responsive: true,
                pageLength: 10,
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
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

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
                            success: (res) => {
                                if (res.success) Swal.fire({
                                    title: '{{ __('messages.deleted_title') }}',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => location.reload());
                                else showAdminToast(res.message, 'error');
                            },
                            error: () => showAdminToast(
                                '{{ __('messages.error_occurred') }}', 'error')
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
                    title: 'Delete ' + ids.length + ' role(s)?',
                    text: 'super_admin role will be skipped. This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete all!',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route('roles.bulk-destroy') }}',
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
