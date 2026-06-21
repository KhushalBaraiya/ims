<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubInCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'brand', 'subInCategory'])
            ->withCount('attributes')
            ->latest()
            ->paginate(10);

        return view('admin.product.index', compact('products'));
    }

    // ================= SHOW =================
    public function show($id)
    {
        $product = Product::with([
            'category',
            'subcategory',
            'subInCategory',
            'brand',
            'reviews.user',
            'attributes',
            'colors',
            'sizes',
        ])->findOrFail($id);

        return view('admin.product.show', compact('product'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.product.create', [
            'categories'     => MainCategory::all(),
            'subcategories'  => SubCategory::all(),
            'brands'         => Brand::all(),
            'subInCategories' => SubInCategory::all(),
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|min:2|max:255',
            'slug'                  => 'nullable|string|max:255|unique:products,slug|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'sku'                   => 'nullable|string|max:100|unique:products,sku',
            'category_id'           => 'required|exists:main_categories,id',
            'subcategory_id'        => 'required|exists:sub_categories,id',
            'brand_id'              => 'required|exists:brands,id',
            'sub_in_categories_id'  => 'required|exists:sub_in_categories,id',
            'description'           => 'required|string|max:1000',
            'product_details'       => 'required|string',
            'original_price'        => 'required|numeric|min:0',
            'price'                 => 'required|numeric|min:0',
            'total_price'           => 'required|numeric|min:0',
            'discount_percent'      => 'required|integer|min:0|max:100',
            'images'                => 'nullable|array',
            'images.*'              => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'quantity'              => 'required|integer|min:0',
            'rating'                => 'required|numeric|min:0|max:5',
            'status'                => 'required|in:active,inactive',
            'is_favourite'          => 'required|in:0,1',
        ]);

        $slug = $request->filled('slug')
            ? $request->slug
            : Str::slug($request->name) . '-' . time();

        // Upload product images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move(public_path('uploads/products'), $name);
                $images[] = $name;
            }
        }

        $product = Product::create([
            'name'                 => $request->name,
            'slug'                 => $slug,
            'sku'                  => $request->sku,
            'description'          => $request->description,
            'product_details'      => $request->product_details,
            'original_price'       => $request->original_price,
            'price'                => $request->price,
            'total_price'          => $request->total_price ?? $request->price,
            'discount_percent'     => $request->discount_percent ?? 0,
            'quantity'             => $request->quantity ?? 0,
            'rating'               => 0,
            'status'               => $request->status,
            'is_favourite'         => $request->is_favourite ?? 0,
            'images'               => $images,
            'category_id'          => $request->category_id,
            'subcategory_id'       => $request->subcategory_id,
            'brand_id'             => $request->brand_id,
            'sub_in_categories_id' => $request->sub_in_categories_id,
        ]);

        return redirect()->route('admin.product.index')
            ->with('success', 'Product Created Successfully');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('admin.product.create', [
            'product'         => $product,
            'categories'      => MainCategory::all(),
            'subcategories'   => SubCategory::all(),
            'brands'          => Brand::all(),
            'subInCategories' => SubInCategory::all(),
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'                  => 'required|string|min:2|max:255',
            'slug'                  => 'nullable|string|max:255|unique:products,slug,' . $id . '|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'sku'                   => 'nullable|string|max:100|unique:products,sku,' . $id,
            'category_id'           => 'required|exists:main_categories,id',
            'subcategory_id'        => 'required|exists:sub_categories,id',
            'brand_id'              => 'required|exists:brands,id',
            'sub_in_categories_id'  => 'required|exists:sub_in_categories,id',
            'description'           => 'required|string|max:1000',
            'product_details'       => 'required|string',
            'original_price'        => 'required|numeric|min:0',
            'price'                 => 'required|numeric|min:0',
            'total_price'           => 'required|numeric|min:0',
            'discount_percent'      => 'required|integer|min:0|max:100',
            'images'                => 'nullable|array',
            'images.*'              => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'quantity'              => 'required|integer|min:0',
            'rating'                => 'required|numeric|min:0|max:5',
            'status'                => 'required|in:active,inactive',
            'is_favourite'          => 'required|in:0,1',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move(public_path('uploads/products'), $name);
                $images[] = $name;
            }
        }

        $product->update([
            'name'                 => $request->name,
            'slug'                 => $request->filled('slug') ? $request->slug : Str::slug($request->name) . '-' . time(),
            'sku'                  => $request->sku,
            'description'          => $request->description,
            'product_details'      => $request->product_details,
            'original_price'       => $request->original_price,
            'price'                => $request->price,
            'total_price'          => $request->total_price ?? $request->price,
            'discount_percent'     => $request->discount_percent ?? 0,
            'quantity'             => $request->quantity ?? 0,
            'status'               => $request->status,
            'is_favourite'         => $request->is_favourite ?? 0,
            'images'               => $images,
            'category_id'          => $request->category_id,
            'subcategory_id'       => $request->subcategory_id,
            'brand_id'             => $request->brand_id,
            'sub_in_categories_id' => $request->sub_in_categories_id,
        ]);

        return redirect()->route('admin.product.index')
            ->with('success', 'Product Updated Successfully');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (!empty($product->images)) {
            foreach ($product->images as $img) {
                $path = public_path('uploads/products/' . $img);
                if (file_exists($path)) unlink($path);
            }
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'Product Deleted Successfully');
    }

    // ================= DELETE IMAGE (AJAX) =================
    public function deleteImage(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) return response()->json(['success' => false]);

        $images = array_filter($product->images ?? [], fn($img) => $img !== $request->image);

        $path = public_path('uploads/products/' . $request->image);
        if (file_exists($path)) unlink($path);

        $product->update(['images' => array_values($images)]);

        return response()->json(['success' => true]);
    }
}
