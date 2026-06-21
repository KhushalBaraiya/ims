@extends('layouts.app')

@section('title', 'Sub Categories')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Sub Categories</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Manage electronics inventory subcategories grouped under main categories.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            @can('sub_categories.create')
                <a href="{{ route('sub-categories.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    Add Sub Category
                </a>
            @endcan
        </div>
    </div>

    <!-- DataTables Card -->
    <x-card>
        <div class="overflow-x-auto">
            <table id="subCategoriesTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Main Category</th>
                        <th class="text-left py-3 px-4">Sub Category Name</th>
                        <th class="text-left py-3 px-4">Sub Category Code</th>
                        <th class="text-left py-3 px-4">Status</th>
                        <th class="text-left py-3 px-4">Created Date</th>
                        <th class="text-center py-3 px-4 no-sort">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    @foreach ($subCategories as $index => $subCategory)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-600 dark:text-slate-350">{{ $subCategory->mainCategory->name ?? '-' }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">{{ $subCategory->name }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-650 dark:text-slate-400">{{ $subCategory->slug }}</td>
                            <td class="py-3 px-4">
                                <x-badge :variant="$subCategory->status === 'active' ? 'success' : 'danger'" :text="ucfirst($subCategory->status)" />
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $subCategory->created_at->format('Y-m-d') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('sub_categories.view')
                                        <a href="{{ route('sub-categories.show', $subCategory->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/50 transition-colors" title="View">
                                            <i class="fa-regular fa-eye text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('sub_categories.update')
                                        <a href="{{ route('sub-categories.edit', $subCategory->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 dark:hover:bg-violet-900/50 transition-colors" title="Edit">
                                            <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('sub_categories.delete')
                                        <form id="delete-form-{{ $subCategory->id }}" action="{{ route('sub-categories.destroy', $subCategory->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-id="{{ $subCategory->id }}" data-name="{{ $subCategory->name }}" class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-900/50 transition-colors" title="Delete">
                                                <i class="fa-regular fa-trash-can text-[11px]"></i>
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
    </x-card>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#subCategoriesTable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            language: {
                searchPlaceholder: "Search sub categories...",
                search: ""
            },
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
        });

        // Setup SweetAlert2 delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const form = $(`#delete-form-${id}`);
            
            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete subcategory "${name}". This action will soft-delete the record.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: isDark ? '#18181b' : '#fff',
                color: isDark ? '#fff' : '#1e293b',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
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
                                    background: isDark ? '#18181b' : '#fff',
                                    color: isDark ? '#fff' : '#1e293b',
                                    confirmButtonColor: '#3b82f6'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('An error occurred while deleting the subcategory.');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(`Deletion of "${name}" was canceled.`);
                }
            });
        });
    });
</script>
@endpush
