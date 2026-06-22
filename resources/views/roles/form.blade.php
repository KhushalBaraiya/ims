@csrf

@php
    $displayName = old('display_name', $role->name ?? '');
    $name = old('name', $role->name ?? '');
@endphp

{{-- ── Basic Info ────────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 gap-5 md:grid-cols-2">

    {{-- Display Name --}}
    <div>
        <label class="block text-[12px] font-semibold text-slate-700 dark:text-slate-300 mb-1" for="displayNameInput">
            Display Name <span class="text-red-400">*</span>
        </label>
        <div class="relative">
            <input
                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 transition @error('display_name') border-red-400 dark:border-red-500 @enderror"
                id="displayNameInput" name="display_name" placeholder="e.g. HR Manager" required type="text"
                value="{{ $displayName }}" />
        </div>
        @error('display_name')
            <p class="mt-1 text-[11px] text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-circle-info"></i> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- System Name (slug) --}}
    <div>
        <label class="block text-[12px] font-semibold text-slate-700 dark:text-slate-300 mb-1" for="nameInput">
            System Name <span class="text-red-400">*</span>
            <span class="ml-1 font-normal text-slate-400 dark:text-slate-500">(auto-generated)</span>
        </label>
        <div class="relative">
            <input
                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-3 py-2 text-sm focus:outline-none transition cursor-not-allowed @error('name') border-red-400 dark:border-red-500 @enderror"
                id="nameInput" name="name" readonly type="text" value="{{ $name }}" />
        </div>
        @error('name')
            <p class="mt-1 text-[11px] text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-circle-info"></i> {{ $message }}
            </p>
        @enderror
    </div>

</div>

{{-- ── Permissions Matrix ───────────────────────────────────────────────────── --}}
<div class="mt-8">

    {{-- Section header with Select All --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <div class="text-sm font-bold text-slate-800 dark:text-slate-200">Permissions</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage role permissions</div>
        </div>

        <label class="inline-flex cursor-pointer items-center gap-2">
            <input class="peer sr-only" id="selectAllPermissions" type="checkbox">
            <div
                class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">
                <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200 peer-checked:opacity-100"
                    fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Select All</span>
        </label>
    </div>

    {{-- Permissions Table --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60">
                    <th
                        class="py-3 px-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Module</th>
                    <th
                        class="py-3 px-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-16">
                        All</th>
                    <th
                        class="py-3 px-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-20">
                        View</th>
                    <th
                        class="py-3 px-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-20">
                        Own</th>
                    <th
                        class="py-3 px-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-20">
                        Create</th>
                    <th
                        class="py-3 px-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-20">
                        Update</th>
                    <th
                        class="py-3 px-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-20">
                        Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($crudPermissions as $module => $actions)
                    @php $moduleSlug = \Illuminate\Support\Str::slug($module); @endphp
                    <tr
                        class="border-t border-slate-100 dark:border-slate-700/60 hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">

                        {{-- Module label --}}
                        <td class="px-4 py-3 font-semibold text-sm text-slate-700 dark:text-slate-300">
                            {{ $module }}
                        </td>

                        {{-- Module "All" toggle --}}
                        <td class="px-4 py-3 text-center">
                            <label class="inline-flex cursor-pointer items-center">
                                <input class="module-checkbox peer sr-only" data-module="{{ $moduleSlug }}"
                                    type="checkbox">
                                <div
                                    class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">
                                    <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200"
                                        fill="none" stroke-width="3" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </label>
                        </td>

                        {{-- Individual action columns --}}
                        @foreach (['view', 'own', 'create', 'update', 'delete'] as $action)
                            <td class="px-4 py-3 text-center">
                                @if (isset($actions[$action]))
                                    <label class="inline-flex cursor-pointer items-center">
                                        <input @checked(in_array($actions[$action]->name, $selectedPermissions ?? [])) class="permission-checkbox peer sr-only"
                                            data-action="{{ $action }}" data-module="{{ $moduleSlug }}"
                                            name="permissions[]" type="checkbox" value="{{ $actions[$action]->name }}">
                                        <div
                                            class="flex h-5 w-5 items-center justify-center rounded-md border border-gray-300 bg-white transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 dark:border-gray-600 dark:bg-gray-800">
                                            <svg class="h-3.5 w-3.5 text-white opacity-0 transition-all duration-200"
                                                fill="none" stroke-width="3" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </label>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600 text-base leading-none">—</span>
                                @endif
                            </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── Form Actions ─────────────────────────────────────────────────────────── --}}
<div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
    <a href="{{ route('roles.index') }}"
        class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        Cancel
    </a>
    <button type="submit"
        class="rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-5 py-2 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98] cursor-pointer">
        {{ isset($role) ? 'Update Role' : 'Save Role' }}
    </button>
</div>
