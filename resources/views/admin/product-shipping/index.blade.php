@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.product_shipping_list') }}</h4>
            <a href="{{ route('admin.product-shipping.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_product_shipping') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="shippingTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.title') }}</th>
                        <th>{{ __('admin.delivery_time') }}</th>
                        <th>{{ __('admin.charge') }}</th>
                        <th>{{ __('admin.free_above') }}</th>
                        <th>{{ __('admin.cod') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shippings as $shipping)
                        <tr>
                            <td>{{ $shipping->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-info">
                                        <i class="bx bx-package text-info"></i>
                                    </div>
                                    <strong>{{ $shipping->title }}</strong>
                                </div>
                            </td>
                            <td>
                                @if ($shipping->delivery_time)
                                    <span
                                        class="badge bg-info text-dark rounded-pill px-3 py-1">{{ $shipping->delivery_time }}</span>
                                @else
                                    <span class="text-muted">{{ __('admin.na') }}</span>
                                @endif
                            </td>
                            <td data-order="{{ $shipping->charge }}">
                                @if ($shipping->charge == 0)
                                    <span class="badge bg-success rounded-pill px-3 py-1">{{ __('admin.free') }}</span>
                                @else
                                    <span class="fw-semibold">₹{{ number_format($shipping->charge, 2) }}</span>
                                @endif
                            </td>
                            <td data-order="{{ $shipping->free_above ?? 0 }}">
                                @if ($shipping->free_above)
                                    <span
                                        class="text-success fw-semibold">{{ __('admin.above_amount', ['amount' => '₹' . number_format($shipping->free_above, 2)]) }}</span>
                                @else
                                    <span class="text-muted">{{ __('admin.na') }}</span>
                                @endif
                            </td>
                            <td data-order="{{ $shipping->cod_available }}">
                                <span
                                    class="badge {{ $shipping->cod_available ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-1">
                                    {{ $shipping->cod_available ? __('admin.yes') : __('admin.no') }}
                                </span>
                            </td>
                            <td class="text-center" data-order="{{ $shipping->status === 'active' ? 1 : 0 }}">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $shipping->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $shipping->id }}" data-model="ProductShipping">
                                    {{ $shipping->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.product-shipping.show', $shipping->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.product-shipping.edit', $shipping->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.product-shipping.destroy', $shipping->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action btn-delete">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                {{ __('admin.no_data_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#shippingTable').DataTable({
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
