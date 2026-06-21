@extends('layouts.app')

@section('title', 'Brand Details')

@section('content')
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 tracking-tight">Brand Details</h2>
            <p class="mt-1 text-sm text-slate-500">Information card for manufacturer brand: {{ $brand->name }}.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('brands.index') }}" class="inline-flex items-center gap-x-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
            @can('brands.update')
                <a href="{{ route('brands.edit', $brand->id) }}" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Brand
                </a>
            @endcan
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden max-w-3xl">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800">General Information</h3>
            <div>
                @if ($brand->status === 'active')
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Active</span>
                @else
                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-600/20">Inactive</span>
                @endif
            </div>
        </div>
        
        <div class="px-6 py-6 divide-y divide-slate-100">
            <div class="grid grid-cols-3 py-3.5">
                <span class="text-sm font-semibold text-slate-400">Brand Name</span>
                <span class="col-span-2 text-sm font-bold text-slate-800">{{ $brand->name }}</span>
            </div>
            
            <div class="grid grid-cols-3 py-3.5">
                <span class="text-sm font-semibold text-slate-400">Brand Code</span>
                <span class="col-span-2 text-sm font-mono font-bold text-slate-600">{{ $brand->slug }}</span>
            </div>

            <div class="grid grid-cols-3 py-3.5">
                <span class="text-sm font-semibold text-slate-400">Description</span>
                <span class="col-span-2 text-sm text-slate-600 whitespace-pre-line">{{ $brand->description ?: 'No description provided.' }}</span>
            </div>

            <div class="grid grid-cols-3 py-3.5">
                <span class="text-sm font-semibold text-slate-400">Created At</span>
                <span class="col-span-2 text-sm text-slate-500">{{ $brand->created_at->format('F d, Y \a\t h:i A') }}</span>
            </div>

            <div class="grid grid-cols-3 py-3.5">
                <span class="text-sm font-semibold text-slate-400">Last Updated</span>
                <span class="col-span-2 text-sm text-slate-500">{{ $brand->updated_at->format('F d, Y \a\t h:i A') }}</span>
            </div>
        </div>
    </div>
@endsection
