<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('suppliers.view');

        $suppliers = Supplier::with('purchases')->latest()->get();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('suppliers.create');

        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request): RedirectResponse
    {
        Gate::authorize('suppliers.create');

        $supplier = Supplier::create($request->validated());

        ActivityLog::log('Supplier Created', "Created supplier: {$supplier->name} ({$supplier->company_name})");

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier): View
    {
        Gate::authorize('suppliers.view');

        $supplier->load(['purchases', 'purchaseReturns']);

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier): View
    {
        Gate::authorize('suppliers.update');

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        Gate::authorize('suppliers.update');

        $supplier->update($request->validated());

        ActivityLog::log('Supplier Updated', "Updated supplier details for: {$supplier->name} ({$supplier->company_name})");

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(Supplier $supplier): JsonResponse
    {
        Gate::authorize('suppliers.update');

        $supplier->status = $supplier->status === 'active' ? 'inactive' : 'active';
        $supplier->save();

        ActivityLog::log('Supplier Status Changed', "Changed supplier status: {$supplier->name} → {$supplier->status}");

        return response()->json([
            'success' => true,
            'status'  => $supplier->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('suppliers.delete');

        $supplierName = $supplier->name;
        $companyName  = $supplier->company_name;

        $supplier->delete();

        ActivityLog::log('Supplier Deleted', "Deleted supplier: {$supplierName} ({$companyName})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully.',
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
