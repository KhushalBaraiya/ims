@props([
    'label' => null,
    'name',
    'required' => false,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
            {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <div class="relative">
        <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 pr-8 text-[14px] text-slate-800 dark:text-slate-100 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 appearance-none ' . ($errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30' : '')]) }}>
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-600">
            <i class="fa-solid fa-angle-down text-xs"></i>
        </div>
    </div>
    @error($name)
        <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
            <i class="fa-solid fa-circle-info"></i> {{ $message }}
        </p>
    @enderror
</div>
