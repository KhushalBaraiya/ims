<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->boot();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// 1. Check permissions.* in DB
$perms = Permission::where('name', 'like', 'permissions.%')->pluck('name');
echo "=== permissions.* in DB ===" . PHP_EOL;
foreach ($perms as $p) echo "  - $p" . PHP_EOL;

// 2. Check super_admin has them
$role = Role::where('name', 'super_admin')->first();
echo PHP_EOL . "=== super_admin role ===" . PHP_EOL;
if ($role) {
    foreach (['permissions.view','permissions.create','permissions.update','permissions.delete'] as $p) {
        echo "  $p: " . ($role->hasPermissionTo($p) ? 'YES' : 'NO') . PHP_EOL;
    }
} else {
    echo "  super_admin role NOT FOUND!" . PHP_EOL;
}

// 3. Total counts
echo PHP_EOL . "=== Counts ===" . PHP_EOL;
echo "  Total permissions: " . Permission::count() . PHP_EOL;
echo "  Total roles: " . Role::count() . PHP_EOL;

// 4. Check admin user
$admin = \App\Models\User::where('email', 'admin@gmail.com')->first();
if ($admin) {
    echo PHP_EOL . "=== Admin user roles ===" . PHP_EOL;
    foreach ($admin->roles as $r) echo "  - $r->name" . PHP_EOL;
    echo "  Can permissions.view: " . ($admin->can('permissions.view') ? 'YES' : 'NO') . PHP_EOL;
}

echo PHP_EOL . "=== DONE ===" . PHP_EOL;
