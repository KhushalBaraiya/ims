<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // All permissions definition
        $permissions = [
            'dashboard.view',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'brands.view',
            'brands.create',
            'brands.update',
            'brands.delete',

            'main_categories.view',
            'main_categories.create',
            'main_categories.update',
            'main_categories.delete',

            'sub_categories.view',
            'sub_categories.create',
            'sub_categories.update',
            'sub_categories.delete',

            'suppliers.view',
            'suppliers.create',
            'suppliers.update',
            'suppliers.delete',

            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',

            'units.view',
            'units.create',
            'units.update',
            'units.delete',

            'currencies.view',
            'currencies.create',
            'currencies.update',
            'currencies.delete',

            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            'purchases.view',
            'purchases.create',
            'purchases.update',
            'purchases.delete',

            'purchase_returns.view',
            'purchase_returns.create',
            'purchase_returns.update',
            'purchase_returns.delete',

            'sales.view',
            'sales.create',
            'sales.update',
            'sales.delete',

            'sale_returns.view',
            'sale_returns.create',
            'sale_returns.update',
            'sale_returns.delete',

            'stocks.view',
            'stocks.create',
            'stocks.update',
            'stocks.delete',

            'reports.view',

            'settings.view',
            'settings.update',

            'activity_logs.view',
        ];

        // Create permissions
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);

        // Super Admin gets all permissions
        $superAdminRole->syncPermissions($permissions);

        // Define Manager permissions (wildcards and specific ones)
        $managerPermissions = array_filter($permissions, function ($permission) {
            if (in_array($permission, ['dashboard.view', 'reports.view', 'settings.view', 'activity_logs.view'])) {
                return true;
            }

            $wildcardPrefixes = [
                'brands.',
                'main_categories.',
                'sub_categories.',
                'suppliers.',
                'customers.',
                'units.',
                'currencies.',
                'products.',
                'purchases.',
                'purchase_returns.',
                'sales.',
                'sale_returns.',
                'stocks.',
            ];

            foreach ($wildcardPrefixes as $prefix) {
                if (str_starts_with($permission, $prefix)) {
                    return true;
                }
            }

            return false;
        });

        $managerRole->syncPermissions($managerPermissions);

        // Define Staff permissions
        $staffPermissions = [
            'dashboard.view',
            'products.view',
            'stocks.view',
            'sales.view',
            'sales.create',
            'sale_returns.view',
            'sale_returns.create',
            'reports.view',
        ];

        $staffRole->syncPermissions($staffPermissions);

        // Create Default Super Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
            ]
        );

        $admin->assignRole($superAdminRole);
    }
}
