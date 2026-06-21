<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\WishList;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    public function index()
    {
        $wishlists = WishList::with('user','product')->latest()->get();
        return view('admin.wishlist.index', compact('wishlists'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();

        return view('admin.wishlist.create', compact('users','products'));
    }

    public function show($id)
    {
        $wishlist = WishList::with('user','product')->findOrFail($id);
        return view('admin.wishlist.show', compact('wishlist'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required',
            'product_id' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        WishList::create([
            'user_id'    => $request->user_id,
            'product_id' => $request->product_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.wishlist.index')
            ->with('success','Wishlist Added Successfully');
    }

    public function edit($id)
    {
        $wishlist = WishList::findOrFail($id);
        $users = User::all();
        $products = Product::all();

        return view('admin.wishlist.create', compact('wishlist','users','products'));
    }

    public function update(Request $request, $id)
    {
        $wishlist = WishList::findOrFail($id);

        $request->validate([
            'user_id'    => 'required',
            'product_id' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        $wishlist->update([
            'user_id'    => $request->user_id,
            'product_id' => $request->product_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.wishlist.index')
            ->with('success','Wishlist Updated Successfully');
    }

    public function destroy($id)
    {
        $wishlist = WishList::findOrFail($id);
        $wishlist->delete();

        return redirect()->route('admin.wishlist.index')
            ->with('success','Wishlist Deleted Successfully');
    }
}