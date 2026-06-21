@extends('layouts.admin')
@section('title', 'Term & Condition')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.terms_conditions') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.term_condition.index') }}">{{ __('admin.terms_conditions') }}</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($term->title, 40) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.term_condition.edit', $term->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.term_condition.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-file me-2 text-primary"></i>{{ __('admin.terms_conditions') }}</h6>
                    <span
                        class="badge rounded-pill {{ $term->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $term->status }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 p-3 rounded-3" style="background:#f0f4ff;border-left:4px solid #696cff;">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.title') }}</div>
                        <h5 class="fw-bold mb-0">{{ $term->title }}</h5>
                    </div>
                    <div class="p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-2 fw-semibold">{{ __('admin.content') }}</div>
                        <div style="white-space:pre-line;line-height:1.8;">{{ $term->content }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-info-circle me-2 text-primary"></i>{{ __('admin.information') }}</h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">ID</span><span
                                class="fw-bold">#{{ $term->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.title') }}</span><span
                                class="small fw-bold">{{ Str::limit($term->title, 25) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $term->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $term->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $term->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $term->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.term_condition.edit', $term->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <form action="{{ route('admin.term_condition.destroy', $term->id) }}" method="POST"
                        class="delete-form">@csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-lg w-100 btn-delete"><i
                                class="bx bx-trash me-1"></i>{{ __('admin.delete') }}</button>
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
