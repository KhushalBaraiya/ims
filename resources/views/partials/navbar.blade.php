@php
    $currentLocale = app()->getLocale();
    $currentLang = $supportedLanguages[$currentLocale] ?? $supportedLanguages['en'];
@endphp

<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end w-100" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

            {{-- ── Live Clock ── --}}
            <li class="nav-item me-2 d-none d-lg-flex align-items-center">
                <div class="d-flex flex-column align-items-end" style="line-height:1.2;">
                    <span id="navClock-time" class="fw-bold"
                        style="font-size:.93rem;font-variant-numeric:tabular-nums;letter-spacing:.02em;"></span>
                    <span id="navClock-date" class="text-muted" style="font-size:.71rem;"></span>
                </div>
            </li>

            {{-- ── Language Switcher ── --}}
            <li class="nav-item dropdown me-1">

                {{-- Trigger: globe + code --}}
                <a class="nav-link d-flex align-items-center gap-1 px-2 ls-trigger" href="javascript:void(0);"
                    data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('messages.language') }}"
                    style="height:36px;">
                    <i class="bx bx-globe" style="font-size:1.2rem;"></i>
                    <span class="fw-bold"
                        style="font-size:.85rem;letter-spacing:.04em;">{{ $currentLang['code'] }}</span>
                </a>

                {{-- Dropdown — use standard Bootstrap ul>li>a.dropdown-item structure --}}
                <ul class="dropdown-menu dropdown-menu-end ls-dropdown-menu">

                    @foreach ($supportedLanguages as $locale => $lang)
                        @php $active = ($currentLocale === $locale); @endphp
                        <li>
                            <a href="{{ route('language.switch', $locale) }}"
                                class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 ls-lang-item {{ $active ? 'active' : '' }}">
                                {{-- Country code badge --}}
                                <span class="ls-lang-abbr">{{ $lang['abbr'] }}</span>
                                {{-- Language name --}}
                                <span class="ls-lang-name">{{ $lang['label'] }}</span>
                                {{-- Active check --}}
                                @if ($active)
                                    <i class="bx bx-check ms-auto" style="font-size:1rem;"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach

                </ul>
            </li>

            {{-- ── Theme Toggle ── --}}
            <li class="nav-item me-1">
                <a href="javascript:void(0);" id="adminThemeToggle"
                    class="nav-link d-flex align-items-center justify-content-center px-2 theme-toggle-btn"
                    title="Dark mode" style="height:36px;width:36px;border-radius:8px;">
                    <i class="bx bx-moon" style="font-size:1.2rem;"></i>
                </a>
            </li>

            {{-- ── User Dropdown ── --}}
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . Auth::user()->profile_photo) }}"
                                class="rounded-circle" alt="avatar"
                                style="object-fit:cover;width:40px;height:40px;" />
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px;height:40px;background:#696cff;">
                                <span class="text-white fw-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                            <div class="d-flex align-items-center gap-3">
                                @if (Auth::user()->profile_photo)
                                    <img src="{{ asset('uploads/profiles/' . Auth::user()->profile_photo) }}"
                                        class="rounded-circle flex-shrink-0"
                                        style="object-fit:cover;width:40px;height:40px;" />
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:40px;height:40px;background:#696cff;">
                                        <span class="text-white fw-bold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                            <i class="bx bx-user me-2"></i>{{ __('messages.profile') }}
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-power-off me-2"></i>{{ __('messages.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>

@push('styles')
    <style>
        /* ── Language trigger hover ── */
        .ls-trigger {
            border-radius: 8px;
            transition: background .15s;
        }

        .ls-trigger:hover,
        .ls-trigger[aria-expanded="true"] {
            background: rgba(105, 108, 255, .09) !important;
        }

        /* ── Theme toggle button ── */
        .theme-toggle-btn {
            border-radius: 8px !important;
            transition: background .15s;
            cursor: pointer;
            text-decoration: none !important;
            color: inherit;
            border: none;
            background: transparent;
        }

        .theme-toggle-btn:hover {
            background: rgba(105, 108, 255, .09) !important;
            color: #696cff !important;
        }

        .theme-toggle-btn i {
            font-size: 1.2rem;
            line-height: 1;
            color: #566a7f;
            transition: color .15s;
        }

        [data-bs-theme="dark"] .theme-toggle-btn i {
            color: #a1b0c0;
        }

        .theme-toggle-btn:hover i {
            color: #696cff !important;
        }

        /* ── Dropdown menu ── */
        .ls-dropdown-menu {
            min-width: 175px !important;
            border-radius: 10px !important;
            border: 1px solid rgba(0, 0, 0, .08) !important;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .12) !important;
            overflow: hidden;
            padding: 4px 0 !important;
            animation: lsDrop .13s ease;
        }

        [data-bs-theme="dark"] .ls-dropdown-menu {
            border-color: rgba(255, 255, 255, .1) !important;
        }

        @keyframes lsDrop {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Each language item — inherits dropdown-item display:block ── */
        .ls-lang-item {
            min-height: 42px;
            gap: 10px !important;
        }

        .ls-lang-item:hover {
            background: rgba(105, 108, 255, .07) !important;
        }

        /* Active state matches Sneat primary color */
        .ls-lang-item.active {
            background: rgba(105, 108, 255, .12) !important;
            color: #696cff !important;
        }

        .ls-lang-item.active .ls-lang-abbr {
            color: #696cff !important;
        }

        .ls-lang-item.active .ls-lang-name {
            color: #696cff !important;
            font-weight: 600;
        }

        /* Country abbreviation (GB / IN) */
        .ls-lang-abbr {
            font-size: .65rem;
            font-weight: 800;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #8592a3;
            width: 20px;
            flex-shrink: 0;
            line-height: 1;
        }

        /* Language name */
        .ls-lang-name {
            font-size: .9rem;
            font-weight: 500;
            color: #566a7f;
            line-height: 1;
        }

        [data-bs-theme="dark"] .ls-lang-name {
            color: #cfd8e3;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tick() {
                const now = new Date();
                let h = now.getHours(),
                    m = now.getMinutes(),
                    s = now.getSeconds();
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                const t = document.getElementById('navClock-time');
                const d = document.getElementById('navClock-date');
                if (t) t.textContent = pad(h) + ':' + pad(m) + ':' + pad(s) + ' ' + ampm;
                if (d) d.textContent = pad(now.getDate()) + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            }
            tick();
            setInterval(tick, 1000);
        })();
    </script>
@endpush
