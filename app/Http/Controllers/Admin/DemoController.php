<?php

namespace App\Http\Controllers\Admin;

use App\Models\Demo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demos = Demo::latest()->paginate(10);

        return view('admin.demo.index', compact('demos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.demo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:demos,email',
            'password'  => 'required|min:6',
            'phone'     => 'required',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png',
            'gender'    => 'required',
            'address'   => 'nullable',
            'status'    => 'required',
        ]);
dd($request->all());
        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(
                public_path('uploads/demo'),
                $imageName
            );
        }   

        Demo::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'image'     => $imageName,
            'gender'    => $request->gender,
            'address'   => $request->address,
            'status'    => $request->status,
        ]);

        return redirect()
            ->route('demo.index')
            ->with('success', 'Demo created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Demo $demo)
    {
        return view('admin.demo.show', compact('demo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demo $demo)
    {
        return view('admin.demo.create', compact('demo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demo $demo)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:demos,email,' . $demo->id,
            'password'  => 'nullable|min:6',
            'phone'     => 'required',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png',
            'gender'    => 'required',
            'address'   => 'nullable',
            'status'    => 'required',
        ]);

        $imageName = $demo->image;

        if ($request->hasFile('image')) {

            // Old image delete
            if (
                $demo->image &&
                file_exists(public_path('uploads/demo/' . $demo->image))
            ) {

                unlink(public_path('uploads/demo/' . $demo->image));
            }

            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(
                public_path('uploads/demo'),
                $imageName
            );
        }

        $demo->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'image'     => $imageName,
            'gender'    => $request->gender,
            'address'   => $request->address,
            'status'    => $request->status,
        ]);
        

        // Password update if filled
        if ($request->password) {

            $demo->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()
            ->route('admin.demo.index')
            ->with('success', 'Demo updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demo $demo)
    {
        // Image delete
        if (
            $demo->image &&
            file_exists(public_path('uploads/demo/' . $demo->image))
        ) {

            unlink(public_path('uploads/demo/' . $demo->image));
        }

        $demo->delete();

        return redirect()
            ->route('admin.demo.index')
            ->with('success', 'Demo deleted successfully');
    }
}






