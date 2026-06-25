<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Models\ActivityLog;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        Gate::authorize('units.view');

        $units = Unit::latest()->get();

        return view('units.index', compact('units'));
    }

    public function create(): View
    {
        Gate::authorize('units.create');

        return view('units.create');
    }

    public function store(UnitRequest $request): RedirectResponse
    {
        Gate::authorize('units.create');

        $unit = Unit::create($request->validated());

        ActivityLog::log('Unit Created', "Created unit: {$unit->name} ({$unit->short_name})");

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit): View
    {
        Gate::authorize('units.view');

        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit): View
    {
        Gate::authorize('units.update');

        return view('units.edit', compact('unit'));
    }

    public function update(UnitRequest $request, Unit $unit): RedirectResponse
    {
        Gate::authorize('units.update');

        $unit->update($request->validated());

        ActivityLog::log('Unit Updated', "Updated unit: {$unit->name} ({$unit->short_name})");

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(Unit $unit): JsonResponse
    {
        Gate::authorize('units.update');

        $unit->status = $unit->status === 'active' ? 'inactive' : 'active';
        $unit->save();

        ActivityLog::log('Unit Status Changed', "Changed unit status: {$unit->name} → {$unit->status}");

        return response()->json([
            'success' => true,
            'status'  => $unit->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    public function destroy(Unit $unit, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('units.delete');

        $name      = $unit->name;
        $shortName = $unit->short_name;

        $unit->delete();

        ActivityLog::log('Unit Deleted', "Deleted unit: {$name} ({$shortName})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit deleted successfully.',
            ]);
        }

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
