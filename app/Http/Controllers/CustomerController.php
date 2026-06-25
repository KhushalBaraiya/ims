<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('customers.view');

        $customers = Customer::latest()->get();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('customers.create');

        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        Gate::authorize('customers.create');

        $customer = Customer::create($request->validated());

        ActivityLog::log('Customer Created', "Created customer: {$customer->name} (Phone: {$customer->phone})");

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        Gate::authorize('customers.view');

        $customer->load(['sales', 'saleReturns']);

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        Gate::authorize('customers.update');

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        Gate::authorize('customers.update');

        $customer->update($request->validated());

        ActivityLog::log('Customer Updated', "Updated customer details for: {$customer->name} (Phone: {$customer->phone})");

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(Customer $customer): JsonResponse
    {
        Gate::authorize('customers.update');

        $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->save();

        ActivityLog::log('Customer Status Changed', "Changed customer status: {$customer->name} → {$customer->status}");

        return response()->json([
            'success' => true,
            'status'  => $customer->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('customers.delete');

        $customerName  = $customer->name;
        $customerPhone = $customer->phone;

        $customer->delete();

        ActivityLog::log('Customer Deleted', "Deleted customer: {$customerName} (Phone: {$customerPhone})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.',
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
