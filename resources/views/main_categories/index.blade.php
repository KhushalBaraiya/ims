@extends('layouts.admin')
@section('title', __('messages.main_categories'))

@section('content')

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

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="categoriesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.category_name') }}</th>
                            <th>{{ __('messages.th_code') }}</th>
                            <th class="text-center">{{ __('messages.th_status') }}</th>
                            <th>{{ __('messages.th_created') }}</th>
                            <th class="text-center no-sort">{{ __('messages.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $category->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $category->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('main_categories.view')
                                            <a href="{{ route('main-categories.show', $category->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="{{ __('messages.view') }}"><i class="bx bx-show"></i></a>
                                        @endcan
                                        @can('main_categories.update')
                                            <a href="{{ route('main-categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="{{ __('messages.edit') }}"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('main_categories.delete')
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('main-categories.destroy', $category->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                    title="{{ __('messages.delete') }}"><i class="bx bx-trash"></i></button>
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
                }]
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
                            success: function(res) {
                                if (res.success) Swal.fire({
                                    title: '{{ __('messages.deleted_title') }}',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => window.location.reload());
                                else showAdminToast(res.message, 'error');
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
