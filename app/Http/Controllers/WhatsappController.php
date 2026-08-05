<?php

namespace App\Http\Controllers;

use App\Models\WhatsappTemplate;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WhatsappController extends Controller
{
    /**
     * List all templates + active customers with phone numbers.
     */
    public function index(): View
    {
        $templates = WhatsappTemplate::latest()->get();
        $customers = Customer::where('status', 'active')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        return view('whatsapp.index', compact('templates', 'customers'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('whatsapp.create');
    }

    /**
     * Store new template.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'message' => 'required|string',
            'status'  => 'required|in:active,inactive',
        ]);

        $template = WhatsappTemplate::create($data);

        ActivityLog::log('WhatsApp Template Created', "Created template: {$template->name}");

        return redirect()->route('whatsapp.index')->with('success', 'Template created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(WhatsappTemplate $whatsapp): View
    {
        return view('whatsapp.edit', ['template' => $whatsapp]);
    }

    /**
     * Update template.
     */
    public function update(Request $request, WhatsappTemplate $whatsapp): RedirectResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'message' => 'required|string',
            'status'  => 'required|in:active,inactive',
        ]);

        $whatsapp->update($data);

        ActivityLog::log('WhatsApp Template Updated', "Updated template: {$whatsapp->name}");

        return redirect()->route('whatsapp.index')->with('success', 'Template updated successfully.');
    }

    /**
     * Delete template.
     */
    public function destroy(WhatsappTemplate $whatsapp): JsonResponse|RedirectResponse
    {
        $name = $whatsapp->name;
        $whatsapp->delete();

        ActivityLog::log('WhatsApp Template Deleted', "Deleted template: {$name}");

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Template deleted successfully.']);
        }

        return redirect()->route('whatsapp.index')->with('success', 'Template deleted successfully.');
    }

    /**
     * AJAX — return template message body.
     */
    public function getMessage(WhatsappTemplate $whatsapp): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $whatsapp->message,
            'name'    => $whatsapp->name,
        ]);
    }
}
