<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Print Invoice - {{ $sale->invoice_no }}</title>

    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
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

            .print-padding-0 {
                padding: 0 !important;
            }

            .print-border-black {
                border-color: #000 !important;
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

<body class="min-h-screen bg-slate-50 py-10 text-slate-800 antialiased print:bg-white print:py-0 print:text-black">

    <!-- Top Action bar (Hidden on print) -->
    <div
        class="no-print mx-auto mb-6 flex max-w-4xl items-center justify-between rounded-2xl border border-slate-200 bg-white p-3.5 px-4 shadow-sm">
        <span class="text-sm font-medium text-slate-500">Invoice Preview: <strong
                class="font-mono text-slate-800">{{ $sale->invoice_no }}</strong></span>
        <div class="flex items-center gap-2">
            <button
                class="cursor-pointer rounded-xl border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:bg-slate-50"
                onclick="window.close()">
                Close Window
            </button>
            <button
                class="cursor-pointer rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-1.5 text-xs font-semibold text-white shadow-md transition-all hover:from-blue-500 hover:to-violet-500 active:scale-[0.98]"
                onclick="window.print()">
                <i class="fa-solid fa-print mr-1"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Printable Invoice Template container -->
    <div
        class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-8 shadow-sm print:rounded-none print:border-0 print:p-0 print:shadow-none">

        <!-- Header: Logo and Details -->
        <div class="mb-6 flex items-start justify-between border-b border-slate-100 pb-6 print:border-black">
            <div>
                <!-- Brand logo placeholder -->
                <span
                    class="bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-xl font-extrabold text-transparent print:bg-none print:from-transparent print:to-transparent print:text-black">
                    <i class="fa-solid fa-bolt mr-1 text-blue-600 print:text-black"></i> Kalathiya POS POS
                </span>
                <p class="mt-1.5 text-xs font-semibold text-slate-500 print:text-black">Electronics ERP & Inventory
                    Management System</p>
                <p class="mt-0.5 text-[11px] text-slate-400 print:text-black">Sector 5, Salt Lake, Kolkata, WB, 700091
                </p>
                <p class="text-[11px] text-slate-400 print:text-black">Email: support@antigravitypos.com | Phone: +91
                    98765 43210</p>
            </div>
            <div class="text-right">
                <h1 class="text-2xl font-black uppercase tracking-tight text-slate-800 print:text-black">Invoice</h1>
                <p class="mt-1 font-mono text-xs font-bold text-slate-500 print:text-black">{{ $sale->invoice_no }}</p>

                <div
                    class="mt-4 inline-block space-y-1 rounded-xl border border-slate-100 bg-slate-50 p-2.5 text-left text-[11px] print:border-0 print:bg-transparent">
                    <p class="print:text-black"><strong
                            class="mr-1.5 text-[9px] uppercase text-slate-400 print:text-black">Date:</strong>
                        {{ $sale->invoice_date }}</p>
                    <p class="print:text-black"><strong
                            class="mr-1.5 text-[9px] uppercase text-slate-400 print:text-black">Payment:</strong>
                        {{ $sale->payment_method ?: 'Cash' }}</p>
                </div>
            </div>
        </div>
        <!-- Billing details grid -->
        <div class="mb-6 grid grid-cols-2 gap-8 border-b border-slate-100 pb-6 print:border-black">
            <div>
                <span
                    class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Billing
                    Address</span>
                <strong class="mt-1 block text-sm text-slate-800 print:text-black">{{ $sale->customer->name }}</strong>
                @if ($sale->customer->phone)
                    <span class="mt-0.5 block text-xs text-slate-600 print:text-black">Phone:
                        {{ $sale->customer->phone }}</span>
                @endif
                @if ($sale->customer->email)
                    <span class="block text-xs text-slate-600 print:text-black">Email:
                        {{ $sale->customer->email }}</span>
                @endif
                @if ($sale->customer->address)
                    <span
                        class="mt-1 block whitespace-pre-line text-xs text-slate-500 print:text-black">{{ $sale->customer->address }}</span>
                @endif
            </div>

            <div class="text-right">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Sales
                    Person</span>
                <strong
                    class="mt-1 block text-sm text-slate-800 print:text-black">{{ $sale->salesPerson->name ?? '-' }}</strong>
                <span class="mt-0.5 block text-xs text-slate-500 print:text-black">Billing Counter: #01</span>

                <div class="mt-4">
                    <span
                        class="{{ in_array($sale->status, ['Completed']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase print:border-black print:bg-transparent print:text-black">
                        Invoice {{ $sale->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Products table -->
        <div class="mb-6">
            <table class="w-full border-collapse text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 print:border-black print:bg-transparent">
                        <th class="w-8 px-3 py-2.5 text-center font-bold text-slate-500 print:text-black">#</th>
                        <th class="px-3 py-2.5 font-bold text-slate-500 print:text-black">Product Details</th>
                        <th class="w-24 px-3 py-2.5 text-center font-bold text-slate-500 print:text-black">SKU</th>
                        <th class="w-20 px-3 py-2.5 text-right font-bold text-slate-500 print:text-black">Price</th>
                        <th class="w-16 px-3 py-2.5 text-center font-bold text-slate-500 print:text-black">Qty</th>
                        <th class="w-16 px-3 py-2.5 text-right font-bold text-slate-500 print:text-black">Tax</th>
                        <th class="w-16 px-3 py-2.5 text-right font-bold text-slate-500 print:text-black">Discount</th>
                        <th class="w-24 px-3 py-2.5 text-right font-bold text-slate-500 print:text-black">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 print:border-b print:border-black">
                    @foreach ($sale->items as $index => $item)
                        <tr>
                            <td class="px-3 py-3 text-center font-semibold text-slate-400 print:text-black">
                                {{ $index + 1 }}</td>
                            <td class="px-3 py-3">
                                <strong
                                    class="block text-slate-800 print:text-black">{{ $item->product->name }}</strong>
                            </td>
                            <td class="px-3 py-3 text-center font-mono text-[10px] text-slate-500 print:text-black">
                                {{ $item->product->code }}</td>
                            <td class="text-slate-650 px-3 py-3 text-right print:text-black">
                                ₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-3 py-3 text-center font-bold text-slate-800 print:text-black">
                                {{ number_format($item->quantity, 2) }}
                                {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                            <td class="px-3 py-3 text-right text-slate-500 print:text-black">
                                ₹{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="px-3 py-3 text-right text-red-500 print:text-black">
                                ₹{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="px-3 py-3 text-right font-bold text-slate-800 print:text-black">
                                ₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="flex items-start justify-between border-t border-slate-100 pt-6 print:border-black">
            <!-- Left Notes block -->
            <div class="w-1/2 pr-6">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Terms
                    & Notes</span>
                <p class="mt-1.5 whitespace-pre-line text-[11px] leading-relaxed text-slate-500 print:text-black">
                    {{ $sale->notes ?: '1. Payment terms: immediate receipt.\n2. Goods once sold cannot be returned or exchanged.\n3. Warranty claims require presenting this invoice printout.' }}
                </p>
            </div>

            <!-- Right calculations block -->
            <div class="w-2/5 space-y-2.5 text-xs text-slate-600 print:text-black">
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Subtotal</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">₹{{ number_format($sale->sub_total, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Tax Amount</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">₹{{ number_format($sale->tax_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Discount
                        (-)</span>
                    <span
                        class="font-bold text-red-500 print:text-black">₹{{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Shipping
                        Charge</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">₹{{ number_format($sale->shipping_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b-2 border-slate-200 pb-2 print:border-black">
                    <span class="text-[11px] font-black uppercase text-slate-800 print:text-black">Grand Total</span>
                    <span
                        class="text-sm font-black text-blue-600 print:text-black">₹{{ number_format($sale->grand_total, 2) }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Paid Amount</span>
                    <span
                        class="font-bold text-emerald-600 print:text-black">₹{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-450 text-[10px] font-bold uppercase print:text-black">Due Amount</span>
                    <span
                        class="font-bold text-red-600 print:text-black">₹{{ number_format($sale->due_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-16 flex items-center justify-between text-xs">
            <div>
                <p class="text-slate-400 print:text-black">Authorized Signatory</p>
                <div class="border-slate-350 mt-10 w-48 border-b print:border-black"></div>
            </div>

            <div class="text-right">
                <p class="text-slate-400 print:text-black">Thank you for your business!</p>
                <p class="text-slate-350 mt-1 text-[10px] print:text-black">Generated via Kalathiya POS ERP</p>
            </div>
        </div>

    </div>

    <!-- Print Trigger script -->
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>


{{-- dashbord done --}}
{{-- sales desin done --}}
{{-- purches desin done --}}
{{-- light dark delete conform box working --}}
{{-- Purchase Retrun changes --}}
