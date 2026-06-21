<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            [
                'user_id'             => 2,
                'first_name'          => 'John',
                'last_name'           => 'Doe',
                'mobile_country_code' => '+91',
                'mobile_no'           => '9876543211',
                'email'               => 'john@example.com',
                'house_no'            => 'A-101',
                'landmark'            => 'Near City Mall',
                'locality_area'       => 'Navrangpura',
                'pincode'             => '380009',
                'city'                => 'Ahmedabad',
                'state'               => 'Gujarat',
                'country'             => 'India',
                'address_type'        => 'home',
                'status'              => 'active',
            ],
            [
                'user_id'             => 2,
                'first_name'          => 'John',
                'last_name'           => 'Doe',
                'mobile_country_code' => '+91',
                'mobile_no'           => '9876543211',
                'email'               => 'john@example.com',
                'house_no'            => 'B-202',
                'landmark'            => 'Opposite Bus Stand',
                'locality_area'       => 'Satellite',
                'pincode'             => '380015',
                'city'                => 'Ahmedabad',
                'state'               => 'Gujarat',
                'country'             => 'India',
                'address_type'        => 'work',
                'status'              => 'active',
            ],
            [
                'user_id'             => 3,
                'first_name'          => 'Jane',
                'last_name'           => 'Smith',
                'mobile_country_code' => '+91',
                'mobile_no'           => '9876543212',
                'email'               => 'jane@example.com',
                'house_no'            => 'C-303',
                'landmark'            => 'Near Railway Station',
                'locality_area'       => 'Maninagar',
                'pincode'             => '380008',
                'city'                => 'Ahmedabad',
                'state'               => 'Gujarat',
                'country'             => 'India',
                'address_type'        => 'home',
                'status'              => 'active',
            ],
            [
                'user_id'             => 4,
                'first_name'          => 'Mike',
                'last_name'           => 'Manager',
                'mobile_country_code' => '+91',
                'mobile_no'           => '9876543213',
                'email'               => 'manager@example.com',
                'house_no'            => 'D-404',
                'landmark'            => 'Near Park',
                'locality_area'       => 'Bopal',
                'pincode'             => '380058',
                'city'                => 'Ahmedabad',
                'state'               => 'Gujarat',
                'country'             => 'India',
                'address_type'        => 'other',
                'status'              => 'inactive',
            ],
        ];

        foreach ($addresses as $address) {
            DB::table('user_addresses')->insert(array_merge($address, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
