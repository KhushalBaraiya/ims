@extends('layouts.admin')

@section('title', 'Purchase Order Details')

@section('content')
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold h4 mb-1">Purchase Order Details</h2>
            <p class="text-muted small">Detailed view of supplier purchase, line items, and payment status.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('purchases.index') }}">
                <i class="bx bx-arrow-back me-1"></i> Back to Purchases
            </a>
            <a class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all hover:bg-emerald-500 active:scale-[0.98]"
                href="{{ route('purchases.print', $purchase->id) }}" target="_blank">
                <i class="bx bx-printer"></i> Print Order
            </a>
            @can('purchases.update')
                <a class="btn btn-primary" href="{{ route('purchases.edit', $purchase->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit Order
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Summary Cards -->
        <div class="col-lg-3">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Order Summary</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Purchase No</span>
                            <span
                                class="mt-1 block font-mono text-sm font-bold text-slate-800 dark:text-slate-200">{{ $purchase->purchase_no }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Purchase
                                Date</span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $purchase->purchase_date }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Supplier</span>
                            <span
                                class="text-slate-805 mt-1 block text-sm font-bold dark:text-slate-100">{{ $purchase->supplier->name ?? '-' }}</span>
                            @if ($purchase->supplier?->phone)
                                <span
                                    class="text-slate-450 mt-0.5 block text-xs dark:text-slate-500">{{ $purchase->supplier->phone }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Reference
                                No</span>
                            <span
                                class="text-slate-705 dark:text-slate-205 mt-1 block text-sm font-semibold">{{ $purchase->reference_no ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Status</span>
                            <div class="mt-1">
                                @if ($purchase->status === 'Completed')
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">Completed</span>
                                @elseif ($purchase->status === 'Draft')
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20">Draft</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-bold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Payment Details</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Payment
                                Method</span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $purchase->payment_method ?: '-' }}</span>
                        </div>
                        <div class="space-y-2">
                            <div
                                class="flex items-center justify-between border-b border-slate-100 py-1.5 dark:border-slate-800">
                                <span class="text-xs font-bold uppercase text-slate-400">Subtotal</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">{{ format_currency($purchase->sub_total) }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between border-b border-slate-100 py-1.5 dark:border-slate-800">
                                <span class="text-xs font-bold uppercase text-slate-400">Discount</span>
                                <span class="font-semibold text-red-500">-
                                    {{ format_currency($purchase->discount_amount) }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between border-b border-slate-100 py-1.5 dark:border-slate-800">
                                <span class="text-xs font-bold uppercase text-slate-400">Tax</span>
                                <span class="font-semibold text-orange-500">+
                                    {{ format_currency($purchase->tax_amount) }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between border-b border-slate-100 py-1.5 dark:border-slate-800">
                                <span class="text-xs font-bold uppercase text-slate-400">Shipping</span>
                                <span class="font-semibold text-slate-600 dark:text-slate-400">+
                                    {{ format_currency($purchase->shipping_amount) }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-lg bg-slate-50 px-2 py-2 dark:bg-slate-800/50">
                                <span class="text-sm font-bold uppercase text-slate-700 dark:text-slate-200">Grand
                                    Total</span>
                                <span
                                    class="text-base font-black text-blue-600 dark:text-blue-400">{{ format_currency($purchase->grand_total) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-xs font-bold uppercase text-emerald-600">Paid Amount</span>
                                <span
                                    class="font-bold text-emerald-600">{{ format_currency($purchase->paid_amount) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-xs font-bold uppercase text-red-500">Balance Due</span>
                                <span class="font-bold text-red-500">{{ format_currency($purchase->due_amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($purchase->notes)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Notes</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="whitespace-pre-line text-sm text-slate-600 dark:text-slate-400">{{ $purchase->notes }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Line Items Table -->
        <div class="col-lg-9">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Purchased Items</h6><small
                        class="text-muted">{{ $purchase->items->count() }} product(s) in this order</small>
                </div>
                <div class="card-body p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[13px]">
                            <thead>
                                <tr
                                    class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-800/50">
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3">SKU</th>
                                    <th class="px-4 py-3 text-center">Qty</th>
                                    <th class="px-4 py-3 text-right">Unit Price</th>
                                    <th class="px-4 py-3 text-right">Discount</th>
                                    <th class="px-4 py-3 text-right">Tax</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($purchase->items as $index => $item)
                                    <tr class="transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                        <td class="px-4 py-3 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                @if ($item->product->image)
                                                    <img class="h-8 w-8 rounded border object-cover shadow-sm dark:border-slate-700"
                                                        src="{{ asset('uploads/products/' . $item->product->image) }}">
                                                @endif
                                                <div>
                                                    <p class="font-bold text-slate-800 dark:text-slate-200">
                                                        {{ $item->product->name }}</p>
                                                    <p class="text-[11px] text-slate-400">
                                                        {{ $item->product->unit_code ?? 'PCS' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-[11px] text-slate-500">
                                            {{ $item->product->code }}</td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-700 dark:text-slate-300">
                                            {{ number_format($item->quantity, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">
                                            {{ format_currency($item->purchase_price) }}</td>
                                        <td class="px-4 py-3 text-right text-red-500">
                                            {{ format_currency($item->discount_amount) }}</td>
                                        <td class="px-4 py-3 text-right text-orange-500">
                                            {{ format_currency($item->tax_amount) }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-slate-800 dark:text-slate-200">
                                            {{ format_currency($item->total_amount) }}</td>
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
