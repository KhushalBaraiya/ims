<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->latest()->get();
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::where('status', 'active')->get();
        return view('admin.blog.create', compact('categories'));
    }

    public function show($id)
    {
        $blog = Blog::with('category')->findOrFail($id);
        return view('admin.blog.show', compact('blog'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_category_id'  => 'required',
            'title'             => 'required|string|min:3|max:255',
            'short_description' => 'required|string|min:10|max:500',
            'content'           => 'required|string|min:20',
            'author'            => 'required|string|max:100',
            'image'             => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'            => 'required|in:active,inactive',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/blog'), $image);
        }

        Blog::create([
            'blog_category_id'  => $request->blog_category_id,
            'title'             => $request->title,
            'slug'              => Str::slug($request->title),
            'short_description' => $request->short_description,
            'content'           => $request->content,
            'author'            => $request->author,
            'image'             => $image,
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog Added Successfully');
    }

    public function edit($id)
    {
        $blog       = Blog::findOrFail($id);
        $categories = BlogCategory::where('status', 'active')->get();
        return view('admin.blog.create', compact('blog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'blog_category_id'  => 'required',
            'title'             => 'required|string|min:3|max:255',
            'short_description' => 'required|string|min:10|max:500',
            'content'           => 'required|string|min:20',
            'author'            => 'required|string|max:100',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'            => 'required|in:active,inactive',
        ]);

        $image = $blog->image;
        if ($request->hasFile('image')) {
            if ($image && file_exists(public_path('uploads/blog/' . $image))) {
                unlink(public_path('uploads/blog/' . $image));
            }
            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/blog'), $image);
        }

        $blog->update([
            'blog_category_id'  => $request->blog_category_id,
            'title'             => $request->title,
            'slug'              => Str::slug($request->title),
            'short_description' => $request->short_description,
            'content'           => $request->content,
            'author'            => $request->author,
            'image'             => $image,
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog Updated Successfully');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && file_exists(public_path('uploads/blog/' . $blog->image))) {
            unlink(public_path('uploads/blog/' . $blog->image));
        }
        $blog->delete();
        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog Deleted Successfully');
    }
}
