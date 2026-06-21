<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductService;
use Illuminate\Http\Request;

class ProductServiceController extends Controller
{
    public function index()
    {
        $services = ProductService::latest()->get();
        return view('admin.product-service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.product-service.create');
    }

    public function show($id)
    {
        $service = ProductService::findOrFail($id);
        return view('admin.product-service.show', compact('service'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $file  = $request->file('image');
            $image = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/product-services'), $image);
        }

        ProductService::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $image,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.product-service.index')
            ->with('success', 'Product Service Added Successfully');
    }

    public function edit($id)
    {
        $service = ProductService::findOrFail($id);
        return view('admin.product-service.create', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = ProductService::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $image = $service->image;
        if ($request->hasFile('image')) {
            // delete old
            if ($image && file_exists(public_path('uploads/product-services/' . $image))) {
                unlink(public_path('uploads/product-services/' . $image));
            }
            $file  = $request->file('image');
            $image = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/product-services'), $image);
        }

        $service->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $image,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.product-service.index')
            ->with('success', 'Product Service Updated Successfully');
    }

    public function destroy($id)
    {
        $service = ProductService::findOrFail($id);
        if ($service->image && file_exists(public_path('uploads/product-services/' . $service->image))) {
            unlink(public_path('uploads/product-services/' . $service->image));
        }
        $service->delete();
        return redirect()->route('admin.product-service.index')
            ->with('success', 'Product Service Deleted Successfully');
    }
}
