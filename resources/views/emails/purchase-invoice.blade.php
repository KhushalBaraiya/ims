<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order — {{ $purchase->purchase_no }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.6;
        }

        .wrapper {
            max-width: 680px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%);
            padding: 36px 40px;
            color: #fff;
        }

        .header .company-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .header .tagline {
            font-size: 11px;
            opacity: 0.75;
            margin-top: 2px;
        }

        .header .invoice-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.85;
            margin-top: 20px;
        }

        .header .invoice-no {
            font-size: 26px;
            font-weight: 900;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #fde68a;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-info {
            background: rgba(99, 102, 241, 0.2);
            color: #c7d2fe;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        /* ── Body ── */
        .body {
            padding: 36px 40px;
        }

        .greeting {
            font-size: 15px;
            color: #374151;
            margin-bottom: 20px;
        }

        .greeting strong {
            color: #1e293b;
        }

        /* meta row */
        .meta-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }

        .meta-cell {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 6px 0 0;
        }

        .meta-cell:last-child {
            padding: 0 0 0 6px;
        }

        .meta-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px 18px;
        }

        .meta-box .label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .meta-box .value {
            font-size: 13px;
            color: #1e293b;
            font-weight: 600;
        }

        .meta-box .sub-value {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        /* items table */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 10px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 12px;
        }

        table.items thead tr {
            background: #0f172a;
            color: #fff;
        }

        table.items thead th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        table.items thead th.text-right {
            text-align: right;
        }

        table.items thead th.text-center {
            text-align: center;
        }

        table.items tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        table.items tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: middle;
        }

        table.items tbody td.text-right {
            text-align: right;
        }

        table.items tbody td.text-center {
            text-align: center;
        }

        .product-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 12px;
        }

        .product-sku {
            font-size: 10px;
            color: #94a3b8;
            font-family: monospace;
        }

        /* totals */
        .totals-wrapper {
            display: table;
            width: 100%;
        }

        .totals-spacer {
            display: table-cell;
            width: 55%;
        }

        .totals-box {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        .totals-row .t-label {
            color: #64748b;
            font-weight: 600;
        }

        .totals-row .t-value {
            font-weight: 700;
            color: #1e293b;
        }

        .totals-row.grand {
            background: #0f172a;
            color: #fff;
            border-radius: 8px;
            padding: 12px 14px;
            margin-top: 6px;
            border-bottom: none;
        }

        .totals-row.grand .t-label {
            color: #fff;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .totals-row.grand .t-value {
            color: #fff;
            font-size: 18px;
            font-weight: 900;
        }

        .totals-row.paid .t-value {
            color: #16a34a;
        }

        .totals-row.due .t-value {
            color: #dc2626;
        }

        .totals-row.due-zero .t-value {
            color: #16a34a;
        }

        /* payment box */
        .payment-box {
            background: linear-gradient(135deg, #ecfdf5, #f0fdf4);
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 18px 20px;
            margin: 28px 0;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .payment-box .icon {
            width: 36px;
            height: 36px;
            background: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
            color: #fff;
        }

        .payment-box .content .title {
            font-weight: 700;
            color: #15803d;
            font-size: 14px;
        }

        .payment-box .content .detail {
            font-size: 12px;
            color: #166534;
            margin-top: 3px;
        }

        /* footer */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }

        .footer p {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .footer .company {
            font-weight: 700;
            color: #475569;
        }

        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 24px 0;
        }
    </style>
</head>

<body>

    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $companyName = $settings['company_name'] ?? config('app.name', 'IMS');
        $companyAddress = $settings['company_address'] ?? '';
        $companyPhone = $settings['company_phone'] ?? '';
        $companyEmail = $settings['company_email'] ?? '';
        $companyLogo = $settings['company_logo'] ?? null;
        $sym = optional(current_currency())->symbol ?? '₹';
    @endphp

    <div class="wrapper">

        {{-- ─── Header ─── --}}
        <div class="header">
            {{-- Logo + Company Name --}}
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px;">
                @if ($companyLogo && file_exists(public_path('uploads/settings/' . $companyLogo)))
                    <img src="{{ asset('uploads/settings/' . $companyLogo) }}" alt="{{ $companyName }}"
                        style="height:48px;max-width:140px;object-fit:contain;border-radius:6px;
                                background:rgba(255,255,255,.15);padding:4px;">
                @endif
                <div class="company-name">{{ $companyName }}</div>
            </div>
            <div class="tagline">Inventory Management System</div>

            <div class="invoice-title">Purchase Order</div>
            <div class="invoice-no">{{ $purchase->purchase_no }}</div>

            @if ($purchase->payment_status === 'Paid')
                <span class="badge badge-success">✔ Paid</span>
            @elseif ($purchase->payment_status === 'Partial')
                <span class="badge badge-warning">⚡ Partial Payment</span>
            @else
                <span class="badge badge-danger">⚠ Unpaid</span>
            @endif

            @if ($purchase->status === 'received')
                <span class="badge badge-success" style="margin-left:6px;">Received</span>
            @elseif ($purchase->status === 'pending')
                <span class="badge badge-warning" style="margin-left:6px;">Pending</span>
            @elseif ($purchase->status === 'ordered')
                <span class="badge badge-info" style="margin-left:6px;">Ordered</span>
            @else
                <span class="badge badge-info" style="margin-left:6px;">{{ ucfirst($purchase->status) }}</span>
            @endif
        </div>

        {{-- ─── Body ─── --}}
        <div class="body">

            {{-- Greeting --}}
            <p class="greeting">
                Dear <strong>{{ $purchase->supplier->name ?? 'Supplier' }}</strong>,<br>
                Please find the details of your purchase order below.
            </p>

            {{-- Meta --}}
            <div class="meta-row">
                <div class="meta-cell">
                    <div class="meta-box">
                        <div class="label">Order Details</div>
                        <div class="value">{{ $purchase->purchase_no }}</div>
                        <div class="sub-value">Date:
                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</div>
                        <div class="sub-value">Payment: {{ $purchase->payment_method ?: 'Cash' }}</div>
                        @if ($purchase->reference_no)
                            <div class="sub-value">Ref No: {{ $purchase->reference_no }}</div>
                        @endif
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-box">
                        <div class="label">Supplier Details</div>
                        <div class="value">{{ $purchase->supplier->name ?? '-' }}</div>
                        @if ($purchase->supplier->phone)
                            <div class="sub-value">📞 {{ $purchase->supplier->phone }}</div>
                        @endif
                        @if ($purchase->supplier->email)
                            <div class="sub-value">✉ {{ $purchase->supplier->email }}</div>
                        @endif
                        @if ($purchase->supplier->address)
                            <div class="sub-value" style="margin-top:4px;">{{ $purchase->supplier->address }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Box --}}
            @if ($purchase->payment_status === 'Paid')
                <div class="payment-box">
                    <div class="icon">✔</div>
                    <div class="content">
                        <div class="title">Payment Confirmed — Thank You!</div>
                        <div class="detail">
                            Amount paid:
                            <strong>{{ $sym }}{{ number_format($purchase->paid_amount, 2) }}</strong>
                            via <strong>{{ $purchase->payment_method }}</strong> on
                            {{ $purchase->updated_at->format('d M Y, h:i A') }}.
                        </div>
                    </div>
                </div>
            @elseif ($purchase->payment_status === 'Partial')
                <div class="payment-box"
                    style="background:linear-gradient(135deg,#fffbeb,#fef9c3);border-color:#fde68a;">
                    <div class="icon" style="background:#d97706;">⚡</div>
                    <div class="content">
                        <div class="title" style="color:#92400e;">Partial Payment Received</div>
                        <div class="detail" style="color:#78350f;">
                            Paid: <strong>{{ $sym }}{{ number_format($purchase->paid_amount, 2) }}</strong> —
                            Balance Due:
                            <strong>{{ $sym }}{{ number_format($purchase->due_amount, 2) }}</strong>.
                        </div>
                    </div>
                </div>
            @endif

            {{-- Items Table --}}
            <div class="section-title">Purchased Items</div>
            <table class="items">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Tax</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="product-name">{{ $item->product->name ?? '(Deleted Product)' }}</div>
                                <div class="product-sku">{{ $item->product->code ?? '' }}</div>
                            </td>
                            <td class="text-center">
                                {{ number_format($item->quantity, 2) }} {{ $item->product->unit_code ?? 'PCS' }}
                            </td>
                            <td class="text-right">{{ $sym }}{{ number_format($item->purchase_price, 2) }}
                            </td>
                            <td class="text-right" style="color:#dc2626;">
                                -{{ $sym }}{{ number_format($item->discount_amount, 2) }}</td>
                            <td class="text-right" style="color:#d97706;">
                                +{{ $sym }}{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="text-right">
                                <strong>{{ $sym }}{{ number_format($item->total_amount, 2) }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals-wrapper">
                <div class="totals-spacer"></div>
                <div class="totals-box">
                    <div class="totals-row">
                        <span class="t-label">Subtotal</span>
                        <span class="t-value">{{ $sym }}{{ number_format($purchase->sub_total, 2) }}</span>
                    </div>
                    @if ($purchase->discount_amount > 0)
                        <div class="totals-row">
                            <span class="t-label">Discount (-)</span>
                            <span class="t-value"
                                style="color:#dc2626;">{{ $sym }}{{ number_format($purchase->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if ($purchase->tax_amount > 0)
                        <div class="totals-row">
                            <span class="t-label">Tax (+)</span>
                            <span class="t-value"
                                style="color:#d97706;">{{ $sym }}{{ number_format($purchase->tax_amount, 2) }}</span>
                        </div>
                    @endif
                    @if ($purchase->shipping_amount > 0)
                        <div class="totals-row">
                            <span class="t-label">Shipping (+)</span>
                            <span
                                class="t-value">{{ $sym }}{{ number_format($purchase->shipping_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="totals-row grand">
                        <span class="t-label">GRAND TOTAL</span>
                        <span class="t-value">{{ $sym }}{{ number_format($purchase->grand_total, 2) }}</span>
                    </div>
                    <div class="totals-row paid" style="margin-top:8px;">
                        <span class="t-label">Paid Amount</span>
                        <span class="t-value">{{ $sym }}{{ number_format($purchase->paid_amount, 2) }}</span>
                    </div>
                    <div class="totals-row {{ $purchase->due_amount > 0 ? 'due' : 'due-zero' }}">
                        <span class="t-label">Balance Due</span>
                        <span class="t-value">{{ $sym }}{{ number_format($purchase->due_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            @if ($purchase->notes)
                <hr class="divider">
                <div class="section-title" style="margin-bottom:6px;">Notes</div>
                <p style="font-size:12px;color:#64748b;white-space:pre-line;">{{ $purchase->notes }}</p>
            @endif

        </div>

        {{-- ─── Footer ─── --}}
        <div class="footer">
            @if ($companyAddress || $companyPhone || $companyEmail)
                <p>
                    @if ($companyAddress)
                        {{ $companyAddress }}
                    @endif
                    @if ($companyPhone)
                        | 📞 {{ $companyPhone }}
                    @endif
                    @if ($companyEmail)
                        | ✉ {{ $companyEmail }}
                    @endif
                </p>
            @endif
            <p>This is a system-generated purchase order from <span class="company">{{ $companyName }}</span>. Please
                do not reply to this email.</p>
            <p style="margin-top:6px;">Generated on {{ now()->format('d M Y, h:i A') }} by
                {{ $purchase->user->name ?? 'System' }}</p>
        </div>

    </div>
</body>

</html>
