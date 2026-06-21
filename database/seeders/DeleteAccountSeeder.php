<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteAccountSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            'I no longer need this account',
            'I have a duplicate account',
            'Privacy concerns',
            'Too many emails/notifications',
            'Switching to a different service',
        ];

        foreach ($reasons as $reason) {
            DB::table('delete_accounts')->insert([
                'title'      => $reason,
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
