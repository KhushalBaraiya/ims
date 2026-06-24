@extends('layouts.admin')

@section('title', 'Sales Invoice Details')

@section('content')
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold h4 mb-1">Invoice Details</h2>
            <p class="text-muted small">Deep transaction view, itemized entries, and payments history.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('sales.index') }}">
                <i class="bx bx-arrow-back me-1"></i> Back to Invoices
            </a>
            <a class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all hover:bg-emerald-500 active:scale-[0.98]"
                href="{{ route('sales.print', $sale->id) }}" target="_blank">
                <i class="bx bx-printer"></i> Print Invoice
            </a>
            @can('sales.update')
                <a class="btn btn-primary" href="{{ route('sales.edit', $sale->id) }}">
                    <i class="bx bx-edit me-1"></i> Edit Invoice
                </a>
            @endcan
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-4">
        <!-- Invoice Header Card -->
        <div class="col-lg-3">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Invoice Summary</h6>
                </div>
                <div class="card-body p-4">
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Invoice No</span>
                            <span
                                class="mt-1 block font-mono text-sm font-bold text-slate-800 dark:text-slate-200">{{ $sale->invoice_no }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Invoice
                                Date</span>
                            <span
                                class="mt-1 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $sale->invoice_date }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Customer</span>
                            <span
                                class="text-slate-805 mt-1 block text-sm font-bold dark:text-slate-100">{{ $sale->customer->name }}</span>
                            @if ($sale->customer->phone)
                                <span
                                    class="text-slate-450 mt-0.5 block text-xs dark:text-slate-500">{{ $sale->customer->phone }}</span>
                            @endif
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Sales
                                Person</span>
                            <span
                                class="text-slate-705 dark:text-slate-205 mt-1 block text-sm font-semibold">{{ $sale->salesPerson->name ?? '-' }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">PO / Reference
                                No</span>
                            <span
                                class="text-slate-705 dark:text-slate-205 mt-1 block text-sm font-semibold">{{ $sale->reference_no ?: '-' }}</span>
                        </div>

                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Status</span>
                            <div class="mt-1">
                                @if ($sale->status === 'Completed')
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">Completed</span>
                                @elseif ($sale->status === 'Draft')
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
                                class="mt-1 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $sale->payment_method ?: 'Not Specified' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Paid Amount</span>
                            <span
                                class="mt-1 block text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ format_currency($sale->paid_amount) }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Balance Due</span>
                            <span
                                class="mt-1 block text-sm font-bold text-red-600 dark:text-red-400">{{ format_currency($sale->due_amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table and Summary -->
        <div class="col-lg-9">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-white py-3">
                    <h6 class="fw-semibold mb-0">Invoice Items</h6>
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
                                    <th class="px-3 py-2.5 text-center">Quantity</th>
                                    <th class="px-3 py-2.5 text-right">Discount</th>
                                    <th class="px-3 py-2.5 text-right">Tax</th>
                                    <th class="px-3 py-2.5 text-right">Total Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($sale->items as $item)
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
                                        <td class="text-slate-650 dark:text-slate-350 px-3 py-3 text-center font-semibold">
                                            {{ number_format($item->quantity, 2) }}
                                            {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                                        <td class="px-3 py-3 text-right font-semibold text-red-500">
                                            {{ format_currency($item->discount_amount) }}</td>
                                        <td class="px-3 py-3 text-right font-semibold text-slate-600 dark:text-slate-400">
                                            {{ format_currency($item->tax_amount) }}</td>
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
                        <h6 class="fw-semibold mb-0">Invoice Notes</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ $sale->notes ?: 'No customer notes or annotations added to this invoice.' }}</p>
                    </div>
                </div>

                <!-- Calculations summary -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">Invoice Calculations</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="space-y-3.5 text-xs">
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="font-semibold uppercase text-slate-400">Subtotal</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">{{ format_currency($sale->sub_total) }}</span>
                            </div>
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="font-semibold uppercase text-slate-400">Discount (-)</span>
                                <span class="font-bold text-red-500">{{ format_currency($sale->discount_amount) }}</span>
                            </div>
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="font-semibold uppercase text-slate-400">Tax (+)</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">{{ format_currency($sale->tax_amount) }}</span>
                            </div>
                            <div
                                class="dark:border-slate-850 flex items-center justify-between border-b border-slate-50 pb-1.5">
                                <span class="font-semibold uppercase text-slate-400">Shipping / Delivery (+)</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-300">{{ format_currency($sale->shipping_amount) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-750 text-[13px] font-bold uppercase dark:text-slate-200">Grand
                                    Total</span>
                                <span
                                    class="text-sm font-black text-blue-600 dark:text-blue-400">{{ format_currency($sale->grand_total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
