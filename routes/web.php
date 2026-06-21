<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // User Management CRUD Resource Route
    Route::resource('users', UserController::class);

    // Inventory CRUD Resource Routes
    Route::resource('brands', BrandController::class);
    Route::resource('main-categories', MainCategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('units', UnitController::class);
    
    // Currencies Routes (with status toggle)
    Route::post('/currencies/{currency}/toggle-status', [CurrencyController::class, 'toggleStatus'])->name('currencies.toggle-status');
    Route::resource('currencies', CurrencyController::class);
    
    // Products Routes
    Route::get('/products/search', [SaleController::class, 'searchProducts'])->name('products.search');
    Route::resource('products', ProductController::class);

    // Sales Routes
    Route::get('/sales/{sale}/print', [SaleController::class, 'printInvoice'])->name('sales.print');
    Route::resource('sales', SaleController::class);

    // Sales Return Routes
    Route::get('/sales/{sale}/return-data', [SaleReturnController::class, 'getSaleReturnData'])->name('sales.return-data');
    Route::get('/sale-returns/{sale_return}/print', [SaleReturnController::class, 'printReturn'])->name('sale-returns.print');
    Route::resource('sale-returns', SaleReturnController::class);
    // Purchase Management Routes
    Route::get('/purchases/search-products', [PurchaseController::class, 'searchProducts'])->name('purchases.search-products');
    Route::get('/purchases/{purchase}/print', [PurchaseController::class, 'printInvoice'])->name('purchases.print');
    Route::resource('purchases', PurchaseController::class);

    // Purchase Return Routes
    Route::get('/purchases/{purchase}/return-data', [PurchaseReturnController::class, 'getPurchaseReturnData'])->name('purchases.return-data');
    Route::get('/purchase-returns/{purchase_return}/print', [PurchaseReturnController::class, 'printReturn'])->name('purchase-returns.print');
    Route::resource('purchase-returns', PurchaseReturnController::class);
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