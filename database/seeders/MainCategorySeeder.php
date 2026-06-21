<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Men\'s Clothing'],
            ['name' => 'Women\'s Clothing'],
            ['name' => 'Kids Clothing'],
            ['name' => 'Footwear'],
            ['name' => 'Accessories'],
        ];

        foreach ($categories as $cat) {
            DB::table('main_categories')->insert([
                'name'       => $cat['name'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
