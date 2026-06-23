<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name'            => 'Amit Patel',
                'phone'           => '+91 94260 10001',
                'alt_phone'       => null,
                'email'           => 'amit@gmail.com',
                'gst_number'      => null,
                'address'         => 'Ring Road, Surat',
                'city'            => 'Surat',
                'state'           => 'Gujarat',
                'country'         => 'India',
                'pincode'         => '395001',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Rahul Shah',
                'phone'           => '+91 98790 10002',
                'alt_phone'       => null,
                'email'           => 'rahul@gmail.com',
                'gst_number'      => null,
                'address'         => 'CG Road, Ahmedabad',
                'city'            => 'Ahmedabad',
                'state'           => 'Gujarat',
                'country'         => 'India',
                'pincode'         => '380006',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Priya Mehta',
                'phone'           => '+91 98200 10003',
                'alt_phone'       => null,
                'email'           => 'priya@gmail.com',
                'gst_number'      => null,
                'address'         => 'Bandra West, Mumbai',
                'city'            => 'Mumbai',
                'state'           => 'Maharashtra',
                'country'         => 'India',
                'pincode'         => '400050',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Vikram Desai',
                'phone'           => '+91 98980 10004',
                'alt_phone'       => null,
                'email'           => 'vikram@gmail.com',
                'gst_number'      => null,
                'address'         => 'Alkapuri, Vadodara',
                'city'            => 'Vadodara',
                'state'           => 'Gujarat',
                'country'         => 'India',
                'pincode'         => '390007',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Neha Joshi',
                'phone'           => '+91 94270 10005',
                'alt_phone'       => null,
                'email'           => 'neha@gmail.com',
                'gst_number'      => null,
                'address'         => 'Kalavad Road, Rajkot',
                'city'            => 'Rajkot',
                'state'           => 'Gujarat',
                'country'         => 'India',
                'pincode'         => '360001',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Suresh Patel',
                'phone'           => '+91 94260 10006',
                'alt_phone'       => null,
                'email'           => 'suresh@gmail.com',
                'gst_number'      => null,
                'address'         => 'Adajan, Surat',
                'city'            => 'Surat',
                'state'           => 'Gujarat',
                'country'         => 'India',
                'pincode'         => '395009',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Kavita Shah',
                'phone'           => '+91 98790 10007',
                'alt_phone'       => null,
                'email'           => 'kavita@gmail.com',
                'gst_number'      => null,
                'address'         => 'Satellite, Ahmedabad',
                'city'            => 'Ahmedabad',
                'state'           => 'Gujarat',
                'country'         => 'India',
                'pincode'         => '380015',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
            [
                'name'            => 'Ravi Kumar',
                'phone'           => '+91 98200 10008',
                'alt_phone'       => null,
                'email'           => 'ravi@gmail.com',
                'gst_number'      => null,
                'address'         => 'Andheri East, Mumbai',
                'city'            => 'Mumbai',
                'state'           => 'Maharashtra',
                'country'         => 'India',
                'pincode'         => '400069',
                'opening_balance' => 0,
                'status'          => 'active',
                'notes'           => null,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
