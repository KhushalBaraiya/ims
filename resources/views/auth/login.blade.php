@extends('layouts.auth')
@section('title', __('messages.login'))

@section('content')

    {{-- Brand --}}
    <div class="brand-wrap">
        <div class="brand-icon"><i class="bx bx-bolt-circle"></i></div>
        <span class="brand-name">Kalathiya POS</span>
    </div>

    <div class="auth-title">Welcome back! 👋</div>
    <div class="auth-subtitle">Please sign in to your account</div>

    {{-- Status / logout alert --}}
    @if (session('status'))
        <div class="alert-success-dark">
            <i class="bx bx-check-circle fs-5"></i>
            <span>{{ session('status') }}</span>
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
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
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
            <label class="check-wrap" for="remember_me">
                <input id="remember_me" name="remember" type="checkbox" />
                <span>{{ __('messages.remember_session') }}</span>
            </label>
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
                <i class="bx bx-copy"></i> Copy
            </button>
        </div>

        <div class="demo-user-card">
            <div class="demo-user-info">
                <div class="demo-role">Manager</div>
                <div class="demo-email">manager@gmail.com</div>
                <div class="demo-password">Manager@123</div>
            </div>
            <button class="demo-copy-btn" onclick="fillCredentials('manager@gmail.com', 'Manager@123')" type="button">
                <i class="bx bx-copy"></i> Copy
            </button>
        </div>

        <div class="demo-user-card">
            <div class="demo-user-info">
                <div class="demo-role">Staff</div>
                <div class="demo-email">staff@gmail.com</div>
                <div class="demo-password">Staff@123</div>
            </div>
            <button class="demo-copy-btn" onclick="fillCredentials('staff@gmail.com', 'Staff@123')" type="button">
                <i class="bx bx-copy"></i> Copy
            </button>
        </div>
    </div>

    <style>
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
