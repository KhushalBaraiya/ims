@extends('layouts.admin')
@section('title', __('messages.user_management'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.user_management') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_users') }}</li>
                </ol>
            </nav>
        </div>
        @can('users.create')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.add_user') }}
            </a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="usersTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th class="no-sort">{{ __('messages.th_photo') }}</th>
                            <th>{{ __('messages.th_name') }}</th>
                            <th>{{ __('messages.th_email') }}</th>
                            <th>{{ __('messages.th_phone') }}</th>
                            <th>{{ __('messages.th_role') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th>{{ __('messages.th_created') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $u)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    @if ($u->profile_photo)
                                        <img src="{{ asset('uploads/profiles/' . $u->profile_photo) }}"
                                            class="tbl-img-round" alt="{{ $u->name }}">
                                    @else
                                        <div
                                            class="tbl-img-round d-flex align-items-center justify-content-center bg-label-primary">
                                            <strong>{{ strtoupper(substr($u->name, 0, 1)) }}</strong>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $u->name }}</strong></td>
                                <td class="text-muted">{{ $u->email }}</td>
                                <td class="text-muted">{{ $u->phone ?: '-' }}</td>
                                <td>
                                    <span class="badge bg-label-primary">
                                        {{ $u->roles->pluck('name')->implode(', ') ?: 'Staff' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $u->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $u->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('users.view')
                                            <a href="{{ route('users.show', $u->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @endcan
                                        @can('users.update')
                                            <a href="{{ route('users.edit', $u->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan
                                        @can('users.delete')
                                            @if (auth()->id() !== $u->id)
                                                <form id="delete-form-{{ $u->id }}"
                                                    action="{{ route('users.destroy', $u->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="{{ $u->id }}" data-name="{{ $u->name }}"
                                                        title="{{ __('messages.delete') }}">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
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
            $('#usersTable').DataTable({
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
