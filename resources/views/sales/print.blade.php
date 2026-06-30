<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice - {{ $sale->invoice_no }}</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen py-10 print:py-0 print:bg-white print:text-black">

    <!-- Top Action bar (Hidden on print) -->
    <div class="max-w-4xl mx-auto mb-6 px-4 no-print flex justify-between items-center bg-white border border-slate-200 shadow-sm p-3.5 rounded-2xl">
        <span class="text-sm text-slate-500 font-medium">Invoice Preview: <strong class="text-slate-800 font-mono">{{ $sale->invoice_no }}</strong></span>
        <div class="flex items-center gap-2">
            <button onclick="window.close()" class="rounded-xl border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all cursor-pointer">
                Close Window
            </button>
            <button onclick="window.print()" class="rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
                <i class="fa-solid fa-print mr-1"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Printable Invoice Template container -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm p-8 print:border-0 print:shadow-none print:p-0 print:rounded-none">
        
        <!-- Header: Logo and Details -->
        <div class="flex justify-between items-start border-b border-slate-100 print:border-black pb-6 mb-6">
            <div>
                <!-- Brand logo placeholder -->
                <span class="text-xl font-extrabold bg-gradient-to-r from-blue-600 to-violet-600 bg-clip-text text-transparent print:text-black print:bg-none print:from-transparent print:to-transparent">
                    <i class="fa-solid fa-bolt mr-1 text-blue-600 print:text-black"></i> Kalathiya POS POS
                </span>
                <p class="text-xs text-slate-500 mt-1.5 print:text-black font-semibold">Electronics ERP & Inventory Management System</p>
                <p class="text-[11px] text-slate-400 mt-0.5 print:text-black">Sector 5, Salt Lake, Kolkata, WB, 700091</p>
                <p class="text-[11px] text-slate-400 print:text-black">Email: support@antigravitypos.com | Phone: +91 98765 43210</p>
            </div>
            <div class="text-right">
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight print:text-black">Invoice</h1>
                <p class="text-xs font-mono font-bold text-slate-500 mt-1 print:text-black">{{ $sale->invoice_no }}</p>
                
                <div class="mt-4 text-left inline-block text-[11px] bg-slate-50 print:bg-transparent border border-slate-100 print:border-0 p-2.5 rounded-xl space-y-1">
                    <p class="print:text-black"><strong class="text-slate-400 print:text-black uppercase text-[9px] mr-1.5">Date:</strong> {{ $sale->invoice_date }}</p>
                    <p class="print:text-black"><strong class="text-slate-400 print:text-black uppercase text-[9px] mr-1.5">PO Ref:</strong> {{ $sale->reference_no ?: '-' }}</p>
                    <p class="print:text-black"><strong class="text-slate-400 print:text-black uppercase text-[9px] mr-1.5">Payment:</strong> {{ $sale->payment_method ?: 'Cash' }}</p>
                </div>
            </div>
        </div>
        <!-- Billing details grid -->
        <div class="grid grid-cols-2 gap-8 border-b border-slate-100 print:border-black pb-6 mb-6">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">Billing Address</span>
                <strong class="text-sm text-slate-800 mt-1 block print:text-black">{{ $sale->customer->name }}</strong>
                @if($sale->customer->phone)
                    <span class="text-xs text-slate-600 block mt-0.5 print:text-black">Phone: {{ $sale->customer->phone }}</span>
                @endif
                @if($sale->customer->email)
                    <span class="text-xs text-slate-600 block print:text-black">Email: {{ $sale->customer->email }}</span>
                @endif
                @if($sale->customer->address)
                    <span class="text-xs text-slate-500 block mt-1 print:text-black whitespace-pre-line">{{ $sale->customer->address }}</span>
                @endif
            </div>
            
            <div class="text-right">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">Sales Person</span>
                <strong class="text-sm text-slate-800 mt-1 block print:text-black">{{ $sale->salesPerson->name ?? '-' }}</strong>
                <span class="text-xs text-slate-500 block mt-0.5 print:text-black">Billing Counter: #01</span>
                
                <div class="mt-4">
                    <span class="inline-block text-[10px] font-bold uppercase py-0.5 px-2.5 rounded-full {{ $sale->status === 'Completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }} print:border-black print:text-black print:bg-transparent">
                        Invoice {{ $sale->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Products table -->
        <div class="mb-6">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 print:bg-transparent print:border-black">
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-8 text-center">#</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black">Product Details</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-24 text-center">SKU</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-20 text-right">Price</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-16 text-center">Qty</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-16 text-right">Tax</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-16 text-right">Discount</th>
                        <th class="py-2.5 px-3 font-bold text-slate-500 print:text-black w-24 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 print:border-b print:border-black">
                    @foreach($sale->items as $index => $item)
                        <tr>
                            <td class="py-3 px-3 text-center text-slate-400 print:text-black font-semibold">{{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <strong class="text-slate-800 print:text-black block">{{ $item->product->name }}</strong>
                            </td>
                            <td class="py-3 px-3 font-mono text-[10px] text-slate-500 print:text-black text-center">{{ $item->product->code }}</td>
                            <td class="py-3 px-3 text-right text-slate-650 print:text-black">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-800 print:text-black">{{ number_format($item->quantity, 2) }} {{ $item->product->unit->short_name ?? 'PCS' }}</td>
                            <td class="py-3 px-3 text-right text-slate-500 print:text-black">₹{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="py-3 px-3 text-right text-red-500 print:text-black">₹{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="py-3 px-3 text-right font-bold text-slate-800 print:text-black">₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="flex justify-between items-start border-t border-slate-100 print:border-black pt-6">
            <!-- Left Notes block -->
            <div class="w-1/2 pr-6">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">Terms & Notes</span>
                <p class="text-[11px] text-slate-500 mt-1.5 print:text-black leading-relaxed whitespace-pre-line">{{ $sale->notes ?: '1. Payment terms: immediate receipt.\n2. Goods once sold cannot be returned or exchanged.\n3. Warranty claims require presenting this invoice printout.' }}</p>
            </div>
            
            <!-- Right calculations block -->
            <div class="w-2/5 space-y-2.5 text-xs text-slate-600 print:text-black">
                <div class="flex justify-between items-center border-b border-slate-50 print:border-slate-200 pb-1.5">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Subtotal</span>
                    <span class="font-bold text-slate-800 print:text-black">₹{{ number_format($sale->sub_total, 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-50 print:border-slate-200 pb-1.5">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Tax Amount</span>
                    <span class="font-bold text-slate-800 print:text-black">₹{{ number_format($sale->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-50 print:border-slate-200 pb-1.5">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Discount (-)</span>
                    <span class="font-bold text-red-500 print:text-black">₹{{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-50 print:border-slate-200 pb-1.5">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Shipping Charge</span>
                    <span class="font-bold text-slate-800 print:text-black">₹{{ number_format($sale->shipping_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center border-b-2 border-slate-200 print:border-black pb-2">
                    <span class="font-black text-slate-800 print:text-black uppercase text-[11px]">Grand Total</span>
                    <span class="font-black text-blue-600 print:text-black text-sm">₹{{ number_format($sale->grand_total, 2) }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 print:text-black font-semibold uppercase text-[10px]">Paid Amount</span>
                    <span class="font-bold text-emerald-600 print:text-black">₹{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-450 print:text-black font-bold uppercase text-[10px]">Due Amount</span>
                    <span class="font-bold text-red-600 print:text-black">₹{{ number_format($sale->due_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-16 flex justify-between items-center text-xs">
            <div>
                <p class="text-slate-400 print:text-black">Authorized Signatory</p>
                <div class="w-48 border-b border-slate-350 print:border-black mt-10"></div>
            </div>
            
            <div class="text-right">
                <p class="text-slate-400 print:text-black">Thank you for your business!</p>
                <p class="text-[10px] text-slate-350 print:text-black mt-1">Generated via Kalathiya POS ERP</p>
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