<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Purchase Order — {{ $purchase->purchase_no }}</title>
</head>

<body style="margin:0;padding:0;background:#f0f2f5;font-family:'Segoe UI',Helvetica,Arial,sans-serif;color:#1e293b;">

    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $companyName = $settings['company_name'] ?? config('app.name', 'IMS');
        $companyAddress = $settings['company_address'] ?? '';
        $companyPhone = $settings['company_phone'] ?? '';
        $companyEmail = $settings['company_email'] ?? '';
        $companyLogo = $settings['company_logo'] ?? null;
        $sym = optional(current_currency())->symbol ?? '₹';
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0f2f5;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;
              overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">

                    <!-- ══ HEADER ══ -->
                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#696cff 0%,#9155fd 55%,#c053d8 100%);
               padding:32px 40px 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td valign="middle">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                @if ($companyLogo && file_exists(public_path('uploads/settings/' . $companyLogo)))
                                                    <td style="padding-right:12px;vertical-align:middle;">
                                                        <img src="{{ asset('uploads/settings/' . $companyLogo) }}"
                                                            alt="{{ $companyName }}"
                                                            style="height:42px;width:auto;border-radius:8px;
                            background:rgba(255,255,255,.2);padding:4px;display:block;">
                                                    </td>
                                                @endif
                                                <td valign="middle">
                                                    <div
                                                        style="font-size:18px;font-weight:800;color:#fff;letter-spacing:-.3px;line-height:1.1;">
                                                        {{ $companyName }}
                                                    </div>
                                                    <div
                                                        style="font-size:10px;color:rgba(255,255,255,.75);margin-top:2px;
                            letter-spacing:.8px;text-transform:uppercase;">
                                                        {{ __('messages.email_ims_subtitle') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td align="right" valign="middle">
                                        @if ($purchase->payment_status === 'Paid')
                                            <span
                                                style="display:inline-block;background:rgba(255,255,255,.22);color:#fff;
                           border:1.5px solid rgba(255,255,255,.4);padding:5px 16px;border-radius:20px;
                           font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;">
                                                ✔&nbsp; {{ __('messages.email_paid_badge') }}
                                            </span>
                                        @elseif($purchase->payment_status === 'Partial')
                                            <span
                                                style="display:inline-block;background:rgba(251,191,36,.3);color:#fff;
                           border:1.5px solid rgba(251,191,36,.5);padding:5px 16px;border-radius:20px;
                           font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;">
                                                ⚡&nbsp; {{ __('messages.email_partial_badge') }}
                                            </span>
                                        @else
                                            <span
                                                style="display:inline-block;background:rgba(239,68,68,.3);color:#fff;
                           border:1.5px solid rgba(239,68,68,.5);padding:5px 16px;border-radius:20px;
                           font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;">
                                                ⚠&nbsp; {{ __('messages.email_unpaid_badge') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <div style="height:1px;background:rgba(255,255,255,.2);margin:20px 0 18px;"></div>

                            <div
                                style="font-size:10px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;
                  color:rgba(255,255,255,.65);margin-bottom:5px;">
                                {{ __('messages.email_purchase_order') }}</div>
                            <div
                                style="font-size:24px;font-weight:900;color:#fff;letter-spacing:-.3px;
                  font-family:'Courier New',monospace;">
                                {{ $purchase->purchase_no }}
                            </div>
                            <div style="margin-top:10px;">
                                @if ($purchase->status === 'received')
                                    <span
                                        style="display:inline-block;background:rgba(255,255,255,.2);color:#fff;
                       border:1px solid rgba(255,255,255,.35);padding:3px 12px;border-radius:20px;
                       font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;">
                                        ✓&nbsp; {{ __('messages.email_received_badge') }}
                                    </span>
                                @elseif($purchase->status === 'pending')
                                    <span
                                        style="display:inline-block;background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);
                       border:1px solid rgba(255,255,255,.3);padding:3px 12px;border-radius:20px;
                       font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;">
                                        {{ __('messages.email_pending_badge') }}
                                    </span>
                                @else
                                    <span
                                        style="display:inline-block;background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);
                       border:1px solid rgba(255,255,255,.3);padding:3px 12px;border-radius:20px;
                       font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- ══ BODY ══ -->
                    <tr>
                        <td style="padding:32px 40px;background:#ffffff;">

                            <!-- Greeting -->
                            <p style="font-size:14px;color:#475569;margin:0 0 28px;line-height:1.7;">
                                Dear <strong
                                    style="color:#1e293b;">{{ $purchase->supplier->name ?? 'Supplier' }}</strong>,<br>
                                {{ __('messages.email_dear_supplier') }}
                            </p>

                            <!-- ── Payment Banner ── -->
                            @if ($purchase->payment_status === 'Paid')
                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;margin-bottom:28px;">
                                    <tr>
                                        <td style="padding:16px 20px;">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td style="padding-right:14px;vertical-align:top;">
                                                        <div
                                                            style="width:38px;height:38px;background:#16a34a;border-radius:50%;text-align:center;
                            line-height:38px;font-size:16px;color:#fff;">
                                                            ✔</div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            style="font-weight:700;color:#15803d;font-size:13px;margin-bottom:3px;">
                                                            {{ __('messages.email_payment_confirmed') }}
                                                        </div>
                                                        <div style="font-size:12px;color:#166534;line-height:1.5;">
                                                            Paid:
                                                            <strong>{{ $sym }}{{ number_format($purchase->paid_amount, 2) }}</strong>
                                                            via <strong>{{ $purchase->payment_method }}</strong> on
                                                            {{ $purchase->updated_at->format('d M Y, h:i A') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @elseif($purchase->payment_status === 'Partial')
                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;margin-bottom:28px;">
                                    <tr>
                                        <td style="padding:16px 20px;">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td style="padding-right:14px;vertical-align:top;">
                                                        <div
                                                            style="width:38px;height:38px;background:#d97706;border-radius:50%;text-align:center;
                            line-height:38px;font-size:16px;color:#fff;">
                                                            ⚡</div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            style="font-weight:700;color:#92400e;font-size:13px;margin-bottom:3px;">
                                                            {{ __('messages.email_partial_payment') }}
                                                        </div>
                                                        <div style="font-size:12px;color:#78350f;line-height:1.5;">
                                                            {{ __('messages.email_paid_amount') }}:
                                                            <strong>{{ $sym }}{{ number_format($purchase->paid_amount, 2) }}</strong>
                                                            —
                                                            {{ __('messages.email_balance_due') }}:
                                                            <strong>{{ $sym }}{{ number_format($purchase->due_amount, 2) }}</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;margin-bottom:28px;">
                                    <tr>
                                        <td style="padding:14px 20px;">
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td style="padding-right:14px;vertical-align:top;">
                                                        <div
                                                            style="width:38px;height:38px;background:#dc2626;border-radius:50%;text-align:center;
                            line-height:38px;font-size:16px;color:#fff;">
                                                            ⚠</div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            style="font-weight:700;color:#991b1b;font-size:13px;margin-bottom:2px;">
                                                            {{ __('messages.email_payment_pending') }}
                                                        </div>
                                                        <div style="font-size:12px;color:#b91c1c;">
                                                            {{ __('messages.email_balance_due') }}:
                                                            <strong>{{ $sym }}{{ number_format($purchase->due_amount, 2) }}</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <!-- ── Order + Supplier Meta Boxes ── -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-bottom:28px;">
                                <tr>
                                    <td width="48%" valign="top"
                                        style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
                                        <div
                                            style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
                        color:#94a3b8;margin-bottom:10px;">
                                            {{ __('messages.email_order_details') }}</div>
                                        <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:5px;">
                                            {{ $purchase->purchase_no }}
                                        </div>
                                        <div style="font-size:11px;color:#64748b;margin-bottom:3px;">
                                            📅 {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
                                        </div>
                                        <div style="font-size:11px;color:#64748b;margin-bottom:3px;">
                                            💳 {{ $purchase->payment_method ?: 'Cash' }}
                                        </div>
                                        @if ($purchase->reference_no)
                                            <div style="font-size:11px;color:#64748b;">🔖 {{ $purchase->reference_no }}
                                            </div>
                                        @endif
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%" valign="top"
                                        style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px 18px;">
                                        <div
                                            style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
                        color:#94a3b8;margin-bottom:10px;">
                                            {{ __('messages.email_supplier_details') }}</div>
                                        <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:5px;">
                                            {{ $purchase->supplier->name ?? '-' }}
                                        </div>
                                        @if ($purchase->supplier?->phone)
                                            <div style="font-size:11px;color:#64748b;margin-bottom:3px;">
                                                📞 {{ $purchase->supplier->phone }}
                                            </div>
                                        @endif
                                        @if ($purchase->supplier?->email)
                                            <div style="font-size:11px;color:#696cff;margin-bottom:3px;">
                                                ✉ {{ $purchase->supplier->email }}
                                            </div>
                                        @endif
                                        @if ($purchase->supplier?->address)
                                            <div style="font-size:10px;color:#94a3b8;margin-top:4px;line-height:1.4;">
                                                {{ $purchase->supplier->address }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- ── Items Table ── -->
                            <div
                                style="font-size:9px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;
                  color:#94a3b8;margin-bottom:10px;">
                                {{ __('messages.email_purchased_items') }}</div>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="border-radius:10px;overflow:hidden;margin-bottom:24px;
                    border:1px solid #e2e8f0;font-size:12px;">
                                <tr style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
                                    <th align="left"
                                        style="padding:10px 12px;color:rgba(255,255,255,.9);font-weight:700;font-size:9px;letter-spacing:.8px;text-transform:uppercase;">
                                        #</th>
                                    <th align="left"
                                        style="padding:10px 12px;color:rgba(255,255,255,.9);font-weight:700;font-size:9px;letter-spacing:.8px;text-transform:uppercase;">
                                        {{ __('messages.print_col_product') }}</th>
                                    <th align="center"
                                        style="padding:10px 12px;color:rgba(255,255,255,.9);font-weight:700;font-size:9px;letter-spacing:.8px;text-transform:uppercase;">
                                        {{ __('messages.print_col_qty') }}</th>
                                    <th align="right"
                                        style="padding:10px 12px;color:rgba(255,255,255,.9);font-weight:700;font-size:9px;letter-spacing:.8px;text-transform:uppercase;">
                                        {{ __('messages.print_col_unit_price') }}</th>
                                    <th align="right"
                                        style="padding:10px 12px;color:rgba(255,255,255,.9);font-weight:700;font-size:9px;letter-spacing:.8px;text-transform:uppercase;">
                                        {{ __('messages.print_col_tax') }}</th>
                                    <th align="right"
                                        style="padding:10px 12px;color:rgba(255,255,255,.9);font-weight:700;font-size:9px;letter-spacing:.8px;text-transform:uppercase;">
                                        {{ __('messages.print_col_total') }}</th>
                                </tr>
                                @foreach ($purchase->items as $i => $item)
                                    <tr
                                        style="background:{{ $i % 2 === 0 ? '#ffffff' : '#f8fafc' }};
                   border-top:1px solid #f1f5f9;">
                                        <td style="padding:11px 12px;color:#94a3b8;font-size:11px;">
                                            {{ $i + 1 }}</td>
                                        <td style="padding:11px 12px;">
                                            <div style="font-weight:700;color:#1e293b;font-size:12px;">
                                                {{ $item->product->name ?? 'Deleted Product' }}
                                            </div>
                                            <div
                                                style="font-size:10px;color:#94a3b8;font-family:monospace;margin-top:1px;">
                                                {{ $item->product->code ?? '' }}
                                            </div>
                                        </td>
                                        <td align="center"
                                            style="padding:11px 12px;font-weight:600;color:#334155;font-size:12px;">
                                            {{ number_format($item->quantity, 2) }}
                                            <div style="font-size:9px;color:#94a3b8;">
                                                {{ $item->product->unit_code ?? 'PCS' }}</div>
                                        </td>
                                        <td align="right" style="padding:11px 12px;color:#475569;font-size:12px;">
                                            {{ $sym }}{{ number_format($item->purchase_price, 2) }}
                                        </td>
                                        <td align="right"
                                            style="padding:11px 12px;color:#d97706;font-weight:600;font-size:12px;">
                                            +{{ $sym }}{{ number_format($item->tax_amount, 2) }}
                                        </td>
                                        <td align="right"
                                            style="padding:11px 12px;font-weight:700;color:#1e293b;font-size:12px;">
                                            {{ $sym }}{{ number_format($item->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <!-- ── Totals ── -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="42%"></td>
                                    <td width="58%">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                            style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                                            <tr style="border-bottom:1px solid #f1f5f9;">
                                                <td
                                                    style="padding:9px 16px;font-size:11px;color:#64748b;font-weight:600;">
                                                    {{ __('messages.email_subtotal') }}</td>
                                                <td align="right"
                                                    style="padding:9px 16px;font-size:11px;font-weight:700;color:#1e293b;">
                                                    {{ $sym }}{{ number_format($purchase->sub_total, 2) }}
                                                </td>
                                            </tr>
                                            @if ($purchase->discount_amount > 0)
                                                <tr style="border-bottom:1px solid #f1f5f9;">
                                                    <td
                                                        style="padding:9px 16px;font-size:11px;color:#64748b;font-weight:600;">
                                                        {{ __('messages.email_discount') }}</td>
                                                    <td align="right"
                                                        style="padding:9px 16px;font-size:11px;font-weight:700;color:#dc2626;">
                                                        −{{ $sym }}{{ number_format($purchase->discount_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($purchase->tax_amount > 0)
                                                <tr style="border-bottom:1px solid #f1f5f9;">
                                                    <td
                                                        style="padding:9px 16px;font-size:11px;color:#64748b;font-weight:600;">
                                                        {{ __('messages.email_tax') }}</td>
                                                    <td align="right"
                                                        style="padding:9px 16px;font-size:11px;font-weight:700;color:#d97706;">
                                                        +{{ $sym }}{{ number_format($purchase->tax_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($purchase->shipping_amount > 0)
                                                <tr style="border-bottom:1px solid #f1f5f9;">
                                                    <td
                                                        style="padding:9px 16px;font-size:11px;color:#64748b;font-weight:600;">
                                                        {{ __('messages.email_shipping') }}</td>
                                                    <td align="right"
                                                        style="padding:9px 16px;font-size:11px;font-weight:700;color:#1e293b;">
                                                        +{{ $sym }}{{ number_format($purchase->shipping_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
                                                <td
                                                    style="padding:13px 16px;font-size:11px;font-weight:800;color:rgba(255,255,255,.85);
                           letter-spacing:.6px;text-transform:uppercase;">
                                                    {{ __('messages.email_grand_total') }}</td>
                                                <td align="right"
                                                    style="padding:13px 16px;font-size:19px;font-weight:900;color:#fff;">
                                                    {{ $sym }}{{ number_format($purchase->grand_total, 2) }}
                                                </td>
                                            </tr>
                                            <tr style="border-bottom:1px solid #f1f5f9;">
                                                <td
                                                    style="padding:9px 16px;font-size:11px;color:#64748b;font-weight:600;">
                                                    {{ __('messages.email_paid_amount') }}</td>
                                                <td align="right"
                                                    style="padding:9px 16px;font-size:12px;font-weight:700;color:#16a34a;">
                                                    {{ $sym }}{{ number_format($purchase->paid_amount, 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="padding:9px 16px;font-size:11px;font-weight:700;color:#64748b;">
                                                    {{ __('messages.email_balance_due') }}</td>
                                                <td align="right"
                                                    style="padding:9px 16px;font-size:13px;font-weight:800;
                    color:{{ $purchase->due_amount > 0 ? '#dc2626' : '#16a34a' }};">
                                                    {{ $sym }}{{ number_format($purchase->due_amount, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if ($purchase->notes)
                                <div
                                    style="margin-top:24px;padding:14px 18px;background:#f8fafc;
                  border-left:4px solid #696cff;border-radius:0 8px 8px 0;">
                                    <div
                                        style="font-size:9px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;
                    color:#94a3b8;margin-bottom:6px;">
                                        {{ __('messages.email_notes') }}</div>
                                    <p
                                        style="font-size:12px;color:#64748b;line-height:1.6;white-space:pre-line;margin:0;">
                                        {{ $purchase->notes }}
                                    </p>
                                </div>
                            @endif

                        </td>
                    </tr>

                    <!-- ══ FOOTER ══ -->
                    <tr>
                        <td
                            style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 40px;text-align:center;">
                            @if ($companyAddress || $companyPhone || $companyEmail)
                                <p style="font-size:11px;color:#64748b;margin:0 0 5px;">
                                    @if ($companyAddress)
                                        {{ $companyAddress }}
                                    @endif
                                    @if ($companyPhone)
                                        &nbsp;|&nbsp; 📞 {{ $companyPhone }}
                                    @endif
                                    @if ($companyEmail)
                                        &nbsp;|&nbsp; {{ $companyEmail }}
                                    @endif
                                </p>
                            @endif
                            <p style="font-size:10px;color:#94a3b8;margin:0 0 3px;">
                                {{ __('messages.email_footer_purchase') }}
                                <strong style="color:#696cff;">{{ $companyName }}</strong>.
                                {{ __('messages.email_no_reply') }}
                            </p>
                            <p style="font-size:10px;color:#94a3b8;margin:0;">
                                {{ __('messages.email_generated_on') }} {{ now()->format('d M Y, h:i A') }}
                                &middot; {{ $purchase->user->name ?? 'System' }}
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
