<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Demo;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Demo::create([
            'name'      => 'Jignasha',
            'email'     => 'jignasha@gmail.com',
            'password'  => Hash::make('123456'),
            'phone'     => '9876543210',
            'image'     => 'uploads/demo/user1.jpg',
            'address'   => 'Ahmedabad, Gujarat',
            'gender'    => 'female',
            'status'    => 'active',
        ]);

        Demo::create([
            'name'      => 'Rahul',
            'email'     => 'rahul@gmail.com',
            'password'  => Hash::make('123456'),
            'phone'     => '9999999999',
            'image'     => 'uploads/demo/user2.jpg',
            'address'   => 'Surat, Gujarat',
            'gender'    => 'male',
            'status'    => 'inactive',
        ]);
    }
}