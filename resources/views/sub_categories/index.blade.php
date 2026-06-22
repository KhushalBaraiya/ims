@extends('layouts.admin')
@section('title', 'Sub Categories')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sub Categories</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sub Categories</li>
                </ol>
            </nav>
        </div>
        @can('sub_categories.create')
            <a href="{{ route('sub-categories.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add Sub Category
            </a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="subCategoriesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Main Category</th>
                            <th>Sub Category</th>
                            <th>Code</th>
                            <th class="text-center">Status</th>
                            <th>Created</th>
                            <th class="text-center no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subCategories as $index => $subCategory)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-muted">{{ $subCategory->mainCategory->name ?? '-' }}</td>
                                <td><strong>{{ $subCategory->name }}</strong></td>
                                <td><code>{{ $subCategory->slug }}</code></td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $subCategory->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($subCategory->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $subCategory->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('sub_categories.view')
                                            <a href="{{ route('sub-categories.show', $subCategory->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @endcan
                                        @can('sub_categories.update')
                                            <a href="{{ route('sub-categories.edit', $subCategory->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endcan
                                        @can('sub_categories.delete')
                                            <form id="delete-form-{{ $subCategory->id }}"
                                                action="{{ route('sub-categories.destroy', $subCategory->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $subCategory->id }}" data-name="{{ $subCategory->name }}"
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
            $('#subCategoriesTable').DataTable({
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
                    text: `Delete "${name}"?`,
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
