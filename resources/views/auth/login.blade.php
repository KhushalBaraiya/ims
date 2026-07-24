@extends('layouts.auth')
@section('title', __('messages.login'))

@section('content')

    {{-- Brand --}}
    <div class="brand-wrap">
        <div class="brand-icon"><i class="bx bx-bolt-circle"></i></div>
        <span class="brand-name">Kalathiya POS</span>
    </div>

    <div class="auth-title">{{ __('messages.welcome_back') }} 👋</div>
    <div class="auth-subtitle">{{ __('messages.sign_in') }}</div>

    {{-- Status / logout alert --}}
    @if (session('status'))
        <div class="alert-success-dark">
            <i class="bx bx-check-circle fs-5"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Session expired / error alert --}}
    @if (session('error'))
        <div class="mb-3 d-flex align-items-center gap-2 px-3 py-2 rounded-3"
            style="background:rgba(255,62,29,.12);border:1px solid rgba(255,62,29,.3);color:#ff3e1d;font-size:.875rem;">
            <i class="bx bx-time-five flex-shrink-0" style="font-size:1.1rem;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div class="mb-field">
            <label class="form-label-dark" for="email">{{ __('messages.email_address_label') }}</label>
            <input autocomplete="email" autofocus class="input-dark @error('email') is-invalid @enderror" id="email"
                name="email" placeholder="name@company.com" required type="email" value="{{ old('email') }}" />
            @error('email')
                @php
                    $isLocked = str_starts_with($message, 'LOCKED:');
                    $lockTs = $isLocked ? (int) substr($message, 7) : 0;
                    $isWarning = !$isLocked && str_contains($message, 'remaining');
                @endphp
                @if ($isLocked)
                    {{-- Account Locked — live countdown banner --}}
                    <div class="login-alert-banner login-alert-danger mt-3">
                        <div class="login-alert-icon">
                            <i class="bx bx-lock"></i>
                        </div>
                        <div class="login-alert-body">
                            <div class="login-alert-title">Account Locked</div>
                            <div class="login-alert-msg">
                                Too many failed login attempts. Please wait before trying again.
                            </div>
                            <div class="lock-countdown-wrap mt-2">
                                <span class="lock-countdown-label">Try again in</span>
                                <span class="lock-countdown-timer" id="lockCountdown">--:--</span>
                            </div>
                        </div>
                    </div>
                    <script>
                        (function() {
                            var unlockAt = {{ $lockTs }} * 1000;
                            var el = document.getElementById('lockCountdown');
                            var submitBtn = document.querySelector('.btn-submit');

                            // Disable Sign In button while locked
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.style.opacity = '0.45';
                                submitBtn.style.cursor = 'not-allowed';
                            }

                            function tick() {
                                var diff = Math.max(0, Math.ceil((unlockAt - Date.now()) / 1000));

                                if (diff <= 0) {
                                    if (el) el.textContent = '00:00';
                                    // Re-enable button & auto-refresh after short delay
                                    if (submitBtn) {
                                        submitBtn.disabled = false;
                                        submitBtn.style.opacity = '1';
                                        submitBtn.style.cursor = '';
                                    }
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 800);
                                    return;
                                }

                                var m = Math.floor(diff / 60);
                                var s = diff % 60;
                                if (el) el.textContent =
                                    String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

                                setTimeout(tick, 1000);
                            }
                            tick();
                        })();
                    </script>
                @elseif ($isWarning)
                    {{-- Attempts remaining — Orange warning --}}
                    <div class="login-alert-banner login-alert-warning mt-3">
                        <div class="login-alert-icon">
                            <i class="bx bx-error"></i>
                        </div>
                        <div class="login-alert-body">
                            <div class="login-alert-title">Invalid Credentials</div>
                            <div class="login-alert-msg">{{ $message }}</div>
                        </div>
                    </div>
                @else
                    {{-- Normal error --}}
                    <div class="login-alert-banner login-alert-danger mt-3">
                        <div class="login-alert-icon">
                            <i class="bx bx-x-circle"></i>
                        </div>
                        <div class="login-alert-body">
                            <div class="login-alert-title">Login Failed</div>
                            <div class="login-alert-msg">{{ $message }}</div>
                        </div>
                    </div>
                @endif
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-field">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label-dark mb-0" for="password">{{ __('messages.password_label') }}</label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
                @endif
            </div>
            <div class="pw-wrap">
                <input autocomplete="current-password" class="input-dark @error('password') is-invalid @enderror"
                    id="password" name="password" placeholder="••••••••" required type="password" />
                <button class="pw-toggle" id="togglePw" tabindex="-1" type="button">
                    <i class="bx bx-hide" id="pwIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="form-meta-row">
            <div class="form-check">
                <input class="form-check-input" id="remember_me" name="remember" type="checkbox" />
                <label class="form-check-label" for="remember_me">
                    {{ __('messages.remember_session') }}
                </label>
            </div>
        </div>

        <button class="btn-submit" type="submit">
            <i class="bx bx-log-in-circle"></i> {{ __('messages.sign_in') }}
        </button>
    </form>

    {{-- Demo Credentials --}}
    <div class="demo-credentials-wrap">
        <div class="demo-title">Demo Credentials</div>

        <div class="demo-user-card">
            <div class="demo-user-info">
                <div class="demo-role">Super Admin</div>
                <div class="demo-email">admin@gmail.com</div>
                <div class="demo-password">Admin@123</div>
            </div>
            <button class="demo-copy-btn" onclick="fillCredentials('admin@gmail.com', 'Admin@123')" type="button">
                <i class="bx bx-copy"></i> {{ __('messages.copy') }}
            </button>
        </div>

        <div class="demo-user-card">
            <div class="demo-user-info">
                <div class="demo-role">Manager</div>
                <div class="demo-email">manager@gmail.com</div>
                <div class="demo-password">Manager@123</div>
            </div>
            <button class="demo-copy-btn" onclick="fillCredentials('manager@gmail.com', 'Manager@123')" type="button">
                <i class="bx bx-copy"></i> {{ __('messages.copy') }}
            </button>
        </div>

        <div class="demo-user-card">
            <div class="demo-user-info">
                <div class="demo-role">Staff</div>
                <div class="demo-email">staff@gmail.com</div>
                <div class="demo-password">Staff@123</div>
            </div>
            <button class="demo-copy-btn" onclick="fillCredentials('staff@gmail.com', 'Staff@123')" type="button">
                <i class="bx bx-copy"></i> {{ __('messages.copy') }}
            </button>
        </div>
    </div>

    <style>
        /* ── Login Alert Banners ─────────────────────────────────────────── */
        .login-alert-banner {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 4px;
            animation: alertSlide .25s ease;
        }

        @keyframes alertSlide {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-alert-danger {
            background: rgba(239, 68, 68, .13);
            border: 1.5px solid rgba(239, 68, 68, .35);
        }

        .login-alert-warning {
            background: rgba(245, 158, 11, .13);
            border: 1.5px solid rgba(245, 158, 11, .35);
        }

        .login-alert-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .login-alert-danger .login-alert-icon {
            background: rgba(239, 68, 68, .2);
            color: #f87171;
        }

        .login-alert-warning .login-alert-icon {
            background: rgba(245, 158, 11, .2);
            color: #fbbf24;
        }

        .login-alert-body {
            flex: 1;
            min-width: 0;
        }

        .login-alert-title {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 3px;
        }

        .login-alert-danger .login-alert-title {
            color: #f87171;
        }

        .login-alert-warning .login-alert-title {
            color: #fbbf24;
        }

        .login-alert-msg {
            font-size: .85rem;
            line-height: 1.4;
        }

        .login-alert-danger .login-alert-msg {
            color: rgba(255, 255, 255, .8);
        }

        .login-alert-warning .login-alert-msg {
            color: rgba(255, 255, 255, .8);
        }

        /* ── Live Countdown Timer ────────────────────────────────────────── */
        .lock-countdown-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        .lock-countdown-label {
            font-size: .78rem;
            color: rgba(255, 255, 255, .5);
            font-weight: 500;
            white-space: nowrap;
        }

        .lock-countdown-timer {
            font-size: 1.6rem;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            letter-spacing: .06em;
            color: #f87171;
            background: rgba(239, 68, 68, .15);
            border: 1.5px solid rgba(239, 68, 68, .35);
            border-radius: 10px;
            padding: 3px 14px;
            min-width: 80px;
            text-align: center;
            display: inline-block;
            animation: timerPulse 1s ease infinite alternate;
        }

        @keyframes timerPulse {
            from {
                opacity: 1;
            }

            to {
                opacity: .7;
            }
        }

        /* ── Demo Credentials ────────────────────────────────────────────── */
        .demo-credentials-wrap {
            margin-top: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .demo-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-user-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            margin-bottom: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }

        .demo-user-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .demo-user-card:last-child {
            margin-bottom: 0;
        }

        .demo-user-info {
            flex: 1;
        }

        .demo-role {
            font-size: 0.813rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .demo-email {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            font-family: 'Courier New', monospace;
            margin-bottom: 0.125rem;
        }

        .demo-password {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            font-family: 'Courier New', monospace;
        }

        .demo-copy-btn {
            padding: 0.5rem 0.875rem;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 6px;
            color: #818cf8;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .demo-copy-btn:hover {
            background: rgba(99, 102, 241, 0.25);
            border-color: rgba(99, 102, 241, 0.5);
            transform: translateY(-1px);
        }

        .demo-copy-btn:active {
            transform: translateY(0);
        }

        .demo-copy-btn i {
            font-size: 0.875rem;
        }

        /* ── Remember me — square checkbox ──────────────────────────────── */

        /* Override the toggle style from auth layout — use plain square checkbox */
        .check-wrap input[type="checkbox"].box-input {
            appearance: none;
            -webkit-appearance: none;
            width: 17px;
            height: 17px;
            background: var(--bg-input);
            border: 1.5px solid rgba(255, 255, 255, .25);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: background .2s, border-color .2s;
            /* reset toggle styles */
            border-radius: 4px !important;
        }

        .check-wrap input[type="checkbox"].box-input::after {
            content: '';
            position: absolute;
            top: 1px;
            left: 4px;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            border-radius: 0;
            transform: rotate(45deg) scale(0);
            transition: transform .15s ease;
            background: transparent;
        }

        .check-wrap input[type="checkbox"].box-input:checked {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .check-wrap input[type="checkbox"].box-input:checked::after {
            transform: rotate(45deg) scale(1);
            background: transparent;
        }
    </style>

    <script>
        document.getElementById('togglePw').addEventListener('click', function() {
            var inp = document.getElementById('password');
            var icon = document.getElementById('pwIcon');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'text' ? 'bx bx-show' : 'bx bx-hide';
        });

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;

            // Optional: Show visual feedback
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            emailInput.style.transition = 'all 0.3s ease';
            passwordInput.style.transition = 'all 0.3s ease';

            emailInput.style.background = 'rgba(34, 197, 94, 0.1)';
            passwordInput.style.background = 'rgba(34, 197, 94, 0.1)';

            setTimeout(() => {
                emailInput.style.background = '';
                passwordInput.style.background = '';
            }, 500);
        }
    </script>

@endsection
