@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')

    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Stock Management</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Monitor inventory levels, total valuations, and record
                manual stock adjustments.</p>
        </div>
    </div>

    <!-- ─────────────────────────────────────────────────────────────────────────
             Section 1 · Inventory Overview
        ──────────────────────────────────────────────────────────────────────────── -->
    <x-card>
        <!-- Card Header with inline search/filter -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-blue-500"></i>
                Inventory Overview
            </h3>
            <form method="GET" action="{{ route('stocks.index') }}" class="flex flex-wrap gap-2" id="filterForm">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or SKU…"
                        class="h-8 pl-8 pr-3 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/30 w-52">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2 text-slate-400 text-[11px]"></i>
                </div>
                <select name="main_category_id" onchange="this.form.submit()"
                    class="h-8 px-3 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('main_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
                @if (request('search') || request('main_category_id'))
                    <a href="{{ route('stocks.index') }}"
                        class="h-8 px-3 flex items-center text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <i class="fa-solid fa-xmark mr-1"></i> Clear
                    </a>
                @endif
                <button type="submit"
                    class="h-8 px-4 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-500 text-white transition-colors">
                    Search
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table id="inventoryTable" class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap"
                style="width:100%">
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="text-left py-3 px-4">#</th>
                        <th class="text-left py-3 px-4">Product Name</th>
                        <th class="text-left py-3 px-4">SKU</th>
                        <th class="text-left py-3 px-4">Category</th>
                        <th class="text-left py-3 px-4">Unit</th>
                        <th class="text-right py-3 px-4">Current Stock</th>
                        <th class="text-right py-3 px-4">Alert Level</th>
                        <th class="text-right py-3 px-4">Selling Price</th>
                        <th class="text-right py-3 px-4">Inventory Value</th>
                        <th class="text-center py-3 px-4 no-sort">Stock Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                    @forelse($products as $index => $product)
                        @php
                            $qty = $product->stock->quantity ?? 0;
                            $alert = $product->minimum_stock_alert ?? 0;
                            $isLow = $qty <= $alert;
                            $inventoryValue = $qty * $product->selling_price;
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $product->name }}
                                @if ($product->is_featured)
                                    <i class="fa-solid fa-star text-amber-400 text-[10px] ml-1" title="Featured"></i>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $product->code }}
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                {{ $product->mainCategory->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $product->unit_code ?? '-' }}</td>
                            <td
                                class="py-3 px-4 text-right font-bold text-lg {{ $isLow ? 'text-red-500' : 'text-emerald-500' }}">
                                {{ number_format($qty, 2) }}
                            </td>
                            <td class="py-3 px-4 text-right text-slate-500 dark:text-slate-400">
                                {{ number_format($alert, 2) }}
                            </td>
                            <td class="py-3 px-4 text-right text-blue-600 dark:text-blue-400 font-semibold">
                                {{ format_currency($product->selling_price) }}
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-700 dark:text-slate-300">
                                {{ format_currency($inventoryValue) }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($qty <= 0)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400">
                                        <i class="fa-solid fa-circle-xmark text-[9px]"></i> Out of Stock
                                    </span>
                                @elseif($isLow)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400">
                                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Low Stock
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i> In Stock
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                <i class="fa-solid fa-box-open text-2xl mb-2 block opacity-40"></i>
                                No products found matching your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($products->isNotEmpty())
                    <tfoot>
                        <tr
                            class="border-t-2 border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/40 text-[11px] font-bold text-slate-600 dark:text-slate-350">
                            <td colspan="7" class="py-3 px-4 text-right uppercase tracking-wide">Total Inventory Value:
                            </td>
                            <td colspan="3"
                                class="py-3 px-4 text-right text-base font-bold text-blue-600 dark:text-blue-400">
                                {{ format_currency($products->sum(fn($p) => ($p->stock->quantity ?? 0) * $p->selling_price)) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </x-card>

    <!-- ─────────────────────────────────────────────────────────────────────────
             Section 2 & 3 · Adjustment Form + Adjustment Log (side by side on lg+)
        ──────────────────────────────────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mt-0">

        <!-- Adjustment Form (left, narrower) -->
        @can('stocks.create')
            <div class="lg:col-span-2">
                <x-card>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 mb-5">
                        <i class="fa-solid fa-sliders text-violet-500"></i>
                        Record Stock Adjustment
                    </h3>

                    <form method="POST" action="{{ route('stocks.store') }}" class="space-y-4 text-[13px]">
                        @csrf

                        <!-- Product Dropdown -->
                        <div>
                            <label for="product_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Product <span class="text-red-500">*</span>
                            </label>
                            <select name="product_id" id="product_id" required
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 @error('product_id') border-red-500 @enderror">
                                <option value="">Select a product…</option>
                                @foreach ($allProducts as $p)
                                    <option value="{{ $p->id }}" data-stock="{{ $p->stock->quantity ?? 0 }}"
                                        data-unit="{{ $p->unit_code ?? 'Units' }}"
                                        {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                            @enderror

                            <!-- Live current stock display -->
                            <p class="mt-1.5 text-[11px] text-slate-400" id="currentStockDisplay"></p>
                        </div>

                        <!-- Adjustment Type -->
                        <div>
                            <label for="adjustment_type"
                                class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Adjustment Type <span class="text-red-500">*</span>
                            </label>
                            <select name="adjustment_type" id="adjustment_type" required
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 @error('adjustment_type') border-red-500 @enderror">
                                <option value="">Select type…</option>
                                <option value="Restock" {{ old('adjustment_type') === 'Restock' ? 'selected' : '' }}>Restock
                                    (Add Stock)</option>
                                <option value="Damage" {{ old('adjustment_type') === 'Damage' ? 'selected' : '' }}>Damage
                                    (Remove Stock)</option>
                                <option value="Return" {{ old('adjustment_type') === 'Return' ? 'selected' : '' }}>Return
                                    (Add Stock)</option>
                                <option value="Write-Off" {{ old('adjustment_type') === 'Write-Off' ? 'selected' : '' }}>
                                    Write-Off (Remove Stock)</option>
                                <option value="Correction" {{ old('adjustment_type') === 'Correction' ? 'selected' : '' }}>
                                    Correction (Add or Remove)</option>
                                <option value="Other" {{ old('adjustment_type') === 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                            @error('adjustment_type')
                                <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity Change -->
                        <div>
                            <label for="quantity_change"
                                class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Quantity Change <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="quantity_change" id="quantity_change" step="0.01"
                                value="{{ old('quantity_change') }}" placeholder="e.g. +10 to add, -3 to remove"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 @error('quantity_change') border-red-500 @enderror">
                            <p class="mt-1 text-[11px] text-slate-400">Use a positive number to add stock; negative to remove.
                            </p>
                            @error('quantity_change')
                                <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Reason for adjustment, reference number, etc."
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 resize-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-2.5 text-xs font-bold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Adjustment
                            </button>
                        </div>
                    </form>
                </x-card>
            </div>
        @endcan

        <!-- Adjustment History Log (right, wider) -->
        <div class="{{ auth()->user()->can('stocks.create') ? 'lg:col-span-3' : 'lg:col-span-5' }}">
            <x-card>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i>
                    Stock Adjustment History
                    <span class="ml-auto text-[11px] font-normal text-slate-400">Latest 50 records</span>
                </h3>

                <div class="overflow-x-auto">
                    <table id="adjustmentsTable"
                        class="w-full text-slate-800 dark:text-slate-200 display responsive nowrap" style="width:100%">
                        <thead>
                            <tr
                                class="bg-slate-50 dark:bg-slate-800/50 text-slate-405 dark:text-slate-450 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                <th class="text-left py-3 px-4">#</th>
                                <th class="text-left py-3 px-4">Product</th>
                                <th class="text-left py-3 px-4">Type</th>
                                <th class="text-right py-3 px-4">Change</th>
                                <th class="text-left py-3 px-4">Adjusted By</th>
                                <th class="text-left py-3 px-4">Notes</th>
                                <th class="text-left py-3 px-4">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                            @forelse($adjustments as $index => $adj)
                                @php $isPositive = $adj->quantity_change >= 0; @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="py-3 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="font-bold text-slate-800 dark:text-slate-200">{{ $adj->product->name ?? 'Deleted Product' }}</span>
                                        <br>
                                        <span
                                            class="font-mono text-[11px] text-slate-400">{{ $adj->product->code ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                            $typeColors = [
                                                'Restock' =>
                                                    'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400',
                                                'Damage' =>
                                                    'bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400',
                                                'Return' =>
                                                    'bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400',
                                                'Write-Off' =>
                                                    'bg-orange-100 dark:bg-orange-950/40 text-orange-700 dark:text-orange-400',
                                                'Correction' =>
                                                    'bg-violet-100 dark:bg-violet-950/40 text-violet-700 dark:text-violet-400',
                                                'Other' =>
                                                    'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                            ];
                                            $typeColor = $typeColors[$adj->adjustment_type] ?? $typeColors['Other'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $typeColor }}">
                                            {{ $adj->adjustment_type }}
                                        </span>
                                    </td>
                                    <td
                                        class="py-3 px-4 text-right font-bold text-base {{ $isPositive ? 'text-emerald-500' : 'text-red-500' }}">
                                        {{ $isPositive ? '+' : '' }}{{ number_format($adj->quantity_change, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 dark:text-slate-350 font-semibold">
                                        {{ $adj->user->name ?? 'System' }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 dark:text-slate-400 max-w-xs truncate"
                                        title="{{ $adj->notes }}">
                                        {{ $adj->notes ?: '—' }}
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        {{ $adj->created_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                        <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 block opacity-40"></i>
                                        No stock adjustments recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ── DataTables ──────────────────────────────────────────────────────────────
            $('#inventoryTable').DataTable({
                responsive: true,
                order: [
                    [5, 'asc']
                ], // sort by current stock asc so low-stock surfaces first
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }],
                language: {
                    searchPlaceholder: 'Search inventory…',
                    search: ''
                },
                dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
            });

            $('#adjustmentsTable').DataTable({
                responsive: true,
                order: [
                    [6, 'desc']
                ], // sort by date desc
                columnDefs: [{
                    targets: 'no-sort',
                    orderable: false
                }],
                language: {
                    searchPlaceholder: 'Search history…',
                    search: ''
                },
                dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-1"<"flex items-center"l><"flex items-center"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 px-1"ip>'
            });

            // ── Live "current stock" display when product is selected ───────────────────
            function updateStockDisplay() {
                const selected = $('#product_id option:selected');
                const stock = selected.data('stock');
                const unit = selected.data('unit') || 'Units';
                const $display = $('#currentStockDisplay');

                if (selected.val()) {
                    const isLow = parseFloat(stock) <= 0;
                    $display.html(
                        `Current stock: <strong class="${isLow ? 'text-red-500' : 'text-emerald-500'}">${parseFloat(stock).toFixed(2)} ${unit}</strong>`
                    );
                } else {
                    $display.text('');
                }
            }

            $('#product_id').on('change', updateStockDisplay);

            // Init on page load if old() value was set
            if ($('#product_id').val()) {
                updateStockDisplay();
            }

            // ── Currency switcher ───────────────────────────────────────────────────────
            const currencyDropdownBtn = document.getElementById('currency-dropdown-btn');
            const currencyDropdownMenu = document.getElementById('currency-dropdown-menu');

            if (currencyDropdownBtn && currencyDropdownMenu) {
                currencyDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    currencyDropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!currencyDropdownMenu.classList.contains('hidden') && !e.target.closest(
                            '#currency-dropdown-container')) {
                        currencyDropdownMenu.classList.add('hidden');
                    }
                });
            }

            $(document).on('click', '.switch-currency-btn', function() {
                const currencyId = $(this).data('id');
                $.ajax({
                    url: '{{ route('currencies.switch') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        currency_id: currencyId
                    },
                    success: function(res) {
                        if (res.success) {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to switch currency.');
                    }
                });
            });
        });
    </script>
@endpush
