<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * PurchaseSeeder — creates realistic purchase orders across 6 months
 * covering: Completed, Pending, Cancelled statuses,
 * multiple suppliers, various payment methods.
 */
class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminId   = 1;
        $suppliers = Supplier::all()->keyBy('email');
        $products  = Product::with('stock')->where('status', 'active')->get()->keyBy('code');

        $p  = fn(string $code) => $products->get($code);
        $s  = fn(string $email) => $suppliers->get($email)?->id ?? null;

        $po = 0;
        $makePo = function () use (&$po) {
            $po++;
            return 'PUR-SEED-' . str_pad($po, 5, '0', STR_PAD_LEFT);
        };

        $purchases = [
            // ── January ──────────────────────────────────────────────────────
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-01-03',
                'supplier_email' => 'sales@mobilesparehub.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MMB-SAM-A53',  'qty' => 10, 'price' => 3200],
                    ['code' => 'MMB-XIA-RN12', 'qty' => 15, 'price' => 2800],
                    ['code' => 'DSP-SAM-A53',  'qty' => 8,  'price' => 2800],
                ],
            ],
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-01-18',
                'supplier_email' => 'contact@laptoppartsworld.com',
                'status'         => 'Completed',
                'payment_method' => 'Cheque',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MB-DEL-INS15', 'qty' => 5,  'price' => 5000],
                    ['code' => 'MB-HP-PAV14',  'qty' => 5,  'price' => 4200],
                    ['code' => 'DSP-DEL-INS15','qty' => 6,  'price' => 3500],
                ],
            ],

            // ── February ──────────────────────────────────────────────────────
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-02-08',
                'supplier_email' => 'rajesh@rajeshelectronics.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'RAM-SAM-8G3200', 'qty' => 20, 'price' => 1800],
                    ['code' => 'SSD-SAM-256G',   'qty' => 15, 'price' => 2200],
                    ['code' => 'HDD-SEA-1TB',    'qty' => 10, 'price' => 2800],
                ],
            ],
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-02-20',
                'supplier_email' => 'global@globaltech.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 80,  // partial payment
                'items' => [
                    ['code' => 'ACC-USB-TYPEC', 'qty' => 100, 'price' => 80],
                    ['code' => 'ACC-SG-TEMP',   'qty' => 150, 'price' => 40],
                    ['code' => 'ACC-CASE-SIL',  'qty' => 100, 'price' => 60],
                ],
            ],

            // ── March ─────────────────────────────────────────────────────────
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-03-05',
                'supplier_email' => 'sales@mobilesparehub.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'DSP-APL-IP13',  'qty' => 5,  'price' => 5500],
                    ['code' => 'MMB-OP-NCE3',   'qty' => 8,  'price' => 3500],
                    ['code' => 'DSP-XIA-RN12',  'qty' => 12, 'price' => 1800],
                ],
            ],
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-03-22',
                'supplier_email' => 'contact@laptoppartsworld.com',
                'status'         => 'Pending',
                'payment_method' => 'Cheque',
                'paid_pct'       => 0,
                'items' => [
                    ['code' => 'MB-APL-MBP14M3', 'qty' => 3, 'price' => 65000],
                ],
            ],

            // ── April ─────────────────────────────────────────────────────────
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-04-10',
                'supplier_email' => 'rajesh@rajeshelectronics.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'CPU-INT-I512400', 'qty' => 5,  'price' => 13500],
                    ['code' => 'RAM-SAM-8G3200',  'qty' => 15, 'price' => 1800],
                ],
            ],
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-04-25',
                'supplier_email' => 'global@globaltech.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'ACC-PB-MI20K',    'qty' => 50, 'price' => 800],
                    ['code' => 'ACC-EAR-BOAT100',  'qty' => 60, 'price' => 250],
                ],
            ],

            // ── May ───────────────────────────────────────────────────────────
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-05-08',
                'supplier_email' => 'sales@mobilesparehub.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'MMB-SAM-A53',  'qty' => 15, 'price' => 3200],
                    ['code' => 'DSP-SAM-A53',  'qty' => 10, 'price' => 2800],
                    ['code' => 'MMB-XIA-RN12', 'qty' => 20, 'price' => 2800],
                ],
            ],
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-05-20',
                'supplier_email' => 'contact@laptoppartsworld.com',
                'status'         => 'Cancelled',
                'payment_method' => 'Cash',
                'paid_pct'       => 0,
                'items' => [
                    ['code' => 'MB-ASU-TUFF15', 'qty' => 5, 'price' => 7500],
                ],
            ],

            // ── June ──────────────────────────────────────────────────────────
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-06-05',
                'supplier_email' => 'rajesh@rajeshelectronics.com',
                'status'         => 'Completed',
                'payment_method' => 'Bank Transfer',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'SSD-SAM-256G',   'qty' => 20, 'price' => 2200],
                    ['code' => 'HDD-SEA-1TB',    'qty' => 15, 'price' => 2800],
                    ['code' => 'RAM-SAM-8G3200', 'qty' => 25, 'price' => 1800],
                ],
            ],
            [
                'purchase_no'    => $makePo(),
                'purchase_date'  => '2026-06-18',
                'supplier_email' => 'global@globaltech.com',
                'status'         => 'Completed',
                'payment_method' => 'Cash',
                'paid_pct'       => 100,
                'items' => [
                    ['code' => 'ACC-USB-TYPEC', 'qty' => 200, 'price' => 80],
                    ['code' => 'ACC-SG-TEMP',   'qty' => 200, 'price' => 40],
                    ['code' => 'ACC-CASE-SIL',  'qty' => 150, 'price' => 60],
                    ['code' => 'ACC-PB-MI20K',  'qty' => 60,  'price' => 800],
                ],
            ],
        ];

        foreach ($purchases as $purchaseData) {
            $supplierId = $s($purchaseData['supplier_email']);

            $subTotal = 0;
            $taxTotal = 0;
            $itemsToCreate = [];

            foreach ($purchaseData['items'] as $item) {
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
                    'purchase_price'  => $price,
                    'tax_amount'      => $taxAmt,
                    'discount_amount' => 0,
                    'total_amount'    => $lineTotal + $taxAmt,
                ];
            }

            if (empty($itemsToCreate)) continue;

            $grandTotal = round($subTotal + $taxTotal, 2);
            $paidAmount = round($grandTotal * ($purchaseData['paid_pct'] / 100), 2);
            $dueAmount  = round($grandTotal - $paidAmount, 2);

            $purchase = Purchase::create([
                'purchase_no'    => $purchaseData['purchase_no'],
                'purchase_date'  => $purchaseData['purchase_date'],
                'supplier_id'    => $supplierId,
                'sub_total'      => $subTotal,
                'tax_amount'     => $taxTotal,
                'discount_amount'=> 0,
                'shipping_amount'=> 0,
                'grand_total'    => $grandTotal,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_method' => $purchaseData['payment_method'],
                'status'         => $purchaseData['status'],
                'user_id'        => $adminId,
                'notes'          => null,
            ]);

            foreach ($itemsToCreate as $item) {
                PurchaseItem::create(array_merge($item, ['purchase_id' => $purchase->id]));

                // Add to stock for Completed purchases
                if ($purchaseData['status'] === 'Completed') {
                    $stock = Stock::firstOrCreate(
                        ['product_id' => $item['product_id']],
                        ['quantity' => 0]
                    );
                    $stock->increment('quantity', $item['quantity']);
                }
            }
        }

        $this->command->info('✅ PurchaseSeeder done — ' . count($purchases) . ' purchase orders seeded across Jan–Jun 2026.');
    }
}
