@extends('layouts.app')

@section('title', 'Sales Return Details')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Return Details</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Deep sales return review, refunded entries, and stock adjustments logs.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <x-button href="{{ route('sale-returns.index') }}" variant="secondary">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Returns
            </x-button>
            <a href="{{ route('sale-returns.print', $saleReturn->id) }}" target="_blank" class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all active:scale-[0.98]">
                <i class="fa-solid fa-print"></i> Print Return Sheet
            </a>
            @can('sale_returns.update')
                <x-button href="{{ route('sale-returns.edit', $saleReturn->id) }}" variant="primary">
                    <i class="fa-regular fa-pen-to-square mr-1"></i> Edit Return
                </x-button>
            @endcan
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-[14px]">
        <!-- Return Header Card -->
        <div class="lg:col-span-1 space-y-6">
            <x-card title="Return Summary">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Return No</span>
                        <span class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200 mt-1 block">{{ $saleReturn->return_no }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Return Date</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $saleReturn->return_date }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Original Sale Invoice</span>
                        <span class="font-mono text-sm font-bold text-blue-600 dark:text-blue-400 mt-1 block hover:underline">
                            @if($saleReturn->sale)
                                <a href="{{ route('sales.show', $saleReturn->sale_id) }}">{{ $saleReturn->sale->invoice_no }}</a>
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Customer</span>
                        <span class="text-sm font-bold text-slate-805 dark:text-slate-100 mt-1 block">{{ $saleReturn->customer->name }}</span>
                        @if($saleReturn->customer->phone)
                            <span class="text-xs text-slate-450 dark:text-slate-500 block mt-0.5">{{ $saleReturn->customer->phone }}</span>
                        @endif
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Processed By</span>
                        <span class="text-sm font-semibold text-slate-705 dark:text-slate-205 mt-1 block">{{ $saleReturn->user->name ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Reference No</span>
                        <span class="text-sm font-semibold text-slate-705 dark:text-slate-205 mt-1 block">{{ $saleReturn->reference_no ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status</span>
                        <div class="mt-1">
                            @if ($saleReturn->status === 'Completed')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-500/20">Completed</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-500/20">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card title="Refund Summary">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Return Value</span>
                        <span class="text-sm font-bold text-slate-750 dark:text-slate-200 mt-1 block">₹{{ number_format($saleReturn->grand_total, 2) }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Paid / Refunded Amount</span>
                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">₹{{ number_format($saleReturn->refunded_amount, 2) }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Items Table and Summary -->
        <div class="lg:col-span-3 space-y-6">
            <x-card title="Returned Items">
                <div class="overflow-x-auto">
                    <table class="w-full text-slate-800 dark:text-slate-200 text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                <th class="py-2.5 px-3 w-16">Image</th>
                                <th class="py-2.5 px-3">Product Name</th>
                                <th class="py-2.5 px-3">SKU</th>
                                <th class="py-2.5 px-3 text-right">Unit Price</th>
                                <th class="py-2.5 px-3 text-center">Return Qty</th>
                                <th class="py-2.5 px-3">Return Reason</th>
                                <th class="py-2.5 px-3 text-right font-bold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($saleReturn->items as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3 px-3">
                                        @if ($item->product->image)
                                            <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="h-8 w-8 rounded object-cover shadow-sm ring-1 ring-slate-200 dark:ring-slate-700">
                                        @else
                                            <div class="h-8 w-8 bg-slate-50 dark:bg-slate-800 rounded flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-inner">
                                                <i class="fa-regular fa-image text-[10px]"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">{{ $item->product->name }}</td>
                                    <td class="py-3 px-3 font-mono text-[11px] text-slate-550">{{ $item->product->code }}</td>
                                    <td class="py-3 px-3 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-3 text-center font-bold text-blue-600 dark:text-blue-400">{{ number_format($item->quantity, 2) }} {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                                    <td class="py-3 px-3 text-slate-600 dark:text-slate-400 italic">{{ $item->reason ?: 'No specified reason' }}</td>
                                    <td class="py-3 px-3 text-right font-bold text-slate-750 dark:text-slate-250">₹{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Bottom summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Notes -->
                <x-card title="Return Notes / Remarks">
                    <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-line leading-relaxed">{{ $saleReturn->notes ?: 'No customer notes or refund terms annotations added to this sales return.' }}</p>
                </x-card>

                <!-- Calculations summary -->
                <x-card title="Financial Adjustment">
                    <div class="space-y-3.5 text-xs">
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-400 font-semibold uppercase">Items Refund Subtotal</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">₹{{ number_format($saleReturn->sub_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-400 font-semibold uppercase">Total Tax Refundeded</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">₹{{ number_format($saleReturn->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-700 dark:text-slate-205 font-bold uppercase text-[13px]">Grand Refund Total</span>
                            <span class="font-black text-blue-600 dark:text-blue-400 text-sm">₹{{ number_format($saleReturn->grand_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-750 dark:text-slate-200 font-bold uppercase text-[13px]">Actual Cash/Credit Returned</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm">₹{{ number_format($saleReturn->refunded_amount, 2) }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
