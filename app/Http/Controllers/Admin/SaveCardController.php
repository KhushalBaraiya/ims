<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaveCard;
use App\Models\User;
use Illuminate\Http\Request;

class SaveCardController extends Controller
{
    public function index()
    {
        $cards = SaveCard::with('user')->latest()->get();
        return view('admin.save-card.index', compact('cards'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.save-card.create', compact('users'));
    }

    public function show($id)
    {
        $card = SaveCard::with('user')->findOrFail($id);
        return view('admin.save-card.show', compact('card'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'card_holder_name' => 'required|string|min:2|max:255',
            'last_four_digits' => 'required|digits:4',
            'expiry_month'     => 'required|digits_between:1,2',
            'expiry_year'      => 'required|digits:4|numeric|min:' . date('Y'),
            'card_brand'       => 'nullable|string|max:50',
            'gateway_token'    => 'nullable|string|max:255',
            'status'           => 'required|in:active,inactive',
        ], [
            'user_id.required'          => 'Please select a user',
            'user_id.exists'            => 'Selected user does not exist',
            'card_holder_name.required' => 'Card holder name is required',
            'card_holder_name.min'      => 'Card holder name must be at least 2 characters',
            'card_holder_name.max'      => 'Card holder name cannot exceed 255 characters',
            'last_four_digits.required' => 'Last 4 digits of card are required',
            'last_four_digits.digits'   => 'Last 4 digits must be exactly 4 numeric digits',
            'expiry_month.required'     => 'Expiry month is required',
            'expiry_month.digits_between' => 'Expiry month must be a valid month between 1 and 12',
            'expiry_year.required'      => 'Expiry year is required',
            'expiry_year.digits'        => 'Expiry year must be 4 digits',
            'expiry_year.min'           => 'Card has already expired. Please enter a valid year',
            'status.required'           => 'Status is required',
            'status.in'                 => 'Status must be either active or inactive',
        ]);

        SaveCard::create($request->only(
            'user_id',
            'card_holder_name',
            'last_four_digits',
            'expiry_month',
            'expiry_year',
            'card_brand',
            'gateway_token',
            'status'
        ));

        return redirect()->route('admin.save-card.index')
            ->with('success', 'Card Saved Successfully');
    }

    public function edit($id)
    {
        $card  = SaveCard::findOrFail($id);
        $users = User::all();
        return view('admin.save-card.create', compact('card', 'users'));
    }

    public function update(Request $request, $id)
    {
        $card = SaveCard::findOrFail($id);

        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'card_holder_name' => 'required|string|min:2|max:255',
            'last_four_digits' => 'required|digits:4',
            'expiry_month'     => 'required|digits_between:1,2',
            'expiry_year'      => 'required|digits:4|numeric|min:' . date('Y'),
            'card_brand'       => 'nullable|string|max:50',
            'gateway_token'    => 'nullable|string|max:255',
            'status'           => 'required|in:active,inactive',
        ], [
            'user_id.required'          => 'Please select a user',
            'user_id.exists'            => 'Selected user does not exist',
            'card_holder_name.required' => 'Card holder name is required',
            'card_holder_name.min'      => 'Card holder name must be at least 2 characters',
            'card_holder_name.max'      => 'Card holder name cannot exceed 255 characters',
            'last_four_digits.required' => 'Last 4 digits of card are required',
            'last_four_digits.digits'   => 'Last 4 digits must be exactly 4 numeric digits',
            'expiry_month.required'     => 'Expiry month is required',
            'expiry_month.digits_between' => 'Expiry month must be a valid month between 1 and 12',
            'expiry_year.required'      => 'Expiry year is required',
            'expiry_year.digits'        => 'Expiry year must be 4 digits',
            'expiry_year.min'           => 'Card has already expired. Please enter a valid year',
            'status.required'           => 'Status is required',
            'status.in'                 => 'Status must be either active or inactive',
        ]);

        $card->update($request->only(
            'user_id',
            'card_holder_name',
            'last_four_digits',
            'expiry_month',
            'expiry_year',
            'card_brand',
            'gateway_token',
            'status'
        ));

        return redirect()->route('admin.save-card.index')
            ->with('success', 'Save Card Updated Successfully');
    }

    public function destroy($id)
    {
        SaveCard::findOrFail($id)->delete();
        return redirect()->route('admin.save-card.index')
            ->with('success', 'Save Card Deleted Successfully');
    }
}
