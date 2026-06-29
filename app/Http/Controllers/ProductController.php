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
     * Category-wise Products — products grouped by main category with sub-category tabs.
     */
    public function byCategory(Request $request): View
    {
        Gate::authorize('products.view');

        $search       = $request->input('search', '');
        $statusFilter = $request->input('status', '');
        $stockFilter  = $request->input('stock_filter', '');

        // Load all active main categories with their sub-categories and products
        $categoriesQuery = MainCategory::with([
            'subCategories' => fn ($q) => $q->where('status', 'active')->orderBy('name'),
            'products' => function ($q) use ($search, $statusFilter, $stockFilter) {
                $q->with(['brand', 'subCategory', 'stock']);
                if ($search) {
                    $q->where(function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
                    });
                }
                if ($statusFilter) {
                    $q->where('status', $statusFilter);
                }
                if ($stockFilter === 'out') {
                    $q->where(function ($sq) {
                        $sq->whereHas('stock', fn ($s) => $s->where('quantity', '<=', 0))
                           ->orWhereDoesntHave('stock');
                    });
                } elseif ($stockFilter === 'low') {
                    $q->whereHas('stock', fn ($s) => $s->where('quantity', '>', 0)
                        ->whereRaw('stocks.quantity <= products.minimum_stock_alert'));
                } elseif ($stockFilter === 'ok') {
                    $q->whereHas('stock', fn ($s) => $s->whereRaw('stocks.quantity > products.minimum_stock_alert'));
                }
                $q->orderBy('name');
            },
        ])->where('status', 'active')->orderBy('name');

        $categories = $categoriesQuery->get();

        // Also get products with no category (uncategorized)
        $uncategorizedQuery = Product::with(['brand', 'mainCategory', 'subCategory', 'stock'])
            ->whereNull('main_category_id');
        if ($search) {
            $uncategorizedQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        if ($statusFilter) {
            $uncategorizedQuery->where('status', $statusFilter);
        }
        $uncategorized = $uncategorizedQuery->orderBy('name')->get();

        // Summary stats
        $totalProducts   = Product::count();
        $totalCategories = MainCategory::where('status', 'active')->count();

        return view('products.by_category', compact(
            'categories', 'uncategorized',
            'totalProducts', 'totalCategories',
            'search', 'statusFilter', 'stockFilter'
        ));
    }

    /**
     * Product Gallery — visual card grid with filters, sorting, and pagination.
     */
    public function gallery(Request $request): View
    {
        Gate::authorize('products.view');

        $perPage = (int) $request->input('per_page', 24);
        $perPage = in_array($perPage, [12, 24, 48, 96]) ? $perPage : 24;

        $query = Product::with(['brand', 'mainCategory', 'subCategory', 'stock'])
            ->select('products.*');

        // Always left-join stocks once (used by both stock_filter and stock sort)
        $needsStockJoin = $request->filled('stock_filter')
            || in_array($request->input('sort'), ['stock_asc', 'stock_desc']);
        if ($needsStockJoin) {
            $query->leftJoin('stocks', 'stocks.product_id', '=', 'products.id');
        }

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('products.name', 'like', "%{$s}%")
                  ->orWhere('products.code', 'like', "%{$s}%")
                  ->orWhere('products.barcode', 'like', "%{$s}%");
            });
        }
        if ($request->filled('brand_id')) {
            $query->where('products.brand_id', $request->brand_id);
        }
        if ($request->filled('main_category_id')) {
            $query->where('products.main_category_id', $request->main_category_id);
        }
        if ($request->filled('status')) {
            $query->where('products.status', $request->status);
        }

        if ($request->filled('stock_filter')) {
            match ($request->stock_filter) {
                'out' => $query->where(function ($q) {
                    $q->whereNull('stocks.quantity')->orWhere('stocks.quantity', '<=', 0);
                }),
                'low' => $query->whereNotNull('stocks.quantity')
                               ->where('stocks.quantity', '>', 0)
                               ->whereRaw('stocks.quantity <= products.minimum_stock_alert'),
                'ok'  => $query->whereNotNull('stocks.quantity')
                               ->whereRaw('stocks.quantity > products.minimum_stock_alert'),
                default => null,
            };
        }

        // Sorting
        switch ($request->input('sort', 'latest')) {
            case 'name_asc':   $query->orderBy('products.name', 'asc');         break;
            case 'name_desc':  $query->orderBy('products.name', 'desc');        break;
            case 'price_asc':  $query->orderBy('products.selling_price', 'asc'); break;
            case 'price_desc': $query->orderBy('products.selling_price', 'desc'); break;
            case 'stock_asc':  $query->orderBy('stocks.quantity', 'asc');       break;
            case 'stock_desc': $query->orderBy('stocks.quantity', 'desc');      break;
            default:           $query->orderBy('products.created_at', 'desc');  break;
        }

        $products = $query->paginate($perPage)->withQueryString();

        // Summary counts (unfiltered base)
        $activeCount     = Product::where('status', 'active')->count();
        $lowStockCount   = Product::whereHas('stock', fn ($q) =>
                               $q->where('quantity', '>', 0)
                                 ->whereRaw('stocks.quantity <= products.minimum_stock_alert')
                           )->count();
        $outOfStockCount = Product::where(function ($q) {
            $q->whereHas('stock', fn ($sq) => $sq->where('quantity', '<=', 0))
              ->orWhereDoesntHave('stock');
        })->count();

        $brands     = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();

        return view('products.gallery', compact(
            'products', 'brands', 'categories',
            'activeCount', 'lowStockCount', 'outOfStockCount'
        ));
    }

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

        $products = $query->paginate(20)->withQueryString();

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
        // Pass an empty Product so form.blade.php never crashes on $product->xxx ?? ''
        $product       = new Product();

        return view('products.create', compact('brands', 'categories', 'subCategories', 'suppliers', 'product'));
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
                    'product_id'       => $product->id,
                    'voucher_no'       => $purchaseNo,
                    'transaction_date' => now()->toDateString(),
                    'quantity_change'  => $initialQty,
                    'adjustment_type'  => 'Restock',
                    'notes'            => "Opening stock added on product creation (Purchase: {$purchaseNo}).",
                    'user_id'          => auth()->id(),
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

        $product->load([
            'brand',
            'mainCategory',
            'subCategory',
            'stock',
            'purchaseItems.purchase.supplier',
            'saleItems.sale.customer',
            'stockAdjustments.user',
            'purchaseReturnItems.purchaseReturn',
            'saleReturnItems.saleReturn',
        ]);

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
        $product->stock()->firstOrCreate([], ['quantity' => 0]);

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
        $suffix    = '-COPY-' . strtoupper(\Illuminate\Support\Str::random(4));
        $baseCode  = strtoupper($product->code);
        $maxBase   = 50 - strlen($suffix);
        $copy->code    = substr($baseCode, 0, $maxBase) . $suffix;
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
     * Warns (but still allows) deletion of products with transactions via soft-delete.
     */
    public function destroy(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('products.delete');

        $productName = $product->name;
        $productSku  = $product->code;

        $product->delete();

        // Soft-delete the related stock row so it won't confuse stock reports
        if ($product->stock) {
            $product->stock()->delete();
        }

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
