@extends('layouts.app')

@section('title', 'Brands')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Brands</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Manage manufacturers and product brands in the system.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            @can('brands.create')
                <a href="{{ route('brands.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                    <i class="fa-solid fa-plus"></i>
                    Add Brand
                </a>
            @endcan
        </div>
    </div>

    <!-- DataTables Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden p-6 transition-colors duration-150">
        <table id="brandsTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="text-left py-3 px-4">#</th>
                    <th class="text-left py-3 px-4">Brand Name</th>
                    <th class="text-left py-3 px-4">Brand Code</th>
                    <th class="text-left py-3 px-4">Status</th>
                    <th class="text-left py-3 px-4">Created Date</th>
                    <th class="text-center py-3 px-4 no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                @foreach ($brands as $index => $brand)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-400 dark:text-slate-500">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">{{ $brand->name }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-650 dark:text-slate-400">{{ $brand->slug }}</td>
                        <td class="py-3 px-4">
                            @if ($brand->status === 'active')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-500/20">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-500/10 px-2 py-0.5 text-[11px] font-bold text-red-700 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/20">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-450">{{ $brand->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                @can('brands.view')
                                    <a href="{{ route('brands.show', $brand->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/50 transition-colors" title="View">
                                        <i class="fa-regular fa-eye text-[11px]"></i>
                                    </a>
                                @endcan

                                @can('brands.update')
                                    <a href="{{ route('brands.edit', $brand->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 dark:hover:bg-violet-900/50 transition-colors" title="Edit">
                                        <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                    </a>
                                @endcan

                                @can('brands.delete')
                                    <form id="delete-form-{{ $brand->id }}" action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-id="{{ $brand->id }}" data-name="{{ $brand->name }}" class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-900/50 transition-colors" title="Delete">
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#brandsTable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            language: {
                searchPlaceholder: "Search brands...",
                search: ""
            },
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
        });

        // Setup SweetAlert2 delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const brandId = $(this).data('id');
            const brandName = $(this).data('name');
            const form = $(`#delete-form-${brandId}`);
            
            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete brand "${brandName}". This action will soft-delete the record.`,
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
                            toastr.error('An error occurred while deleting the brand.');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(`Deletion of "${brandName}" was canceled.`);
                }
            });
        });
    });
</script>
@endpush
