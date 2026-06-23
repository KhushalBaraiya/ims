@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')

    <div class="brand-wrap">
        <div class="brand-icon"><i class="bx bx-bolt-circle"></i></div>
        <span class="brand-name">Kalathiya POS</span>
    </div>

    <div class="auth-title">Set New Password 🔑</div>
    <div class="auth-subtitle">{{ __('messages.reset_password_desc') }}</div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email (readonly) --}}
        <div class="mb-field">
            <label class="form-label-dark" for="email">{{ __('messages.email_address_label') }}</label>
            <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" readonly
                class="input-dark @error('email') is-invalid @enderror" />
            @error('email')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- New password --}}
        <div class="mb-field">
            <label class="form-label-dark" for="password">{{ __('messages.new_password_label') }}</label>
            <div class="pw-wrap">
                <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="new-password"
                    required class="input-dark @error('password') is-invalid @enderror" />
                <button type="button" class="pw-toggle" id="togglePw" tabindex="-1">
                    <i class="bx bx-hide" id="pwIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm password --}}
        <div class="mb-field">
            <label class="form-label-dark" for="password_confirmation">Confirm New Password</label>
            <div class="pw-wrap">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••"
                    autocomplete="new-password" required class="input-dark" />
                <button type="button" class="pw-toggle" id="toggleConfirm" tabindex="-1">
                    <i class="bx bx-hide" id="confirmIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit" style="margin-bottom:.75rem;">
            <i class="bx bx-check-shield"></i> {{ __('messages.reset_password_btn') }}
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="back-link">
                <i class="bx bx-chevron-left"></i> {{ __('messages.back_to_sign_in') }}
            </a>
        </div>
    </form>

    <script>
        function toggleField(inputId, iconId) {
            var inp = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'text' ? 'bx bx-show' : 'bx bx-hide';
        }
        document.getElementById('togglePw').addEventListener('click', function() {
            toggleField('password', 'pwIcon');
        });
        document.getElementById('toggleConfirm').addEventListener('click', function() {
            toggleField('password_confirmation', 'confirmIcon');
        });
    </script>

@endsection
