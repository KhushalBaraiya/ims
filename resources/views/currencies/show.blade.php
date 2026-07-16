@extends('layouts.admin')
@section('title', __('messages.currency_details') . ' — ' . $currency->name)

@section('content')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.currency_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('currencies.index') }}">{{ __('messages.menu_currencies') }}</a></li>
                    <li class="breadcrumb-item active">{{ $currency->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('currencies.update')
                <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('currencies.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- Hero Banner --}}
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #696cff, #9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2);">
                <span class="text-white fw-bold fs-5">{{ $currency->symbol }}</span>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $currency->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-3 mt-1">
                    <span><i class="bx bx-code me-1"></i>{{ $currency->code }}</span>
                    <span><i class="bx bx-transfer me-1"></i>1 {{ $currency->code }} =
                        {{ number_format($currency->exchange_rate, 4) }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($currency->is_default)
                    <span class="badge bg-warning text-dark fw-semibold px-3 py-2">
                        <i class="bx bx-star me-1"></i>{{ __('messages.th_default') }}
                    </span>
                @endif
                <span
                    class="badge bg-white fw-semibold {{ $currency->status === 'active' ? 'text-success' : 'text-secondary' }} px-3 py-2">
                    <i class="bx {{ $currency->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>
                    {{ $currency->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-dollar me-2 text-primary"></i>{{ __('messages.currency_details') }}
                    </h6>
                    <span class="badge rounded-pill {{ $currency->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $currency->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">

                    {{-- Symbol display --}}
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="flex-shrink-0">
                            <div class="avatar-initial rounded-circle bg-label-primary d-flex align-items-center justify-content-center"
                                style="width:72px;height:72px;font-size:1.8rem;font-weight:700;">
                                {{ $currency->symbol }}
                            </div>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $currency->name }}</h4>
                            <code class="text-primary fs-6">{{ $currency->code }}</code>
                            @if ($currency->is_default)
                                <span class="badge bg-label-warning ms-2">
                                    <i class="bx bx-star me-1"></i>{{ __('messages.th_default') }}
                                </span>
                            @endif
                            <p class="text-muted small mb-0 mt-1">
                                <i class="bx bx-calendar me-1"></i>
                                {{ __('messages.th_created') }}: {{ $currency->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">{{ $currency->symbol }}</div>
                                <div class="text-muted small">{{ __('messages.currency_symbol_label') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 text-center bg-label-info">
                                <div class="fw-bold fs-4 text-info">{{ $currency->code }}</div>
                                <div class="text-muted small">{{ __('messages.currency_code') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-4 text-success">{{ number_format($currency->exchange_rate, 4) }}
                                </div>
                                <div class="text-muted small">{{ __('messages.exchange_rate') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Exchange rate info --}}
                    <div class="border rounded-3 p-3 bg-light">
                        <p class="text-muted small fw-semibold mb-1">
                            <i class="bx bx-info-circle me-1"></i>{{ __('messages.exchange_rate') }}
                        </p>
                        <p class="mb-0">
                            1 {{ $currency->code }} = <strong>{{ number_format($currency->exchange_rate, 4) }}</strong>
                        </p>
                    </div>

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
                            <span class="fw-bold">#{{ $currency->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.currency_name') }}</span>
                            <span class="fw-bold">{{ $currency->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.currency_code') }}</span>
                            <code class="text-primary">{{ $currency->code }}</code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.currency_symbol_label') }}</span>
                            <span class="fw-bold fs-5">{{ $currency->symbol }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.exchange_rate') }}</span>
                            <span class="fw-semibold">{{ number_format($currency->exchange_rate, 4) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_default') }}</span>
                            @if ($currency->is_default)
                                <span class="badge bg-label-warning"><i
                                        class="bx bx-star me-1"></i>{{ __('messages.yes') }}</span>
                            @else
                                <span class="text-muted small">{{ __('messages.no') }}</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span
                                class="badge rounded-pill {{ $currency->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $currency->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $currency->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $currency->updated_at->format('d M Y') }}</span>
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
                    @can('currencies.update')
                        <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_currency') }}
                        </a>
                        @if (!$currency->is_default)
                            <button type="button" class="btn btn-outline-warning set-default-btn"
                                data-id="{{ $currency->id }}">
                                <i class="bx bx-star me-1"></i> {{ __('messages.set_default') }}
                            </button>
                        @endif
                    @endcan
                    @can('currencies.delete')
                        <form id="deleteForm" action="{{ route('currencies.destroy', $currency->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $currency->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_currency') }}
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
        // Set default
        $(document).on('click', '.set-default-btn', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: '{{ __('messages.set_default_q') }}',
                text: '{{ __('messages.set_default_text') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __('messages.yes') }}!',
                cancelButtonText: '{{ __('messages.cancel') }}'
            }).then((r) => {
                if (r.isConfirmed) {
                    $.ajax({
                        url: `/currencies/${id}/set-default`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                showAdminToast('{{ __('messages.default_changed') }}',
                                    'success');
                                setTimeout(() => window.location.reload(), 800);
                            } else {
                                showAdminToast(res.message, 'error');
                            }
                        },
                        error: function() {
                            showAdminToast('{{ __('messages.error_occurred') }}', 'error');
                        }
                    });
                }
            });
        });

        // Delete
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
                if (r.isConfirmed) {
                    $.ajax({
                        url: document.getElementById('deleteForm').action,
                        type: 'POST',
                        data: $('form#deleteForm').serialize(),
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    title: '{{ __('messages.deleted_title') }}',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => window.location.href =
                                    '{{ route('currencies.index') }}');
                            } else {
                                showAdminToast(res.message, 'error');
                            }
                        },
                        error: function() {
                            showAdminToast('{{ __('messages.error_occurred') }}', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
