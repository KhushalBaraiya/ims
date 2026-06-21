<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->get();
        return view('admin.subcategory.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = MainCategory::all();
        return view('admin.subcategory.create', compact('categories'));
    }

    public function show($id)
    {
        $subCategory = SubCategory::with('category')
            ->withCount('products')
            ->findOrFail($id);

        $subInCategories = \App\Models\SubInCategory::where('subcategory_id', $id)
            ->latest()
            ->get();

        $products = \App\Models\Product::where('subcategory_id', $id)
            ->with('brand')
            ->latest()
            ->take(10)
            ->get();

        $totalProducts   = \App\Models\Product::where('subcategory_id', $id)->count();
        $activeProducts  = \App\Models\Product::where('subcategory_id', $id)->where('status', 'active')->count();
        $lowStockCount   = \App\Models\Product::where('subcategory_id', $id)->where('quantity', '<=', 5)->count();

        return view('admin.subcategory.show', compact(
            'subCategory',
            'subInCategories',
            'products',
            'totalProducts',
            'activeProducts',
            'lowStockCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:main_categories,id',
            'name'        => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.subcategory.index')
            ->with('success', 'SubCategory added successfully.');
    }

    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories  = MainCategory::all();

        return view('admin.subcategory.create', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:main_categories,id',
            'name'        => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $subCategory->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.subcategory.index')
            ->with('success', 'SubCategory updated successfully.');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete(); // Soft delete

        return redirect()
            ->route('admin.subcategory.index')
            ->with('success', 'SubCategory deleted successfully.');
    }
}
