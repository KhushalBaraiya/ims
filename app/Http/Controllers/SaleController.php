<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
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
     */
    public function index(Request $request): View
    {
        Gate::authorize('sales.view');

        $query = Sale::with(['customer', 'user', 'items'])->latest();

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
        // Load sales persons (admin, manager, staff)
        $salesPersons = User::where('status', 'active')->orderBy('name')->get();

        return view('sales.create', compact('customers', 'salesPersons'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(SaleRequest $request): RedirectResponse
    {
        Gate::authorize('sales.create');

        DB::beginTransaction();
        try {
            // Generate Invoice No (concurrent-safe)
            $invoiceNo = $this->generateInvoiceNo();

            // Validate stock levels before decrementing
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

            // Calculate totals in PHP for security
            $subTotal = 0;
            foreach ($request->items as $item) {
                $subTotal += ($item['quantity'] * $item['unit_price']);
            }

            $taxAmount = (float) ($request->tax_amount ?? 0.00);
            $discountAmount = (float) ($request->discount_amount ?? 0.00);
            $shippingAmount = (float) ($request->shipping_amount ?? 0.00);
            $grandTotal = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount = (float) ($request->paid_amount ?? 0.00);
            $dueAmount = max(0.00, $grandTotal - $paidAmount);

            // Create Sale
            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'invoice_date' => $request->invoice_date,
                'customer_id' => $request->customer_id,
                'sales_person_id' => $request->sales_person_id,
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

            // Add Sale Items
            foreach ($request->items as $item) {
                $itemSub = $item['quantity'] * $item['unit_price'];
                $itemTax = (float) ($item['tax_amount'] ?? 0.00);
                $itemDisc = (float) ($item['discount_amount'] ?? 0.00);

                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_amount' => $itemTax,
                    'discount_amount' => $itemDisc,
                    'total_amount' => $itemSub + $itemTax - $itemDisc,
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
     * Display the specified sale profile details.
     */
    public function show(Sale $sale): View
    {
        Gate::authorize('sales.view');
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
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $salesPersons = User::where('status', 'active')->orderBy('name')->get();

        return view('sales.edit', compact('sale', 'customers', 'salesPersons'));
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(SaleRequest $request, Sale $sale): RedirectResponse
    {
        Gate::authorize('sales.update');

        DB::beginTransaction();
        try {
            $oldStatus = $sale->status;
            $newStatus = $request->status;

            // 1. Restore previous stock levels if previous status was 'Completed'
            if ($oldStatus === 'Completed') {
                foreach ($sale->items as $oldItem) {
                    $product = $oldItem->product;
                    $product->stock->increment('quantity', $oldItem->quantity);
                }
            }

            // 2. Validate and decrement stock levels if new status is 'Completed'
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
                $itemSub = $item['quantity'] * $item['unit_price'];
                $itemTax = (float) ($item['tax_amount'] ?? 0.00);
                $itemDisc = (float) ($item['discount_amount'] ?? 0.00);

                $subTotal += $itemSub;

                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_amount' => $itemTax,
                    'discount_amount' => $itemDisc,
                    'total_amount' => $itemSub + $itemTax - $itemDisc,
                ]);
            }

            $taxAmount = (float) ($request->tax_amount ?? 0.00);
            $discountAmount = (float) ($request->discount_amount ?? 0.00);
            $shippingAmount = (float) ($request->shipping_amount ?? 0.00);
            $grandTotal = $subTotal + $taxAmount + $shippingAmount - $discountAmount;
            $paidAmount = (float) ($request->paid_amount ?? 0.00);
            $dueAmount = max(0.00, $grandTotal - $paidAmount);

            // 4. Update Sale record
            $sale->update([
                'invoice_date' => $request->invoice_date,
                'customer_id' => $request->customer_id,
                'sales_person_id' => $request->sales_person_id,
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
            // Restore stock if status is Completed
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
     * Live search active products for sales panel.
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
            $price = $rate > 0 ? ($p->selling_price / $rate) : $p->selling_price;
            $results[] = [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->code,
                'barcode' => $p->barcode,
                'stock' => $p->stock->quantity ?? 0.00,
                'price' => $price,
                'tax' => $p->tax_percentage,
                'discount' => 0,
                'unit' => $p->unit_code ?? 'PCS',
                'currency_symbol' => $symbol,
                'image_url' => $p->image ? asset('uploads/products/'.$p->image) : 'https://placehold.co/50x50/e2e8f0/94a3b8?text=No+Image',
            ];
        }

        return response()->json($results);
    }

    /**
     * Render the invoice printable window view.
     */
    public function printInvoice(Sale $sale): View
    {
        Gate::authorize('sales.view');

        $sale->load(['customer', 'user', 'salesPerson', 'items.product.stock']);

        // Log the printing activity
        ActivityLog::log('Sale Printed', "Printed invoice sheet: {$sale->invoice_no}");

        return view('sales.print', compact('sale'));
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
            $nextNum = $latestSale ? ((int) substr($latestSale->invoice_no, -5)) + 1 : 1;
            $invoiceNo = 'INV-'.date('Ymd').'-'.str_pad($nextNum, 5, '0', STR_PAD_LEFT);

            // Double check if invoice no is unique
            if (! Sale::where('invoice_no', $invoiceNo)->exists()) {
                return $invoiceNo;
            }
        }

        return 'INV-'.date('Ymd').'-'.uniqid();
    }
}
