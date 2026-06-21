<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_items')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $items = [
            [
                'order_id'       => 1,
                'product_id'     => 1,
                'product_name'   => 'iPhone 13',
                'product_image'  => 'iphone13.jpg',
                'quantity'       => 2,
                'price'          => 500.00,
                'total_price'    => 1000.00,
            ],
            [
                'order_id'       => 1,
                'product_id'     => 2,
                'product_name'   => 'Samsung Galaxy',
                'product_image'  => 'samsung.jpg',
                'quantity'       => 1,
                'price'          => 300.00,
                'total_price'    => 300.00,
            ],
            [
                'order_id'       => 2,
                'product_id'     => 2,
                'product_name'   => 'Samsung Galaxy',
                'product_image'  => 'samsung.jpg',
                'quantity'       => 1,
                'price'          => 1200.00,
                'total_price'    => 1200.00,
            ],
            [
                'order_id'       => 3,
                'product_id'     => 3,
                'product_name'   => 'Headphones',
                'product_image'  => 'headphones.jpg',
                'quantity'       => 3,
                'price'          => 300.00,
                'total_price'    => 900.00,
            ],
            [
                'order_id'       => 4,
                'product_id'     => 1,
                'product_name'   => 'iPhone 13',
                'product_image'  => 'iphone13.jpg',
                'quantity'       => 1,
                'price'          => 800.00,
                'total_price'    => 800.00,
            ],
        ];

        foreach ($items as $item) {
            DB::table('order_items')->insert([
                ...$item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}