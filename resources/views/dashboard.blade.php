@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Dashboard Title and Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-2xl tracking-tight">
                POS Dashboard
            </h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Welcome back, {{ auth()->user()->name ?? 'User' }}! Here is a summary of today's activities.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <button type="button" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                <i class="fa-solid fa-cart-shopping"></i>
                New POS Sale
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        
        <!-- Total Products Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-box text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Total Products</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ number_format($totalProducts) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>
        </div>

        <!-- Total Categories Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-tags text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Total Categories</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ number_format($totalCategories) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-purple-500 to-purple-400"></div>
        </div>

        <!-- Total Suppliers Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-parachute-box text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Total Suppliers</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ number_format($totalSuppliers) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-400"></div>
        </div>

        <!-- Total Customers Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Total Customers</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
        </div>

        <!-- Today's Purchase -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-truck-ramp-box text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Today's Purchases</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">${{ number_format($todayPurchase, 2) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-orange-500 to-orange-400"></div>
        </div>

        <!-- Today's Sales -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-sack-dollar text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Today's Sales</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">${{ number_format($todaySales, 2) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-rose-400"></div>
        </div>

        <!-- Low Stock Alert Panel Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-905 p-4 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5 sm:col-span-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 group-hover:scale-105 transition-transform duration-200">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-[10px] font-semibold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Low Stock Warnings</p>
                        <p class="text-xl font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $lowStockProducts->count() }}</p>
                    </div>
                </div>
                <div>
                    @if ($lowStockProducts->count() > 0)
                        <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-500/10 px-2 py-0.5 text-[10px] font-bold text-red-600 dark:text-red-400 ring-1 ring-inset ring-red-600/10 dark:ring-red-500/20">Action Needed</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/10 dark:ring-emerald-500/20">Stock Healthy</span>
                    @endif
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-red-500 to-red-400"></div>
        </div>
        
    </div>

    <!-- Details Grid split columns -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- Low Stock Warning Table Card -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-850 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-warehouse text-red-500"></i>
                    Low Stock Alert List
                </h3>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 align-middle">
                    <thead class="bg-slate-50 dark:bg-slate-950">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Code</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Product Name</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Min Alert Qty</th>
                            <th class="px-5 py-3 text-right text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        @forelse ($lowStockProducts as $product)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3 whitespace-nowrap font-semibold text-slate-400 dark:text-slate-500">{{ $product->code }}</td>
                                <td class="px-5 py-3 whitespace-nowrap font-bold text-slate-800 dark:text-slate-200">{{ $product->name }}</td>
                                {{-- <td class="px-5 py-3 whitespace-nowrap text-slate-500 dark:text-slate-400">{{ number_format($product->stock_alert_qty, 0) }}</td> --}}
                                <td class="px-5 py-3 whitespace-nowrap text-right font-extrabold text-red-500 dark:text-red-400">
                                    {{ number_format($product->stock->quantity ?? 0, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-regular fa-circle-check text-4xl text-emerald-450 mb-3"></i>
                                        <p class="font-semibold text-slate-500 dark:text-slate-400">All stock is currently OK!</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">No products are under the stock alert quantity.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-850 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-blue-500"></i>
                    Recent Activities
                </h3>
            </div>
            
            <div class="p-5 flex-1 overflow-y-auto">
                <ul class="space-y-4 relative before:absolute before:left-3.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100 dark:before:bg-slate-800">
                    @forelse ($recentActivities as $activity)
                        <li class="relative pl-8 flex gap-x-3">
                            <div class="absolute left-0 flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                                <i class="fa-solid fa-user-clock text-[10px]"></i>
                            </div>
                            <div class="flex-auto py-0.5">
                                <div class="flex justify-between items-center gap-x-2">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $activity->user->name ?? 'System' }}</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-350 mt-1 leading-normal">{{ $activity->activity }}</p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 leading-relaxed">{{ $activity->description }}</p>
                            </div>
                        </li>
                    @empty
                        <div class="text-center py-12 text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-regular fa-folder-open text-4xl text-slate-300 dark:text-slate-700 mb-3"></i>
                                <p class="font-semibold text-slate-500 dark:text-slate-400">No activity recorded</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">System operations will be logged here.</p>
                            </div>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
        
    </div>
@endsection
