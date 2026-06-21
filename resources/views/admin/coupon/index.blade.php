@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.coupon_list') }}</h4>
            <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_coupon') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="couponTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.coupon') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.type') }}</th>
                        <th>{{ __('admin.discount') }}</th>
                        {{-- <th>{{ __('admin.validity') }}</th> --}}
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-success">
                                        <i class="bx bxs-coupon text-success"></i>
                                    </div>
                                    <div>
                                        <strong><code>{{ $coupon->code }}</code></strong><br>
                                        <small class="text-muted">{{ $coupon->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $coupon->product->name ?? __('admin.na') }}</td>
                            <td><span
                                    class="badge bg-secondary rounded-pill px-3 py-1">{{ admin_label($coupon->discount_type, 'dt') }}</span>
                            </td>
                            {{-- <td>{{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : '₹' . $coupon->discount_value }}
                            </td> --}}
                            <td><small class="text-muted">{{ $coupon->start_date }} - {{ $coupon->end_date }}</small></td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $coupon->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $coupon->id }}" data-model="Coupon">
                                    {{ $coupon->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.coupon.show', $coupon->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST"
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
            $('#couponTable').DataTable({
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
