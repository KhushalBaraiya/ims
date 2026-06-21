<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center me-auto">
            <div class="nav-item d-flex align-items-center">
                <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none"
                    placeholder="{{ __('admin.search') }}" aria-label="{{ __('admin.search') }}" />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-md-auto">

            <!-- Live Date & Time -->
            <li class="nav-item me-3 d-none d-lg-flex align-items-center">
                <div id="navbarClock" class="d-flex flex-column align-items-end"
                    style="line-height:1.2;cursor:default;user-select:none;">
                    <span id="navClock-time" class="fw-bold"
                        style="font-size:.95rem;font-variant-numeric:tabular-nums;letter-spacing:.02em;"></span>
                    <span id="navClock-date" class="text-muted" style="font-size:.72rem;"></span>
                </div>
            </li>
            <!-- /Live Date & Time -->

            <!-- Language Switcher -->
            <li class="nav-item dropdown me-2">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center gap-1 px-2"
                    href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-globe fs-5"></i>
                    <span class="d-none d-md-inline fw-semibold" style="font-size:.85rem;">
                        @if (app()->getLocale() === 'hi')
                            हिंदी
                        @elseif(app()->getLocale() === 'gu')
                            ગુજ
                        @else
                            EN
                        @endif
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:140px;">
                    <li>
                        <a href="{{ route('locale.switch', ['locale' => 'en']) }}"
                            class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <span>🇬🇧</span> {{ __('admin.locale_english') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('locale.switch', ['locale' => 'hi']) }}"
                            class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'hi' ? 'active' : '' }}">
                            <span>🇮🇳</span> {{ __('admin.locale_hindi') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('locale.switch', ['locale' => 'gu']) }}"
                            class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'gu' ? 'active' : '' }}">
                            <span>🇮🇳</span> {{ __('admin.locale_gujarati') }}
                        </a>
                    </li>
                </ul>
            </li>
            <!-- /Language Switcher -->

            <!-- Theme Toggle -->
            <li class="nav-item me-2">
                <button type="button" id="adminThemeToggle"
                    class="nav-link admin-theme-toggle d-flex align-items-center justify-content-center"
                    aria-label="Switch to dark mode" title="Switch theme">
                    <i class="bx bx-moon fs-5"></i>
                </button>
            </li>
            <!-- /Theme Toggle -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->image)
                            <img src="{{ asset(Auth::user()->image) }}" alt class="w-px-40 h-auto rounded-circle"
                                style="object-fit:cover;width:40px;height:40px;" />
                        @else
                            <div class="w-px-40 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px;height:40px;background:#696cff;">
                                <span
                                    class="text-white fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (Auth::user()->image)
                                            <img src="{{ asset(Auth::user()->image) }}"
                                                class="w-px-40 h-auto rounded-circle"
                                                style="object-fit:cover;width:40px;height:40px;" />
                                        @else
                                            <div class="w-px-40 rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:40px;height:40px;background:#696cff;">
                                                <span
                                                    class="text-white fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-body-secondary">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="icon-base bx bx-user icon-md me-3"></i>
                            <span>{{ __('admin.my_profile') }}</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="icon-base bx bx-power-off icon-md me-3"></i>
                                <span>{{ __('admin.logout') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>

            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
