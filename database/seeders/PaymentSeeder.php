<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $payments = [
            [
                'user_id'        => 2,
                'order_id'       => 1,
                'amount'         => 1000.00,
                'currency'       => 'INR',
                'payment_method' => 'upi',
                'payment_status' => 'success',
                'transaction_id' => 'TXN100001',
                'gateway'        => 'razorpay',
                'paid_at'        => '2026-01-10 10:30:00',
                'status'         => 'active',
            ],
            [
                'user_id'        => 3,
                'order_id'       => 2,
                'amount'         => 1200.00,
                'currency'       => 'INR',
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'transaction_id' => null,
                'gateway'        => null,
                'paid_at'        => null,
                'status'         => 'active',
            ],
            [
                'user_id'        => 4,
                'order_id'       => 3,
                'amount'         => 900.00,
                'currency'       => 'INR',
                'payment_method' => 'card',
                'payment_status' => 'success',
                'transaction_id' => 'TXN100003',
                'gateway'        => 'stripe',
                'paid_at'        => '2026-02-01 14:00:00',
                'status'         => 'active',
            ],
            [
                'user_id'        => 2,
                'order_id'       => 4,
                'amount'         => 800.00,
                'currency'       => 'INR',
                'payment_method' => 'card',
                'payment_status' => 'failed',
                'transaction_id' => 'TXN100004',
                'gateway'        => 'razorpay',
                'paid_at'        => null,
                'status'         => 'inactive',
            ],
        ];

        foreach ($payments as $payment) {
            DB::table('payments')->insert(array_merge($payment, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
