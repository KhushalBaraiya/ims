<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleReturnRequest;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of sales returns.
     */
    public function index(Request $request): View
    {
        Gate::authorize('sale_returns.view');

        $query = SaleReturn::with(['sale', 'customer', 'user'])->latest();

        // Apply filters
        if ($request->filled('return_no')) {
            $query->where('return_no', 'like', "%{$request->return_no}%");
        }
        if ($request->filled('sale_invoice')) {
            $query->whereHas('sale', function($q) use ($request) {
                $q->where('invoice_no', 'like', "%{$request->sale_invoice}%");
            });
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('return_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('sale_returns.index', compact('returns', 'customers'));
    }

    /**
     * Show the form for creating a new return.
     */
    public function create(): View
    {
        Gate::authorize('sale_returns.create');

        // Only load Completed sales for return
        $sales = Sale::where('status', 'Completed')->orderBy('invoice_no', 'desc')->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('sale_returns.create', compact('sales', 'customers'));
    }

    /**
     * Store a newly created return in database.
     */
    public function store(SaleReturnRequest $request): RedirectResponse
    {
        Gate::authorize('sale_returns.create');

        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($request->sale_id);

            // ── STEP 1: Validate all quantities before touching stock ──
            $itemsToProcess = [];
            $subTotal = 0;
            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $saleItem = $sale->items()->where('product_id', $item['product_id'])->first();
                if (!$saleItem) {
                    throw new \Exception("Product ID {$item['product_id']} was not part of original sale.");
                }

                $alreadyReturned = SaleReturnItem::whereHas('saleReturn', function ($q) use ($sale) {
                    $q->where('sale_id', $sale->id)->where('status', 'Completed');
                })->where('product_id', $item['product_id'])->sum('quantity');

                $availableReturn = max(0, (int)($saleItem->quantity - $alreadyReturned));

                if ($qty > $availableReturn) {
                    DB::rollBack();
                    return back()->withInput()->with('error',
                        "Cannot return {$qty} unit(s) for \"{$saleItem->product->name}\". Max returnable: {$availableReturn}.");
                }

                $subTotal += $qty * $saleItem->unit_price;
                $itemsToProcess[] = [
                    'product_id'   => $item['product_id'],
                    'quantity'     => $qty,
                    'unit_price'   => $saleItem->unit_price,
                    'tax_amount'   => 0.00,
                    'total_amount' => $qty * $saleItem->unit_price,
                    'reason'       => $item['reason'] ?? null,
                ];
            }

            if (empty($itemsToProcess)) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Please specify a return quantity of at least 1 for one or more items.');
            }

            // ── STEP 2: Create the Sale Return record ──
            $returnNo = $this->generateReturnNo();
            $saleReturn = SaleReturn::create([
                'return_no'       => $returnNo,
                'return_date'     => $request->return_date,
                'sale_id'         => $sale->id,
                'customer_id'     => $sale->customer_id,
                'reference_no'    => $request->reference_no,
                'sub_total'       => $subTotal,
                'tax_amount'      => 0.00,
                'discount_amount' => 0.00,
                'grand_total'     => $subTotal,
                'refunded_amount' => (float) ($request->refunded_amount ?? 0.00),
                'notes'           => $request->notes,
                'status'          => $request->status,
                'user_id'         => auth()->id(),
            ]);

            // ── STEP 3: Create items and increment stock ──
            foreach ($itemsToProcess as $item) {
                $saleReturn->items()->create($item);

                if ($request->status === 'Completed') {
                    $product = Product::findOrFail($item['product_id']);
                    $product->stock->increment('quantity', $item['quantity']);
                }
            }

            DB::commit();
            ActivityLog::log('Sale Returned', "Created sales return: {$saleReturn->return_no} for Invoice: {$sale->invoice_no}");

            return redirect()->route('sale-returns.index')->with('success', 'Sales return created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display return details.
     */
    public function show(SaleReturn $saleReturn): View
    {
        Gate::authorize('sale_returns.view');
        $saleReturn->load(['sale', 'customer', 'user', 'items.product.stock']);
        return view('sale_returns.show', compact('saleReturn'));
    }

    /**
     * Show form for editing a sales return.
     */
    public function edit(SaleReturn $saleReturn): View
    {
        Gate::authorize('sale_returns.update');
        $saleReturn->load(['items.product', 'sale']);
        return view('sale_returns.edit', compact('saleReturn'));
    }

    /**
     * Update the return record and stock values.
     */
    public function update(SaleReturnRequest $request, SaleReturn $saleReturn): RedirectResponse
    {
        Gate::authorize('sale_returns.update');

        DB::beginTransaction();
        try {
            $sale      = $saleReturn->sale;
            $oldStatus = $saleReturn->status;
            $newStatus = $request->status;

            // ── STEP 1: Validate quantities BEFORE touching any stock ──
            $itemsToProcess = [];
            $subTotal = 0;
            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $saleItem = $sale->items()->where('product_id', $item['product_id'])->first();
                if (!$saleItem) {
                    throw new \Exception("Product ID {$item['product_id']} was not part of original sale.");
                }

                $alreadyReturned = SaleReturnItem::whereHas('saleReturn', function ($q) use ($sale, $saleReturn) {
                    $q->where('sale_id', $sale->id)
                      ->where('id', '!=', $saleReturn->id)
                      ->where('status', 'Completed');
                })->where('product_id', $item['product_id'])->sum('quantity');

                $availableReturn = max(0, (int)($saleItem->quantity - $alreadyReturned));

                if ($qty > $availableReturn) {
                    DB::rollBack();
                    return back()->withInput()->with('error',
                        "Cannot return {$qty} unit(s) for \"{$saleItem->product->name}\". Max returnable: {$availableReturn}.");
                }

                $subTotal += $qty * $saleItem->unit_price;
                $itemsToProcess[] = [
                    'product_id'   => $item['product_id'],
                    'quantity'     => $qty,
                    'unit_price'   => $saleItem->unit_price,
                    'tax_amount'   => 0.00,
                    'total_amount' => $qty * $saleItem->unit_price,
                    'reason'       => $item['reason'] ?? null,
                ];
            }

            // ── STEP 2: Revert previous stock if old status was Completed ──
            if ($oldStatus === 'Completed') {
                foreach ($saleReturn->items as $oldItem) {
                    $oldItem->product->stock->decrement('quantity', $oldItem->quantity);
                }
            }

            // ── STEP 3: Delete old items ──
            $saleReturn->items()->delete();

            // ── STEP 4: Create new items and apply new stock ──
            foreach ($itemsToProcess as $item) {
                $saleReturn->items()->create($item);

                if ($newStatus === 'Completed') {
                    $product = Product::findOrFail($item['product_id']);
                    $product->stock->increment('quantity', $item['quantity']);
                }
            }

            $saleReturn->update([
                'return_date'     => $request->return_date,
                'reference_no'    => $request->reference_no,
                'sub_total'       => $subTotal,
                'grand_total'     => $subTotal,
                'refunded_amount' => (float) ($request->refunded_amount ?? 0.00),
                'notes'           => $request->notes,
                'status'          => $newStatus,
            ]);

            DB::commit();
            ActivityLog::log('Return Updated', "Updated sales return: {$saleReturn->return_no}");

            return redirect()->route('sale-returns.index')->with('success', 'Sales return updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the return record and decrease returned stock quantities.
     */
    public function destroy(SaleReturn $saleReturn, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('sale_returns.delete');

        DB::beginTransaction();
        try {
            // Decrease stock by returned quantity to reverse return
            if ($saleReturn->status === 'Completed') {
                foreach ($saleReturn->items as $item) {
                    $item->product->stock->decrement('quantity', $item->quantity);
                }
            }

            $returnNo = $saleReturn->return_no;
            $saleReturn->delete();

            DB::commit();
            ActivityLog::log('Return Deleted', "Deleted sales return: {$returnNo}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sales return deleted successfully.',
                ]);
            }

            return redirect()->route('sale-returns.index')->with('success', 'Sales return deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Bulk delete sale returns.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('sale_returns.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $deleted = 0;
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $ret = SaleReturn::with('items.product.stock')->find($id);
                if (!$ret) continue;
                if ($ret->status === 'Completed') {
                    foreach ($ret->items as $item) {
                        $item->product->stock->decrement('quantity', $item->quantity);
                    }
                }
                $ret->delete();
                $deleted++;
            }
            DB::commit();
            ActivityLog::log('Sale Returns Bulk Deleted', "Deleted {$deleted} sale return(s).");
            return response()->json(['success' => true, 'message' => "{$deleted} return(s) deleted successfully."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * AJAX action to load details of a sale invoice.
     */
    public function getSaleReturnData(Sale $sale): JsonResponse
    {
        Gate::authorize('sale_returns.create');

        $sale->load(['items.product', 'customer']);

        $items = [];
        foreach ($sale->items as $item) {
            // Count already returned quantities in Completed state
            $alreadyReturned = SaleReturnItem::whereHas('saleReturn', function($q) use ($sale) {
                $q->where('sale_id', $sale->id)->where('status', 'Completed');
            })->where('product_id', $item->product_id)->sum('quantity');

            $availableReturn = max(0.00, $item->quantity - $alreadyReturned);

            $items[] = [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'sku' => $item->product->code,
                'unit_price' => (float)$item->unit_price,
                'sold_quantity' => (float)$item->quantity,
                'returned_quantity' => (float)$alreadyReturned,
                'available_quantity' => (float)$availableReturn,
                'unit' => $item->product->unit_code ?? 'PCS',
                'image_url' => $item->product->image ? asset('uploads/products/' . $item->product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image'
            ];
        }

        return response()->json([
            'success' => true,
            'invoice_no' => $sale->invoice_no,
            'customer_name' => $sale->customer->name,
            'items' => $items
        ]);
    }

    /**
     * Render the refund printable window view.
     */
    public function printReturn(SaleReturn $saleReturn): View
    {
        Gate::authorize('sale_returns.view');
        
        $saleReturn->load(['sale', 'customer', 'user', 'items.product.stock']);
        
        // Log the printing activity
        ActivityLog::log('Sale Printed', "Printed return sheet: {$saleReturn->return_no}");

        return view('sale_returns.print', compact('saleReturn'));
    }

    /**
     * Generate unique Return number.
     */
    private function generateReturnNo(): string
    {
        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $latestReturn = SaleReturn::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum = $latestReturn ? ((int) substr($latestReturn->return_no, -5)) + 1 : 1;
            $returnNo = 'RET-' . date('Ymd') . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

            if (!SaleReturn::where('return_no', $returnNo)->exists()) {
                return $returnNo;
            }
        }
        return 'RET-' . date('Ymd') . '-' . uniqid();
    }
}
