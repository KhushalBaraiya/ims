<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'Rajesh Electronics',
                'company_name'   => 'Rajesh Electronics Pvt. Ltd.',
                'contact_person' => 'Rajesh Kumar',
                'phone'          => '+91 98200 11111',
                'email'          => 'rajesh@rajeshelectronics.com',
                'status'         => 'active',
                'address'        => 'Shop 12, Electronics Market, Lamington Road',
                'city'           => 'Mumbai',
                'state'          => 'Maharashtra',
                'country'        => 'India',
                'pincode'        => '400004',
                'notes'          => 'Accessories and general electronics supplier',
            ],
            [
                'name'           => 'Tech Parts India',
                'company_name'   => 'Tech Parts India Traders',
                'contact_person' => 'Anil Sharma',
                'phone'          => '+91 98100 22222',
                'email'          => 'info@techpartsindia.com',
                'status'         => 'active',
                'address'        => '45, Nehru Place Electronics Market',
                'city'           => 'Delhi',
                'state'          => 'Delhi',
                'country'        => 'India',
                'pincode'        => '110019',
                'notes'          => 'Bulk electronics parts supplier based in Delhi',
            ],
            [
                'name'           => 'Mobile Spare Hub',
                'company_name'   => 'Mobile Spare Hub LLP',
                'contact_person' => 'Dinesh Patel',
                'phone'          => '+91 94260 33333',
                'email'          => 'sales@mobilesparehub.com',
                'status'         => 'active',
                'address'        => 'Ring Road, Udhna',
                'city'           => 'Surat',
                'state'          => 'Gujarat',
                'country'        => 'India',
                'pincode'        => '394210',
                'notes'          => 'Specialises in mobile spare parts and displays',
            ],
            [
                'name'           => 'Laptop Parts World',
                'company_name'   => 'Laptop Parts World Enterprises',
                'contact_person' => 'Hardik Mehta',
                'phone'          => '+91 98790 44444',
                'email'          => 'contact@laptoppartsworld.com',
                'status'         => 'active',
                'address'        => 'CG Road, Navrangpura',
                'city'           => 'Ahmedabad',
                'state'          => 'Gujarat',
                'country'        => 'India',
                'pincode'        => '380009',
                'notes'          => 'Laptop motherboards, displays and batteries',
            ],
            [
                'name'           => 'Global Tech Supplier',
                'company_name'   => 'Global Tech Supplier Co.',
                'contact_person' => 'Suresh Nair',
                'phone'          => '+91 98440 55555',
                'email'          => 'global@globaltech.com',
                'status'         => 'active',
                'address'        => 'SP Road, Chickpet',
                'city'           => 'Bangalore',
                'state'          => 'Karnataka',
                'country'        => 'India',
                'pincode'        => '560002',
                'notes'          => 'Computer hardware and components distributor',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}
