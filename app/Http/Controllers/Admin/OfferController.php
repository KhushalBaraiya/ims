<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::all();
        return view('admin.offer.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offer.create');
    }

    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        return view('admin.offer.show', compact('offer'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'offer_name'          => 'required|string|max:255',
            'offer_code'          => 'required|string|max:255|unique:offers,offer_code',
            'offer_type'          => 'required|string',
            'discount_value'      => 'required|numeric',
            'offer_value'         => 'required|numeric',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'is_active'           => 'required|in:0,1',
            'current_usage_count' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        Offer::create([
            'offer_name'          => $request->offer_name,
            'offer_code'          => $request->offer_code,
            'offer_type'          => $request->offer_type,
            'discount_value'      => $request->discount_value,
            'offer_value'         => $request->offer_value,
            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
            'is_active'           => $request->is_active,
            'current_usage_count' => $request->current_usage_count ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.offer.index')
            ->with('success', 'Offer added successfully.');
    }

    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        return view('admin.offer.create', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        // dd($offer_name->all());
        $request->validate([
            'offer_name'          => 'required|string|max:255',
            'offer_code'          => 'required|string|max:255|unique:offers,offer_code,' . $offer->id,
            'offer_type'          => 'required|string',
            'discount_value'      => 'required|numeric',
            'offer_value'         => 'required|numeric',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'is_active'           => 'required|in:0,1',
            'current_usage_count' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
            ]);
            
        $offer->update([
            'offer_name'          => $request->offer_name,
            'offer_code'          => $request->offer_code,
            'offer_type'          => $request->offer_type,
            'discount_value'      => $request->discount_value,
            'offer_value'         => $request->offer_value,
            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
            'is_active'           => $request->is_active,
            'current_usage_count' => $request->current_usage_count ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.offer.index')
            ->with('success', 'Offer updated successfully.');
    }

    public function destroy($id)
    {
        Offer::findOrFail($id)->delete();
        return redirect()->route('admin.offer.index')
            ->with('success', 'Offer deleted successfully.');
    }
}
