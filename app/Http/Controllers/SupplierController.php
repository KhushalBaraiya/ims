<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\ActivityLog;
use App\Models\Currency;
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
    public function index(Request $request): View
    {
        Gate::authorize('suppliers.view');

        $query = Supplier::with('purchases');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('company_name', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->latest()->get();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     * 
     */
    public function create(): View
    {
        Gate::authorize('suppliers.create');

        $currencies = Currency::where('status', 'active')->orderBy('name')->get();
        return view('suppliers.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request): RedirectResponse
    {
        Gate::authorize('suppliers.create');

        $data = $request->validated();

        // Auto-assign currency from country if not manually selected
        if (empty($data['currency_id']) && !empty($data['country'])) {
            $auto = $this->resolveCurrencyFromCountry($data['country']);
            if ($auto) {
                $data['currency_id'] = $auto->id;
            }
        }

        $supplier = Supplier::create($data);

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
     * specification of 
     */
    public function edit(Supplier $supplier): View
    {
        Gate::authorize('suppliers.update');

        $currencies = Currency::where('status', 'active')->orderBy('name')->get();
        return view('suppliers.edit', compact('supplier', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        Gate::authorize('suppliers.update');

        $data = $request->validated();

        // Auto-assign currency from country if not manually selected
        if (empty($data['currency_id']) && !empty($data['country'])) {
            $auto = $this->resolveCurrencyFromCountry($data['country']);
            if ($auto) {
                $data['currency_id'] = $auto->id;
            }
        }

        $supplier->update($data);

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

    /**
     * AJAX: Return supplier's currency info for purchase form auto-switch.
     */
    public function getCurrency(Supplier $supplier): JsonResponse
    {
        $supplier->load('currency');
        $currency = $supplier->currency;

        if (!$currency) {
            // Fall back to system default currency
            $currency = Currency::where('is_default', true)->where('status', 'active')->first()
                     ?? Currency::where('status', 'active')->first();
        }

        if (!$currency) {
            return response()->json(['success' => false, 'message' => 'No currency found.'], 404);
        }

        return response()->json([
            'success'       => true,
            'currency_id'   => $currency->id,
            'code'          => $currency->code,
            'symbol'        => $currency->symbol,
            'exchange_rate' => $currency->exchange_rate,
            'name'          => $currency->name,
        ]);
    }

    /**
     * Bulk delete suppliers.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('suppliers.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        Supplier::whereIn('id', $ids)->each(fn($s) => $s->delete());

        ActivityLog::log('Suppliers Bulk Deleted', 'Deleted ' . count($ids) . ' supplier(s).');

        return response()->json(['success' => true, 'message' => count($ids) . ' supplier(s) deleted successfully.']);
    }

    /**
     * Resolve a Currency model from a country name string.
     * Matches common country names to ISO currency codes, then looks up the DB.
     */
    private function resolveCurrencyFromCountry(string $country): ?Currency
    {
        $map = [
            'INR' => ['india', 'bharat'],
            'CNY' => ['china', 'peoples republic of china'],
            'JPY' => ['japan'],
            'KRW' => ['south korea', 'korea'],
            'SGD' => ['singapore'],
            'HKD' => ['hong kong'],
            'PKR' => ['pakistan'],
            'BDT' => ['bangladesh'],
            'LKR' => ['sri lanka', 'ceylon'],
            'NPR' => ['nepal'],
            'MYR' => ['malaysia'],
            'THB' => ['thailand'],
            'IDR' => ['indonesia'],
            'PHP' => ['philippines'],
            'VND' => ['vietnam', 'viet nam'],
            'AED' => ['uae', 'united arab emirates', 'dubai', 'abu dhabi'],
            'SAR' => ['saudi arabia', 'ksa'],
            'QAR' => ['qatar'],
            'KWD' => ['kuwait'],
            'BHD' => ['bahrain'],
            'OMR' => ['oman'],
            'ILS' => ['israel'],
            'TRY' => ['turkey', 'turkiye'],
            'IRR' => ['iran'],
            'EUR' => [
                'germany', 'france', 'italy', 'spain', 'netherlands', 'belgium',
                'austria', 'portugal', 'greece', 'finland', 'ireland', 'luxembourg',
                'slovakia', 'slovenia', 'estonia', 'latvia', 'lithuania', 'malta',
                'cyprus', 'croatia',
            ],
            'GBP' => ['united kingdom', 'uk', 'britain', 'england', 'great britain', 'scotland', 'wales'],
            'CHF' => ['switzerland'],
            'NOK' => ['norway'],
            'SEK' => ['sweden'],
            'DKK' => ['denmark'],
            'PLN' => ['poland'],
            'CZK' => ['czech republic', 'czechia'],
            'HUF' => ['hungary'],
            'RON' => ['romania'],
            'RUB' => ['russia', 'russian federation'],
            'UAH' => ['ukraine'],
            'USD' => ['united states', 'usa', 'us', 'america', 'united states of america'],
            'CAD' => ['canada'],
            'MXN' => ['mexico'],
            'BRL' => ['brazil'],
            'ARS' => ['argentina'],
            'CLP' => ['chile'],
            'COP' => ['colombia'],
            'AUD' => ['australia'],
            'NZD' => ['new zealand'],
            'ZAR' => ['south africa'],
            'NGN' => ['nigeria'],
            'KES' => ['kenya'],
            'EGP' => ['egypt'],
            'MAD' => ['morocco'],
            'GHS' => ['ghana'],
            'TZS' => ['tanzania'],
        ];

        $needle = strtolower(trim($country));

        $matchedCode = null;
        foreach ($map as $code => $keywords) {
            foreach ($keywords as $keyword) {
                if ($needle === $keyword || str_contains($needle, $keyword) || str_contains($keyword, $needle)) {
                    $matchedCode = $code;
                    break 2;
                }
            }
        }

        if (!$matchedCode) {
            return null;
        }

        return Currency::where('code', $matchedCode)->where('status', 'active')->first();
    }
}
