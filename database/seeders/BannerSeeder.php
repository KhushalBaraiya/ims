<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['title' => 'Summer Sale',      'sub_title' => 'Up to 50% off',       'btn_text' => 'Shop Now',  'image' => '1.jpg',  'offer_discountLabel' => '50% OFF'],
            ['title' => 'New Arrivals',     'sub_title' => 'Check latest trends',  'btn_text' => 'Explore',   'image' => '2.webp', 'offer_discountLabel' => 'NEW'],
            ['title' => 'Electronics Deal', 'sub_title' => 'Best gadgets on sale', 'btn_text' => 'Buy Now',   'image' => '3.png',  'offer_discountLabel' => '30% OFF'],
            ['title' => 'Fashion Week',     'sub_title' => 'Trendy styles await',  'btn_text' => 'View All',  'image' => '4.webp', 'offer_discountLabel' => '20% OFF'],
            ['title' => 'Clearance Sale',   'sub_title' => 'Limited time offer',   'btn_text' => 'Grab Deal', 'image' => '5.jpg',  'offer_discountLabel' => '70% OFF'],
        ];

        foreach ($banners as $banner) {
            DB::table('banners')->insert([
                'title'               => $banner['title'],
                'sub_title'           => $banner['sub_title'],
                'btn_text'            => $banner['btn_text'],
                'image'               => $banner['image'],
                'offer_discountLabel' => $banner['offer_discountLabel'],
                'status'              => 'active',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }
}
