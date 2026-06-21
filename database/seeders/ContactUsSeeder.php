<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactUsSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Raj Patel',
                'email' => 'raj@example.com',
                'subject' => 'Order Issue',
                'message' => 'My order has not arrived yet.',
                'status' => 'active'
            ],
            [
                'name' => 'Priya Shah',
                'email' => 'priya@example.com',
                'subject' => 'Return Request',
                'message' => 'I want to return my product.',
                'status' => 'inactive'
            ],
            [
                'name' => 'Amit Kumar',
                'email' => 'amit@example.com',
                'subject' => 'Payment Problem',
                'message' => 'Payment was deducted but order not placed.',
                'status' => 'active'
            ],
            [
                'name' => 'Neha Joshi',
                'email' => 'neha@example.com',
                'subject' => 'Product Query',
                'message' => 'Is this product available in blue color?',
                'status' => 'active'
            ],
            [
                'name' => 'Suresh Mehta',
                'email' => 'suresh@example.com',
                'subject' => 'Discount Coupon',
                'message' => 'My coupon code is not working.',
                'status' => 'inactive'
            ],
        ];

        foreach ($contacts as $contact) {
            DB::table('contact_us')->insert([
                'name'       => $contact['name'],
                'email'      => $contact['email'],
                'subject'    => $contact['subject'],
                'message'    => $contact['message'],
                'status'     => $contact['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
