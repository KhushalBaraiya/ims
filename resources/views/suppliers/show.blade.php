@extends('layouts.admin')
@section('title', __('messages.supplier_details') . ' — ' . $supplier->name)

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.supplier_details') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('suppliers.index') }}">{{ __('messages.menu_suppliers') }}</a></li>
                    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('suppliers.update')
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> {{ __('messages.edit') }}
                </a>
            @endcan
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    {{-- -- Hero Banner -- --}}
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-truck text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm">{{ $supplier->name }}</div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    @if ($supplier->company_name)
                        <span><i class="bx bx-buildings me-1"></i>{{ $supplier->company_name }}</span>
                    @endif
                    <span><i class="bx bx-phone me-1"></i>{{ $supplier->phone }}</span>
                    <span>· {{ $supplier->purchases->count() }} {{ __('messages.total_purchases') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold {{ $supplier->status === 'active' ? 'text-success' : 'text-secondary' }}">
                    <i
                        class="bx {{ $supplier->status === 'active' ? 'bx-check' : 'bx-x' }} me-1"></i>{{ ucfirst($supplier->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Header Card --}}
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-store me-2 text-primary"></i>{{ __('messages.supplier_details') }}
                    </h6>
                    <span class="badge rounded-pill {{ $supplier->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $supplier->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="avatar flex-shrink-0" style="width:72px;height:72px;">
                            <span
                                class="avatar-initial rounded-circle bg-label-warning w-100 h-100 d-flex align-items-center justify-content-center"
                                style="font-size:2rem;">
                                <i class="bx bx-store"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $supplier->name }}</h4>
                            @if ($supplier->company_name)
                                <p class="text-muted mb-1"><i
                                        class="bx bx-buildings me-1"></i>{{ $supplier->company_name }}</p>
                            @endif
                            @if ($supplier->contact_person)
                                <p class="text-muted small mb-0"><i
                                        class="bx bx-user me-1"></i>{{ $supplier->contact_person }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-info">
                                <div class="fw-bold fs-4 text-info">{{ $supplier->purchases->count() }}</div>
                                <div class="text-muted small">{{ __('messages.total_purchases') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-warning">
                                <div class="fw-bold fs-4 text-warning">{{ $supplier->purchaseReturns->count() }}</div>
                                <div class="text-muted small">{{ __('messages.purchase_returns') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">
                                    {{ format_currency($supplier->opening_balance ?? 0) }}</div>
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
                            <span class="fw-semibold">{{ $supplier->phone }}</span>
                        </li>
                        @if ($supplier->alt_phone)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold">{{ __('messages.alt_phone') }}</span>
                                <span>{{ $supplier->alt_phone }}</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold">{{ __('messages.email_address') }}</span>
                            <span>{{ $supplier->email ?: '—' }}</span>
                        </li>
                        @if ($supplier->gst_number)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold">{{ __('messages.gst_number') }}</span>
                                <code>{{ $supplier->gst_number }}</code>
                            </li>
                        @endif
                        @if ($supplier->pan_number)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold">{{ __('messages.pan_number') }}</span>
                                <code>{{ $supplier->pan_number }}</code>
                            </li>
                        @endif
                        @if ($supplier->address || $supplier->city)
                            <li class="list-group-item px-4 py-3">
                                <span
                                    class="text-muted small fw-semibold d-block mb-1">{{ __('messages.address_label') }}</span>
                                <p class="mb-0 small">
                                    {{ implode(', ', array_filter([$supplier->address, $supplier->city, $supplier->state, $supplier->pincode, $supplier->country])) ?: '—' }}
                                </p>
                            </li>
                        @endif
                        @if ($supplier->notes)
                            <li class="list-group-item px-4 py-3">
                                <span class="text-muted small fw-semibold d-block mb-1">{{ __('messages.notes') }}</span>
                                <p class="mb-0 small text-muted">{{ $supplier->notes }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Recent Purchases --}}
            <div class="card shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-receipt me-2 text-info"></i>{{ __('messages.recent_purchases') }}
                        <span class="badge bg-label-info ms-1">{{ $supplier->purchases->count() }}</span>
                    </h6>
                    <a href="{{ route('purchases.index') }}"
                        class="btn btn-sm btn-outline-info">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    @php $recentPurchases = $supplier->purchases->sortByDesc('created_at')->take(5); @endphp
                    @if ($recentPurchases->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('messages.th_purchase_no') }}</th>
                                        <th>{{ __('messages.th_date') }}</th>
                                        <th class="text-end">{{ __('messages.th_total') }}</th>
                                        <th class="text-center">{{ __('messages.th_status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentPurchases as $p)
                                        <tr>
                                            <td class="ps-4">
                                                <a href="{{ route('purchases.show', $p->id) }}"
                                                    class="fw-semibold text-info">
                                                    <code>{{ $p->purchase_no }}</code>
                                                </a>
                                            </td>
                                            <td class="small text-muted">{{ $p->purchase_date }}</td>
                                            <td class="text-end fw-bold">{{ format_currency($p->grand_total) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $p->status === 'Completed' ? 'bg-success' : ($p->status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                    {{ $p->status }}
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

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Information --}}
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
                            <span class="fw-bold">#{{ $supplier->id }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.supplier_name') }}</span>
                            <span class="fw-bold">{{ $supplier->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_status') }}</span>
                            <span
                                class="badge rounded-pill {{ $supplier->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $supplier->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.total_purchases') }}</span>
                            <span class="badge bg-label-info">{{ $supplier->purchases->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">{{ __('messages.th_created') }}</span>
                            <span class="small">{{ $supplier->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold">{{ __('messages.updated') }}</span>
                            <span class="small">{{ $supplier->updated_at->format('d M Y') }}</span>
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
                    @can('suppliers.update')
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> {{ __('messages.edit_supplier') }}
                        </a>
                    @endcan
                    @can('purchases.create')
                        <a href="{{ route('purchases.create') }}" class="btn btn-outline-info">
                            <i class="bx bx-plus me-1"></i> {{ __('messages.add_purchase') }}
                        </a>
                    @endcan
                    @can('suppliers.delete')
                        <form id="deleteForm" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="{{ $supplier->name }}">
                                <i class="bx bx-trash me-1"></i> {{ __('messages.delete_supplier') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

        </div>
    </div>

@endsection
{{-- do  --}}
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
