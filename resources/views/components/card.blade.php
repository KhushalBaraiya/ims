@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-md overflow-hidden']) }}>
    @if ($title || $actions)
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4">
            <div>
                @if ($title)
                    <h3 class="text-[15px] font-bold text-slate-800 dark:text-slate-200">
                        {{ $title }}
                    </h3>
                @endif
                @if ($subtitle)
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            @if ($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>
</div>
