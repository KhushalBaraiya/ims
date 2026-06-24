@extends('layouts.admin')

@section('title', 'Purchase Order Details')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold mb-1 h4">Purchase Order Details</h2>
            <p class="text-muted small">Detailed view of supplier purchase, line items, and payment status.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Purchases
            </a>
            <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank"
                class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all active:scale-[0.98]">
                <i class="bx bx-printer"></i> Print Order
            </a>
            @can('purchases.update')
                <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit Order
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Summary Cards -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">Order Summary</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Purchase No</span>
                            <span
                                class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200 mt-1 block">{{ $purchase->purchase_no }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Purchase
                                Date</span>
                            <span
                                class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $purchase->purchase_date }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Supplier</span>
                            <span
                                class="text-sm font-bold text-slate-805 dark:text-slate-100 mt-1 block">{{ $purchase->supplier->name ?? '-' }}</span>
                            @if ($purchase->supplier?->phone)
                                <span
                                    class="text-xs text-slate-450 dark:text-slate-500 block mt-0.5">{{ $purchase->supplier->phone }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Reference
                                No</span>
                            <span
                                class="text-sm font-semibold text-slate-705 dark:text-slate-205 mt-1 block">{{ $purchase->reference_no ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status</span>
                            <div class="mt-1">
                                @if ($purchase->status === 'Completed')
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-500/20">Completed</span>
                                @elseif ($purchase->status === 'Draft')
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-500/20">Draft</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-500/10 px-2 py-0.5 text-[11px] font-bold text-red-700 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/20">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">Payment Details</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Payment
                                Method</span>
                            <span
                                class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $purchase->payment_method ?: '-' }}</span>
                        </div>
                        <div class="space-y-2">
                            <div
                                class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-400 uppercase">Subtotal</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">₹{{ number_format($purchase->sub_total, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-400 uppercase">Discount</span>
                                <span class="text-red-500 font-semibold">-
                                    ₹{{ number_format($purchase->discount_amount, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-400 uppercase">Tax</span>
                                <span class="text-orange-500 font-semibold">+
                                    ₹{{ number_format($purchase->tax_amount, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-400 uppercase">Shipping</span>
                                <span class="font-semibold text-slate-600 dark:text-slate-400">+
                                    ₹{{ number_format($purchase->shipping_amount, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 bg-slate-50 dark:bg-slate-800/50 rounded-lg px-2">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase">Grand
                                    Total</span>
                                <span
                                    class="text-base font-black text-blue-600 dark:text-blue-400">₹{{ number_format($purchase->grand_total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-xs font-bold text-emerald-600 uppercase">Paid Amount</span>
                                <span
                                    class="font-bold text-emerald-600">₹{{ number_format($purchase->paid_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-xs font-bold text-red-500 uppercase">Balance Due</span>
                                <span class="font-bold text-red-500">₹{{ number_format($purchase->due_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($purchase->notes)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">Notes</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $purchase->notes }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Line Items Table -->
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">Purchased Items</h6><small
                        class="text-muted">{{ $purchase->items->count() }} product(s) in this order</small>
                </div>
                <div class="card-body p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[13px]">
                            <thead>
                                <tr
                                    class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-3 px-4">#</th>
                                    <th class="py-3 px-4">Product</th>
                                    <th class="py-3 px-4">SKU</th>
                                    <th class="py-3 px-4 text-center">Qty</th>
                                    <th class="py-3 px-4 text-right">Unit Price</th>
                                    <th class="py-3 px-4 text-right">Discount</th>
                                    <th class="py-3 px-4 text-right">Tax</th>
                                    <th class="py-3 px-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($purchase->items as $index => $item)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="py-3 px-4 text-slate-400 font-semibold">{{ $index + 1 }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                @if ($item->product->image)
                                                    <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                        class="h-8 w-8 rounded object-cover border dark:border-slate-700 shadow-sm">
                                                @endif
                                                <div>
                                                    <p class="font-bold text-slate-800 dark:text-slate-200">
                                                        {{ $item->product->name }}</p>
                                                    <p class="text-[11px] text-slate-400">
                                                        {{ $item->product->unit_code ?? 'PCS' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                                            {{ $item->product->code }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                            {{ number_format($item->quantity, 2) }}</td>
                                        <td class="py-3 px-4 text-right font-semibold text-slate-700 dark:text-slate-300">
                                            ₹{{ number_format($item->purchase_price, 2) }}</td>
                                        <td class="py-3 px-4 text-right text-red-500">
                                            ₹{{ number_format($item->discount_amount, 2) }}</td>
                                        <td class="py-3 px-4 text-right text-orange-500">
                                            ₹{{ number_format($item->tax_amount, 2) }}</td>
                                        <td class="py-3 px-4 text-right font-bold text-slate-800 dark:text-slate-200">
                                            ₹{{ number_format($item->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
