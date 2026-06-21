<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::with('user')->latest()->get();
        return view('admin.user-address.index', compact('addresses'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.user-address.create', compact('users'));
    }

    public function show($id)
    {
        $address = UserAddress::with('user')->findOrFail($id);
        return view('admin.user-address.show', compact('address'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'user_id'             => 'required|exists:users,id',
            'first_name'          => 'required|string|min:2|max:100',
            'last_name'           => 'required|string|min:2|max:100',
            'mobile_country_code' => 'nullable|string|max:10',
            'mobile_no'           => 'required|digits_between:7,15',
            'email'               => 'nullable|email|max:255',
            'house_no'            => 'required|string|max:50',
            'landmark'            => 'nullable|string|max:255',
            'locality_area'       => 'nullable|string|max:255',
            'pincode'             => 'required|digits:6',
            'city'                => 'required|string|max:100',
            'state'               => 'required|string|max:100',
            'country'             => 'required|string|max:100',
            'address_type'        => 'required|in:home,work,other',
            'status'              => 'required|in:active,inactive',
        ], [
            'user_id.required'             => 'Please select a user.',
            'user_id.exists'               => 'Selected user is invalid.',
            'first_name.required'          => 'First name is required.',
            'first_name.min'               => 'First name must be at least 2 characters.',
            'first_name.max'               => 'First name cannot exceed 100 characters.',
            'last_name.required'           => 'Last name is required.',
            'last_name.min'                => 'Last name must be at least 2 characters.',
            'last_name.max'                => 'Last name cannot exceed 100 characters.',
            'mobile_no.required'           => 'Mobile number is required.',
            'mobile_no.digits_between'     => 'Mobile number must be between 7 and 15 digits.',
            'email.email'                  => 'Please enter a valid email address.',
            'house_no.required'            => 'House number is required.',
            'pincode.required'             => 'Pincode is required.',
            'pincode.digits'               => 'Pincode must be exactly 6 digits.',
            'city.required'                => 'City is required.',
            'state.required'               => 'State is required.',
            'country.required'             => 'Country is required.',
            'address_type.required'        => 'Please select an address type.',
            'address_type.in'              => 'Invalid address type selected.',
        ]);

        UserAddress::create($request->only([
            'user_id', 'first_name', 'last_name', 'mobile_country_code',
            'mobile_no', 'email', 'house_no', 'landmark', 'locality_area',
            'pincode', 'city', 'state', 'country', 'address_type', 'status',
        ]));

        return redirect()->route('admin.user-address.index')
            ->with('success', 'User Address Added Successfully');
    }

    public function edit($id)
    {
        $address = UserAddress::findOrFail($id);
        $users   = User::all();
        return view('admin.user-address.create', compact('address', 'users'));
    }

    public function update(Request $request, $id)
    {
        $address = UserAddress::findOrFail($id);

        $request->validate([
            'user_id'             => 'required|exists:users,id',
            'first_name'          => 'required|string|min:2|max:100',
            'last_name'           => 'required|string|min:2|max:100',
            'mobile_country_code' => 'nullable|string|max:10',
            'mobile_no'           => 'required|digits_between:7,15',
            'email'               => 'nullable|email|max:255',
            'house_no'            => 'required|string|max:50',
            'landmark'            => 'nullable|string|max:255',
            'locality_area'       => 'nullable|string|max:255',
            'pincode'             => 'required|digits:6',
            'city'                => 'required|string|max:100',
            'state'               => 'required|string|max:100',
            'country'             => 'required|string|max:100',
            'address_type'        => 'required|in:home,work,other',
            'status'              => 'required|in:active,inactive',
        ], [
            'user_id.required'             => 'Please select a user.',
            'first_name.required'          => 'First name is required.',
            'first_name.min'               => 'First name must be at least 2 characters.',
            'last_name.required'           => 'Last name is required.',
            'last_name.min'                => 'Last name must be at least 2 characters.',
            'mobile_no.required'           => 'Mobile number is required.',
            'mobile_no.digits_between'     => 'Mobile number must be between 7 and 15 digits.',
            'email.email'                  => 'Please enter a valid email address.',
            'house_no.required'            => 'House number is required.',
            'pincode.required'             => 'Pincode is required.',
            'pincode.digits'               => 'Pincode must be exactly 6 digits.',
            'city.required'                => 'City is required.',
            'state.required'               => 'State is required.',
            'country.required'             => 'Country is required.',
            'address_type.required'        => 'Please select an address type.',
        ]);

        $address->update($request->only([
            'user_id', 'first_name', 'last_name', 'mobile_country_code',
            'mobile_no', 'email', 'house_no', 'landmark', 'locality_area',
            'pincode', 'city', 'state', 'country', 'address_type', 'status',
        ]));

        return redirect()->route('admin.user-address.index')
            ->with('success', 'User Address Updated Successfully');
    }

    public function destroy($id)
    {
        UserAddress::findOrFail($id)->delete();
        return redirect()->route('admin.user-address.index')
            ->with('success', 'User Address Deleted Successfully');
    }
}
