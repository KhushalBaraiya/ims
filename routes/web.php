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
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RazorpayController;
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
    Route::delete('roles/bulk-delete', [RoleController::class, 'bulkDestroy'])->name('roles.bulk-destroy');
    Route::resource('roles', RoleController::class);

    // Permission Management CRUD Resource Route
    Route::delete('permissions/bulk-delete', [PermissionController::class, 'bulkDestroy'])->name('permissions.bulk-destroy');
    Route::resource('permissions', PermissionController::class);

    // User Management CRUD Resource Route
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::resource('users', UserController::class);

    // Inventory CRUD Resource Routes
    Route::resource('brands', BrandController::class);
    Route::patch('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    Route::delete('brands/bulk-delete', [BrandController::class, 'bulkDestroy'])->name('brands.bulk-destroy');
    Route::resource('main-categories', MainCategoryController::class);
    Route::patch('main-categories/{mainCategory}/toggle-status', [MainCategoryController::class, 'toggleStatus'])->name('main-categories.toggle-status');
    Route::delete('main-categories/bulk-delete', [MainCategoryController::class, 'bulkDestroy'])->name('main-categories.bulk-destroy');
    Route::resource('sub-categories', SubCategoryController::class);
    Route::patch('sub-categories/{subCategory}/toggle-status', [SubCategoryController::class, 'toggleStatus'])->name('sub-categories.toggle-status');
    Route::delete('sub-categories/bulk-delete', [SubCategoryController::class, 'bulkDestroy'])->name('sub-categories.bulk-destroy');
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    Route::delete('suppliers/bulk-delete', [SupplierController::class, 'bulkDestroy'])->name('suppliers.bulk-destroy');
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('customers/bulk-delete', [CustomerController::class, 'bulkDestroy'])->name('customers.bulk-destroy');
    Route::get('/stocks/low-stock', [StockController::class, 'lowStock'])->name('stocks.low_stock');
    Route::get('/stocks-history', [StockController::class, 'history'])->name('stocks.history');
    Route::resource('stocks', StockController::class);
    Route::get('/stocks-adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    Route::post('/stocks-adjust', [StockController::class, 'store'])->name('stocks.store_adjustment');
    Route::get('/stocks-adjust/{voucher_no}/show', [StockController::class, 'show_adjustment'])->name('stocks.show_adjustment');
    Route::get('/stocks-adjust/{voucher_no}/edit', [StockController::class, 'edit_adjustment'])->name('stocks.edit_adjustment');
    Route::put('/stocks-adjust/{voucher_no}', [StockController::class, 'update_adjustment'])->name('stocks.update_adjustment');
    Route::delete('/stocks-adjust/{voucher_no}', [StockController::class, 'destroy_adjustment'])->name('stocks.destroy_adjustment');
    // Currencies Routes (with status toggle, set-default and switcher)
    Route::post('/currencies/switch', [CurrencyController::class, 'switchCurrency'])->name('currencies.switch');
    Route::post('/currencies/{currency}/toggle-status', [CurrencyController::class, 'toggleStatus'])->name('currencies.toggle-status');
    Route::post('/currencies/{currency}/set-default', [CurrencyController::class, 'setDefault'])->name('currencies.set-default');
    Route::resource('currencies', CurrencyController::class);

    // Products Routes
    Route::get('/products/generate-sku', [ProductController::class, 'generateSkuAjax'])->name('products.generate-sku');
    Route::get('/products/gallery', [ProductController::class, 'gallery'])->name('products.gallery');
    Route::get('/products/by-category', [ProductController::class, 'byCategory'])->name('products.by-category');
    Route::get('/products/search', [SaleController::class, 'searchProducts'])->name('products.search');
    Route::get('/products/{product}/copy', [ProductController::class, 'copy'])->name('products.copy');
    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
    Route::resource('products', ProductController::class);

    // Sales Routes
    Route::get('/sales/generate-invoice-no', [SaleController::class, 'generateInvoiceNoAjax'])->name('sales.generate-invoice-no');
    Route::get('/sales/{sale}/print', [SaleController::class, 'printInvoice'])->name('sales.print');
    Route::delete('/sales/bulk-delete', [SaleController::class, 'bulkDestroy'])->name('sales.bulk-destroy');
    Route::post('/sales/{sale}/payment', [SaleController::class, 'updatePayment'])->name('sales.update-payment');
    Route::resource('sales', SaleController::class);

    // Sales Return Routes
    Route::get('/sales/{sale}/return-data', [SaleReturnController::class, 'getSaleReturnData'])->name('sales.return-data');
    Route::get('/sale-returns/{sale_return}/print', [SaleReturnController::class, 'printReturn'])->name('sale-returns.print');
    Route::delete('/sale-returns/bulk-delete', [SaleReturnController::class, 'bulkDestroy'])->name('sale-returns.bulk-destroy');
    Route::resource('sale-returns', SaleReturnController::class);
    // Purchase Management Routes
    Route::get('/purchases/generate-purchase-no', [PurchaseController::class, 'generatePurchaseNoAjax'])->name('purchases.generate-purchase-no');
    Route::get('/purchases/search-products', [PurchaseController::class, 'searchProducts'])->name('purchases.search-products');
    Route::get('/purchases/generate-no', [PurchaseController::class, 'generateNoAjax'])->name('purchases.generate-no');
    Route::get('/purchases/{purchase}/print', [PurchaseController::class, 'printInvoice'])->name('purchases.print');
    Route::post('/purchases/{purchase}/payment', [PurchaseController::class, 'updatePayment'])->name('purchases.update-payment');
    Route::delete('/purchases/bulk-delete', [PurchaseController::class, 'bulkDestroy'])->name('purchases.bulk-destroy');
    Route::resource('purchases', PurchaseController::class);

    // Razorpay Routes
    Route::post('/razorpay/create-order', [RazorpayController::class, 'createOrder'])->name('razorpay.create-order');
    Route::post('/razorpay/verify-and-store', [RazorpayController::class, 'verifyAndStore'])->name('razorpay.verify-and-store');

    // Purchase Return Routes
    Route::get('/purchase-returns/search-products', [PurchaseReturnController::class, 'searchProducts'])->name('purchase-returns.search-products');
    Route::get('/purchases/{purchase}/return-data', [PurchaseReturnController::class, 'getPurchaseReturnData'])->name('purchases.return-data');
    Route::get('/purchase-returns/{purchase_return}/print', [PurchaseReturnController::class, 'printReturn'])->name('purchase-returns.print');
    Route::delete('/purchase-returns/bulk-delete', [PurchaseReturnController::class, 'bulkDestroy'])->name('purchase-returns.bulk-destroy');
    Route::resource('purchase-returns', PurchaseReturnController::class);

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Activity Logs Routes
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Report Export Routes (CSV — no external package needed)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/top-selling', [ReportController::class, 'topSelling'])->name('top-selling');
        Route::get('/stock-alert', [ReportController::class, 'stockAlert'])->name('stock-alert');
        // CSV exports
        Route::get('/sales/export', [ReportController::class, 'exportSales'])->name('sales.export');
        Route::get('/purchases/export', [ReportController::class, 'exportPurchases'])->name('purchases.export');
        Route::get('/stock-alert/export', [ReportController::class, 'exportStockAlert'])->name('stock-alert.export');
    });
});
