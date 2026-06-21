<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishListSeeder extends Seeder
{
    public function run(): void
    {
        $wishLists = [
            ['user_id' => 2, 'product_id' => 1, 'status' => 'active'],
            ['user_id' => 2, 'product_id' => 2, 'status' => 'active'],
            ['user_id' => 3, 'product_id' => 2, 'status' => 'active'],
            ['user_id' => 3, 'product_id' => 3, 'status' => 'inactive'],
            ['user_id' => 4, 'product_id' => 4, 'status' => 'active'],
        ];

        foreach ($wishLists as $item) {
            DB::table('wish_lists')->insert([
                'user_id'    => $item['user_id'],
                'product_id' => $item['product_id'],
                'status'     => $item['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
