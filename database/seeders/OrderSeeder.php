<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Disable foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🔥 Pehla child table clean karo
        DB::table('order_items')->truncate();

        // 🔥 Pachi parent table
        DB::table('orders')->truncate();

        // 🔥 Enable back
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $orders = [
            [
                'user_id'          => 1,
                'product_id'       => 1,
                'order_number'     => 'ORD-0001',
                'order_date'       => '2026-01-10',
                'status'           => 'active',
                'quantity'         => 2,
                'price'            => 500.00,
                'total_amount'     => 1000.00,
                'payment_method'   => 'razorpay',
                'payment_status'   => 'paid',
                'shipping_address' => 'Ahmedabad - 380001',
            ],
            [
                'user_id'          => 2,
                'product_id'       => 2,
                'order_number'     => 'ORD-0002',
                'order_date'       => '2026-01-15',
                'status'           => 'active',
                'quantity'         => 1,
                'price'            => 1200.00,
                'total_amount'     => 1200.00,
                'payment_method'   => 'cod',
                'payment_status'   => 'pending',
                'shipping_address' => 'Ahmedabad - 380002',
            ],
            [
                'user_id'          => 3,
                'product_id'       => 3,
                'order_number'     => 'ORD-0003',
                'order_date'       => '2026-02-01',
                'status'           => 'active',
                'quantity'         => 3,
                'price'            => 300.00,
                'total_amount'     => 900.00,
                'payment_method'   => 'upi',
                'payment_status'   => 'paid',
                'shipping_address' => 'Ahmedabad - 380003',
            ],
            [
                'user_id'          => 4,
                'product_id'       => 1,
                'order_number'     => 'ORD-0004',
                'order_date'       => '2026-02-10',
                'status'           => 'inactive',
                'quantity'         => 1,
                'price'            => 800.00,
                'total_amount'     => 800.00,
                'payment_method'   => 'card',
                'payment_status'   => 'failed',
                'shipping_address' => 'Ahmedabad - 380004',
            ],
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insert([
                ...$order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}