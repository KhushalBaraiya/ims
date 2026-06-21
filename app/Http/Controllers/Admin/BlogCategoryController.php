<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->latest()->get();
        return view('admin.blog-category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog-category.create');
    }

    public function show($id)
    {
        $category = BlogCategory::withCount('blogs')->with('blogs')->findOrFail($id);
        return view('admin.blog-category.show', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'status'      => 'required|in:active,inactive',
        ]);

        BlogCategory::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.blog-category.index')
            ->with('success', 'Blog Category Added Successfully');
    }

    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        return view('admin.blog-category.create', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'status'      => 'required|in:active,inactive',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.blog-category.index')
            ->with('success', 'Blog Category Updated Successfully');
    }

    public function destroy($id)
    {
        BlogCategory::findOrFail($id)->delete();
        return redirect()->route('admin.blog-category.index')
            ->with('success', 'Blog Category Deleted Successfully');
    }
}
