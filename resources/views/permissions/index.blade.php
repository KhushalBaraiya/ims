@extends('layouts.admin')
@section('title', __('messages.permission_management'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.permission_management') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.menu_permissions') }}</li>
                </ol>
            </nav>
        </div>
        @can('permissions.create')
            <div class="d-flex align-items-center gap-2">
                @can('permissions.delete')
                    <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button">
                        <i class="bx bx-trash me-1"></i> {{ __('messages.delete_multiples') }}
                    </button>
                @endcan
                <a class="btn btn-outline-primary" href="{{ route('permissions.create') }}">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_permission') }}
                </a>
            </div>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filters') }}</h6>
            @if ($paginatedPermissions)
                <span class="badge bg-label-primary">Total: {{ $paginatedPermissions->total() }} Permissions</span>
            @endif
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('permissions.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="{{ request('search') }}" placeholder="{{ __('messages.ph_search_permission') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.module_label') }}</label>
                        <select name="module" class="form-select form-select-sm">
                            <option value="">-- {{ __('messages.all') }} --</option>
                            @foreach ($allModules as $mod)
                                <option value="{{ $mod }}" @selected(request('module') === $mod)>
                                    {{ ucwords(str_replace('_', ' ', $mod)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Per Page</label>
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="5" @selected(request('per_page') == '5')>5</option>
                            <option value="10" @selected(request('per_page', 10) == '10')>10</option>
                            <option value="15" @selected(request('per_page') == '15')>15</option>
                            <option value="25" @selected(request('per_page') == '25')>25</option>
                            <option value="50" @selected(request('per_page') == '50')>50</option>
                            <option value="100" @selected(request('per_page') == '100')>100</option>
                            <option value="all" @selected(request('per_page') == 'all')>All</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Grouped Permission Tables --}}
    @forelse ($grouped as $module => $perms)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-cube me-2 text-primary"></i>
                    {{ ucwords(str_replace('_', ' ', $module)) }}
                    <span class="badge bg-label-primary ms-1">{{ count($perms) }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 permission-table" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px"><input class="form-check-input module-select-all" type="checkbox"
                                        data-module="{{ $module }}"></th>
                                <th>{{ __('messages.permission_name') }}</th>
                                <th>{{ __('messages.th_actions_label') }}</th>
                                <th>{{ __('messages.roles_count') }}</th>
                                <th>{{ __('messages.th_created') }}</th>
                                <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perms as $permission)
                                @php
                                    [$mod, $act] = str_contains($permission->name, '.')
                                        ? explode('.', $permission->name, 2)
                                        : [$permission->name, '—'];
                                    $badgeColor = match ($act) {
                                        'view' => 'info',
                                        'create' => 'success',
                                        'update' => 'warning',
                                        'delete' => 'danger',
                                        'own' => 'secondary',
                                        default => 'primary',
                                    };
                                @endphp
                                <tr>
                                    <td><input class="form-check-input row-checkbox" type="checkbox"
                                            value="{{ $permission->id }}"></td>
                                    <td>
                                        <code class="small fw-semibold text-primary">{{ $permission->name }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $badgeColor }}">{{ ucfirst($act) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-warning">
                                            <i class="bx bx-shield me-1"></i>{{ $permission->roles()->count() }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $permission->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            @can('permissions.view')
                                                <a class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                    href="{{ route('permissions.show', $permission->id) }}"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="{{ __('messages.view') }}">
                                                    <i class="bx bx-show" style="font-size:1rem;"></i>
                                                </a>
                                            @endcan
                                            @can('permissions.update')
                                                <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                    href="{{ route('permissions.edit', $permission->id) }}"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="{{ __('messages.edit') }}">
                                                    <i class="bx bx-edit" style="font-size:1rem;"></i>
                                                </a>
                                            @endcan
                                            @can('permissions.delete')
                                                <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                    class="d-inline" id="delete-form-{{ $permission->id }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="{{ $permission->id }}" data-name="{{ $permission->name }}"
                                                        style="width:30px;height:30px;padding:0;"
                                                        title="{{ __('messages.delete') }}" type="button">
                                                        <i class="bx bx-trash" style="font-size:1rem;"></i>
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
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bx bx-key" style="font-size:3rem;opacity:.3;"></i>
                <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
            </div>
    @endforelse

    {{-- Pagination Links --}}
    @if (isset($paginatedPermissions) && $paginatedPermissions && $paginatedPermissions->hasPages())
        <div class="card shadow-sm mt-4">
            <div class="card-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="text-muted small">
                    Showing <strong>{{ $paginatedPermissions->firstItem() }}</strong> to
                    <strong>{{ $paginatedPermissions->lastItem() }}</strong> of
                    <strong>{{ $paginatedPermissions->total() }}</strong> permissions
                </div>
                <div>
                    {{ $paginatedPermissions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // -- Delete single
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name');
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
                            url: $(`#delete-form-${id}`).attr('action'),
                            type: 'POST',
                            data: $(`#delete-form-${id}`).serialize(),
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

            // -- Module select-all checkbox
            $(document).on('change', '.module-select-all', function() {
                const $table = $(this).closest('table');
                $table.find('.row-checkbox').prop('checked', this.checked);
                syncBulkBtn();
            });

            // -- Individual row checkboxes
            $(document).on('change', '.row-checkbox', function() {
                syncBulkBtn();
            });

            function syncBulkBtn() {
                const count = $('.row-checkbox:checked').length;
                count > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            // -- Bulk Delete
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: ids.length + ' {{ __('messages.permissions') }}?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route('permissions.bulk-destroy') }}',
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: ids
                            },
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
