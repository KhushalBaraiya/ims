@extends('layouts.app')

@section('title', 'Purchase Returns')

@section('content')
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Purchase Returns</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Manage supplier purchase returns, track refunds, and reverse inventory stock.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <button type="button" id="toggleFiltersBtn" class="inline-flex items-center gap-x-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-350 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-filter"></i> Filters
                <i id="filtersChevron" class="fa-solid fa-chevron-down text-[10px] ml-0.5 transition-transform duration-200"></i>
            </button>
            @can('purchase_returns.create')
                <a href="{{ route('purchase-returns.create') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                    <i class="fa-solid fa-plus"></i> New Return
                </a>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div id="filtersCard" class="hidden mb-6">
        <x-card title="Filter Purchase Returns">
            <form method="GET" action="{{ route('purchase-returns.index') }}">
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-6 text-[14px]">
                    <div class="sm:col-span-2">
                        <x-input label="Return No" name="return_no" :value="request('return_no')" placeholder="PRET-YYYYMMDD-XXXXX" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-select label="Supplier" name="supplier_id">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div class="sm:col-span-2">
                        <x-select label="Status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Pending"   {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        </x-select>
                    </div>
                    <div class="sm:col-span-3 grid grid-cols-2 gap-2">
                        <div><x-input label="Return Date From" type="date" name="start_date" :value="request('start_date')" /></div>
                        <div><x-input label="Return Date To" type="date" name="end_date" :value="request('end_date')" /></div>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('purchase-returns.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-350 hover:bg-slate-50 transition-colors">Reset</a>
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-500 px-5 py-2 text-xs font-semibold text-white shadow-md transition-all">Apply Filters</button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- DataTable -->
    <x-card>
        <div class="overflow-x-auto">
            <table id="purchaseReturnsTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Return No</th>
                        <th class="text-left py-3 px-4">Return Date</th>
                        <th class="text-left py-3 px-4">Purchase No</th>
                        <th class="text-left py-3 px-4">Supplier</th>
                        <th class="text-left py-3 px-4">Grand Total</th>
                        <th class="text-left py-3 px-4">Refunded</th>
                        <th class="text-left py-3 px-4">Status</th>
                        <th class="text-left py-3 px-4">Created By</th>
                        <th class="text-center py-3 px-4 no-sort">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    @foreach ($returns as $index => $return)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $return->return_no }}</td>
                        <td class="py-3 px-4 text-slate-600 dark:text-slate-350">{{ $return->return_date }}</td>
                        <td class="py-3 px-4 font-mono text-slate-500 text-[11px]">{{ $return->purchase->purchase_no ?? '-' }}</td>
                        <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">{{ $return->supplier->name ?? '-' }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">₹{{ number_format($return->grand_total, 2) }}</td>
                        <td class="py-3 px-4 text-emerald-600 dark:text-emerald-400 font-semibold">₹{{ number_format($return->refunded_amount, 2) }}</td>
                        <td class="py-3 px-4">
                            @if ($return->status === 'Completed')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20">Completed</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20">Pending</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $return->user->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                @can('purchase_returns.view')
                                    <a href="{{ route('purchase-returns.show', $return->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-colors" title="View">
                                        <i class="fa-regular fa-eye text-[11px]"></i>
                                    </a>
                                    <a href="{{ route('purchase-returns.print', $return->id) }}" target="_blank" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 transition-colors" title="Print">
                                        <i class="fa-solid fa-print text-[11px]"></i>
                                    </a>
                                @endcan
                                @can('purchase_returns.update')
                                    <a href="{{ route('purchase-returns.edit', $return->id) }}" class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 transition-colors" title="Edit">
                                        <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                    </a>
                                @endcan
                                @can('purchase_returns.delete')
                                    <form id="delete-form-{{ $return->id }}" action="{{ route('purchase-returns.destroy', $return->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-id="{{ $return->id }}" data-no="{{ $return->return_no }}" class="delete-btn inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-colors" title="Delete">
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
    $('#purchaseReturnsTable').DataTable({
        responsive: true,
        columnDefs: [{ targets: 'no-sort', orderable: false }],
        language: { searchPlaceholder: "Search returns...", search: "" },
        dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
    });

    let filtersOpen = localStorage.getItem('pur_returns_filters_open') === 'true';
    if (filtersOpen) { $('#filtersCard').removeClass('hidden'); $('#filtersChevron').addClass('rotate-180'); }
    $('#toggleFiltersBtn').on('click', function() {
        $('#filtersCard').slideToggle(150, function() {
            const isOpen = $(this).is(':visible');
            $('#filtersChevron').toggleClass('rotate-180', isOpen);
            localStorage.setItem('pur_returns_filters_open', isOpen);
        });
    });

    $('.delete-btn').on('click', function() {
        const id   = $(this).data('id');
        const no   = $(this).data('no');
        const form = $(`#delete-form-${id}`);
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'Delete Purchase Return?',
            text: `Deleting "${no}" will reverse its stock changes.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete!',
            background: isDark ? '#18181b' : '#fff',
            color: isDark ? '#fff' : '#1e293b',
            customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-slate-800' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
