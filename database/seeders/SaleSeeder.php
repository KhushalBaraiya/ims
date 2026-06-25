<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * SaleSeeder — creates realistic sales across 6 months
 * covering: Completed, Draft, Cancelled statuses,
 * multiple customers, various payment methods,
 * partial payments (due amount > 0), and different product mixes.
 */
class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $adminId   = 1; // Super Admin user
        $customers = Customer::all()->keyBy('email');
        $products  = Product::with('stock')->where('status', 'active')->get()->keyBy('code');

        // Helper: get product or null
        $p = fn(string $code) => $products->get($code);

        // Helper: generate invoice number
        $inv = 0;
        $makeInv = function () use (&$inv) {
            $inv++;
            return 'INV-' . str_pad($inv, 4, '0', STR_PAD_LEFT);
        };

        $sales = [
            // ── January ──────────────────────────────────────────────────────
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-01-05',
                'customer_email' => 'amit@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'DSP-XIA-RN12', 'qty' => 2, 'price' => 2800],
                    ['code' => 'ACC-USB-TYPEC', 'qty' => 5, 'price' => 150],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-01-12',
                'customer_email' => 'rahul@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'UPI / QR',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MB-DEL-INS15', 'qty' => 1, 'price' => 7200],
                    ['code' => 'DSP-DEL-INS15', 'qty' => 1, 'price' => 5200],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-01-20',
                'customer_email' => 'priya@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 80,   // partial payment
                'items' => [
                    ['code' => 'MMB-SAM-A53', 'qty' => 2, 'price' => 4800],
                    ['code' => 'ACC-SG-TEMP', 'qty' => 10, 'price' => 99],
                ],
            ],

            // ── February ──────────────────────────────────────────────────────
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-02-03',
                'customer_email' => 'vikram@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'RAM-SAM-8G3200', 'qty' => 4, 'price' => 2499],
                    ['code' => 'SSD-SAM-256G',   'qty' => 2, 'price' => 3200],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-02-14',
                'customer_email' => 'amit@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Card',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'ACC-EAR-BOAT100', 'qty' => 3, 'price' => 450],
                    ['code' => 'ACC-PB-MI20K',    'qty' => 2, 'price' => 1299],
                    ['code' => 'ACC-CASE-SIL',    'qty' => 5, 'price' => 149],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-02-22',
                'customer_email' => 'neha@gmail.com',
                'status'         => 'Draft',
                'payment_method' => 'Cash',
                'paid_pct'       => 0,
                'items' => [
                    ['code' => 'DSP-APL-IP13', 'qty' => 1, 'price' => 8500],
                ],
            ],

            // ── March ─────────────────────────────────────────────────────────
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-03-07',
                'customer_email' => 'suresh@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'UPI / QR',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MB-HP-PAV14',  'qty' => 1, 'price' => 6000],
                    ['code' => 'DSP-HP-PAV15', 'qty' => 1, 'price' => 4800],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-03-15',
                'customer_email' => 'kavita@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MMB-XIA-RN12', 'qty' => 3, 'price' => 4200],
                    ['code' => 'ACC-SG-TEMP',  'qty' => 15, 'price' => 99],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-03-28',
                'customer_email' => 'ravi@gmail.com',
                'status'         => 'Cancelled',
                'payment_method' => 'Cash',
                'paid_pct'       => 0,
                'items' => [
                    ['code' => 'MB-ASU-VB15', 'qty' => 1, 'price' => 6500],
                ],
            ],

            // ── April ─────────────────────────────────────────────────────────
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-04-02',
                'customer_email' => 'amit@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'HDD-SEA-1TB',    'qty' => 2, 'price' => 3800],
                    ['code' => 'RAM-SAM-8G3200',  'qty' => 2, 'price' => 2499],
                    ['code' => 'ACC-USB-TYPEC',   'qty' => 10, 'price' => 150],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-04-18',
                'customer_email' => 'rahul@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'UPI / QR',
                'paid_pct'       => 60,  // partial
                'items' => [
                    ['code' => 'MB-APL-MBP14M3', 'qty' => 1, 'price' => 85000],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-04-25',
                'customer_email' => 'priya@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Card',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'DSP-SAM-A53',  'qty' => 1, 'price' => 4200],
                    ['code' => 'ACC-CASE-SIL', 'qty' => 8, 'price' => 149],
                ],
            ],

            // ── May ───────────────────────────────────────────────────────────
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-05-05',
                'customer_email' => 'vikram@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MMB-OP-NCE3', 'qty' => 2, 'price' => 5200],
                    ['code' => 'ACC-SG-TEMP', 'qty' => 20, 'price' => 99],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-05-14',
                'customer_email' => 'neha@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'SSD-SAM-256G',   'qty' => 3, 'price' => 3200],
                    ['code' => 'RAM-SAM-8G3200',  'qty' => 4, 'price' => 2499],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-05-22',
                'customer_email' => 'suresh@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'ACC-PB-MI20K', 'qty' => 5, 'price' => 1299],
                    ['code' => 'ACC-USB-TYPEC', 'qty' => 20, 'price' => 150],
                    ['code' => 'ACC-SG-TEMP',  'qty' => 30, 'price' => 99],
                ],
            ],

            // ── June ──────────────────────────────────────────────────────────
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-06-03',
                'customer_email' => 'kavita@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'UPI / QR',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MB-LEN-IP3',    'qty' => 1, 'price' => 6800],
                    ['code' => 'DSP-DEL-INS15', 'qty' => 1, 'price' => 5200],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-06-10',
                'customer_email' => 'ravi@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MMB-SAM-A53', 'qty' => 3, 'price' => 4800],
                    ['code' => 'ACC-EAR-CLR01', 'qty' => 10, 'price' => 200],
                ],
            ],
            [
                'invoice_no'     => $makeInv(),
                'invoice_date'   => '2026-06-20',
                'customer_email' => 'amit@gmail.com',
                'status'         => 'Completed',
                'payment_method' => 'Card',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'HDD-SEA-1TB',   'qty' => 3, 'price' => 3800],
                    ['code' => 'SSD-SAM-256G',   'qty' => 2, 'price' => 3200],
                    ['code' => 'RAM-SAM-8G3200', 'qty' => 3, 'price' => 2499],
                ],
            ],
        ];

        foreach ($sales as $saleData) {
            $customer  = $customers->get($saleData['customer_email']);
            $customerId = $customer?->id ?? null;

            $subTotal = 0;
            $taxTotal = 0;
            $itemsToCreate = [];

            foreach ($saleData['items'] as $item) {
                $product = $p($item['code']);
                if (!$product) continue;

                $qty      = $item['qty'];
                $price    = $item['price'];
                $taxPct   = $product->tax_percentage ?? 0;
                $taxAmt   = round(($taxPct / 100) * $price * $qty, 2);
                $lineTotal = round($price * $qty, 2);

                $subTotal  += $lineTotal;
                $taxTotal  += $taxAmt;

                $itemsToCreate[] = [
                    'product_id'      => $product->id,
                    'quantity'        => $qty,
                    'unit_price'      => $price,
                    'tax_amount'      => $taxAmt,
                    'discount_amount' => 0,
                    'total_amount'    => $lineTotal + $taxAmt,
                ];
            }

            if (empty($itemsToCreate)) continue;

            $grandTotal  = round($subTotal + $taxTotal, 2);
            $paidAmount  = round($grandTotal * ($saleData['paid_pct'] / 100), 2);
            $dueAmount   = round($grandTotal - $paidAmount, 2);

            $sale = Sale::create([
                'invoice_no'      => $saleData['invoice_no'],
                'invoice_date'    => $saleData['invoice_date'],
                'customer_id'     => $customerId,
                'sales_person_id' => $adminId,
                'sub_total'       => $subTotal,
                'tax_amount'      => $taxTotal,
                'discount_amount' => 0,
                'shipping_amount' => 0,
                'grand_total'     => $grandTotal,
                'paid_amount'     => $paidAmount,
                'due_amount'      => $dueAmount,
                'payment_method'  => $saleData['payment_method'],
                'status'          => $saleData['status'],
                'user_id'         => $adminId,
                'notes'           => null,
            ]);

            foreach ($itemsToCreate as $item) {
                SaleItem::create(array_merge($item, ['sale_id' => $sale->id]));

                // Deduct stock for Completed sales
                if ($saleData['status'] === 'Completed') {
                    $stock = Stock::where('product_id', $item['product_id'])->first();
                    if ($stock) {
                        $stock->decrement('quantity', $item['quantity']);
                    }
                }
            }
        }

        $this->command->info('✅ SaleSeeder done — ' . count($sales) . ' sales seeded across Jan–Jun 2026.');
    }
}
