@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.sub_category_list') }}</h4>
            <a href="{{ route('admin.subcategory.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_sub_category') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="subCategoryTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.sub_category') }}</th>
                        <th>{{ __('admin.main_category') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subCategories as $subCategory)
                        <tr>
                            <td>{{ $subCategory->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-info">
                                        <i class="bx bx-category text-info"></i>
                                    </div>
                                    <strong>{{ $subCategory->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $subCategory->category->name ?? __('admin.dash') }}</td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $subCategory->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $subCategory->id }}" data-model="SubCategory">
                                    {{ $subCategory->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.subcategory.show', $subCategory->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.subcategory.edit', $subCategory->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.subcategory.destroy', $subCategory->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
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
        {{-- Pre-assign Blade translation strings to avoid quote clashes inside JS --}}
        var jsLang = {
            swalAreYouSure: "{{ __('admin.swal_are_you_sure') }}",
            swalDeleteText: "{{ __('admin.swal_delete_text') }}",
            swalYesDelete: "{{ __('admin.swal_yes_delete') }}",
            swalCancel: "{{ __('admin.swal_cancel') }}"
        };

        $(document).ready(function() {

            // -- DataTable ----------------------------------------------------
            $('#subCategoryTable').DataTable({
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

            // -- Delete confirmation ------------------------------------------
            $(document).on('click', '.btn-delete', function() {
                var form = $(this).closest('.delete-form');
                Swal.fire({
                    title: jsLang.swalAreYouSure,
                    text: jsLang.swalDeleteText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: jsLang.swalYesDelete,
                    cancelButtonText: jsLang.swalCancel
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
