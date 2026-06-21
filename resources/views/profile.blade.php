@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Account Profile</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage your user profile settings and password security details.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Profile Overview Card -->
        <div class="lg:col-span-1">
            <x-card title="Profile Overview" subtitle="Your account parameters">
                <div class="flex flex-col items-center text-center pb-4 border-b border-slate-100 dark:border-slate-800">
                    <!-- Profile Photo -->
                    <div class="relative group">
                        @if ($user->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover border-2 border-slate-200 dark:border-slate-800 shadow-sm">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-violet-500 text-white font-bold text-2xl shadow-md border-2 border-slate-200 dark:border-slate-800">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-3">{{ $user->name }}</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $user->email }}</p>
                    
                    <div class="mt-3">
                        <x-badge variant="primary" :text="$user->roles->pluck('name')->implode(', ') ?: 'Staff'" />
                    </div>
                </div>

                <div class="mt-5 space-y-3.5 divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="flex justify-between items-center text-[12px] pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Phone Number</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $user->phone ?: 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Member Since</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Last Login</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Edit Profile and Change Password Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- General Profile Settings -->
            <x-card title="Profile Information" subtitle="Update your account name, email address, phone, and profile photo.">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input label="Name" name="name" :value="old('name', $user->name)" required placeholder="John Doe" />
                        <x-input label="Email Address" name="email" type="email" :value="old('email', $user->email)" required placeholder="john@company.com" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input label="Phone Number" name="phone" :value="old('phone', $user->phone)" placeholder="e.g. +1234567890" />
                        
                        <div>
                            <label for="profile_photo" class="block text-[12px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Profile Picture
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" 
                                class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-800 dark:file:text-slate-300 focus:outline-none cursor-pointer border border-slate-300 dark:border-slate-800 rounded-lg p-0.5 bg-white dark:bg-slate-950">
                            <p class="text-[10px] text-slate-400 mt-1">Allowed files: JPG, PNG, GIF. Max size: 2MB.</p>
                            @error('profile_photo')
                                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <x-button type="submit" variant="primary">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Profile Details
                        </x-button>
                    </div>
                </form>
            </x-card>

            <!-- Change Password Settings -->
            <x-card title="Change Password" subtitle="Change your current login security credentials.">
                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-input label="Current Password" name="current_password" type="password" required placeholder="••••••••" />
                        <x-input label="New Password" name="password" type="password" required placeholder="••••••••" />
                        <x-input label="Confirm New Password" name="password_confirmation" type="password" required placeholder="••••••••" />
                    </div>

                    <div class="flex justify-end pt-3">
                        <x-button type="submit" variant="danger">
                            <i class="fa-solid fa-key mr-1.5"></i> Change Password
                        </x-button>
                    </div>
                </form>
            </x-card>
            
        </div>
    </div>
@endsection
