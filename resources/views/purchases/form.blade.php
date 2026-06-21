@csrf

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-[14px]">

    <!-- Left Column: Order Info & Payment -->
    <div class="lg:col-span-1 space-y-6">
        <x-card title="Order Info">
            <div class="space-y-4">
                <!-- Purchase Number (readonly) -->
                <div>
                    <label class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Purchase No</label>
                    <input type="text" class="block w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-500 font-bold font-mono outline-none" value="{{ $purchase->purchase_no ?? 'AUTO-GENERATED' }}" readonly>
                </div>

                <!-- Purchase Date -->
                <div>
                    <x-input label="Purchase Date" type="date" name="purchase_date" :value="old('purchase_date', $purchase->purchase_date ?? date('Y-m-d'))" required />
                </div>

                <!-- Supplier -->
                <div>
                    <x-select label="Supplier" name="supplier_id" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id', $purchase->supplier_id ?? '') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} {{ $s->phone ? '('.$s->phone.')' : '' }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Purchase Person -->
                <div>
                    <x-select label="Purchase Person" name="purchase_person_id" required>
                        <option value="">Select Person</option>
                        @foreach($purchasePersons as $pp)
                            <option value="{{ $pp->id }}" {{ old('purchase_person_id', $purchase->purchase_person_id ?? auth()->id()) == $pp->id ? 'selected' : '' }}>
                                {{ $pp->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Reference No -->
                <div>
                    <x-input label="Reference / PO Number" name="reference_no" :value="old('reference_no', $purchase->reference_no ?? '')" placeholder="Optional reference..." />
                </div>

                <!-- Status -->
                <div>
                    <x-select label="Status" name="status" required>
                        <option value="Completed" {{ old('status', $purchase->status ?? 'Completed') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Draft"     {{ old('status', $purchase->status ?? '') === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Cancelled" {{ old('status', $purchase->status ?? '') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </x-select>
                </div>
            </div>
        </x-card>

        <!-- Supplier Invoice Details -->
        <x-card title="Supplier Invoice">
            <div class="space-y-4">
                <div>
                    <x-input label="Supplier Invoice No" name="invoice_no" :value="old('invoice_no', $purchase->invoice_no ?? '')" placeholder="Supplier's invoice number..." />
                </div>
                <div>
                    <x-input label="Supplier Invoice Date" type="date" name="invoice_date" :value="old('invoice_date', $purchase->invoice_date ?? '')" />
                </div>
            </div>
        </x-card>

        <!-- Payment Details -->
        <x-card title="Payment Details">
            <div class="space-y-4">
                <div>
                    <x-select label="Payment Method" name="payment_method" required>
                        <option value="Cash"          {{ old('payment_method', $purchase->payment_method ?? 'Cash') === 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank Transfer" {{ old('payment_method', $purchase->payment_method ?? '') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Card"          {{ old('payment_method', $purchase->payment_method ?? '') === 'Card' ? 'selected' : '' }}>Credit/Debit Card</option>
                        <option value="UPI / QR"      {{ old('payment_method', $purchase->payment_method ?? '') === 'UPI / QR' ? 'selected' : '' }}>UPI / QR Code</option>
                        <option value="Cheque"        {{ old('payment_method', $purchase->payment_method ?? '') === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    </x-select>
                </div>
                <div>
                    <x-input label="Paid Amount" type="number" step="0.01" name="paid_amount" id="paid_amount" :value="old('paid_amount', $purchase->paid_amount ?? '0.00')" required />
                </div>
            </div>
        </x-card>
    </div>

    <!-- Right Column: Products & Totals -->
    <div class="lg:col-span-3 space-y-6">

        <!-- Product Search Card -->
        <x-card title="Add Products to Order">
            <div class="relative">
                <div class="relative rounded-lg shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" id="productSearchInput" class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2.5 pl-10 pr-4 text-[14px] text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50" placeholder="Type Product Name, SKU, or Scan Barcode...">
                </div>
                <div id="autocompleteResults" class="absolute left-0 right-0 z-50 mt-1.5 hidden max-h-72 overflow-y-auto rounded-xl bg-white dark:bg-slate-800 p-1.5 shadow-xl border border-slate-200 dark:border-slate-700 ring-1 ring-slate-900/5"></div>
            </div>

            <!-- Items Table -->
            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-slate-800 dark:text-slate-200 text-left border-collapse" id="purchaseItemsTable">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <th class="py-2.5 px-3 w-16">Image</th>
                            <th class="py-2.5 px-3">Product Name</th>
                            <th class="py-2.5 px-3 w-28">SKU</th>
                            <th class="py-2.5 px-3 w-24 text-center">Qty</th>
                            <th class="py-2.5 px-3 w-28 text-center">Purchase Price</th>
                            <th class="py-2.5 px-3 w-24 text-center">Discount</th>
                            <th class="py-2.5 px-3 w-24 text-center">Tax</th>
                            <th class="py-2.5 px-3 w-28 text-right">Subtotal</th>
                            <th class="py-2.5 px-3 w-12 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]" id="purchaseItemsContainer">
                        <!-- Dynamic rows injected here -->
                    </tbody>
                </table>
                <div id="emptyTableMsg" class="py-8 text-center text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                    No products added to the purchase order.
                </div>
            </div>
        </x-card>

        <!-- Notes & Totals -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card title="Order Notes">
                <x-textarea name="notes" rows="6" :value="old('notes', $purchase->notes ?? '')" placeholder="Delivery instructions, warranty terms, special conditions..." />
            </x-card>

            <x-card title="Calculation Summary">
                <div class="space-y-3.5 text-xs">
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-400 font-semibold uppercase">Subtotal</span>
                        <span class="font-bold text-slate-700 dark:text-slate-305" id="sum_subtotal">₹0.00</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-400 font-semibold uppercase">Total Discount (-)</span>
                        <input type="number" step="0.01" min="0" name="discount_amount" id="discount_amount" class="w-28 text-right rounded border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-1 px-2 text-xs font-bold text-slate-800 dark:text-slate-100 outline-none" value="{{ old('discount_amount', $purchase->discount_amount ?? '0.00') }}">
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-400 font-semibold uppercase">Total Tax (+)</span>
                        <input type="number" step="0.01" min="0" name="tax_amount" id="tax_amount" class="w-28 text-right rounded border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-1 px-2 text-xs font-bold text-slate-800 dark:text-slate-100 outline-none" value="{{ old('tax_amount', $purchase->tax_amount ?? '0.00') }}">
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-400 font-semibold uppercase">Shipping / Freight (+)</span>
                        <input type="number" step="0.01" min="0" name="shipping_amount" id="shipping_amount" class="w-28 text-right rounded border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-1 px-2 text-xs font-bold text-slate-800 dark:text-slate-100 outline-none" value="{{ old('shipping_amount', $purchase->shipping_amount ?? '0.00') }}">
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-700 dark:text-slate-200 font-bold uppercase text-[13px]">Grand Total</span>
                        <span class="font-black text-blue-600 dark:text-blue-400 text-sm" id="sum_grandtotal">₹0.00</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-1.5">
                        <div class="bg-red-50 dark:bg-red-950/20 p-2.5 rounded-xl border border-red-100 dark:border-red-900/30 flex justify-between items-center">
                            <span class="text-[10px] text-red-500 font-bold uppercase">Balance Due</span>
                            <span class="font-black text-red-600 dark:text-red-400 text-xs" id="sum_due">₹0.00</span>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-950/20 p-2.5 rounded-xl border border-emerald-100 dark:border-emerald-900/30 flex justify-between items-center">
                            <span class="text-[10px] text-emerald-500 font-bold uppercase">Overpaid</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400 text-xs" id="sum_change">₹0.00</span>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Submit Row -->
        <div class="flex justify-end gap-3">
            <x-button href="{{ route('purchases.index') }}" variant="secondary">Cancel</x-button>
            <x-button type="submit" variant="primary">
                {{ isset($purchase) ? 'Update Purchase Order' : 'Save Purchase Order' }}
            </x-button>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let rowCount = 0;

    // Pre-load items for Edit mode
    @if(isset($purchase) && $purchase->items->count() > 0)
        @foreach($purchase->items as $item)
            addProductRow({
                id:             "{{ $item->product_id }}",
                name:           "{{ addslashes($item->product->name) }}",
                sku:            "{{ $item->product->code }}",
                purchase_price: parseFloat("{{ $item->purchase_price }}"),
                tax:            parseFloat("{{ $item->tax_amount }}") / parseFloat("{{ $item->quantity }}"),
                discount:       parseFloat("{{ $item->discount_amount }}") / parseFloat("{{ $item->quantity }}"),
                qty:            parseFloat("{{ $item->quantity }}"),
                unit:           "{{ $item->product->unit->short_name ?? 'PCS' }}",
                image_url:      "{{ $item->product->image ? asset('uploads/products/'.$item->product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image' }}"
            });
        @endforeach
    @endif

    // AJAX product search
    const searchInput = $('#productSearchInput');
    const resultsContainer = $('#autocompleteResults');
    let searchTimeout = null;

    searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();
        if (query.length < 1) { resultsContainer.addClass('hidden').empty(); return; }
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: "{{ route('purchases.search-products') }}",
                type: "GET",
                data: { query: query },
                success: function(data) {
                    resultsContainer.empty();
                    if (data.length > 0) {
                        data.forEach(product => {
                            resultsContainer.append(`
                                <div class="autocomplete-item flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-750 cursor-pointer transition-colors"
                                     data-id="${product.id}"
                                     data-name="${product.name}"
                                     data-sku="${product.sku}"
                                     data-purchase-price="${product.purchase_price}"
                                     data-tax="${product.tax}"
                                     data-discount="${product.discount}"
                                     data-unit="${product.unit}"
                                     data-image="${product.image_url}">
                                    <div class="flex items-center gap-3">
                                        <img src="${product.image_url}" class="h-8 w-8 rounded object-cover border dark:border-slate-700 shadow-sm">
                                        <div class="text-[12px]">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 leading-tight">${product.name}</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">SKU: ${product.sku}</p>
                                        </div>
                                    </div>
                                    <div class="text-right text-[11px]">
                                        <p class="font-bold text-violet-600 dark:text-violet-400">${product.currency_symbol}${parseFloat(product.purchase_price).toFixed(2)}</p>
                                        <p class="text-[10px] text-slate-500">Stock: ${parseFloat(product.stock).toFixed(2)} ${product.unit}</p>
                                    </div>
                                </div>
                            `);
                        });
                        resultsContainer.removeClass('hidden');
                    } else {
                        resultsContainer.html('<p class="p-3 text-xs text-slate-400 text-center">No products found.</p>').removeClass('hidden');
                    }
                }
            });
        }, 250);
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#productSearchInput, #autocompleteResults').length) {
            resultsContainer.addClass('hidden');
        }
    });

    // Add product row from autocomplete
    $(document).on('click', '.autocomplete-item', function() {
        const product = {
            id:             $(this).data('id'),
            name:           $(this).data('name'),
            sku:            $(this).data('sku'),
            purchase_price: parseFloat($(this).data('purchase-price')),
            tax:            parseFloat($(this).data('tax')),
            discount:       parseFloat($(this).data('discount')),
            unit:           $(this).data('unit'),
            image_url:      $(this).data('image'),
            qty:            1
        };

        // Prevent duplicates
        let isDuplicate = false;
        $('#purchaseItemsContainer tr').each(function() {
            if ($(this).data('product-id') == product.id) { isDuplicate = true; return false; }
        });
        if (isDuplicate) {
            toastr.warning(`"${product.name}" is already in the order.`);
            resultsContainer.addClass('hidden').empty();
            searchInput.val('');
            return;
        }

        addProductRow(product);
        resultsContainer.addClass('hidden').empty();
        searchInput.val('');
    });

    function addProductRow(product) {
        $('#emptyTableMsg').addClass('hidden');

        const itemTax  = (product.tax / 100) * product.purchase_price;
        const itemDisc = (product.discount / 100) * product.purchase_price;
        const price    = product.purchase_price || 0;

        const tr = `
            <tr class="item-row hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors" data-product-id="${product.id}">
                <td class="py-3 px-3">
                    <img src="${product.image_url}" class="h-8 w-8 rounded object-cover border dark:border-slate-700 shadow-sm">
                </td>
                <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">
                    ${product.name}
                    <input type="hidden" name="items[${rowCount}][product_id]" value="${product.id}">
                </td>
                <td class="py-3 px-3 font-mono text-[11px] text-slate-500">${product.sku}</td>
                <td class="py-3 px-3">
                    <input type="number" step="0.01" min="0.01" name="items[${rowCount}][quantity]" value="${product.qty}" class="qty-input block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center font-bold">
                </td>
                <td class="py-3 px-3">
                    <input type="number" step="0.01" min="0" name="items[${rowCount}][purchase_price]" value="${price.toFixed(2)}" class="price-input block w-24 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center font-semibold">
                </td>
                <td class="py-3 px-3">
                    <input type="number" step="0.01" min="0" name="items[${rowCount}][discount_amount]" value="${itemDisc.toFixed(2)}" class="discount-input block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center">
                </td>
                <td class="py-3 px-3">
                    <input type="number" step="0.01" min="0" name="items[${rowCount}][tax_amount]" value="${itemTax.toFixed(2)}" class="tax-input block w-20 mx-auto rounded border border-slate-350 dark:border-slate-850 bg-white dark:bg-slate-950 py-1 px-1.5 text-xs text-slate-800 dark:text-slate-100 outline-none text-center">
                </td>
                <td class="py-3 px-3 font-bold text-right subtotal-cell text-slate-700 dark:text-slate-300">₹0.00</td>
                <td class="py-3 px-3 text-center">
                    <button type="button" class="remove-row-btn text-red-500 hover:text-red-700 transition-colors focus:outline-none" title="Remove"><i class="fa-regular fa-trash-can"></i></button>
                </td>
            </tr>
        `;

        $('#purchaseItemsContainer').append(tr);
        rowCount++;
        calculateTotals();
    }

    // Remove row
    $(document).on('click', '.remove-row-btn', function() {
        $(this).closest('tr').remove();
        if ($('#purchaseItemsContainer tr').length === 0) { $('#emptyTableMsg').removeClass('hidden'); }
        calculateTotals();
    });

    // Recalculate on changes
    $(document).on('input change', '.qty-input, .price-input, .discount-input, .tax-input', function() {
        const row = $(this).closest('tr');
        const qty = parseFloat(row.find('.qty-input').val()) || 0;
        if (qty <= 0) { row.find('.qty-input').val(0.01); toastr.warning('Quantity must be greater than zero.'); }
        calculateTotals();
    });

    $('#discount_amount, #tax_amount, #shipping_amount, #paid_amount').on('input change', calculateTotals);

    function calculateTotals() {
        let totalSubtotal = 0, sumItemTax = 0, sumItemDiscount = 0;

        $('#purchaseItemsContainer tr').each(function() {
            const row      = $(this);
            const qty      = parseFloat(row.find('.qty-input').val()) || 0;
            const price    = parseFloat(row.find('.price-input').val()) || 0;
            const disc     = parseFloat(row.find('.discount-input').val()) || 0;
            const tax      = parseFloat(row.find('.tax-input').val()) || 0;
            const itemSub  = price * qty;
            const rowTotal = (price + tax - disc) * qty;
            row.find('.subtotal-cell').text('₹' + rowTotal.toFixed(2));
            totalSubtotal   += itemSub;
            sumItemTax      += (tax * qty);
            sumItemDiscount += (disc * qty);
        });

        $('#sum_subtotal').text('₹' + totalSubtotal.toFixed(2));
        $('#discount_amount').val(sumItemDiscount.toFixed(2));
        $('#tax_amount').val(sumItemTax.toFixed(2));

        const globalDisc  = parseFloat($('#discount_amount').val()) || 0;
        const globalTax   = parseFloat($('#tax_amount').val()) || 0;
        const shipping    = parseFloat($('#shipping_amount').val()) || 0;
        const grandTotal  = totalSubtotal + globalTax + shipping - globalDisc;

        $('#sum_grandtotal').text('₹' + grandTotal.toFixed(2));

        const paid   = parseFloat($('#paid_amount').val()) || 0;
        const due    = Math.max(0.00, grandTotal - paid);
        const change = Math.max(0.00, paid - grandTotal);

        $('#sum_due').text('₹' + due.toFixed(2));
        $('#sum_change').text('₹' + change.toFixed(2));
    }
});
</script>
@endpush
