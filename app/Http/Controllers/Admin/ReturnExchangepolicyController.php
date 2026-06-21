<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnExchangepolicy;
use App\Models\Product;
use Illuminate\Http\Request;

class ReturnExchangePolicyController extends Controller
{
    public function index()
    {
        $policies = ReturnExchangepolicy::with('product')->latest()->get();
        return view('admin.return-exchange-policy.index', compact('policies'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.return-exchange-policy.create', compact('products'));
    }

    public function show($id)
    {
        $policy = ReturnExchangepolicy::with('product')->findOrFail($id);
        return view('admin.return-exchange-policy.show', compact('policy'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'title'      => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        ReturnExchangepolicy::create([
            'product_id' => $request->product_id,
            'title'      => $request->title,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.return-exchange-policy.index')
            ->with('success', 'Return Exchange Policy Added Successfully');
    }

    public function edit($id)
    {
        $policy   = ReturnExchangepolicy::findOrFail($id);
        $products = Product::all();
        return view('admin.return-exchange-policy.create', compact('policy', 'products'));
    }

    public function update(Request $request, $id)
    {
        $policy = ReturnExchangepolicy::findOrFail($id);

        $request->validate([
            'product_id' => 'required',
            'title'      => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $policy->update([
            'product_id' => $request->product_id,
            'title'      => $request->title,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.return-exchange-policy.index')
            ->with('success', 'Return Exchange Policy Updated Successfully');
    }

    public function destroy($id)
    {
        ReturnExchangepolicy::findOrFail($id)->delete();
        return redirect()->route('admin.return-exchange-policy.index')
            ->with('success', 'Return Exchange Policy Deleted Successfully');
    }
}
