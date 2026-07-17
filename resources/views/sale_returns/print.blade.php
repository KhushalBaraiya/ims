<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Sales Return - {{ $saleReturn->return_no }}</title>
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
    @endphp

    <!-- Top Action bar (Hidden on print) -->
    <div
        class="max-w-4xl mx-auto mb-6 px-4 no-print flex flex-wrap justify-between items-center gap-2 bg-white border border-slate-200 shadow-sm p-3.5 rounded-2xl">
        <span class="text-sm text-slate-500 font-medium">Sales Return Preview: <strong
                class="text-slate-800 font-mono">{{ $saleReturn->return_no }}</strong></span>
        <div class="flex items-center gap-2">
            <button onclick="window.close()"
                class="rounded-xl border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer">
                Close Window
            </button>
            <button onclick="window.print()"
                class="rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all cursor-pointer">
                🖨️ Print Return Sheet
            </button>
        </div>
    </div>

    <!-- Printable Return Template -->
    <div
        class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm p-8 print:border-0 print:shadow-none print:p-0 print:rounded-none">

        <!-- Header -->
        <div
            class="flex flex-wrap justify-between items-start gap-4 border-b border-slate-100 print:border-black pb-6 mb-6">
            <div>
                <span
                    class="text-xl font-extrabold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent print:text-black print:bg-none">
                    ⚡ {{ $companyName }}
                </span>
                <p class="text-xs text-slate-500 mt-1.5 print:text-black font-semibold">Electronics ERP & Inventory
                    Management System</p>
                @if ($companyAddress)
                    <p class="text-[11px] text-slate-400 mt-0.5 print:text-black">{{ $companyAddress }}</p>
                @endif
                @if ($companyPhone || $companyEmail)
                    <p class="text-[11px] text-slate-400 print:text-black">
                        @if ($companyEmail)
                            Email: {{ $companyEmail }}
                        @endif
                        @if ($companyEmail && $companyPhone)
                            |
                        @endif
                        @if ($companyPhone)
                            Phone: {{ $companyPhone }}
                        @endif
                    </p>
                @endif
            </div>
            <div class="text-right">
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight print:text-black">Sales Return
                </h1>
                <p class="text-xs font-mono font-bold text-slate-500 mt-1 print:text-black">{{ $saleReturn->return_no }}
                </p>
                <div
                    class="mt-4 text-left inline-block text-[11px] bg-slate-50 print:bg-transparent border border-slate-100 print:border-0 p-2.5 rounded-xl space-y-1">
                    <p class="print:text-black"><strong
                            class="text-slate-400 print:text-black uppercase text-[9px] mr-1.5">Return Date:</strong>
                        {{ $saleReturn->return_date }}</p>
                    <p class="print:text-black"><strong
                            class="text-slate-400 print:text-black uppercase text-[9px] mr-1.5">Ref Invoice:</strong>
                        {{ $saleReturn->sale->invoice_no ?? '-' }}</p>
                    <p class="print:text-black"><strong
                            class="text-slate-400 print:text-black uppercase text-[9px] mr-1.5">Ref No:</strong>
                        {{ $saleReturn->reference_no ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Customer & Processed By -->
        <div
            class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 border-b border-slate-100 print:border-black pb-6 mb-6 print:grid-cols-2">
            <div>
                <span
                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">Customer
                    Details</span>
                <strong
                    class="text-sm text-slate-800 mt-1 block print:text-black">{{ $saleReturn->customer->name }}</strong>
                @if ($saleReturn->customer->phone)
                    <span class="text-xs text-slate-600 block mt-0.5 print:text-black">Phone:
                        {{ $saleReturn->customer->phone }}</span>
                @endif
                @if ($saleReturn->customer->email)
                    <span class="text-xs text-slate-600 block print:text-black">Email:
                        {{ $saleReturn->customer->email }}</span>
                @endif
                @if ($saleReturn->customer->address)
                    <span
                        class="text-xs text-slate-500 block mt-1 print:text-black whitespace-pre-line">{{ $saleReturn->customer->address }}</span>
                @endif
            </div>
            <div class="text-right">
                <span
                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">Processed
                    By</span>
                <strong
                    class="text-sm text-slate-800 mt-1 block print:text-black">{{ $saleReturn->user->name ?? '-' }}</strong>
                <div class="mt-4">
                    <span
                        class="inline-block text-[10px] font-bold uppercase py-0.5 px-2.5 rounded-full {{ $saleReturn->status === 'Completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} print:border-black print:text-black print:bg-transparent">
                        Return {{ $saleReturn->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Products table -->
        <div class="mb-6 overflow-x-auto -mx-2 px-2 print:overflow-visible">
            <table class="w-full text-left text-xs border-collapse" style="min-width:480px;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 print:bg-transparent print:border-black">
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-8 text-center">#</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black">
                            {{ __('messages.product_details_th') }}</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-24 text-center">
                            {{ __('messages.sku') }}</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-20 text-right">
                            {{ __('messages.price_th_print') }}</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-20 text-center">Return Qty
                        </th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black">
                            {{ __('messages.return_reason_th') }}</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-24 text-right">
                            {{ __('messages.subtotal_th_print') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 print:border-b print:border-black">
                    @foreach ($saleReturn->items as $index => $item)
                        <tr>
                            <td class="py-3 px-3 text-center text-slate-400 print:text-black font-semibold">
                                {{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <strong
                                    class="text-slate-800 print:text-black block">{{ $item->product->name }}</strong>
                            </td>
                            <td class="py-3 px-3 font-mono text-[10px] text-slate-500 print:text-black text-center">
                                {{ $item->product->code }}</td>
                            <td class="py-3 px-3 text-right text-slate-700 print:text-black">
                                {{ $sym }}{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-800 print:text-black">
                                {{ number_format($item->quantity, 2) }} {{ $item->product->unit_code ?? 'PCS' }}
                            </td>
                            <td class="py-3 px-3 text-slate-500 italic print:text-black">
                                {{ $item->reason ?: 'Damaged / Malfunctional' }}</td>
                            <td class="py-3 px-3 text-right font-bold text-slate-800 print:text-black">
                                {{ $sym }}{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div
            class="flex flex-col gap-6 sm:flex-row sm:justify-between sm:items-start border-t border-slate-100 print:border-black pt-6 print:flex-row">
            <!-- Notes -->
            <div class="w-full sm:w-1/2 pr-0 sm:pr-6">
                <span
                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">Remarks
                    / Conditions</span>
                <p class="text-[11px] text-slate-500 mt-1.5 print:text-black leading-relaxed whitespace-pre-line">
                    {{ $saleReturn->notes ?: "1. Returned goods have been inspected and restocked accordingly.\n2. Refund amount processed as store credit / original payment source." }}
                </p>
            </div>
            <!-- Calculations -->
            <div class="w-full sm:w-2/5 space-y-2.5 text-xs text-slate-600 print:text-black">
                <div class="flex justify-between items-center border-b border-slate-50 print:border-slate-200 pb-1.5">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Return
                        Value</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">{{ $sym }}{{ number_format($saleReturn->sub_total, 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-50 print:border-slate-200 pb-1.5">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Tax
                        Adjusted</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">{{ $sym }}{{ number_format($saleReturn->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b-2 border-slate-200 print:border-black pb-2">
                    <span class="font-black text-slate-800 print:text-black uppercase text-[11px]">Grand Refund
                        Total</span>
                    <span
                        class="font-black text-blue-600 print:text-black text-sm">{{ $sym }}{{ number_format($saleReturn->grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-800 print:text-black uppercase text-[11px]">Refunded
                        Cash/Credit</span>
                    <span
                        class="font-black text-emerald-600 print:text-black text-sm">{{ $sym }}{{ number_format($saleReturn->refunded_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-16 flex flex-wrap justify-between items-center gap-4 text-xs">
            <div>
                <p class="text-slate-400 print:text-black">{{ __('messages.store_inspector_sig') }}</p>
                <div class="w-48 border-b border-slate-300 print:border-black mt-10"></div>
            </div>
            <div class="text-right">
                <p class="text-slate-400 print:text-black">{{ $companyName }}</p>
                <p class="text-[10px] text-slate-400 print:text-black mt-1">Generated via {{ $companyName }} ERP</p>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
