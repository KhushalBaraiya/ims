<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubInCategory;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubInCategoryController extends Controller
{
    public function index()
    {
        $subInCategories = SubInCategory::with(['category', 'subcategory'])
                            ->latest()
                            ->get();

        return view('admin.subincategory.index', compact('subInCategories'));
    }

    public function create()
    {
        $categories = MainCategory::all();
        $subcategories = SubCategory::all();

        return view('admin.subincategory.create', compact('categories', 'subcategories'));
    }

    public function show($id)
    {
        $subInCategory = SubInCategory::with(['category', 'subcategory'])
            ->findOrFail($id);

        $products = \App\Models\Product::where('sub_in_categories_id', $id)
            ->with('brand')
            ->latest()
            ->get();

        $totalProducts  = $products->count();
        $activeProducts = $products->where('status', 'active')->count();
        $lowStockCount  = $products->where('quantity', '<=', 5)->count();
        $totalValue     = $products->sum('price');

        return view('admin.subincategory.show', compact(
            'subInCategory',
            'products',
            'totalProducts',
            'activeProducts',
            'lowStockCount',
            'totalValue'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'    => 'required|exists:main_categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'name'           => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        SubInCategory::create([
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'name'           => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.subincategory.index')
            ->with('success', 'SubInCategory added successfully.');
    }

    public function edit($id)
    {
        $subInCategory = SubInCategory::findOrFail($id);
        $categories = MainCategory::all();
        $subcategories = SubCategory::all();

        return view('admin.subincategory.create',
            compact('subInCategory', 'categories', 'subcategories'));
    }

    public function update(Request $request, $id)
    {
        $subInCategory = SubInCategory::findOrFail($id);

        $request->validate([
            'category_id'    => 'required|exists:main_categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'name'           => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $subInCategory->update([
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'name'           => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.subincategory.index')
            ->with('success', 'SubInCategory updated successfully.');
    }

    public function destroy($id)
    {
        $subInCategory = SubInCategory::findOrFail($id);
        $subInCategory->delete(); // Soft Delete

        return redirect()
            ->route('admin.subincategory.index')
            ->with('success', 'SubInCategory deleted successfully.');
    }
}
