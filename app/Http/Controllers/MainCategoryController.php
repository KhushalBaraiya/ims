<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainCategoryRequest;
use App\Models\ActivityLog;
use App\Models\MainCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('main_categories.view');

        $query = MainCategory::withCount('subCategories')->with('subCategories');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('slug', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->latest()->get();

        return view('main_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('main_categories.create');

        return view('main_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MainCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('main_categories.create');

        $category = MainCategory::create($request->validated());

        ActivityLog::log('Category Created', "Created main category: {$category->name} (Code: {$category->slug})");

        return redirect()->route('main-categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MainCategory $mainCategory): View
    {
        Gate::authorize('main_categories.view');

        $mainCategory->load(['subCategories', 'products']);

        return view('main_categories.show', compact('mainCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainCategory $mainCategory): View
    {
        Gate::authorize('main_categories.update');

        return view('main_categories.edit', compact('mainCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MainCategoryRequest $request, MainCategory $mainCategory): RedirectResponse
    {
        Gate::authorize('main_categories.update');

        $mainCategory->update($request->validated());

        ActivityLog::log('Category Updated', "Updated main category: {$mainCategory->name} (Code: {$mainCategory->slug})");

        return redirect()->route('main-categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(MainCategory $mainCategory): JsonResponse
    {
        Gate::authorize('main_categories.update');

        $mainCategory->status = $mainCategory->status === 'active' ? 'inactive' : 'active';
        $mainCategory->save();

        ActivityLog::log('Category Status Changed', "Changed main category status: {$mainCategory->name} → {$mainCategory->status}");

        return response()->json([
            'success' => true,
            'status' => $mainCategory->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainCategory $mainCategory, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('main_categories.delete');

        $categoryName = $mainCategory->name;
        $categoryCode = $mainCategory->slug;

        $mainCategory->delete();

        ActivityLog::log('Category Deleted', "Deleted main category: {$categoryName} (Code: {$categoryCode})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ]);
        }

        return redirect()->route('main-categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Bulk delete main categories.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('main_categories.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        MainCategory::whereIn('id', $ids)->each(fn ($c) => $c->delete());

        ActivityLog::log('Main Categories Bulk Deleted', 'Deleted '.count($ids).' main category(s).');

        return response()->json(['success' => true, 'message' => count($ids).' category(s) deleted successfully.']);
    }
}
