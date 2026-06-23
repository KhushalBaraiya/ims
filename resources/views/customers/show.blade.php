@extends('layouts.admin')
@section('title', __('messages.customer_details') . ' — ' . $customer->name)
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.customer_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('customers.index') }}">{{ __('messages.menu_customers') }}</a></li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('customers.update')
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="d-flex align-items-center justify-content-center bg-label-info rounded-3"
                            style="width:60px;height:60px;flex-shrink:0;">
                            <i class="bx bx-user-pin text-info" style="font-size:1.8rem;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $customer->name }}</h5>
                            <p class="text-muted small mb-1">{{ $customer->phone }}</p>
                            <span
                                class="badge rounded-pill {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $customer->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">{{ __('messages.contact_details') }}</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span
                                class="text-muted fw-semibold">{{ __('messages.phone_label') }}</span><span>{{ $customer->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span
                                class="text-muted fw-semibold">{{ __('messages.th_email') }}</span><span>{{ $customer->email ?: '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span
                                class="text-muted fw-semibold">{{ __('messages.gst_number') }}</span><code>{{ $customer->gst_number ?: '-' }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted fw-semibold">{{ __('messages.opening_balance') }}</span>
                            <strong>{{ format_currency($customer->opening_balance ?? 0) }}</strong>
                        </li>
                        <li class="list-group-item px-4 py-3">
                            <span class="text-muted fw-semibold d-block mb-1">{{ __('messages.address_label') }}</span>
                            <p class="mb-0">{{ $customer->address ?: 'Not provided.' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i
                            class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}</h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('customers.update')
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                        </a>
                    @endcan
                    @can('customers.delete')
                        <form id="deleteForm" action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $customer->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete') }}
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
            Swal.fire({
                title: '{{ __('messages.confirm_delete') }}',
                text: `{{ __('messages.delete') }} "${$(this).data('name')}"?`,
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
