@extends('layouts.app')

@section('title', 'Main Categories')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 tracking-tight">Main Categories</h2>
            <p class="mt-1 text-sm text-slate-500">Manage main product classifications for inventory grouping.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            @can('main_categories.create')
                <a href="{{ route('main-categories.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                    <i class="fa-solid fa-plus"></i>
                    Add Category
                </a>
            @endcan
        </div>
    </div>

    <!-- DataTables Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6">
        <table id="categoriesTable" class="w-full text-slate-800 display responsive nowrap" style="width:100%">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                    <th class="text-left py-3.5 px-4">#</th>
                    <th class="text-left py-3.5 px-4">Category Name</th>
                    <th class="text-left py-3.5 px-4">Category Code</th>
                    <th class="text-left py-3.5 px-4">Status</th>
                    <th class="text-left py-3.5 px-4">Created Date</th>
                    <th class="text-center py-3.5 px-4 no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($categories as $index => $category)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4 font-semibold text-slate-400 text-sm">{{ $index + 1 }}</td>
                        <td class="py-4 px-4 font-bold text-slate-800 text-sm">{{ $category->name }}</td>
                        <td class="py-4 px-4 font-mono font-bold text-slate-600 text-sm">{{ $category->slug }}</td>
                        <td class="py-4 px-4 text-sm">
                            @if ($category->status === 'active')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-600/20">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-slate-500 text-sm">{{ $category->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @can('main_categories.view')
                                    <a href="{{ route('main-categories.show', $category->id) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-colors" title="View">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </a>
                                @endcan

                                @can('main_categories.update')
                                    <a href="{{ route('main-categories.edit', $category->id) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 transition-colors" title="Edit">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                @endcan

                                @can('main_categories.delete')
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('main-categories.destroy', $category->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-id="{{ $category->id }}" data-name="{{ $category->name }}" class="delete-btn inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-colors" title="Delete">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#categoriesTable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            language: {
                searchPlaceholder: "Search records...",
                search: ""
            },
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>',
            // Export buttons layout placeholder
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Setup SweetAlert2 delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');
            const form = $(`#delete-form-${categoryId}`);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete category "${categoryName}". This action will soft-delete the record.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: '#1f2937',
                color: '#fff',
                customClass: {
                    popup: 'rounded-2xl border border-zinc-800 shadow-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX delete request
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    background: '#1f2937',
                                    color: '#fff',
                                    confirmButtonColor: '#3b82f6'
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            toastr.error('An error occurred while deleting the category.');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(`Deletion of "${categoryName}" was canceled.`);
                }
            });
        });
    });
</script>
@endpush
