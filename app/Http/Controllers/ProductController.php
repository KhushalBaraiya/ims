<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\ActivityLog;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockAdjustment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request): View
    {
        Gate::authorize('products.view');

        $query = Product::with(['brand', 'mainCategory', 'subCategory', 'stock'])->latest();

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }
        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('price_min')) {
            $query->where('selling_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('selling_price', '<=', $request->price_max);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        $brands        = Brand::where('status', 'active')->orderBy('name')->get();
        $categories    = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();

        return view('products.index', compact('products', 'brands', 'categories', 'subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('products.create');

        $brands        = Brand::where('status', 'active')->orderBy('name')->get();
        $categories    = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();
        $suppliers     = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('products.create', compact('brands', 'categories', 'subCategories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * If the user checks "Add stock while creating product", we:
     *  1. Create a Stock row with the given initial qty.
     *  2. Create a Purchase (Completed) + PurchaseItem for traceability.
     *  3. Log a StockAdjustment (Restock) for the stock history page.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Gate::authorize('products.create');

        $validated = $request->validated();

        // supplier_id is only for opening stock — do NOT persist on product
        $openingSupplier = $validated['supplier_id'] ?? null;
        unset($validated['supplier_id'], $validated['initial_qty']);

        // Upload directory
        $uploadPath = public_path('uploads/products');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Primary image
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $validated['image'] = $fileName;
        }

        // Gallery
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $gallery[] = $fileName;
            }
        }
        $validated['gallery'] = $gallery;

        $addStock   = $request->boolean('add_opening_stock');
        $initialQty = $addStock ? max(0, (float) $request->input('initial_qty', 0)) : 0.00;

        DB::beginTransaction();
        try {
            // 1. Create the product (no supplier_id on product)
            $product = Product::create($validated);

            // 2. Always create a stock row
            $product->stock()->create(['quantity' => $initialQty]);

            // 3. If checkbox was ticked and qty > 0 — create Purchase + PurchaseItem + StockAdjustment
            if ($addStock && $initialQty > 0) {
                $purchaseNo = 'PO-INIT-' . strtoupper($product->code) . '-' . now()->format('YmdHis');
                $lineTotal  = round($initialQty * $product->purchase_price, 2);

                $purchase = Purchase::create([
                    'purchase_no'    => $purchaseNo,
                    'purchase_date'  => now()->toDateString(),
                    'supplier_id'    => $openingSupplier,
                    'reference_no'   => 'Opening stock — ' . $product->name,
                    'sub_total'      => $lineTotal,
                    'tax_amount'     => 0.00,
                    'discount_amount' => 0.00,
                    'shipping_amount' => 0.00,
                    'grand_total'    => $lineTotal,
                    'paid_amount'    => $lineTotal,
                    'due_amount'     => 0.00,
                    'payment_method' => 'Cash',
                    'status'         => 'Completed',
                    'notes'          => 'Auto-created opening-stock purchase on product creation.',
                    'user_id'        => auth()->id(),
                ]);

                PurchaseItem::create([
                    'purchase_id'     => $purchase->id,
                    'product_id'      => $product->id,
                    'quantity'        => $initialQty,
                    'purchase_price'  => $product->purchase_price,
                    'tax_amount'      => 0.00,
                    'discount_amount' => 0.00,
                    'total_amount'    => $lineTotal,
                ]);

                StockAdjustment::create([
                    'product_id'      => $product->id,
                    'quantity_change' => $initialQty,
                    'adjustment_type' => 'Restock',
                    'notes'           => "Opening stock added on product creation (Purchase: {$purchaseNo}).",
                    'user_id'         => auth()->id(),
                ]);
            }

            ActivityLog::log(
                'Product Created',
                "Created product: {$product->name} (SKU: {$product->code})" .
                ($addStock && $initialQty > 0 ? ", opening stock: {$initialQty}" : '')
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Could not save product: ' . $e->getMessage()]);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        Gate::authorize('products.view');

        $product->load(['brand', 'mainCategory', 'subCategory', 'stock']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        Gate::authorize('products.update');

        $brands        = Brand::where('status', 'active')->orderBy('name')->get();
        $categories    = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();
        $suppliers     = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('products.edit', compact('product', 'brands', 'categories', 'subCategories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     * NOTE: No opening-stock checkbox on edit — stock is managed via the Stock Adjustment page.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('products.update');

        $validated = $request->validated();

        // supplier_id / initial_qty are only for opening stock on create — strip them
        unset($validated['supplier_id'], $validated['initial_qty']);

        $uploadPath = public_path('uploads/products');

        // Single image removal
        if ($request->input('remove_image') == 1) {
            if ($product->image && File::exists($uploadPath . '/' . $product->image)) {
                File::delete($uploadPath . '/' . $product->image);
            }
            $validated['image'] = null;
        }

        // Single image replacement
        if ($request->hasFile('image')) {
            if ($product->image && File::exists($uploadPath . '/' . $product->image)) {
                File::delete($uploadPath . '/' . $product->image);
            }
            $file     = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $validated['image'] = $fileName;
        }

        // Gallery clearance
        $gallery = $product->gallery ?: [];
        if ($request->input('clear_gallery') == 1) {
            foreach ($gallery as $img) {
                if (File::exists($uploadPath . '/' . $img)) {
                    File::delete($uploadPath . '/' . $img);
                }
            }
            $gallery = [];
        }

        // Remove specific gallery images
        if ($request->filled('remove_gallery_images')) {
            foreach (explode(',', $request->remove_gallery_images) as $imgToRemove) {
                $imgToRemove = trim($imgToRemove);
                if (($key = array_search($imgToRemove, $gallery)) !== false) {
                    if (File::exists($uploadPath . '/' . $imgToRemove)) {
                        File::delete($uploadPath . '/' . $imgToRemove);
                    }
                    unset($gallery[$key]);
                }
            }
            $gallery = array_values($gallery);
        }

        // New gallery uploads
        if ($request->hasFile('gallery')) {
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            foreach ($request->file('gallery') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $gallery[] = $fileName;
            }
        }
        $validated['gallery'] = $gallery;

        $product->update($validated);

        // Ensure a stock row exists (do NOT overwrite current live quantity)
        $product->stock()->firstOrCreate(['product_id' => $product->id], ['quantity' => 0]);

        ActivityLog::log('Product Updated', "Updated product: {$product->name} (SKU: {$product->code})");

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Pre-fill the create form with an existing product's data for quick duplication.
     */
    public function copy(Product $product): View
    {
        Gate::authorize('products.create');

        $brands        = Brand::where('status', 'active')->orderBy('name')->get();
        $categories    = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();
        $suppliers     = Supplier::where('status', 'active')->orderBy('name')->get();

        $copy = $product->replicate(['code', 'barcode', 'image', 'gallery', 'slug']);
        $copy->code    = strtoupper($product->code) . '-COPY-' . strtoupper(\Illuminate\Support\Str::random(4));
        $copy->barcode = null;
        $copy->image   = null;
        $copy->gallery = [];
        $copy->exists  = false;

        return view('products.create', [
            'product'       => $copy,
            'brands'        => $brands,
            'categories'    => $categories,
            'subCategories' => $subCategories,
            'suppliers'     => $suppliers,
            'isCopy'        => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('products.delete');

        $productName = $product->name;
        $productSku  = $product->code;

        $product->delete();

        ActivityLog::log('Product Deleted', "Deleted product: {$productName} (SKU: {$productSku})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
