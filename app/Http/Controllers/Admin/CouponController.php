<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('product')->get();
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.coupon.create', compact('products'));
    }

    
    public function show($id)
    {
        $coupon = Coupon::with('product')->findOrFail($id);
        return view('admin.coupon.show', compact('coupon'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'code'                    => 'required|string|max:255|unique:coupons,code',
            'name'                    => 'required|string|max:255',
            'product_id'              => 'required|exists:products,id',
            'discount_type'           => 'required|in:percentage,fixed',
            'discount_value'          => 'required|numeric',
            'start_date'              => 'required|date',
            'end_date'                => 'required|date|after_or_equal:start_date',
            'minimum_order_amount'    => 'nullable|numeric',
            'maximum_discount_value'  => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        Coupon::create([
            'code'                   => $request->code,
            'name'                   => $request->name,
            'product_id'             => $request->product_id,
            'discount_type'          => $request->discount_type,
            'discount_value'         => $request->discount_value,
            'start_date'             => $request->start_date,
            'end_date'               => $request->end_date,
            'minimum_order_amount'   => $request->minimum_order_amount,
            'maximum_discount_value' => $request->maximum_discount_value,
            'status'                 => $request->status,
        ]);

        return redirect()
            ->route('admin.coupon.index')
            ->with('success', 'Coupon added successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $products = Product::all();

        return view('admin.coupon.create', compact('coupon', 'products'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'                    => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'name'                    => 'required|string|max:255',
            'product_id'              => 'required|exists:products,id',
            'discount_type'           => 'required|in:percentage,fixed',
            'discount_value'          => 'required|numeric',
            'start_date'              => 'required|date',
            'end_date'                => 'required|date|after_or_equal:start_date',
            'minimum_order_amount'    => 'nullable|numeric',
            'maximum_discount_value'  => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon->update([
            'code'                   => $request->code,
            'name'                   => $request->name,
            'product_id'             => $request->product_id,
            'discount_type'          => $request->discount_type,
            'discount_value'         => $request->discount_value,
            'start_date'             => $request->start_date,
            'end_date'               => $request->end_date,
            'minimum_order_amount'   => $request->minimum_order_amount,
            'maximum_discount_value' => $request->maximum_discount_value,
            'status'                 => $request->status,
        ]);

        return redirect()
            ->route('admin.coupon.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()
            ->route('admin.coupon.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}
