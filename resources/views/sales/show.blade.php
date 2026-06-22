@extends('layouts.admin')

@section('title', 'Sales Invoice Details')

@section('content')
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="">
            <h2 class="fw-bold mb-1 h4">Invoice Details</h2>
            <p class="text-muted small">Deep transaction view, itemized entries, and payments history.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Back to Invoices
            </a>
            <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="inline-flex items-center gap-x-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md transition-all active:scale-[0.98]">
                <i class="bx bx-printer"></i> Print Invoice
            </a>
            @can('sales.update')
                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> Edit Invoice
                </a>
            @endcan
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-4">
        <!-- Invoice Header Card -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Invoice Summary</h6></div><div class="card-body p-4">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Invoice No</span>
                        <span class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200 mt-1 block">{{ $sale->invoice_no }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Invoice Date</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $sale->invoice_date }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Customer</span>
                        <span class="text-sm font-bold text-slate-805 dark:text-slate-100 mt-1 block">{{ $sale->customer->name }}</span>
                        @if($sale->customer->phone)
                            <span class="text-xs text-slate-450 dark:text-slate-500 block mt-0.5">{{ $sale->customer->phone }}</span>
                        @endif
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Sales Person</span>
                        <span class="text-sm font-semibold text-slate-705 dark:text-slate-205 mt-1 block">{{ $sale->salesPerson->name ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">PO / Reference No</span>
                        <span class="text-sm font-semibold text-slate-705 dark:text-slate-205 mt-1 block">{{ $sale->reference_no ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status</span>
                        <div class="mt-1">
                            @if ($sale->status === 'Completed')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-500/20">Completed</span>
                            @elseif ($sale->status === 'Draft')
                                <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 text-[11px] font-bold text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-500/20">Draft</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-500/10 px-2 py-0.5 text-[11px] font-bold text-red-700 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/20">Cancelled</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div></div>

            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Payment Details</h6></div><div class="card-body p-4">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Payment Method</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1 block">{{ $sale->payment_method ?: 'Not Specified' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Paid Amount</span>
                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 mt-1 block">₹{{ number_format($sale->paid_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Balance Due</span>
                        <span class="text-sm font-bold text-red-600 dark:text-red-400 mt-1 block">₹{{ number_format($sale->due_amount, 2) }}</span>
                    </div>
                </div>
            </div></div>
        </div>

        <!-- Items Table and Summary -->
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Invoice Items</h6></div><div class="card-body p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-slate-800 dark:text-slate-200 text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                <th class="py-2.5 px-3 w-16">Image</th>
                                <th class="py-2.5 px-3">Product Name</th>
                                <th class="py-2.5 px-3">SKU</th>
                                <th class="py-2.5 px-3 text-right">Unit Price</th>
                                <th class="py-2.5 px-3 text-center">Quantity</th>
                                <th class="py-2.5 px-3 text-right">Discount</th>
                                <th class="py-2.5 px-3 text-right">Tax</th>
                                <th class="py-2.5 px-3 text-right">Total Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($sale->items as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3 px-3">
                                        @if ($item->product->image)
                                            <img src="{{ asset('uploads/products/' . $item->product->image) }}" class="h-8 w-8 rounded object-cover shadow-sm ring-1 ring-slate-200 dark:ring-slate-700">
                                        @else
                                            <div class="h-8 w-8 bg-slate-50 dark:bg-slate-800 rounded flex items-center justify-center text-slate-400 border border-slate-100 dark:border-slate-700 shadow-inner">
                                                <i class="bx bx-image text-[10px]"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 font-bold text-slate-800 dark:text-slate-200">{{ $item->product->name }}</td>
                                    <td class="py-3 px-3 font-mono text-[11px] text-slate-550">{{ $item->product->code }}</td>
                                    <td class="py-3 px-3 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-3 text-center font-semibold text-slate-650 dark:text-slate-350">{{ number_format($item->quantity, 2) }} {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                                    <td class="py-3 px-3 text-right text-red-500 font-semibold">₹{{ number_format($item->discount_amount, 2) }}</td>
                                    <td class="py-3 px-3 text-right text-slate-600 dark:text-slate-400 font-semibold">₹{{ number_format($item->tax_amount, 2) }}</td>
                                    <td class="py-3 px-3 text-right font-bold text-slate-750 dark:text-slate-250">₹{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div></div>

            <!-- Bottom summary -->
            <div class="row g-4">
                <!-- Notes -->
                <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Invoice Notes</h6></div><div class="card-body p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-line leading-relaxed">{{ $sale->notes ?: 'No customer notes or annotations added to this invoice.' }}</p>
                </div></div>

                <!-- Calculations summary -->
                <div class="card shadow-sm mb-4"><div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-semibold">Invoice Calculations</h6></div><div class="card-body p-4">
                    <div class="space-y-3.5 text-xs">
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-400 font-semibold uppercase">Subtotal</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">₹{{ number_format($sale->sub_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-400 font-semibold uppercase">Discount (-)</span>
                            <span class="font-bold text-red-500">₹{{ number_format($sale->discount_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-400 font-semibold uppercase">Tax (+)</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">₹{{ number_format($sale->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-50 dark:border-slate-850 pb-1.5">
                            <span class="text-slate-400 font-semibold uppercase">Shipping / Delivery (+)</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">₹{{ number_format($sale->shipping_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-750 dark:text-slate-200 font-bold uppercase text-[13px]">Grand Total</span>
                            <span class="font-black text-blue-600 dark:text-blue-400 text-sm">₹{{ number_format($sale->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div></div>
            </div>
        </div>
    </div>
@endsection
