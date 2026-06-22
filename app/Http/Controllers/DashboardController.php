<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the POS Dashboard.
     */
    public function index(): View
    {
        // 1. Basic Counts
        $totalProducts = Product::count();
        $totalCategories = MainCategory::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = Customer::count();

        // 2. Financial Summaries for Today
        $todayPurchase = Purchase::whereDate('created_at', today())->sum('grand_total');
        $todaySales = Sale::whereDate('created_at', today())->sum('grand_total');

        // 3. Low Stock Products Query (where current stock is <= alert threshold)
        $lowStockProducts = Product::with('stock')
            ->where(function ($query) {
                $query->whereHas('stock', function ($q) {
                    // $q->whereRaw('stocks.quantity <= products.stock_alert_qty');
                })
                    ->orWhereDoesntHave('stock'); // If no stock entry exists, it means 0 stock
            })
            ->where('status', 'active')
            ->take(8) // Limit list to fit layout
            ->get();

        // 4. Recent Activities log (latest 5)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalCustomers',
            'todayPurchase',
            'todaySales',
            'lowStockProducts',
            'recentActivities'
        ));
    }
}
