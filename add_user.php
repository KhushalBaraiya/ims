<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check roles
$roles = \Spatie\Permission\Models\Role::all(['id','name']);
echo "=== Roles ===" . PHP_EOL;
foreach ($roles as $r) {
    echo "ID:{$r->id} | {$r->name}" . PHP_EOL;
}

// Check users table columns
$cols = \Illuminate\Support\Facades\Schema::getColumnListing('users');
echo PHP_EOL . "=== Users Table Columns ===" . PHP_EOL;
echo implode(', ', $cols) . PHP_EOL;
