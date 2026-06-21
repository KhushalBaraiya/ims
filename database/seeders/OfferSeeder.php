<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $offers = [
            ['offer_name' => 'Summer Blast',   'offer_code' => 'SUMMER25',  'offer_type' => 'percentage', 'discount_value' => '25',  'offer_value' => '25'],
            ['offer_name' => 'Festive Sale',   'offer_code' => 'FESTIVE30', 'offer_type' => 'percentage', 'discount_value' => '30',  'offer_value' => '30'],
            ['offer_name' => 'Flash Deal',     'offer_code' => 'FLASH100',  'offer_type' => 'fixed',      'discount_value' => '100', 'offer_value' => '100'],
            ['offer_name' => 'Weekend Offer',  'offer_code' => 'WEEKEND15', 'offer_type' => 'percentage', 'discount_value' => '15',  'offer_value' => '15'],
            ['offer_name' => 'New User Offer', 'offer_code' => 'NEWUSER50', 'offer_type' => 'fixed',      'discount_value' => '50',  'offer_value' => '50'],
        ];

        foreach ($offers as $offer) {
            DB::table('offers')->insert([
                'offer_name'          => $offer['offer_name'],
                'offer_code'          => $offer['offer_code'],
                'offer_type'          => $offer['offer_type'],
                'discount_value'      => $offer['discount_value'],
                'offer_value'         => $offer['offer_value'],
                'start_date'          => now()->toDateString(),
                'end_date'            => now()->addMonths(2)->toDateString(),
                'is_active'           => '1',
                'current_usage_count' => '0',
                'status'              => 'active',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }
}
