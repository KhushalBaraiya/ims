<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Free Shipping',
                'description' => 'Free shipping on orders above ₹499.',
                'price' => 0,
                'image' => 'services/free-shipping.png', // ઈમેજ પાથ ઉમેર્યો
                'status' => 'active'
            ],
            [
                'name' => 'Easy Returns',
                'description' => '7-day hassle-free return policy.',
                'price' => 0,
                'image' => 'services/easy-returns.png',
                'status' => 'active'
            ],
            [
                'name' => 'Gift Wrapping',
                'description' => 'Premium gift wrapping available.',
                'price' => 49,
                'image' => 'services/gift-wrap.png',
                'status' => 'active'
            ],
            [
                'name' => 'Express Delivery',
                'description' => 'Delivery within 24 hours.',
                'price' => 99,
                'image' => 'services/express-delivery.png',
                'status' => 'active'
            ],
            [
                'name' => 'Cash on Delivery',
                'description' => 'Pay when your order arrives.',
                'price' => 0,
                'image' => 'services/cod.png',
                'status' => 'inactive'
            ],
        ];

        foreach ($services as $s) {
            DB::table('product_services')->insert([
                'name'        => $s['name'],
                'description' => $s['description'],
                'price'       => $s['price'],
                'image'       => $s['image'], // ઈમેજ અહીં ઇન્સર્ટ થશે
                'status'      => $s['status'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
