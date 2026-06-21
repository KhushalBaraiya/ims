@extends('layouts.admin')
@section('title', 'Blog — ' . $blog->title)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.blog') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">{{ __('admin.all_blogs') }}</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($blog->title, 40) }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                @if ($blog->image)
                    <img src="{{ asset('uploads/blog/' . $blog->image) }}" class="card-img-top"
                        style="max-height:320px;object-fit:cover;" onerror="imgError(this)">
                @endif
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <span
                            class="badge rounded-pill {{ $blog->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $blog->status }}</span>
                        @if ($blog->category)
                            <span class="badge bg-label-primary px-2 py-1">{{ $blog->category->name }}</span>
                        @endif
                        <span class="badge bg-label-secondary px-2 py-1"><i
                                class="bx bx-user me-1"></i>{{ $blog->author }}</span>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $blog->title }}</h3>
                    <p class="text-muted small mb-1"><i class="bx bx-link me-1"></i>Slug: <code>{{ $blog->slug }}</code>
                    </p>
                    <p class="text-muted small mb-3"><i
                            class="bx bx-calendar me-1"></i>{{ $blog->created_at->format('d M Y, h:i A') }}</p>
                    @if ($blog->short_description)
                        <div class="p-3 rounded-3 mb-3" style="background:#f0f4ff;">
                            <div class="text-muted small mb-1 fw-semibold">{{ __('admin.short_description') }}</div>
                            <p class="mb-0">{{ $blog->short_description }}</p>
                        </div>
                    @endif
                    <div class="p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-2 fw-semibold">{{ __('admin.content') }}</div>
                        <div style="white-space:pre-line;line-height:1.7;">{{ $blog->content }}</div>
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
                                class="fw-bold">#{{ $blog->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.author') }}</span><span
                                class="fw-bold">{{ $blog->author }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.category') }}</span><span
                                class="small">{{ $blog->category->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $blog->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $blog->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $blog->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $blog->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($blog->category)
                        <a href="{{ route('admin.blog-category.show', $blog->category->id) }}"
                            class="btn btn-outline-primary btn-lg"><i class="bx bx-folder me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.category') }}</a>
                    @endif
                    <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="delete-form">@csrf
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
