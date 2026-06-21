<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class CartItemController extends Controller
{

    // ===============================
    // Cart Item List
    // ===============================
    public function index()
    {
        $cartItems = CartItem::with(['product','user'])->latest()->get();

        return view('admin.cart-item.index', compact('cartItems'));
    }


    // ===============================
    // Create Page
    // ===============================
    public function create()
    {
        $products = Product::all();
        $users = User::all();

        return view('admin.cart-item.create', compact('products','users'));
    }


    // ===============================
    // Store Cart Item
    // ===============================
    public function store(Request $request)
    {

        $request->validate([
            'product_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        CartItem::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.cart-item.index')
        ->with('success','Cart Item Created Successfully');
    }


    // ===============================
    // Edit Page
    // ===============================
    public function edit($id)
    {
        $cartItem = CartItem::findOrFail($id);

        $products = Product::all();
        $users = User::all();

        return view('admin.cart-item.create', compact('cartItem','products','users'));
    }


    // ===============================
    // Update Cart
    // ===============================
    public function update(Request $request, $id)
    {

        $cartItem = CartItem::findOrFail($id);

        $request->validate([
            'product_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $cartItem->update([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.cart-item.index')
        ->with('success','Cart Item Updated Successfully');
    }


    // ===============================
    // Delete
    // ===============================
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);

        $cartItem->delete();

        return redirect()->route('admin.cart-item.index')
        ->with('success','Cart Item Deleted Successfully');
    }
    // ===============================
// Show Cart Item Detail
// ===============================
public function show($id)
{
    $cartItem = CartItem::with(['product', 'user'])->findOrFail($id);

    return view('admin.cart-item.show', compact('cartItem'));
}

}
