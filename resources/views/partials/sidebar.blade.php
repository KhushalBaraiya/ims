<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    {{-- ── Brand ── --}}
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
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
            <span class="app-brand-text demo menu-text fw-bold ms-2">E-Com</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    {{-- ── Logged-in user card ── --}}
    <div class="px-3 py-3">
        <a href="{{ route('profile.edit') }}"
            class="d-flex align-items-center gap-3 text-decoration-none sidebar-user-card rounded-3 px-2 py-2"
            style="transition:background .18s;">
            @if (Auth::user()->image)
                <img src="{{ asset(Auth::user()->image) }}" class="rounded-circle flex-shrink-0"
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
    </div>

    <div class="menu-divider my-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ══════════════════════════════════
             MAIN
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.main') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div class="text-truncate">{{ __('admin.dashboard') }}</div>
            </a>
        </li>

        {{-- ══════════════════════════════════
             CATALOGUE
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.catalogue') }}</span>
        </li>

        {{-- Categories --}}
        @php
            $catActive = request()->routeIs(
                'admin.category.*',
                'admin.subcategory.*',
                'admin.subincategory.*',
                'admin.categoryimage.*',
            );
        @endphp
        <li class="menu-item {{ $catActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div class="text-truncate">{{ __('admin.category') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.category.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.main_category') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.subcategory.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subcategory.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.sub_category') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.subincategory.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subincategory.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.sub_in_category') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.categoryimage.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categoryimage.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.category_images') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Brands --}}
        <li class="menu-item {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">
            <a href="{{ route('admin.brand.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-award"></i>
                <div class="text-truncate">{{ __('admin.brands') }}</div>
            </a>
        </li>

        {{-- Products --}}
        @php
            $prodActive = request()->routeIs(
                'admin.product.*',
                'admin.product-service.*',
                'admin.product-shipping.*',
                'admin.stock.*',
            );
        @endphp
        <li class="menu-item {{ $prodActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate">{{ __('admin.products') }}</div>
            </a>
            <ul class="menu-sub">
                <li
                    class="menu-item {{ request()->routeIs('admin.product.index', 'admin.product.create', 'admin.product.edit', 'admin.product.show', 'admin.product.attributes.edit') ? 'active' : '' }}">
                    <a href="{{ route('admin.product.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.all_products') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.product-service.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.product-service.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.services') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.product-shipping.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.product-shipping.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.shipping_methods') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.stock.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.stock') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ══════════════════════════════════
             ORDERS
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.orders_section') }}</span>
        </li>

        <li
            class="menu-item {{ request()->routeIs('admin.order.index', 'admin.order.create', 'admin.order.edit', 'admin.order.show') ? 'active' : '' }}">
            <a href="{{ route('admin.order.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                <div class="text-truncate">{{ __('admin.orders') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.order-item.*') ? 'active' : '' }}">
            <a href="{{ route('admin.order-item.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div class="text-truncate">{{ __('admin.order_items') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.cart-item.*') ? 'active' : '' }}">
            <a href="{{ route('admin.cart-item.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div class="text-truncate">{{ __('admin.cart_items') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.return-order.*') ? 'active' : '' }}">
            <a href="{{ route('admin.return-order.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-revision"></i>
                <div class="text-truncate">{{ __('admin.return_orders') }}</div>
            </a>
        </li>

        {{-- ══════════════════════════════════
             USERS
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.users_section') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <a href="{{ route('admin.user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div class="text-truncate">{{ __('admin.users') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
            <a href="{{ route('admin.role.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield"></i>
                <div class="text-truncate">{{ __('admin.roles') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.user-address.*') ? 'active' : '' }}">
            <a href="{{ route('admin.user-address.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div class="text-truncate">{{ __('admin.user_addresses') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.save-card.*') ? 'active' : '' }}">
            <a href="{{ route('admin.save-card.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card-alt"></i>
                <div class="text-truncate">{{ __('admin.saved_cards') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.wishlist.*') ? 'active' : '' }}">
            <a href="{{ route('admin.wishlist.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-heart"></i>
                <div class="text-truncate">{{ __('admin.wishlists') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reviews.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-star"></i>
                <div class="text-truncate">{{ __('admin.reviews') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.notification.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notification.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bell"></i>
                <div class="text-truncate">{{ __('admin.notifications') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.delete-account.*') ? 'active' : '' }}">
            <a href="{{ route('admin.delete-account.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-x"></i>
                <div class="text-truncate">{{ __('admin.delete_accounts') }}</div>
            </a>
        </li>

        {{-- ══════════════════════════════════
             FINANCE
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.finance') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.payment.*') ? 'active' : '' }}">
            <a href="{{ route('admin.payment.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div class="text-truncate">{{ __('admin.payments') }}</div>
            </a>
        </li>

        {{-- ══════════════════════════════════
             MARKETING
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.marketing') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
            <a href="{{ route('admin.banner.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image-alt"></i>
                <div class="text-truncate">{{ __('admin.banners') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.offer.*') ? 'active' : '' }}">
            <a href="{{ route('admin.offer.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-offer"></i>
                <div class="text-truncate">{{ __('admin.offers') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.coupon.*') ? 'active' : '' }}">
            <a href="{{ route('admin.coupon.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-coupon"></i>
                <div class="text-truncate">{{ __('admin.coupons') }}</div>
            </a>
        </li>

        {{-- Blog --}}
        @php $blogActive = request()->routeIs('admin.blog.*','admin.blog-category.*'); @endphp
        <li class="menu-item {{ $blogActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div class="text-truncate">{{ __('admin.blog') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.blog-category.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.blog-category.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.categories') }}</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ request()->routeIs('admin.blog.index', 'admin.blog.create', 'admin.blog.edit', 'admin.blog.show') ? 'active' : '' }}">
                    <a href="{{ route('admin.blog.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.all_blogs') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ══════════════════════════════════
             SUPPORT & POLICIES
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.support_policies') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.contactus.*') ? 'active' : '' }}">
            <a href="{{ route('admin.contactus.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate">{{ __('admin.contact_us') }}</div>
            </a>
        </li>

        {{-- FAQs --}}
        @php $faqActive = request()->routeIs('admin.faq.*','admin.sub-faq.*'); @endphp
        <li class="menu-item {{ $faqActive ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-question-mark"></i>
                <div class="text-truncate">{{ __('admin.faqs') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faq.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.faqs') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.sub-faq.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sub-faq.index') }}" class="menu-link">
                        <div class="text-truncate">{{ __('admin.sub_faqs') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.term_condition.*') ? 'active' : '' }}">
            <a href="{{ route('admin.term_condition.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate">{{ __('admin.terms_conditions') }}</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.return-exchange-policy.*') ? 'active' : '' }}">
            <a href="{{ route('admin.return-exchange-policy.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer"></i>
                <div class="text-truncate">{{ __('admin.return_exchange_policy') }}</div>
            </a>
        </li>

        {{-- ══════════════════════════════════
             ACCOUNT
        ══════════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('admin.account') }}</span>
        </li>

        <li class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div class="text-truncate">{{ __('admin.my_profile') }}</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link"
                onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off text-danger"></i>
                <div class="text-truncate text-danger">{{ __('admin.logout') }}</div>
            </a>
        </li>

    </ul>

    <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
        @csrf
    </form>

</aside>

@push('styles')
    <style>
        .sidebar-user-card:hover {
            background: rgba(105, 108, 255, 0.08) !important;
        }
    </style>
@endpush
