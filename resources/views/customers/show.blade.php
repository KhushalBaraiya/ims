@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white tracking-tight">Customer Details</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Detailed account profile for customer: {{ $customer->name }}.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <x-button href="{{ route('customers.index') }}" variant="outline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to List
            </x-button>
            @can('customers.update')
                <x-button href="{{ route('customers.edit', $customer->id) }}" variant="primary">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Customer
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
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 font-bold text-xl border border-violet-100 dark:border-violet-850 shadow-md">
                        <i class="fa-solid fa-user-tag text-2xl"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-200 mt-3">{{ $customer->name }}</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $customer->phone }}</p>
                    <div class="mt-3">
                        <x-badge :variant="$customer->status === 'active' ? 'success' : 'danger'" :text="ucfirst($customer->status)" />
                    </div>
                </div>

                <div class="mt-4 space-y-3 divide-y divide-slate-150 dark:divide-slate-800">
                    <div class="flex justify-between items-center text-xs pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Opening Balance</span>
                        <span class="font-bold text-slate-700 dark:text-slate-350">${{ number_format($customer->opening_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs pt-3">
                        <span class="font-semibold text-slate-400 dark:text-slate-500">Created Date</span>
                        <span class="font-bold text-slate-700 dark:text-slate-350">{{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Contact & Operations Details -->
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Business Contact & Billing Parameters">
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Phone Number</span>
                        <span class="col-span-2 text-xs font-bold text-slate-800 dark:text-slate-200">{{ $customer->phone }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Alternative Phone</span>
                        <span class="col-span-2 text-xs text-slate-700 dark:text-slate-350">{{ $customer->alt_phone ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Email Address</span>
                        <span class="col-span-2 text-xs text-slate-700 dark:text-slate-350">{{ $customer->email ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">GST Number</span>
                        <span class="col-span-2 text-xs font-mono font-bold text-slate-700 dark:text-slate-300">{{ $customer->gst_number ?: '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Billing Address</span>
                        <span class="col-span-2 text-xs text-slate-700 dark:text-slate-300">
                            @if($customer->address || $customer->city || $customer->state)
                                {{ $customer->address }}<br>
                                {{ $customer->city }}{{ $customer->state ? ', ' . $customer->state : '' }} {{ $customer->pincode }}<br>
                                {{ $customer->country }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="grid grid-cols-3 py-3">
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Internal Notes</span>
                        <span class="col-span-2 text-xs text-slate-600 dark:text-slate-400 italic">{{ $customer->notes ?: 'No notes available.' }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
