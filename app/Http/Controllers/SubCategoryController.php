<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('sub_categories.view');

        $subCategories = SubCategory::with('mainCategory')->latest()->get();

        return view('sub_categories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('sub_categories.create');

        $mainCategories = MainCategory::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('sub_categories.create', compact('mainCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('sub_categories.create');

        $subCategory = SubCategory::create($request->validated());

        ActivityLog::log('Sub Category Created', "Created sub category: {$subCategory->name} (Code: {$subCategory->slug})");

        return redirect()->route('sub-categories.index')->with('success', 'Sub category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory): View
    {
        Gate::authorize('sub_categories.view');

        $subCategory->load(['mainCategory', 'products']);

        return view('sub_categories.show', compact('subCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory): View
    {
        Gate::authorize('sub_categories.update');

        $mainCategories = MainCategory::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('sub_categories.edit', compact('subCategory', 'mainCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubCategoryRequest $request, SubCategory $subCategory): RedirectResponse
    {
        Gate::authorize('sub_categories.update');

        $subCategory->update($request->validated());

        ActivityLog::log('Sub Category Updated', "Updated sub category: {$subCategory->name} (Code: {$subCategory->slug})");

        return redirect()->route('sub-categories.index')->with('success', 'Sub category updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(SubCategory $subCategory): JsonResponse
    {
        Gate::authorize('sub_categories.update');

        $subCategory->status = $subCategory->status === 'active' ? 'inactive' : 'active';
        $subCategory->save();

        ActivityLog::log('Sub Category Status Changed', "Changed sub category status: {$subCategory->name} → {$subCategory->status}");

        return response()->json([
            'success' => true,
            'status'  => $subCategory->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('sub_categories.delete');

        $subCategoryName = $subCategory->name;
        $subCategoryCode = $subCategory->slug;

        $subCategory->delete();

        ActivityLog::log('Sub Category Deleted', "Deleted sub category: {$subCategoryName} (Code: {$subCategoryCode})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sub category deleted successfully.',
            ]);
        }

        return redirect()->route('sub-categories.index')->with('success', 'Sub category deleted successfully.');
    }
}
