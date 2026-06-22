<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Display a listing of all purchases.
     */
    public function index(Request $request): View
    {
        Gate::authorize('purchases.view');

        $query = Purchase::with(['supplier', 'user', 'items'])->latest();

        // Apply filters
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

        $purchases  = $query->get();
        $suppliers  = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show form for creating a new purchase.
     */
    public function create(): View
    {
        Gate::authorize('purchases.create');

        $suppliers       = Supplier::where('status', 'active')->orderBy('name')->get();
        $purchasePersons = User::where('status', 'active')->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'purchasePersons'));
    }

    /**
     * Store a newly created purchase in storage.
     */
    public function store(PurchaseRequest $request): RedirectResponse
    {
        Gate::authorize('purchases.create');

        DB::beginTransaction();
        try {
            // Generate unique purchase number
            $purchaseNo = $this->generatePurchaseNo();

            // Calculate totals server-side
            $subTotal = 0;
            foreach ($request->items as $item) {
                $subTotal += ($item['quantity'] * $item['purchase_price']);
            }

            $taxAmount      = (float) ($request->tax_amount ?? 0.00);
            $discountAmount = (float) ($request->discount_amount ?? 0.00);
            $shippingAmount = (float) ($request->shipping_amount ?? 0.00);
            $grandTotal     = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount     = (float) ($request->paid_amount ?? 0.00);
            $dueAmount      = max(0.00, $grandTotal - $paidAmount);

            // Create Purchase record
            $purchase = Purchase::create([
                'purchase_no'        => $purchaseNo,
                'purchase_date'      => $request->purchase_date,
                'supplier_id'        => $request->supplier_id,
                'purchase_person_id' => $request->purchase_person_id,
                'reference_no'       => $request->reference_no,
                'invoice_no'         => $request->invoice_no,
                'invoice_date'       => $request->invoice_date,
                'sub_total'          => $subTotal,
                'tax_amount'         => $taxAmount,
                'discount_amount'    => $discountAmount,
                'shipping_amount'    => $shippingAmount,
                'grand_total'        => $grandTotal,
                'paid_amount'        => $paidAmount,
                'due_amount'         => $dueAmount,
                'payment_method'     => $request->payment_method,
                'notes'              => $request->notes,
                'status'             => $request->status,
                'user_id'            => auth()->id(),
            ]);

            // Create line items and update stock if Completed
            foreach ($request->items as $item) {
                $qty        = (float) $item['quantity'];
                $price      = (float) $item['purchase_price'];
                $itemDisc   = (float) ($item['discount_amount'] ?? 0.00);
                $itemTax    = (float) ($item['tax_amount'] ?? 0.00);
                $itemTotal  = ($qty * $price) + $itemTax - $itemDisc;

                $purchase->items()->create([
                    'product_id'      => $item['product_id'],
                    'quantity'        => $qty,
                    'purchase_price'  => $price,
                    'discount_amount' => $itemDisc,
                    'tax_amount'      => $itemTax,
                    'total_amount'    => $itemTotal,
                ]);

                // Stock increases when purchase is Completed
                if ($request->status === 'Completed') {
                    $product = Product::findOrFail($item['product_id']);
                    $product->stock->increment('quantity', $qty);
                }
            }

            DB::commit();
            ActivityLog::log('Purchase Created', "Created purchase order: {$purchase->purchase_no} from Supplier: {$purchase->supplier->name}");

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
        $purchase->load(['supplier', 'user', 'purchasePerson', 'items.product.stock']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing a purchase.
     */
    public function edit(Purchase $purchase): View
    {
        Gate::authorize('purchases.update');

        $purchase->load(['items.product.stock']);
        $suppliers       = Supplier::where('status', 'active')->orderBy('name')->get();
        $purchasePersons = User::where('status', 'active')->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'purchasePersons'));
    }

    /**
     * Update the specified purchase in storage.
     */
    public function update(PurchaseRequest $request, Purchase $purchase): RedirectResponse
    {
        Gate::authorize('purchases.update');

        DB::beginTransaction();
        try {
            $oldStatus = $purchase->status;
            $newStatus = $request->status;

            // 1. Reverse previous stock if old status was Completed
            if ($oldStatus === 'Completed') {
                foreach ($purchase->items as $oldItem) {
                    $oldItem->product->stock->decrement('quantity', $oldItem->quantity);
                    // Guard: stock must not go negative
                    if ($oldItem->product->stock->quantity < 0) {
                        throw new \Exception("Reversing stock for \"{$oldItem->product->name}\" would cause negative inventory.");
                    }
                }
            }

            // 2. Delete old line items
            $purchase->items()->delete();

            // 3. Recalculate and create new items
            $subTotal = 0;
            foreach ($request->items as $item) {
                $qty       = (float) $item['quantity'];
                $price     = (float) $item['purchase_price'];
                $itemDisc  = (float) ($item['discount_amount'] ?? 0.00);
                $itemTax   = (float) ($item['tax_amount'] ?? 0.00);
                $itemTotal = ($qty * $price) + $itemTax - $itemDisc;

                $subTotal += ($qty * $price);

                $purchase->items()->create([
                    'product_id'      => $item['product_id'],
                    'quantity'        => $qty,
                    'purchase_price'  => $price,
                    'discount_amount' => $itemDisc,
                    'tax_amount'      => $itemTax,
                    'total_amount'    => $itemTotal,
                ]);

                // 4. Apply new stock if new status is Completed
                if ($newStatus === 'Completed') {
                    $product = Product::findOrFail($item['product_id']);
                    $product->stock->increment('quantity', $qty);
                }
            }

            $taxAmount      = (float) ($request->tax_amount ?? 0.00);
            $discountAmount = (float) ($request->discount_amount ?? 0.00);
            $shippingAmount = (float) ($request->shipping_amount ?? 0.00);
            $grandTotal     = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount     = (float) ($request->paid_amount ?? 0.00);
            $dueAmount      = max(0.00, $grandTotal - $paidAmount);

            // 5. Update purchase header
            $purchase->update([
                'purchase_date'      => $request->purchase_date,
                'supplier_id'        => $request->supplier_id,
                'purchase_person_id' => $request->purchase_person_id,
                'reference_no'       => $request->reference_no,
                'invoice_no'         => $request->invoice_no,
                'invoice_date'       => $request->invoice_date,
                'sub_total'          => $subTotal,
                'tax_amount'         => $taxAmount,
                'discount_amount'    => $discountAmount,
                'shipping_amount'    => $shippingAmount,
                'grand_total'        => $grandTotal,
                'paid_amount'        => $paidAmount,
                'due_amount'         => $dueAmount,
                'payment_method'     => $request->payment_method,
                'notes'              => $request->notes,
                'status'             => $newStatus,
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
     */
    public function destroy(Purchase $purchase, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('purchases.delete');

        DB::beginTransaction();
        try {
            // Reverse stock if purchase was Completed
            if ($purchase->status === 'Completed') {
                foreach ($purchase->items as $item) {
                    $item->product->stock->decrement('quantity', $item->quantity);
                    if ($item->product->stock->quantity < 0) {
                        throw new \Exception("Deleting this purchase would cause negative stock for \"{$item->product->name}\".");
                    }
                }
            }

            $purchaseNo = $purchase->purchase_no;
            $purchase->delete();

            DB::commit();
            ActivityLog::log('Purchase Deleted', "Deleted purchase order: {$purchaseNo}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase order deleted successfully.',
                ]);
            }

            return redirect()->route('purchases.index')->with('success', 'Purchase order deleted successfully.');
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
     * Render a printable purchase order invoice.
     */
    public function printInvoice(Purchase $purchase): View
    {
        Gate::authorize('purchases.view');
        $purchase->load(['supplier', 'user', 'purchasePerson', 'items.product.stock']);
        ActivityLog::log('Purchase Printed', "Printed purchase order: {$purchase->purchase_no}");
        return view('purchases.print', compact('purchase'));
    }

    /**
     * Live AJAX product search for purchase form — returns purchase_price in payload.
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
                'id'              => $p->id,
                'name'            => $p->name,
                'sku'             => $p->code,
                'barcode'         => $p->barcode,
                'stock'           => $p->stock->quantity ?? 0.00,
                'purchase_price'  => $purchasePrice,
                'selling_price'   => $sellingPrice,
                'tax'             => $p->tax_percentage,
                'discount'        => $p->discount_percentage,
                'unit'            => $p->unit_code ?? 'PCS',
                'currency_symbol' => $symbol,
                'image_url'       => $p->image
                    ? asset('uploads/products/' . $p->image)
                    : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image',
            ];
        }

        return response()->json($results);
    }

    /**
     * Generate a concurrent-safe unique purchase number.
     */
    private function generatePurchaseNo(): string
    {
        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $latest  = Purchase::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum = $latest ? ((int) substr($latest->purchase_no, -5)) + 1 : 1;
            $no      = 'PUR-' . date('Ymd') . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            if (!Purchase::where('purchase_no', $no)->exists()) {
                return $no;
            }
        }
        return 'PUR-' . date('Ymd') . '-' . uniqid();
    }
}
