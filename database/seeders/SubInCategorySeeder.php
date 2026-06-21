<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubInCategorySeeder extends Seeder
{
    public function run(): void
    {
        // sub_id: 1=T-Shirts, 2=Shirts, 3=Jeans(M), 4=Ethnic(M)
        //         5=Tops, 6=Sarees, 7=Kurtis, 8=Jeans(W)
        //         9=Boys, 10=Girls, 11=Men Shoes, 12=Women Shoes
        $items = [
            ['category_id' => 1, 'subcategory_id' => 1,  'name' => 'Round Neck T-Shirts'],
            ['category_id' => 1, 'subcategory_id' => 1,  'name' => 'Polo T-Shirts'],
            ['category_id' => 1, 'subcategory_id' => 1,  'name' => 'Graphic T-Shirts'],
            ['category_id' => 1, 'subcategory_id' => 2,  'name' => 'Casual Shirts'],
            ['category_id' => 1, 'subcategory_id' => 2,  'name' => 'Formal Shirts'],
            ['category_id' => 1, 'subcategory_id' => 3,  'name' => 'Slim Fit Jeans'],
            ['category_id' => 1, 'subcategory_id' => 3,  'name' => 'Cargo Trousers'],
            ['category_id' => 1, 'subcategory_id' => 4,  'name' => 'Kurta Sets'],
            ['category_id' => 2, 'subcategory_id' => 5,  'name' => 'Crop Tops'],
            ['category_id' => 2, 'subcategory_id' => 5,  'name' => 'Printed Tops'],
            ['category_id' => 2, 'subcategory_id' => 6,  'name' => 'Silk Sarees'],
            ['category_id' => 2, 'subcategory_id' => 7,  'name' => 'Cotton Kurtis'],
            ['category_id' => 2, 'subcategory_id' => 8,  'name' => 'High Waist Jeans'],
            ['category_id' => 3, 'subcategory_id' => 9,  'name' => 'Boys T-Shirts'],
            ['category_id' => 3, 'subcategory_id' => 10, 'name' => 'Girls Frocks'],
            ['category_id' => 4, 'subcategory_id' => 11, 'name' => 'Sneakers'],
            ['category_id' => 4, 'subcategory_id' => 12, 'name' => 'Heels & Flats'],
        ];

        foreach ($items as $item) {
            DB::table('sub_in_categories')->insert([
                'category_id'    => $item['category_id'],
                'subcategory_id' => $item['subcategory_id'],
                'name'           => $item['name'],
                'status'         => 'active',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
