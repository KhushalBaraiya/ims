@extends('layouts.admin')
@section('title', __('messages.main_categories'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.main_categories') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.main_categories') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            @can('main_categories.delete')
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> {{ __('messages.delete_multiples') }}
                </button>
            @endcan
            @can('main_categories.create')
                <a href="{{ route('main-categories.create') }}" class="btn btn-outline-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('messages.add_category') }}
                </a>
            @endcan
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filters') }}</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('main-categories.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="{{ request('search') }}" placeholder="Category name or code...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.status') }}</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_statuses') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                {{ __('messages.active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('main-categories.index') }}" class="btn btn-outline-secondary btn-sm"
                            title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-primary flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-category"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-primary">{{ $categories->count() }}</div>
                        <div class="text-muted small mt-1">{{ __('messages.th_total') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-success flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-success" id="statActiveCount">
                            {{ $categories->where('status', 'active')->count() }}
                        </div>
                        <div class="text-muted small mt-1">{{ __('messages.active') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-danger flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-danger" id="statInactiveCount">
                            {{ $categories->where('status', 'inactive')->count() }}
                        </div>
                        <div class="text-muted small mt-1">{{ __('messages.inactive') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-info flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-sitemap"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-info">
                            <a href="{{ route('sub-categories.index') }}" class="text-info text-decoration-none">
                                {{ \App\Models\SubCategory::count() }}
                            </a>
                        </div>
                        <div class="text-muted small mt-1">{{ __('messages.sub_categories') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="categoriesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.category_name') }}</th>
                            <th>{{ __('messages.th_code') }}</th>
                            <th class="text-center">{{ __('messages.sub_categories') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="{{ $category->id }}"></td>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class="bx bx-category" style="font-size:1rem;"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $category->name }}</strong>
                                            @if ($category->description)
                                                <small class="d-block text-muted text-truncate"
                                                    style="max-width:220px;">{{ $category->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-primary">{{ $category->slug }}</code></td>
                                <td class="text-center">
                                    <span class="badge bg-label-success">{{ $category->subCategories->count() }}</span>
                                </td>
                                <td class="text-center">
                                    @can('main_categories.update')
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1
                                                {{ $category->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;cursor:pointer;" data-id="{{ $category->id }}"
                                            data-status="{{ $category->status }}"
                                            title="{{ __('messages.click_to_toggle') }}">
                                            {{ $category->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </button>
                                    @else
                                        <span
                                            class="badge rounded-pill border fw-semibold px-3 py-1
                                            {{ $category->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;">
                                            {{ $category->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @can('main_categories.view')
                                            <a href="{{ route('main-categories.show', $category->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('main_categories.update')
                                            <a href="{{ route('main-categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        @endcan
                                        @can('main_categories.delete')
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('main-categories.destroy', $category->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                    title="{{ __('messages.delete') }}"
                                                    style="width:30px;height:30px;padding:0;">
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

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#categoriesTable').DataTable({
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-category" style="font-size:3rem;opacity:.3;line-height:1;"></i>{{ __('messages.no_records') }}</div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            $(document).on('click', '.status-toggle-btn', function() {
                const btn = $(this),
                    id = btn.data('id'),
                    cur = btn.data('status');
                $.ajax({
                    url: `/main-categories/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: () => btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span>'),
                    success: (res) => {
                        btn.prop('disabled', false);
                        if (res.success) {
                            btn.data('status', res.status)
                                .removeClass(
                                    'border-success text-success border-danger text-danger')
                                .addClass(res.status === 'active' ?
                                    'border-success text-success' : 'border-danger text-danger')
                                .text(res.status === 'active' ? '{{ __('messages.active') }}' :
                                    '{{ __('messages.inactive') }}');
                            showAdminToast(res.message, 'success');
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                            $('#statInactiveCount').text($('.status-toggle-btn.border-danger')
                                .length);
                        } else {
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
                }).then(r => {
                    if (!r.isConfirmed) return;
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: res => {
                            if (res.success) Swal.fire({
                                title: '{{ __('messages.deleted_title') }}',
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#696cff'
                            }).then(() => location.reload());
                            else showAdminToast(res.message, 'error');
                        },
                        error: () => showAdminToast('{{ __('messages.error_occurred') }}',
                            'error')
                    });
                });
            });

            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleBulk();
            });
            $(document).on('change', '.row-checkbox', function() {
                $('#selectAll').prop('checked', !$('.row-checkbox:not(:checked)').length);
                toggleBulk();
            });

            function toggleBulk() {
                const c = $('.row-checkbox:checked').length;
                c > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '{{ __('messages.confirm_delete') }}',
                    text: `Delete ${ids.length} item(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: '{{ __('messages.cancel') }}'
                }).then(r => {
                    if (!r.isConfirmed) return;
                    $.ajax({
                        url: '{{ route('main-categories.bulk-destroy') }}',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: res => {
                            if (res.success) Swal.fire({
                                title: '{{ __('messages.deleted_title') }}',
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#696cff'
                            }).then(() => location.reload());
                            else showAdminToast(res.message, 'error');
                        },
                        error: () => showAdminToast('{{ __('messages.error_occurred') }}',
                            'error')
                    });
                });
            });
        });
    </script>
@endpush
