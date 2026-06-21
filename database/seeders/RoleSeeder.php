<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'dashboard',
            'users',
            'roles',
            'brands',
            'main_categories',
            'sub_categories',
            'suppliers',
            'customers',
            'units',
            'currencies',
            'products',
            'purchases',
            'purchase_returns',
            'sales',
            'sale_returns',
            'stocks',
            'reports',
            'settings',
        ];

        foreach ($modules as $module) {
            foreach (['view', 'create', 'update', 'delete'] as $action) {
                Permission::create([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        $superAdmin = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $manager = Role::create([
            'name' => 'manager',
            'guard_name' => 'web',
        ]);

        $staff = Role::create([
            'name' => 'staff',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(Permission::all());
    
        $user = User::create([
            'email' => 'admin@gmail.com',
            'name' => 'Super Admin',
            'password' => Hash::make('123456'),
            'status' => 'active',
        ]);

        $user->assignRole($superAdmin);
    }
}