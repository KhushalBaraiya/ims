@extends('layouts.admin')
@section('title', 'Saved Card')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.saved_cards') }} {{ __('admin.details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.save-card.index') }}">{{ __('admin.saved_cards') }}</a></li>
                    <li class="breadcrumb-item active">#{{ $card->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.save-card.edit', $card->id) }}" class="btn btn-primary btn-lg"><i
                    class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
            <a href="{{ route('admin.save-card.index') }}" class="btn btn-outline-secondary btn-lg"><i
                    class="bx bx-arrow-back me-1"></i>{{ __('admin.back') }}</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-credit-card-alt me-2 text-primary"></i>{{ __('admin.saved_cards') }}</h6>
                    <span
                        class="badge rounded-pill {{ $card->status === 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2">{{ $card->status }}</span>
                </div>
                <div class="card-body p-4">
                    {{-- Card Visual --}}
                    <div class="rounded-3 p-4 mb-4 text-white"
                        style="background:linear-gradient(135deg,#1a1f71,#0d6efd);min-height:160px;position:relative;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="fw-bold fs-5">{{ strtoupper($card->card_brand ?? 'CARD') }}</span>
                            <i class="bx bx-chip" style="font-size:2rem;opacity:.7;"></i>
                        </div>
                        <div class="fw-bold fs-4 letter-spacing-2 mb-3">•••• •••• •••• {{ $card->last_four_digits }}</div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div style="font-size:.7rem;opacity:.7;">CARD HOLDER</div>
                                <div class="fw-semibold">{{ strtoupper($card->card_holder_name) }}</div>
                            </div>
                            <div>
                                <div style="font-size:.7rem;opacity:.7;">EXPIRES</div>
                                <div class="fw-semibold">
                                    {{ str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->expiry_year }}</div>
                            </div>
                        </div>
                    </div>
                    {{-- User --}}
                    <div class="p-3 rounded-3 bg-light">
                        <div class="text-muted small mb-2 fw-semibold">{{ __('admin.user') }}</div>
                        <div class="d-flex align-items-center gap-3">
                            @if ($card->user && $card->user->image)
                                <img src="{{ asset($card->user->image) }}" class="rounded-circle flex-shrink-0"
                                    style="width:44px;height:44px;object-fit:cover;">
                            @else<div
                                    class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                                    style="width:44px;height:44px;font-size:1.1rem;font-weight:700;color:#696cff;">
                                    {{ strtoupper(substr($card->user->name ?? 'U', 0, 1)) }}</div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0">{{ $card->user->name ?? '—' }}</h6>
                                <p class="text-muted small mb-0">{{ $card->user->email ?? '' }}</p>
                            </div>
                            @if ($card->user)
                                <a href="{{ route('admin.user.show', $card->user->id) }}"
                                    class="btn btn-sm btn-outline-primary"><i
                                        class="bx bx-show me-1"></i>{{ __('admin.view') }}</a>
                            @endif
                        </div>
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
                                class="fw-bold">#{{ $card->id }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.card_holder') }}</span><span
                                class="fw-bold">{{ $card->card_holder_name }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.card_number') }}</span><span
                                class="fw-bold">•••• {{ $card->last_four_digits }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.expiry') }}</span><span
                                class="small">{{ str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->expiry_year }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.card_brand') }}</span><span
                                class="badge bg-label-primary">{{ $card->card_brand ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.user') }}</span><span
                                class="small">{{ $card->user->name ?? '—' }}</span></li>
                        <li class="d-flex justify-content-between py-2 border-bottom"><span
                                class="text-muted small fw-semibold">{{ __('admin.status') }}</span><span
                                class="badge rounded-pill {{ $card->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $card->status }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2"><span
                                class="text-muted small fw-semibold">{{ __('admin.created') }}</span><span
                                class="small">{{ $card->created_at->format('d M Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold fs-5"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <a href="{{ route('admin.save-card.edit', $card->id) }}" class="btn btn-primary btn-lg"><i
                            class="bx bx-edit me-1"></i>{{ __('admin.edit') }}</a>
                    @if ($card->user)
                        <a href="{{ route('admin.user.show', $card->user->id) }}" class="btn btn-outline-primary btn-lg"><i
                                class="bx bx-user me-1"></i>{{ __('admin.view') }} {{ __('admin.user') }}</a>
                    @endif
                    <form action="{{ route('admin.save-card.destroy', $card->id) }}" method="POST" class="delete-form">
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
