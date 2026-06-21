<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $items = OrderItem::with('order', 'product')->latest()->get();
        return view('admin.order-item.index', compact('items'));
    }

    public function create()
    {
        $orders   = Order::all();
        $products = Product::all();
        return view('admin.order-item.create', compact('orders', 'products'));
    }

    public function show($id)
    {
        $item = OrderItem::with('order','product')->findOrFail($id);
        return view('admin.order-item.show', compact('item'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'total_price'   => 'required|numeric|min:0',
        ]);

        // Auto-fill product_name and product_image from selected product
        $product = Product::findOrFail($request->product_id);

        OrderItem::create([
            'order_id'      => $request->order_id,
            'product_id'    => $request->product_id,
            'product_name'  => $product->name,
            'product_image' => is_array($product->images) && count($product->images)
                                ? $product->images[0]
                                : ($product->image ?? ''),
            'quantity'      => $request->quantity,
            'price'         => $request->price,
            'total_price'   => $request->total_price,
        ]);

        return redirect()->route('admin.order-item.index')
            ->with('success', 'Order Item Added Successfully');
    }

    public function edit($id)
    {
        $item     = OrderItem::findOrFail($id);
        $orders   = Order::all();
        $products = Product::all();
        return view('admin.order-item.create', compact('item', 'orders', 'products'));
    }

    public function update(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);

        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'total_price'   => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);

        $item->update([
            'order_id'      => $request->order_id,
            'product_id'    => $request->product_id,
            'product_name'  => $product->name,
            'product_image' => is_array($product->images) && count($product->images)
                                ? $product->images[0]
                                : ($product->image ?? ''),
            'quantity'      => $request->quantity,
            'price'         => $request->price,
            'total_price'   => $request->total_price,
        ]);

        return redirect()->route('admin.order-item.index')
            ->with('success', 'Order Item Updated Successfully');
    }

    public function destroy($id)
    {
        OrderItem::findOrFail($id)->delete();
        return redirect()->route('admin.order-item.index')
            ->with('success', 'Order Item Deleted Successfully');
    }
}
