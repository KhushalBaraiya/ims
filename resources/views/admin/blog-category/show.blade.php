@extends('layouts.admin')
@section('title', 'Blog Category — ' . $category->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.blog') }} {{ __('admin.categories') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.blog-category.index') }}">{{ __('admin.categories') }}</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blog-category.edit', $category->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.blog-category.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                            style="width:80px;height:80px;"><i class="bx bx-news text-primary"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold mb-0">{{ $category->name }}</h3>
                                <span
                                    class="badge rounded-pill {{ $category->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $category->status }}</span>
                            </div>
                            <p class="text-muted mb-1 small"><i class="bx bx-link me-1"></i>Slug:
                                <code>{{ $category->slug }}</code></p>
                            <p class="text-muted mb-0 small"><i class="bx bx-news me-1"></i>{{ $category->blogs_count }}
                                {{ __('admin.all_blogs') }}</p>
                        </div>
                    </div>
                    @if ($category->description)
                        <div class="mt-4 p-3 rounded-3 bg-light">
                            <div class="text-muted small mb-1">{{ __('admin.description') }}</div>
                            <p class="mb-0">{{ $category->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i class="bx bx-news me-2 text-info"></i>{{ __('admin.all_blogs') }}
                        <span class="badge bg-label-info ms-2">{{ $category->blogs_count }}</span></h6>
                    <a href="{{ route('admin.blog.index') }}"
                        class="btn btn-sm btn-outline-info">{{ __('admin.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    @if ($category->blogs->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('admin.title') }}</th>
                                        <th>{{ __('admin.author') }}</th>
                                        <th class="text-center">{{ __('admin.status') }}</th>
                                        <th class="text-center">{{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->blogs as $blog)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($blog->image)
                                                        <img src="{{ asset('uploads/blog/' . $blog->image) }}"
                                                        class="tbl-img" onerror="imgError(this)">@else<div
                                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                                            <i class="bx bx-news text-muted"></i></div>
                                                    @endif
                                                    <strong>{{ Str::limit($blog->title, 35) }}</strong>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-label-secondary">{{ $blog->author }}</span></td>
                                            <td class="text-center"><span
                                                    class="badge rounded-pill {{ $blog->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $blog->status }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.blog.show', $blog->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-1 btn-action"><i
                                                        class="bx bx-show"></i></a>
                                                <a href="{{ route('admin.blog.edit', $blog->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"><i
                                                        class="bx bx-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else<div class="text-center py-5 text-muted"><i class="bx bx-news"
                                style="font-size:3rem;opacity:.3;"></i>
                            <p class="mt-2">{{ __('admin.no_data') }}</p>
                        </div>
                    @endif
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
                                class="fw-bold">#{{ $category->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.name') }}</span><span
                                class="fw-bold">{{ $category->name }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">Slug</span><code
                                class="small">{{ $category->slug }}</code></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $category->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $category->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.all_blogs') }}</span><span
                                class="badge bg-label-info">{{ $category->blogs_count }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $category->created_at->format('d M Y') }}</span></li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.updated') }}</span><span
                                class="small">{{ $category->updated_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.blog-category.edit', $category->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-outline-info btn-lg"><i
                            class="bx bx-plus me-1"></i>{{ __('admin.add') }} {{ __('admin.blog') }}</a>
                    <form action="{{ route('admin.blog-category.destroy', $category->id) }}" method="POST"
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
