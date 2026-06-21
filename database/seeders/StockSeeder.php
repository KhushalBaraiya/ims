<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = [
            ['product_id' => 1, 'date' => '2026-01-05', 'type' => 'in',         'quantity' => 100, 'price' => 500.00,  'total_price' => 50000.00, 'status' => 'active'],
            ['product_id' => 1, 'date' => '2026-01-20', 'type' => 'out',        'quantity' => 30,  'price' => 500.00,  'total_price' => 15000.00, 'status' => 'active'],
            ['product_id' => 2, 'date' => '2026-02-01', 'type' => 'in',         'quantity' => 50,  'price' => 1200.00, 'total_price' => 60000.00, 'status' => 'active'],
            ['product_id' => 2, 'date' => '2026-02-15', 'type' => 'out',        'quantity' => 10,  'price' => 1200.00, 'total_price' => 12000.00, 'status' => 'active'],
            ['product_id' => 3, 'date' => '2026-03-01', 'type' => 'in',         'quantity' => 75,  'price' => 800.00,  'total_price' => 60000.00, 'status' => 'active'],
            ['product_id' => 3, 'date' => '2026-03-10', 'type' => 'adjustment', 'quantity' => 5,   'price' => 800.00,  'total_price' => 4000.00,  'status' => 'inactive'],
        ];

        foreach ($stocks as $stock) {
            DB::table('stocks')->insert(array_merge($stock, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}


 
