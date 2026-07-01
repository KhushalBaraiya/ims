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
            <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.add_user') }}
            </a>
        @endcan
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-primary flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-group"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-primary">{{ $users->count() }}</div>
                        <div class="text-muted small mt-1">{{ __('messages.th_total') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-success flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-success" id="statActiveCount">
                            {{ $users->where('status', 'active')->count() }}</div>
                        <div class="text-muted small mt-1">{{ __('messages.active') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-danger flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-danger" id="statInactiveCount">
                            {{ $users->where('status', 'inactive')->count() }}</div>
                        <div class="text-muted small mt-1">{{ __('messages.inactive') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-warning flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-shield"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-warning">
                            <a href="{{ route('roles.index') }}" class="text-warning text-decoration-none">
                                {{ \Spatie\Permission\Models\Role::count() }}
                            </a>
                        </div>
                        <div class="text-muted small mt-1">{{ __('messages.menu_roles') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
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
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            @if ($u->profile_photo)
                                                <img src="{{ asset('uploads/profiles/' . $u->profile_photo) }}"
                                                    class="rounded-circle" style="width:36px;height:36px;object-fit:cover;"
                                                    onerror="imgError(this)">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <strong>{{ $u->name }}</strong>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $u->email }}</td>
                                <td class="text-muted">{{ $u->phone ?: '—' }}</td>
                                <td>
                                    <span class="badge bg-label-primary">
                                        {{ $u->roles->pluck('name')->implode(', ') ?: 'No Role' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @can('users.update')
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1
                                                {{ $u->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;cursor:pointer;" data-id="{{ $u->id }}"
                                            data-status="{{ $u->status }}" title="{{ __('messages.click_to_toggle') }}">
                                            {{ $u->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </button>
                                    @else
                                        <span
                                            class="badge rounded-pill border fw-semibold px-3 py-1
                                            {{ $u->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;">
                                            {{ $u->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="text-muted small">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('users.view')
                                            <a href="{{ route('users.show', $u->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('users.update')
                                            <a href="{{ route('users.edit', $u->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
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
                                                        title="{{ __('messages.delete') }}"
                                                        style="width:30px;height:30px;padding:0;">
                                                        <i class="bx bx-trash" style="font-size:1rem;"></i>
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
                const btn = $(this),
                    id = btn.data('id'),
                    cur = btn.data('status');
                $.ajax({
                    url: `/users/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: () => btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span>'),
                    success: (res) => {
                        btn.prop('disabled', false);
                        if (res.success) {
                            btn.data('status', res.status);
                            btn.removeClass(
                                'border-success text-success border-danger text-danger');
                            btn.addClass(res.status === 'active' ?
                                'border-success text-success' : 'border-danger text-danger');
                            btn.text(res.status === 'active' ? '{{ __('messages.active') }}' :
                                '{{ __('messages.inactive') }}');
                            showAdminToast(res.message, 'success');
                            // ── Update stat cards live ──────────────────
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                            $('#statInactiveCount').text($('.status-toggle-btn.border-danger')
                                .length);
                        } else {
                            btn.text(cur === 'active' ? '{{ __('messages.active') }}' :
                                '{{ __('messages.inactive') }}');
                            showAdminToast(res.message ||
                                '{{ __('messages.error_occurred') }}', 'error');
                        }
                    },
                    error: () => {
                        btn.prop('disabled', false).text(cur === 'active' ?
                            '{{ __('messages.active') }}' :
                            '{{ __('messages.inactive') }}');
                        showAdminToast('{{ __('messages.error_occurred') }}', 'error');
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
