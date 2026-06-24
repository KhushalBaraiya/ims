@extends('layouts.admin')

@section('title', 'Purchase Return Details')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold h4 mb-1">Purchase Return Details</h2>
            <p class="text-muted small">Detailed view of supplier return, returned items, and refund information.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('purchase-returns.index') }}">
                <i class="bx bx-arrow-back me-1"></i> Back to Returns
            </a>
            <a class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all hover:bg-emerald-500 active:scale-[0.98]"
                href="{{ route('purchase-returns.print', $purchaseReturn->id) }}" target="_blank">
                <i class="bx bx-printer"></i> Print Return
            </a>
            @can('purchase_returns.update')
                <a class="btn btn-primary" href="{{ route('purchase-returns.edit', $purchaseReturn->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit Return
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Return Summary</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Return No</span>
                            <span
                                class="mt-1 block font-mono text-sm font-bold text-slate-800 dark:text-slate-200">{{ $purchaseReturn->return_no }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Return Date</span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $purchaseReturn->return_date }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Purchase
                                Order</span>
                            <span
                                class="mt-1 block font-mono text-sm font-bold text-slate-600 dark:text-slate-400">{{ $purchaseReturn->purchase->purchase_no ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Supplier</span>
                            <span
                                class="mt-1 block text-sm font-bold text-slate-800 dark:text-slate-200">{{ $purchaseReturn->supplier->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Reference
                                No</span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-600 dark:text-slate-400">{{ $purchaseReturn->reference_no ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Status</span>
                            <div class="mt-1">
                                @if ($purchaseReturn->status === 'Completed')
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400">Completed</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Refund Details</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-3 text-sm">
                        <div
                            class="flex items-center justify-between border-b border-slate-100 py-1.5 dark:border-slate-800">
                            <span class="text-xs font-bold uppercase text-slate-400">Grand Total</span>
                            <span class="font-bold text-blue-600">{{ format_currency($purchaseReturn->grand_total) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-xs font-bold uppercase text-violet-500">Refunded</span>
                            <span
                                class="font-bold text-violet-600">{{ format_currency($purchaseReturn->refunded_amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($purchaseReturn->notes)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Notes</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="whitespace-pre-line text-sm text-slate-600 dark:text-slate-400">
                            {{ $purchaseReturn->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-9">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Returned Items</h6><small
                        class="text-muted">{{ $purchaseReturn->items->count() }} item(s) returned</small>
                </div>
                <div class="card-body p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[13px]">
                            <thead>
                                <tr
                                    class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-800/50">
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3 text-center">Return Qty</th>
                                    <th class="px-4 py-3">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($purchaseReturn->items as $index => $item)
                                    <tr class="transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                        <td class="px-4 py-3 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <p class="font-bold text-slate-800 dark:text-slate-200">
                                                {{ $item->product->name }}</p>
                                            <p class="font-mono text-[11px] text-slate-400">{{ $item->product->code }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-700 dark:text-slate-300">
                                            {{ number_format($item->quantity, 2) }}</td>
                                        <td class="px-4 py-3 text-[12px] italic text-slate-500">{{ $item->reason ?: '-' }}
                                        </td>
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
