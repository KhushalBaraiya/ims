<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('brands.view');

        $query = Brand::with('products');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('slug', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $brands = $query->latest()->get();

        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('brands.create');

        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): RedirectResponse
    {
        Gate::authorize('brands.create');

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/brands'), $filename);
            $data['image'] = $filename;
        }

        $brand = Brand::create($data);

        ActivityLog::log('Brand Created', "Created brand: {$brand->name} (Code: {$brand->slug})");

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): View
    {
        Gate::authorize('brands.view');

        $brand->load('products');

        return view('brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand): View
    {
        Gate::authorize('brands.update');

        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        Gate::authorize('brands.update');

        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
                unlink(public_path('uploads/brands/' . $brand->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/brands'), $filename);
            $data['image'] = $filename;
        }

        $brand->update($data);

        ActivityLog::log('Brand Updated', "Updated brand: {$brand->name} (Code: {$brand->slug})");

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(Brand $brand): JsonResponse
    {
        Gate::authorize('brands.update');

        $brand->status = $brand->status === 'active' ? 'inactive' : 'active';
        $brand->save();

        ActivityLog::log('Brand Status Changed', "Changed brand status: {$brand->name} → {$brand->status}");

        return response()->json([
            'success' => true,
            'status'  => $brand->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('brands.delete');

        $brandName = $brand->name;
        $brandCode = $brand->slug;

        $brand->delete();

        ActivityLog::log('Brand Deleted', "Deleted brand: {$brandName} (Code: {$brandCode})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully.',
            ]);
        }

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }

    /**
     * Bulk delete brands.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('brands.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $brands = Brand::whereIn('id', $ids)->get();
        foreach ($brands as $brand) {
            $brand->delete();
        }

        ActivityLog::log('Brands Bulk Deleted', 'Deleted ' . count($ids) . ' brand(s).');

        return response()->json(['success' => true, 'message' => count($ids) . ' brand(s) deleted successfully.']);
    }
}
