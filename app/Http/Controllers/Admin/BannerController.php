<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();

        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.show', compact('banner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'btn_text' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'offer_discountLabel' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/banner'), $image);
        }

        Banner::create([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'btn_text' => $request->btn_text,
            'image' => $image,
            'offer_discountLabel' => $request->offer_discountLabel,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.banner.index')
            ->with('success', 'Banner added successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin.banner.create', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'btn_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'offer_discountLabel' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $image = $banner->image;

        if ($request->hasFile('image')) {

            if ($image && file_exists(public_path('uploads/banner/' . $image))) {
                unlink(public_path('uploads/banner/' . $image));
            }

            $image = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/banner'), $image);
        }

        $banner->update([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'btn_text' => $request->btn_text,
            'image' => $image,
            'offer_discountLabel' => $request->offer_discountLabel,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.banner.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && file_exists(public_path('uploads/banner/' . $banner->image))) {
            unlink(public_path('uploads/banner/' . $banner->image));
        }

        $banner->delete();

        return redirect()
            ->route('admin.banner.index')
            ->with('success', 'Banner deleted successfully.');
    }
}
