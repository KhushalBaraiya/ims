<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Display a listing of all purchases.
     */
    public function index(Request $request): View
    {
        Gate::authorize('purchases.view');

        $query = Purchase::with(['supplier', 'user', 'items', 'returns'])->latest();

        if ($request->filled('purchase_no')) {
            $query->where('purchase_no', 'like', "%{$request->purchase_no}%");
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }

        $purchases = $query->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show form for creating a new purchase.
     */
    public function create(): View
    {
        Gate::authorize('purchases.create');

        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();
        $purchaseNo = $this->generatePurchaseNo();

        return view('purchases.create', compact('suppliers', 'purchaseNo'));
    }

    /**
     * Ajax route to generate unique purchase number.
     */
    public function generateNoAjax(): JsonResponse
    {
        return response()->json(['purchase_no' => $this->generatePurchaseNo()]);
    }

    /**
     * Update payment details for a purchase order.
     */
    public function updatePayment(Request $request, Purchase $purchase): RedirectResponse|JsonResponse
    {
        Gate::authorize('purchases.update');

        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
        ]);

        $paidAmount = (float) $request->paid_amount;
        $grandTotal = (float) $purchase->grand_total;
        $dueAmount = max(0.00, $grandTotal - $paidAmount);

        $purchase->update([
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_method' => $request->payment_method,
        ]);

        ActivityLog::log(
            'Purchase Payment Updated',
            "Updated payment for purchase: {$purchase->purchase_no}. Paid: {$paidAmount}, Due: {$dueAmount}"
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully.',
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Store a newly created purchase in storage.
     * Stock is incremented for each item when status = Completed.
     */
    public function store(PurchaseRequest $request): RedirectResponse
    {
        Gate::authorize('purchases.create');

        DB::beginTransaction();
        try {
            $purchaseNo = $request->input('purchase_no') ?: $this->generatePurchaseNo();

            $subTotal = 0;
            foreach ($request->items as $item) {
                $subTotal += ((float) $item['quantity'] * (float) $item['purchase_price']);
            }

            $taxAmount = (float) ($request->tax_amount ?? 0);
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $shippingAmount = (float) ($request->shipping_amount ?? 0);
            $grandTotal = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount = (float) ($request->paid_amount ?? 0);
            $dueAmount = max(0.00, $grandTotal - $paidAmount);

            $purchase = Purchase::create([
                'purchase_no' => $purchaseNo,
                'purchase_date' => $request->purchase_date,
                'supplier_id' => $request->supplier_id,
                'reference_no' => $request->reference_no,
                'sub_total' => $subTotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => $request->status,
                'user_id' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['purchase_price'];
                $itemDisc = (float) ($item['discount_amount'] ?? 0);
                $itemTax = (float) ($item['tax_amount'] ?? 0);
                $itemTotal = ($qty * $price) + $itemTax - $itemDisc;

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'purchase_price' => $price,
                    'discount_amount' => $itemDisc,
                    'tax_amount' => $itemTax,
                    'total_amount' => $itemTotal,
                ]);

                // Only add stock for Completed purchases
                if ($request->status === 'Completed') {
                    $product = Product::with('stock')->findOrFail($item['product_id']);
                    $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                    $stock->increment('quantity', $qty);
                }
            }

            DB::commit();
            ActivityLog::log(
                'Purchase Created',
                "Created purchase order: {$purchase->purchase_no} from Supplier: {$purchase->supplier->name}"
            );

            return redirect()->route('purchases.index')->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified purchase details.
     */
    public function show(Purchase $purchase): View
    {
        Gate::authorize('purchases.view');
        $purchase->load(['supplier', 'user', 'items.product.stock']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing a purchase.
     */
    public function edit(Purchase $purchase): View
    {
        Gate::authorize('purchases.update');

        $purchase->load(['items.product.stock']);
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    /**
     * Update the specified purchase in storage.
     *
     * Stock logic:
     *  - Only if old status was Completed: check net stock change per product.
     *    If (current_stock - old_qty + new_qty) < 0 → reject with a stock error.
     *    This means we only block when the net result goes negative — increasing
     *    qty is always fine, decreasing is only blocked if it would cause negative stock.
     *  - Re-create items with new quantities.
     *  - If new status is Completed → add new stock quantities.
     */
    public function update(PurchaseRequest $request, Purchase $purchase): RedirectResponse
    {
        Gate::authorize('purchases.update');

        DB::beginTransaction();
        try {
            $oldStatus = $purchase->status;
            $newStatus = $request->status;

            // Build a map of product_id => new_qty from the incoming request
            $newQtyMap = [];
            foreach ($request->items as $item) {
                $newQtyMap[(int) $item['product_id']] = (float) $item['quantity'];
            }

            // ── STEP 1: Pre-check — only fail when stock would go net-negative ──
            // net = current_stock - old_qty + new_qty
            // We only care when old status was Completed (stock was previously added).
            if ($oldStatus === 'Completed') {
                foreach ($purchase->items as $oldItem) {
                    $currentQty = $oldItem->product->stock->quantity ?? 0;
                    $oldQty = $oldItem->quantity;
                    $newQty = $newQtyMap[$oldItem->product_id] ?? 0;
                    $netStock = $currentQty - $oldQty + $newQty;

                    // Only block if net result is negative (i.e. reducing qty causes issue)
                    if ($netStock < 0) {
                        DB::rollBack();
                        $msg = "Cannot update purchase: stock for \"{$oldItem->product->name}\" "
                             .'would go negative. '
                             ."Current stock: {$currentQty}, old purchase qty: {$oldQty}, new qty: {$newQty}.";

                        return back()->withInput()->withErrors(['stock_error' => $msg]);
                    }
                }
            }

            // ── STEP 2: Reverse old stock ──
            if ($oldStatus === 'Completed') {
                foreach ($purchase->items as $oldItem) {
                    $oldItem->product->stock->decrement('quantity', $oldItem->quantity);
                }
            }

            // ── STEP 3: Delete old line items ──
            $purchase->items()->delete();

            // ── STEP 4: Create new items and apply new stock ──
            $subTotal = 0;
            foreach ($request->items as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['purchase_price'];
                $itemDisc = (float) ($item['discount_amount'] ?? 0);
                $itemTax = (float) ($item['tax_amount'] ?? 0);
                $itemTotal = ($qty * $price) + $itemTax - $itemDisc;
                $subTotal += ($qty * $price);

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'purchase_price' => $price,
                    'discount_amount' => $itemDisc,
                    'tax_amount' => $itemTax,
                    'total_amount' => $itemTotal,
                ]);

                if ($newStatus === 'Completed') {
                    $product = Product::with('stock')->findOrFail($item['product_id']);
                    $stock = $product->stock ?? $product->stock()->create(['quantity' => 0]);
                    $stock->increment('quantity', $qty);
                }
            }

            $taxAmount = (float) ($request->tax_amount ?? 0);
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $shippingAmount = (float) ($request->shipping_amount ?? 0);
            $grandTotal = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount = (float) ($request->paid_amount ?? 0);
            $dueAmount = max(0.00, $grandTotal - $paidAmount);

            $purchase->update([
                'purchase_date' => $request->purchase_date,
                'supplier_id' => $request->supplier_id,
                'reference_no' => $request->reference_no,
                'sub_total' => $subTotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_amount' => $shippingAmount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => $newStatus,
            ]);

            DB::commit();
            ActivityLog::log('Purchase Updated', "Updated purchase order: {$purchase->purchase_no}");

            return redirect()->route('purchases.index')->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified purchase from storage.
     *
     * Stock logic:
     *  - Pre-check all items won't go negative before touching anything.
     *  - If safe, reverse stock then soft-delete.
     */
    public function destroy(Purchase $purchase, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('purchases.delete');

        DB::beginTransaction();
        try {
            // ── Pre-check: will reversing stock go negative? ──
            if ($purchase->status === 'Completed') {
                foreach ($purchase->items as $item) {
                    $currentQty = $item->product->stock->quantity ?? 0;
                    $afterReversal = $currentQty - $item->quantity;
                    if ($afterReversal < 0) {
                        DB::rollBack();
                        $msg = "Cannot delete purchase: reversing stock for \"{$item->product->name}\" "
                             ."would result in negative stock ({$afterReversal}). "
                             ."Current stock: {$currentQty}, purchase qty: {$item->quantity}.";

                        if ($request->ajax() || $request->wantsJson()) {
                            return response()->json(['success' => false, 'message' => $msg], 422);
                        }

                        return back()->withErrors(['stock_error' => $msg]);
                    }
                }

                // ── Safe — reverse stock ──
                foreach ($purchase->items as $item) {
                    $item->product->stock->decrement('quantity', $item->quantity);
                }
            }

            $purchaseNo = $purchase->purchase_no;
            $purchase->delete();

            DB::commit();
            ActivityLog::log('Purchase Deleted', "Deleted purchase order: {$purchaseNo}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Purchase order deleted successfully.']);
            }

            return redirect()->route('purchases.index')->with('success', 'Purchase order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Bulk delete purchases.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('purchases.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $deleted = 0;
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $purchase = Purchase::with('items.product.stock')->find($id);
                if (!$purchase) continue;
                if ($purchase->status === 'Completed') {
                    foreach ($purchase->items as $item) {
                        $currentQty = $item->product->stock->quantity ?? 0;
                        if (($currentQty - $item->quantity) < 0) {
                            throw new \Exception("Cannot delete: reversing stock for \"{$item->product->name}\" would go negative.");
                        }
                    }
                    foreach ($purchase->items as $item) {
                        $item->product->stock->decrement('quantity', $item->quantity);
                    }
                }
                $purchase->delete();
                $deleted++;
            }
            DB::commit();
            ActivityLog::log('Purchases Bulk Deleted', "Deleted {$deleted} purchase(s).");
            return response()->json(['success' => true, 'message' => "{$deleted} purchase(s) deleted successfully."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Render a printable purchase order invoice.
     */
    public function printInvoice(Purchase $purchase): View
    {
        Gate::authorize('purchases.view');
        $purchase->load(['supplier', 'user', 'items.product']);
        ActivityLog::log('Purchase Printed', "Printed purchase order: {$purchase->purchase_no}");

        return view('purchases.print', compact('purchase'));
    }

    /**
     * Live AJAX product search for purchase form.
     */
    public function searchProducts(Request $request): JsonResponse
    {
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
        $rate = $activeCurrency ? $activeCurrency->exchange_rate : 1.0;
        $symbol = $activeCurrency ? $activeCurrency->symbol : '₹';

        $results = [];
        foreach ($products as $p) {
            $purchasePrice = $rate > 0 ? ($p->purchase_price / $rate) : $p->purchase_price;
            $sellingPrice = $rate > 0 ? ($p->selling_price / $rate) : $p->selling_price;
            $results[] = [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->code,
                'barcode' => $p->barcode,
                'stock' => $p->stock->quantity ?? 0,
                'purchase_price' => $purchasePrice,
                'selling_price' => $sellingPrice,
                'tax' => $p->tax_percentage,
                'discount' => 0,
                'unit' => $p->unit_code ?? 'PCS',
                'currency_symbol' => $symbol,
                'image_url' => $p->image
                    ? asset('uploads/products/'.$p->image)
                    : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image',
            ];
        }

        return response()->json($results);
    }

    /**
     * AJAX: Generate a preview purchase number.
     */
    public function generatePurchaseNoAjax(): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('purchases.create');
        return response()->json(['purchase_no' => $this->generatePurchaseNo()]);
    }

    /**
     * Generate a concurrent-safe unique purchase number.
     */
    private function generatePurchaseNo(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $latest = Purchase::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum = $latest ? ((int) substr($latest->purchase_no, -5)) + 1 : 1;
            $no = 'PUR-'.date('Ymd').'-'.str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            if (! Purchase::where('purchase_no', $no)->exists()) {
                return $no;
            }
        }

        return 'PUR-'.date('Ymd').'-'.uniqid();
    }
}
