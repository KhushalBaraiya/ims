@extends('layouts.app')

@section('title', 'Create Sales Return')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Sales Return</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Process refunds and return products to inventory by referencing a completed sale invoice.</p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sale-returns.store') }}" id="returnForm" novalidate>
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-[14px]">
            
            <!-- Left Column: Return Info -->
            <div class="lg:col-span-1 space-y-6">
                <x-card title="Return Details">
                    <div class="space-y-4">
                        <!-- Select Sale Invoice -->
                        <div>
                            <x-select label="Sales Invoice" name="sale_id" id="sale_id" required>
                                <option value="">Select Invoice</option>
                                @foreach($sales as $s)
                                    <option value="{{ $s->id }}" {{ old('sale_id', request('sale_id')) == $s->id ? 'selected' : '' }}>
                                        {{ $s->invoice_no }} ({{ $s->customer->name }})
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Return Date -->
                        <div>
                            <x-input label="Return Date" type="date" name="return_date" :value="old('return_date', date('Y-m-d'))" required />
                        </div>

                        <!-- Reference / PO Number -->
                        <div>
                            <x-input label="Reference No" name="reference_no" :value="old('reference_no')" placeholder="Optional reference..." />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-select label="Status" name="status" required>
                                <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            </x-select>
                        </div>
                    </div>
                </x-card>
                
                <!-- Refund details card -->
                <x-card title="Refund Details">
                    <div class="space-y-4">
                        <!-- Refunded Amount -->
                        <div>
                            <x-input label="Refunded Amount" type="number" step="0.01" name="refunded_amount" id="refunded_amount" :value="old('refunded_amount', '0.00')" required />
                            <p class="mt-1 text-[10px] text-slate-400">Total amount paid back to the customer.</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Sale Items Comparison Table -->
            <div class="lg:col-span-3 space-y-6">
                
                <!-- Items Table Card -->
                <x-card title="Invoice Return Items">
                    <div class="overflow-x-auto">
                        <table class="w-full text-slate-800 dark:text-slate-200 text-left border-collapse" id="returnItemsTable">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-2.5 px-3 w-16">Image</th>
                                    <th class="py-2.5 px-3">Product Name</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Sold Qty</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Already Ret.</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Unit Price</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Avail. Return</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Return Qty</th>
                                    <th class="py-2.5 px-3">Reason</th>
                                    <th class="py-2.5 px-3 w-28 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]" id="returnItemsContainer">
                                <!-- Dynamic items loaded via AJAX -->
                            </tbody>
                        </table>
                        
                        <div id="noInvoiceMsg" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-file-invoice text-4xl mb-3 block"></i>
                            Select a sales invoice on the left to load line items.
                        </div>
                        
                        <div id="emptyReturnMsg" class="py-12 text-center text-slate-400 dark:text-slate-500 hidden">
                            <i class="fa-solid fa-circle-check text-4xl mb-3 text-emerald-500 block"></i>
                            All items in this invoice have already been returned.
                        </div>
                    </div>
                </x-card>

                <!-- Summary & Notes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Notes -->
                    <x-card title="Return Notes">
                        <x-textarea name="notes" rows="6" :value="old('notes')" placeholder="State return reasons, item conditions, or general annotations..." />
                    </x-card>

                    <!-- Calculation Summary -->
                    <x-card title="Refund Summary">
                        <div class="space-y-4 text-xs">
                            <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-2">
                                <span class="text-slate-400 font-semibold uppercase">Total Refund Subtotal</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300" id="sum_subtotal">₹0.00</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-2">
                                <span class="text-slate-700 dark:text-slate-200 font-bold uppercase text-[13px]">Grand Total Refund</span>
                                <span class="font-black text-blue-600 dark:text-blue-400 text-sm" id="sum_grandtotal">₹0.00</span>
                            </div>
                            
                            <div class="bg-blue-50 dark:bg-blue-950/20 p-3 rounded-xl border border-blue-100 dark:border-blue-900/30 flex justify-between items-center">
                                <span class="text-[10px] text-blue-500 font-bold uppercase">Customer Credit/Refund</span>
                                <span class="font-black text-blue-600 dark:text-blue-400 text-xs" id="summary_refunded">₹0.00</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Submit Row -->
                <div class="flex justify-end gap-3">
                    <x-button href="{{ route('sale-returns.index') }}" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary" id="submitBtn" disabled>
                        Process Return
                    </x-button>
                </div>

            </div>

        </div>
    </form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const saleIdSelect = $('#sale_id');
        const itemsContainer = $('#returnItemsContainer');
        const noInvoiceMsg = $('#noInvoiceMsg');
        const emptyReturnMsg = $('#emptyReturnMsg');
        const submitBtn = $('#submitBtn');
        const refundedInput = $('#refunded_amount');

        // Function to load sale items
        function loadSaleItems(saleId) {
            if (!saleId) {
                itemsContainer.empty();
                noInvoiceMsg.removeClass('hidden');
                emptyReturnMsg.addClass('hidden');
                submitBtn.attr('disabled', true);
                calculateRefundTotals();
                return;
            }

            // Show loading placeholder
            noInvoiceMsg.addClass('hidden');
            emptyReturnMsg.addClass('hidden');
            itemsContainer.html(`
                <tr>
                    <td colspan="9" class="py-8 text-center text-slate-450">
                        <i class="fa-solid fa-circle-notch fa-spin text-xl mr-2 text-blue-500"></i> Loading invoice items...
                    </td>
                </tr>
            `);

            $.ajax({
                url: `/sales/${saleId}/return-data`,
                type: 'GET',
                success: function(response) {
                    itemsContainer.empty();
                    if (!response.success || response.items.length === 0) {
                        itemsContainer.html(`
                            <tr>
                                <td colspan="9" class="py-8 text-center text-red-500 font-semibold">
                                    Error loading invoice data.
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    let rowCount = 0;
                    let hasReturnableItems = false;

                    response.items.forEach((item, index) => {
                        if (item.available_quantity > 0) {
                            hasReturnableItems = true;
                            
                            itemsContainer.append(`
                                <tr class="item-row hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors" data-product-id="${item.product_id}">
                                    <td class="py-3 px-3">
                                        <img src="${item.image_url}" class="h-8 w-8 rounded object-cover border dark:border-slate-700 shadow-sm">
                                    </td>
                                    <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">
                                        ${item.name}
                                        <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                                    </td>
                                    <td class="py-3 px-3 text-center text-slate-500 font-semibold">${item.sold_quantity.toFixed(2)} ${item.unit}</td>
                                    <td class="py-3 px-3 text-center text-slate-450 font-semibold">${item.returned_quantity.toFixed(2)} ${item.unit}</td>
                                    <td class="py-3 px-3 text-center font-semibold price-cell" data-price="${item.unit_price}">₹${item.unit_price.toFixed(2)}</td>
                                    <td class="py-3 px-3 text-center font-bold text-slate-600 dark:text-slate-400 max-returnable-cell" data-max="${item.available_quantity}">
                                        ${item.available_quantity.toFixed(2)} ${item.unit}
                                    </td>
                                    <td class="py-3 px-3">
                                        <input type="number" step="0.01" min="0" max="${item.available_quantity}" name="items[${rowCount}][quantity]" value="0.00" class="qty-input block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center font-bold focus:border-blue-500">
                                    </td>
                                    <td class="py-3 px-3">
                                        <input type="text" name="items[${rowCount}][reason]" class="reason-input block w-full rounded border border-slate-305 dark:border-slate-855 bg-white dark:bg-slate-950 py-1 px-2 text-xs text-slate-800 dark:text-slate-100 outline-none placeholder-slate-400" placeholder="Reason (e.g. damaged)...">
                                    </td>
                                    <td class="py-3 px-3 font-bold text-right subtotal-cell text-slate-700 dark:text-slate-300">₹0.00</td>
                                </tr>
                            `);
                            rowCount++;
                        }
                    });

                    if (!hasReturnableItems) {
                        emptyReturnMsg.removeClass('hidden');
                        submitBtn.attr('disabled', true);
                    } else {
                        submitBtn.attr('disabled', false);
                    }

                    calculateRefundTotals();
                },
                error: function() {
                    itemsContainer.html(`
                        <tr>
                            <td colspan="9" class="py-8 text-center text-red-500 font-semibold">
                                Failed to fetch invoice items from server.
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Dropdown trigger
        saleIdSelect.on('change', function() {
            loadSaleItems($(this).val());
        });

        // Trigger load on startup if invoice ID is pre-selected (via query or old input)
        if (saleIdSelect.val()) {
            loadSaleItems(saleIdSelect.val());
        }

        // Qty input triggers calculations
        $(document).on('input change', '.qty-input', function() {
            const row = $(this);
            const qtyVal = parseFloat(row.val()) || 0;
            const maxVal = parseFloat(row.closest('tr').find('.max-returnable-cell').data('max'));

            if (qtyVal > maxVal) {
                row.val(maxVal.toFixed(2));
                toastr.error(`Cannot return more than available quantity (${maxVal.toFixed(2)} units).`);
            }

            if (qtyVal < 0) {
                row.val(0.00);
                toastr.warning('Return quantity cannot be negative.');
            }

            calculateRefundTotals();
        });

        // Refund input updates labels
        refundedInput.on('input change', function() {
            const val = parseFloat($(this).val()) || 0;
            $('#summary_refunded').text('₹' + val.toFixed(2));
        });

        // Refund Calculations
        function calculateRefundTotals() {
            let refundSubtotal = 0;

            $('#returnItemsContainer tr.item-row').each(function() {
                const row = $(this);
                const qty = parseFloat(row.find('.qty-input').val()) || 0;
                const price = parseFloat(row.find('.price-cell').data('price')) || 0;

                const rowSub = price * qty;
                row.find('.subtotal-cell').text('₹' + rowSub.toFixed(2));

                refundSubtotal += rowSub;
            });

            $('#sum_subtotal').text('₹' + refundSubtotal.toFixed(2));
            $('#sum_grandtotal').text('₹' + refundSubtotal.toFixed(2));
            
            // Auto match refunded amount to return subtotal on calculation if it's currently 0 or has not been custom set
            const currentRefunded = parseFloat(refundedInput.val()) || 0;
            if (currentRefunded === 0 || currentRefunded > refundSubtotal) {
                refundedInput.val(refundSubtotal.toFixed(2));
                $('#summary_refunded').text('₹' + refundSubtotal.toFixed(2));
            } else {
                $('#summary_refunded').text('₹' + currentRefunded.toFixed(2));
            }
        }

        // Prevent submitting form if no quantities are returned
        $('#returnForm').on('submit', function(e) {
            let totalReturnQty = 0;
            $('.qty-input').each(function() {
                totalReturnQty += (parseFloat($(this).val()) || 0);
            });

            if (totalReturnQty <= 0) {
                e.preventDefault();
                toastr.error('Please specify return quantity for at least one item.');
                return false;
            }
        });
    });
</script>
@endpush
