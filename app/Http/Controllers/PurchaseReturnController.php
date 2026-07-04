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
     * If ?purchase_id= is passed, the form pre-loads items from that purchase (purchase_id stored hidden).
     * Otherwise the user can freely search any active stock product.
     */
    public function create(Request $request): View
    {
        Gate::authorize('purchase_returns.create');

        $selectedPurchase = null;
        if ($request->filled('purchase_id')) {
            $selectedPurchase = Purchase::with(['supplier', 'items.product.stock'])->findOrFail($request->purchase_id);
        }

        return view('purchase_returns.create', compact('selectedPurchase'));
    }

    /**
     * Store a newly created purchase return.
     *
     * Two modes:
     *  - With purchase_id: validate items belong to that purchase and respect already-returned qty limits.
     *  - Without purchase_id: validate only that current stock >= qty to return.
     */
    public function store(PurchaseReturnRequest $request): RedirectResponse
    {
        Gate::authorize('purchase_returns.create');

        DB::beginTransaction();
        try {
            $purchase = $request->purchase_id ? Purchase::with(['items.product.stock'])->findOrFail($request->purchase_id) : null;
            $alreadyReturnedMap = $purchase ? $this->getAlreadyReturnedMap($purchase->id) : collect();

            // ── STEP 1: Validate quantities & check stock before touching anything ──
            $subTotal       = 0;
            $itemsToProcess = [];

            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $product = Product::with('stock')->findOrFail($item['product_id']);
                $unitPrice = (float) $item['unit_price'];

                if ($purchase) {
                    // Purchase-linked mode: validate against original purchase items
                    $purchaseItem = $purchase->items()->where('product_id', $item['product_id'])->first();
                    if (!$purchaseItem) {
                        throw new \Exception("Product \"{$product->name}\" was not part of the original purchase.");
                    }

                    $alreadyReturned = $alreadyReturnedMap->get((int) $item['product_id'], 0);
                    $maxReturnable   = $purchaseItem->quantity - $alreadyReturned;

                    if ($qty > $maxReturnable) {
                        DB::rollBack();
                        return back()->withInput()->with('error',
                            "Cannot return {$qty} unit(s) for \"{$product->name}\". Max returnable: {$maxReturnable}.");
                    }

                    $unitPrice = (float) $purchaseItem->purchase_price;
                }

                // Stock check for Completed status
                if ($request->status === 'Completed') {
                    $currentStock = $product->stock->quantity ?? 0;
                    if ($currentStock < $qty) {
                        DB::rollBack();
                        return back()->withInput()->with('error',
                            "Insufficient stock to return \"{$product->name}\". Current stock: {$currentStock}, trying to return: {$qty}.");
                    }
                }

                $subTotal += $qty * $unitPrice;
                $itemsToProcess[] = [
                    'product_id'     => $item['product_id'],
                    'quantity'       => $qty,
                    'purchase_price' => $unitPrice,
                    'total_amount'   => $qty * $unitPrice,
                    'reason'         => $item['reason'] ?? null,
                ];
            }

            if (empty($itemsToProcess)) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Please add at least one product with a return quantity of 1 or more.');
            }

            // ── STEP 2: Create the Purchase Return record ──
            $returnNo = $this->generateReturnNo();
            $return = PurchaseReturn::create([
                'return_no'       => $returnNo,
                'return_date'     => $request->return_date,
                'purchase_id'     => $purchase?->id,
                'supplier_id'     => $purchase?->supplier_id,
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

            $logDetail = $purchase
                ? "Created purchase return: {$return->return_no} for PO: {$purchase->purchase_no}"
                : "Created standalone purchase return: {$return->return_no}";
            ActivityLog::log('Purchase Return Created', $logDetail);

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
     *
     * Two modes:
     *  - With linked purchase: validate against original purchase.
     *  - Standalone: validate only stock sufficiency.
     */
    public function update(PurchaseReturnRequest $request, PurchaseReturn $purchaseReturn): RedirectResponse
    {
        Gate::authorize('purchase_returns.update');

        DB::beginTransaction();
        try {
            $purchase  = $purchaseReturn->purchase;
            $oldStatus = $purchaseReturn->status;
            $newStatus = $request->status;

            $itemsToProcess = [];
            $subTotal = 0;

            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) continue;

                $product   = Product::with('stock')->findOrFail($item['product_id']);
                $unitPrice = (float) $item['unit_price'];

                if ($purchase) {
                    // Purchase-linked: enforce original purchase qty limits
                    $purchaseItem = $purchase->items()->where('product_id', $item['product_id'])->first();
                    if (!$purchaseItem) continue;

                    $alreadyByOthers = PurchaseReturnItem::whereHas('purchaseReturn', function ($q) use ($purchase, $purchaseReturn) {
                        $q->where('purchase_id', $purchase->id)
                          ->where('id', '!=', $purchaseReturn->id)
                          ->where('status', 'Completed');
                    })->where('product_id', $item['product_id'])->sum('quantity');

                    $maxReturnable = $purchaseItem->quantity - $alreadyByOthers;

                    if ($qty > $maxReturnable) {
                        DB::rollBack();
                        return back()->withInput()->with('error',
                            "Cannot return {$qty} unit(s) for \"{$product->name}\". Max returnable: {$maxReturnable}.");
                    }

                    $unitPrice = (float) $purchaseItem->purchase_price;

                    // Stock check after reversing old completed return
                    if ($newStatus === 'Completed') {
                        $oldReturnedQty     = $purchaseReturn->items->where('product_id', $item['product_id'])->sum('quantity');
                        $stockAfterReversal = ($product->stock->quantity ?? 0) + ($oldStatus === 'Completed' ? $oldReturnedQty : 0);
                        if ($stockAfterReversal < $qty) {
                            DB::rollBack();
                            return back()->withInput()->with('error',
                                "Insufficient stock for \"{$product->name}\". Available after reversal: {$stockAfterReversal}, trying to return: {$qty}.");
                        }
                    }
                } else {
                    // Standalone: just check stock
                    if ($newStatus === 'Completed') {
                        $oldReturnedQty     = $purchaseReturn->items->where('product_id', $item['product_id'])->sum('quantity');
                        $stockAfterReversal = ($product->stock->quantity ?? 0) + ($oldStatus === 'Completed' ? $oldReturnedQty : 0);
                        if ($stockAfterReversal < $qty) {
                            DB::rollBack();
                            return back()->withInput()->with('error',
                                "Insufficient stock for \"{$product->name}\". Available after reversal: {$stockAfterReversal}, trying to return: {$qty}.");
                        }
                    }
                }

                $itemTotal = $qty * $unitPrice;
                $subTotal += $itemTotal;
                $itemsToProcess[] = [
                    'product_id'     => $item['product_id'],
                    'quantity'       => $qty,
                    'purchase_price' => $unitPrice,
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
     * Bulk delete purchase returns.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('purchase_returns.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $deleted = 0;
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $ret = PurchaseReturn::with('items.product.stock')->find($id);
                if (!$ret) continue;
                if ($ret->status === 'Completed') {
                    foreach ($ret->items as $item) {
                        $item->product->stock->increment('quantity', $item->quantity);
                    }
                }
                $ret->delete();
                $deleted++;
            }
            DB::commit();
            ActivityLog::log('Purchase Returns Bulk Deleted', "Deleted {$deleted} purchase return(s).");
            return response()->json(['success' => true, 'message' => "{$deleted} purchase return(s) deleted successfully."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
     * AJAX: Live product search for the purchase return form (free-mode, all active products with stock).
     */
    public function searchProducts(Request $request): JsonResponse
    {
        Gate::authorize('purchase_returns.create');

        $query = $request->get('query', '');
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $products = Product::with(['stock'])
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        $activeCurrency = current_currency();
        $rate   = $activeCurrency ? $activeCurrency->exchange_rate : 1.0;
        $symbol = $activeCurrency ? $activeCurrency->symbol : '₹';

        $results = [];
        foreach ($products as $p) {
            $purchasePrice = $rate > 0 ? ($p->purchase_price / $rate) : $p->purchase_price;
            $stock         = $p->stock->quantity ?? 0;
            $results[] = [
                'id'             => $p->id,
                'name'           => $p->name,
                'sku'            => $p->code,
                'barcode'        => $p->barcode,
                'stock'          => (float) $stock,
                'purchase_price' => (float) $purchasePrice,
                'unit'           => $p->unit_code ?? 'PCS',
                'currency_symbol' => $symbol,
                'image_url'      => $p->image
                    ? asset('uploads/products/' . $p->image)
                    : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image',
            ];
        }

        return response()->json($results);
    }

    /**
     * AJAX: Fetch purchase items with already-returned quantities for the purchase-linked return form.
     */
    public function getPurchaseReturnData(Purchase $purchase, Request $request): JsonResponse
    {
        if (!Gate::allows('purchase_returns.create') && !Gate::allows('purchase_returns.update')) {
            abort(403);
        }

        $purchase->load('items.product.stock');

        $excludeReturnId    = $request->query('exclude_return_id');
        $alreadyReturnedMap = $this->getAlreadyReturnedMap($purchase->id, $excludeReturnId);

        $data = [];
        foreach ($purchase->items as $item) {
            $product       = $item->product;
            $alreadyRet    = $alreadyReturnedMap->get($product->id, 0);
            $maxReturnable = max(0, $item->quantity - $alreadyRet);

            $data[] = [
                'product_id'       => $product->id,
                'name'             => $product->name,
                'sku'              => $product->code,
                'purchased_qty'    => (float) $item->quantity,
                'already_returned' => (float) $alreadyRet,
                'max_returnable'   => (float) $maxReturnable,
                'stock'            => (float) ($product->stock->quantity ?? 0),
                'unit_price'       => (float) $item->purchase_price,
                'image_url'        => $product->image ? asset('uploads/products/' . $product->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image',
            ];
        }

        return response()->json($data);
    }

    /**
     * Get a map of product_id => total already returned (Completed only) for a given purchase.
     *
     * @return \Illuminate\Support\Collection<int, float>
     */
    private function getAlreadyReturnedMap(int $purchaseId, $excludeReturnId = null)
    {
        return PurchaseReturnItem::whereHas('purchaseReturn', function ($q) use ($purchaseId, $excludeReturnId) {
            $q->where('purchase_id', $purchaseId)->where('status', 'Completed');
            if ($excludeReturnId) {
                $q->where('id', '!=', $excludeReturnId);
            }
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
            $latest   = PurchaseReturn::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum  = $latest ? ((int) substr($latest->return_no, -5)) + 1 : 1;
            $returnNo = 'PRET-' . date('Ymd') . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            if (!PurchaseReturn::where('return_no', $returnNo)->exists()) {
                return $returnNo;
            }
        }
        return 'PRET-' . date('Ymd') . '-' . uniqid();
    }
}
