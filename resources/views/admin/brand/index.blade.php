@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.brand_list') }}</h4>
            <a href="{{ route('admin.brand.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add') }} {{ __('admin.brands') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="brandTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.brand') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        {{-- <th>{{ __('admin.created') }}</th> --}}
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($brand->image)
                                        <img src="{{ asset('uploads/brand/' . $brand->image) }}" class="tbl-img-round"
                                            onerror="imgError(this)">
                                    @else
                                        <div
                                            class="tbl-img-round d-flex align-items-center justify-content-center bg-light">
                                            <i class="bx bx-image text-muted icon-md-tbl"></i>
                                        </div>
                                    @endif
                                    <strong>{{ $brand->name }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $brand->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $brand->id }}" data-model="Brand">
                                    {{ $brand->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            {{-- <td>{{ $brand->created_at->format('d M Y') }}</td> --}}
                            <td class="text-center">
                                <a href="{{ route('admin.brand.show', $brand->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.brand.edit', $brand->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.brand.destroy', $brand->id) }}" method="POST"
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
            $('#brandTable').DataTable({
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