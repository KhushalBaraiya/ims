<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales resource.
     * If user only has sales.own permission, restrict to their own records.
     */
    public function index(Request $request): View
    {
        // Allow both 'sales.view' (all records) and 'sales.own' (own records only)
        if (! auth()->user()->canAny(['sales.view', 'sales.own'])) {
            abort(403);
        }

        $query = Sale::with(['customer', 'user', 'items', 'returns'])->latest();

        // RBAC: sales.own restricts to records created by the authenticated user
        if (! auth()->user()->can('sales.view') || auth()->user()->hasPermissionTo('sales.own') && ! auth()->user()->hasAnyRole(['super_admin', 'manager'])) {
            $query->where('user_id', auth()->id());
        }

        // Apply filters
        if ($request->filled('invoice_no')) {
            $query->where('invoice_no', 'like', "%{$request->invoice_no}%");
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }

        $sales = $query->get();
        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('sales.index', compact('sales', 'customers'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create(): View
    {
        Gate::authorize('sales.create');

        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $salesPersons = User::where('status', 'active')->orderBy('name')->get();
        $isSalesOwn = auth()->user()->hasPermissionTo('sales.own')
            && ! auth()->user()->hasAnyRole(['super_admin', 'manager']);
        $showOutOfStock = Setting::where('key', 'show_out_of_stock_products')->value('value') === '1';

        return view('sales.create', compact('customers', 'salesPersons', 'isSalesOwn', 'showOutOfStock'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(SaleRequest $request): RedirectResponse
    {
        Gate::authorize('sales.create');

        DB::beginTransaction();
        try {
            $invoiceNo = $this->generateInvoiceNo();

            // Validate & decrement stock when Completed
            if ($request->status === 'Completed') {
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $stock = $product->stock;
                    if ($stock->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$stock->quantity}.");
                    }
                    $stock->decrement('quantity', $item['quantity']);
                }
            }

            // Calculate row-level subtotal
            $subTotal = 0;
            foreach ($request->items as $item) {
                $subTotal += ($item['quantity'] * $item['unit_price']);
            }

            // Global discount: fixed or percentage
            $discountType  = $request->discount_type ?? 'fixed';
            $discountValue = (float) ($request->discount_value ?? 0.00);
            $discountAmount = $discountType === 'percentage'
                ? round($subTotal * $discountValue / 100, 2)
                : $discountValue;

            // Global tax (percentage applied after discount)
            $taxPercentage = (float) ($request->tax_percentage ?? 0.00);
            $afterDiscount = $subTotal - $discountAmount;
            $taxAmount     = round($afterDiscount * $taxPercentage / 100, 2);

            $shippingAmount = (float) ($request->shipping_amount ?? 0.00);
            $grandTotal     = $afterDiscount + $taxAmount + $shippingAmount;
            $paidAmount     = (float) ($request->paid_amount ?? 0.00);
            $dueAmount      = max(0.00, $grandTotal - $paidAmount);

            // Restrict sales_person_id for sales.own users
            $salesPersonId = auth()->user()->hasPermissionTo('sales.own')
                && ! auth()->user()->hasAnyRole(['super_admin', 'manager'])
                ? auth()->id()
                : $request->sales_person_id;

            $sale = Sale::create([
                'invoice_no'      => $invoiceNo,
                'invoice_date'    => $request->invoice_date,
                'customer_id'     => $request->customer_id,
                'sales_person_id' => $salesPersonId,
                'sub_total'       => $subTotal,
                'tax_amount'      => $taxAmount,
                'discount_amount' => $discountAmount,
                'discount_type'   => $discountType,
                'discount_value'  => $discountValue,
                'tax_percentage'  => $taxPercentage,
                'shipping_amount' => $shippingAmount,
                'grand_total'     => $grandTotal,
                'paid_amount'     => $paidAmount,
                'due_amount'      => $dueAmount,
                'payment_method'  => $request->payment_method,
                'notes'           => $request->notes,
                'status'          => $request->status,
                'user_id'         => auth()->id(),
            ]);

            // Add Sale Items
            foreach ($request->items as $item) {
                $itemSub  = $item['quantity'] * $item['unit_price'];
                $itemTax  = (float) ($item['tax_amount'] ?? 0.00);
                $itemDisc = (float) ($item['discount_amount'] ?? 0.00);

                $sale->items()->create([
                    'product_id'      => $item['product_id'],
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $item['unit_price'],
                    'tax_amount'      => $itemTax,
                    'discount_amount' => $itemDisc,
                    'total_amount'    => $itemSub + $itemTax - $itemDisc,
                ]);
            }

            DB::commit();
            ActivityLog::log('Sale Created', "Created sale invoice: {$sale->invoice_no} for Customer: {$sale->customer->name}");

            return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified sale details.
     */
    public function show(Sale $sale): View
    {
        $this->authorizeSaleAccess($sale);
        $sale->load(['customer', 'user', 'salesPerson', 'items.product.stock']);

        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing a sale.
     */
    public function edit(Sale $sale): View
    {
        Gate::authorize('sales.update');

        $sale->load(['items.product.stock']);
        $customers    = Customer::where('status', 'active')->orderBy('name')->get();
        $salesPersons = User::where('status', 'active')->orderBy('name')->get();
        $isSalesOwn   = auth()->user()->hasPermissionTo('sales.own')
            && ! auth()->user()->hasAnyRole(['super_admin', 'manager']);
        $showOutOfStock = Setting::where('key', 'show_out_of_stock_products')->value('value') === '1';

        return view('sales.edit', compact('sale', 'customers', 'salesPersons', 'isSalesOwn', 'showOutOfStock'));
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(SaleRequest $request, Sale $sale): RedirectResponse
    {
        Gate::authorize('sales.update');

        if ($sale->returns()->exists()) {
            return back()->withInput()->withErrors(['stock_error' => 'This sale has already been returned and cannot be edited.']);
        }

        DB::beginTransaction();
        try {
            $oldStatus = $sale->status;
            $newStatus = $request->status;

            // 1. Restore previous stock if was Completed
            if ($oldStatus === 'Completed') {
                foreach ($sale->items as $oldItem) {
                    $oldItem->product->stock->increment('quantity', $oldItem->quantity);
                }
            }

            // 2. Validate & decrement stock if new status is Completed
            if ($newStatus === 'Completed') {
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    if ($product->stock->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock->quantity}.");
                    }
                    $product->stock->decrement('quantity', $item['quantity']);
                }
            }

            // 3. Clear old items and recreate
            $sale->items()->delete();

            $subTotal = 0;
            foreach ($request->items as $item) {
                $itemSub  = $item['quantity'] * $item['unit_price'];
                $itemTax  = (float) ($item['tax_amount'] ?? 0.00);
                $itemDisc = (float) ($item['discount_amount'] ?? 0.00);
                $subTotal += $itemSub;

                $sale->items()->create([
                    'product_id'      => $item['product_id'],
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $item['unit_price'],
                    'tax_amount'      => $itemTax,
                    'discount_amount' => $itemDisc,
                    'total_amount'    => $itemSub + $itemTax - $itemDisc,
                ]);
            }

            // 4. Recalculate global discount & tax
            $discountType  = $request->discount_type ?? 'fixed';
            $discountValue = (float) ($request->discount_value ?? 0.00);
            $discountAmount = $discountType === 'percentage'
                ? round($subTotal * $discountValue / 100, 2)
                : $discountValue;

            $taxPercentage  = (float) ($request->tax_percentage ?? 0.00);
            $afterDiscount  = $subTotal - $discountAmount;
            $taxAmount      = round($afterDiscount * $taxPercentage / 100, 2);

            $shippingAmount = (float) ($request->shipping_amount ?? 0.00);
            $grandTotal     = $afterDiscount + $taxAmount + $shippingAmount;
            $paidAmount     = (float) ($request->paid_amount ?? 0.00);
            $dueAmount      = max(0.00, $grandTotal - $paidAmount);

            // Restrict sales_person_id for sales.own users
            $salesPersonId = auth()->user()->hasPermissionTo('sales.own')
                && ! auth()->user()->hasAnyRole(['super_admin', 'manager'])
                ? auth()->id()
                : $request->sales_person_id;

            // 5. Update Sale record
            $sale->update([
                'invoice_date'    => $request->invoice_date,
                'customer_id'     => $request->customer_id,
                'sales_person_id' => $salesPersonId,
                'sub_total'       => $subTotal,
                'tax_amount'      => $taxAmount,
                'discount_amount' => $discountAmount,
                'discount_type'   => $discountType,
                'discount_value'  => $discountValue,
                'tax_percentage'  => $taxPercentage,
                'shipping_amount' => $shippingAmount,
                'grand_total'     => $grandTotal,
                'paid_amount'     => $paidAmount,
                'due_amount'      => $dueAmount,
                'payment_method'  => $request->payment_method,
                'notes'           => $request->notes,
                'status'          => $newStatus,
            ]);

            DB::commit();
            ActivityLog::log('Sale Updated', "Updated sale invoice: {$sale->invoice_no}");

            return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('sales.delete');

        DB::beginTransaction();
        try {
            if ($sale->status === 'Completed') {
                foreach ($sale->items as $item) {
                    $item->product->stock->increment('quantity', $item->quantity);
                }
            }

            $invoiceNo = $sale->invoice_no;
            $sale->delete();

            DB::commit();
            ActivityLog::log('Sale Deleted', "Deleted sale invoice: {$invoiceNo}");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sale invoice deleted successfully.',
                ]);
            }

            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
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
     * Update payment details for a sale invoice (AJAX modal).
     */
    public function updatePayment(Request $request, Sale $sale): RedirectResponse|JsonResponse
    {
        Gate::authorize('sales.update');

        $request->validate([
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
        ]);

        $paidAmount  = (float) $request->paid_amount;
        $grandTotal  = (float) $sale->grand_total;
        $dueAmount   = max(0.00, $grandTotal - $paidAmount);

        $sale->update([
            'paid_amount'    => $paidAmount,
            'due_amount'     => $dueAmount,
            'payment_method' => $request->payment_method,
        ]);

        ActivityLog::log(
            'Sale Payment Updated',
            "Updated payment for sale: {$sale->invoice_no}. Paid: {$paidAmount}, Due: {$dueAmount}"
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully.',
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * AJAX: Generate a preview invoice number.
     */
    public function generateInvoiceNoAjax(): JsonResponse
    {
        return response()->json(['invoice_no' => $this->generateInvoiceNo()]);
    }

    /**
     * Live search active products for sales panel.
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = $request->get('query', '');

        $showOutOfStock = Setting::where('key', 'show_out_of_stock_products')->value('value') === '1';

        $productsQuery = Product::with(['stock'])
            ->where('status', 'active')
            ->when(strlen($query) > 0, function ($q) use ($query) {
                $q->where(function ($sq) use ($query) {
                    $sq->where('name', 'like', "%{$query}%")
                        ->orWhere('code', 'like', "%{$query}%")
                        ->orWhere('barcode', 'like', "%{$query}%");
                });
            });

        // If the setting is OFF, only show products with stock > 0
        if (! $showOutOfStock) {
            $productsQuery->whereHas('stock', function ($q) {
                $q->where('quantity', '>', 0);
            });
        }

        $products = $productsQuery->limit($query ? 10 : 50)->get();

        $activeCurrency = current_currency();
        $rate           = $activeCurrency ? $activeCurrency->exchange_rate : 1.0;
        $symbol         = $activeCurrency ? $activeCurrency->symbol : '₹';

        $results = [];
        foreach ($products as $p) {
            $price     = $rate > 0 ? ($p->selling_price / $rate) : $p->selling_price;
            $stockQty  = $p->stock->quantity ?? 0;
            $results[] = [
                'id'              => $p->id,
                'name'            => $p->name,
                'sku'             => $p->code,
                'barcode'         => $p->barcode,
                'stock'           => $stockQty,
                'out_of_stock'    => $stockQty <= 0,
                'price'           => $price,
                'tax_percent'     => $p->tax_percentage,
                'discount_amount' => $p->discount_price_amount ?? 0,
                'unit'            => $p->unit_code ?? 'PCS',
                'currency_symbol' => $symbol,
                'image_url'       => $p->image
                    ? asset('uploads/products/'.$p->image)
                    : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image',
            ];
        }

        return response()->json($results);
    }

    /**
     * Render the invoice printable window view.
     */
    public function printInvoice(Sale $sale): View
    {
        $this->authorizeSaleAccess($sale);
        $sale->load(['customer', 'user', 'salesPerson', 'items.product.stock']);

        ActivityLog::log('Sale Printed', "Printed invoice sheet: {$sale->invoice_no}");

        return view('sales.print', compact('sale'));
    }

    /**
     * Bulk delete sales.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('sales.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $deleted = 0;
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $sale = Sale::with('items.product.stock')->find($id);
                if (!$sale) continue;
                if ($sale->status === 'Completed') {
                    foreach ($sale->items as $item) {
                        $item->product->stock->increment('quantity', $item->quantity);
                    }
                }
                $sale->delete();
                $deleted++;
            }
            DB::commit();
            ActivityLog::log('Sales Bulk Deleted', "Deleted {$deleted} sale(s).");
            return response()->json(['success' => true, 'message' => "{$deleted} sale(s) deleted successfully."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Authorize view/print access — allow if user has sales.view OR (sales.own and owns record).
     */
    private function authorizeSaleAccess(Sale $sale): void
    {
        $user = auth()->user();

        if ($user->hasPermissionTo('sales.own') && ! $user->hasAnyRole(['super_admin', 'manager'])) {
            if ($sale->user_id !== $user->id) {
                abort(403, 'Access denied: you can only view your own sales.');
            }
        } else {
            Gate::authorize('sales.view');
        }
    }

    /**
     * Generate concurrent-safe unique invoice number.
     */
    private function generateInvoiceNo(): string
    {
        $attempts = 0;
        while ($attempts < 10) {
            $attempts++;
            $latestSale = Sale::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum    = $latestSale ? ((int) substr($latestSale->invoice_no, -5)) + 1 : 1;
            $invoiceNo  = 'INV-'.date('Ymd').'-'.str_pad($nextNum, 5, '0', STR_PAD_LEFT);

            if (! Sale::where('invoice_no', $invoiceNo)->exists()) {
                return $invoiceNo;
            }
        }

        return 'INV-'.date('Ymd').'-'.uniqid();
    }
}
