<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/database-structure', function () {

    $database = DB::getDatabaseName();

    $tables = DB::select("SHOW TABLES");

    $tableKey = 'Tables_in_' . $database;

    $output = "Database : {$database}\n\n";

    foreach ($tables as $table) {

        $tableName = $table->$tableKey;

        // Laravel internal tables skip
        if (in_array($tableName, [
            'migrations',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'password_reset_tokens',
            'sessions'
        ])) {
            continue;
        }

        $output .= "{$tableName}\n";

        $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");

        foreach ($columns as $column) {
            $output .= "    - {$column->Field} | {$column->Type}\n";
        }

        $output .= "\n";
    }

    return response($output)
        ->header('Content-Type', 'text/plain');
});