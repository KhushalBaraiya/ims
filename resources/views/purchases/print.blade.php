<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $purchase->purchase_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            body {
                background: #fff;
                color: #000;
                font-size: 12px;
                line-height: 1.4;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 1.5cm;
            }
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen py-10 print:py-0 print:bg-white print:text-black">

    @php
        $sym = optional(current_currency())->symbol ?? '₹';
        $settings = \App\Models\Setting::pluck('value', 'key');
        $companyName = $settings['company_name'] ?? config('app.name', 'IMS');
        $companyAddress = $settings['company_address'] ?? '';
        $companyPhone = $settings['company_phone'] ?? '';
        $companyEmail = $settings['company_email'] ?? '';
        $companyLogo = $settings['company_logo'] ?? null;
    @endphp

    <!-- Top Action Bar (hidden on print) -->
    <div
        class="max-w-4xl mx-auto mb-6 px-4 no-print flex flex-wrap justify-between items-center gap-2 bg-white border border-slate-200 shadow-sm p-3.5 rounded-2xl">
        <span class="text-sm text-slate-500 font-medium">Purchase Order Preview: <strong
                class="text-slate-800 font-mono">{{ $purchase->purchase_no }}</strong></span>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                class="inline-flex items-center gap-x-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
            <a href="{{ route('purchases.show', $purchase->id) }}"
                class="inline-flex items-center gap-x-1.5 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-xl hover:bg-slate-50 transition-all">
                &larr; Back
            </a>
        </div>
    </div>

    <!-- A4 Document -->
    <div
        class="max-w-4xl mx-auto bg-white shadow-xl border border-slate-200 rounded-2xl p-10 print:shadow-none print:border-0 print:rounded-none">

        <!-- Header -->
        <div class="flex flex-wrap justify-between items-start gap-4 border-b border-slate-200 pb-8 mb-8">
            <div>
                @if ($companyLogo && file_exists(public_path('uploads/settings/' . $companyLogo)))
                    <img src="{{ asset('uploads/settings/' . $companyLogo) }}" alt="{{ $companyName }}"
                        style="height:52px;max-width:180px;object-fit:contain;margin-bottom:8px;display:block;">
                @endif
                <div
                    class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent mb-1 print:text-black print:bg-none">
                    ⚡ {{ $companyName }}
                </div>
                <p class="text-xs text-slate-400">Electronics & Inventory Management System</p>
                @if ($companyAddress)
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $companyAddress }}</p>
                @endif
                @if ($companyPhone || $companyEmail)
                    <p class="text-[11px] text-slate-400">
                        @if ($companyPhone)
                            Phone: {{ $companyPhone }}
                        @endif
                        @if ($companyPhone && $companyEmail)
                            &bull;
                        @endif
                        @if ($companyEmail)
                            Email: {{ $companyEmail }}
                        @endif
                    </p>
                @endif
            </div>
            <div class="text-right">
                <div class="text-3xl font-black text-slate-800 tracking-tight">PURCHASE ORDER</div>
                <div class="mt-2 text-sm font-mono font-bold text-violet-600">{{ $purchase->purchase_no }}</div>
                <div class="text-xs text-slate-400 mt-1">Date: {{ $purchase->purchase_date }}</div>
                <div class="mt-3">
                    @if ($purchase->status === 'received')
                        <span
                            class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">✔
                            RECEIVED</span>
                    @elseif ($purchase->status === 'pending')
                        <span
                            class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700 border border-amber-200">PENDING</span>
                    @elseif ($purchase->status === 'ordered')
                        <span
                            class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200">ORDERED</span>
                    @elseif ($purchase->status === 'draft')
                        <span
                            class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 border border-slate-200">DRAFT</span>
                    @else
                        <span
                            class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700 border border-red-200">{{ strtoupper($purchase->status) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Supplier & Order Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mb-8 print:grid-cols-2">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Supplier Details</h3>
                <p class="font-bold text-slate-800 text-base">{{ $purchase->supplier->name ?? 'N/A' }}</p>
                @if ($purchase->supplier?->email)
                    <p class="text-sm text-slate-500 mt-0.5">{{ $purchase->supplier->email }}</p>
                @endif
                @if ($purchase->supplier?->phone)
                    <p class="text-sm text-slate-500">{{ $purchase->supplier->phone }}</p>
                @endif
                @if ($purchase->supplier?->address)
                    <p class="text-sm text-slate-500 mt-1">{{ $purchase->supplier->address }}</p>
                @endif
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Order Info</h3>
                @if ($purchase->reference_no)
                    <div class="mb-1"><span class="text-xs text-slate-400">Reference No:</span> <span
                            class="font-semibold text-slate-700">{{ $purchase->reference_no }}</span></div>
                @endif
                <div><span class="text-xs text-slate-400">Payment Method:</span> <span
                        class="font-semibold text-slate-700">{{ $purchase->payment_method }}</span></div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto -mx-2 px-2 print:overflow-visible">
            <table class="w-full text-sm border-collapse mb-8" style="min-width:600px;">
                <thead>
                    <tr class="bg-slate-800 text-white text-xs uppercase">
                        <th class="py-3 px-4 text-left rounded-l-lg">#</th>
                        <th class="py-3 px-4 text-left">Product</th>
                        <th class="py-3 px-4 text-center">Unit</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Unit Price</th>
                        <th class="py-3 px-4 text-right">Discount</th>
                        <th class="py-3 px-4 text-right">Tax</th>
                        <th class="py-3 px-4 text-right rounded-r-lg">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $index => $item)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }} border-b border-slate-100">
                            <td class="py-3 px-4 text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-800">
                                    {{ $item->product->name ?? '(Deleted Product #' . $item->product_id . ')' }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $item->product->code ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-4 text-center text-slate-500">{{ $item->product->unit_code ?? 'PCS' }}
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-700">
                                {{ number_format($item->quantity, 2) }}</td>
                            <td class="py-3 px-4 text-right text-slate-700">
                                {{ $sym }}{{ number_format($item->purchase_price, 2) }}</td>
                            <td class="py-3 px-4 text-right text-red-500">
                                {{ $sym }}{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="py-3 px-4 text-right text-orange-500">
                                {{ $sym }}{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-slate-800">
                                {{ $sym }}{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-72 space-y-2 text-sm">
                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="font-semibold">{{ $sym }}{{ number_format($purchase->sub_total, 2) }}</span>
                </div>
                @if ($purchase->discount_amount > 0)
                    <div class="flex justify-between border-b border-slate-100 pb-1.5">
                        <span class="text-slate-500">Discount (-)</span>
                        <span
                            class="text-red-500 font-semibold">{{ $sym }}{{ number_format($purchase->discount_amount, 2) }}</span>
                    </div>
                @endif
                @if ($purchase->tax_amount > 0)
                    <div class="flex justify-between border-b border-slate-100 pb-1.5">
                        <span class="text-slate-500">Tax (+)</span>
                        <span
                            class="text-orange-500 font-semibold">{{ $sym }}{{ number_format($purchase->tax_amount, 2) }}</span>
                    </div>
                @endif
                @if ($purchase->shipping_amount > 0)
                    <div class="flex justify-between border-b border-slate-100 pb-1.5">
                        <span class="text-slate-500">Shipping (+)</span>
                        <span
                            class="font-semibold">{{ $sym }}{{ number_format($purchase->shipping_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-center bg-slate-800 text-white px-3 py-2.5 rounded-xl">
                    <span class="font-bold uppercase tracking-wide text-xs">Grand Total</span>
                    <span
                        class="font-black text-lg">{{ $sym }}{{ number_format($purchase->grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-600 font-semibold pt-1">
                    <span>{{ __('messages.paid_amount_label') }}</span>
                    <span>{{ $sym }}{{ number_format($purchase->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-red-500 font-semibold">
                    <span>{{ __('messages.balance_due') }}</span>
                    <span>{{ $sym }}{{ number_format($purchase->due_amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if ($purchase->notes)
            <div class="border-t border-slate-100 pt-4 mb-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Notes</h3>
                <p class="text-sm text-slate-600">{{ $purchase->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="border-t border-slate-200 pt-4 text-center text-xs text-slate-400">
            <p>This is a system-generated purchase order from <strong>{{ $companyName }}</strong> &mdash;
                Electronics
                & Inventory Management System.</p>
            <p class="mt-1">Created by: {{ $purchase->user->name ?? 'System' }} &bull; Printed on:
                {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
