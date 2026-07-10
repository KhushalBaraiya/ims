<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Print Invoice - {{ $sale->invoice_no }}</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
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

<body class="min-h-screen bg-slate-50 py-10 text-slate-800 antialiased print:bg-white print:py-0 print:text-black">

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
        class="no-print mx-auto mb-6 flex max-w-4xl flex-wrap items-center justify-between gap-2 rounded-2xl border border-slate-200 bg-white p-3.5 px-4 shadow-sm">
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
                🖨️ Print Invoice
            </button>
        </div>
    </div>

    <!-- Printable Invoice -->
    <div
        class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-8 shadow-sm print:rounded-none print:border-0 print:p-0 print:shadow-none">

        <!-- Header -->
        <div class="mb-6 flex items-start justify-between border-b border-slate-100 pb-6 print:border-black">
            <div>
                <span
                    class="bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-xl font-extrabold text-transparent print:bg-none print:text-black">
                    ⚡ {{ $companyName }}
                </span>
                <p class="mt-1.5 text-xs font-semibold text-slate-500 print:text-black">Electronics ERP & Inventory
                    Management System</p>
                @if ($companyAddress)
                    <p class="mt-0.5 text-[11px] text-slate-400 print:text-black">{{ $companyAddress }}</p>
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

        <!-- Billing details -->
        <div
            class="mb-6 grid grid-cols-1 gap-4 border-b border-slate-100 pb-6 sm:grid-cols-2 sm:gap-8 print:grid-cols-2 print:border-black">
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
                @if ($sale->customer->address ?? null)
                    <span
                        class="mt-1 block whitespace-pre-line text-xs text-slate-500 print:text-black">{{ $sale->customer->address }}</span>
                @endif
            </div>
            <div class="text-right">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Sales
                    Person</span>
                <strong
                    class="mt-1 block text-sm text-slate-800 print:text-black">{{ $sale->salesPerson->name ?? '-' }}</strong>
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
                            <td class="px-3 py-3 text-right text-slate-700 print:text-black">
                                {{ $sym }}{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-3 py-3 text-center font-bold text-slate-800 print:text-black">
                                {{ number_format($item->quantity, 2) }} {{ $item->product->unit_code ?? 'PCS' }}
                            </td>
                            <td class="px-3 py-3 text-right text-slate-500 print:text-black">
                                {{ $sym }}{{ number_format($item->tax_amount, 2) }}</td>

                            <td class="px-3 py-3 text-right text-red-500 print:text-black">
                                {{ $sym }}{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="px-3 py-3 text-right font-bold text-slate-800 print:text-black">
                                {{ $sym }}{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div
            class="flex flex-col gap-6 border-t border-slate-100 pt-6 sm:flex-row sm:items-start sm:justify-between print:flex-row print:border-black">
            <!-- Notes -->
            <div class="w-full sm:w-1/2 pr-0 sm:pr-6">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Terms
                    & Notes</span>
                <p class="mt-1.5 whitespace-pre-line text-[11px] leading-relaxed text-slate-500 print:text-black">
                    {{ $sale->notes ?: "1. Payment terms: immediate receipt.\n2. Goods once sold cannot be returned without this invoice.\n3. Warranty claims require presenting this invoice." }}
                </p>
            </div>

            <!-- Calculations -->
            <div class="w-full sm:w-2/5 space-y-2.5 text-xs text-slate-600 print:text-black">
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Subtotal</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">{{ $sym }}{{ number_format($sale->sub_total, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Tax Amount</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">{{ $sym }}{{ number_format($sale->tax_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Discount
                        (-)</span>
                    <span
                        class="font-bold text-red-500 print:text-black">{{ $sym }}{{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-50 pb-1.5 print:border-slate-200">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Shipping</span>
                    <span
                        class="font-bold text-slate-800 print:text-black">{{ $sym }}{{ number_format($sale->shipping_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between border-b-2 border-slate-200 pb-2 print:border-black">
                    <span class="text-[11px] font-black uppercase text-slate-800 print:text-black">Grand Total</span>
                    <span
                        class="text-sm font-black text-blue-600 print:text-black">{{ $sym }}{{ number_format($sale->grand_total, 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold uppercase text-slate-400 print:text-black">Paid Amount</span>
                    <span
                        class="font-bold text-emerald-600 print:text-black">{{ $sym }}{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase text-slate-400 print:text-black">Due Amount</span>
                    <span
                        class="font-bold text-red-600 print:text-black">{{ $sym }}{{ number_format($sale->due_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-16 flex flex-wrap items-center justify-between gap-4 text-xs">
            <div>
                <p class="text-slate-400 print:text-black">Authorized Signatory</p>
                <div class="mt-10 w-48 border-b border-slate-300 print:border-black"></div>
            </div>
            <div class="text-right">
                <p class="text-slate-400 print:text-black">Thank you for your business!</p>
                <p class="mt-1 text-[10px] text-slate-400 print:text-black">Generated via {{ $companyName }} ERP</p>
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
