<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubFaqSeeder extends Seeder
{
    public function run(): void
    {
        $subFaqs = [
            ['faq_id' => 1, 'question' => 'Where is the tracking link?',         'answer' => 'Tracking link is sent to your registered email.'],
            ['faq_id' => 1, 'question' => 'Can I track without an account?',     'answer' => 'Yes, use the order ID and email on the tracking page.'],
            ['faq_id' => 2, 'question' => 'How do I initiate a return?',         'answer' => 'Go to My Orders and click Return on the item.'],
            ['faq_id' => 3, 'question' => 'Can I cancel after dispatch?',        'answer' => 'No, cancellation is not possible after dispatch.'],
            ['faq_id' => 4, 'question' => 'Is free shipping available on COD?',  'answer' => 'Yes, free shipping applies to COD orders too.'],
        ];

        foreach ($subFaqs as $item) {
            DB::table('sub_faqs')->insert([
                'faq_id'     => $item['faq_id'],
                'question'   => $item['question'],
                'answer'     => $item['answer'],
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
