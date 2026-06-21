@extends('layouts.app')

@section('title', 'Currencies')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Currencies</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Manage transaction currencies, exchange rates, and configure default settings.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            @can('currencies.create')
                <button type="button" id="openCreateModalBtn" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    Add Currency
                </button>
            @endcan
        </div>
    </div>

    <!-- DataTables Card -->
    <x-card>
        <div class="overflow-x-auto">
            <table id="currenciesTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Currency Name</th>
                        <th class="text-left py-3 px-4">Currency Code</th>
                        <th class="text-left py-3 px-4">Symbol</th>
                        <th class="text-left py-3 px-4">Exchange Rate</th>
                        <th class="text-left py-3 px-4">Default</th>
                        <th class="text-left py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4 no-sort">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    <!-- AJAX populated -->
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- AJAX Modal -->
    <div id="currencyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalBackdrop" class="fixed inset-0 bg-slate-950/45 dark:bg-slate-950/70 transition-opacity backdrop-blur-sm"></div>

            <!-- Trick to center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div id="modalPanel" class="relative inline-block transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-left align-middle shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200" id="modalTitle">
                        Add New Currency
                    </h3>
                    <button type="button" class="closeModalBtn text-slate-400 hover:text-slate-500 dark:hover:text-slate-350 focus:outline-none">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="currencyForm" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="p-6 space-y-4">
                        <!-- Currency Name -->
                        <div class="form-group-container">
                            <label for="name" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Currency Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50" placeholder="e.g. US Dollar, Indian Rupee">
                            <span class="error-msg text-xs text-red-500 mt-1 flex items-center gap-1 hidden"><i class="fa-solid fa-circle-info"></i> <span class="msg-content"></span></span>
                        </div>

                        <!-- Currency Code -->
                        <div class="form-group-container">
                            <label for="code" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Currency Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" id="code" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50" placeholder="e.g. USD, INR">
                            <span class="error-msg text-xs text-red-500 mt-1 flex items-center gap-1 hidden"><i class="fa-solid fa-circle-info"></i> <span class="msg-content"></span></span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Symbol -->
                            <div class="form-group-container">
                                <label for="symbol" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Currency Symbol <span class="text-red-500">*</span></label>
                                <input type="text" name="symbol" id="symbol" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50" placeholder="e.g. $, ₹, €">
                                <span class="error-msg text-xs text-red-500 mt-1 flex items-center gap-1 hidden"><i class="fa-solid fa-circle-info"></i> <span class="msg-content"></span></span>
                            </div>

                            <!-- Exchange Rate -->
                            <div class="form-group-container">
                                <label for="exchange_rate" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Exchange Rate <span class="text-red-500">*</span></label>
                                <input type="number" step="0.0001" name="exchange_rate" id="exchange_rate" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50" placeholder="e.g. 1.0000, 83.5000">
                                <span class="error-msg text-xs text-red-500 mt-1 flex items-center gap-1 hidden"><i class="fa-solid fa-circle-info"></i> <span class="msg-content"></span></span>
                            </div>
                        </div>

                        <!-- Status & Is Default -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group-container">
                                <label for="status" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-800 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <span class="error-msg text-xs text-red-500 mt-1 flex items-center gap-1 hidden"><i class="fa-solid fa-circle-info"></i> <span class="msg-content"></span></span>
                            </div>

                            <div class="flex items-center pt-6">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="is_default" id="is_default" value="1" class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300">Set as Default</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 bg-slate-50/50 dark:bg-slate-900/50">
                        <button type="button" class="closeModalBtn rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                            Save Currency
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const canUpdate = {{ auth()->user()->can('currencies.update') ? 'true' : 'false' }};
        const canDelete = {{ auth()->user()->can('currencies.delete') ? 'true' : 'false' }};

        // Initialize DataTable
        const table = $('#currenciesTable').DataTable({
            processing: true,
            ajax: "{{ route('currencies.index') }}",
            responsive: true,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            columns: [
                { 
                    data: null, 
                    render: function(data, type, row, meta) { 
                        return meta.row + 1; 
                    } 
                },
                { 
                    data: 'name', 
                    className: 'font-bold text-slate-800 dark:text-slate-200' 
                },
                { 
                    data: 'code', 
                    className: 'font-mono font-bold text-slate-650 dark:text-slate-400' 
                },
                { 
                    data: 'symbol', 
                    className: 'font-semibold text-slate-700 dark:text-slate-300' 
                },
                { 
                    data: 'exchange_rate', 
                    render: function(data) {
                        return parseFloat(data).toFixed(4);
                    }
                },
                { 
                    data: 'is_default',
                    render: function(data, type, row) {
                        if (data) {
                            return '<span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-500/10 px-2.5 py-0.5 text-[11px] font-bold text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-600/20 dark:ring-blue-500/20">Default</span>';
                        }
                        if (canUpdate) {
                            return '<button type="button" class="set-default-btn text-[11px] font-bold text-slate-400 hover:text-blue-600 transition-colors" data-id="'+row.id+'">Set Default</button>';
                        }
                        return '<span class="text-slate-400 text-[11px]">-</span>';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        const activeClass = 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 ring-emerald-600/20 dark:ring-emerald-500/20';
                        const inactiveClass = 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 ring-red-600/20 dark:ring-red-500/20';
                        const badgeClass = data === 'active' ? activeClass : inactiveClass;
                        const statusText = data.charAt(0).toUpperCase() + data.slice(1);
                        
                        if (canUpdate) {
                            return `<span class="toggle-status-btn cursor-pointer inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-bold ${badgeClass} ring-1 ring-inset" data-id="${row.id}">${statusText}</span>`;
                        }
                        return `<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-bold ${badgeClass} ring-1 ring-inset">${statusText}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let actionButtons = '<div class="flex items-center justify-center gap-1.5">';
                        if (canUpdate) {
                            actionButtons += `<button type="button" class="edit-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 dark:hover:bg-violet-900/50 transition-colors" data-id="${row.id}" title="Edit"><i class="fa-regular fa-pen-to-square text-[11px]"></i></button>`;
                        }
                        if (canDelete) {
                            actionButtons += `<button type="button" class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-900/50 transition-colors" data-id="${row.id}" data-name="${row.name}" title="Delete"><i class="fa-regular fa-trash-can text-[11px]"></i></button>`;
                        }
                        actionButtons += '</div>';
                        return actionButtons;
                    }
                }
            ],
            language: {
                searchPlaceholder: "Search currencies...",
                search: ""
            },
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
        });

        const modal = $('#currencyModal');
        const form = $('#currencyForm');
        
        // Open Create Modal
        $('#openCreateModalBtn').on('click', function() {
            resetForm();
            $('#modalTitle').text('Add New Currency');
            $('#formMethod').val('POST');
            form.attr('action', "{{ route('currencies.store') }}");
            showModal();
        });

        // Close Modal
        $('.closeModalBtn, #modalBackdrop').on('click', hideModal);

        // Save / Update via AJAX
        form.on('submit', function(e) {
            e.preventDefault();
            clearErrors();

            const actionUrl = form.attr('action');
            const formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'POST', // Handled by Laravel spoofing for PUT if needed
                data: formData,
                success: function(response) {
                    if (response.success) {
                        hideModal();
                        table.ajax.reload(null, false); // Reload without resetting paging
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            // Find target element and append error info
                            const inputElement = $(`#${field}`);
                            const container = inputElement.closest('.form-group-container');
                            const errorSpan = container.find('.error-msg');
                            errorSpan.find('.msg-content').text(errors[field][0]);
                            errorSpan.removeClass('hidden');
                            inputElement.addClass('border-red-500 focus:ring-red-500/30');
                        }
                    } else {
                        toastr.error('An error occurred while saving the currency.');
                    }
                }
            });
        });

        // Open Edit Modal via AJAX
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            resetForm();

            $.ajax({
                url: `/currencies/${id}/edit`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#name').val(data.name);
                        $('#code').val(data.code);
                        $('#symbol').val(data.symbol);
                        $('#exchange_rate').val(data.exchange_rate);
                        $('#status').val(data.status);
                        $('#is_default').prop('checked', data.is_default);

                        $('#modalTitle').text('Edit Currency');
                        $('#formMethod').val('PUT');
                        form.attr('action', `/currencies/${id}`);
                        showModal();
                    }
                },
                error: function() {
                    toastr.error('Could not fetch currency details.');
                }
            });
        });

        // Quick Set Default via AJAX
        $(document).on('click', '.set-default-btn', function() {
            const id = $(this).data('id');
            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Set Default?',
                text: 'Are you sure you want to make this currency the default transaction currency?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, set default!',
                cancelButtonText: 'Cancel',
                background: isDark ? '#18181b' : '#fff',
                color: isDark ? '#fff' : '#1e293b',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-800'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // We must pass other fields to satisfy the validation
                    $.ajax({
                        url: `/currencies/${id}/edit`,
                        type: 'GET',
                        success: function(getRes) {
                            if (getRes.success) {
                                const currencyData = getRes.data;
                                currencyData._token = "{{ csrf_token() }}";
                                currencyData._method = "PUT";
                                currencyData.is_default = 1;

                                $.ajax({
                                    url: `/currencies/${id}`,
                                    type: 'POST',
                                    data: currencyData,
                                    success: function(updateRes) {
                                        if (updateRes.success) {
                                            table.ajax.reload(null, false);
                                            toastr.success('Default currency changed successfully.');
                                        }
                                    },
                                    error: function() {
                                        toastr.error('Could not set currency as default.');
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });

        // Toggle Status via AJAX
        $(document).on('click', '.toggle-status-btn', function() {
            const id = $(this).data('id');
            
            $.ajax({
                url: `/currencies/${id}/toggle-status`,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload(null, false);
                        toastr.success(response.message);
                    }
                },
                error: function() {
                    toastr.error('Could not toggle currency status.');
                }
            });
        });

        // Delete via AJAX (with SweetAlert2)
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete currency "${name}". This action will soft-delete the record.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: isDark ? '#18181b' : '#fff',
                color: isDark ? '#fff' : '#1e293b',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 dark:border-slate-800'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/currencies/${id}`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
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
                                    table.ajax.reload(null, false);
                                });
                            }
                        },
                        error: function() {
                            toastr.error('Could not delete currency.');
                        }
                    });
                }
            });
        });

        // Helper functions
        function showModal() {
            modal.removeClass('hidden');
        }

        function hideModal() {
            modal.addClass('hidden');
        }

        function resetForm() {
            form[0].reset();
            $('#is_default').prop('checked', false);
            clearErrors();
        }

        function clearErrors() {
            $('.error-msg').addClass('hidden').find('.msg-content').text('');
            $('input, select').removeClass('border-red-500 focus:ring-red-500/30');
        }
    });
</script>
@endpush
