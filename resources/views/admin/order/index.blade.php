@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.order_list') }}</h4>
            <a href="{{ route('admin.order.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_order') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="orderTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.order') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.qty') }}</th>
                        <th>{{ __('admin.price') }}</th>
                        <th>{{ __('admin.total') }}</th>
                        <th>{{ __('admin.payment_method') }}</th>
                        <th>{{ __('admin.payment_status') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-primary">
                                        <i class="bx bx-cart text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $order->order_number }}</strong><br>
                                        <small class="text-muted">{{ $order->user->name ?? __('admin.na') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $order->product->name ?? __('admin.na') }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>₹{{ number_format($order->price, 2) }}</td>
                            <td>₹{{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ admin_label($order->payment_method, 'pm') }}</td>
                            <td>
                                @php
                                    $pBadge = match ($order->payment_status) {
                                        'paid' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $pBadge }} rounded-pill px-3 py-1">
                                    {{ admin_label($order->payment_status, 'ps') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $order->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $order->id }}" data-model="Order">
                                    {{ $order->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.order.show', $order->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.order.edit', $order->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST"
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
            $('#orderTable').DataTable({
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
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush
