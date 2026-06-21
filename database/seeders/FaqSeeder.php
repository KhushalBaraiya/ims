<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'How do I track my order?',         'answer' => 'You can track your order from the My Orders section in your account.'],
            ['question' => 'What is the return policy?',       'answer' => 'We accept returns within 7 days of delivery.'],
            ['question' => 'How can I cancel my order?',       'answer' => 'Orders can be cancelled within 24 hours of placing them.'],
            ['question' => 'Do you offer free shipping?',      'answer' => 'Yes, free shipping is available on orders above Rs. 999.'],
            ['question' => 'How do I apply a coupon code?',    'answer' => 'Enter the coupon code at checkout in the promo code field.'],
        ];

        foreach ($faqs as $faq) {
            DB::table('faqs')->insert([
                'question'   => $faq['question'],
                'answer'     => $faq['answer'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
