<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Display the stock management overview.
     */
    public function index(Request $request): View
    {
        Gate::authorize('stocks.view');

        // Build inventory list with stock quantities and values
        $query = Product::with(['stock', 'brand', 'mainCategory'])
            ->where('status', 'active')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }

        $products = $query->get();

        // Stock adjustment history (most recent first)
        $adjustments = StockAdjustment::with(['product', 'user'])
            ->latest()
            ->take(50)
            ->get();

        // Products dropdown for the adjustment form
        $allProducts = Product::where('status', 'active')->orderBy('name')->get();

        // Filter options
        $categories = \App\Models\MainCategory::where('status', 'active')->orderBy('name')->get();

        return view('stocks.index', compact('products', 'adjustments', 'allProducts', 'categories'));
    }

    /**
     * Store a manual stock adjustment.
     */
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

            // Ensure stock record exists
            $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);

            // Guard: prevent negative stock
            $newQty = $stock->quantity + $change;
            if ($newQty < 0) {
                return back()
                    ->withInput()
                    ->withErrors(['quantity_change' => "Adjustment would result in negative stock ({$newQty}) for \"{$product->name}\". Current stock: {$stock->quantity}."]);
            }

            // Apply the change
            $stock->update(['quantity' => $newQty]);

            // Log the adjustment
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
                "Adjusted stock for \"{$product->name}\" (SKU: {$product->code}): {$sign}{$change} [{$validated['adjustment_type']}]. New qty: {$newQty}."
            );

            DB::commit();
            return redirect()->route('stocks.index')
                ->with('success', "Stock adjusted successfully for \"{$product->name}\" (new qty: {$newQty}).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Unused resource methods — stock has no individual show/edit/destroy pages.
     * Defined to satisfy the resource route binding without 404s.
     */
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
