<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Antigravity POS</title>
    
    <!-- Inline no-flicker dark mode check -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Toastr Alert Style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- DataTables Styles -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        /* Custom global scrollbars (Webkit) */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Custom scrollbars (Firefox) */
        html {
            font-size: 15px; /* Sizing scaled up by ~15% */
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .dark html {
            scrollbar-color: #475569 transparent;
        }

        /* Typography & Spacing Overrides */
        body {
            font-size: 14px !important;
            line-height: 1.6 !important;
        }
        h2 {
            font-size: 20px !important; /* Page Title */
        }
        h3, .section-heading {
            font-size: 16px !important; /* Section Heading */
        }
        /* Sidebar Menu links */
        #sidebar nav a, #sidebar nav button {
            font-size: 14px !important;
            padding-top: 0.625rem !important;
            padding-bottom: 0.625rem !important;
        }
        /* Sidebar icon size */
        #sidebar nav i {
            font-size: 16px !important;
        }
        /* Navbar items */
        header {
            height: 4rem !important; /* h-16 */
        }
        header button, header span, header a {
            font-size: 14px !important;
        }
        /* Dropdown menu font size & padding */
        #profile-dropdown-menu a, #profile-dropdown-menu button {
            font-size: 14px !important;
            padding-top: 0.625rem !important;
            padding-bottom: 0.625rem !important;
        }
        /* Table styles */
        table.dataTable {
            font-size: 13px !important; /* Table Text */
        }
        table.dataTable thead th {
            font-size: 13px !important;
            font-weight: 700 !important;
            padding: 12px 18px !important; /* Increased cell padding */
            background-color: #f8fafc !important;
        }
        .dark table.dataTable thead th {
            background-color: #0f172a !important;
        }
        table.dataTable tbody td {
            font-size: 13px !important;
            padding: 12px 18px !important; /* Increased cell padding */
            line-height: 1.5 !important;
        }
        table.dataTable tbody tr {
            transition: background-color 0.2s ease-in-out;
        }
        /* DataTable length and search select / inputs styling */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            font-size: 13px !important;
            padding: 0.375rem 0.75rem !important;
            height: auto !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.75rem !important;
        }
        /* Pagination buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 12px !important;
            padding: 0.25rem 0.75rem !important;
        }
        /* Card Margin / Spacing Updates */
        .card, x-card {
            margin-bottom: 1.5rem !important;
        }

        /* Custom Styles for DataTables to match Tailwind and Dark Mode themes */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.25rem 1.5rem 0.25rem 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e1;
            background-color: #fff;
            outline: none;
            font-size: 12px;
        }
        .dark .dataTables_wrapper .dataTables_length select {
            border-color: #334155;
            background-color: #0f172a;
            color: #f8fafc;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e1;
            outline: none;
            margin-left: 0.5rem;
            font-size: 12px;
        }
        .dark .dataTables_wrapper .dataTables_filter input {
            border-color: #334155;
            background-color: #0f172a;
            color: #f8fafc;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.15rem 0.5rem !important;
            border-radius: 0.375rem !important;
            border: 1px solid transparent !important;
            font-size: 11px !important;
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%) !important;
            color: #fff !important;
            border-color: transparent !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
            border-color: #e2e8f0 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #1e293b !important;
            color: #fff !important;
            border-color: #334155 !important;
        }
        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            font-size: 12px;
        }
        table.dataTable thead th {
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 10px 18px !important;
        }
        .dark table.dataTable thead th {
            border-bottom: 1px solid #334155 !important;
            color: #f8fafc !important;
        }
        .dataTables_wrapper {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_processing,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #94a3b8 !important;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-850 dark:text-slate-200 transition-colors duration-150">

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar component -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-900 border-r border-slate-800 dark:border-slate-900 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex h-16 shrink-0 items-center px-6 border-b border-slate-800 dark:border-slate-900">
            <span class="text-lg font-bold bg-gradient-to-r from-blue-400 to-violet-400 bg-clip-text text-transparent">
                <i class="fa-solid fa-bolt mr-2"></i>Antigravity POS
            </span>
        </div>
        
        <nav class="flex flex-1 flex-col overflow-y-auto px-4 py-4">
            <ul role="list" class="flex flex-1 flex-col gap-y-5">
                <!-- Dashboard Section -->
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-lg p-2 text-xs font-semibold leading-5 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }} transition-all">
                                <i class="fa-solid fa-chart-pie flex h-5 w-5 shrink-0 items-center justify-center text-sm"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Inventory collapsible group -->
                <li class="sidebar-group" data-group="inventory">
                    <button type="button" class="sidebar-group-toggle w-full flex items-center justify-between gap-x-3 rounded-lg p-2 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all focus:outline-none">
                        <span class="flex items-center gap-x-3">
                            <i class="fa-solid fa-warehouse flex h-5 w-5 shrink-0 items-center justify-center text-sm"></i>
                            <span>Inventory</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200"></i>
                    </button>
                    <ul class="sidebar-group-items mt-1 pl-8 space-y-0.5 hidden">
          <li>
              <a href="{{ route('main-categories.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('main-categories.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                  Main Categories
              </a>
          </li>
          @can('sub_categories.view')
          <li>
              <a href="{{ route('sub-categories.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('sub-categories.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                  Sub Categories
              </a>
          </li>
          @endcan
          <li>
              <a href="{{ route('brands.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('brands.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                  Brands
              </a>
          </li>
          @can('units.view')
          <li>
              <a href="{{ route('units.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('units.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                  Units
              </a>
          </li>
          @endcan
          @can('products.view')
          <li>
              <a href="{{ route('products.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('products.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                  Products
              </a>
          </li>
          @endcan
          <li>
              <a href="#" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/30 transition-all">
                  Stocks
              </a>
          </li>
      </ul>
                </li>

                <!-- Transactions collapsible group -->
                <li class="sidebar-group" data-group="transactions">
                    <button type="button" class="sidebar-group-toggle w-full flex items-center justify-between gap-x-3 rounded-lg p-2 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all focus:outline-none">
                        <span class="flex items-center gap-x-3">
                            <i class="fa-solid fa-cart-shopping flex h-5 w-5 shrink-0 items-center justify-center text-sm"></i>
                            <span>Transactions</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200"></i>
                    </button>
                    <ul class="sidebar-group-items mt-1 pl-8 space-y-0.5 hidden">
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/30 transition-all">
                                Purchases
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/30 transition-all">
                                Sales
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Management collapsible group -->
                <li class="sidebar-group" data-group="management">
                    <button type="button" class="sidebar-group-toggle w-full flex items-center justify-between gap-x-3 rounded-lg p-2 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all focus:outline-none">
                        <span class="flex items-center gap-x-3">
                            <i class="fa-solid fa-gears flex h-5 w-5 shrink-0 items-center justify-center text-sm"></i>
                            <span>Management</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200"></i>
                    </button>
                    <ul class="sidebar-group-items mt-1 pl-8 space-y-0.5 hidden">
                        @can('suppliers.view')
                        <li>
                            <a href="{{ route('suppliers.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('suppliers.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                                Suppliers
                            </a>
                        </li>
                        @endcan
                        @can('customers.view')
                        <li>
                            <a href="{{ route('customers.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('customers.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                                Customers
                            </a>
                        </li>
                        @endcan
                        @can('users.view')
                        <li>
                            <a href="{{ route('users.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('users.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                                User Accounts
                            </a>
                        </li>
                        @endcan
                        @can('currencies.view')
                        <li>
                            <a href="{{ route('currencies.index') }}" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 {{ request()->routeIs('currencies.*') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800/30' }} transition-all">
                                Currencies
                            </a>
                        </li>
                        @endcan
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/30 transition-all">
                                Reports
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/30 transition-all">
                                Settings
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-1.5 text-xs font-semibold leading-5 text-slate-400 hover:text-white hover:bg-slate-800/30 transition-all">
                                Activity Logs
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="p-3 border-t border-slate-800 text-center text-[10px] text-slate-500">
            &copy; 2026 Antigravity POS
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- Header Navbar -->
        <header class="flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 sticky top-0 z-30 transition-colors duration-150">
            <button id="toggle-sidebar-mobile" type="button" class="-m-2.5 p-2 text-slate-700 dark:text-slate-350 lg:hidden hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg">
                <span class="sr-only">Open sidebar</span>
                <i class="fa-solid fa-bars text-lg"></i>
            </button>

            <div class="flex items-center gap-x-3 ml-auto">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button" class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-lg p-1.5 transition-colors">
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-sm hidden"></i>
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-sm hidden"></i>
                </button>

                <!-- User Dropdown Menu -->
                <div class="relative" id="profile-dropdown-container">
                    <button type="button" id="profile-dropdown-btn" class="-m-1.5 flex items-center p-1.5 gap-x-2.5 hover:bg-slate-50 dark:hover:bg-slate-850 rounded-full transition-all focus:outline-none">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . auth()->user()->profile_photo) }}" alt="Avatar" class="h-7 w-7 rounded-full object-cover shadow-sm ring-1 ring-slate-200 dark:ring-slate-700">
                        @else
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-violet-500 text-white font-bold text-xs shadow-md">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                        @endif
                        <span class="hidden md:flex md:items-center">
                            <span class="text-xs font-semibold leading-5 text-slate-800 dark:text-slate-200" aria-hidden="true">
                                {{ auth()->user()->name ?? 'User' }}
                            </span>
                            <i class="fa-solid fa-angle-down ml-1 text-[10px] text-slate-400"></i>
                        </span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown-menu" class="absolute right-0 z-10 mt-2 w-52 origin-top-right rounded-xl bg-white dark:bg-slate-800 p-1.5 shadow-lg border border-slate-100 dark:border-slate-700 ring-1 ring-slate-900/5 focus:outline-none hidden">
                        <div class="px-3.5 py-2.5 border-b border-slate-100 dark:border-slate-700 mb-1 flex items-center gap-x-3">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('uploads/profiles/' . auth()->user()->profile_photo) }}" alt="Avatar" class="h-9 w-9 rounded-full object-cover shadow-sm border border-slate-100 dark:border-slate-600">
                            @else
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-violet-500 text-white font-bold text-sm shadow-md">
                                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5 truncate">{{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'Staff' }}</p>
                            </div>
                        </div>

                        <a href="{{ route('profile.show') }}" class="flex items-center gap-x-3 rounded-lg px-3.5 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <i class="fa-regular fa-user w-4 text-center"></i> My Profile
                        </a>
                        <a href="#" class="flex items-center gap-x-3 rounded-lg px-3.5 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <i class="fa-solid fa-sliders w-4 text-center"></i> Settings
                        </a>
                        <div class="h-px bg-slate-100 dark:bg-slate-700 my-1"></div>
                        
                        <!-- Logout Action Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-x-3 rounded-lg px-3.5 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors text-left border-0 bg-transparent cursor-pointer font-medium">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center text-red-600 dark:text-red-400"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 py-6 bg-slate-50 dark:bg-slate-950 transition-colors duration-150">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-3 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400 dark:text-slate-500 transition-colors duration-150">
            &copy; 2026 Electronics Inventory & POS Management System. All rights reserved.
        </footer>
    </div>

    <!-- Core CDNs -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- DataTables JS CDN -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Base Scripts -->
    <script>
        // Set Toastr Global Options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };

        // Mobile Sidebar toggler
        const toggleMobileBtn = document.getElementById('toggle-sidebar-mobile');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');

        if(toggleMobileBtn && sidebar && overlay) {
            toggleMobileBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // Profile Dropdown handler
        const dropdownBtn = document.getElementById('profile-dropdown-btn');
        const dropdownMenu = document.getElementById('profile-dropdown-menu');

        if(dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if(!dropdownMenu.classList.contains('hidden') && !e.target.closest('#profile-dropdown-container')) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }

        // Dark Mode Toggle logic
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleBtn = document.getElementById('theme-toggle');

        // Determine state and show appropriate icon
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
                
                // Dispatch event so external items (like maps, charts) can adapt
                window.dispatchEvent(new Event('theme-changed'));
            });
        }

        // Collapsible sidebar groups logic
        $(document).ready(function() {
            $('.sidebar-group').each(function() {
                const $group = $(this);
                const groupKey = $group.data('group');
                const $toggle = $group.find('.sidebar-group-toggle');
                const $items = $group.find('.sidebar-group-items');
                const $chevron = $group.find('.fa-chevron-down');
                
                // Check if any link inside is active
                const hasActiveLink = $items.find('a.text-white').length > 0;
                
                // Retrieve state or default to expanded ('true')
                let isExpanded = localStorage.getItem('sidebar-group-' + groupKey);
                if (isExpanded === null) {
                    isExpanded = 'true';
                }
                
                // If has active link, override to true
                if (hasActiveLink) {
                    isExpanded = 'true';
                }
                
                if (isExpanded === 'true') {
                    $items.show();
                    $chevron.addClass('rotate-180');
                } else {
                    $items.hide();
                    $chevron.removeClass('rotate-180');
                }
                
                $toggle.on('click', function() {
                    const isVisible = $items.is(':visible');
                    $items.slideToggle(150);
                    $chevron.toggleClass('rotate-180', !isVisible);
                    localStorage.setItem('sidebar-group-' + groupKey, (!isVisible).toString());
                });
            });
        });

        // Global Alert Handler (Blade to Toastr)
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any() && !request()->routeIs('login') && !request()->routeIs('password.*'))
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    @stack('scripts')
</body>
</html>
