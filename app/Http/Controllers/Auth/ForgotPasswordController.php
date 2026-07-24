<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     * If MAIL_MAILER is "log" (no real mailer configured), generates the reset
     * URL directly and shows it on-screen so the app works without SMTP.
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        // Generate the password-reset token via the broker
        $token = Password::broker()->createToken($user);

        // Build the reset URL
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ], false));

        // If a real mailer is configured, try to send the email
        $mailer = config('mail.default', 'log');
        if ($mailer !== 'log' && $mailer !== 'array') {
            $status = Password::broker()->sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', __($status));
            }
        }

        // Fallback: display the reset URL directly on screen
        return back()->with([
            'reset_url'   => $resetUrl,
            'reset_email' => $request->email,
        ]);
    }
}
