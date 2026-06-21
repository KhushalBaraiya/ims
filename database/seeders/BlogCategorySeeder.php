<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fashion',     'description' => 'Latest fashion trends and tips.',       'status' => 'active'],
            ['name' => 'Technology',  'description' => 'Tech news and product reviews.',         'status' => 'active'],
            ['name' => 'Lifestyle',   'description' => 'Healthy living and lifestyle guides.',   'status' => 'active'],
            ['name' => 'Travel',      'description' => 'Travel guides and destination reviews.', 'status' => 'inactive'],
        ];

        foreach ($categories as $cat) {
            DB::table('blog_categories')->insert([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'status'      => $cat['status'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
