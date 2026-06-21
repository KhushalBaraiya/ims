<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductShipping;
use Illuminate\Http\Request;

class ProductShippingController extends Controller
{
    public function index()
    {
        $shippings = ProductShipping::latest()->get();
        return view('admin.product-shipping.index', compact('shippings'));
    }

    public function create()
    {
        return view('admin.product-shipping.create');
    }

    public function show($id)
    {
        $shipping = ProductShipping::findOrFail($id);
        return view('admin.product-shipping.show', compact('shipping'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'delivery_time' => 'required|string|max:100',
            'charge'        => 'required|numeric|min:0',
            'free_above'    => 'nullable|numeric|min:0',
            'cod_available' => 'required|in:0,1',
            'status' => 'required|in:active,inactive',
        ]);

        ProductShipping::create($request->only(
            'title',
            'delivery_time',
            'charge',
            'free_above',
            'cod_available',
            'status'
        ));

        return redirect()->route('admin.product-shipping.index')
            ->with('success', 'Product Shipping Method Added Successfully');
    }

    public function edit($id)
    {
        $shipping = ProductShipping::findOrFail($id);
        return view('admin.product-shipping.create', compact('shipping'));
    }

    public function update(Request $request, $id)
    {
        $shipping = ProductShipping::findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'delivery_time' => 'required|string|max:100',
            'charge'        => 'required|numeric|min:0',
            'free_above'    => 'nullable|numeric|min:0',
            'cod_available' => 'required|in:0,1',
            'status' => 'required|in:active,inactive',
        ]);

        $shipping->update($request->only(
            'title',
            'delivery_time',
            'charge',
            'free_above',
            'cod_available',
            'status'
        ));

        return redirect()->route('admin.product-shipping.index')
            ->with('success', 'Product Shipping Method Updated Successfully');
    }

    public function destroy($id)
    {
        ProductShipping::findOrFail($id)->delete();
        return redirect()->route('admin.product-shipping.index')
            ->with('success', 'Product Shipping Method Deleted Successfully');
    }
}
