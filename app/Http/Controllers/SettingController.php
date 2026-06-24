<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index(): View
    {
        Gate::authorize('settings.view');

        // Load all settings as a key => value map for easy access in the view
        $settings = Setting::all()->pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('settings.update');

        $request->validate([
            'company_name'    => ['required', 'string', 'max:255'],
            'company_email'   => ['required', 'email', 'max:255'],
            'company_phone'   => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'tax_name'        => ['nullable', 'string', 'max:50'],
            'tax_percentage'  => ['nullable', 'numeric', 'min:0', 'max:100'],
            'invoice_prefix'  => ['nullable', 'string', 'max:20'],
            'purchase_prefix' => ['nullable', 'string', 'max:20'],
        ]);

        $keys = [
            'company_name',
            'company_email',
            'company_phone',
            'company_address',
            'tax_name',
            'tax_percentage',
            'invoice_prefix',
            'purchase_prefix',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        ActivityLog::log('Settings Updated', 'Company settings were updated.');

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
