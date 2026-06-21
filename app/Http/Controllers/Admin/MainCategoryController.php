<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    public function index()
    {
        $categories = MainCategory::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function show($id)
    {
        $category = MainCategory::withCount([
            'products',
        ])->with([
            'products' => fn($q) => $q->latest()->take(5),
        ])->findOrFail($id);

        $subCategories = \App\Models\SubCategory::where('category_id', $id)
            ->withCount('products' )
            ->latest()
            ->get();

        $subInCategories = \App\Models\SubInCategory::where('category_id', $id)
            ->latest()
            ->get();

        return view('admin.category.show', compact('category', 'subCategories', 'subInCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:main_categories,name',
            'status' => 'required|in:active,inactive',
        ]);

        MainCategory::create([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'MainCategory added successfully.');
    }

    public function edit($id)
    {
        $category = MainCategory::findOrFail($id);
        return view('admin.category.create', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = MainCategory::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255|unique:main_categories,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'MainCategory updated successfully.');
    }

    public function destroy($id)
    {
        $category = MainCategory::findOrFail($id);
        $category->delete(); // Soft Delete

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'MainCategory deleted successfully.');
    }
}
