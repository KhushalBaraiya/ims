<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    // ================= CREATE =================
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    // ================= STORE =================
    public function show($id)
    {
        $user = User::with('role','orders','addresses','reviews','wishlist')->withCount('orders','reviews','wishlist')->findOrFail($id);
        return view('admin.user.show', compact('user'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'role_id'     => 'required|exists:roles,id',
            'name'        => 'required|string|min:2|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'password'    => 'required|string|min:8|max:64',
            'gender'      => 'required|in:Male,Female,Other',
            'phone'       => 'required|digits_between:7,15|unique:users,phone',
            'dob'         => 'nullable|date|before:today',
            'otp'         => 'nullable|digits:6',
            'address'     => 'required|string|min:5|max:500',
            'google_id'   => 'nullable|string|max:255',
            'facebook_id' => 'nullable|string|max:255',
            'status'      => 'required|in:active,inactive',
        ], [
            'role_id.required'    => 'Please select a role.',
            'role_id.exists'      => 'Selected role is invalid.',
            'name.required'       => 'Name is required.',
            'name.min'            => 'Name must be at least 2 characters.',
            'name.max'            => 'Name cannot exceed 255 characters.',
            'email.required'      => 'Email address is required.',
            'email.email'         => 'Please enter a valid email address.',
            'email.unique'        => 'This email is already registered.',
            'password.required'   => 'Password is required.',
            'password.min'        => 'Password must be at least 8 characters.',
            'password.max'        => 'Password cannot exceed 64 characters.',
            'gender.required'     => 'Please select a gender.',
            'gender.in'           => 'Invalid gender selected.',
            'phone.required'      => 'Phone number is required.',
            'phone.digits_between'=> 'Phone number must be between 7 and 15 digits.',
            'phone.unique'        => 'This phone number is already registered.',
            'dob.date'            => 'Please enter a valid date of birth.',
            'dob.before'          => 'Date of birth must be before today.',
            'otp.digits'          => 'OTP must contain digits only.',
            'otp.min'             => 'OTP must be exactly 6 digits.',
            'otp.max'             => 'OTP must be exactly 6 digits.',
            'address.max'         => 'Address cannot exceed 500 characters.',
            'address.required'    => 'Address is required.',
            'address.min'         => 'Address must be at least 5 characters.',
            'status.required'     => 'Please select a status.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $img       = $request->file('image');
            $imageName = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/user'), $imageName);
            $imagePath = 'uploads/user/' . $imageName;
        }

        User::create([
            'role_id'     => $request->role_id,
            'name'        => $request->name,
            'dob'         => $request->dob,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'otp'         => $request->otp,
            'gender'      => $request->gender,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'image'       => $imagePath,
            'google_id'   => $request->google_id,
            'facebook_id' => $request->facebook_id,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'User Created Successfully');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.user.create', compact('user', 'roles'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role_id'     => 'required|exists:roles,id',
            'name'        => 'required|string|min:2|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $id,
            'gender'      => 'required|in:Male,Female,Other',
            'phone'       => 'required|digits_between:7,15|unique:users,phone,' . $id,
            'password'    => 'nullable|string|min:8|max:64',
            'dob'         => 'nullable|date|before:today',
            'otp'         => 'nullable|digits:6',
            'address'     => 'required|string|min:5|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'google_id'   => 'nullable|string|max:255',
            'facebook_id' => 'nullable|string|max:255',
            'status'      => 'required|in:active,inactive',
        ], [
            'role_id.required'    => 'Please select a role.',
            'name.required'       => 'Name is required.',
            'name.min'            => 'Name must be at least 2 characters.',
            'email.required'      => 'Email address is required.',
            'email.email'         => 'Please enter a valid email address.',
            'email.unique'        => 'This email is already registered.',
            'gender.required'     => 'Please select a gender.',
            'phone.required'      => 'Phone number is required.',
            'phone.digits_between'=> 'Phone number must be between 7 and 15 digits.',
            'phone.unique'        => 'This phone number is already registered.',
            'password.min'        => 'Password must be at least 8 characters.',
            'address.required'    => 'Address is required.',
            'address.min'         => 'Address must be at least 5 characters.',
            'address.max'         => 'Address cannot exceed 500 characters.',
            'image.image'         => 'File must be an image.',
            'image.mimes'         => 'Only JPG, PNG, WEBP images are allowed.',
            'image.max'           => 'Profile picture must not exceed 2MB.',
        ]);

        $data = [
            'role_id'     => $request->role_id,
            'name'        => $request->name,
            'dob'         => $request->dob,
            'email'       => $request->email,
            'otp'         => $request->otp,
            'gender'      => $request->gender,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'google_id'   => $request->google_id,
            'facebook_id' => $request->facebook_id,
            'status'      => $request->status,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $img       = $request->file('image');
            $imageName = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/user'), $imageName);
            $data['image'] = 'uploads/user/' . $imageName;
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', 'User Updated Successfully');
    }

    // ================= DESTROY =================
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User Deleted Successfully');
    }
}
