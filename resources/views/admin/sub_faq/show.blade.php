@extends('layouts.admin')
@section('title', 'Sub FAQ')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.sub_faqs') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sub-faq.index') }}">{{ __('admin.sub_faqs') }}</a>
                    </li>
                    <li class="breadcrumb-item active">#{{ $subFaq->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sub-faq.edit', $subFaq->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.sub-faq.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-question-mark me-2 text-info"></i>{{ __('admin.sub_faqs') }}</h6>
                    <span
                        class="badge rounded-pill {{ $subFaq->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $subFaq->status }}</span>
                </div>
                <div class="card-body p-4">
                    @if ($subFaq->faq)
                        <div class="mb-3 p-3 rounded-3" style="background:#f0f4ff;">
                            <div class="text-muted small mb-1">{{ __('admin.parent_faq') }}</div><a
                                href="{{ route('admin.faq.show', $subFaq->faq->id) }}"
                                class="fw-semibold text-primary text-decoration-none">{{ $subFaq->faq->question }}</a>
                        </div>
                    @endif
                    <div class="mb-4 p-3 rounded-3" style="background:#fff8f0;border-left:4px solid #ffab00;">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.question') }}</div>
                        <p class="fw-bold mb-0 fs-5">{{ $subFaq->question }}</p>
                    </div>
                    <div class="p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.answer') }}</div>
                        <p class="mb-0" style="white-space:pre-line;line-height:1.7;">{{ $subFaq->answer }}</p>
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
                                class="fw-bold">#{{ $subFaq->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.faqs') }}</span><span
                                class="small">{{ $subFaq->faq->question ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $subFaq->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $subFaq->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $subFaq->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $subFaq->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.sub-faq.edit', $subFaq->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($subFaq->faq)
                        <a href="{{ route('admin.faq.show', $subFaq->faq->id) }}" class="btn btn-outline-primary btn-lg"><i
                                class="bx bx-question-mark me-1"></i>{{ __('admin.view') }} {{ __('admin.faqs') }}</a>
                    @endif
                    <form action="{{ route('admin.sub-faq.destroy', $subFaq->id) }}" method="POST" class="delete-form">
                        @csrf @method('DELETE')
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
