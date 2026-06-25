@extends('layouts.admin')
@section('title', __('messages.unit_details') . ' — ' . $unit->name)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.unit_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">{{ __('messages.units') }}</a></li>
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

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Unit Header Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-ruler me-2 text-warning"></i>{{ __('messages.unit_details') }}
                    </h6>
                    <span class="badge rounded-pill {{ $unit->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $unit->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="avatar avatar-xl flex-shrink-0">
                            <span class="avatar-initial rounded-circle bg-label-warning"
                                style="width:72px;height:72px;font-size:2rem;">
                                <i class="bx bx-ruler"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $unit->name }}</h4>
                            <code class="text-warning">{{ $unit->short_name }}</code>
                            <p class="text-muted small mb-0 mt-1">
                                <i class="bx bx-calendar me-1"></i>
                                {{ __('messages.th_created') }}: {{ $unit->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center bg-label-warning">
                                <div class="fw-bold fs-4 text-warning">{{ $unit->short_name }}</div>
                                <div class="text-muted small">{{ __('messages.short_name') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">
                                    {{ $unit->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                </div>
                                <div class="text-muted small">{{ __('messages.th_status') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Information Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $unit->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.unit_name') }}</span>
                            <span class="fw-bold">{{ $unit->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.short_name') }}</span>
                            <code class="text-warning">{{ $unit->short_name }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span class="badge rounded-pill {{ $unit->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $unit->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $unit->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $unit->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('units.update')
                        <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_unit') }}
                        </a>
                    @endcan
                    @can('units.create')
                        <a href="{{ route('units.create') }}" class="btn btn-outline-success">
                            <i class="bx bx-plus me-1"></i> {{ __('messages.add_unit') }}
                        </a>
                    @endcan
                    @can('units.delete')
                        <form id="deleteForm" action="{{ route('units.destroy', $unit->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $unit->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_unit') }}
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
                confirmButtonText: '{{ __('messages.yes_delete') }}',
                cancelButtonText: '{{ __('messages.cancel') }}'
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
@endpush
