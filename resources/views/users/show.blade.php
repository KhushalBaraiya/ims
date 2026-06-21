@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">User Details</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Account profile and operations summary.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-x-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-3.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
            @can('users.update')
                <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                    <i class="fa-solid fa-pen-to-square"></i> Edit User
                </a>
            @endcan
        </div>
    </div>

    <!-- Details Card -->
    <div class="max-w-3xl">
        <x-card title="General Information" subtitle="User account parameters">
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <!-- Profile Avatar -->
                <div>
                    @if ($user->profile_photo)
                        <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover border-2 border-slate-200 dark:border-slate-800 shadow-sm">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-violet-500 text-white font-bold text-2xl shadow-md border-2 border-slate-200 dark:border-slate-800">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="text-center sm:text-left">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $user->email }}</p>
                    <div class="mt-2.5 flex items-center justify-center sm:justify-start gap-2">
                        <x-badge variant="primary" :text="$user->roles->pluck('name')->implode(', ') ?: 'Staff'" />
                        <x-badge :variant="$user->status === 'active' ? 'success' : 'danger'" :text="ucfirst($user->status)" />
                    </div>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800 text-[12px]">
                <div class="grid grid-cols-3 py-3">
                    <span class="font-semibold text-slate-400 dark:text-slate-500">Phone Number</span>
                    <span class="col-span-2 font-bold text-slate-700 dark:text-slate-300">{{ $user->phone ?: 'Not provided' }}</span>
                </div>

                <div class="grid grid-cols-3 py-3">
                    <span class="font-semibold text-slate-400 dark:text-slate-500">Last Login Timestamp</span>
                    <span class="col-span-2 font-bold text-slate-700 dark:text-slate-300">
                        {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') . ' (' . $user->last_login_at->diffForHumans() . ')' : 'Never logged in' }}
                    </span>
                </div>

                <div class="grid grid-cols-3 py-3">
                    <span class="font-semibold text-slate-400 dark:text-slate-500">Account Created Date</span>
                    <span class="col-span-2 font-bold text-slate-700 dark:text-slate-300">{{ $user->created_at->format('Y-m-d H:i:s') }}</span>
                </div>

                <div class="grid grid-cols-3 py-3">
                    <span class="font-semibold text-slate-400 dark:text-slate-500">Last Profile Update</span>
                    <span class="col-span-2 font-bold text-slate-700 dark:text-slate-300">{{ $user->updated_at->format('Y-m-d H:i:s') }}</span>
                </div>
            </div>
        </x-card>
    </div>
@endsection
