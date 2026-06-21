<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturnExchangePolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            ['product_id' => 1, 'title' => '7 days return policy applicable on this product.', 'status' => 'active'],
            ['product_id' => 2, 'title' => 'No return accepted. Exchange within 3 days only.', 'status' => 'active'],
            ['product_id' => 3, 'title' => '15 days easy return and exchange policy.',          'status' => 'active'],
            ['product_id' => 4, 'title' => 'Return not applicable for this product.',           'status' => 'inactive'],
        ];

        foreach ($policies as $policy) {
            DB::table('return_exchangepolicies')->insert(array_merge($policy, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
