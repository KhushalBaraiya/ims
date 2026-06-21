<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Reviews::with(['product', 'user'])->latest()->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();

        return view('admin.reviews.create', compact('products', 'users'));
    }

    public function show($id)
    {
        $review = Reviews::with('product','user')->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time().'_'.rand(1000, 9999).'.'.$file->extension();
                $file->move(public_path('uploads/reviews'), $name);
                $images[] = $name;
            }
        }

        Reviews::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'title' => $request->title,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'images' => $images,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Reviews Created Successfully');
    }

    public function edit($id)
    {
        $review = Reviews::findOrFail($id);
        $products = Product::all();
        $users = User::all();

        return view('admin.reviews.create', compact('review', 'products', 'users'));
    }

    public function update(Request $request, $id)
    {
        $review = Reviews::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required',
        ]);

        $images = $review->images ?? [];
        if ($request->hasFile('images')) {
            // Delete old images before replacing
            foreach ($images as $old) {
                $path = public_path('uploads/reviews/'.$old);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $images = [];
            foreach ($request->file('images') as $file) {
                $name = time().'_'.rand(1000, 9999).'.'.$file->extension();
                $file->move(public_path('uploads/reviews'), $name);
                $images[] = $name;
            }
        }

        $review->update([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'title' => $request->title,
            'comment' => $request->comment,
            'rating' => $request->rating,
            'images' => $images,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Reviews Updated Successfully');
    }

    public function destroy($id)
    {
        $review = Reviews::findOrFail($id);

        if (! empty($review->images)) {
            foreach ($review->images as $img) {
                $path = public_path('uploads/reviews/'.$img);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Reviews Deleted Successfully');
    }
}
