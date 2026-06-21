<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function show($id)
    {
        $brand = Brand::withCount('products')->findOrFail($id);
        $products = \App\Models\Product::where('brand_id', $id)->latest()->take(10)->get();
        return view('admin.brand.show', compact('brand', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'image'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brand'), $image);
        }

        Brand::create([
            'name'   => $request->name,
            'image'  => $image,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.brand.index')
            ->with('success', 'Brand added successfully.');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.create', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $image = $brand->image;

        if ($request->hasFile('image')) {

            if ($image && file_exists(public_path('uploads/brand/' . $image))) {
                unlink(public_path('uploads/brand/' . $image));
            }

            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brand'), $image);
        }

        $brand->update([
            'name'   => $request->name,
            'image'  => $image,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.brand.index')
            ->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && file_exists(public_path('uploads/brand/' . $brand->image))) {
            unlink(public_path('uploads/brand/' . $brand->image));
        }

        $brand->delete();

        return redirect()
            ->route('admin.brand.index')
            ->with('success', 'Brand deleted successfully.');
    }
}
