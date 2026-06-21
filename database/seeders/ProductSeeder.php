<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'                 => 'Classic Round Neck T-Shirt',
                'sku'                  => 'PRD-001',
                'category_id'          => 1,
                'subcategory_id'       => 1,
                'sub_in_categories_id' => 1,
                'brand_id'             => 1,
                'description'          => '100% pure cotton round neck t-shirt for daily casual wear.',
                'product_details'      => "Fabric: 100% Cotton\nFit: Regular Fit\nNeck: Round Neck",
                'original_price'       => 999,
                'price'                => 699,
                'total_price'          => 699,
                'discount_percent'     => 30,
                'quantity'             => 150,
                'images'               => ['1.jpg', '2.jpg'],
            ],
            [
                'name'                 => 'Slim Fit Formal Shirt',
                'sku'                  => 'PRD-002',
                'category_id'          => 1,
                'subcategory_id'       => 2,
                'sub_in_categories_id' => 2,
                'brand_id'             => 2,
                'description'          => 'Premium slim fit formal shirt for office wear.',
                'product_details'      => "Fabric: Cotton Blend\nFit: Slim Fit\nOccasion: Formal",
                'original_price'       => 1499,
                'price'                => 1099,
                'total_price'          => 1099,
                'discount_percent'     => 27,
                'quantity'             => 80,
                'images'               => ['3.jpg', '4.jpg'],
            ],
            [
                'name'                 => 'Women Printed Kurti',
                'sku'                  => 'PRD-003',
                'category_id'          => 2,
                'subcategory_id'       => 3,
                'sub_in_categories_id' => 3,
                'brand_id'             => 3,
                'description'          => 'Beautiful printed kurti for women, perfect for casual outings.',
                'product_details'      => "Fabric: Rayon\nFit: Regular\nLength: Knee Length",
                'original_price'       => 799,
                'price'                => 549,
                'total_price'          => 549,
                'discount_percent'     => 31,
                'quantity'             => 200,
                'images'               => ['5.jpg', '6.jpg'],
            ],
            [
                'name'                 => 'Kids Graphic T-Shirt',
                'sku'                  => 'PRD-004',
                'category_id'          => 3,
                'subcategory_id'       => 4,
                'sub_in_categories_id' => 4,
                'brand_id'             => 4,
                'description'          => 'Fun graphic t-shirt for kids, soft and comfortable.',
                'product_details'      => "Fabric: 100% Cotton\nFit: Regular\nAge: 4-12 Years",
                'original_price'       => 499,
                'price'                => 349,
                'total_price'          => 349,
                'discount_percent'     => 30,
                'quantity'             => 120,
                'images'               => ['7.jpg', '8.jpg'],
            ],
        ];

        foreach ($products as $p) {
            DB::table('products')->insert([
                'name'                 => $p['name'],
                'slug'                 => Str::slug($p['name']) . '-' . time() . rand(10, 99),
                'sku'                  => $p['sku'],
                'category_id'          => $p['category_id'],
                'subcategory_id'       => $p['subcategory_id'],
                'sub_in_categories_id' => $p['sub_in_categories_id'],
                'brand_id'             => $p['brand_id'],
                'description'          => $p['description'],
                'product_details'      => $p['product_details'],
                'original_price'       => $p['original_price'],
                'price'                => $p['price'],
                'total_price'          => $p['total_price'],
                'discount_percent'     => $p['discount_percent'],
                'quantity'             => $p['quantity'],
                'images'               => json_encode($p['images']),
                'rating'               => 0,
                'status'               => 'active',
                'is_favourite'         => 0,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }
    }
}
