<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    public function index()
    {
        $terms = TermCondition::latest()->get();
        return view('admin.term_condition.index', compact('terms'));
    }

    public function create()
    {
        return view('admin.term_condition.create');
    }

    public function show($id)
    {
        $term = TermCondition::findOrFail($id);
        return view('admin.term_condition.show', compact('term'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        TermCondition::create([
            'title'   => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.term_condition.index')
            ->with('success', 'Term & Condition added successfully.');
    }

    public function edit($id)
    {
        $term = TermCondition::findOrFail($id);
        return view('admin.term_condition.create', compact('term'));
    }

    public function update(Request $request, $id)
    {
        $term = TermCondition::findOrFail($id);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $term->update([
            'title'   => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.term_condition.index')
            ->with('success', 'Term & Condition updated successfully.');
    }

    public function destroy($id)
    {
        $term = TermCondition::findOrFail($id);
        $term->delete();

        return redirect()
            ->route('admin.term_condition.index')
            ->with('success', 'Term & Condition deleted successfully.');
    }
}