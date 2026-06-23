@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')

    <div class="brand-wrap">
        <div class="brand-icon"><i class="bx bx-bolt-circle"></i></div>
        <span class="brand-name">Kalathiya POS</span>
    </div>

    <div class="auth-title">Forgot Password? 🔒</div>
    <div class="auth-subtitle">{{ __('messages.forgot_password_desc') }}</div>

    @if (session('status'))
        <div class="alert-success-dark">
            <i class="bx bx-check-circle fs-5"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-field">
            <label class="form-label-dark" for="email">{{ __('messages.email_address_label') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com"
                autocomplete="email" autofocus required class="input-dark @error('email') is-invalid @enderror" />
            @error('email')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit mb-3">
            <i class="bx bx-send"></i> {{ __('messages.send_reset_link') }}
        </button>

        <div class="text-center" style="margin-top:.75rem;">
            <a href="{{ route('login') }}" class="back-link">
                <i class="bx bx-chevron-left"></i> {{ __('messages.back_to_sign_in') }}
            </a>
        </div>
    </form>

@endsection
