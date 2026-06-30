@extends('layouts.admin')
@section('title', __('messages.role_management'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.role_management') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_roles') }}</li>
                </ol>
            </nav>
        </div>
        @can('roles.create')
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.add_role') }}
            </a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="rolesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.role') }}</th>
                            <th class="text-center">{{ __('messages.permissions') }}</th>
                            <th>{{ __('messages.th_created') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $index => $role)
                            <tr>
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
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-outline-secondary rounded-circle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="width:32px;height:32px;padding:0;">
                                            <i class="bx bx-dots-vertical-rounded" style="font-size:1.1rem;"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                            style="min-width:160px;border-radius:10px;">
                                            @can('roles.update')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('roles.edit', $role->id) }}">
                                                        <i class="bx bx-edit text-primary" style="font-size:1rem;"></i>
                                                        <span>{{ __('messages.edit') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('roles.delete')
                                                @if ($role->name !== 'Super Admin')
                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>
                                                    <li>
                                                        <form id="delete-form-{{ $role->id }}"
                                                            action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="button"
                                                                class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger delete-btn"
                                                                data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                                                                <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                                <span>{{ __('messages.delete') }}</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bx bx-shield" style="font-size:2.5rem;opacity:.3;"></i>
                                    <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
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
        });
    </script>
@endpush
