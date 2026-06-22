@extends('layouts.admin')
@section('title', 'Customers')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Customers</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Customers</li>
                </ol>
            </nav>
        </div>
        @can('customers.create')
            <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add Customer</a>
        @endcan
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="customersTable" style="width:100%">
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
                        @foreach ($customers as $index => $customer)
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td class="fw-semibold">{{ $customer->phone }}</td>
                                <td class="text-muted">{{ $customer->email ?: '-' }}</td>
                                <td class="text-center"><span
                                        class="badge rounded-pill {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($customer->status) }}</span>
                                </td>
                                <td class="text-muted small">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @can('customers.view')
                                            <a href="{{ route('customers.show', $customer->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="View"><i class="bx bx-show"></i></a>
                                        @endcan
                                        @can('customers.update')
                                            <a href="{{ route('customers.edit', $customer->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="Edit"><i class="bx bx-edit"></i></a>
                                        @endcan
                                        @can('customers.delete')
                                            <form id="delete-form-{{ $customer->id }}"
                                                action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
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
            $('#customersTable').DataTable({
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
                        confirmButtonText: 'Yes!'
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
