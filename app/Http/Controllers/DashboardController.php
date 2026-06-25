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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the POS Dashboard.
     */
    public function index(): View
    {
        Gate::authorize('dashboard.view');
        // 1. Basic Counts
        $totalProducts   = Product::count();
        $totalCategories = MainCategory::count();
        $totalSuppliers  = Supplier::count();
        $totalCustomers  = Customer::count();
        $totalBrands     = Brand::count();
        $totalUsers      = User::count();

        // 2. Financial Summaries for Today
        $todayPurchases = Purchase::whereDate('created_at', today())->count();
        $todaySales     = Sale::whereDate('created_at', today())->sum('grand_total');
        $pendingSales   = Sale::where('status', 'Draft')->count();

        // 3. Overall financial summary
        $totalRevenue  = Sale::where('status', 'Completed')->sum('grand_total');
        $totalPurchases = Purchase::count();

        // 4. Low Stock Products — products where stock qty <= minimum_stock_alert
        $lowStockProducts = Product::with('stock')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereHas('stock', function ($q) {
                    $q->whereRaw('stocks.quantity <= products.minimum_stock_alert');
                })->orWhereDoesntHave('stock');
            })
            ->take(8)
            ->get();

        // 5. Recent Sales (latest 8)
        $recentSales = Sale::with('customer')
            ->latest()
            ->take(8)
            ->get();

        // 6. Recent Activity Log (latest 5)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalCustomers',
            'totalBrands',
            'totalUsers',
            'todayPurchases',
            'todaySales',
            'pendingSales',
            'totalRevenue',
            'totalPurchases',
            'lowStockProducts',
            'recentSales',
            'recentActivities'
        ));
    }
}
