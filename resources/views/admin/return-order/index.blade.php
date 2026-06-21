@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.return_order_list') }}</h4>
            <a href="{{ route('admin.return-order.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_return_order') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="returnTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.order') }}</th>
                        <th>{{ __('admin.user') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.qty') }}</th>
                        <th>{{ __('admin.type') }}</th>
                        <th>{{ __('admin.refund') }}</th>
                        <th>{{ __('admin.return_date') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($returns as $return)
                        <tr>
                            <td>{{ $return->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-danger">
                                        <i class="bx bx-revision text-danger"></i>
                                    </div>
                                    <strong>{{ $return->order->order_number ?? __('admin.na') }}</strong>
                                </div>
                            </td>
                            <td>{{ $return->user->name ?? __('admin.na') }}</td>
                            <td>{{ $return->product->name ?? __('admin.na') }}</td>
                            <td>{{ $return->quantity }}</td>
                            <td>
                                @php
                                    $typeBadge = match ($return->return_type) {
                                        'return' => 'warning',
                                        'exchange' => 'info',
                                        'refund' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span
                                    class="badge bg-{{ $typeBadge }} rounded-pill px-3 py-1">{{ admin_label($return->return_type, 'rt') }}</span>
                            </td>
                            <td>₹{{ number_format($return->refund_amount, 2) }}</td>
                            <td>{{ $return->return_date ?? __('admin.dash') }}</td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $return->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $return->id }}" data-model="ReturnOrder">
                                    {{ $return->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.return-order.show', $return->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.return-order.edit', $return->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.return-order.destroy', $return->id) }}" method="POST"
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
            $('#returnTable').DataTable({
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

            // SweetAlert2 delete confirm
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
