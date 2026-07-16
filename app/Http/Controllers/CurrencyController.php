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
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('currencies.view');

        $query = Currency::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $currencies = $query->orderBy('name', 'asc')->get();

        return view('currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('currencies.create');

        return view('currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('currencies.create');

        $validated = $request->validated();

        $isDefault = $request->boolean('is_default');
        $validated['is_default'] = $isDefault;

        if ($isDefault) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $currency = Currency::create($validated);

        ActivityLog::log('Currency Created', "Created currency: {$currency->name} (Code: {$currency->code}, Symbol: {$currency->symbol})");

        return redirect()->route('currencies.index')
            ->with('success', __('messages.currency_created'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Currency $currency): View
    {
        Gate::authorize('currencies.view');

        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency): View
    {
        Gate::authorize('currencies.update');

        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CurrencyRequest $request, Currency $currency): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('currencies.update');

        $validated = $request->validated();

        $isDefault = $request->boolean('is_default');
        $validated['is_default'] = $isDefault;

        if ($isDefault) {
            Currency::where('id', '!=', $currency->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $currency->update($validated);

        ActivityLog::log('Currency Updated', "Updated currency: {$currency->name} (Code: {$currency->code})");

        return redirect()->route('currencies.index')
            ->with('success', __('messages.currency_updated'));
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
            'message' => __('messages.currency_deleted'),
        ]);
    }

    /**
     * Set a currency as the default.
     */
    public function setDefault(Currency $currency): JsonResponse
    {
        Gate::authorize('currencies.update');

        Currency::where('is_default', true)->update(['is_default' => false]);
        $currency->update(['is_default' => true]);

        ActivityLog::log('Currency Default Set', "Set default currency to: {$currency->name} ({$currency->code})");

        return response()->json([
            'success' => true,
            'message' => __('messages.default_changed'),
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

    /**
     * Switch the globally active currency for the logged-in user.
     * Saves the preference to the user record so it persists across sessions.
     */
    public function switchCurrency(Request $request): JsonResponse
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
        ]);

        $currency = Currency::where('status', 'active')->findOrFail($request->currency_id);

        // Persist in session (immediate effect)
        session(['active_currency' => $currency]);

        // Persist to user record (survives session expiry / re-login)
        if (auth()->check()) {
            auth()->user()->update(['currency' => $currency->code]);
        }

        ActivityLog::log('Currency Switched', "Switched active currency to: {$currency->name} ({$currency->code})");

        return response()->json([
            'success'  => true,
            'message'  => "Currency switched to {$currency->name} ({$currency->symbol}).",
            'currency' => [
                'id'     => $currency->id,
                'name'   => $currency->name,
                'code'   => $currency->code,
                'symbol' => $currency->symbol,
            ],
        ]);
    }
}
