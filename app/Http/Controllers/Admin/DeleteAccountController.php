<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeleteAccount;
use Illuminate\Http\Request;

class DeleteAccountController extends Controller
{
    public function index()
    {
        $deleteAccounts = DeleteAccount::latest()->get();
        return view('admin.delete-account.index', compact('deleteAccounts'));
    }

    public function create()
    {
        return view('admin.delete-account.create');
    }

    public function show($id)
    {
        $deleteAccount = \App\Models\DeleteAccount::findOrFail($id);
        return view('admin.delete-account.show', compact('deleteAccount'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        DeleteAccount::create([
            'title'  => $request->title,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.delete-account.index')->with('success', 'Record created successfully.');
    }

    public function edit(DeleteAccount $deleteAccount)
    {
        return view('admin.delete-account.create', compact('deleteAccount'));
    }

    public function update(Request $request, DeleteAccount $deleteAccount)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $deleteAccount->update([
            'title'  => $request->title,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.delete-account.index')->with('success', 'Record updated successfully.');
    }

    public function destroy(DeleteAccount $deleteAccount)
    {
        $deleteAccount->delete();
        return redirect()->route('admin.delete-account.index')->with('success', 'Record deleted.');
    }
}
