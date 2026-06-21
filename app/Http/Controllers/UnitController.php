<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('units.view');

        $units = Unit::latest()->get();

        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('units.create');

        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request): RedirectResponse
    {
        Gate::authorize('units.create');

        $unit = Unit::create($request->validated());

        ActivityLog::log('Unit Created', "Created unit: {$unit->name} ({$unit->short_name})");

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit): View
    {
        Gate::authorize('units.view');

        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        Gate::authorize('units.update');

        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, Unit $unit): RedirectResponse
    {
        Gate::authorize('units.update');

        $unit->update($request->validated());

        ActivityLog::log('Unit Updated', "Updated unit: {$unit->name} ({$unit->short_name})");

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('units.delete');

        $unitName = $unit->name;
        $unitShort = $unit->short_name;

        $unit->delete();

        ActivityLog::log('Unit Deleted', "Deleted unit: {$unitName} ({$unitShort})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit deleted successfully.',
            ]);
        }

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
