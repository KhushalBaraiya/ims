<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'blog_category_id'  => 1,
                'title'             => 'Top 10 Fashion Trends of 2026',
                'short_description' => 'Explore the hottest fashion trends dominating 2026.',
                'content'           => 'Fashion in 2026 is all about bold colors, sustainable fabrics, and minimalist designs...',
                'author'            => 'Admin',
                'status'            => 'active',
                'image'             => '1.jpg',
            ],
            [
                'blog_category_id'  => 2,
                'title'             => 'Best Smartphones to Buy in 2026',
                'short_description' => 'A complete guide to the best smartphones available this year.',
                'content'           => 'The smartphone market in 2026 is packed with incredible options from top brands...',
                'author'            => 'Tech Team',
                'status'            => 'active',
                'image'             => '2.jpg',
            ],
            [
                'blog_category_id'  => 3,
                'title'             => 'How to Live a Healthier Lifestyle',
                'short_description' => 'Simple tips to improve your daily health and wellness.',
                'content'           => 'Living healthy does not have to be complicated. Start with small changes...',
                'author'            => 'Wellness Expert',
                'status'            => 'active',
                'image'             => '3.jpg',
            ],
            [
                'blog_category_id'  => 1,
                'title'             => 'Summer Collection 2026 Preview',
                'short_description' => 'Get a sneak peek at the upcoming summer collection.',
                'content'           => 'This summer is going to be vibrant with pastel shades and floral patterns...',
                'author'            => 'Admin',
                'status'            => 'inactive',
                'image'             => '5.jpg',
            ],
        ];

        foreach ($blogs as $blog) {
            DB::table('blogs')->insert([
                'blog_category_id'  => $blog['blog_category_id'],
                'title'             => $blog['title'],
                'slug'              => Str::slug($blog['title']),
                'short_description' => $blog['short_description'],
                'content'           => $blog['content'],
                'image'             => $blog['image'], // ✅ important
                'author'            => $blog['author'],
                'status'            => $blog['status'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}