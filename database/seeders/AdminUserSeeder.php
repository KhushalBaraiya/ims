<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user if not exists
        $exists = DB::table('users')->where('email', 'admin@example.com')->exists();

        if (!$exists) {
            DB::table('users')->insert([
                'name'       => 'JIGI Admin',
                'email'      => 'jigi@gmail.com',
                'password'   => Hash::make('admin@123'),
                'gender'     => 'male',
                'phone'      => '9876543210',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Admin user seeded successfully.');
        $this->command->info('Email   : jigi@gmail.com');
        $this->command->info('Password: admin@123');
    }
}
