<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Antigravity POS</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
        /* Custom Styles for DataTables to match Tailwind theme */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e1;
            background-color: #fff;
            outline: none;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #cbd5e1;
            outline: none;
            margin-left: 0.5rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.75rem !important;
            border-radius: 0.375rem !important;
            border: 1px solid transparent !important;
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
        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        table.dataTable border-b {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .dataTables_wrapper {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800">

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar component -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-slate-900 border-r border-slate-800 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex h-16 shrink-0 items-center px-6 border-b border-slate-800">
            <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-violet-400 bg-clip-text text-transparent">
                <i class="fa-solid fa-bolt mr-2"></i>Antigravity POS
            </span>
        </div>
        
        <nav class="flex flex-1 flex-col overflow-y-auto px-4 py-6">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }} transition-all">
                                <i class="fa-solid fa-chart-pie flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Inventory Module Section -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-slate-500 uppercase tracking-wider">Inventory</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <!-- Main Categories -->
                        <li>
                            <a href="{{ route('main-categories.index') }}" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 {{ request()->routeIs('main-categories.*') ? 'bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }} transition-all">
                                <i class="fa-solid fa-tags flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Main Categories
                            </a>
                        </li>
                        
                        <!-- Brands -->
                        <li>
                            <a href="{{ route('brands.index') }}" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 {{ request()->routeIs('brands.*') ? 'bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }} transition-all">
                                <i class="fa-solid fa-copyright flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Brands
                            </a>
                        </li>

                        <!-- Products (Coming Soon) -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-500 hover:text-slate-400 hover:bg-slate-800/20 cursor-not-allowed">
                                <i class="fa-solid fa-box flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Products <span class="ml-auto text-[10px] bg-slate-800 text-slate-400 py-0.5 px-2 rounded-full font-medium">Soon</span>
                            </a>
                        </li>

                        <!-- Stocks -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-warehouse flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Stocks
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Transactions Section -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-slate-500 uppercase tracking-wider">Transactions</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <!-- Purchases -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-truck-ramp-box flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Purchases
                            </a>
                        </li>

                        <!-- Sales (POS) -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-cart-shopping flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Sales
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- People & Admin Section -->
                <li>
                    <div class="text-xs font-semibold leading-6 text-slate-500 uppercase tracking-wider">Management</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <!-- Suppliers -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-parachute-box flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Suppliers
                            </a>
                        </li>

                        <!-- Customers -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-users flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Customers
                            </a>
                        </li>

                        <!-- Reports -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-chart-line flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Reports
                            </a>
                        </li>

                        <!-- Settings -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-sliders flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Settings
                            </a>
                        </li>

                        <!-- Activity Logs -->
                        <li>
                            <a href="#" class="group flex gap-x-3 rounded-lg p-2.5 text-sm font-semibold leading-6 text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                                <i class="fa-solid fa-clock-rotate-left flex h-6 w-6 shrink-0 items-center justify-center text-lg"></i>
                                Activity Logs
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-slate-800 text-center text-xs text-slate-600">
            &copy; 2026 Antigravity POS
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        
        <!-- Header Navbar -->
        <header class="flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 sticky top-0 z-30">
            <button id="toggle-sidebar-mobile" type="button" class="-m-2.5 p-2.5 text-slate-700 lg:hidden hover:bg-slate-50 rounded-lg">
                <span class="sr-only">Open sidebar</span>
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            <!-- User Menu -->
            <div class="flex items-center gap-x-4 lg:gap-x-6 ml-auto">
                <div class="relative" id="profile-dropdown-container">
                    <button type="button" id="profile-dropdown-btn" class="-m-1.5 flex items-center p-1.5 gap-x-3 hover:bg-slate-50 rounded-full transition-all focus:outline-none">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-violet-500 text-white font-bold text-sm shadow-md">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="hidden md:flex md:items-center">
                            <span class="text-sm font-semibold leading-6 text-slate-800" aria-hidden="true">
                                {{ auth()->user()->name ?? 'User' }}
                            </span>
                            <i class="fa-solid fa-angle-down ml-2 text-xs text-slate-400"></i>
                        </span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown-menu" class="absolute right-0 z-10 mt-2.5 w-52 origin-top-right rounded-xl bg-white p-1.5 shadow-lg border border-slate-100 ring-1 ring-slate-900/5 focus:outline-none hidden">
                        <div class="px-3.5 py-2.5 border-b border-slate-100 mb-1">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'Staff' }}</p>
                        </div>

                        <a href="#" class="flex items-center gap-x-3 rounded-lg px-3.5 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i class="fa-regular fa-user w-4 text-center"></i> My Profile
                        </a>
                        <a href="#" class="flex items-center gap-x-3 rounded-lg px-3.5 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i class="fa-solid fa-sliders w-4 text-center"></i> Settings
                        </a>
                        <div class="h-px bg-slate-100 my-1"></div>
                        
                        <!-- Logout Action Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-x-3 rounded-lg px-3.5 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left border-0 bg-transparent cursor-pointer font-medium">
                                <i class="fa-solid fa-right-from-bracket w-4 text-center text-red-600"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 py-10 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400">
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
