@extends('layouts.admin')
@section('title', 'Sub Category — ' . $subCategory->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sub Category Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sub-categories.index') }}">Sub Categories</a></li>
                    <li class="breadcrumb-item active">{{ $subCategory->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('sub_categories.update')
                <a href="{{ route('sub-categories.edit', $subCategory->id) }}" class="btn btn-primary"><i
                        class="bx bx-edit me-1"></i> Edit</a>
            @endcan
            <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-secondary"><i
                    class="bx bx-arrow-back me-1"></i> Back</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">General Information</h6>
                    <span
                        class="badge rounded-pill {{ $subCategory->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($subCategory->status) }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted fw-semibold">Main Category</span>
                            <strong>{{ $subCategory->mainCategory->name ?? '-' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted fw-semibold">Name</span>
                            <strong>{{ $subCategory->name }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted fw-semibold">Code</span>
                            <code>{{ $subCategory->slug }}</code>
                        </li>
                        <li class="list-group-item px-4 py-3">
                            <span class="text-muted fw-semibold d-block mb-1">Description</span>
                            <p class="mb-0">{{ $subCategory->description ?: 'No description.' }}</p>
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
                    @can('sub_categories.update')
                        <a href="{{ route('sub-categories.edit', $subCategory->id) }}" class="btn btn-primary"><i
                                class="bx bx-edit me-1"></i> Edit</a>
                    @endcan
                    @can('sub_categories.delete')
                        <form id="deleteForm" action="{{ route('sub-categories.destroy', $subCategory->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $subCategory->name }}"><i class="bx bx-trash me-1"></i> Delete</button>
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
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endpush
