@extends('layouts.admin')
@section('title', __('messages.stock_history'))

@section('content')

    {{-- ── Page Header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.stock_history') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('stocks.index') }}">{{ __('messages.stock_overview') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('messages.stock_history') }}</li>
                </ol>
            </nav>
        </div>
        @can('stocks.create')
            <a href="{{ route('stocks.adjust') }}" class="btn btn-primary">
                <i class="bx bx-slider me-1"></i> {{ __('messages.adjust_stock') }}
            </a>
        @endcan
    </div>

    {{-- ── Filters ── --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filters') }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('stocks.history') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.product') }}</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_products') }}</option>
                            @foreach ($allProducts as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.adjustment_type') }}</label>
                        <select name="adjustment_type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}"
                                    {{ request('adjustment_type') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('stocks.history') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-x"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── History Table ── --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-history me-2 text-success"></i>{{ __('messages.stock_history') }}
            </h6>
            <span class="badge bg-label-success">{{ $adjustments->count() }} records</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="historyTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.th_date') }}</th>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ __('messages.sku') }}</th>
                            <th class="text-center">{{ __('messages.adjustment_type') }}</th>
                            <th class="text-end">{{ __('messages.quantity_change') }}</th>
                            <th>{{ __('messages.th_created_by') }}</th>
                            <th>{{ __('messages.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $index => $adj)
                            @php
                                $isPositive = $adj->quantity_change >= 0;
                                $typeColors = [
                                    'Restock' => 'success',
                                    'Return' => 'info',
                                    'Damage' => 'danger',
                                    'Write-Off' => 'warning',
                                    'Correction' => 'primary',
                                    'Other' => 'secondary',
                                ];
                                $color = $typeColors[$adj->adjustment_type] ?? 'secondary';
                            @endphp
                            <tr>
                                <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td class="text-muted small">
                                    {{ $adj->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $adj->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($adj->product?->image)
                                            <img src="{{ asset('uploads/products/' . $adj->product->image) }}"
                                                class="rounded" style="width:30px;height:30px;object-fit:cover;">
                                        @endif
                                        <strong>{{ $adj->product->name ?? 'Deleted Product' }}</strong>
                                    </div>
                                </td>
                                <td><code class="small">{{ $adj->product->code ?? '-' }}</code></td>
                                <td class="text-center">
                                    <span class="badge bg-label-{{ $color }}">{{ $adj->adjustment_type }}</span>
                                </td>
                                <td
                                    class="text-end fw-bold fs-6
                                {{ $isPositive ? 'text-success' : 'text-danger' }}">
                                    {{ $isPositive ? '+' : '' }}{{ number_format($adj->quantity_change, 2) }}
                                </td>
                                <td class="fw-semibold small">{{ $adj->user->name ?? 'System' }}</td>
                                <td class="text-muted small" title="{{ $adj->notes }}">
                                    {{ $adj->notes ? \Str::limit($adj->notes, 40) : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bx bx-history" style="font-size:2.5rem;opacity:.3;"></i>
                                    <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    targets: [2, 7],
                    orderable: false
                }]
            });
        });
    </script>
@endpush
