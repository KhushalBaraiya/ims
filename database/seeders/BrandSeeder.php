<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple',   'slug' => 'APPLE',   'status' => 'active'],
            ['name' => 'Samsung', 'slug' => 'SAMSUNG', 'status' => 'active'],
            ['name' => 'Asus',    'slug' => 'ASUS',    'status' => 'active'],
            ['name' => 'Dell',    'slug' => 'DELL',    'status' => 'active'],
            ['name' => 'HP',      'slug' => 'HP',      'status' => 'active'],
            ['name' => 'Lenovo',  'slug' => 'LENOVO',  'status' => 'active'],
            ['name' => 'Intel',   'slug' => 'INTEL',   'status' => 'active'],
            ['name' => 'AMD',     'slug' => 'AMD',     'status' => 'active'],
            ['name' => 'Xiaomi',  'slug' => 'XIAOMI',  'status' => 'active'],
            ['name' => 'OnePlus', 'slug' => 'ONEPLUS', 'status' => 'active'],
            ['name' => 'Realme',  'slug' => 'REALME',  'status' => 'active'],
            ['name' => 'Oppo',    'slug' => 'OPPO',    'status' => 'active'],
            ['name' => 'Vivo',    'slug' => 'VIVO',    'status' => 'active'],
            ['name' => 'Generic', 'slug' => 'GENERIC', 'status' => 'active'],
            ['name' => 'Bosch',   'slug' => 'BOSCH',   'status' => 'active'],
        ];

        foreach ($brands as $b) {
            Brand::firstOrCreate(['slug' => $b['slug']], $b);
        }
    }
}
