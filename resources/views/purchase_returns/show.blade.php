@extends('layouts.admin')

@section('title', 'Purchase Return Details')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold mb-1 h4">Purchase Return Details</h2>
            <p class="text-muted small">Detailed view of supplier return, returned items, and refund information.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('purchase-returns.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Returns
            </a>
            <a href="{{ route('purchase-returns.print', $purchaseReturn->id) }}" target="_blank" class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all active:scale-[0.98]">
                <i class="bx bx-printer"></i> Print Return
            </a>
            @can('purchase_returns.update')
                <a href="{{ route('purchase-returns.edit', $purchaseReturn->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit Return
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Return Summary</h6></div><div class="card-body p-4">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Return No</span>
                        <span class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200 mt-1 block">{{ $purchaseReturn->return_no }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Return Date</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $purchaseReturn->return_date }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Purchase Order</span>
                        <span class="font-mono text-sm font-bold text-slate-600 dark:text-slate-400 mt-1 block">{{ $purchaseReturn->purchase->purchase_no ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Supplier</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-1 block">{{ $purchaseReturn->supplier->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Reference No</span>
                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 mt-1 block">{{ $purchaseReturn->reference_no ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status</span>
                        <div class="mt-1">
                            @if ($purchaseReturn->status === 'Completed')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20">Completed</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div></div>

            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Refund Details</h6></div><div class="card-body p-4">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-400 uppercase">Grand Total</span>
                        <span class="font-bold text-blue-600">₹{{ number_format($purchaseReturn->grand_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-xs font-bold text-violet-500 uppercase">Refunded</span>
                        <span class="font-bold text-violet-600">₹{{ number_format($purchaseReturn->refunded_amount, 2) }}</span>
                    </div>
                </div>
            </div></div>

            @if($purchaseReturn->notes)
            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Notes</h6></div><div class="card-body p-4">
                <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $purchaseReturn->notes }}</p>
            </div></div>
            @endif
        </div>

        <div class="col-lg-9">
            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Returned Items</h6><small class="text-muted">{{ $purchaseReturn->items->count() }} item(s) returned</small></div><div class="card-body p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Product</th>
                                <th class="py-3 px-4 text-center">Return Qty</th>
                                <th class="py-3 px-4">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($purchaseReturn->items as $index => $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="py-3 px-4 text-slate-400 font-semibold">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-800 dark:text-slate-200">{{ $item->product->name }}</p>
                                    <p class="text-[11px] font-mono text-slate-400">{{ $item->product->code }}</p>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-slate-700 dark:text-slate-300">{{ number_format($item->quantity, 2) }}</td>
                                <td class="py-3 px-4 text-slate-500 italic text-[12px]">{{ $item->reason ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div></div>
        </div>
    </div>
@endsection
