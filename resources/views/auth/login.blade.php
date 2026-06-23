@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <!-- Status message -->
    @if (session('status'))
        <div
            class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-base"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email"
                class="block text-sm font-semibold text-zinc-300 mb-1.5">{{ __('messages.email_address_label') }}</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email" autofocus
                    class="block w-full rounded-lg border border-zinc-800 bg-zinc-950/40 py-2.5 pl-10 pr-4 text-sm text-white placeholder-zinc-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 @error('email') border-red-500/80 focus:border-red-500 focus:ring-red-500/30 @enderror"
                    placeholder="name@company.com">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password"
                    class="block text-sm font-semibold text-zinc-300">{{ __('messages.password_label') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs text-blue-400 hover:text-blue-300 hover:underline">
                        {{ __('messages.forgot_password') }}
                    </a>
                @endif
            </div>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border border-zinc-800 bg-zinc-950/40 py-2.5 pl-10 pr-10 text-sm text-white placeholder-zinc-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 @error('password') border-red-500/80 focus:border-red-500 focus:ring-red-500/30 @enderror"
                    placeholder="••••••••">
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-500 hover:text-zinc-300 focus:outline-none">
                    <i class="fa-regular fa-eye" id="passwordIcon"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="h-4 w-4 rounded border-zinc-800 bg-zinc-950/50 text-blue-600 focus:ring-blue-500/30 focus:ring-offset-zinc-950 cursor-pointer">
            <label for="remember_me" class="ml-2 block text-sm text-zinc-400 cursor-pointer select-none">
                {{ __('messages.remember_session') }}
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-violet-600 py-2.5 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 focus:outline-none transition-all active:scale-[0.99]">
            <i class="fa-solid fa-right-to-bracket"></i> {{ __('messages.sign_in') }}
        </button>
    </form>

    <script>
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');

        if (togglePasswordBtn && passwordInput && passwordIcon) {
            togglePasswordBtn.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordIcon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            });
        }
    </script>
@endsection
