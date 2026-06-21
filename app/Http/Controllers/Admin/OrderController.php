<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'product')->latest()->get();
        return view('admin.order.index', compact('orders'));
    }

    public function create()
    {
        $users    = User::all();
        $products = Product::all();
        return view('admin.order.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'product_id'       => 'required|exists:products,id',
            'order_number'     => 'required|unique:orders,order_number',
            'order_date'       => 'required|date',
            'quantity'         => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'total_amount'     => 'required|numeric|min:0',
            'payment_method'   => 'required',
            'payment_status'   => 'required',
            'shipping_address' => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        Order::create($request->only([
            'user_id', 'product_id', 'order_number', 'order_date',
            'quantity', 'price', 'total_amount', 'payment_method',
            'payment_status', 'shipping_address', 'status',
        ]));

        return redirect()->route('admin.order.index')
            ->with('success', 'Order Added Successfully');
    }

    public function show($id)
    {
        $order = Order::with('user', 'product', 'items')->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    public function edit($id)
    {
        $order    = Order::findOrFail($id);
        $users    = User::all();
        $products = Product::all();
        return view('admin.order.create', compact('order', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'product_id'       => 'required|exists:products,id',
            'order_number'     => 'required|unique:orders,order_number,' . $id,
            'order_date'       => 'required|date',
            'quantity'         => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'total_amount'     => 'required|numeric|min:0',
            'payment_method'   => 'required',
            'payment_status'   => 'required',
            'shipping_address' => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        $order->update($request->only([
            'user_id', 'product_id', 'order_number', 'order_date',
            'quantity', 'price', 'total_amount', 'payment_method',
            'payment_status', 'shipping_address', 'status',
        ]));

        return redirect()->route('admin.order.index')
            ->with('success', 'Order Updated Successfully');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->route('admin.order.index')
            ->with('success', 'Order Deleted Successfully');
    }
}
