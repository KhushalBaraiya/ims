@extends('layouts.admin')
@section('title', 'FAQ')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.faqs') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}">{{ __('admin.faqs') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $faq->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-question-mark me-2 text-primary"></i>{{ __('admin.faqs') }}</h6>
                    <span
                        class="badge rounded-pill {{ $faq->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $faq->status }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 p-3 rounded-3" style="background:#f0f4ff;border-left:4px solid #696cff;">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.question') }}</div>
                        <p class="fw-bold mb-0 fs-5">{{ $faq->question }}</p>
                    </div>
                    <div class="p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-1 fw-semibold">{{ __('admin.answer') }}</div>
                        <p class="mb-0" style="white-space:pre-line;line-height:1.7;">{{ $faq->answer }}</p>
                    </div>
                </div>
            </div>
            @if ($faq->subFaqs->count())
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-list-ul me-2 text-info"></i>{{ __('admin.sub_faqs') }} <span
                                class="badge bg-label-info ms-2">{{ $faq->subFaqs->count() }}</span></h6>
                        <a href="{{ route('admin.sub-faq.create') }}" class="btn btn-sm btn-outline-info"><i
                                class="bx bx-plus me-1"></i>{{ __('admin.add') }}</a>
                    </div>
                    <div class="card-body p-0">
                        @foreach ($faq->subFaqs as $i => $sub)
                            <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <span
                                        class="badge bg-label-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:28px;height:28px;">{{ $i + 1 }}</span>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-1">{{ $sub->question }}</div>
                                        <p class="text-muted small mb-1">{{ $sub->answer }}</p>
                                        <span
                                            class="badge rounded-pill {{ $sub->status === 'active' ? 'bg-success' : 'bg-danger' }}"
                                            style="font-size:.7rem;">{{ $sub->status }}</span>
                                    </div>
                                    <a href="{{ route('admin.sub-faq.edit', $sub->id) }}"
                                        class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"><i
                                            class="bx bx-edit"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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
                                class="fw-bold">#{{ $faq->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.sub_faqs') }}</span><span
                                class="badge bg-label-info">{{ $faq->subFaqs->count() }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $faq->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $faq->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $faq->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $faq->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <a href="{{ route('admin.sub-faq.create') }}" class="btn btn-outline-info btn-lg"><i
                            class="bx bx-plus me-1"></i>{{ __('admin.add') }} {{ __('admin.sub_faqs') }}</a>
                    <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" class="delete-form">@csrf
                        @method('DELETE')
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
