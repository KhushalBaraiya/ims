@extends('layouts.admin')
@section('title', 'Main Categories')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Main Categories</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Main Categories</li>
                </ol>
            </nav>
        </div>
        @can('main_categories.create')
            <a href="{{ route('main-categories.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add Category
            </a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="categoriesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Code</th>
                            <th class="text-center">Status</th>
                            <th>Created</th>
                            <th class="text-center no-sort">Action</th>
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
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $category->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('main_categories.view')
                                            <a href="{{ route('main-categories.show', $category->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @endcan
                                        @can('main_categories.update')
                                            <a href="{{ route('main-categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Edit">
                                                <i class="bx bx-edit"></i>
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
                                                    title="Delete">
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
                    name = $(this).data('name');
                const form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete category "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete!'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(res) {
                                if (res.success) Swal.fire({
                                    title: 'Deleted!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => window.location.reload());
                                else showAdminToast(res.message, 'error');
                            },
                            error: function() {
                                showAdminToast('An error occurred.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
