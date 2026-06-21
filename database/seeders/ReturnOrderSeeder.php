<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnOrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('return_orders')->insert([
            [
                'order_id'      => 1,
                'user_id'       => 1,
                'product_id'    => 1,
                'quantity'      => 1,
                'reason'        => 'Product was damaged on delivery',
                'return_date'   => '2026-01-15',
                'return_type'   => 'return',
                'refund_amount' => 500.00,
                'status'        => 'active',
                'admin_note'    => 'Refund processed successfully',
                'is_active'     => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'order_id'      => 2,
                'user_id'       => 2,
                'product_id'    => 2,
                'quantity'      => 1,
                'reason'        => 'Wrong size delivered',
                'return_date'   => '2026-01-20',
                'return_type'   => 'exchange',
                'refund_amount' => 0.00,
                'status'        => 'active',
                'admin_note'    => null,
                'is_active'     => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'order_id'      => 3,
                'user_id'       => 3,
                'product_id'    => 3,
                'quantity'      => 2,
                'reason'        => 'Product quality not good',
                'return_date'   => '2026-02-05',
                'return_type'   => 'refund',
                'refund_amount' => 600.00,
                'status'        => 'active',
                'admin_note'    => 'Full refund given',
                'is_active'     => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'order_id'      => 4,
                'user_id'       => 1,
                'product_id'    => 1,
                'quantity'      => 1,
                'reason'        => 'Changed mind',
                'return_date'   => '2026-02-12',
                'return_type'   => 'return',
                'refund_amount' => 800.00,
                'status'        => 'inactive',
                'admin_note'    => 'Return period expired',
                'is_active'     => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}