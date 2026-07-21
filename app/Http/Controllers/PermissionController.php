<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    private array $validActions = ['view', 'create', 'update', 'delete', 'own'];

    /**
     * Display a listing of permissions grouped by module with pagination.
     */
    public function index(Request $request): View
    {
        Gate::authorize('permissions.view');

        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('module')) {
            $query->where('name', 'like', "{$request->module}.%");
        }

        $perPageInput = $request->input('per_page', 10);

        if ($perPageInput === 'all') {
            $permissionsCollection = $query->orderBy('name')->get();
            $paginatedPermissions = null;
        } else {
            $perPage = in_array((int) $perPageInput, [5, 10, 15, 25, 50, 100]) ? (int) $perPageInput : 10;
            $paginatedPermissions = $query->orderBy('name')->paginate($perPage)->withQueryString();
            $permissionsCollection = collect($paginatedPermissions->items());
        }

        // Group by module
        $grouped = [];
        foreach ($permissionsCollection as $permission) {
            if (str_contains($permission->name, '.')) {
                [$module] = explode('.', $permission->name, 2);
            } else {
                $module = 'other';
            }
            $grouped[$module][] = $permission;
        }
        ksort($grouped);

        // All unique modules for filter dropdown
        $allModules = Permission::orderBy('name')->get()
            ->map(fn($p) => str_contains($p->name, '.') ? explode('.', $p->name, 2)[0] : 'other')
            ->unique()->sort()->values();

        return view('permissions.index', compact('grouped', 'allModules', 'paginatedPermissions', 'perPageInput'));
    }

    /**
     * Show form to create a new permission.
     */
    public function create(): View
    {
        Gate::authorize('permissions.create');

        $allModules = Permission::orderBy('name')->get()
            ->filter(fn($p) => str_contains($p->name, '.'))
            ->map(fn($p) => explode('.', $p->name, 2)[0])
            ->unique()->sort()->values();

        $allRoles = Role::orderBy('name')->get();

        return view('permissions.create', compact('allModules', 'allRoles'));
    }

    /**
     * Store newly created permission(s) and assign to roles.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('permissions.create');

        $request->validate([
            'module' => ['required', 'string', 'max:60', 'regex:/^[a-z0-9_]+$/'],
        ], [
            'module.regex' => 'Module may only contain lowercase letters, numbers, and underscores.',
        ]);

        $module = strtolower(trim($request->module));

        // Collect actions from checkboxes
        $actions = $request->input('actions', []);
        if (! is_array($actions)) {
            $actions = [$actions];
        }

        if ($request->filled('action')) {
            $actions[] = $request->action;
        }

        if ($request->filled('custom_action')) {
            $actions[] = $request->custom_action;
        }

        // Clean & sanitize action names
        $actions = array_unique(array_filter(array_map(function ($act) {
            return strtolower(trim(preg_replace('/[^a-z0-9_]/i', '', $act)));
        }, $actions)));

        if (empty($actions)) {
            return back()->withInput()->withErrors(['actions' => 'Please select at least one action or enter a custom action.']);
        }

        $createdPermissions = [];
        $createdNames = [];

        foreach ($actions as $act) {
            $name = $module . '.' . $act;
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web']
            );
            $createdPermissions[$act] = $permission;
            $createdNames[] = $name;
        }

        // Assign to Roles based on matrix input: role_actions[role_id][action]
        $roleActions = $request->input('role_actions', []);
        $assignedCount = 0;

        if (! empty($roleActions) && is_array($roleActions)) {
            foreach ($roleActions as $roleId => $assignedActions) {
                $role = Role::find($roleId);
                if (! $role || ! is_array($assignedActions)) {
                    continue;
                }

                $permsToGive = [];
                foreach ($assignedActions as $act) {
                    if (isset($createdPermissions[$act])) {
                        $permsToGive[] = $createdPermissions[$act]->name;
                    }
                }

                if (! empty($permsToGive)) {
                    $role->givePermissionTo($permsToGive);
                    $assignedCount++;
                }
            }
        }

        $summaryText = count($createdNames) . ' permission(s) created for module "' . $module . '"';
        if ($assignedCount > 0) {
            $summaryText .= ' and assigned to ' . $assignedCount . ' role(s)';
        }

        ActivityLog::log('Permission Created', $summaryText);

        return redirect()->route('permissions.index')->with('success', $summaryText . ' successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission): View
    {
        Gate::authorize('permissions.view');

        $permission->load('roles');

        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing a permission.
     */
    public function edit(Permission $permission): View
    {
        Gate::authorize('permissions.update');

        $allModules = Permission::orderBy('name')->get()
            ->filter(fn($p) => str_contains($p->name, '.'))
            ->map(fn($p) => explode('.', $p->name, 2)[0])
            ->unique()->sort()->values();

        $allRoles = Role::orderBy('name')->get();
        $assignedRoleIds = $permission->roles->pluck('id')->toArray();

        [$currentModule, $currentAction] = str_contains($permission->name, '.')
            ? explode('.', $permission->name, 2)
            : [$permission->name, ''];

        return view('permissions.edit', compact(
            'permission',
            'allModules',
            'allRoles',
            'assignedRoleIds',
            'currentModule',
            'currentAction'
        ));
    }

    /**
     * Update the specified permission and its assigned roles.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        Gate::authorize('permissions.update');

        $request->validate([
            'module' => ['required', 'string', 'max:60', 'regex:/^[a-z0-9_]+$/'],
            'action' => ['required', 'string', 'max:60', 'regex:/^[a-z0-9_]+$/'],
        ], [
            'module.regex' => 'Module may only contain lowercase letters, numbers, and underscores.',
            'action.regex' => 'Action may only contain lowercase letters, numbers, and underscores.',
        ]);

        $newName = $request->module . '.' . $request->action;

        if ($newName !== $permission->name && Permission::where('name', $newName)->exists()) {
            return back()->withInput()->withErrors(['action' => "Permission '{$newName}' already exists."]);
        }

        $oldName = $permission->name;
        $permission->update(['name' => $newName]);

        // Sync Roles if roles input is present
        if ($request->has('roles')) {
            $roleIds = $request->input('roles', []);
            $roles = Role::whereIn('id', $roleIds)->get();
            $permission->syncRoles($roles);
        }

        ActivityLog::log('Permission Updated', "Updated permission: {$oldName} → {$newName}");

        return redirect()->route('permissions.index')->with('success', "Permission updated to '{$newName}' successfully.");
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('permissions.delete');

        $name = $permission->name;
        $permission->delete();

        ActivityLog::log('Permission Deleted', "Deleted permission: {$name}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Permission '{$name}' deleted successfully."]);
        }

        return redirect()->route('permissions.index')->with('success', "Permission '{$name}' deleted successfully.");
    }

    /**
     * Bulk delete permissions.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('permissions.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $permissions = Permission::whereIn('id', $ids)->get();
        $deleted = 0;
        foreach ($permissions as $p) {
            ActivityLog::log('Permission Deleted', "Deleted permission: {$p->name}");
            $p->delete();
            $deleted++;
        }

        return response()->json(['success' => true, 'message' => "{$deleted} permission(s) deleted successfully."]);
    }
}
