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
            <div class="table-responsive p-3">
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
                        @forelse($roles as $index => $role)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><strong>{{ $role->name }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-label-primary">
                                        <i class="bx bx-key me-1"></i>{{ $role->permissions_count }}
                                        {{ __('messages.permissions') }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $role->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('roles.update')
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan
                                        @can('roles.delete')
                                            @if ($role->name !== 'Super Admin')
                                                <form id="delete-form-{{ $role->id }}"
                                                    action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="{{ $role->id }}" data-name="{{ $role->name }}"
                                                        title="{{ __('messages.delete') }}">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
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
                }]
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const form = $(`#delete-form-${id}`);

                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `{{ __('messages.delete') }} "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}',
                }).then((result) => {
                    if (result.isConfirmed) {
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
