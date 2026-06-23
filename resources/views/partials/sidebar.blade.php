<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    {{-- Brand --}}
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

    {{-- Logged-in user --}}
    {{-- <div class="px-3 py-3">
        <a href="{{ route('profile.show') }}"
            class="d-flex align-items-center gap-3 text-decoration-none sidebar-user-card rounded-3 px-2 py-2"
            style="transition:background .18s;">
            @if (Auth::user()->profile_photo)
                <img src="{{ asset('uploads/profiles/' . Auth::user()->profile_photo) }}"
                    class="rounded-circle flex-shrink-0"
                    style="width:38px;height:38px;object-fit:cover;border:2px solid rgba(105,108,255,.3);">
            @else
                <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center fw-bold"
                    style="width:38px;height:38px;background:rgba(105,108,255,.15);color:#696cff;font-size:1rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="overflow-hidden">
                <div class="fw-semibold text-truncate" style="font-size:.88rem;max-width:140px;">
                    {{ Auth::user()->name }}</div>
                <div class="text-truncate" style="font-size:.75rem;opacity:.6;max-width:140px;">
                    {{ Auth::user()->email }}</div>
            </div>
        </a>
    </div> --}}

    <div class="menu-divider my-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ── MAIN ── --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.section_main') }}</span>
        </li>
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div class="text-truncate">{{ __('messages.menu_dashboard') }}</div>
            </a>
        </li>

        {{-- ── INVENTORY ── --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.section_inventory') }}</span>
        </li>

        @php
            $catActive = request()->routeIs('main-categories.*', 'sub-categories.*');
        @endphp
        <li class="menu-item {{ $catActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div class="text-truncate">{{ __('messages.menu_categories') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('main-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('main-categories.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('messages.menu_categories') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('sub-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('sub-categories.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('messages.menu_sub_categories') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('brands.*') ? 'active' : '' }}">
            <a href="{{ route('brands.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-award"></i>
                <div class="text-truncate">{{ __('messages.menu_brands') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <a href="{{ route('products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate">{{ __('messages.menu_products') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
            <a href="{{ route('stocks.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store-alt"></i>
                <div class="text-truncate">{{ __('messages.menu_stock') }}</div>
            </a>
        </li>

        {{-- ── TRANSACTIONS ── --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.section_transactions') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
            <a href="{{ route('purchases.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart-download"></i>
                <div class="text-truncate">{{ __('messages.menu_purchases') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('purchase-returns.*') ? 'active' : '' }}">
            <a href="{{ route('purchase-returns.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-revision"></i>
                <div class="text-truncate">{{ __('messages.menu_purchase_returns') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <a href="{{ route('sales.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                <div class="text-truncate">{{ __('messages.menu_sales') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('sale-returns.*') ? 'active' : '' }}">
            <a href="{{ route('sale-returns.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer"></i>
                <div class="text-truncate">{{ __('messages.menu_sale_returns') }}</div>
            </a>
        </li>

        {{-- ── MANAGEMENT ── --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.section_management') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <a href="{{ route('suppliers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div class="text-truncate">{{ __('messages.menu_suppliers') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <a href="{{ route('customers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div class="text-truncate">{{ __('messages.menu_customers') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('currencies.*') ? 'active' : '' }}">
            <a href="{{ route('currencies.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div class="text-truncate">{{ __('messages.menu_currencies') }}</div>
            </a>
        </li>

        {{-- ── USERS ── --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.section_users') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate">{{ __('messages.user_accounts') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <a href="{{ route('roles.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield"></i>
                <div class="text-truncate">{{ __('messages.menu_roles') }}</div>
            </a>
        </li>

        {{-- ── ACCOUNT ── --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.section_account') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <a href="{{ route('profile.show') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div class="text-truncate">{{ __('messages.profile') }}</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link"
                onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off text-danger"></i>
                <div class="text-truncate text-danger">{{ __('messages.logout') }}</div>
            </a>
        </li>

    </ul>

    <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>

</aside>

@push('styles')
    <style>
        .sidebar-user-card:hover {
            background: rgba(105, 108, 255, .08) !important;
        }
    </style>
@endpush
