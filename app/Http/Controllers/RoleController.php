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
     * Returns array with 'matrix', 'actions', and 'others'.
     */
    private function buildPermissionsMatrix(): array
    {
        $allPermissions = Permission::orderBy('name')->get();

        $crudPermissions = [];
        $otherPermissions = [];
        $actionsList = ['view', 'own', 'create', 'update', 'delete'];

        foreach ($allPermissions as $permission) {
            // Standalone permissions without dot
            if (! str_contains($permission->name, '.')) {
                $otherPermissions[] = $permission;
                continue;
            }

            [$module, $action] = explode('.', $permission->name, 2);

            if (! in_array($action, $actionsList)) {
                $actionsList[] = $action;
            }

            // Normalize module name for display (underscores → spaces, title case)
            $displayModule = ucwords(str_replace('_', ' ', $module));

            if (! isset($crudPermissions[$displayModule])) {
                $crudPermissions[$displayModule] = [];
            }

            $crudPermissions[$displayModule][$action] = $permission;
        }

        // Sort modules alphabetically
        ksort($crudPermissions);

        return [
            'matrix'  => $crudPermissions,
            'actions' => $actionsList,
            'others'  => $otherPermissions,
        ];
    }

    /**
     * Display a listing of roles.
     */
    public function index(Request $request): View
    {
        Gate::authorize('roles.view');

        $query = Role::withCount('permissions');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $roles = $query->latest()->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        Gate::authorize('roles.create');

        $data = $this->buildPermissionsMatrix();
        $crudPermissions = $data['matrix'];
        $allActions = $data['actions'];
        $otherPermissions = $data['others'];

        return view('roles.create', compact('crudPermissions', 'allActions', 'otherPermissions'));
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

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        ActivityLog::log('Role Created', "Created role: {$role->name} with ".count($request->permissions ?? []).' permissions');

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): View
    {
        Gate::authorize('roles.view');

        $role->load('permissions', 'users');

        $data = $this->buildPermissionsMatrix();
        $crudPermissions = $data['matrix'];
        $allActions = $data['actions'];
        $otherPermissions = $data['others'];
        $selectedPermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.show', compact('role', 'crudPermissions', 'allActions', 'otherPermissions', 'selectedPermissions'));
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role): View
    {
        Gate::authorize('roles.update');

        $data = $this->buildPermissionsMatrix();
        $crudPermissions = $data['matrix'];
        $allActions = $data['actions'];
        $otherPermissions = $data['others'];
        $selectedPermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'crudPermissions', 'allActions', 'otherPermissions', 'selectedPermissions'));
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
