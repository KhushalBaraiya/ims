@extends('layouts.admin')
@section('title', 'Supplier — ' . $supplier->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Supplier Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('suppliers.update')
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary"><i class="bx bx-edit me-1"></i>
                    Edit</a>
            @endcan
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i>
                Back</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center bg-label-primary rounded-3"
                            style="width:60px;height:60px;flex-shrink:0;"><i class="bx bx-buildings text-primary"
                                style="font-size:1.8rem;"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $supplier->name }}</h5>
                            <p class="text-muted small mb-1">{{ $supplier->company_name ?: 'No company associated' }}</p>
                            <span
                                class="badge rounded-pill {{ $supplier->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $supplier->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">Contact & Tax Details</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3"><span
                                class="text-muted fw-semibold">Phone</span><span>{{ $supplier->phone }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3"><span
                                class="text-muted fw-semibold">Email</span><span>{{ $supplier->email ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3"><span
                                class="text-muted fw-semibold">GST
                                Number</span><code>{{ $supplier->gst_number ?: '-' }}</code></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3"><span
                                class="text-muted fw-semibold">City</span><span>{{ $supplier->city ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3"><span
                                class="text-muted fw-semibold">Opening
                                Balance</span><strong>{{ format_currency($supplier->opening_balance ?? 0) }}</strong></li>
                        <li class="list-group-item px-4 py-3"><span
                                class="text-muted fw-semibold d-block mb-1">Address</span>
                            <p class="mb-0">{{ $supplier->address ?: 'Not provided.' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bx bx-bolt-circle me-2 text-warning"></i>Quick Actions</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('suppliers.update')
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary"><i
                                class="bx bx-edit me-1"></i> Edit</a>
                    @endcan
                    @can('suppliers.delete')
                        <form id="deleteForm" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $supplier->name }}"><i class="bx bx-trash me-1"></i> Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.delete-btn', function() {
            Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete "${$(this).data('name')}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes!'
                })
                .then((r) => {
                    if (r.isConfirmed) document.getElementById('deleteForm').submit();
                });
        });
    </script>
@endpush
