<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    /* ──────────────────────────────────────────────────────────────────
     |  1. REPORT INDEX — overview / navigation hub
     * ─────────────────────────────────────────────────────────────── */
    public function index(): View
    {
        Gate::authorize('reports.view');

        $summary = [
            'total_sales' => Sale::where('status', 'Completed')->sum('grand_total'),
            'total_purchases' => Purchase::where('status', 'received')->sum('grand_total'),
            'total_returns' => SaleReturn::sum('grand_total'),
            'total_products' => Product::count(),
            'low_stock' => Product::whereHas('stock', fn ($q) => $q->where('quantity', '>', 0)
                ->whereRaw('stocks.quantity <= products.minimum_stock_alert')
            )->count(),
            'out_of_stock' => Product::where(fn ($q) => $q->whereHas('stock', fn ($sq) => $sq->where('quantity', '<=', 0))
                ->orWhereDoesntHave('stock')
            )->count(),
        ];

        $netProfit = $summary['total_sales'] - $summary['total_purchases'] - $summary['total_returns'];

        return view('reports.index', compact('summary', 'netProfit'));
    }

    /* ──────────────────────────────────────────────────────────────────
     |  2. SALES REPORT
     * ─────────────────────────────────────────────────────────────── */
    public function sales(Request $request): View
    {
        Gate::authorize('reports.view');

        $query = Sale::with('customer', 'user')
            ->when($request->date_from, fn ($q) => $q->whereDate('invoice_date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('invoice_date', '<=', $request->date_to))
            ->when($request->customer_id, fn ($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->payment_method, fn ($q) => $q->where('payment_method', $request->payment_method))
            ->latest('invoice_date');

        $sales = $query->get();
        $customers = Customer::orderBy('name')->get();

        $totals = [
            'sub_total' => $sales->sum('sub_total'),
            'tax_amount' => $sales->sum('tax_amount'),
            'discount_amount' => $sales->sum('discount_amount'),
            'shipping_amount' => $sales->sum('shipping_amount'),
            'grand_total' => $sales->sum('grand_total'),
            'paid_amount' => $sales->sum('paid_amount'),
            'due_amount' => $sales->sum('due_amount'),
            'count' => $sales->count(),
        ];

        return view('reports.sales', compact('sales', 'customers', 'totals'));
    }

    /* ──────────────────────────────────────────────────────────────────
     |  3. PURCHASE REPORT
     * ─────────────────────────────────────────────────────────────── */
    public function purchases(Request $request): View
    {
        Gate::authorize('reports.view');

        $query = Purchase::with('supplier', 'user')
            ->when($request->date_from, fn ($q) => $q->whereDate('purchase_date', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('purchase_date', '<=', $request->date_to))
            ->when($request->supplier_id, fn ($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->payment_method, fn ($q) => $q->where('payment_method', $request->payment_method))
            ->latest('purchase_date');

        $purchases = $query->get();
        $suppliers = Supplier::orderBy('name')->get();

        $totals = [
            'sub_total' => $purchases->sum('sub_total'),
            'tax_amount' => $purchases->sum('tax_amount'),
            'discount_amount' => $purchases->sum('discount_amount'),
            'shipping_amount' => $purchases->sum('shipping_amount'),
            'grand_total' => $purchases->sum('grand_total'),
            'paid_amount' => $purchases->sum('paid_amount'),
            'due_amount' => $purchases->sum('due_amount'),
            'count' => $purchases->count(),
        ];

        return view('reports.purchases', compact('purchases', 'suppliers', 'totals'));
    }

    /* ──────────────────────────────────────────────────────────────────
     |  4. PROFIT & LOSS REPORT
     * ─────────────────────────────────────────────────────────────── */
    public function profitLoss(Request $request): View
    {
        Gate::authorize('reports.view');

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        // ── Revenue (completed sales) ──
        $salesQuery = Sale::where('status', 'Completed')
            ->when($dateFrom, fn ($q) => $q->whereDate('invoice_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('invoice_date', '<=', $dateTo));

        $totalRevenue = $salesQuery->sum('grand_total');
        $totalTaxCollected = $salesQuery->sum('tax_amount');
        $totalDiscounts = $salesQuery->sum('discount_amount');
        $totalSalesCount = $salesQuery->count();

        // ── Cost of goods sold (purchase cost of sold items) ──
        $cogsQuery = SaleItem::whereHas('sale', function ($q) use ($dateFrom, $dateTo) {
            $q->where('status', 'Completed')
                ->when($dateFrom, fn ($q2) => $q2->whereDate('invoice_date', '>=', $dateFrom))
                ->when($dateTo, fn ($q2) => $q2->whereDate('invoice_date', '<=', $dateTo));
        })->with('product');

        $saleItems = $cogsQuery->get();
        $totalCogs = $saleItems->sum(fn ($item) => ($item->product->purchase_price ?? 0) * $item->quantity
        );

        // ── Sale Returns ──
        $returnsQuery = SaleReturn::when($dateFrom, fn ($q) => $q->whereDate('return_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('return_date', '<=', $dateTo));

        $totalSaleReturns = $returnsQuery->sum('grand_total');

        // ── Purchase Returns (recovered cost) ──
        $purReturnsQuery = PurchaseReturn::when($dateFrom, fn ($q) => $q->whereDate('return_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('return_date', '<=', $dateTo));

        $totalPurchaseReturns = $purReturnsQuery->sum('grand_total');

        // ── Calculations ──
        $netRevenue = $totalRevenue - $totalSaleReturns;
        $grossProfit = $netRevenue - $totalCogs;
        $netProfit = $grossProfit + $totalPurchaseReturns;
        $profitMargin = $netRevenue > 0 ? round(($netProfit / $netRevenue) * 100, 2) : 0;

        // ── Monthly breakdown ──
        $monthlyData = Sale::where('status', 'Completed')
            ->when($dateFrom, fn ($q) => $q->whereDate('invoice_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('invoice_date', '<=', $dateTo))
            ->selectRaw("DATE_FORMAT(invoice_date, '%Y-%m') as month, SUM(grand_total) as revenue, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.profit-loss', compact(
            'totalRevenue', 'totalCogs', 'totalSaleReturns', 'totalPurchaseReturns',
            'totalTaxCollected', 'totalDiscounts', 'totalSalesCount',
            'netRevenue', 'grossProfit', 'netProfit', 'profitMargin',
            'monthlyData', 'dateFrom', 'dateTo'
        ));
    }

    /* ──────────────────────────────────────────────────────────────────
     |  5. TOP SELLING PRODUCTS REPORT
     * ─────────────────────────────────────────────────────────────── */
    public function topSelling(Request $request): View
    {
        Gate::authorize('reports.view');

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $limit = (int) $request->input('limit', 20);
        $limit = in_array($limit, [10, 20, 50, 100]) ? $limit : 20;
        $sortBy = in_array($request->sort_by, ['quantity', 'revenue', 'profit']) ? $request->sort_by : 'quantity';

        $topProducts = SaleItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('COUNT(DISTINCT sale_id) as order_count')
        )
            ->whereHas('sale', function ($q) use ($dateFrom, $dateTo) {
                $q->where('status', 'Completed')
                    ->when($dateFrom, fn ($q2) => $q2->whereDate('invoice_date', '>=', $dateFrom))
                    ->when($dateTo, fn ($q2) => $q2->whereDate('invoice_date', '<=', $dateTo));
            })
            ->with(['product' => fn ($q) => $q->with('brand', 'mainCategory', 'stock')])
            ->groupBy('product_id')
            ->orderByDesc($sortBy === 'revenue' ? 'total_revenue' : 'total_qty')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $purchasePrice = $item->product->purchase_price ?? 0;
                $item->total_cost = $purchasePrice * $item->total_qty;
                $item->total_profit = $item->total_revenue - $item->total_cost;
                $item->profit_pct = $item->total_revenue > 0
                    ? round(($item->total_profit / $item->total_revenue) * 100, 1)
                    : 0;

                return $item;
            });

        if ($sortBy === 'profit') {
            $topProducts = $topProducts->sortByDesc('total_profit')->values();
        }

        $grandTotal = [
            'qty' => $topProducts->sum('total_qty'),
            'revenue' => $topProducts->sum('total_revenue'),
            'cost' => $topProducts->sum('total_cost'),
            'profit' => $topProducts->sum('total_profit'),
        ];

        return view('reports.top-selling', compact(
            'topProducts', 'grandTotal', 'dateFrom', 'dateTo', 'limit', 'sortBy'
        ));
    }

    /* ──────────────────────────────────────────────────────────────────
     |  6. STOCK ALERT REPORT
     * ─────────────────────────────────────────────────────────────── */
    public function stockAlert(Request $request): View
    {
        Gate::authorize('reports.view');

        $filter = $request->input('filter', 'all'); // all | low | out

        $query = Product::with(['stock', 'brand', 'mainCategory'])
            ->where('status', 'active');

        if ($filter === 'out') {
            $query->where(fn ($q) => $q->whereHas('stock', fn ($sq) => $sq->where('quantity', '<=', 0))
                ->orWhereDoesntHave('stock')
            );
        } elseif ($filter === 'low') {
            $query->whereHas('stock', fn ($sq) => $sq->where('quantity', '>', 0)
                ->whereRaw('stocks.quantity <= products.minimum_stock_alert')
            );
        } else {
            // all — both low and out
            $query->where(fn ($q) => $q->whereHas('stock', fn ($sq) => $sq->whereRaw('stocks.quantity <= products.minimum_stock_alert')
            )
                ->orWhereDoesntHave('stock')
            );
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        $products = $query->orderBy('name')->get()
            ->map(function ($p) {
                $qty = (float) ($p->stock->quantity ?? 0);
                $alert = (float) ($p->minimum_stock_alert ?? 0);
                $p->current_qty = $qty;
                $p->alert_qty = $alert;
                $p->qty_needed = max(0, $alert - $qty);
                $p->restock_value = $p->qty_needed * $p->purchase_price;
                $p->stock_status = $qty <= 0 ? 'out' : 'low';

                return $p;
            });

        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();

        $summary = [
            'total' => $products->count(),
            'out_of_stock' => $products->where('stock_status', 'out')->count(),
            'low_stock' => $products->where('stock_status', 'low')->count(),
            'restock_value' => $products->sum('restock_value'),
        ];

        return view('reports.stock-alert', compact('products', 'brands', 'categories', 'summary', 'filter'));
    }

    /* ──────────────────────────────────────────────────────────────────
     |  7. EXPORT — Sales CSV
     * ─────────────────────────────────────────────────────────────── */
    public function exportSales(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        Gate::authorize('reports.view');

        $sales = Sale::with('customer', 'user')
            ->when($request->date_from, fn($q) => $q->whereDate('invoice_date', '>=', $request->date_from))
            ->when($request->date_to,   fn($q) => $q->whereDate('invoice_date', '<=', $request->date_to))
            ->when($request->customer_id, fn($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->status,    fn($q) => $q->where('status', $request->status))
            ->when($request->payment_method, fn($q) => $q->where('payment_method', $request->payment_method))
            ->latest('invoice_date')->get();

        $filename = 'sales-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($sales) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF");
            fputcsv($h, ['Invoice No', 'Date', 'Customer', 'Status', 'Payment Status', 'Payment Method', 'Subtotal', 'Tax', 'Discount', 'Shipping', 'Grand Total', 'Paid', 'Due', 'Created By']);
            foreach ($sales as $s) {
                fputcsv($h, [
                    $s->invoice_no, $s->invoice_date,
                    $s->customer->name ?? '-', $s->status, $s->payment_status,
                    $s->payment_method ?? '-',
                    number_format($s->sub_total, 2), number_format($s->tax_amount, 2),
                    number_format($s->discount_amount, 2), number_format($s->shipping_amount, 2),
                    number_format($s->grand_total, 2), number_format($s->paid_amount, 2),
                    number_format($s->due_amount, 2), $s->user->name ?? '-',
                ]);
            }
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /* ──────────────────────────────────────────────────────────────────
     |  8. EXPORT — Purchases CSV
     * ─────────────────────────────────────────────────────────────── */
    public function exportPurchases(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        Gate::authorize('reports.view');

        $purchases = Purchase::with('supplier', 'user')
            ->when($request->date_from,   fn($q) => $q->whereDate('purchase_date', '>=', $request->date_from))
            ->when($request->date_to,     fn($q) => $q->whereDate('purchase_date', '<=', $request->date_to))
            ->when($request->supplier_id, fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->status,      fn($q) => $q->where('status', $request->status))
            ->when($request->payment_method, fn($q) => $q->where('payment_method', $request->payment_method))
            ->latest('purchase_date')->get();

        $filename = 'purchases-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($purchases) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF");
            fputcsv($h, ['Purchase No', 'Date', 'Supplier', 'Ref No', 'Status', 'Payment Status', 'Payment Method', 'Subtotal', 'Tax', 'Discount', 'Shipping', 'Grand Total', 'Paid', 'Due', 'Created By']);
            foreach ($purchases as $p) {
                fputcsv($h, [
                    $p->purchase_no, $p->purchase_date,
                    $p->supplier->name ?? '-', $p->reference_no ?? '-',
                    $p->status, $p->payment_status,
                    $p->payment_method ?? '-',
                    number_format($p->sub_total, 2), number_format($p->tax_amount, 2),
                    number_format($p->discount_amount, 2), number_format($p->shipping_amount, 2),
                    number_format($p->grand_total, 2), number_format($p->paid_amount, 2),
                    number_format($p->due_amount, 2), $p->user->name ?? '-',
                ]);
            }
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /* ──────────────────────────────────────────────────────────────────
     |  9. EXPORT — Stock Alert CSV
     * ─────────────────────────────────────────────────────────────── */
    public function exportStockAlert(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        Gate::authorize('reports.view');

        $products = Product::with(['stock', 'brand', 'mainCategory'])
            ->where('status', 'active')
            ->where(fn($q) =>
                $q->whereHas('stock', fn($sq) => $sq->whereRaw('stocks.quantity <= products.minimum_stock_alert'))
                  ->orWhereDoesntHave('stock')
            )
            ->orderBy('name')->get();

        $filename = 'stock-alert-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF");
            fputcsv($h, ['Product Name', 'SKU', 'Brand', 'Category', 'Unit', 'Current Stock', 'Alert Level', 'Qty Needed', 'Purchase Price', 'Restock Value', 'Status']);
            foreach ($products as $p) {
                $qty    = (float)($p->stock->quantity ?? 0);
                $alert  = (float)($p->minimum_stock_alert ?? 0);
                $needed = max(0, $alert - $qty);
                fputcsv($h, [
                    $p->name, $p->code,
                    $p->brand->name ?? '-', $p->mainCategory->name ?? '-',
                    $p->unit_code ?? 'PCS',
                    number_format($qty, 2), number_format($alert, 2), number_format($needed, 2),
                    number_format($p->purchase_price, 2),
                    number_format($needed * $p->purchase_price, 2),
                    $qty <= 0 ? 'Out of Stock' : 'Low Stock',
                ]);
            }
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
