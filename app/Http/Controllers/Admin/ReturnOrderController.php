<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnOrder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class ReturnOrderController extends Controller
{
    public function index()
    {
        $returns = ReturnOrder::with('order', 'user', 'product')->latest()->get();
        return view('admin.return-order.index', compact('returns'));
    }

    public function create()
    {
        $orders   = Order::all();
        $users    = User::all();
        $products = Product::all();
        return view('admin.return-order.create', compact('orders', 'users', 'products'));
    }

    public function show($id)
    {
        $return = ReturnOrder::with('order','user','product')->findOrFail($id);
        return view('admin.return-order.show', compact('return'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required',
            'user_id'       => 'required',
            'product_id'    => 'required',
            'quantity'      => 'required|integer|min:1',
            'return_type'   => 'required|in:return,exchange,refund',
            'refund_amount' => 'required|numeric|min:0',
            'status'        => 'required',
            'return_date'   => 'nullable|date',
        ]);

        ReturnOrder::create($request->all());

        return redirect()->route('admin.return-order.index')
            ->with('success', 'Return Order Added Successfully');
    }

    public function edit($id)
    {
        $return   = ReturnOrder::findOrFail($id);
        $orders   = Order::all();
        $users    = User::all();
        $products = Product::all();
        return view('admin.return-order.create', compact('return', 'orders', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        $return = ReturnOrder::findOrFail($id);

        $request->validate([
            'order_id'      => 'required',
            'user_id'       => 'required',
            'product_id'    => 'required',
            'quantity'      => 'required|integer|min:1',
            'return_type'   => 'required|in:return,exchange,refund',
            'refund_amount' => 'required|numeric|min:0',
            'status'        => 'required',
            'return_date'   => 'nullable|date',
        ]);

        $return->update($request->all());

        return redirect()->route('admin.return-order.index')
            ->with('success', 'Return Order Updated Successfully');
    }

    public function destroy($id)
    {
        ReturnOrder::findOrFail($id)->delete();
        return redirect()->route('admin.return-order.index')
            ->with('success', 'Return Order Deleted Successfully');
    }
}
