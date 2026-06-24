<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Language Switcher (public - no auth required)
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

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

    // Role Management CRUD Resource Route
    Route::resource('roles', RoleController::class);

    // User Management CRUD Resource Route
    Route::resource('users', UserController::class);

    // Inventory CRUD Resource Routes
    Route::resource('brands', BrandController::class);
    Route::resource('main-categories', MainCategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('stocks', StockController::class);
    Route::get('/stocks-adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    Route::get('/stocks-history', [StockController::class, 'history'])->name('stocks.history');

    // Currencies Routes (with status toggle and switcher)
    Route::post('/currencies/switch', [CurrencyController::class, 'switchCurrency'])->name('currencies.switch');
    Route::post('/currencies/{currency}/toggle-status', [CurrencyController::class, 'toggleStatus'])->name('currencies.toggle-status');
    Route::resource('currencies', CurrencyController::class);

    // Products Routes
    Route::get('/products/gallery', [ProductController::class, 'gallery'])->name('products.gallery');
    Route::get('/products/search', [SaleController::class, 'searchProducts'])->name('products.search');
    Route::get('/products/{product}/copy', [ProductController::class, 'copy'])->name('products.copy');
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

    // Units Routes
    Route::resource('units', UnitController::class);

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Activity Logs Routes
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',             [ReportController::class, 'index'])      ->name('index');
        Route::get('/sales',        [ReportController::class, 'sales'])      ->name('sales');
        Route::get('/purchases',    [ReportController::class, 'purchases'])  ->name('purchases');
        Route::get('/profit-loss',  [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/top-selling',  [ReportController::class, 'topSelling'])->name('top-selling');
        Route::get('/stock-alert',  [ReportController::class, 'stockAlert'])->name('stock-alert');
    });
});
