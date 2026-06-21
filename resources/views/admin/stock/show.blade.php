@extends('layouts.admin')
@section('title', 'Stock')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.stock') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.stock.index') }}">{{ __('admin.stock') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $stock->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stock.edit', $stock->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.stock.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    @php $typeColor=['in'=>'success','out'=>'danger','adjustment'=>'warning']; @endphp
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-{{ $typeColor[$stock->type] ?? 'primary' }} flex-shrink-0"
                            style="width:80px;height:80px;"><i
                                class="bx {{ $stock->type === 'in' ? 'bx-trending-up' : ($stock->type === 'out' ? 'bx-trending-down' : 'bx-transfer') }} text-{{ $typeColor[$stock->type] ?? 'primary' }}"
                                style="font-size:2.5rem;"></i></div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h4 class="fw-bold mb-0">{{ __('admin.stock') }} #{{ $stock->id }}</h4>
                                <span
                                    class="badge bg-{{ $typeColor[$stock->type] ?? 'secondary' }} px-3 py-2">{{ strtoupper($stock->type) }}</span>
                                <span
                                    class="badge rounded-pill {{ $stock->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $stock->status }}</span>
                            </div>
                            <p class="text-muted mb-0 small"><i class="bx bx-calendar me-1"></i>{{ $stock->date }}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0f4ff;">
                                <div class="fw-bold fs-2 text-primary">{{ $stock->quantity }}</div>
                                <div class="text-muted small">{{ __('admin.quantity') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#f0fff4;">
                                <div class="fw-bold fs-3 text-success">₹{{ number_format($stock->price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.price') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 rounded-3 text-center" style="background:#fff8f0;">
                                <div class="fw-bold fs-3 text-warning">₹{{ number_format($stock->total_price, 0) }}</div>
                                <div class="text-muted small">{{ __('admin.total') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($stock->product)
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold fs-5"><i
                                class="bx bx-package me-2 text-info"></i>{{ __('admin.product') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            @if (!empty($stock->product->images))
                                <img src="{{ asset('uploads/products/' . $stock->product->images[0]) }}"
                                    class="tbl-img rounded-3" onerror="imgError(this)">
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $stock->product->name }}</h6>
                                <p class="text-muted small mb-0">₹{{ number_format($stock->product->price, 0) }} &bull; Qty:
                                    {{ $stock->product->quantity }}</p>
                            </div>
                            <a href="{{ route('admin.product.show', $stock->product->id) }}"
                                class="btn btn-sm btn-outline-info"><i
                                    class="bx bx-show me-1"></i>{{ __('admin.view') }}</a>
                        </div>
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
                                class="fw-bold">#{{ $stock->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.product') }}</span><span
                                class="small">{{ Str::limit($stock->product->name ?? '—', 20) }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.type') }}</span><span
                                class="badge bg-{{ $typeColor[$stock->type] ?? 'secondary' }}">{{ strtoupper($stock->type) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.date') }}</span><span
                                class="small">{{ $stock->date }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $stock->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $stock->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $stock->created_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.stock.edit', $stock->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($stock->product)
                        <a href="{{ route('admin.product.show', $stock->product->id) }}"
                            class="btn btn-outline-info btn-lg"><i class="bx bx-package me-1"></i>{{ __('admin.view') }}
                            {{ __('admin.product') }}</a>
                    @endif
                    <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST" class="delete-form">@csrf
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
