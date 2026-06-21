<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['user_id' => 2, 'product_id' => 1, 'title' => 'Great Phone!',       'comment' => 'Amazing camera and battery life.',     'rating' => 5, 'images' => '[]'],
            ['user_id' => 3, 'product_id' => 2, 'title' => 'Worth the price',    'comment' => 'iPhone 15 is smooth and fast.',         'rating' => 4, 'images' => '[]'],
            ['user_id' => 2, 'product_id' => 3, 'title' => 'Comfortable shoes',  'comment' => 'Very comfortable for daily running.',   'rating' => 5, 'images' => '[]'],
            ['user_id' => 4, 'product_id' => 4, 'title' => 'Good quality',       'comment' => 'Nice fabric and fits well.',            'rating' => 4, 'images' => '[]'],
            ['user_id' => 3, 'product_id' => 4, 'title' => 'Excellent sound',    'comment' => 'Best noise cancellation headphones.',   'rating' => 5, 'images' => '[]'],
        ];

        foreach ($reviews as $review) {
            DB::table('reviews')->insert([
                'user_id'    => $review['user_id'],
                'product_id' => $review['product_id'],
                'title'      => $review['title'],
                'comment'    => $review['comment'],
                'rating'     => $review['rating'],
                'images'     => $review['images'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
