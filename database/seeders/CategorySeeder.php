<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Accessories Item',
                'slug'        => 'ACCESSORIES-ITEM',
                'status'      => 'active',
                'description' => 'All accessories items',
                'subs'        => [
                    ['name' => 'USB Cables',    'slug' => 'USB-CABLES'],
                    ['name' => 'Earphones',     'slug' => 'EARPHONES'],
                    ['name' => 'Power Banks',   'slug' => 'POWER-BANKS'],
                    ['name' => 'Screen Guards', 'slug' => 'SCREEN-GUARDS'],
                    ['name' => 'Phone Cases',   'slug' => 'PHONE-CASES'],
                ],
            ],
            [
                'name'        => 'Laptop motherboard',
                'slug'        => 'LAPTOP-MB',
                'status'      => 'active',
                'description' => 'Laptop motherboards',
                'subs'        => [
                    ['name' => 'Asus Motherboards',   'slug' => 'ASUS-MB'],
                    ['name' => 'Dell Motherboards',   'slug' => 'DELL-MB'],
                    ['name' => 'HP Motherboards',     'slug' => 'HP-MB'],
                    ['name' => 'Lenovo Motherboards', 'slug' => 'LENOVO-MB'],
                ],
            ],
            [
                'name'        => 'mobile motherboard',
                'slug'        => 'MOBILE-MB',
                'status'      => 'active',
                'description' => 'Mobile motherboards',
                'subs'        => [
                    ['name' => 'Samsung Motherboards', 'slug' => 'SAMSUNG-MOBILE-MB'],
                    ['name' => 'Xiaomi Motherboards',  'slug' => 'XIAOMI-MOBILE-MB'],
                    ['name' => 'OnePlus Motherboards', 'slug' => 'ONEPLUS-MOBILE-MB'],
                    ['name' => 'Realme Motherboards',  'slug' => 'REALME-MOBILE-MB'],
                    ['name' => 'Oppo Motherboards',    'slug' => 'OPPO-MOBILE-MB'],
                ],
            ],
            [
                'name'        => 'Display',
                'slug'        => 'DISPLAY',
                'status'      => 'active',
                'description' => 'Displays and screens',
                'subs'        => [
                    ['name' => 'Laptop Display', 'slug' => 'LAPTOP-DISPLAY'],
                    ['name' => 'Mobile Display', 'slug' => 'MOBILE-DISPLAY'],
                ],
            ],
            [
                'name'        => 'electronice item',
                'slug'        => 'ELECTRONICE-ITEM',
                'status'      => 'active',
                'description' => 'Electronic items and hardware',
                'subs'        => [
                    ['name' => 'Processors CPU',  'slug' => 'CPUS'],
                    ['name' => 'RAM Memory',       'slug' => 'RAM'],
                    ['name' => 'Hard Drives',      'slug' => 'HDD'],
                    ['name' => 'SSDs',             'slug' => 'SSD'],
                    ['name' => 'Graphics Cards',   'slug' => 'GPU'],
                ],
            ],
        ];

        foreach ($categories as $cat) {
            $main = MainCategory::firstOrCreate(['slug' => $cat['slug']], [
                'name' => $cat['name'],
                'status' => $cat['status'],
                'description' => $cat['description']
            ]);

            foreach ($cat['subs'] as $sub) {
                SubCategory::firstOrCreate(['slug' => $sub['slug']], [
                    'main_category_id' => $main->id,
                    'name' => $sub['name'],
                    'status' => 'active',
                    'description' => $sub['name']
                ]);
            }
        }
    }
}

