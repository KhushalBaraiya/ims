<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Reviews;
use App\Models\WishList;
use App\Models\MainCategory;
use App\Models\Brand;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stats ──
        $totalUsers    = User::count();
        $totalOrders   = Order::count();
        $totalProducts = Product::count();
        $totalRevenue  = Order::where('payment_status', 'paid')->sum('total_amount');

        // ── Today ──
        $todayOrders  = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_amount');

        // ── Order Status (using payment_status since no order_status column) ──
        $pendingOrders        = Order::where('payment_status', 'pending')->count();
        $confirmedOrders      = Order::where('payment_status', 'paid')->count();
        $shippedOrders        = 0;
        $outForDeliveryOrders = 0;
        $deliveredOrders      = Order::where('payment_status', 'paid')->count();
        $cancelledOrders      = Order::where('payment_status', 'failed')->count();

        // ── Recent ──
        $recentOrders = Order::with(['user', 'product'])->latest()->take(8)->get();
        $recentUsers  = User::with('role')->latest()->take(6)->get();

        // ── Top Products by order items ──
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        // ── Monthly Revenue (last 6 months) ──
        $monthlyRevenue = [];
        $monthlyLabels  = [];
        $monthlyOrders  = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[]  = $month->format('M Y');
            $monthlyRevenue[] = (float) Order::where('payment_status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
            $monthlyOrders[]  = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // ── Low Stock ──
        $lowStockProducts = Product::where('quantity', '<=', 5)->latest()->take(5)->get();

        // ── Recent Reviews ──
        $recentReviews = Reviews::with(['product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalOrders', 'totalProducts', 'totalRevenue',
            'todayOrders', 'todayRevenue',
            'pendingOrders', 'confirmedOrders', 'shippedOrders',
            'outForDeliveryOrders', 'deliveredOrders', 'cancelledOrders',
            'recentOrders', 'recentUsers',
            'topProducts', 'lowStockProducts', 'recentReviews',
            'monthlyRevenue', 'monthlyLabels', 'monthlyOrders'
        ));
    }
}
