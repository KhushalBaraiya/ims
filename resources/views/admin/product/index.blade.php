@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.product_list') }}</h4>
            <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_product') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="productTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.category') }}</th>
                        <th>{{ __('admin.brand') }}</th>
                        <th>{{ __('admin.price') }}</th>
                        <th>{{ __('admin.qty') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.attribute') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if (!empty($product->images) && count($product->images))
                                        <img src="{{ asset('uploads/products/' . $product->images[0]) }}" class="tbl-img"
                                            onerror="imgError(this)">
                                    @else
                                        <div
                                            class="tbl-img d-flex align-items-center justify-content-center bg-light rounded">
                                            <i class="bx bx-package text-muted fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($product->description, 35) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? __('admin.dash') }}</td>
                            <td>{{ $product->brand->name ?? __('admin.dash') }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="badge bg-label-primary px-3 py-2 fs-6">{{ $product->quantity }}</span>
                            </td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $product->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $product->id }}" data-model="Product">
                                    {{ $product->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                @php $attrCount = $product->attributes_count ?? 0; @endphp
                                <a href="{{ route('admin.product.attributes.edit', $product->id) }}"
                                    class="btn btn-sm btn-warning px-3 py-1 fw-semibold attr-btn-text">
                                    <i class="bx bx-edit-alt me-1"></i>
                                    {{ $attrCount > 0 ? __('admin.edit_attrs') : __('admin.add_attrs') }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.product.show', $product->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-1 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-1 btn-action"
                                    title="{{ __('admin.edit') }}">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
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
            $('#productTable').DataTable({
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