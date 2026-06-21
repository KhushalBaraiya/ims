@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.payment_list') }}</h4>
            <a href="{{ route('admin.payment.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_payment') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="paymentTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.user') }}</th>
                        <th>{{ __('admin.order_no') }}</th>
                        <th>{{ __('admin.amount') }}</th>
                        <th>{{ __('admin.method') }}</th>
                        <th>{{ __('admin.gateway') }}</th>
                        <th>{{ __('admin.payment_status') }}</th>
                        <th>{{ __('admin.paid_at') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-success">
                                        <i class="bx bx-credit-card text-success"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $payment->user->name ?? __('admin.na') }}</strong><br>
                                        <small class="text-muted">{{ $payment->currency }}
                                            {{ number_format($payment->amount, 2) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $payment->order->order_number ?? __('admin.na') }}</td>
                            <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ admin_label($payment->payment_method, 'pm') }}</td>
                            <td>{{ admin_label($payment->gateway ?? '', 'pm') }}</td>
                            <td>
                                @php
                                    $badge = match ($payment->payment_status) {
                                        'success' => 'success',
                                        'failed' => 'danger',
                                        'refunded' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span
                                    class="badge bg-{{ $badge }} rounded-pill px-3 py-1">{{ admin_label($payment->payment_status, 'ps') }}</span>
                            </td>
                            <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : '—' }}
                            </td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $payment->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $payment->id }}" data-model="Payment">
                                    {{ $payment->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.payment.show', $payment->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.payment.edit', $payment->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.payment.destroy', $payment->id) }}" method="POST"
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
            $('#paymentTable').DataTable({
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
                    title: '{{ __("admin.swal_are_you_sure") }}',
                    text: '{{ __("admin.swal_delete_text") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __("admin.swal_yes_delete") }}',
                    cancelButtonText: '{{ __("admin.swal_cancel") }}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush