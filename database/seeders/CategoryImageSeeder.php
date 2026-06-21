<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryImageSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['category_id' => 1, 'title' => "Men's Clothing Banner",  'image' => '1.jpg'],
            ['category_id' => 2, 'title' => "Women's Clothing Banner", 'image' => '2.jpg'],
            ['category_id' => 3, 'title' => 'Kids Clothing Banner',    'image' => '3.jpg'],
            ['category_id' => 4, 'title' => 'Footwear Banner',         'image' => '4.jpg'],
            ['category_id' => 5, 'title' => 'Accessories Banner',      'image' => '5.jpg'],
        ];

        foreach ($items as $item) {
            DB::table('category_images')->insert([
                'category_id' => $item['category_id'],
                'title'       => $item['title'],
                'image'       => $item['image'],
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
