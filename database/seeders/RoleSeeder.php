<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin', 'User', 'Manager', 'Vendor', 'Support'];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name'       => $role,
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
      