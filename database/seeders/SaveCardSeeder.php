<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaveCardSeeder extends Seeder
{
    public function run(): void
    {
        $cards = [
            ['user_id' => 2, 'card_holder_name' => 'Rahul Sharma', 'last_four_digits' => '4242', 'expiry_month' => '12', 'expiry_year' => '2027', 'card_brand' => 'Visa',       'gateway_token' => 'tok_visa_001',  'status' => 'active'],
            ['user_id' => 2, 'card_holder_name' => 'Rahul Sharma', 'last_four_digits' => '5555', 'expiry_month' => '08', 'expiry_year' => '2026', 'card_brand' => 'Mastercard', 'gateway_token' => 'tok_mc_002',    'status' => 'active'],
            ['user_id' => 3, 'card_holder_name' => 'Priya Patel',  'last_four_digits' => '1234', 'expiry_month' => '03', 'expiry_year' => '2028', 'card_brand' => 'RuPay',      'gateway_token' => 'tok_rupay_003', 'status' => 'active'],
            ['user_id' => 4, 'card_holder_name' => 'Amit Verma',   'last_four_digits' => '9876', 'expiry_month' => '11', 'expiry_year' => '2025', 'card_brand' => 'Visa',       'gateway_token' => 'tok_visa_004',  'status' => 'inactive'],
            ['user_id' => 3, 'card_holder_name' => 'Priya Patel',  'last_four_digits' => '6789', 'expiry_month' => '06', 'expiry_year' => '2027', 'card_brand' => 'Mastercard', 'gateway_token' => 'tok_mc_005',    'status' => 'active'],
        ];

        foreach ($cards as $card) {
            DB::table('save_cards')->insert([
                'user_id'          => $card['user_id'],
                'card_holder_name' => $card['card_holder_name'],
                'last_four_digits' => $card['last_four_digits'],
                'expiry_month'     => $card['expiry_month'],
                'expiry_year'      => $card['expiry_year'],
                'card_brand'       => $card['card_brand'],
                'gateway_token'    => $card['gateway_token'],
                'status'           => $card['status'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
