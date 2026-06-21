@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Supplier Details</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Detailed account profile for: {{ $supplier->name }}.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <x-button href="{{ route('suppliers.index') }}" variant="outline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to List
            </x-button>
            @can('suppliers.update')
                <x-button href="{{ route('suppliers.edit', $supplier->id) }}" variant="primary">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Supplier
                </x-button>
            @endcan
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Overview Card -->
        <div class="lg:col-span-1">
            <x-card title="Overview">
                <div class="flex flex-col items-center text-center pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 font-bold text-xl border border-blue-100 dark:border-blue-800 shadow-md">
                        <i class="fa-solid fa-building text-2xl"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-200 mt-3">{{ $supplier->name }}</h4>
                    <p class="text-xs text-slate-405 dark:text-slate-500 mt-0.5">{{ $supplier->company_name ?: 'No Company Associated' }}</p>
                    <div class="mt-3">
                        <x-badge :variant="$supplier->status === 'active' ? 'success' : 'danger'" :text="ucfirst($supplier->status)" />
                    </div>
                </div>

                <div class="mt-4 space-y-3 divide-y divide-slate-150 dark:divide-slate-800">
                    <div class="flex justify-between items-center text-xs pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Contact Person</span>
                        <span class="font-bold text-slate-700 dark:text-slate-350">{{ $supplier->contact_person ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Opening Balance</span>
                        <span class="font-bold text-slate-700 dark:text-slate-350">${{ number_format($supplier->opening_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Registered Date</span>
                        <span class="font-bold text-slate-700 dark:text-slate-350">{{ $supplier->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Contact & Operations Details -->
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Business Contact & Tax Parameters">
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Phone Number</span>
                        <span class="col-span-2 text-xs font-bold text-slate-800 dark:text-slate-200">{{ $supplier->phone }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Alternative Phone</span>
                        <span class="col-span-2 text-xs text-slate-700 dark:text-slate-350">{{ $supplier->alt_phone ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Email Address</span>
                        <span class="col-span-2 text-xs text-slate-700 dark:text-slate-350">{{ $supplier->email ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">GST Number</span>
                        <span class="col-span-2 text-xs font-mono font-bold text-slate-700 dark:text-slate-300">{{ $supplier->gst_number ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">PAN Number</span>
                        <span class="col-span-2 text-xs font-mono font-bold text-slate-700 dark:text-slate-300">{{ $supplier->pan_number ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Full Address</span>
                        <span class="col-span-2 text-xs text-slate-700 dark:text-slate-300">
                            @if($supplier->address || $supplier->city || $supplier->state)
                                {{ $supplier->address }}<br>
                                {{ $supplier->city }}{{ $supplier->state ? ', ' . $supplier->state : '' }} {{ $supplier->pincode }}<br>
                                {{ $supplier->country }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Internal Notes</span>
                        <span class="col-span-2 text-xs text-slate-600 dark:text-slate-400 italic">{{ $supplier->notes ?: 'No notes available.' }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
