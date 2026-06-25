<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('users.view');

        $users = User::with('roles')->latest()->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('users.create');

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        Gate::authorize('users.create');

        $validated = $request->validated();
        $validated['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['profile_photo'] = $filename;
        }

        $user = User::create($validated);
        $user->assignRole($request->role);

        ActivityLog::log('User Created', "Created new user account: {$user->name} ({$user->email}) as {$request->role}");

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        Gate::authorize('users.view');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        Gate::authorize('users.update');

        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('users.update');

        $validated = $request->except('password');

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old profile picture if exists
            if ($user->profile_photo) {
                $oldFilePath = $uploadPath . '/' . $user->profile_photo;
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }

            $file->move($uploadPath, $filename);
            $validated['profile_photo'] = $filename;
        }

        $user->update($validated);
        $user->syncRoles([$request->role]);

        ActivityLog::log('User Updated', "Updated user account settings for: {$user->name} ({$user->email})");

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus(User $user): JsonResponse
    {
        Gate::authorize('users.update');

        if (auth()->id() === $user->id) {
            return response()->json(['success' => false, 'message' => 'You cannot change your own status.'], 403);
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        ActivityLog::log('User Status Changed', "Changed user status: {$user->name} → {$user->status}");

        return response()->json([
            'success' => true,
            'status'  => $user->status,
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('users.delete');

        $userName = $user->name;
        $userEmail = $user->email;

        // Prevent self-deletion
        if (auth()->id() === $user->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account.',
                ], 403);
            }
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        ActivityLog::log('User Deleted', "Deleted user account: {$userName} ({$userEmail})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
