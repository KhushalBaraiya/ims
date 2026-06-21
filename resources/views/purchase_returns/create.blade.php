@extends('layouts.app')

@section('title', 'Create Purchase Return')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Create Purchase Return</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Return items to supplier by selecting a completed purchase order. Stock will be decremented automatically.</p>
    </div>

    <form method="POST" action="{{ route('purchase-returns.store') }}" id="returnForm" novalidate>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-[14px]">

            <!-- Left Column: Return Info -->
            <div class="lg:col-span-1 space-y-6">
                <x-card title="Return Details">
                    <div class="space-y-4">
                        <!-- Select Purchase -->
                        <div>
                            <x-select label="Purchase Order" name="purchase_id" id="purchase_id" required>
                                <option value="">Select Purchase Order</option>
                                @foreach($purchases as $p)
                                    <option value="{{ $p->id }}" {{ old('purchase_id', request('purchase_id')) == $p->id ? 'selected' : '' }}>
                                        {{ $p->purchase_no }} ({{ $p->supplier->name ?? '-' }})
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Return Date -->
                        <div>
                            <x-input label="Return Date" type="date" name="return_date" :value="old('return_date', date('Y-m-d'))" required />
                        </div>

                        <!-- Reference No -->
                        <div>
                            <x-input label="Reference No" name="reference_no" :value="old('reference_no')" placeholder="Optional reference..." />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-select label="Status" name="status" required>
                                <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Pending"   {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            </x-select>
                        </div>
                    </div>
                </x-card>

                <x-card title="Refund Details">
                    <div class="space-y-4">
                        <div>
                            <x-input label="Refunded Amount" type="number" step="0.01" name="refunded_amount" id="refunded_amount" :value="old('refunded_amount', '0.00')" required />
                            <p class="mt-1 text-[10px] text-slate-400">Total amount refunded to company / credited from supplier.</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Purchase Items -->
            <div class="lg:col-span-3 space-y-6">

                <x-card title="Purchase Return Items">
                    <div class="overflow-x-auto">
                        <table class="w-full text-slate-800 dark:text-slate-200 text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-2.5 px-3 w-16">Image</th>
                                    <th class="py-2.5 px-3">Product Name</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Purchased Qty</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Already Ret.</th>
                                    <th class="py-2.5 px-3 w-24 text-center">Stock</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Max Returnable</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Return Qty</th>
                                    <th class="py-2.5 px-3">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]" id="returnItemsContainer">
                            </tbody>
                        </table>

                        <div id="noInvoiceMsg" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-truck-ramp-box text-4xl mb-3 block"></i>
                            Select a purchase order to load its items.
                        </div>
                        <div id="emptyReturnMsg" class="py-12 text-center text-slate-400 dark:text-slate-500 hidden">
                            <i class="fa-solid fa-circle-check text-4xl mb-3 text-emerald-500 block"></i>
                            All items in this purchase have already been returned.
                        </div>
                    </div>
                </x-card>

                <!-- Notes & Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-card title="Return Notes">
                        <x-textarea name="notes" rows="6" :value="old('notes')" placeholder="Describe the reason for returning, item conditions, damage details..." />
                    </x-card>
                    <x-card title="Refund Summary">
                        <div class="space-y-4 text-xs">
                            <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-2">
                                <span class="text-slate-400 font-semibold uppercase">Return Subtotal</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300" id="sum_subtotal">₹0.00</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-2">
                                <span class="text-slate-700 dark:text-slate-200 font-bold uppercase text-[13px]">Grand Refund Total</span>
                                <span class="font-black text-blue-600 dark:text-blue-400 text-sm" id="sum_grandtotal">₹0.00</span>
                            </div>
                            <div class="bg-violet-50 dark:bg-violet-950/20 p-3 rounded-xl border border-violet-100 dark:border-violet-900/30 flex justify-between items-center">
                                <span class="text-[10px] text-violet-500 font-bold uppercase">Refunded to Company</span>
                                <span class="font-black text-violet-600 dark:text-violet-400 text-xs" id="summary_refunded">₹0.00</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button href="{{ route('purchase-returns.index') }}" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary" id="submitBtn" disabled>Process Return</x-button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const purchaseSelect = $('#purchase_id');
    const itemsContainer = $('#returnItemsContainer');
    const noInvoiceMsg   = $('#noInvoiceMsg');
    const emptyMsg       = $('#emptyReturnMsg');
    const submitBtn      = $('#submitBtn');
    const refundedInput  = $('#refunded_amount');

    function loadPurchaseItems(purchaseId) {
        if (!purchaseId) {
            itemsContainer.empty();
            noInvoiceMsg.removeClass('hidden');
            emptyMsg.addClass('hidden');
            submitBtn.attr('disabled', true);
            return;
        }

        noInvoiceMsg.addClass('hidden');
        emptyMsg.addClass('hidden');
        itemsContainer.html(`
            <tr>
                <td colspan="8" class="py-8 text-center text-slate-450">
                    <i class="fa-solid fa-circle-notch fa-spin text-xl mr-2 text-blue-500"></i> Loading purchase items...
                </td>
            </tr>
        `);

        $.ajax({
            url: `/purchases/${purchaseId}/return-data`,
            type: 'GET',
            success: function(data) {
                itemsContainer.empty();
                let rowCount = 0;
                let hasReturnableItems = false;

                data.forEach((item) => {
                    if (item.max_returnable > 0) {
                        hasReturnableItems = true;
                        itemsContainer.append(`
                            <tr class="item-row hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors" data-product-id="${item.product_id}">
                                <td class="py-3 px-3">
                                    <div class="h-8 w-8 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-box text-xs"></i>
                                    </div>
                                </td>
                                <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">
                                    ${item.name}
                                    <input type="hidden" name="items[${rowCount}][product_id]" value="${item.product_id}">
                                </td>
                                <td class="py-3 px-3 text-center text-slate-500 font-semibold">${parseFloat(item.purchased_qty).toFixed(2)}</td>
                                <td class="py-3 px-3 text-center text-amber-500 font-semibold">${parseFloat(item.already_returned).toFixed(2)}</td>
                                <td class="py-3 px-3 text-center text-slate-500 font-semibold">${parseFloat(item.stock).toFixed(2)}</td>
                                <td class="py-3 px-3 text-center font-bold text-emerald-600 dark:text-emerald-400 max-returnable-cell" data-max="${item.max_returnable}">
                                    ${parseFloat(item.max_returnable).toFixed(2)}
                                </td>
                                <td class="py-3 px-3">
                                    <input type="number" step="0.01" min="0" max="${item.max_returnable}"
                                        name="items[${rowCount}][quantity]" value="0.00"
                                        class="qty-input block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center font-bold focus:border-blue-500">
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="items[${rowCount}][reason]"
                                        class="block w-full rounded border border-slate-305 dark:border-slate-855 bg-white dark:bg-slate-950 py-1 px-2 text-xs text-slate-800 dark:text-slate-100 outline-none placeholder-slate-400"
                                        placeholder="Reason (optional)...">
                                </td>
                            </tr>
                        `);
                        rowCount++;
                    }
                });

                if (!hasReturnableItems) {
                    emptyMsg.removeClass('hidden');
                    submitBtn.attr('disabled', true);
                } else {
                    submitBtn.attr('disabled', false);
                }
                calculateTotals();
            },
            error: function() {
                itemsContainer.html('<tr><td colspan="8" class="py-8 text-center text-red-500 font-semibold">Failed to load purchase items. Please try again.</td></tr>');
            }
        });
    }

    purchaseSelect.on('change', function() { loadPurchaseItems($(this).val()); });
    if (purchaseSelect.val()) { loadPurchaseItems(purchaseSelect.val()); }

    $(document).on('input change', '.qty-input', function() {
        const val = parseFloat($(this).val()) || 0;
        const max = parseFloat($(this).closest('tr').find('.max-returnable-cell').data('max'));
        if (val > max) {
            $(this).val(max.toFixed(2));
            toastr.error(`Cannot return more than ${max.toFixed(2)} units.`);
        }
        if (val < 0) { $(this).val(0); }
        calculateTotals();
    });

    refundedInput.on('input change', function() {
        $('#summary_refunded').text('₹' + (parseFloat($(this).val()) || 0).toFixed(2));
    });

    function calculateTotals() {
        let total = 0;
        // No price in return items (just count qty), so show item count as indicator
        let totalQty = 0;
        $('#returnItemsContainer tr.item-row').each(function() {
            const qty = parseFloat($(this).find('.qty-input').val()) || 0;
            totalQty += qty;
        });
        // Auto-fill refunded amount based on qty (simplified)
        if (parseFloat(refundedInput.val()) === 0 || !refundedInput.data('manually-set')) {
            // Leave user to set refund amount — just update summary
        }
        $('#sum_subtotal').text(totalQty.toFixed(2) + ' units selected');
        $('#sum_grandtotal').text(totalQty.toFixed(2) + ' units to return');
        const refunded = parseFloat(refundedInput.val()) || 0;
        $('#summary_refunded').text('₹' + refunded.toFixed(2));
    }

    refundedInput.on('input', function() { $(this).data('manually-set', true); calculateTotals(); });

    $('#returnForm').on('submit', function(e) {
        let totalQty = 0;
        $('.qty-input').each(function() { totalQty += (parseFloat($(this).val()) || 0); });
        if (totalQty <= 0) {
            e.preventDefault();
            toastr.error('Please specify return quantity for at least one item.');
            return false;
        }
    });
});
</script>
@endpush
