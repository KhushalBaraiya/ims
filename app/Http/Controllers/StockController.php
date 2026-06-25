<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
                'out'  => $query->whereHas('stock', fn($q) => $q->where('quantity', '<=', 0)),
                'low'  => $query->whereHas('stock', fn($q) => $q->where('quantity', '>', 0)
                                                               ->whereRaw('stocks.quantity <= products.minimum_stock_alert')),
                'ok'   => $query->whereHas('stock', fn($q) => $q->whereColumn('quantity', '>', 'minimum_stock_alert')),
                default => null,
            };
        }

        $products   = $query->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();

        // Summary stats
        $totalProducts   = $products->count();
        $outOfStock      = $products->filter(fn($p) => ($p->stock->quantity ?? 0) <= 0)->count();
        $lowStock        = $products->filter(fn($p) => ($q = $p->stock->quantity ?? 0) > 0 && $q <= $p->minimum_stock_alert)->count();
        $totalInvValue   = $products->sum(fn($p) => ($p->stock->quantity ?? 0) * $p->purchase_price);

        return view('stocks.index', compact(
            'products', 'categories',
            'totalProducts', 'outOfStock', 'lowStock', 'totalInvValue'
        ));
    }

    // ──────────────────────────────────────────────────
    //  2. ADJUSTMENT FORM PAGE
    // ──────────────────────────────────────────────────
    public function adjust(Request $request): View
    {
        Gate::authorize('stocks.create');

        $allProducts = Product::with('stock')->where('status', 'active')->orderBy('name')->get();
        $preselected = $request->query('product_id');

        return view('stocks.adjust', compact('allProducts', 'preselected'));
    }

    // ──────────────────────────────────────────────────
    //  3. SAVE ADJUSTMENT  (POST)
    // ──────────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('stocks.create');

        $validated = $request->validate([
            'product_id'      => 'required|exists:products,id',
            'adjustment_type' => 'required|in:Restock,Damage,Return,Write-Off,Correction,Other',
            'quantity_change' => 'required|numeric|not_in:0',
            'notes'           => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::with('stock')->findOrFail($validated['product_id']);
            $change  = (float) $validated['quantity_change'];
            $stock   = $product->stock ?? $product->stock()->create(['quantity' => 0]);

            $newQty  = $stock->quantity + $change;
            if ($newQty < 0) {
                return back()->withInput()
                    ->withErrors(['quantity_change' =>
                        "Adjustment would result in negative stock ({$newQty}) for \"{$product->name}\". "
                        . "Current stock: {$stock->quantity}."]);
            }

            $stock->update(['quantity' => $newQty]);

            StockAdjustment::create([
                'product_id'      => $product->id,
                'quantity_change' => $change,
                'adjustment_type' => $validated['adjustment_type'],
                'notes'           => $validated['notes'] ?? null,
                'user_id'         => auth()->id(),
            ]);

            $sign = $change > 0 ? '+' : '';
            ActivityLog::log(
                'Stock Adjusted',
                "Adjusted stock for \"{$product->name}\" (SKU: {$product->code}): "
                . "{$sign}{$change} [{$validated['adjustment_type']}]. New qty: {$newQty}."
            );

            DB::commit();
            return redirect()->route('stocks.index')
                ->with('success', "Stock adjusted for \"{$product->name}\" → new qty: {$newQty}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    // ──────────────────────────────────────────────────
    //  4. HISTORY PAGE
    // ──────────────────────────────────────────────────
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
                DB::raw('DATE(created_at)'),
                [$request->start_date, $request->end_date]
            );
        }

        $adjustments = $query->get();
        $allProducts = Product::orderBy('name')->get(['id', 'name', 'code']);

        $types = ['Restock', 'Damage', 'Return', 'Write-Off', 'Correction', 'Other'];

        return view('stocks.history', compact('adjustments', 'allProducts', 'types'));
    }

    // ──────────────────────────────────────────────────
    //  Unused stubs
    // ──────────────────────────────────────────────────
    public function create(): never  { abort(404); }
    public function show(Stock $stock): never  { abort(404); }
    public function edit(Stock $stock): never  { abort(404); }
    public function update(Request $request, Stock $stock): never { abort(404); }
    public function destroy(Stock $stock): never { abort(404); }
}
