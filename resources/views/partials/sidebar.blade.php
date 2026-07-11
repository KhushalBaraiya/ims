<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    {{-- ── Brand / Logo ── --}}
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <svg width="25" viewBox="0 0 25 42" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <path
                                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                id="path-1"></path>
                            <path
                                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                id="path-3"></path>
                        </defs>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-27.000000, -15.000000)">
                                <g transform="translate(27.000000, 15.000000)">
                                    <g transform="translate(0.000000, 8.000000)">
                                        <use fill="currentColor" xlink:href="#path-1"></use>
                                        <use fill="currentColor" xlink:href="#path-3"></use>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">IMS</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ════════════════════════════════════════
             1. DASHBOARD  (flat, non-collapsible)
             ════════════════════════════════════════ --}}
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div class="text-truncate">{{ __('messages.menu_dashboard') }}</div>
            </a>
        </li>

        {{-- ════════════════════════════════════════
             2. INVENTORY  (non-collapsible group)
             Sub-items: Categories → Brands → Products
             ════════════════════════════════════════ --}}
        @canany(['main_categories.view', 'sub_categories.view', 'brands.view', 'products.view'])
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-3">
                    <i class="bx bx-box" style="font-size:20px;"></i>
                    {{ __('messages.section_inventory') }}
                </span>
            </li>
        @endcanany

        {{-- Categories --}}
        @php
            $catActive = request()->routeIs('main-categories.*', 'sub-categories.*');
            $showCatMenu = auth()->user()->can('main_categories.view') || auth()->user()->can('sub_categories.view');
        @endphp
        @if ($showCatMenu)
            <li class="menu-item {{ $catActive ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div class="text-truncate">{{ __('messages.menu_categories') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('main_categories.view')
                        <li class="menu-item {{ request()->routeIs('main-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('main-categories.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('messages.menu_categories') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('sub_categories.view')
                        <li class="menu-item {{ request()->routeIs('sub-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('sub-categories.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('messages.menu_sub_categories') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        {{-- Brands --}}
        @can('brands.view')
            <li class="menu-item {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                <a href="{{ route('brands.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-award"></i>
                    <div class="text-truncate">{{ __('messages.menu_brands') }}</div>
                </a>
            </li>
        @endcan

        {{-- Products --}}
        @can('products.view')
            @php $productsActive = request()->routeIs('products.*'); @endphp
            <li class="menu-item {{ $productsActive ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div class="text-truncate">{{ __('messages.menu_products') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-ul"></i>
                            <div class="text-truncate">{{ __('messages.list_view') }}</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('products.gallery') ? 'active' : '' }}">
                        <a href="{{ route('products.gallery') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                            <div class="text-truncate">{{ __('messages.gallery_view') }}</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('products.by-category') ? 'active' : '' }}">
                        <a href="{{ route('products.by-category') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-category-alt"></i>
                            <div class="text-truncate">{{ __('messages.prod_by_category') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             4. STOCK IN  (group)
             Sub-items: Purchase → Purchase Return
             ════════════════════════════════════════ --}}
        @canany(['purchases.view', 'purchase_returns.view'])
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-2">
                    <i class="bx bx-cart-download" style="font-size:20px;"></i>
                    {{ __('messages.menu_purchases') }} / {{ __('messages.menu_purchase_returns') }}
                </span>
            </li>
        @endcanany

        @can('purchases.view')
            <li class="menu-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <a href="{{ route('purchases.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-download"></i>
                    <div class="text-truncate">{{ __('messages.menu_purchases') }}</div>
                </a>
            </li>
        @endcan

        @can('purchase_returns.view')
            <li class="menu-item {{ request()->routeIs('purchase-returns.*') ? 'active' : '' }}">
                <a href="{{ route('purchase-returns.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-revision"></i>
                    <div class="text-truncate">{{ __('messages.menu_purchase_returns') }}</div>
                </a>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             4. STOCK OUT  (group)
             Sub-items: Sale → Sale Return
             ════════════════════════════════════════ --}}
        @canany(['sales.view', 'sale_returns.view'])
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-2">
                    <i class="bx bx-cart-alt" style="font-size:20px;"></i>
                    {{ __('messages.menu_sales') }} / {{ __('messages.menu_sale_returns') }}
                </span>
            </li>
        @endcanany

        @can('sales.view')
            <li class="menu-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <a href="{{ route('sales.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                    <div class="text-truncate">{{ __('messages.menu_sales') }}</div>
                </a>
            </li>
        @endcan

        @can('sale_returns.view')
            <li class="menu-item {{ request()->routeIs('sale-returns.*') ? 'active' : '' }}">
                <a href="{{ route('sale-returns.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-transfer"></i>
                    <div class="text-truncate">{{ __('messages.menu_sale_returns') }}</div>
                </a>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             3.5 STOCK MANAGEMENT
             Single flat item: Adjustments
             ════════════════════════════════════════ --}}
        @canany(['stocks.view', 'stocks.create'])
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-2">
                    <i class="bx bx-layer" style="font-size:20px;"></i>
                    {{ __('messages.stock_adjustments') }}
                </span>
            </li>
        @endcanany

        @can('stocks.view')
            @php
                $adjustmentsActive =
                    request()->routeIs('stocks.history') ||
                    request()->routeIs('stocks.adjust') ||
                    request()->routeIs('stocks.store_adjustment') ||
                    request()->routeIs('stocks.edit_adjustment') ||
                    request()->routeIs('stocks.show_adjustment') ||
                    request()->routeIs('stocks.update_adjustment') ||
                    request()->routeIs('stocks.index');
            @endphp
            <li class="menu-item {{ $adjustmentsActive ? 'active' : '' }}">
                <a href="{{ route('stocks.history') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-slider"></i>
                    <div class="text-truncate">{{ __('messages.stock_adjustments') }}</div>
                </a>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             5. PEOPLES  (group)
             Sub-items: Supplier → Customer → Users
             ════════════════════════════════════════ --}}
        @canany(['suppliers.view', 'customers.view', 'users.view'])
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-2">
                    <i class="bx bx-group" style="font-size:20px;"></i>
                    {{ __('messages.menu_suppliers') }} / {{ __('messages.menu_customers') }}
                </span>
            </li>
        @endcanany

        @can('suppliers.view')
            <li class="menu-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <a href="{{ route('suppliers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div class="text-truncate">{{ __('messages.menu_suppliers') }}</div>
                </a>
            </li>
        @endcan

        @can('customers.view')
            <li class="menu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div class="text-truncate">{{ __('messages.menu_customers') }}</div>
                </a>
            </li>
        @endcan

        @can('users.view')
            <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div class="text-truncate">{{ __('messages.user_accounts') }}</div>
                </a>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             6. REPORTS  (group)
             ════════════════════════════════════════ --}}
        @can('reports.view')
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-2">
                    <i class="bx bx-bar-chart-alt-2" style="font-size:20px;"></i>
                    {{ __('messages.section_reports') }}
                </span>
            </li>

            <li class="menu-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                    <div class="text-truncate">{{ __('messages.all_reports') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <a href="{{ route('reports.sales') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                    <div class="text-truncate">{{ __('messages.sales_report') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('reports.purchases') ? 'active' : '' }}">
                <a href="{{ route('reports.purchases') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-download"></i>
                    <div class="text-truncate">{{ __('messages.purchase_report') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
                <a href="{{ route('reports.profit-loss') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trending-up"></i>
                    <div class="text-truncate">{{ __('messages.profit_loss') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('reports.top-selling') ? 'active' : '' }}">
                <a href="{{ route('reports.top-selling') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trophy"></i>
                    <div class="text-truncate">{{ __('messages.top_selling') }}</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('reports.stock-alert') ? 'active' : '' }}">
                <a href="{{ route('reports.stock-alert') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-error"></i>
                    <div class="text-truncate">{{ __('messages.stock_alert_menu') }}</div>
                </a>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             7. MANAGEMENT & SETTINGS  (bottom group)
             Sub-items: Roles & Permissions → Activity Logs → Settings
             ════════════════════════════════════════ --}}
        @canany(['roles.view', 'activity_logs.view', 'settings.view', 'currencies.view'])
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text d-flex align-items-center gap-2">
                    <i class="bx bx-cog" style="font-size:20px;"></i>
                    {{ __('messages.role_management') }} &amp; {{ __('messages.settings') }}
                </span>
            </li>
        @endcanany

        @can('roles.view')
            <li class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shield"></i>
                    <div class="text-truncate">{{ __('messages.menu_roles') }}</div>
                </a>
            </li>
        @endcan

        @can('activity_logs.view')
            <li class="menu-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <a href="{{ route('activity-logs.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div class="text-truncate">{{ __('messages.activity_logs') }}</div>
                </a>
            </li>
        @endcan

        @can('currencies.view')
            <li class="menu-item {{ request()->routeIs('currencies.*') ? 'active' : '' }}">
                <a href="{{ route('currencies.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div class="text-truncate">{{ __('messages.menu_currencies') }}</div>
                </a>
            </li>
        @endcan

        @can('settings.view')
            <li class="menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <a href="{{ route('settings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div class="text-truncate">{{ __('messages.menu_settings') }}</div>
                </a>
            </li>
        @endcan

        {{-- ════════════════════════════════════════
             8. USER PROFILE  (bottom-most)
             ════════════════════════════════════════ --}}
        <li class="menu-header small text-uppercase mt-1">
            <span class="menu-header-text d-flex align-items-center gap-2">
                <i class="bx bx-user-circle" style="font-size:20px;"></i>
                {{ __('messages.profile') }}
            </span>
        </li>

        {{-- Profile card with dropdown --}}
        <li
            class="menu-item nav-item dropdown sidebar-user-dropdown {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <a href="javascript:void(0);"
                class="menu-link d-flex align-items-center gap-2 px-3 py-2 sidebar-profile-trigger"
                data-bs-toggle="dropdown" aria-expanded="false">
                {{-- Avatar --}}
                @if (Auth::user()->profile_photo)
                    <img src="{{ asset('uploads/profiles/' . Auth::user()->profile_photo) }}"
                        class="rounded-circle flex-shrink-0 sidebar-avatar" alt="{{ Auth::user()->name }}"
                        style="width:32px;height:32px;object-fit:cover;">
                @else
                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center sidebar-avatar-placeholder"
                        style="width:32px;height:32px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                {{-- Name + caret --}}
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate sidebar-user-name"
                        style="font-size:.87rem;max-width:120px;">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-truncate sidebar-user-role" style="font-size:.73rem;max-width:120px;">
                        {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                    </div>
                </div>
                <i class="bx bx-chevron-right sidebar-user-caret ms-auto flex-shrink-0" style="font-size:1rem;"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-end sidebar-profile-dropdown">
                <li>
                    <div class="px-3 py-2 border-bottom sidebar-profile-header">
                        <div class="fw-semibold" style="font-size:.88rem;">{{ Auth::user()->name }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ Auth::user()->email }}</div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.show') }}">
                        <i class="bx bx-user"></i>
                        {{ __('messages.profile') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{ route('profile.show') }}#edit-profile">
                        <i class="bx bx-edit"></i>
                        {{ __('messages.profile_information') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{ route('profile.show') }}#change-password">
                        <i class="bx bx-lock-alt"></i>
                        {{ __('messages.change_password') }}
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider my-1">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-profile-logout">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                            <i class="bx bx-power-off"></i>
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </li>

    </ul>

    {{-- Hidden logout form kept for any legacy references --}}
    <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>

</aside>

@push('styles')
    <style>
        /* ── Sidebar section header icons ───────────────────────── */
        .menu-header-text {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .menu-header-text .bx {
            opacity: .65;
        }

        /* ── User profile trigger ───────────────────────────────── */
        .sidebar-profile-trigger {
            border-radius: 8px !important;
            transition: background .15s;
            cursor: pointer;
        }

        .sidebar-profile-trigger:hover {
            background: rgba(105, 108, 255, .09) !important;
        }

        /* Avatar placeholder circle */
        .sidebar-avatar-placeholder {
            background: rgba(105, 108, 255, .15);
            color: #696cff;
            font-size: .88rem;
            font-weight: 700;
            border: 2px solid rgba(105, 108, 255, .25);
        }

        .sidebar-avatar {
            border: 2px solid rgba(105, 108, 255, .25);
        }

        /* Name / role text */
        .sidebar-user-name {
            color: inherit;
            line-height: 1.2;
        }

        .sidebar-user-role {
            opacity: .6;
            line-height: 1.2;
        }

        /* Caret rotation when open */
        .sidebar-profile-trigger[aria-expanded="true"] .sidebar-user-caret {
            transform: rotate(90deg);
        }

        .sidebar-user-caret {
            transition: transform .2s;
            opacity: .5;
        }

        /* Profile dropdown */
        .sidebar-profile-dropdown {
            min-width: 210px !important;
            border-radius: 10px !important;
            border: 1px solid rgba(0, 0, 0, .08) !important;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .13) !important;
            padding: 4px 0 !important;
        }

        [data-bs-theme="dark"] .sidebar-profile-dropdown {
            border-color: rgba(255, 255, 255, .1) !important;
        }

        .sidebar-profile-header {
            line-height: 1.3;
        }

        .sidebar-profile-dropdown .dropdown-item {
            font-size: .9rem;
            gap: 8px;
        }

        .sidebar-profile-dropdown .dropdown-item:hover {
            background: rgba(105, 108, 255, .07) !important;
        }

        .sidebar-profile-dropdown .dropdown-item .bx {
            font-size: 1rem;
            opacity: .75;
        }
    </style>
@endpush
