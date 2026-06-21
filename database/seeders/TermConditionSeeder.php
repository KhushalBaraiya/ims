<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermConditionSeeder extends Seeder
{
    public function run(): void
    {
        $terms = [
            ['title' => 'General Terms',         'content' => 'By using our website, you agree to these terms and conditions.'],
            ['title' => 'Privacy Policy',         'content' => 'We collect and use your data as described in our privacy policy.'],
            ['title' => 'Return Policy',          'content' => 'Products can be returned within 7 days of delivery in original condition.'],
            ['title' => 'Shipping Policy',        'content' => 'Orders are shipped within 2-3 business days after confirmation.'],
            ['title' => 'Payment Terms',          'content' => 'We accept all major credit/debit cards, UPI, and net banking.'],
        ];

        foreach ($terms as $term) {
            DB::table('term_conditions')->insert([
                'title'      => $term['title'],
                'content'    => $term['content'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
