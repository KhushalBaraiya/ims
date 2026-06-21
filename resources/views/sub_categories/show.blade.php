@extends('layouts.app')

@section('title', 'Sub Category Details')

@section('content')
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Sub Category Details</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">View configuration and products associated with this subcategory.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <x-button href="{{ route('sub-categories.index') }}" variant="secondary">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to List
            </x-button>
            @can('sub_categories.update')
                <x-button href="{{ route('sub-categories.edit', $subCategory->id) }}" variant="primary">
                    <i class="fa-regular fa-pen-to-square mr-1"></i> Edit Details
                </x-button>
            @endcan
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Card -->
        <div class="md:col-span-2">
            <x-card title="General Information">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Sub Category Name</span>
                        <span class="text-base font-bold text-slate-800 dark:text-slate-200 block mt-1">{{ $subCategory->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Sub Category Code</span>
                        <span class="font-mono text-sm font-bold text-slate-650 dark:text-slate-400 block mt-1">{{ $subCategory->slug }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Parent Main Category</span>
                        <span class="text-sm text-slate-700 dark:text-slate-300 block mt-1">
                            <span class="font-semibold">{{ $subCategory->mainCategory->name ?? '-' }}</span>
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status</span>
                        <div class="mt-1">
                            <x-badge :variant="$subCategory->status === 'active' ? 'success' : 'danger'" :text="ucfirst($subCategory->status)" />
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</span>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 block whitespace-pre-line">{{ $subCategory->description ?: 'No description provided.' }}</p>
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
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $subCategory->id }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Created Date</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $subCategory->created_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase">Last Modified</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $subCategory->updated_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
