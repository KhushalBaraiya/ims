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

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-field">
            <label class="form-label-dark" for="email">{{ __('messages.email_address_label') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com"
                autocomplete="email" autofocus required class="input-dark @error('email') is-invalid @enderror" />
            @error('email')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-field">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label-dark mb-0" for="password">{{ __('messages.password_label') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">{{ __('messages.forgot_password') }}</a>
                @endif
            </div>
            <div class="pw-wrap">
                <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password"
                    required class="input-dark @error('password') is-invalid @enderror" />
                <button type="button" class="pw-toggle" id="togglePw" tabindex="-1">
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
                <input type="checkbox" id="remember_me" name="remember" />
                <span>{{ __('messages.remember_session') }}</span>
            </label>
        </div>

        <button type="submit" class="btn-submit">
            <i class="bx bx-log-in-circle"></i> {{ __('messages.sign_in') }}
        </button>
    </form>

    <script>
        document.getElementById('togglePw').addEventListener('click', function() {
            var inp = document.getElementById('password');
            var icon = document.getElementById('pwIcon');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'text' ? 'bx bx-show' : 'bx bx-hide';
        });
    </script>

@endsection
