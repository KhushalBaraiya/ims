@extends('layouts.admin')
@section('title', __('messages.sub_category_details') . ' — ' . $subCategory->name)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.sub_category_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('sub-categories.index') }}">{{ __('messages.sub_categories') }}</a></li>
                    <li class="breadcrumb-item active">{{ $subCategory->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('sub_categories.update')
                <a href="{{ route('sub-categories.edit', $subCategory->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- ── Hero Banner ── --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-sitemap text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $subCategory->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-link me-1"></i>{{ $subCategory->slug }}</span>
                    @if ($subCategory->mainCategory)
                        <span>· {{ $subCategory->mainCategory->name }}</span>
                    @endif
                    <span>· {{ $subCategory->products->count() }} {{ __('messages.total_products') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $subCategory->status === 'active' ? 'text-success' : 'text-secondary' }}">
                    <i
                        class="bx {{ $subCategory->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($subCategory->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Sub Category Header Card --}}
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-sitemap me-2 text-success"></i>{{ __('messages.sub_category_details') }}
                    </h6>
                    <span class="badge rounded-pill {{ $subCategory->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $subCategory->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="avatar avatar-xl flex-shrink-0">
                            <span class="avatar-initial rounded-circle bg-label-success"
                                style="width:72px;height:72px;font-size:2rem;">
                                <i class="bx bx-sitemap"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $subCategory->name }}</h4>
                            <code class="text-success">{{ $subCategory->slug }}</code>
                            @if ($subCategory->mainCategory)
                                <p class="text-muted small mb-0 mt-1">
                                    <i class="bx bx-category me-1"></i>
                                    {{ __('messages.main_category') }}:
                                    <strong>{{ $subCategory->mainCategory->name }}</strong>
                                </p>
                            @endif
                            <p class="text-muted small mb-0 mt-1">
                                <i class="bx bx-calendar me-1"></i>
                                {{ __('messages.th_created') }}: {{ $subCategory->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">{{ $subCategory->products->count() }}</div>
                                <div class="text-muted small">{{ __('messages.total_products') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-4 text-success">
                                    {{ $subCategory->products->where('status', 'active')->count() }}
                                </div>
                                <div class="text-muted small">{{ __('messages.active_products') }}</div>
                            </div>
                        </div>
                    </div>

                    @if ($subCategory->description)
                        <div class="border-top pt-3">
                            <p class="text-muted small fw-semibold mb-1">{{ __('messages.description_label') }}</p>
                            <p class="mb-0">{{ $subCategory->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Products --}}
            <div class="card shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-package me-2 text-info"></i>{{ __('messages.recent_products') }}
                        <span class="badge bg-label-info ms-1">{{ $subCategory->products->count() }}</span>
                    </h6>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-info">
                        {{ __('messages.view_all') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @php $recentProducts = $subCategory->products->sortByDesc('created_at')->take(5); @endphp
                    @if ($recentProducts->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('messages.product') }}</th>
                                        <th>{{ __('messages.th_code') }}</th>
                                        <th class="text-center">{{ __('messages.th_status') }}</th>
                                        <th class="text-center">{{ __('messages.th_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentProducts as $p)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($p->image)
                                                        <img src="{{ asset('uploads/products/' . $p->image) }}"
                                                            class="tbl-img" onerror="imgError(this)">
                                                    @else
                                                        <div
                                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                                            <i class="bx bx-package text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <strong>{{ Str::limit($p->name, 35) }}</strong>
                                                </div>
                                            </td>
                                            <td><code class="small text-primary">{{ $p->code }}</code></td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $p->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $p->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('products.show', $p->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="width:100%;text-align:center;padding:2.5rem 0;">
                            <div class="text-muted"
                                style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                <i class="bx bx-package" style="font-size:3rem;opacity:.3;line-height:1;"></i>
                                <span class="small">{{ __('messages.no_records') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Information Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle me-2 text-primary"></i>{{ __('messages.information') }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#{{ $subCategory->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.sub_category_name') }}</span>
                            <span class="fw-bold">{{ $subCategory->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.main_category') }}</span>
                            <span class="badge bg-label-primary">{{ $subCategory->mainCategory->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span
                                class="badge rounded-pill {{ $subCategory->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $subCategory->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.total_products') }}</span>
                            <span class="badge bg-label-info">{{ $subCategory->products->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $subCategory->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $subCategory->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('sub_categories.update')
                        <a href="{{ route('sub-categories.edit', $subCategory->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_sub_category') }}
                        </a>
                    @endcan
                    @if ($subCategory->mainCategory)
                        @can('main_categories.view')
                            <a href="{{ route('main-categories.show', $subCategory->main_category_id) }}"
                                class="btn btn-outline-success">
                                <i class="bx bx-category me-1"></i> {{ __('messages.view_main_category') }}
                            </a>
                        @endcan
                    @endif
                    @can('products.create')
                        <a href="{{ route('products.create') }}" class="btn btn-outline-info">
                            <i class="bx bx-plus me-1"></i> {{ __('messages.add_product') }}
                        </a>
                    @endcan
                    @can('sub_categories.delete')
                        <form id="deleteForm" action="{{ route('sub-categories.destroy', $subCategory->id) }}"
                            method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $subCategory->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_sub_category') }}
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
