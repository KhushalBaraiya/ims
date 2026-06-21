@props([
    'label' => null,
    'name',
    'value' => '',
    'required' => false,
    'rows' => 3,
    'placeholder' => '',
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
            {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <div class="mt-1">
        <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 py-2 px-3.5 text-[14px] text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 ' . ($errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30' : '')]) }}>{{ $value }}</textarea>
    </div>
    @error($name)
        <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
            <i class="fa-solid fa-circle-info"></i> {{ $message }}
        </p>
    @enderror
</div>
