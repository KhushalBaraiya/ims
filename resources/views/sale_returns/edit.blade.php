@extends('layouts.app')

@section('title', 'Edit Sales Return')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Edit Sales Return</h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Modify returned items, quantities, or details of return record: <span class="font-mono font-bold">{{ $saleReturn->return_no }}</span>.</p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="{{ route('sale-returns.update', $saleReturn->id) }}" id="returnForm" novalidate>
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-[14px]">
            
            <!-- Left Column: Return Info -->
            <div class="lg:col-span-1 space-y-6">
                <x-card title="Return Details">
                    <div class="space-y-4">
                        <!-- Invoice Number (Readonly) -->
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Invoice Number</label>
                            <input type="text" class="block w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-500 font-bold font-mono outline-none" value="{{ $saleReturn->sale->invoice_no }}" readonly>
                            <input type="hidden" name="sale_id" value="{{ $saleReturn->sale_id }}">
                        </div>

                        <!-- Customer (Readonly) -->
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Customer</label>
                            <input type="text" class="block w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-500 font-bold outline-none" value="{{ $saleReturn->customer->name }}" readonly>
                        </div>

                        <!-- Return Date -->
                        <div>
                            <x-input label="Return Date" type="date" name="return_date" :value="old('return_date', $saleReturn->return_date)" required />
                        </div>

                        <!-- Reference No -->
                        <div>
                            <x-input label="Reference No" name="reference_no" :value="old('reference_no', $saleReturn->reference_no)" placeholder="Optional reference..." />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-select label="Status" name="status" required>
                                <option value="Completed" {{ old('status', $saleReturn->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Pending" {{ old('status', $saleReturn->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                            </x-select>
                        </div>
                    </div>
                </x-card>
                
                <!-- Refund details card -->
                <x-card title="Refund Details">
                    <div class="space-y-4">
                        <!-- Refunded Amount -->
                        <div>
                            <x-input label="Refunded Amount" type="number" step="0.01" name="refunded_amount" id="refunded_amount" :value="old('refunded_amount', $saleReturn->refunded_amount)" required />
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
                                    <th class="py-2.5 px-3 w-28 text-center">Other Returns</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Unit Price</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Avail. Return</th>
                                    <th class="py-2.5 px-3 w-28 text-center">Return Qty</th>
                                    <th class="py-2.5 px-3">Reason</th>
                                    <th class="py-2.5 px-3 w-28 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]" id="returnItemsContainer">
                                @php $rowCount = 0; @endphp
                                @foreach($saleReturn->sale->items as $item)
                                    @php
                                        // Sum returned items in completed returns excluding this one
                                        $otherReturned = \App\Models\SaleReturnItem::whereHas('saleReturn', function($q) use ($saleReturn) {
                                            $q->where('sale_id', $saleReturn->sale_id)
                                              ->where('id', '!=', $saleReturn->id)
                                              ->where('status', 'Completed');
                                        })->where('product_id', $item->product_id)->sum('quantity');

                                        $currentReturnedItem = $saleReturn->items->where('product_id', $item->product_id)->first();
                                        $currentQty = $currentReturnedItem ? (float)$currentReturnedItem->quantity : 0.00;
                                        $currentReason = $currentReturnedItem ? $currentReturnedItem->reason : '';
                                        $maxReturnable = max(0.00, $item->quantity - $otherReturned);
                                    @endphp
                                    @if($maxReturnable > 0 || $currentQty > 0)
                                        <tr class="item-row hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors" data-product-id="{{ $item->product_id }}">
                                            <td class="py-3 px-3">
                                                @if ($item->product->image)
                                                    <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="h-8 w-8 rounded object-cover border dark:border-slate-700 shadow-sm">
                                                @else
                                                    <div class="h-8 w-8 bg-slate-50 dark:bg-slate-800 rounded flex items-center justify-center text-slate-405 border border-slate-100 dark:border-slate-700 shadow-inner">
                                                        <i class="fa-regular fa-image text-[10px]"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">
                                                {{ $item->product->name }}
                                                <input type="hidden" name="items[{{ $rowCount }}][product_id]" value="{{ $item->product_id }}">
                                            </td>
                                            <td class="py-3 px-3 text-center text-slate-500 font-semibold">{{ number_format($item->quantity, 2) }} {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                                            <td class="py-3 px-3 text-center text-slate-450 font-semibold">{{ number_format($otherReturned, 2) }} {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                                            <td class="py-3 px-3 text-center font-semibold price-cell" data-price="{{ $item->unit_price }}">₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="py-3 px-3 text-center font-bold text-slate-600 dark:text-slate-400 max-returnable-cell" data-max="{{ $maxReturnable }}">
                                                {{ number_format($maxReturnable, 2) }} {{ $item->product->unit->short_name ?? 'PCS' }}
                                            </td>
                                            <td class="py-3 px-3">
                                                <input type="number" step="0.01" min="0" max="{{ $maxReturnable }}" name="items[{{ $rowCount }}][quantity]" value="{{ number_format($currentQty, 2) }}" class="qty-input block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center font-bold focus:border-blue-500">
                                            </td>
                                            <td class="py-3 px-3">
                                                <input type="text" name="items[{{ $rowCount }}][reason]" value="{{ $currentReason }}" class="reason-input block w-full rounded border border-slate-305 dark:border-slate-855 bg-white dark:bg-slate-950 py-1 px-2 text-xs text-slate-800 dark:text-slate-100 outline-none placeholder-slate-400" placeholder="Reason (e.g. damaged)...">
                                            </td>
                                            <td class="py-3 px-3 font-bold text-right subtotal-cell text-slate-700 dark:text-slate-300">₹{{ number_format($currentQty * $item->unit_price, 2) }}</td>
                                        </tr>
                                        @php $rowCount++; @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>

                <!-- Summary & Notes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Notes -->
                    <x-card title="Return Notes">
                        <x-textarea name="notes" rows="6" :value="old('notes', $saleReturn->notes)" placeholder="State return reasons, item conditions, or general annotations..." />
                    </x-card>

                    <!-- Calculation Summary -->
                    <x-card title="Refund Summary">
                        <div class="space-y-4 text-xs">
                            <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-2">
                                <span class="text-slate-400 font-semibold uppercase">Total Refund Subtotal</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300" id="sum_subtotal">₹{{ number_format($saleReturn->sub_total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-2">
                                <span class="text-slate-700 dark:text-slate-200 font-bold uppercase text-[13px]">Grand Total Refund</span>
                                <span class="font-black text-blue-600 dark:text-blue-400 text-sm" id="sum_grandtotal">₹{{ number_format($saleReturn->grand_total, 2) }}</span>
                            </div>
                            
                            <div class="bg-blue-50 dark:bg-blue-950/20 p-3 rounded-xl border border-blue-100 dark:border-blue-900/30 flex justify-between items-center">
                                <span class="text-[10px] text-blue-500 font-bold uppercase">Customer Credit/Refund</span>
                                <span class="font-black text-blue-600 dark:text-blue-400 text-xs" id="summary_refunded">₹{{ number_format($saleReturn->refunded_amount, 2) }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Submit Row -->
                <div class="flex justify-end gap-3">
                    <x-button href="{{ route('sale-returns.index') }}" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary" id="submitBtn">
                        Update Return
                    </x-button>
                </div>

            </div>

        </div>
    </form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const refundedInput = $('#refunded_amount');

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

        // Run initial calculations
        calculateRefundTotals();
    });
</script>
@endpush
