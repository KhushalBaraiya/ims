@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="mb-6 text-center text-sm text-zinc-400">
        Provide your registered email address below, and we will dispatch a password reset link to your inbox.
    </div>

    <!-- Status Alert -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-base"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-zinc-300 mb-1.5">Email Address</label>
            <div class="relative rounded-lg shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-500">
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                    class="block w-full rounded-lg border border-zinc-800 bg-zinc-950/40 py-2.5 pl-10 pr-4 text-sm text-white placeholder-zinc-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 @error('email') border-red-500/80 focus:border-red-500 focus:ring-red-500/30 @enderror"
                    placeholder="name@company.com">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" 
            class="w-full flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-violet-600 py-2.5 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 focus:outline-none transition-all active:scale-[0.99]">
            <i class="fa-solid fa-paper-plane"></i> Send Reset Link
        </button>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-xs text-zinc-400 hover:text-zinc-200 transition-colors inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Sign In
            </a>
        </div>
    </form>
@endsection
