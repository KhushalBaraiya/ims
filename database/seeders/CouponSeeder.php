<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['code' => 'SAVE10',  'name' => '10% Off',       'product_id' => 1, 'discount_type' => 'percentage', 'discount_value' => 10, 'minimum_order_amount' => 500,  'maximum_discount_value' => 200],
            ['code' => 'FLAT200', 'name' => 'Flat 200 Off',  'product_id' => 2, 'discount_type' => 'fixed',      'discount_value' => 200,'minimum_order_amount' => 1000, 'maximum_discount_value' => 200],
            ['code' => 'SUMMER20','name' => 'Summer 20% Off','product_id' => 3, 'discount_type' => 'percentage', 'discount_value' => 20, 'minimum_order_amount' => 800,  'maximum_discount_value' => 500],
            ['code' => 'WELCOME5','name' => 'Welcome 5% Off','product_id' => 4, 'discount_type' => 'percentage', 'discount_value' => 5,  'minimum_order_amount' => 300,  'maximum_discount_value' => 100],
            ['code' => 'DEAL50',  'name' => 'Deal 50 Off',   'product_id' => 4, 'discount_type' => 'fixed',      'discount_value' => 50, 'minimum_order_amount' => 400,  'maximum_discount_value' => 50],
        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->insert([
                'code'                   => $coupon['code'],
                'name'                   => $coupon['name'],
                'product_id'             => $coupon['product_id'],
                'discount_type'          => $coupon['discount_type'],
                'discount_value'         => $coupon['discount_value'],
                'start_date'             => now(),
                'end_date'               => now()->addMonths(3),
                'minimum_order_amount'   => $coupon['minimum_order_amount'],
                'maximum_discount_value' => $coupon['maximum_discount_value'],
                'status'                 => 'active',
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);
        }
    }
}
