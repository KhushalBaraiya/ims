<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StockController extends Controller
{
    // ──────────────────────────────────────────────────
    //  1. INVENTORY OVERVIEW
    // ──────────────────────────────────────────────────
    public function index(Request $request): View
    {
        Gate::authorize('stocks.view');

        $query = Product::with(['stock', 'brand', 'mainCategory'])
            ->where('status', 'active')
            ->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('code', 'like', "%$s%"));
        }

        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        if ($request->filled('stock_status')) {
            match ($request->stock_status) {
                'out' => $query->whereHas('stock', fn($q) => $q->where('quantity', '<=', 0)),
                'low' => $query->whereHas('stock', fn($q) => $q->where('quantity', '>', 0)
                    ->whereRaw('stocks.quantity <= products.minimum_stock_alert')),
                'ok' => $query->whereHas('stock', fn($q) => $q->whereColumn('quantity', '>', 'minimum_stock_alert')),
                default => null,
            };
        }

        $products = $query->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();

        // Summary stats
        $totalProducts = $products->count();
        $outOfStock = $products->filter(fn($p) => ($p->stock->quantity ?? 0) <= 0)->count();
        $lowStock = $products->filter(fn($p) => ($q = $p->stock->quantity ?? 0) > 0 && $q <= $p->minimum_stock_alert)->count();
        $totalInvValue = $products->sum(fn($p) => ($p->stock->quantity ?? 0) * $p->purchase_price);

        return view('stocks.index', compact(
            'products',
            'categories',
            'totalProducts',
            'outOfStock',
            'lowStock',
            'totalInvValue'
        ));
    }

    // ──────────────────────────────────────────────────
    //  2. ADJUSTMENT FORM PAGE
    // ──────────────────────────────────────────────────
    public function adjust(Request $request): View
    {
        Gate::authorize('stocks.create');

        $allProducts = Product::with('stock')->where('status', 'active')->orderBy('name')->get();

        // Support both single ?product_id=X and multiple ?products[]=X&products[]=Y
        $preselected = [];
        if ($request->filled('products')) {
            $preselected = array_filter((array) $request->query('products'));
        } elseif ($request->filled('product_id')) {
            $preselected = [$request->query('product_id')];
        }

        // Load full product data for preselected IDs
        $preselectedProducts = collect();
        if (!empty($preselected)) {
            $preselectedProducts = Product::with('stock')
                ->whereIn('id', $preselected)
                ->where('status', 'active')
                ->get();
        }

        return view('stocks.adjust', compact('allProducts', 'preselected', 'preselectedProducts'));
    }

    // ──────────────────────────────────────────────────
    //  3. SAVE ADJUSTMENT  (POST)
    // ──────────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('stocks.create');

        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.type' => 'required|in:Plus,Minus',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $today = date('Ymd');
            $count = StockAdjustment::where('voucher_no', 'like', "ADJ-{$today}-%")->distinct()->count('voucher_no');
            $voucherNo = 'ADJ-' . $today . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            $adjustedProductsLog = [];

            foreach ($validated['items'] as $item) {
                $product = Product::with('stock')->findOrFail($item['product_id']);
                $qty = (float) $item['quantity'];
                $change = $item['type'] === 'Minus' ? -$qty : $qty;

                $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                $newQty = $stock->quantity + $change;

                if ($newQty < 0) {
                    throw new \Exception("Adjustment would result in negative stock ({$newQty}) for \"{$product->name}\". Current stock: {$stock->quantity}.");
                }

                $stock->update(['quantity' => $newQty]);

                StockAdjustment::create([
                    'voucher_no' => $voucherNo,
                    'transaction_date' => $validated['transaction_date'],
                    'product_id' => $product->id,
                    'quantity_change' => $change,
                    'adjustment_type' => $item['type'],
                    'notes' => $validated['notes'] ?? null,
                    'user_id' => auth()->id(),
                    'created_at' => $validated['transaction_date'] . ' ' . now()->toTimeString(),
                    'updated_at' => $validated['transaction_date'] . ' ' . now()->toTimeString(),
                ]);

                $sign = $change > 0 ? '+' : '';
                $adjustedProductsLog[] = "{$product->name} ({$sign}{$qty})";
            }

            ActivityLog::log(
                'Stock Adjusted',
                "Created stock adjustment voucher: {$voucherNo}. Adjusted products: " . implode(', ', $adjustedProductsLog)
            );

            DB::commit();

            return redirect()->route('stocks.history')
                ->with('success', "Stock adjustment voucher \"{$voucherNo}\" created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────
    //  3.5  SHOW ADJUSTMENT (VIEW)
    // ──────────────────────────────────────────────────
    public function show_adjustment(string $voucherNo): View
    {
        Gate::authorize('stocks.view');

        $adjustments = StockAdjustment::with(['product.stock', 'user'])
            ->where('voucher_no', $voucherNo)
            ->get();

        if ($adjustments->isEmpty()) {
            abort(404);
        }

        $notes           = $adjustments->first()->notes;
        $transactionDate = $adjustments->first()->transaction_date
            ? \Carbon\Carbon::parse($adjustments->first()->transaction_date)->format('d M Y')
            : $adjustments->first()->created_at->format('d M Y');

        return view('stocks.show_adjustment', compact('adjustments', 'voucherNo', 'notes', 'transactionDate'));
    }

    // ──────────────────────────────────────────────────
    //  4. EDIT ADJUSTMENT
    // ──────────────────────────────────────────────────
    public function edit_adjustment($voucherNo): View
    {
        Gate::authorize('stocks.create');

        $adjustments = StockAdjustment::with('product.stock')->where('voucher_no', $voucherNo)->get();
        if ($adjustments->isEmpty()) {
            abort(404);
        }

        $allProducts = Product::with('stock')->where('status', 'active')->orderBy('name')->get();
        $notes = $adjustments->first()->notes;
        $transactionDate = $adjustments->first()->transaction_date ?: $adjustments->first()->created_at->format('Y-m-d');

        return view('stocks.edit_adjustment', compact('adjustments', 'voucherNo', 'allProducts', 'notes', 'transactionDate'));
    }

    // ──────────────────────────────────────────────────
    //  5. UPDATE ADJUSTMENT
    // ──────────────────────────────────────────────────
    public function update_adjustment(Request $request, $voucherNo): RedirectResponse
    {
        Gate::authorize('stocks.create');

        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.type' => 'required|in:Plus,Minus',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $oldAdjustments = StockAdjustment::where('voucher_no', $voucherNo)->get();
        if ($oldAdjustments->isEmpty()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            // Revert old changes
            foreach ($oldAdjustments as $oldAdj) {
                $product = Product::with('stock')->findOrFail($oldAdj->product_id);
                $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                $stock->update(['quantity' => $stock->quantity - $oldAdj->quantity_change]);
            }

            // Verify new changes are valid (don't result in negative stock)
            foreach ($validated['items'] as $item) {
                $product = Product::with('stock')->findOrFail($item['product_id']);
                $qty = (float) $item['quantity'];
                $change = $item['type'] === 'Minus' ? -$qty : $qty;

                // fresh() ensures we read the updated DB value after reverts above
                $stock = $product->stock()->first() ?? $product->stock()->create(['quantity' => 0]);
                $newQty = $stock->quantity + $change;

                if ($newQty < 0) {
                    throw new \Exception("Adjustment would result in negative stock ({$newQty}) for \"{$product->name}\". Current stock (before adjustment): {$stock->quantity}.");
                }
            }

            // Re-apply stocks and delete old entries
            StockAdjustment::where('voucher_no', $voucherNo)->delete();
            $adjustedProductsLog = [];

            foreach ($validated['items'] as $item) {
                $product = Product::with('stock')->findOrFail($item['product_id']);
                $qty = (float) $item['quantity'];
                $change = $item['type'] === 'Minus' ? -$qty : $qty;

                $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                $stock->update(['quantity' => $stock->quantity + $change]);

                StockAdjustment::create([
                    'voucher_no' => $voucherNo,
                    'transaction_date' => $validated['transaction_date'],
                    'product_id' => $product->id,
                    'quantity_change' => $change,
                    'adjustment_type' => $item['type'],
                    'notes' => $validated['notes'] ?? null,
                    'user_id' => auth()->id(),
                    'created_at' => $validated['transaction_date'] . ' ' . now()->toTimeString(),
                    'updated_at' => $validated['transaction_date'] . ' ' . now()->toTimeString(),
                ]);

                $sign = $change > 0 ? '+' : '';
                $adjustedProductsLog[] = "{$product->name} ({$sign}{$qty})";
            }

            ActivityLog::log(
                'Stock Adjusted',
                "Updated stock adjustment voucher: {$voucherNo}. Adjusted products: " . implode(', ', $adjustedProductsLog)
            );

            DB::commit();

            return redirect()->route('stocks.history')
                ->with('success', "Stock adjustment voucher \"{$voucherNo}\" updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────
    //  6. DELETE ADJUSTMENT
    // ──────────────────────────────────────────────────
    public function destroy_adjustment($voucherNo): RedirectResponse
    {
        Gate::authorize('stocks.create');

        $oldAdjustments = StockAdjustment::where('voucher_no', $voucherNo)->get();
        if ($oldAdjustments->isEmpty()) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            foreach ($oldAdjustments as $oldAdj) {
                $product = Product::with('stock')->findOrFail($oldAdj->product_id);
                $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                $stock->update(['quantity' => $stock->quantity - $oldAdj->quantity_change]);
            }

            StockAdjustment::where('voucher_no', $voucherNo)->delete();

            ActivityLog::log(
                'Stock Adjusted',
                "Deleted stock adjustment voucher: {$voucherNo} (reverted stock changes)."
            );

            DB::commit();

            return redirect()->route('stocks.history')
                ->with('success', "Stock adjustment voucher \"{$voucherNo}\" deleted and stock reverted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ──────────────────────────────────────────────────
    //  7. ADJUSTMENTS PAGE (unified, was "history")
    // ──────────────────────────────────────────────────
    public function lowStock(Request $request): View
    {
        Gate::authorize('stocks.view');

        $filter = $request->input('filter', 'low');
        $query = Product::with(['stock', 'brand', 'mainCategory'])
            ->where('status', 'active');

        if ($filter === 'out') {
            $query->where(
                fn($q) => $q->whereHas('stock', fn($sq) => $sq->where('quantity', '<=', 0))
                    ->orWhereDoesntHave('stock')
            );
        } elseif ($filter === 'low') {
            $query->whereHas(
                'stock',
                fn($sq) => $sq->where('quantity', '>', 0)
                    ->whereRaw('stocks.quantity <= products.minimum_stock_alert')
            );
        } else {
            $query->where(
                fn($q) => $q->whereHas('stock', fn($sq) => $sq->whereRaw('stocks.quantity <= products.minimum_stock_alert'))
                    ->orWhereDoesntHave('stock')
            );
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(
                fn($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
            );
        }

        $products = $query->get()->map(function ($product) {
            $currentQty = (float) ($product->stock->quantity ?? 0);
            $alertQty = (float) ($product->minimum_stock_alert ?? 0);
            $qtyNeeded = $alertQty > $currentQty ? $alertQty - $currentQty : 0;
            $product->current_qty = $currentQty;
            $product->alert_qty = $alertQty;
            $product->qty_needed = $qtyNeeded;
            $product->restock_value = $qtyNeeded * ($product->purchase_price ?? 0);
            $product->stock_status = $currentQty <= 0 ? 'out' : 'low';
            return $product;
        });

        $summary = [
            'total' => $products->count(),
            'out_of_stock' => $products->filter(fn($p) => $p->stock_status === 'out')->count(),
            'low_stock' => $products->filter(fn($p) => $p->stock_status === 'low')->count(),
            'restock_value' => $products->sum('restock_value'),
        ];

        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();

        return view('stocks.low-stock', compact('products', 'summary', 'filter', 'brands', 'categories'));
    }

    public function history(Request $request): View
    {
        Gate::authorize('stocks.view');

        $query = StockAdjustment::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('adjustment_type')) {
            $query->where('adjustment_type', $request->adjustment_type);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(
                DB::raw('DATE(transaction_date)'),
                [$request->start_date, $request->end_date]
            );
        }

        $allAdjustments     = $query->get();
        $adjustmentsGrouped = $allAdjustments->groupBy('voucher_no');
        $allProducts        = Product::orderBy('name')->get(['id', 'name', 'code']);
        $types              = ['Plus', 'Minus'];

        // Summary card stats (across ALL products, not filtered)
        $allStockProducts = Product::with('stock')->where('status', 'active')->get();
        $totalProducts    = $allStockProducts->count();
        $outOfStock       = $allStockProducts->filter(fn($p) => ($p->stock->quantity ?? 0) <= 0)->count();
        $lowStock         = $allStockProducts->filter(fn($p) => ($q = $p->stock->quantity ?? 0) > 0 && $q <= $p->minimum_stock_alert)->count();

        return view('stocks.history', compact(
            'adjustmentsGrouped',
            'allProducts',
            'types',
            'totalProducts',
            'outOfStock',
            'lowStock'
        ));
    }

    // ──────────────────────────────────────────────────
    //  Unused stubs
    // ──────────────────────────────────────────────────
    public function create(): never
    {
        abort(404);
    }

    public function show(Stock $stock): never
    {
        abort(404);
    }

    public function edit(Stock $stock): never
    {
        abort(404);
    }

    public function update(Request $request, Stock $stock): never
    {
        abort(404);
    }

    public function destroy(Stock $stock): never
    {
        abort(404);
    }
}
