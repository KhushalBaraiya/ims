<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'exchange_rate' => 1.0000, 'is_default' => true,  'status' => 'active'],
            ['name' => 'US Dollar',    'code' => 'USD', 'symbol' => '$', 'exchange_rate' => 83.500, 'is_default' => false, 'status' => 'active'],
            ['name' => 'Euro',         'code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 90.200, 'is_default' => false, 'status' => 'active'],
        ];

        foreach ($currencies as $c) {
            Currency::firstOrCreate(['code' => $c['code']], $c);
        }
    }
}
