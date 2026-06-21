@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.reviews_list') }}</h4>
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_review') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="reviewTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.product') }}</th>
                        <th>{{ __('admin.user') }}</th>
                        <th>{{ __('admin.title') }}</th>
                        <th>{{ __('admin.rating') }}</th>
                        <th>{{ __('admin.date') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-warning">
                                        <i class="bx bx-star text-warning"></i>
                                    </div>
                                    <strong>{{ $review->product->name ?? __('admin.na') }}</strong>
                                </div>
                            </td>
                            <td>{{ $review->user->name ?? __('admin.na') }}</td>
                            <td>{{ $review->title }}</td>
                            <td>
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bx bxs-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <small class="text-muted ms-1">{{ $review->rating }}/5</small>
                            </td>
                            <td>{{ $review->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $review->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $review->id }}" data-model="Reviews">
                                    {{ $review->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.reviews.show', $review->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.reviews.edit', $review->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
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
            $('#reviewTable').DataTable({
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
