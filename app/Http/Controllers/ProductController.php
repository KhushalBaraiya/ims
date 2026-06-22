<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $query = Product::with(['brand', 'mainCategory', 'subCategory', 'supplier', 'stock'])->latest();

        // Apply filters
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->main_category_id);
        }
        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
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

        // Get filter options
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('products.index', compact('products', 'brands', 'categories', 'subCategories', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('products.create');

        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('products.create', compact('brands', 'categories', 'subCategories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Gate::authorize('products.create');

        $validated = $request->validated();
        $validated['is_featured'] = $request->has('is_featured');

        // Make sure uploads directory exists
        $uploadPath = public_path('uploads/products');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Handle single image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $validated['image'] = $fileName;
        }

        // Handle gallery images upload
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $gallery[] = $fileName;
            }
        }
        $validated['gallery'] = $gallery;

        // Create product
        $product = Product::create($validated);

        // Manage opening stock record
        $product->stock()->create([
            'quantity' => $validated['opening_stock'] ?? 0.00
        ]);

        ActivityLog::log('Product Created', "Created product: {$product->name} (SKU: {$product->code})");

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        Gate::authorize('products.view');

        $product->load(['brand', 'mainCategory', 'subCategory', 'supplier', 'stock']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        Gate::authorize('products.update');

        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = MainCategory::where('status', 'active')->orderBy('name')->get();
        $subCategories = SubCategory::where('status', 'active')->orderBy('name')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        return view('products.edit', compact('product', 'brands', 'categories', 'subCategories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('products.update');

        $validated = $request->validated();
        $validated['is_featured'] = $request->has('is_featured');

        $uploadPath = public_path('uploads/products');

        // Handle single image removal
        if ($request->input('remove_image') == 1) {
            if ($product->image && File::exists($uploadPath . '/' . $product->image)) {
                File::delete($uploadPath . '/' . $product->image);
            }
            $validated['image'] = null;
        }

        // Handle single image replacement/upload
        if ($request->hasFile('image')) {
            // Delete old file
            if ($product->image && File::exists($uploadPath . '/' . $product->image)) {
                File::delete($uploadPath . '/' . $product->image);
            }
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
            $validated['image'] = $fileName;
        }

        // Handle gallery clearance
        $gallery = $product->gallery ?: [];
        if ($request->input('clear_gallery') == 1) {
            foreach ($gallery as $img) {
                if (File::exists($uploadPath . '/' . $img)) {
                    File::delete($uploadPath . '/' . $img);
                }
            }
            $gallery = [];
        }

        // Remove specific gallery images if requested
        if ($request->filled('remove_gallery_images')) {
            $imagesToRemove = explode(',', $request->remove_gallery_images);
            foreach ($imagesToRemove as $imgToRemove) {
                $imgToRemove = trim($imgToRemove);
                if (($key = array_search($imgToRemove, $gallery)) !== false) {
                    if (File::exists($uploadPath . '/' . $imgToRemove)) {
                        File::delete($uploadPath . '/' . $imgToRemove);
                    }
                    unset($gallery[$key]);
                }
            }
            $gallery = array_values($gallery); // Re-index array
        }

        // Handle new gallery uploads
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

        // Update product
        $product->update($validated);

        // Update stock record (sync opening stock changes if needed)
        $product->stock()->updateOrCreate(
            ['product_id' => $product->id],
            ['quantity' => $validated['opening_stock'] ?? 0.00]
        );

        ActivityLog::log('Product Updated', "Updated product: {$product->name} (SKU: {$product->code})");

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('products.delete');

        $productName = $product->name;
        $productSku = $product->code;

        // We do not delete physical files on soft-delete, keeping database soft-delete clean.
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
