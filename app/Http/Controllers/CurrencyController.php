<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource (Blade layout or AJAX JSON data).
     */
    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('currencies.view');

        if ($request->ajax() || $request->wantsJson()) {
            $currencies = Currency::orderBy('name', 'asc')->get();
            return response()->json([
                'success' => true,
                'data' => $currencies,
            ]);
        }

        return view('currencies.index');
    }

    /**
     * Store a newly created resource in storage via AJAX.
     */
    public function store(CurrencyRequest $request): JsonResponse
    {
        Gate::authorize('currencies.create');

        $validated = $request->validated();
        
        // Handle unique default currency logic
        $isDefault = $request->boolean('is_default');
        $validated['is_default'] = $isDefault;

        if ($isDefault) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $currency = Currency::create($validated);

        ActivityLog::log('Currency Created', "Created currency: {$currency->name} (Code: {$currency->code}, Symbol: {$currency->symbol})");

        return response()->json([
            'success' => true,
            'message' => 'Currency created successfully.',
            'data' => $currency,
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(Currency $currency): JsonResponse
    {
        Gate::authorize('currencies.view');

        return response()->json([
            'success' => true,
            'data' => $currency,
        ]);
    }

    /**
     * Return JSON for edit modal.
     */
    public function edit(Currency $currency): JsonResponse
    {
        Gate::authorize('currencies.update');

        return response()->json([
            'success' => true,
            'data' => $currency,
        ]);
    }

    /**
     * Update the specified resource in storage via AJAX.
     */
    public function update(CurrencyRequest $request, Currency $currency): JsonResponse
    {
        Gate::authorize('currencies.update');

        $validated = $request->validated();
        
        // Handle unique default currency logic
        $isDefault = $request->boolean('is_default');
        $validated['is_default'] = $isDefault;

        if ($isDefault) {
            Currency::where('id', '!=', $currency->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $currency->update($validated);

        ActivityLog::log('Currency Updated', "Updated currency: {$currency->name} (Code: {$currency->code})");

        return response()->json([
            'success' => true,
            'message' => 'Currency updated successfully.',
            'data' => $currency,
        ]);
    }

    /**
     * Remove the specified resource from storage via AJAX.
     */
    public function destroy(Currency $currency): JsonResponse
    {
        Gate::authorize('currencies.delete');

        $currencyName = $currency->name;
        $currencyCode = $currency->code;

        $currency->delete();

        ActivityLog::log('Currency Deleted', "Deleted currency: {$currencyName} (Code: {$currencyCode})");

        return response()->json([
            'success' => true,
            'message' => 'Currency deleted successfully.',
        ]);
    }

    /**
     * Toggle currency status via AJAX.
     */
    public function toggleStatus(Currency $currency): JsonResponse
    {
        Gate::authorize('currencies.update');

        $newStatus = $currency->status === 'active' ? 'inactive' : 'active';
        $currency->update(['status' => $newStatus]);

        ActivityLog::log('Currency Status Toggled', "Toggled status of currency: {$currency->name} to {$newStatus}");

        return response()->json([
            'success' => true,
            'message' => "Currency status updated to {$newStatus}.",
            'status' => $newStatus,
        ]);
    }
}
