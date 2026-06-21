@extends('layouts.app')

@section('title', 'Unit Details')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Unit Details</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">View configuration for this unit of measurement.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <x-button href="{{ route('units.index') }}" variant="secondary">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to List
            </x-button>
            @can('units.update')
                <x-button href="{{ route('units.edit', $unit->id) }}" variant="primary">
                    <i class="fa-regular fa-pen-to-square mr-1"></i> Edit Details
                </x-button>
            @endcan
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Card -->
        <div class="md:col-span-2">
            <x-card title="Unit Information">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Unit Name</span>
                        <span class="text-base font-bold text-slate-800 dark:text-slate-200 block mt-1">{{ $unit->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Short Name</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mt-1">{{ $unit->short_name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status</span>
                        <div class="mt-1">
                            <x-badge :variant="$unit->status === 'active' ? 'success' : 'danger'" :text="ucfirst($unit->status)" />
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</span>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 block whitespace-pre-line">{{ $unit->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Meta Card -->
        <div>
            <x-card title="System Metadata">
                <div class="space-y-4">
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Record ID</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $unit->id }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Created Date</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $unit->created_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Last Modified</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $unit->updated_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
