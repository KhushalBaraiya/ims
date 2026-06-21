@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Dashboard Title and Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl tracking-tight">
                POS Dashboard
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Welcome back, {{ auth()->user()->name ?? 'User' }}! Here is a summary of today's activities.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <button type="button" class="ml-3 inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:from-blue-500 hover:to-violet-500 transition-all active:scale-[0.98]">
                <i class="fa-solid fa-cart-shopping"></i>
                New POS Sale
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        
        <!-- Total Products Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Products</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($totalProducts) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>
        </div>

        <!-- Total Categories Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-tags text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Categories</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($totalCategories) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-purple-500 to-purple-400"></div>
        </div>

        <!-- Total Suppliers Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-parachute-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Suppliers</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($totalSuppliers) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-cyan-500 to-cyan-400"></div>
        </div>

        <!-- Total Customers Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Customers</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($totalCustomers) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
        </div>

        <!-- Today's Purchase -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-truck-ramp-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Today's Purchases</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">${{ number_format($todayPurchase, 2) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-orange-500 to-orange-400"></div>
        </div>

        <!-- Today's Sales -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
            <div class="flex items-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-sack-dollar text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Today's Sales</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">${{ number_format($todaySales, 2) }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-rose-400"></div>
        </div>

        <!-- Low Stock Alert Panel Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5 sm:col-span-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600 group-hover:scale-105 transition-transform duration-200">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Low Stock Warnings</p>
                        <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ $lowStockProducts->count() }}</p>
                    </div>
                </div>
                <div>
                    @if ($lowStockProducts->count() > 0)
                        <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-600 ring-1 ring-inset ring-red-600/10">Action Needed</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-600 ring-1 ring-inset ring-emerald-600/10">Stock Healthy</span>
                    @endif
                </div>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-red-500 to-red-400"></div>
        </div>
        
    </div>

    <!-- Details Grid split columns -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Low Stock Warning Table Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
                    <i class="fa-solid fa-warehouse text-red-500"></i>
                    Low Stock Alert List
                </h3>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-slate-100 align-middle">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Product Name</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Min Alert Qty</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($lowStockProducts as $product)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-400">{{ $product->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ number_format($product->stock_alert_qty, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <span class="text-red-500 font-extrabold">{{ number_format($product->stock->quantity ?? 0, 0) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-regular fa-circle-check text-4xl text-emerald-400 mb-3"></i>
                                        <p class="font-semibold text-slate-500">All stock is currently OK!</p>
                                        <p class="text-xs text-slate-400 mt-0.5">No products are under the stock alert quantity.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activities Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
                    <i class="fa-solid fa-clock-rotate-left text-blue-500"></i>
                    Recent Activities
                </h3>
            </div>
            
            <div class="p-6 flex-1 overflow-y-auto">
                <ul class="space-y-6 relative before:absolute before:left-3.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                    @forelse ($recentActivities as $activity)
                        <li class="relative pl-8 flex gap-x-4">
                            <div class="absolute left-0 flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 border border-slate-200 text-slate-500">
                                <i class="fa-solid fa-user-clock text-[10px]"></i>
                            </div>
                            <div class="flex-auto py-0.5">
                                <div class="flex justify-between items-center gap-x-4">
                                    <span class="text-xs font-bold text-slate-800">{{ $activity->user->name ?? 'System' }}</span>
                                    <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-semibold text-slate-600 mt-1">{{ $activity->activity }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">{{ $activity->description }}</p>
                            </div>
                        </li>
                    @empty
                        <div class="text-center py-12 text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                <p class="font-semibold text-slate-500">No activity recorded</p>
                                <p class="text-xs text-slate-400 mt-0.5">System operations will be logged here.</p>
                            </div>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
        
    </div>
@endsection
