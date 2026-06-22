@extends('layouts.admin')
@section('title', 'Suppliers')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Suppliers</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Suppliers</li>
                </ol>
            </nav>
        </div>
        @can('suppliers.create')
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add Supplier</a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="suppliersTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th class="text-center">Status</th>
                            <th>Created</th>
                            <th class="text-center no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $index => $supplier)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $supplier->name }}</strong>
                                    @if ($supplier->company_name)
                                        <small class="d-block text-muted">{{ $supplier->company_name }}</small>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $supplier->phone }}</td>
                                <td class="text-muted">{{ $supplier->email ?: '-' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $supplier->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $supplier->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('suppliers.view')
                                            <a href="{{ route('suppliers.show', $supplier->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="View"><i class="bx bx-show"></i></a>
                                        @endcan
                                        @can('suppliers.update')
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Edit"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('suppliers.delete')
                                            <form id="delete-form-{{ $supplier->id }}"
                                                action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                                    title="Delete"><i class="bx bx-trash"></i></button>
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
            $('#suppliersTable').DataTable({
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
                        title: 'Are you sure?',
                        text: `Delete "${name}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete!'
                    })
                    .then((r) => {
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
