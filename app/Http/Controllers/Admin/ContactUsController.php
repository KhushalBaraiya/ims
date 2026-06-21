<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
        $contacts = ContactUs::latest()->paginate(10);
        return view('admin.contactus.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.contactus.create');
    }

    public function show($id)
    {
        $contact = ContactUs::findOrFail($id);
        return view('admin.contactus.show', compact('contact'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:active,inactive',

        ]);

        ContactUs::create(array_merge($request->except('status'), ['status' => $request->status]));

        return redirect()
            ->route('admin.contactus.index')
            ->with('success', 'ContactUs message submitted successfully.');
    }

    public function edit($id)
    {
        $contact = ContactUs::findOrFail($id);
        return view('admin.contactus.create', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:active,inactive',

        ]);

        $contact = ContactUs::findOrFail($id);
        $contact->update(array_merge($request->except('status'), ['status' => $request->status]));

        return redirect()
            ->route('admin.contactus.index')
            ->with('success', 'ContactUs updated successfully.');
    }

    public function destroy($id)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->delete();

        return redirect()
            ->route('admin.contactus.index')
            ->with('success', 'ContactUs deleted successfully.');
    }
}
