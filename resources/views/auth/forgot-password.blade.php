@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')

    <div class="brand-wrap">
        <div class="brand-icon"><i class="bx bx-bolt-circle"></i></div>
        <span class="brand-name">Kalathiya POS</span>
    </div>

    <div class="auth-title">Forgot Password? 🔒</div>
    <div class="auth-subtitle">{{ __('messages.forgot_password_desc') }}</div>

    {{-- Email sent success (real SMTP) --}}
    @if (session('status'))
        <div class="alert-success-dark mb-4">
            <i class="bx bx-check-circle fs-5"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- No-email fallback: show the reset link directly --}}
    @if (session('reset_url'))
        <div class="reset-link-box mb-4">
            <div class="reset-link-header">
                <span class="reset-link-icon"><i class="bx bx-link-alt"></i></span>
                <div>
                    <div class="reset-link-title">Password Reset Link</div>
                    <div class="reset-link-sub">Click the button below to reset your password for
                        <strong>{{ session('reset_email') }}</strong>
                    </div>
                </div>
            </div>

            <a href="{{ session('reset_url') }}" class="btn-submit mt-3" style="text-decoration:none;display:flex;">
                <i class="bx bx-lock-open-alt"></i> Reset My Password Now
            </a>

            <details class="mt-3">
                <summary class="reset-link-copy-label">Copy reset link manually</summary>
                <div class="reset-link-copy-wrap mt-2">
                    <input type="text" id="resetLinkInput" class="input-dark" readonly value="{{ session('reset_url') }}"
                        style="font-size:.72rem;padding:.5rem .75rem;border-radius:8px 0 0 8px;border-right:none;">
                    <button type="button" class="copy-btn" onclick="copyResetLink()" title="Copy link">
                        <i class="bx bx-copy" id="copyIcon"></i>
                    </button>
                </div>
            </details>
        </div>
    @else
        {{-- Request form --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-field">
                <label class="form-label-dark" for="email">{{ __('messages.email_address_label') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="name@company.com" autocomplete="email" autofocus required
                    class="input-dark @error('email') is-invalid @enderror" />
                @error('email')
                    <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit mb-3" id="submitBtn">
                <i class="bx bx-send" id="submitIcon"></i>
                <span id="submitText">{{ __('messages.send_reset_link') }}</span>
            </button>
        </form>
    @endif

    <div class="text-center" style="margin-top:.75rem;">
        <a href="{{ route('login') }}" class="back-link">
            <i class="bx bx-chevron-left"></i> {{ __('messages.back_to_sign_in') }}
        </a>
    </div>

    <style>
        /* ── Reset link box ─────────────────────────────────────────────── */
        .reset-link-box {
            background: rgba(105, 108, 255, .07);
            border: 1.5px solid rgba(105, 108, 255, .28);
            border-radius: 12px;
            padding: 1.25rem 1.25rem 1rem;
        }

        .reset-link-header {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
        }

        .reset-link-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-size: 1.1rem;
        }

        .reset-link-title {
            font-size: .85rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: .25rem;
        }

        .reset-link-sub {
            font-size: .78rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .reset-link-copy-label {
            font-size: .75rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
            transition: color .15s;
        }

        .reset-link-copy-label:hover {
            color: var(--primary);
        }

        .reset-link-copy-wrap {
            display: flex;
            align-items: stretch;
        }

        .copy-btn {
            flex-shrink: 0;
            background: var(--primary);
            border: 1px solid var(--primary);
            border-left: none;
            border-radius: 0 8px 8px 0;
            color: #fff;
            padding: 0 .85rem;
            cursor: pointer;
            font-size: 1rem;
            transition: opacity .15s;
        }

        .copy-btn:hover {
            opacity: .85;
        }
    </style>

    <script>
        /* ── Loading state on submit ─────────────────────────────────── */
        var form = document.querySelector('form[action="{{ route('password.email') }}"]');
        if (form) {
            form.addEventListener('submit', function() {
                var btn = document.getElementById('submitBtn');
                var icon = document.getElementById('submitIcon');
                var text = document.getElementById('submitText');
                if (btn) {
                    btn.disabled = true;
                    btn.style.opacity = '.75';
                    icon.className = 'bx bx-loader-alt bx-spin';
                    text.textContent = 'Sending…';
                }
            });
        }

        function copyResetLink() {
            var input = document.getElementById('resetLinkInput');
            var icon = document.getElementById('copyIcon');
            input.select();
            input.setSelectionRange(0, 99999);
            try {
                navigator.clipboard.writeText(input.value).catch(function() {
                    document.execCommand('copy');
                });
            } catch (e) {
                document.execCommand('copy');
            }
            icon.className = 'bx bx-check';
            setTimeout(function() {
                icon.className = 'bx bx-copy';
            }, 2000);
        }
    </script>

@endsection
