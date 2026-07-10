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

    {{-- ── Hero Banner ── --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-user-circle text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $customer->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-phone me-1"></i>{{ $customer->phone }}</span>
                    @if ($customer->email)
                        <span>· {{ $customer->email }}</span>
                    @endif
                    <span>· {{ $customer->sales->count() }} {{ __('messages.total_sales') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $customer->status === 'active' ? 'text-success' : 'text-secondary' }}">
                    <i
                        class="bx {{ $customer->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($customer->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left --}}
        <div class="col-lg-8">

            {{-- Header Card --}}
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-user-circle me-2 text-primary"></i>{{ __('messages.customer_details') }}
                    </h6>
                    <span class="badge rounded-pill {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $customer->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="avatar flex-shrink-0" style="width:72px;height:72px;">
                            <span
                                class="avatar-initial rounded-circle bg-label-success w-100 h-100 d-flex align-items-center justify-content-center"
                                style="font-size:2rem;">
                                <i class="bx bx-user-circle"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
                            <p class="text-muted mb-1"><i class="bx bx-phone me-1"></i>{{ $customer->phone }}</p>
                            @if ($customer->email)
                                <p class="text-muted small mb-0"><i class="bx bx-envelope me-1"></i>{{ $customer->email }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">{{ $customer->sales->count() }}</div>
                                <div class="text-muted small">{{ __('messages.total_sales') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-warning">
                                <div class="fw-bold fs-4 text-warning">{{ $customer->saleReturns->count() }}</div>
                                <div class="text-muted small">{{ __('messages.sale_returns') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-4 text-success">
                                    {{ format_currency($customer->opening_balance ?? 0) }}</div>
                                <div class="text-muted small">{{ __('messages.opening_balance') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Details --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-phone me-2 text-success"></i>{{ __('messages.contact_details') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.ph_phone') }}</span>
                            <span class="fw-semibold">{{ $customer->phone }}</span>
                        </li>
                        @if ($customer->alt_phone)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold">{{ __('messages.alt_phone') }}</span>
                                <span>{{ $customer->alt_phone }}</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.email_address') }}</span>
                            <span>{{ $customer->email ?: '—' }}</span>
                        </li>
                        @if ($customer->gst_number)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold">{{ __('messages.gst_number') }}</span>
                                <code>{{ $customer->gst_number }}</code>
                            </li>
                        @endif
                        @if ($customer->address || $customer->city)
                            <li class="list-group-item px-4 py-3">
                                <span
                                    class="text-muted small fw-semibold d-block mb-1">{{ __('messages.address_label') }}</span>
                                <p class="mb-0 small">
                                    {{ implode(', ', array_filter([$customer->address, $customer->city, $customer->state, $customer->pincode, $customer->country])) ?: '—' }}
                                </p>
                            </li>
                        @endif
                        @if ($customer->notes)
                            <li class="list-group-item px-4 py-3">
                                <span class="text-muted small fw-semibold d-block mb-1">{{ __('messages.notes') }}</span>
                                <p class="mb-0 small text-muted">{{ $customer->notes }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Recent Sales --}}
            <div class="card shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-receipt me-2 text-primary"></i>{{ __('messages.recent_sales') }}
                        <span class="badge bg-label-primary ms-1">{{ $customer->sales->count() }}</span>
                    </h6>
                    <a href="{{ route('sales.index') }}"
                        class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    @php $recentSales = $customer->sales->sortByDesc('created_at')->take(5); @endphp
                    @if ($recentSales->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('messages.th_invoice') }}</th>
                                        <th>{{ __('messages.th_date') }}</th>
                                        <th class="text-end">{{ __('messages.th_total') }}</th>
                                        <th class="text-center">{{ __('messages.th_status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSales as $s)
                                        <tr>
                                            <td class="ps-4">
                                                <a href="{{ route('sales.show', $s->id) }}"
                                                    class="fw-semibold text-primary">
                                                    <code>{{ $s->invoice_no }}</code>
                                                </a>
                                            </td>
                                            <td class="small text-muted">{{ $s->invoice_date }}</td>
                                            <td class="text-end fw-bold">{{ format_currency($s->grand_total) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $s->status === 'Completed' ? 'bg-success' : ($s->status === 'Draft' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                    {{ $s->status }}
                                                </span>
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
                                <i class="bx bx-receipt" style="font-size:3rem;opacity:.3;line-height:1;"></i>
                                <span class="small">{{ __('messages.no_records') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right --}}
        <div class="col-lg-4">

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
                            <span class="fw-bold">#{{ $customer->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.customer_name') }}</span>
                            <span class="fw-bold">{{ $customer->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span
                                class="badge rounded-pill {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $customer->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.total_sales') }}</span>
                            <span class="badge bg-label-primary">{{ $customer->sales->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $customer->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $customer->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i>{{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    @can('customers.update')
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_customer') }}
                        </a>
                    @endcan
                    @can('sales.create')
                        <a href="{{ route('sales.create') }}" class="btn btn-outline-primary">
                            <i class="bx bx-plus me-1"></i> {{ __('messages.add_sale') }}
                        </a>
                    @endcan
                    @can('customers.delete')
                        <form id="deleteForm" action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $customer->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_customer') }}
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
