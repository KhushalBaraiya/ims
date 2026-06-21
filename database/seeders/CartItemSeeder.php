<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $cartItems = [
            ['user_id' => 2, 'product_id' => 1, 'quantity' => 1],
            ['user_id' => 2, 'product_id' => 3, 'quantity' => 2],
            ['user_id' => 3, 'product_id' => 2, 'quantity' => 1],
            ['user_id' => 3, 'product_id' => 4, 'quantity' => 3],
            ['user_id' => 4, 'product_id' => 3, 'quantity' => 1],
        ];

        foreach ($cartItems as $item) {
            DB::table('cart_items')->insert([
                'user_id'    => $item['user_id'],
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
