<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryImage;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class CategoryImagesController extends Controller
{
    public function index()
    {
        $categoryImages = CategoryImage::with('category')
                            ->latest()
                            ->get();

        return view('admin.categoryimage.index', compact('categoryImages'));
    }

    public function create()
    {
        $categories = MainCategory::all();
        return view('admin.categoryimage.create', compact('categories'));
    }

    public function show($id)
    {
        $categoryImage = CategoryImage::with('category')->findOrFail($id);
        return view('admin.categoryimage.show', compact('categoryImage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:main_categories,id',
            'title'       => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categoryimage'), $imageName);
        }

        CategoryImage::create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'image'       => $imageName,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.categoryimage.index')
            ->with('success', 'Category Image added successfully.');
    }

    public function edit($id)
    {
        $categoryImage = CategoryImage::findOrFail($id);
        $categories = MainCategory::all();

        return view('admin.categoryimage.create',
            compact('categoryImage', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $categoryImage = CategoryImage::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:main_categories,id',
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $imageName = $categoryImage->image;

        if ($request->hasFile('image')) {

            // delete old image
            if ($imageName && file_exists(public_path('uploads/categoryimage/' . $imageName))) {
                unlink(public_path('uploads/categoryimage/' . $imageName));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categoryimage'), $imageName);
        }

        $categoryImage->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'image'       => $imageName,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.categoryimage.index')
            ->with('success', 'Category Image updated successfully.');
    }

    public function destroy($id)
    {
        $categoryImage = CategoryImage::findOrFail($id);

        if ($categoryImage->image &&
            file_exists(public_path('uploads/categoryimage/' . $categoryImage->image))) {

            unlink(public_path('uploads/categoryimage/' . $categoryImage->image));
        }

        $categoryImage->delete();

        return redirect()
            ->route('admin.categoryimage.index')
            ->with('success', 'Category Image deleted successfully.');
    }
}
