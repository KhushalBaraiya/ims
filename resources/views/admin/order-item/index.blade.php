@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.order_item_list') }}</h4>
            <a href="{{ route('admin.order-item.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_order_item') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="orderItemTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.order_no') }}</th>
                        <th>{{ __('admin.qty') }}</th>
                        <th>{{ __('admin.price') }}</th>
                        <th>{{ __('admin.total') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @php
                                    // Snapshot image first; if file missing, fallback to live product's first image
                                    $imgFile = $item->product_image;
                                    $imgValid = $imgFile && file_exists(public_path('uploads/products/' . $imgFile));
                                    if (!$imgValid) {
                                        $liveImgs = $item->product->images ?? [];
                                        $imgFile = count($liveImgs) ? $liveImgs[0] : null;
                                        $imgValid = (bool) $imgFile;
                                    }
                                @endphp
                                <div class="d-flex align-items-center gap-3">
                                    @if ($imgValid)
                                        <img src="{{ asset('uploads/products/' . $imgFile) }}" class="tbl-img-round"
                                            onerror="imgError(this)">
                                    @else
                                        <div
                                            class="tbl-img-round d-flex align-items-center justify-content-center bg-label-info">
                                            <i class="bx bx-box text-info fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $item->product_name }}</strong><br>
                                        <small
                                            class="text-muted">{{ $item->order->order_number ?? __('admin.dash') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $item->order->order_number ?? __('admin.na') }}</strong>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>₹{{ number_format($item->total_price, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.order-item.show', $item->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.order-item.edit', $item->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.order-item.destroy', $item->id) }}" method="POST"
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
            $('#orderItemTable').DataTable({
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
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush