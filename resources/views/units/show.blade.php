@extends('layouts.admin')
@section('title', __('messages.menu_units') . ' — ' . $unit->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.unit_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">{{ __('messages.menu_units') }}</a></li>
                    <li class="breadcrumb-item active">{{ $unit->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('units.update')
                <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center justify-content-center bg-label-primary rounded-3"
                            style="width:80px;height:80px;flex-shrink:0;">
                            <i class="bx bx-ruler text-primary" style="font-size:2.2rem;"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold mb-0">{{ $unit->name }}</h4>
                                <span
                                    class="badge rounded-pill {{ $unit->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bx bx-code me-1"></i> {{ __('messages.short_name') }}:
                                <code>{{ $unit->short_name }}</code>
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bx bx-calendar me-1"></i> {{ $unit->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small fw-semibold">ID</span>
                        <span class="fw-bold">#{{ $unit->id }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small fw-semibold">{{ __('messages.unit_name') }}</span>
                        <span class="fw-bold">{{ $unit->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small fw-semibold">{{ __('messages.short_name') }}</span>
                        <code>{{ $unit->short_name }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small fw-semibold">{{ __('messages.status') }}</span>
                        <span class="badge rounded-pill {{ $unit->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($unit->status) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                        <span class="small">{{ $unit->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                        <span class="text-muted small fw-semibold">Updated</span>
                        <span class="small">{{ $unit->updated_at->format('d M Y') }}</span>
                    </li>
                </ul>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('units.update')
                        <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                        </a>
                    @endcan
                    @can('units.delete')
                        <form id="deleteForm" action="{{ route('units.destroy', $unit->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $unit->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete') }}
                            </button>
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
            const name = $(this).data('name');
            Swal.fire({
                title: '{{ __('messages.confirm_delete') }}',
                text: `{{ __('messages.delete') }} "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('messages.yes_delete') }}'
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endpush
