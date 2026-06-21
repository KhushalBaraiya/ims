<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'John Doe',    'email' => 'john@example.com',    'role_id' => 2, 'gender' => 'Male',   'phone' => '9876543211', 'dob' => '1990-01-15', 'address' => '123 Main Street, City',   'otp' => '123456', 'google_id' => 'google_john_001', 'facebook_id' => 'facebook_john_001', 'image' => null, 'status' => 'active'],
            ['name' => 'Jane Smith',  'email' => 'jane@example.com',    'role_id' => 2, 'gender' => 'Female', 'phone' => '9876543212', 'dob' => '1992-05-20', 'address' => '456 Oak Avenue, Town',    'otp' => '654321', 'google_id' => 'google_jane_002', 'facebook_id' => 'facebook_jane_002', 'image' => null, 'status' => 'active'],
            ['name' => 'Mike Manager','email' => 'manager@example.com', 'role_id' => 3, 'gender' => 'Male',   'phone' => '9876543213', 'dob' => '1988-08-10', 'address' => '789 Pine Road, Village',  'otp' => '112233', 'google_id' => 'google_mike_003', 'facebook_id' => 'facebook_mike_003', 'image' => null, 'status' => 'active'],
            ['name' => 'Sara Vendor', 'email' => 'vendor@example.com',  'role_id' => 4, 'gender' => 'Female', 'phone' => '9876543214', 'dob' => '1995-03-25', 'address' => '321 Elm Street, Metro',   'otp' => '445566', 'google_id' => 'google_sara_004', 'facebook_id' => 'facebook_sara_004', 'image' => null, 'status' => 'inactive'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name'        => $user['name'],
                'email'       => $user['email'],
                'password'    => Hash::make('password123'),
                'role_id'     => $user['role_id'],
                'gender'      => $user['gender'],
                'phone'       => $user['phone'],
                'dob'         => $user['dob'],
                'address'     => $user['address'],
                'otp'         => $user['otp'],
                'google_id'   => $user['google_id'],
                'facebook_id' => $user['facebook_id'],
                'image'       => $user['image'],
                'status'      => $user['status'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
