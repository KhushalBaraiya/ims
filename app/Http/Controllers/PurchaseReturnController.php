<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseReturnRequest;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of purchase returns.
     */
    public function index(Request $request): View
    {
        Gate::authorize('purchase_returns.view');

        $query = PurchaseReturn::with(['supplier', 'user', 'purchase'])->latest();

        if ($request->filled('return_no')) {
            $query->where('return_no', 'like', "%{$request->return_no}%");
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('return_date', [$request->start_date, $request->end_date]);
        }

        $returns   = $query->get();
        $suppliers = \App\Models\Supplier::where('status', 'active')->orderBy('name')->get();

        return view('purchase_returns.index', compact('returns', 'suppliers'));
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        Gate::authorize('purchase_returns.create');

        $purchases = Purchase::with('supplier')
            ->where('status', 'Completed')
            ->orderByDesc('created_at')
            ->get();

        return view('purchase_returns.create', compact('purchases'));
    }

    /**
     * Store a newly created purchase return.
     */
    public function store(PurchaseReturnRequest $request): RedirectResponse
    {
        Gate::authorize('purchase_returns.create');

        DB::beginTransaction();
        try {
            $purchase = Purchase::findOrFail($request->purchase_id);

            $alreadyReturnedMap = $this->getAlreadyReturnedMap($purchase->id);

            // ── STEP 1: Validate quantities & check stock before touching anything ──
            $subTotal = 0;
            $itemsToProcess = [];
            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $purchaseItem = $purchase->items()->where('product_id', $item['product_id'])->first();
                if (!$purchaseItem) {
                    throw new \Exception("Product ID {$item['product_id']} was not part of original purchase.");
                }

                $alreadyReturned = $alreadyReturnedMap->get((int) $item['product_id'], 0);
                $maxReturnable   = $purchaseItem->quantity - $alreadyReturned;

                if ($qty > $maxReturnable) {
                    DB::rollBack();
                    return back()->withInput()->with('error',
                        "Cannot return {$qty} unit(s) for \"{$purchaseItem->product->name}\". Max returnable: {$maxReturnable}.");
                }

                // Pre-check stock availability if Completed
                if ($request->status === 'Completed') {
                    $currentStock = $purchaseItem->product->stock->quantity ?? 0;
                    if ($currentStock < $qty) {
                        DB::rollBack();
                        return back()->withInput()->with('error',
                            "Insufficient stock to return \"{$purchaseItem->product->name}\". Current stock: {$currentStock}, trying to return: {$qty}.");
                    }
                }

                $subTotal += ($qty * $purchaseItem->purchase_price);
                $itemsToProcess[] = [
                    'product_id'     => $item['product_id'],
                    'quantity'       => $qty,
                    'purchase_price' => $purchaseItem->purchase_price,
                    'total_amount'   => $qty * $purchaseItem->purchase_price,
                    'reason'         => $item['reason'] ?? null,
                ];
            }

            if (empty($itemsToProcess)) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Please specify a return quantity of at least 1 for one or more items.');
            }

            // ── STEP 2: Create the Purchase Return record ──
            $returnNo = $this->generateReturnNo();
            $return = PurchaseReturn::create([
                'return_no'       => $returnNo,
                'return_date'     => $request->return_date,
                'purchase_id'     => $purchase->id,
                'supplier_id'     => $purchase->supplier_id,
                'reference_no'    => $request->reference_no,
                'sub_total'       => $subTotal,
                'tax_amount'      => 0.00,
                'discount_amount' => 0.00,
                'grand_total'     => $subTotal,
                'refunded_amount' => (float) $request->refunded_amount,
                'notes'           => $request->notes,
                'status'          => $request->status,
                'user_id'         => auth()->id(),
            ]);

            // ── STEP 3: Create items and decrement stock ──
            foreach ($itemsToProcess as $item) {
                $return->items()->create($item);

                if ($request->status === 'Completed') {
                    $product = Product::findOrFail($item['product_id']);
                    $product->stock->decrement('quantity', $item['quantity']);
                }
            }

            DB::commit();
            ActivityLog::log('Purchase Return Created', "Created purchase return: {$return->return_no} for PO: {$purchase->purchase_no}");

            return redirect()->route('purchase-returns.index')->with('success', 'Purchase return processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified purchase return.
     */
    public function show(PurchaseReturn $purchaseReturn): View
    {
        Gate::authorize('purchase_returns.view');
        $purchaseReturn->load(['items.product', 'supplier', 'user', 'purchase']);
        return view('purchase_returns.show', compact('purchaseReturn'));
    }

    /**
     * Show the edit form.
     */
    public function edit(PurchaseReturn $purchaseReturn): View
    {
        Gate::authorize('purchase_returns.update');
        $purchaseReturn->load(['items.product', 'purchase.supplier']);
        return view('purchase_returns.edit', compact('purchaseReturn'));
    }

    /**
     * Update the specified purchase return.
     */
    public function update(PurchaseReturnRequest $request, PurchaseReturn $purchaseReturn): RedirectResponse
    {
        Gate::authorize('purchase_returns.update');

        DB::beginTransaction();
        try {
            $purchase  = $purchaseReturn->purchase;
            $oldStatus = $purchaseReturn->status;
            $newStatus = $request->status;

            // ── STEP 1: Pre-check quantities & stock before touching anything ──
            $alreadyReturnedMap = $this->getAlreadyReturnedMap($purchase->id);

            $itemsToProcess = [];
            $subTotal = 0;
            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $purchaseItem = $purchase->items()->where('product_id', $item['product_id'])->first();
                if (!$purchaseItem) continue;

                // Max returnable = original qty - already returned by OTHER returns (not this one)
                $alreadyByOthers = PurchaseReturnItem::whereHas('purchaseReturn', function ($q) use ($purchase, $purchaseReturn) {
                    $q->where('purchase_id', $purchase->id)
                      ->where('id', '!=', $purchaseReturn->id)
                      ->where('status', 'Completed');
                })->where('product_id', $item['product_id'])->sum('quantity');

                $maxReturnable = $purchaseItem->quantity - $alreadyByOthers;

                if ($qty > $maxReturnable) {
                    DB::rollBack();
                    return back()->withInput()->with('error',
                        "Cannot return {$qty} unit(s) for \"{$purchaseItem->product->name}\". Max returnable: {$maxReturnable}.");
                }

                // If new status is Completed, check stock after reversing old return
                if ($newStatus === 'Completed') {
                    $oldReturnedQty = $purchaseReturn->items->where('product_id', $item['product_id'])->sum('quantity');
                    $stockAfterReversal = ($purchaseItem->product->stock->quantity ?? 0) + ($oldStatus === 'Completed' ? $oldReturnedQty : 0);
                    if ($stockAfterReversal < $qty) {
                        DB::rollBack();
                        return back()->withInput()->with('error',
                            "Insufficient stock for \"{$purchaseItem->product->name}\". Available after reversal: {$stockAfterReversal}, trying to return: {$qty}.");
                    }
                }

                $itemTotal = $qty * $purchaseItem->purchase_price;
                $subTotal += $itemTotal;
                $itemsToProcess[] = [
                    'product_id'     => $item['product_id'],
                    'quantity'       => $qty,
                    'purchase_price' => $purchaseItem->purchase_price,
                    'total_amount'   => $itemTotal,
                    'reason'         => $item['reason'] ?? null,
                ];
            }

            // ── STEP 2: Reverse previous stock ──
            if ($oldStatus === 'Completed') {
                foreach ($purchaseReturn->items as $oldItem) {
                    $oldItem->product->stock->increment('quantity', $oldItem->quantity);
                }
            }

            // ── STEP 3: Delete old items ──
            $purchaseReturn->items()->delete();

            // ── STEP 4: Create new items and apply new stock ──
            foreach ($itemsToProcess as $item) {
                $purchaseReturn->items()->create($item);

                if ($newStatus === 'Completed') {
                    $product = Product::findOrFail($item['product_id']);
                    $product->stock->decrement('quantity', $item['quantity']);
                }
            }

            $purchaseReturn->update([
                'return_date'     => $request->return_date,
                'reference_no'    => $request->reference_no,
                'sub_total'       => $subTotal,
                'grand_total'     => $subTotal,
                'refunded_amount' => (float) $request->refunded_amount,
                'notes'           => $request->notes,
                'status'          => $newStatus,
            ]);

            DB::commit();
            ActivityLog::log('Purchase Return Updated', "Updated purchase return: {$purchaseReturn->return_no}");

            return redirect()->route('purchase-returns.index')->with('success', 'Purchase return updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified purchase return.
     */
    public function destroy(PurchaseReturn $purchaseReturn, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('purchase_returns.delete');

        DB::beginTransaction();
        try {
            // Reverse stock: add back what was returned if status was Completed
            if ($purchaseReturn->status === 'Completed') {
                foreach ($purchaseReturn->items as $item) {
                    $item->product->stock->increment('quantity', $item->quantity);
                }
            }

            $returnNo = $purchaseReturn->return_no;
            $purchaseReturn->delete();

            DB::commit();
            ActivityLog::log('Purchase Return Deleted', "Deleted purchase return: {$returnNo}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Purchase return deleted.']);
            }

            return redirect()->route('purchase-returns.index')->with('success', 'Purchase return deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Print the purchase return receipt.
     */
    public function printReturn(PurchaseReturn $purchaseReturn): View
    {
        Gate::authorize('purchase_returns.view');
        $purchaseReturn->load(['items.product', 'supplier', 'user', 'purchase']);
        ActivityLog::log('Purchase Return Printed', "Printed return: {$purchaseReturn->return_no}");
        return view('purchase_returns.print', compact('purchaseReturn'));
    }

    /**
     * AJAX: Fetch purchase items with already-returned quantities for the return form.
     */
    public function getPurchaseReturnData(Purchase $purchase): JsonResponse
    {
        Gate::authorize('purchase_returns.create');

        $purchase->load('items.product.stock');

        $alreadyReturnedMap = $this->getAlreadyReturnedMap($purchase->id);

        $data = [];
        foreach ($purchase->items as $item) {
            $product        = $item->product;
            $alreadyRet     = $alreadyReturnedMap->get($product->id, 0);
            $maxReturnable  = max(0, $item->quantity - $alreadyRet);

            $data[] = [
                'product_id'       => $product->id,
                'name'             => $product->name,
                'purchased_qty'    => (float) $item->quantity,
                'already_returned' => (float) $alreadyRet,
                'max_returnable'   => (float) $maxReturnable,
                'stock'            => (float) ($product->stock->quantity ?? 0),
                'unit_price'       => (float) $item->purchase_price,
            ];
        }

        return response()->json($data);
    }

    /**
     * Get a map of product_id => total already returned (Completed only) for a given purchase.
     *
     * @return \Illuminate\Support\Collection<int, float>
     */
    private function getAlreadyReturnedMap(int $purchaseId)
    {
        return PurchaseReturnItem::whereHas('purchaseReturn', function ($q) use ($purchaseId) {
            $q->where('purchase_id', $purchaseId)->where('status', 'Completed');
        })->get()->groupBy('product_id')->map(fn($group) => $group->sum('quantity'));
    }

    /**
     * Generate a concurrent-safe unique return number.
     */
    private function generateReturnNo(): string
    {
        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $latest  = PurchaseReturn::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum = $latest ? ((int) substr($latest->return_no, -5)) + 1 : 1;
            $returnNo = 'PRET-' . date('Ymd') . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            if (!PurchaseReturn::where('return_no', $returnNo)->exists()) {
                return $returnNo;
            }
        }
        return 'PRET-' . date('Ymd') . '-' . uniqid();
    }
}
