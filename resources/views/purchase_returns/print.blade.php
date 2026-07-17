<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Return - {{ $purchaseReturn->return_no }}</title>
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

    <!-- Top Action Bar -->
    <div
        class="max-w-4xl mx-auto mb-6 px-4 no-print flex flex-wrap justify-between items-center gap-2 bg-white border border-slate-200 shadow-sm p-3.5 rounded-2xl">
        <span class="text-sm text-slate-500 font-medium">Purchase Return: <strong
                class="text-slate-800 font-mono">{{ $purchaseReturn->return_no }}</strong></span>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                class="inline-flex items-center gap-x-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
            <a href="{{ route('purchase-returns.show', $purchaseReturn->id) }}"
                class="inline-flex items-center gap-x-1.5 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-xl hover:bg-slate-50 transition-all">
                &larr; Back
            </a>
        </div>
    </div>

    @php
        $sym = optional(current_currency())->symbol ?? '₹';
        $settings = \App\Models\Setting::pluck('value', 'key');
        $companyName = $settings['company_name'] ?? config('app.name', 'IMS');
        $companyAddress = $settings['company_address'] ?? '';
        $companyPhone = $settings['company_phone'] ?? '';
        $companyEmail = $settings['company_email'] ?? '';
    @endphp

    <!-- A4 Document -->
    <div
        class="max-w-4xl mx-auto bg-white shadow-xl border border-slate-200 rounded-2xl p-10 print:shadow-none print:border-0 print:rounded-none">

        <!-- Header -->
        <div class="flex flex-wrap justify-between items-start gap-4 border-b border-slate-200 pb-8 mb-8">
            <div>
                <div
                    class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent mb-1 print:text-black print:bg-none">
                    ⚡ {{ $companyName }}</div>
                <p class="text-xs text-slate-400">Electronics & Inventory Management System</p>
                @if ($companyAddress)
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $companyAddress }}</p>
                @endif
                @if ($companyPhone || $companyEmail)
                    <p class="text-[11px] text-slate-400">
                        @if ($companyEmail)
                            Email: {{ $companyEmail }}
                            @endif@if ($companyPhone)
                                | Phone: {{ $companyPhone }}
                            @endif
                    </p>
                @endif
            </div>
            <div class="text-right">
                <div class="text-3xl font-black text-slate-800 tracking-tight">PURCHASE RETURN</div>
                <div class="mt-2 text-sm font-mono font-bold text-violet-600">{{ $purchaseReturn->return_no }}</div>
                <div class="text-xs text-slate-400 mt-1">Date: {{ $purchaseReturn->return_date }}</div>
                <div class="mt-3">
                    @if ($purchaseReturn->status === 'Completed')
                        <span
                            class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">✔
                            COMPLETED</span>
                    @else
                        <span
                            class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700 border border-amber-200">PENDING</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Supplier & Return Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 mb-8 print:grid-cols-2">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Supplier Details</h3>
                <p class="font-bold text-slate-800 text-base">{{ $purchaseReturn->supplier->name ?? 'N/A' }}</p>
                @if ($purchaseReturn->supplier?->email)
                    <p class="text-sm text-slate-500 mt-0.5">{{ $purchaseReturn->supplier->email }}</p>
                    `
                @endif
                @if ($purchaseReturn->supplier?->phone)
                    <p class="text-sm text-slate-500">{{ $purchaseReturn->supplier->phone }}</p>
                @endif
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Return Info</h3>
                <div class="mb-1"><span class="text-xs text-slate-400">Original PO:</span> <span
                        class="font-mono font-bold text-slate-700">{{ $purchaseReturn->purchase->purchase_no ?? '-' }}</span>
                </div>
                @if ($purchaseReturn->reference_no)
                    <div class="mb-1"><span class="text-xs text-slate-400">Reference:</span> <span
                            class="font-semibold text-slate-700">{{ $purchaseReturn->reference_no }}</span></div>
                @endif
                <div><span class="text-xs text-slate-400">Created By:</span> <span
                        class="font-semibold text-slate-700">{{ $purchaseReturn->user->name ?? '-' }}</span></div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto -mx-2 px-2 print:overflow-visible">
            <table class="w-full text-sm border-collapse mb-8" style="min-width:480px;">
                <thead>
                    <tr class="bg-slate-800 text-white text-xs uppercase">
                        <th class="py-3 px-4 text-left rounded-l-lg">#</th>
                        <th class="py-3 px-4 text-left">Product</th>
                        <th class="py-3 px-4 text-center">Return Qty</th>
                        <th class="py-3 px-4 text-left rounded-r-lg">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseReturn->items as $index => $item)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }} border-b border-slate-100">
                            <td class="py-3 px-4 text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-800">{{ $item->product->name }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $item->product->code }}</p>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-700">
                                {{ number_format($item->quantity, 2) }}</td>
                            <td class="py-3 px-4 text-slate-500 italic">{{ $item->reason ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Refund Summary -->
        <div class="flex justify-end mb-8">
            <div class="w-64 space-y-2 text-sm">
                <div class="flex justify-between items-center bg-slate-800 text-white px-3 py-2.5 rounded-xl">
                    <span class="font-bold uppercase tracking-wide text-xs">Grand Total</span>
                    <span
                        class="font-black text-lg">{{ $sym }}{{ number_format($purchaseReturn->grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-violet-600 font-semibold pt-1">
                    <span>{{ __('messages.refunded_amount') }}</span>
                    <span>{{ $sym }}{{ number_format($purchaseReturn->refunded_amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if ($purchaseReturn->notes)
            <div class="border-t border-slate-100 pt-4 mb-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Notes</h3>
                <p class="text-sm text-slate-600">{{ $purchaseReturn->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="border-t border-slate-200 pt-4 text-center text-xs text-slate-400">
            @php $companyName = \App\Models\Setting::where('key','company_name')->value('value') ?? config('app.name','IMS'); @endphp
            <p>This is a system-generated purchase return receipt from <strong>{{ $companyName }}</strong>.</p>
            <p class="mt-1">Created by: {{ $purchaseReturn->user->name ?? 'System' }} &bull; Printed on:
                {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</body>

</html>
