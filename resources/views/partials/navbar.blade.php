<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end w-100" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

            {{-- Live Clock --}}
            <li class="nav-item me-2 d-none d-lg-flex align-items-center">
                <div id="navbarClock" class="d-flex flex-column align-items-end" style="line-height:1.2;">
                    <span id="navClock-time" class="fw-bold"
                        style="font-size:.95rem;font-variant-numeric:tabular-nums;letter-spacing:.02em;"></span>
                    <span id="navClock-date" class="text-muted" style="font-size:.72rem;"></span>
                </div>
            </li>

            {{-- Theme Toggle --}}
            <li class="nav-item me-1">
                <button type="button" id="adminThemeToggle"
                    class="nav-link d-flex align-items-center justify-content-center btn border-0 bg-transparent"
                    title="Toggle theme" style="width:36px;height:36px;">
                    <i class="bx bx-moon fs-5"></i>
                </button>
            </li>

            {{-- User Dropdown --}}
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . Auth::user()->profile_photo) }}" alt
                                class="rounded-circle" style="object-fit:cover;width:40px;height:40px;" />
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
                    {{-- Profile Header --}}
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
                            <i class="bx bx-user me-2"></i> My Profile
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-power-off me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>

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
