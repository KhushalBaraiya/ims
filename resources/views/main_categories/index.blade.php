@extends('layouts.admin')
@section('title', __('messages.main_categories'))

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.main_categories') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.main_categories') }}</li>
                </ol>
            </nav>
        </div>
        @can('main_categories.create')
            <a href="{{ route('main-categories.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('messages.add_category') }}
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
                        <i class="bx bx-category"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-primary">{{ $categories->count() }}</div>
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
                            {{ $categories->where('status', 'active')->count() }}
                        </div>
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
                            {{ $categories->where('status', 'inactive')->count() }}
                        </div>
                        <div class="text-muted small mt-1">{{ __('messages.inactive') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
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
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.category_name') }}</th>
                            <th>{{ __('messages.th_code') }}</th>
                            <th class="text-center">{{ __('messages.sub_categories') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            {{-- <th>{{ __('messages.th_created') }}</th> --}}
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>
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
                                                <small class="d-block text-muted text-truncate" style="max-width:200px;">
                                                    {{ $category->description }}
                                                </small>
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
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1 {{ $category->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;cursor:pointer;" data-id="{{ $category->id }}"
                                            data-status="{{ $category->status }}"
                                            title="{{ __('messages.click_to_toggle') }}">
                                            {{ $category->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </button>
                                    @else
                                        <span
                                            class="badge rounded-pill border fw-semibold px-3 py-1 {{ $category->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                            style="background:transparent;">
                                            {{ $category->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </span>
                                    @endcan
                                </td>
                                {{-- <td class="text-muted small">{{ $category->created_at->format('d M Y') }}</td> --}}
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-outline-secondary rounded-circle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="width:32px;height:32px;padding:0;">
                                            <i class="bx bx-dots-vertical-rounded" style="font-size:1.1rem;"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                            style="min-width:160px;border-radius:10px;">
                                            @can('main_categories.view')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('main-categories.show', $category->id) }}">
                                                        <i class="bx bx-show text-info" style="font-size:1rem;"></i>
                                                        <span>{{ __('messages.view') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('main_categories.update')
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                                        href="{{ route('main-categories.edit', $category->id) }}">
                                                        <i class="bx bx-edit text-primary" style="font-size:1rem;"></i>
                                                        <span>{{ __('messages.edit') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('main_categories.delete')
                                                <li>
                                                    <hr class="dropdown-divider my-1">
                                                </li>
                                                <li>
                                                    <form id="delete-form-{{ $category->id }}"
                                                        action="{{ route('main-categories.destroy', $category->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger delete-btn"
                                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}">
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
            const dt = $('#categoriesTable').DataTable({
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
                    url: `/main-categories/${id}/toggle-status`,
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
                            // ── Update stat cards live ──────────────────
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                            $('#statInactiveCount').text($('.status-toggle-btn.border-danger')
                                .length);
                        } else {
                            showAdminToast(res.message ||
                                '{{ __('messages.error_occurred') }}',
                                'error');
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
