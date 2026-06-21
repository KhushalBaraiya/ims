<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductShippingSeeder extends Seeder
{
    public function run(): void
    {
        $shippings = [
            ['title' => 'Standard Delivery',  'delivery_time' => '5-7 Business Days', 'charge' => 0,   'free_above' => 499,  'cod_available' => 1, 'status' => 'active'],
            ['title' => 'Express Delivery',   'delivery_time' => '1-2 Business Days', 'charge' => 99,  'free_above' => 999,  'cod_available' => 1, 'status' => 'active'],
            ['title' => 'Same Day Delivery',  'delivery_time' => 'Same Day',          'charge' => 149, 'free_above' => null, 'cod_available' => 0, 'status' => 'active'],
            ['title' => 'Next Day Delivery',  'delivery_time' => 'Next Business Day', 'charge' => 79,  'free_above' => 799,  'cod_available' => 1, 'status' => 'active'],
            ['title' => 'International Ship', 'delivery_time' => '10-15 Days',        'charge' => 499, 'free_above' => null, 'cod_available' => 0, 'status' => 'inactive'],
        ];

        foreach ($shippings as $s) {
            DB::table('product_shippings')->insert([
                'title'         => $s['title'],
                'delivery_time' => $s['delivery_time'],
                'charge'        => $s['charge'],
                'free_above'    => $s['free_above'],
                'cod_available' => $s['cod_available'],
                'status'        => $s['status'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}

