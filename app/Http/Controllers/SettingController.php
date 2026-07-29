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

        $settings = Setting::all()->pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    /**
     * Send a test email to verify SMTP settings.
     */
    public function sendTestEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('settings.update');

        $request->validate([
            'to' => ['required', 'email'],
        ]);

        try {
            \Illuminate\Support\Facades\Mail::raw(
                'This is a test email from your IMS application. Your SMTP settings are working correctly.',
                function ($message) use ($request) {
                    $fromAddress = $request->input('mail_from_address')
                        ?: Setting::where('key', 'mail_from_address')->value('value')
                        ?: config('mail.from.address');
                    $fromName = $request->input('mail_from_name')
                        ?: Setting::where('key', 'mail_from_name')->value('value')
                        ?: config('mail.from.name');

                    $message->to($request->input('to'))
                        ->subject('IMS — Test Email')
                        ->from($fromAddress, $fromName);
                }
            );

            ActivityLog::log('Test Email Sent', 'Sent test email to: ' . $request->input('to'));

            return response()->json(['success' => true, 'message' => 'Test email sent to ' . $request->input('to') . ' successfully!']);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('settings.update');

        $request->validate([
            'company_name'                => ['required', 'string', 'max:255'],
            'company_email'               => ['required', 'email', 'max:255'],
            'company_phone'               => ['nullable', 'string', 'max:50'],
            'company_address'             => ['nullable', 'string', 'max:500'],
            'tax_name'                    => ['nullable', 'string', 'max:50'],
            'tax_percentage'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'invoice_prefix'              => ['nullable', 'string', 'max:20'],
            'purchase_prefix'             => ['nullable', 'string', 'max:20'],
            'show_out_of_stock_products'  => ['nullable', 'boolean'],
            'session_timeout'             => ['nullable', 'integer', 'min:0', 'max:1440'],
            'max_login_attempts'          => ['nullable', 'integer', 'min:0', 'max:20'],
            'lockout_duration'            => ['nullable', 'integer', 'min:1', 'max:1440'],
            'company_logo'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            // SMTP settings
            'mail_mailer'                 => ['nullable', 'string', 'max:20'],
            'mail_host'                   => ['nullable', 'string', 'max:255'],
            'mail_port'                   => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username'               => ['nullable', 'string', 'max:255'],
            'mail_password'               => ['nullable', 'string', 'max:255'],
            'mail_from_address'           => ['nullable', 'email', 'max:255'],
            'mail_from_name'              => ['nullable', 'string', 'max:100'],
            'mail_encryption'             => ['nullable', 'string', 'max:10'],
        ]);

        $keys = [
            'company_name', 'company_email', 'company_phone', 'company_address',
            'tax_name', 'tax_percentage', 'invoice_prefix', 'purchase_prefix',
            'session_timeout', 'max_login_attempts', 'lockout_duration',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
        }

        // Checkbox
        Setting::updateOrCreate(
            ['key' => 'show_out_of_stock_products'],
            ['value' => $request->has('show_out_of_stock_products') ? '1' : '0']
        );

        // Logo upload
        if ($request->hasFile('company_logo')) {
            $file     = $request->file('company_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);

            // Delete old logo if exists
            $oldLogo = Setting::where('key', 'company_logo')->value('value');
            if ($oldLogo && file_exists(public_path('uploads/settings/' . $oldLogo))) {
                @unlink(public_path('uploads/settings/' . $oldLogo));
            }

            Setting::updateOrCreate(['key' => 'company_logo'], ['value' => $filename]);
        }

        // SMTP settings — save to DB so email views can read them
        $smtpKeys = [
            'mail_mailer', 'mail_host', 'mail_port',
            'mail_username', 'mail_encryption',
            'mail_from_address', 'mail_from_name',
        ];
        foreach ($smtpKeys as $key) {
            if ($request->filled($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }
        // Password only updated if provided (don't wipe existing)
        if ($request->filled('mail_password')) {
            Setting::updateOrCreate(['key' => 'mail_password'], ['value' => $request->input('mail_password')]);
        }

        // Update .env mail settings live so Mail facade picks them up immediately
        $this->updateEnvMailSettings($request);

        // Clear caches
        \Illuminate\Support\Facades\Cache::forget('setting_session_timeout');
        \Illuminate\Support\Facades\Cache::forget('setting_max_login_attempts');
        \Illuminate\Support\Facades\Cache::forget('setting_lockout_duration');

        ActivityLog::log('Settings Updated', 'Company settings were updated.');

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    /**
     * Update .env MAIL_* values so the running app picks them up without restart.
     */
    private function updateEnvMailSettings(Request $request): void
    {
        $map = [
            'MAIL_MAILER'       => $request->input('mail_mailer'),
            'MAIL_HOST'         => $request->input('mail_host'),
            'MAIL_PORT'         => $request->input('mail_port'),
            'MAIL_USERNAME'     => $request->input('mail_username'),
            'MAIL_FROM_ADDRESS' => $request->input('mail_from_address'),
            'MAIL_FROM_NAME'    => $request->input('mail_from_name'),
            'MAIL_SCHEME'       => $request->input('mail_encryption') === 'ssl' ? 'ssl' : 'null',
        ];

        if ($request->filled('mail_password')) {
            $map['MAIL_PASSWORD'] = $request->input('mail_password');
        }

        $envPath = base_path('.env');
        if (!file_exists($envPath)) return;

        $envContent = file_get_contents($envPath);

        foreach ($map as $key => $value) {
            if ($value === null || $value === '') continue;
            // Wrap value in quotes if it contains spaces
            $quoted = str_contains((string)$value, ' ') ? '"' . $value . '"' : $value;
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$quoted}", $envContent);
            } else {
                $envContent .= "\n{$key}={$quoted}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
