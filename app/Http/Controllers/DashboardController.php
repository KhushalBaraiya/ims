<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the POS Dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $canSalesView = method_exists($user, 'can') ? $user->can('sales.view') : false;
        $canPurchasesView = method_exists($user, 'can') ? $user->can('purchases.view') : false;
        $canCustomersView = method_exists($user, 'can') ? $user->can('customers.view') : false;
        $canActivityLogsView = method_exists($user, 'can') ? $user->can('activity_logs.view') : false;
        $canActivityLogsOwn = method_exists($user, 'can') ? $user->can('activity_logs.own') : false;
        $isSuperAdmin = method_exists($user, 'getRoleNames') && $user->getRoleNames()->contains('super_admin');

        // 1. Basic Counts
        $totalProducts   = Product::count();
        $totalCategories = MainCategory::count();
        $totalSuppliers  = Supplier::count();
        $totalCustomers  = Customer::count();
        $totalBrands     = Brand::count();
        $totalUsers      = User::count();

        // 2. Financial Summaries for Today (only if user can view sales/purchases)
        $todayPurchases = $canPurchasesView
            ? Purchase::whereDate('created_at', today())->count()
            : null;
        $todaySales = $canSalesView
            ? Sale::whereDate('created_at', today())->sum('grand_total')
            : null;
        $todaySalesCount = $canSalesView
            ? Sale::whereDate('created_at', today())->count()
            : null;
        $pendingSales = $canSalesView
            ? Sale::where('status', 'Draft')->count()
            : null;

        // 3. Overall financial summary
        $totalRevenue = $canSalesView
            ? Sale::where('status', 'Completed')->sum('grand_total')
            : null;
        $totalPurchases = $canPurchasesView
            ? Purchase::count()
            : null;

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

        // 5. Recent Sales (latest 8) — only if user has sales.view
        $recentSales = $canSalesView
            ? Sale::with('customer')->latest()->take(8)->get()
            : collect();

        // 6. Recent Activity Log (latest 5) — filtered to own logs when user has
        //    activity_logs.own but is NOT super_admin (super_admin sees everything).
        $recentActivities = collect();
        if ($canActivityLogsView) {
            $activityQuery = ActivityLog::with('user')->latest();

            $restrictToOwn = $canActivityLogsOwn && ! $isSuperAdmin;

            if ($restrictToOwn) {
                $activityQuery->where('user_id', $user->id);
            }

            $recentActivities = $activityQuery->take(5)->get();
        }

        // 7. This Week Sales & Purchases (last 7 days, grouped by date)
        $weekDates = [];
        $weekSalesData = [];
        $weekPurchasesData = [];

        if ($canSalesView || $canPurchasesView) {
            $weekStart = now()->subDays(6)->startOfDay();

            $weekSalesByDay = $canSalesView
                ? Sale::selectRaw('DATE(invoice_date) as date, SUM(grand_total) as total')
                ->where('invoice_date', '>=', $weekStart)
                ->groupByRaw('DATE(invoice_date)')
                ->pluck('total', 'date')
                : collect();

            $weekPurchasesByDay = $canPurchasesView
                ? Purchase::selectRaw('DATE(purchase_date) as date, SUM(grand_total) as total')
                ->where('purchase_date', '>=', $weekStart)
                ->groupByRaw('DATE(purchase_date)')
                ->pluck('total', 'date')
                : collect();

            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i)->format('Y-m-d');
                $weekDates[]         = $d;
                $weekSalesData[]     = (float) ($weekSalesByDay[$d] ?? 0);
                $weekPurchasesData[] = (float) ($weekPurchasesByDay[$d] ?? 0);
            }
        }

        // 8. Top Selling Products this month (by quantity sold)
        $monthStart  = now()->startOfMonth();
        $topProducts = $canSalesView
            ? SaleItem::with('product')
            ->whereHas('sale', fn($q) => $q->where('status', 'Completed')
                ->where('invoice_date', '>=', $monthStart))
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(6)
            ->get()
            : collect();

        // 9. Top 5 Customers this month (by grand_total)
        $topCustomers = $canCustomersView
            ? Sale::with('customer')
            ->where('status', 'Completed')
            ->where('invoice_date', '>=', $monthStart)
            ->select('customer_id', DB::raw('SUM(grand_total) as total_spent'))
            ->groupBy('customer_id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get()
            : collect();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalCustomers',
            'totalBrands',
            'totalUsers',
            'todayPurchases',
            'todaySales',
            'todaySalesCount',
            'pendingSales',
            'totalRevenue',
            'totalPurchases',
            'lowStockProducts',
            'recentSales',
            'recentActivities',
            'weekDates',
            'weekSalesData',
            'weekPurchasesData',
            'topProducts',
            'topCustomers'
        ));
    }
}
