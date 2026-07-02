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

class RoleController extends Controller
{
    /**
     * Build the structured CRUD permissions matrix.
     * Returns: $crudPermissions[$module][$action] = $permissionObject|null
     */
    private function buildPermissionsMatrix(): array
    {
        $allPermissions = Permission::orderBy('name')->get();

        $crudPermissions = [];

        foreach ($allPermissions as $permission) {
            // Expect format: "module.action"
            if (! str_contains($permission->name, '.')) {
                continue;
            }

            [$module, $action] = explode('.', $permission->name, 2);

            // Normalize module name for display (underscores → spaces, title case)
            $displayModule = ucwords(str_replace('_', ' ', $module));

            if (! isset($crudPermissions[$displayModule])) {
                $crudPermissions[$displayModule] = [];
            }

            $crudPermissions[$displayModule][$action] = $permission;
        }

        // Sort modules alphabetically
        ksort($crudPermissions);

        return $crudPermissions;
    }

    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        Gate::authorize('roles.view');

        $roles = Role::withCount('permissions')->latest()->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        Gate::authorize('roles.create');

        $crudPermissions = $this->buildPermissionsMatrix();

        return view('roles.create', compact('crudPermissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('roles.create');

        $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100', 'unique:roles,name', 'regex:/^[a-z0-9\-]+$/'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.regex' => 'The system name may only contain lowercase letters, numbers, and hyphens.',
            'name.unique' => 'A role with this system name already exists.',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        // Store the human-readable display name as a separate attribute if your
        // roles table has a display_name column; otherwise we just use the name.
        // If the column exists, uncomment the line below:
        // $role->update(['display_name' => $request->display_name]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        ActivityLog::log('Role Created', "Created role: {$role->name} with ".count($request->permissions ?? []).' permissions');

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role (redirects to edit).
     */
    public function show(Role $role): RedirectResponse
    {
        Gate::authorize('roles.view');

        return redirect()->route('roles.edit', $role);
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role): View
    {
        Gate::authorize('roles.update');

        $crudPermissions = $this->buildPermissionsMatrix();
        $selectedPermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'crudPermissions', 'selectedPermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        Gate::authorize('roles.update');

        $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9\-]+$/', "unique:roles,name,{$role->id}"],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.regex' => 'The system name may only contain lowercase letters, numbers, and hyphens.',
        ]);

        $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions ?? []);

        ActivityLog::log('Role Updated', "Updated role: {$role->name} — synced ".count($request->permissions ?? []).' permissions');

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('roles.delete');

        // Prevent deleting super_admin
        if ($role->name === 'super_admin') {
            $message = 'The super_admin role cannot be deleted.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }

            return redirect()->route('roles.index')->with('error', $message);
        }

        $roleName = $role->name;
        $role->delete();

        ActivityLog::log('Role Deleted', "Deleted role: {$roleName}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
        }

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * Bulk delete roles (cannot delete super_admin).
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        Gate::authorize('roles.delete');

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $roles = Role::whereIn('id', $ids)->get();
        $deleted = 0;
        foreach ($roles as $role) {
            if ($role->name === 'super_admin') continue;
            $role->delete();
            $deleted++;
        }

        ActivityLog::log('Roles Bulk Deleted', "Deleted {$deleted} role(s).");

        return response()->json(['success' => true, 'message' => "{$deleted} role(s) deleted successfully."]);
    }
}
