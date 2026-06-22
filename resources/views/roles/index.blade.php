@extends('layouts.app')

@section('title', 'Role Management')

@section('content')

    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Role Management</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Define access roles and assign granular permissions
                to control what each role can do.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            @can('roles.create')
                <a href="{{ route('roles.create') }}"
                    class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    Add Role
                </a>
            @endcan
        </div>
    </div>

    <!-- Roles Table Card -->
    <x-card>
        <div class="overflow-x-auto">
            <table id="rolesTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap"
                style="width:100%">
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-800/50 text-slate-400 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Role Name</th>
                        <th class="text-center py-3 px-4">Permissions</th>
                        <th class="text-left py-3 px-4">Created</th>
                        <th class="text-center py-3 px-4 no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    @forelse ($roles as $index => $role)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $role->name }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400">
                                    <i class="fa-solid fa-key text-[9px]"></i>
                                    {{ $role->permissions_count }} permissions
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                {{ $role->created_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('roles.update')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 dark:hover:bg-violet-900/50 transition-colors"
                                            title="Edit">
                                            <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('roles.delete')
                                        @if ($role->name !== 'Super Admin')
                                            <form id="delete-form-{{ $role->id }}"
                                                action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}"
                                                    class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-900/50 transition-colors"
                                                    title="Delete">
                                                    <i class="fa-regular fa-trash-can text-[11px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                <i class="fa-solid fa-shield-halved text-2xl mb-2 block opacity-40"></i>
                                No roles found. Create your first role.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#rolesTable').DataTable({
                responsive: true,
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }],
                language: {
                    searchPlaceholder: 'Search roles…',
                    search: ''
                },
                dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>',
            });

            $('.delete-btn').on('click', function() {
                const roleId = $(this).data('id');
                const roleName = $(this).data('name');
                const form = $(`#delete-form-${roleId}`);
                const isDark = document.documentElement.classList.contains('dark');

                Swal.fire({
                    title: 'Delete Role?',
                    text: `You are about to delete the role "${roleName}". Users assigned to this role will lose their permissions.`,
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
                    },
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
                                        confirmButtonColor: '#3b82f6',
                                    }).then(() => window.location.reload());
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function() {
                                toastr.error(
                                    'An error occurred while deleting the role.');
                            },
                        });
                    }
                });
            });

        });
    </script>
@endpush
