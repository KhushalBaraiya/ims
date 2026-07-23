<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(): View
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    /**
     * Show the dedicated Change Password page.
     */
    public function showChangePassword(): View
    {
        return view('change-password');
    }

    /**
     * Update the authenticated user's profile details.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            // Ensure directory exists
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old photo if it exists
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

        ActivityLog::log('Profile Updated', 'User updated their personal profile details.');

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change the authenticated user's password.
     */
    public function changePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::log('Password Changed', 'User updated their account password.');

        return redirect()->route('profile.change-password')
            ->with('password_success', 'Password changed successfully.');
    }
}
