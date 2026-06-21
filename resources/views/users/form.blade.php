@csrf

<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
    <!-- Profile Photo Preview & Upload -->
    <div class="sm:col-span-6 flex items-center gap-5">
        <div class="shrink-0">
            @if (isset($user) && $user->profile_photo)
                <img src="{{ asset('uploads/profiles/' . $user->profile_photo) }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover border-2 border-slate-200 dark:border-slate-800 shadow-sm">
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 border-2 border-slate-200 dark:border-slate-800">
                    <i class="fa-solid fa-user text-2xl"></i>
                </div>
            @endif
        </div>
        
        <div class="w-full max-w-xs">
            <label for="profile_photo" class="block text-[12px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                Profile Photo
            </label>
            <input type="file" name="profile_photo" id="profile_photo" 
                class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-800 dark:file:text-slate-300 focus:outline-none cursor-pointer border border-slate-300 dark:border-slate-800 rounded-lg p-0.5 bg-white dark:bg-slate-950">
            @error('profile_photo')
                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <!-- Name -->
    <div class="sm:col-span-3">
        <x-input label="Name" name="name" :value="old('name', $user->name ?? '')" required placeholder="John Doe" />
    </div>

    <!-- Email -->
    <div class="sm:col-span-3">
        <x-input label="Email Address" name="email" type="email" :value="old('email', $user->email ?? '')" required placeholder="john@company.com" />
    </div>

    <!-- Phone -->
    <div class="sm:col-span-2">
        <x-input label="Phone Number" name="phone" :value="old('phone', $user->phone ?? '')" placeholder="e.g. +123456789" />
    </div>

    <!-- Role (Spatie) -->
    <div class="sm:col-span-2">
        <x-select label="Role" name="role" required>
            <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Select Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" 
                    {{ (old('role', isset($user) ? $user->roles->first()?->name : '') === $role->name) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </x-select>
    </div>

    <!-- Status -->
    <div class="sm:col-span-2">
        <x-select label="Status" name="status" required>
            <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </x-select>
    </div>

    <!-- Password fields (only required on CREATE, optional on EDIT) -->
    <div class="sm:col-span-3">
        <x-input label="Password {{ isset($user) ? '(Leave blank to keep current)' : '' }}" name="password" type="password" :required="!isset($user)" placeholder="••••••••" />
    </div>

    <div class="sm:col-span-3">
        <x-input label="Confirm Password" name="password_confirmation" type="password" :required="!isset($user)" placeholder="••••••••" />
    </div>
</div>

<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <a href="{{ route('users.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        Cancel
    </a>
    <button type="submit" class="rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-2 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
        {{ isset($user) ? 'Update User' : 'Save User' }}
    </button>
</div>
