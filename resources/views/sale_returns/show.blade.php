@extends('layouts.admin')

@section('title', 'Sales Return Details')

@section('content')
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold h4 mb-1">Return Details</h2>
            <p class="text-muted small">Deep sales return review, refunded entries, and stock adjustments logs.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('sale-returns.index') }}">
                <i class="bx bx-arrow-back me-1"></i> Back to Returns
            </a>
            <a class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all hover:bg-emerald-500 active:scale-[0.98]"
                href="{{ route('sale-returns.print', $saleReturn->id) }}" target="_blank">
                <i class="bx bx-printer"></i> Print Return Sheet
            </a>
            @can('sale_returns.update')
                <a class="btn btn-primary" href="{{ route('sale-returns.edit', $saleReturn->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit Return
                </a>
            @endcan
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-4">
        <!-- Return Header Card -->
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
                                class="mt-1 block font-mono text-sm font-bold text-slate-800 dark:text-slate-200">{{ $saleReturn->return_no }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Return Date</span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $saleReturn->return_date }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Original Sale
                                Invoice</span>
                            <span
                                class="mt-1 block font-mono text-sm font-bold text-blue-600 hover:underline dark:text-blue-400">
                                @if ($saleReturn->sale)
                                    <a
                                        href="{{ route('sales.show', $saleReturn->sale_id) }}">{{ $saleReturn->sale->invoice_no }}</a>
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Customer</span>
                            <span
                                class="text-slate-805 mt-1 block text-sm font-bold dark:text-slate-100">{{ $saleReturn->customer->name }}</span>
                            @if ($saleReturn->customer->phone)
                                <span
                                    class="text-slate-450 mt-0.5 block text-xs dark:text-slate-500">{{ $saleReturn->customer->phone }}</span>
                            @endif
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Processed
                                By</span>
                            <span
                                class="text-slate-705 dark:text-slate-205 mt-1 block text-sm font-semibold">{{ $saleReturn->user->name ?? '-' }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Reference
                                No</span>
                            <span
                                class="text-slate-705 dark:text-slate-205 mt-1 block text-sm font-semibold">{{ $saleReturn->reference_no ?: '-' }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Status</span>
                            <div class="mt-1">
                                @if ($saleReturn->status === 'Completed')
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">Completed</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Refund Summary</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Total Return
                                Value</span>
                            <span
                                class="text-slate-750 mt-1 block text-sm font-bold dark:text-slate-200">{{ format_currency($saleReturn->grand_total) }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Paid / Refunded
                                Amount</span>
                            <span
                                class="mt-1 block text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ format_currency($saleReturn->refunded_amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table and Summary -->
        <div class="col-lg-9">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Returned Items</h6>
                </div>
                <div class="card-body p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left text-xs text-slate-800 dark:text-slate-200">
                            <thead>
                                <tr
                                    class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-800/40">
                                    <th class="w-16 px-3 py-2.5">Image</th>
                                    <th class="px-3 py-2.5">Product Name</th>
                                    <th class="px-3 py-2.5">SKU</th>
                                    <th class="px-3 py-2.5 text-right">Unit Price</th>
                                    <th class="px-3 py-2.5 text-center">Return Qty</th>
                                    <th class="px-3 py-2.5">Return Reason</th>
                                    <th class="px-3 py-2.5 text-right font-bold">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($saleReturn->items as $item)
                                    <tr class="transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                                        <td class="px-3 py-3">
                                            @if ($item->product->image)
                                                <img class="h-8 w-8 rounded object-cover shadow-sm ring-1 ring-slate-200 dark:ring-slate-700"
                                                    src="{{ asset('uploads/products/' . $item->product->image) }}">
                                            @else
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded border border-slate-100 bg-slate-50 text-slate-400 shadow-inner dark:border-slate-700 dark:bg-slate-800">
                                                    <i class="bx bx-image text-[10px]"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 font-bold text-slate-800 dark:text-slate-200">
                                            {{ $item->product->name }}</td>
                                        <td class="text-slate-550 px-3 py-3 font-mono text-[11px]">
                                            {{ $item->product->code }}</td>
                                        <td class="px-3 py-3 text-right">{{ format_currency($item->unit_price) }}</td>
                                        <td class="px-3 py-3 text-center font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format($item->quantity, 2) }}
                                            {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                                        <td class="px-3 py-3 italic text-slate-600 dark:text-slate-400">
                                            {{ $item->reason ?: 'No specified reason' }}</td>
                                        <td class="text-slate-750 dark:text-slate-250 px-3 py-3 text-right font-bold">
                                            {{ format_currency($item->total_amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bottom summary -->
            <div class="row g-4">
                <!-- Notes -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Return Notes / Remarks</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ $saleReturn->notes ?: 'No customer notes or refund terms annotations added to this sales return.' }}
                        </p>
                    </div>
                </div>

                <!-- Calculations summary -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Financial Adjustment</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="space-y-3.5 text-xs">
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="font-semibold uppercase text-slate-400">Items Refund Subtotal</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">{{ format_currency($saleReturn->sub_total) }}</span>
                            </div>
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="font-semibold uppercase text-slate-400">Total Tax Refundeded</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">{{ format_currency($saleReturn->tax_amount) }}</span>
                            </div>
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="dark:text-slate-205 text-[13px] font-bold uppercase text-slate-700">Grand
                                    Refund Total</span>
                                <span
                                    class="text-sm font-black text-blue-600 dark:text-blue-400">{{ format_currency($saleReturn->grand_total) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-750 text-[13px] font-bold uppercase dark:text-slate-200">Actual
                                    Cash/Credit Returned</span>
                                <span
                                    class="text-sm font-black text-emerald-600 dark:text-emerald-400">{{ format_currency($saleReturn->refunded_amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
