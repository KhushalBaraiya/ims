<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SubFaq;
use Illuminate\Http\Request;

class SubFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subFaqs = SubFaq::with('faq')->latest()->paginate(10);

        return view('admin.sub_faq.index', compact('subFaqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faqs = Faq::where('status', 'active')->get();

        return view('admin.sub_faq.create', compact('faqs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show($id)
    {
        $subFaq = SubFaq::with('faq')->findOrFail($id);
        return view('admin.sub_faq.show', compact('subFaq'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'faq_id' => 'required|exists:faqs,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        SubFaq::create([
            'faq_id' => $request->faq_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sub-faq.index')
            ->with('success', 'Sub FAQ created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subFaq = SubFaq::findOrFail($id);
        $faqs = Faq::where('status', 'active')->get();

        return view('admin.sub_faq.create', compact('subFaq', 'faqs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'faq_id' => 'required|exists:faqs,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $subFaq = SubFaq::findOrFail($id);
        $subFaq->update([
            'faq_id' => $request->faq_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sub-faq.index')
            ->with('success', 'Sub FAQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subFaq = SubFaq::findOrFail($id);
        $subFaq->delete();

        return redirect()->route('admin.sub-faq.index')
            ->with('success', 'Sub FAQ deleted successfully.');
    }
}
