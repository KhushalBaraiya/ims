<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        // category_id: 1=Men's, 2=Women's, 3=Kids, 4=Footwear, 5=Accessories
        $subCategories = [
            ['category_id' => 1, 'name' => 'T-Shirts & Polos'],
            ['category_id' => 1, 'name' => 'Shirts'],
            ['category_id' => 1, 'name' => 'Jeans & Trousers'],
            ['category_id' => 1, 'name' => 'Ethnic Wear'],
            ['category_id' => 2, 'name' => 'Tops & Blouses'],
            ['category_id' => 2, 'name' => 'Sarees & Lehengas'],
            ['category_id' => 2, 'name' => 'Kurtis & Suits'],
            ['category_id' => 2, 'name' => 'Jeans & Palazzos'],
            ['category_id' => 3, 'name' => 'Boys Clothing'],
            ['category_id' => 3, 'name' => 'Girls Clothing'],
            ['category_id' => 4, 'name' => 'Men Shoes'],
            ['category_id' => 4, 'name' => 'Women Shoes'],
            ['category_id' => 5, 'name' => 'Belts & Wallets'],
            ['category_id' => 5, 'name' => 'Scarves & Stoles'],
        ];

        foreach ($subCategories as $sub) {
            DB::table('sub_categories')->insert([
                'category_id' => $sub['category_id'],
                'name'        => $sub['name'],
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
