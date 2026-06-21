<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // ── Show profile edit form ─────────────────────────────────────
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // ── Update profile info + avatar ───────────────────────────────
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|min:2|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|digits_between:7,15|unique:users,phone,' . $user->id,
            'gender'   => 'nullable|in:Male,Female,Other',
            'dob'      => 'nullable|date|before:today',
            'address'  => 'nullable|string|max:500',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'  => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique'   => 'This email is already taken.',
            'phone.unique'   => 'This phone number is already taken.',
            'image.image'    => 'File must be an image.',
            'image.mimes'    => 'Only JPG, PNG, WEBP allowed.',
            'image.max'      => 'Image must not exceed 2MB.',
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'gender'  => $request->gender,
            'dob'     => $request->dob ?: null,
            'address' => $request->address,
        ];

        // Handle avatar upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $img       = $request->file('image');
            $imageName = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/user'), $imageName);
            $data['image'] = 'uploads/user/' . $imageName;
        }

        $user->update($data);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    // ── Change password ────────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|max:64|confirmed',
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required'         => 'New password is required.',
            'password.min'              => 'Password must be at least 8 characters.',
            'password.confirmed'        => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password changed successfully.');
    }

    // ── Delete account ─────────────────────────────────────────────
    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        Auth::logout();

        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
