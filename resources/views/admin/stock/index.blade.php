@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('admin.stock_list') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 fs-6">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin.breadcrumb_dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('admin.stock') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.stock.create') }}" class="btn btn-primary btn-lg">
            <i class="bx bx-plus me-1"></i>{{ __('admin.add_stock') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0" id="stockTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.date') }}</th>
                        <th>{{ __('admin.type') }}</th>
                        <th class="text-center">{{ __('admin.qty') }}</th>
                        <th>{{ __('admin.price') }}</th>
                        <th>{{ __('admin.total') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <td><strong>#{{ $stock->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if (!empty($stock->product->images))
                                        <img src="{{ asset('uploads/products/' . $stock->product->images[0]) }}"
                                            class="tbl-img" onerror="imgError(this)">
                                    @else
                                        <div
                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                            <i class="bx bx-package text-muted"></i>
                                        </div>
                                    @endif
                                    <span
                                        class="fw-semibold">{{ Str::limit($stock->product->name ?? __('admin.na'), 25) }}</span>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $stock->date ? \Carbon\Carbon::parse($stock->date)->format('d M Y') : '—' }}
                                </small>
                            </td>
                            <td>
                                @if ($stock->type === 'in')
                                    <span class="badge bg-success rounded-pill px-3 py-1">
                                        <i class="bx bx-trending-up me-1"></i>{{ __('admin.stock_in') }}
                                    </span>
                                @elseif($stock->type === 'out')
                                    <span class="badge bg-danger rounded-pill px-3 py-1">
                                        <i class="bx bx-trending-down me-1"></i>{{ __('admin.stock_out') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1">
                                        <i class="bx bx-transfer me-1"></i>{{ __('admin.stock_adjustment') }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-primary px-2">{{ $stock->quantity }}</span>
                            </td>
                            <td>₹{{ number_format($stock->price, 2) }}</td>
                            <td><strong class="text-success">₹{{ number_format($stock->total_price, 2) }}</strong></td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $stock->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $stock->id }}" data-model="Stock">
                                    {{ $stock->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.stock.show', $stock->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-1 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.stock.edit', $stock->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-1 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action btn-delete">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#stockTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                order: [
                    [0, 'desc']
                ]
            });

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
                    cancelButtonText: '{{ __('admin.swal_cancel') }}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
