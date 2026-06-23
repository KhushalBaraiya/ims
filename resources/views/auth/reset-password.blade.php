@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="mb-6 text-center text-sm text-zinc-400">
        {{ __('messages.reset_password_desc') }}
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email Address -->
        <div>
            <label for="email"
                class="block text-sm font-semibold text-zinc-300 mb-1.5">{{ __('messages.email_address_label') }}</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required
                    autocomplete="email" readonly
                    class="block w-full rounded-lg border border-zinc-800 bg-zinc-950/20 py-2.5 pl-10 pr-4 text-sm text-zinc-400 placeholder-zinc-600 outline-none cursor-not-allowed">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password"
                class="block text-sm font-semibold text-zinc-300 mb-1.5">{{ __('messages.new_password_label') }}</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-lg border border-zinc-800 bg-zinc-950/40 py-2.5 pl-10 pr-4 text-sm text-white placeholder-zinc-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 @error('password') border-red-500/80 focus:border-red-500 focus:ring-red-500/30 @enderror"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-zinc-300 mb-1.5">Confirm New
                Password</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="block w-full rounded-lg border border-zinc-800 bg-zinc-950/40 py-2.5 pl-10 pr-4 text-sm text-white placeholder-zinc-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50"
                    placeholder="••••••••">
            </div>
        </div>

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-violet-600 py-2.5 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 focus:outline-none transition-all active:scale-[0.99]">
            <i class="fa-solid fa-key"></i> {{ __('messages.reset_password_btn') }}
        </button>
    </form>
@endsection
