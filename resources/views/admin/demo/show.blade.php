@extends('layouts.admin')
@section('title', 'Demo — ' . $demo->name)
@section('content')

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Demo {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.demo.index') }}">Demo</a></li>
                    <li class="breadcrumb-item active">{{ $demo->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.demo.edit', $demo->id) }}" class="btn btn-primary btn-lg">
                <i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.demo.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ===== LEFT col-lg-8 ===== --}}
        <div class="col-lg-8">

            {{-- Profile Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i class="bx bx-user me-2 text-primary"></i>{{ __('admin.profile') }}
                    </h6>
                    <span
                        class="badge rounded-pill {{ $demo->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                        {{ $demo->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="flex-shrink-0">
                            @if ($demo->image)
                                <img src="{{ asset('uploads/demo/' . $demo->image) }}"
                                    class="rounded-circle object-fit-cover border" style="width:90px;height:90px;"
                                    onerror="imgError(this)">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary"
                                    style="width:90px;height:90px;">
                                    <span class="fw-bold text-primary" style="font-size:2rem;">
                                        {{ strtoupper(substr($demo->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">{{ $demo->name }}</h3>
                            <p class="text-muted mb-1 small"><i class="bx bx-envelope me-1"></i>{{ $demo->email }}</p>
                            <p class="text-muted mb-0 small"><i class="bx bx-phone me-1"></i>{{ $demo->phone }}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.email') }}</div>
                                <div class="fw-bold">{{ $demo->email }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.phone') }}</div>
                                <div class="fw-bold">{{ $demo->phone }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.gender') }}</div>
                                <div class="fw-bold text-capitalize">{{ $demo->gender ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light">
                                <div class="text-muted small mb-1">{{ __('admin.status') }}</div>
                                <div>
                                    @if ($demo->status === 'active')
                                        <span class="badge bg-success px-3 py-2">{{ __('admin.active') }}</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">{{ __('admin.inactive') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($demo->address)
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted small mb-1">{{ __('admin.address') }}</div>
                                    <div class="fw-bold">{{ $demo->address }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT col-lg-4 ===== --}}
        <div class="col-lg-4">

            {{-- Information --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $demo->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.name') }}</span>
                            <span class="fw-bold">{{ $demo->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.gender') }}</span>
                            <span class="small text-capitalize">{{ $demo->gender ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.status') }}</span>
                            <span class="badge rounded-pill {{ $demo->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $demo->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('admin.created') }}</span>
                            <span class="small">{{ $demo->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('admin.updated') }}</span>
                            <span class="small">{{ $demo->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.demo.edit', $demo->id) }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-edit me-1"></i>{{ __('admin.edit') }}
                    </a>
                    <form action="{{ route('admin.demo.destroy', $demo->id) }}" method="POST" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete">
                            <i class="bx bx-trash me-1"></i>{{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).on('click', '.btn-delete', function() {
            const form = $(this).closest('.delete-form');
            Swal.fire({
                title: '{{ __('admin.swal_are_you_sure') }}',
                text: '{{ __('admin.swal_delete_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('admin.swal_yes_delete') }}',
                cancelButtonText: '{{ __('admin.swal_cancel') }}'
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        });
    </script>
@endpush
