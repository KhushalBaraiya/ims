<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')->latest()->get();

        return view('admin.stock.index', compact('stocks'));
    }

    public function create()
    {
        $products = Product::all();

        return view('admin.stock.create', compact('products'));
    }

    public function show($id)
    {
        $stock = Stock::with('product')->findOrFail($id);
        return view('admin.stock.show', compact('stock'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'type'        => 'required|in:in,out,adjustment',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'status'      => 'required|in:active,inactive',
        ]);

        Stock::create([
            'product_id'  => $request->product_id,
            'date'        => now()->toDateString(),   // auto today
            'type'        => $request->type,
            'quantity'    => $request->quantity,
            'price'       => $request->price,
            'total_price' => $request->total_price,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock Added Successfully');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $products = Product::all();

        return view('admin.stock.create', compact('stock', 'products'));
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'type'        => 'required|in:in,out,adjustment',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'status'      => 'required|in:active,inactive',
        ]);

        $stock->update([
            'product_id'  => $request->product_id,
            'date'        => $stock->date ?? now()->toDateString(), // keep original date
            'type'        => $request->type,
            'quantity'    => $request->quantity,
            'price'       => $request->price,
            'total_price' => $request->total_price,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock Updated Successfully');
    }

    public function destroy($id)
    {
        Stock::findOrFail($id)->delete();

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock Deleted Successfully');
    }
}
