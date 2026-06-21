@extends('layouts.app')

@section('title', 'Sales Returns List')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Sales Returns</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">View and manage customer sales returns, process refunds, and track stock restoration.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <!-- Toggle Filters Button -->
            <button type="button" id="toggleFiltersBtn" class="inline-flex items-center gap-x-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-350 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-filter"></i>
                Filters
                <i id="filtersChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-405 dark:text-slate-500 ml-0.5 transition-transform duration-200"></i>
            </button>
            
            @can('sale_returns.create')
                <a href="{{ route('sale-returns.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    New Return
                </a>
            @endcan
        </div>
    </div>

    <!-- Filters Panel -->
    <div id="filtersCard" class="hidden mb-6">
        <x-card title="Filter Sales Returns" subtitle="Provide parameters to narrow down return results.">
            <form method="GET" action="{{ route('sale-returns.index') }}" id="filterForm">
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-6 text-[14px]">
                    <!-- Return Number -->
                    <div class="sm:col-span-2">
                        <x-input label="Return Number" name="return_no" :value="request('return_no')" placeholder="RET-YYYYMMDD-XXXXX" />
                    </div>

                    <!-- Sale Invoice Number -->
                    <div class="sm:col-span-2">
                        <x-input label="Sale Invoice Number" name="sale_invoice" :value="request('sale_invoice')" placeholder="INV-YYYYMMDD-XXXXX" />
                    </div>

                    <!-- Customer -->
                    <div class="sm:col-span-2">
                        <x-select label="Customer" name="customer_id">
                            <option value="">All Customers</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Date Range -->
                    <div class="sm:col-span-3 grid grid-cols-2 gap-2">
                        <div>
                            <x-input label="Return Date From" type="date" name="start_date" :value="request('start_date')" />
                        </div>
                        <div>
                            <x-input label="Return Date To" type="date" name="end_date" :value="request('end_date')" />
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('sale-returns.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-750 transition-colors">
                        Reset Filters
                    </a>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 px-5 py-2 text-xs font-semibold text-white shadow-md transition-all active:scale-[0.98]">
                        Apply Filters
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- DataTables Card -->
    <x-card>
        <div class="overflow-x-auto">
            <table id="returnsTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Return No</th>
                        <th class="text-left py-3 px-4">Date</th>
                        <th class="text-left py-3 px-4">Sale Invoice</th>
                        <th class="text-left py-3 px-4">Customer</th>
                        <th class="text-left py-3 px-4">Grand Total</th>
                        <th class="text-left py-3 px-4">Refunded</th>
                        <th class="text-left py-3 px-4">Status</th>
                        <th class="text-left py-3 px-4">Created By</th>
                        <th class="text-center py-3 px-4 no-sort">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    @foreach ($returns as $index => $ret)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $ret->return_no }}</td>
                            <td class="py-3 px-4 text-slate-600 dark:text-slate-350">{{ $ret->return_date }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                @if($ret->sale)
                                    <a href="{{ route('sales.show', $ret->sale_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $ret->sale->invoice_no }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">{{ $ret->customer->name }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">₹{{ number_format($ret->grand_total, 2) }}</td>
                            <td class="py-3 px-4 text-emerald-600 dark:text-emerald-400 font-semibold">₹{{ number_format($ret->refunded_amount, 2) }}</td>
                            <td class="py-3 px-4">
                                @if ($ret->status === 'Completed')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-500/20">Completed</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-500/20">Pending</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $ret->user->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('sale_returns.view')
                                        <!-- View Return Profile -->
                                        <a href="{{ route('sale-returns.show', $ret->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/50 transition-colors" title="View Details">
                                            <i class="fa-regular fa-eye text-[11px]"></i>
                                        </a>
                                        
                                        <!-- Print Refund A4 Link -->
                                        <a href="{{ route('sale-returns.print', $ret->id) }}" target="_blank" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 dark:hover:bg-emerald-900/50 transition-colors" title="Print Refund Sheet">
                                            <i class="fa-solid fa-print text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('sale_returns.update')
                                        <!-- Edit Return Details -->
                                        <a href="{{ route('sale-returns.edit', $ret->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 dark:hover:bg-violet-900/50 transition-colors" title="Edit">
                                            <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                        </a>
                                    @endcan

                                    @can('sale_returns.delete')
                                        <!-- Delete Return -->
                                        <form id="delete-form-{{ $ret->id }}" action="{{ route('sale-returns.destroy', $ret->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-id="{{ $ret->id }}" data-return="{{ $ret->return_no }}" class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 dark:hover:bg-red-900/50 transition-colors" title="Delete">
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
        // Initialize DataTable
        $('#returnsTable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            language: {
                searchPlaceholder: "Search returns...",
                search: ""
            },
            dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
        });

        // Toggle Filters Card
        const filtersCard = $('#filtersCard');
        const filtersChevron = $('#filtersChevron');
        
        let filtersOpen = localStorage.getItem('returns_filters_open') === 'true';
        if (filtersOpen) {
            filtersCard.removeClass('hidden');
            filtersChevron.addClass('rotate-180');
        }

        $('#toggleFiltersBtn').on('click', function() {
            filtersCard.slideToggle(150, function() {
                const isOpen = filtersCard.is(':visible');
                filtersChevron.toggleClass('rotate-180', isOpen);
                localStorage.setItem('returns_filters_open', isOpen);
            });
        });

        // Setup SweetAlert2 delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const returnNo = $(this).data('return');
            const form = $(`#delete-form-${id}`);
            
            const isDark = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete sales return "${returnNo}". This will subtract returned products from inventory.`,
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
                            toastr.error('An error occurred while deleting the sales return.');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
