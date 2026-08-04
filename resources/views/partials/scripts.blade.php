<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

<!-- Github Button -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery Validate -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- ─── Global utilities ──────────────────────────────────────────────────── --}}
<script>
    function togglePwd(id, btn) {
        var input = document.getElementById(id);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }

    function showAdminToast(message, type) {
        var old = document.getElementById('adminToast');
        if (old) old.remove();
        var icons = {
            success: '<i class="bx bx-check-circle"></i>',
            error: '<i class="bx bx-x-circle"></i>',
            warning: '<i class="bx bx-error"></i>'
        };
        var toast = document.createElement('div');
        toast.id = 'adminToast';
        toast.className = 'toast-' + (type || 'success');
        toast.innerHTML = '<span class="toast-icon">' + (icons[type] || icons.success) +
            '</span><span class="toast-msg">' + message + '</span><div class="toast-progress"></div>';
        toast.onclick = function() {
            toast.remove();
        };
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.style.transition = 'opacity 0.4s ease';
            toast.style.opacity = '0';
            setTimeout(function() {
                if (toast.parentNode) toast.remove();
            }, 400);
        }, 3000);
    }

    (function() {
        var themeToggle = document.getElementById('adminThemeToggle');
        var html = document.documentElement;

        function setTheme(theme) {
            var safeTheme = theme === 'dark' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', safeTheme);
            if (themeToggle) {
                var icon = themeToggle.querySelector('i');
                themeToggle.setAttribute('title', safeTheme === 'dark' ? 'Light mode' : 'Dark mode');
                if (icon) {
                    icon.className = safeTheme === 'dark' ? 'bx bx-sun' : 'bx bx-moon';
                    icon.style.fontSize = '1.2rem';
                }
            }
            // ── Sync SweetAlert2 theme ──────────────────────────────────────
            if (typeof Swal !== 'undefined') {
                Swal.mixin({
                    background: safeTheme === 'dark' ? '#2b2c40' : '#fff'
                });
            }
            try {
                localStorage.setItem('admin-theme', safeTheme);
            } catch (e) {}
        }
        setTheme(html.getAttribute('data-bs-theme'));
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                setTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
            });
        }
    })();
</script>

{{-- ─── Global Price Field: clear 0.00 on focus, restore on blur ─────────── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function bindPriceField(el) {
            if (el._priceBound) return;
            el._priceBound = true;

            el.addEventListener('focus', function() {
                if (parseFloat(this.value) === 0) {
                    this.value = '';
                }
                this.select();
            });

            el.addEventListener('blur', function() {
                if (this.value.trim() === '' || isNaN(parseFloat(this.value))) {
                    this.value = '0.00';
                } else {
                    // keep decimal places consistent with step
                    var step = parseFloat(this.getAttribute('step') || '0.01');
                    var decimals = (step.toString().split('.')[1] || '').length;
                    this.value = parseFloat(this.value).toFixed(decimals);
                }
            });
        }

        // Init on all existing number inputs with step="0.01"
        document.querySelectorAll('input[type="number"][step="0.01"]').forEach(bindPriceField);

        // Also catch dynamically added rows (purchase/sale line items)
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(function(node) {
                    if (node.nodeType !== 1) return;
                    var inputs = node.querySelectorAll ?
                        node.querySelectorAll('input[type="number"][step="0.01"]') : [];
                    inputs.forEach(bindPriceField);
                    if (node.matches && node.matches(
                            'input[type="number"][step="0.01"]')) {
                        bindPriceField(node);
                    }
                });
            });
        });
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
</script>

{{-- ─── Global Flatpickr Auto-Init ───────────────────────────────────────── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function fpConfig(el) {
            // Copy all Bootstrap classes (form-control, form-control-sm etc.) to altInput
            var classes = Array.from(el.classList)
                .filter(function(c) {
                    return c !== 'flatpickr-date' && c !== 'flatpickr-filter-date';
                })
                .join(' ');
            return {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-m-Y',
                altInputClass: classes,
                allowInput: true,
                disableMobile: true,
                appendTo: document.body,
                locale: {
                    firstDayOfWeek: 1
                },
                onReady: function(selectedDates, dateStr, instance) {
                    instance.altInput.placeholder = 'Select Date';
                    instance.altInput.style.cursor = 'pointer';

                    // Replace native month <select> with a plain text span
                    // so it never gets grabbed by Select2 or shows a browser dropdown
                    var cal = instance.calendarContainer;
                    if (cal) {
                        var monthSel = cal.querySelector('.flatpickr-monthDropdown-months');
                        if (monthSel) {
                            monthSel.setAttribute('data-no-select2', '1');
                            var mSpan = document.createElement('span');
                            mSpan.className = 'fp-month-label';
                            var idx = monthSel.selectedIndex;
                            mSpan.textContent = (idx >= 0 && monthSel.options[idx]) ?
                                monthSel.options[idx].text : '';
                            monthSel.parentNode.insertBefore(mSpan, monthSel);
                            monthSel.style.cssText =
                                'position:absolute;opacity:0;pointer-events:none;width:0;height:0;';
                            instance._fpMonthSpan = mSpan;
                            instance._fpMonthSel = monthSel;
                        }
                    }

                    // Wrap in input-group to add calendar icon
                    var wrapper = instance.altInput.parentNode;
                    if (wrapper && !wrapper.classList.contains('fp-wrapper')) {
                        var grp = document.createElement('div');
                        grp.className = 'input-group fp-wrapper';
                        wrapper.insertBefore(grp, instance.altInput);
                        grp.appendChild(instance.altInput);
                        var span = document.createElement('span');
                        span.className = 'input-group-text';
                        span.style.cursor = 'pointer';
                        span.innerHTML =
                            '<i class="bx bx-calendar" style="font-size:1rem;color:#697a8d;"></i>';
                        span.addEventListener('click', function() {
                            instance.open();
                        });
                        grp.appendChild(span);
                    }
                },
                onMonthChange: function(selectedDates, dateStr, instance) {
                    if (instance._fpMonthSpan && instance._fpMonthSel) {
                        var idx = instance._fpMonthSel.selectedIndex;
                        if (idx >= 0 && instance._fpMonthSel.options[idx]) {
                            instance._fpMonthSpan.textContent = instance._fpMonthSel.options[idx].text;
                        }
                    }
                }
            };
        }

        function initFlatpickr(context) {
            var root = context || document;

            root.querySelectorAll('.flatpickr-date:not([data-fp]), .flatpickr-filter-date:not([data-fp])')
                .forEach(function(el) {
                    el.setAttribute('data-fp', '1');
                    flatpickr(el, fpConfig(el));
                });
        }

        initFlatpickr(null);

        document.addEventListener('shown.bs.modal', function(e) {
            initFlatpickr(e.target);
        });
    });
</script>

{{-- ─── Mobile: overlay click closes sidebar + swipe-to-open ─────────── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Overlay click → close sidebar
        var overlay = document.querySelector('.layout-overlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                if (window.Helpers && typeof window.Helpers.toggleCollapsed === 'function') {
                    window.Helpers.setCollapsed(true, true);
                }
            });
        }

        // 2. Swipe right on left-edge (≤40px) → open sidebar on mobile
        // Swipe left anywhere on sidebar → close sidebar on mobile
        var touchStartX = 0;
        var touchStartY = 0;
        var SWIPE_THRESHOLD = 60; // min px to register swipe
        var EDGE_ZONE = 40; // px from left edge to trigger open

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, {
            passive: true
        });

        document.addEventListener('touchend', function(e) {
            if (!window.Helpers || typeof window.Helpers.isSmallScreen !== 'function') return;
            if (!window.Helpers.isSmallScreen()) return; // desktop only needs click

            var dx = e.changedTouches[0].clientX - touchStartX;
            var dy = e.changedTouches[0].clientY - touchStartY;

            // Ignore if more vertical than horizontal
            if (Math.abs(dy) > Math.abs(dx)) return;

            // Swipe RIGHT from left edge → open
            if (touchStartX <= EDGE_ZONE && dx > SWIPE_THRESHOLD) {
                window.Helpers.setCollapsed(false, true);
                return;
            }

            // Swipe LEFT while sidebar is open → close
            if (dx < -SWIPE_THRESHOLD && !window.Helpers.isCollapsed()) {
                window.Helpers.setCollapsed(true, true);
            }
        }, {
            passive: true
        });

        // 3. Close sidebar on nav-link click (mobile — navigate away)
        var menuLinks = document.querySelectorAll('#layout-menu .menu-link:not(.menu-toggle)');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.Helpers && window.Helpers.isSmallScreen && window.Helpers
                    .isSmallScreen()) {
                    window.Helpers.setCollapsed(true, true);
                }
            });
        });
    });
</script>

{{-- ─── Page-specific scripts ──────────────────────────────────────────────── --}}
@stack('scripts')

{{-- ─── Global jQuery Validate Setup ─────────────────────────────────────── --}}
<script>
    // Configure jQuery Validate defaults globally so every form benefits
    $.validator.setDefaults({
        errorElement: 'div',
        errorClass: 'invalid-feedback d-block',
        validClass: '',
        ignore: ':hidden:not([class*="select2"]):not(.flatpickr-input)',
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
            $(element).closest(
                    '.mb-3, .mb-4, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-12, .col')
                .find('.select2-container').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
            $(element).closest(
                    '.mb-3, .mb-4, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-12, .col')
                .find('.select2-container').removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
            // Select2
            var s2 = element.next('.select2-container');
            if (s2.length) {
                error.insertAfter(s2);
                return;
            }
            // Input group (flatpickr wrapper etc.)
            var ig = element.closest('.input-group, .fp-wrapper');
            if (ig.length) {
                error.insertAfter(ig);
                return;
            }
            // Radio / checkbox
            if (element.is(':radio') || element.is(':checkbox')) {
                error.insertAfter(element.closest('.form-check, #typeButtons') || element);
                return;
            }
            error.insertAfter(element);
        }
    });

    // Custom: value must not equal 0
    $.validator.addMethod('not_zero', function(value) {
        return parseFloat(value) !== 0;
    }, 'Value cannot be zero.');

    // Custom: alpha_dash — letters, numbers, dashes, underscores only
    $.validator.addMethod('alpha_dash', function(value) {
        return /^[a-zA-Z0-9_\-]+$/.test(value);
    }, 'Only letters, numbers, dashes, and underscores are allowed.');

    // Custom: minVal for numeric
    $.validator.addMethod('minVal', function(value, element, param) {
        return parseFloat(value) >= parseFloat(param);
    }, $.validator.format('Please enter a value of at least {0}.'));

    // Auto-init validation on any form with data-validate="true"
    $(document).ready(function() {
        $('form[data-validate="true"]').each(function() {
            $(this).validate();
        });
    });
</script>

{{-- ─── Global Select2 Auto-Init ─────────────────────────────────────────── --}}
<script>
    $(document).ready(function() {

        function s2Init(context) {
            var $selects = context ? $(context).find('select').addBack('select') : $('select');
            $selects
                .not('[data-no-select2]')
                .not('.dataTables_filter select')
                .not('.dataTables_length select')
                .not('.select2-hidden-accessible')
                .not('.flatpickr-monthDropdown-months')
                .not('[type="date"]')
                .each(function() {
                    var $el = $(this);
                    var $modal = $el.closest('.modal');
                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: true,
                        placeholder: $el.find('option[value=""]').first().text().trim() ||
                            'Select…',
                        dropdownParent: $modal.length ? $modal : $('body'),
                        templateResult: function(state) {
                            if (!state.id) return state.text;
                            var element = $(state.element);
                            var img = element.data('image');
                            var sku = element.data('sku');
                            if (!img && !sku) return state.text;
                            return $(
                                '<div class="d-flex align-items-center gap-2">' +
                                '<img src="' + img +
                                '" class="rounded" style="width:32px;height:32px;object-fit:cover;">' +
                                '<div class="d-flex flex-column">' +
                                '<span class="fw-semibold text-dark lh-sm" style="font-size:13px;">' +
                                (element.data('name') || state.text.split(' (')[0]) +
                                '</span>' +
                                '<span class="text-muted" style="font-size:10.5px;">SKU: ' +
                                sku + '</span>' +
                                '</div>' +
                                '</div>'
                            );
                        }
                    });
                    if ($el.hasClass('is-invalid')) {
                        $el.next('.select2-container').addClass('is-invalid');
                    }
                });
        }

        // Run after a tiny delay so all other $(document).ready() callbacks
        // (DataTables, page-specific scripts) complete first
        setTimeout(function() {
            s2Init(null);
        }, 0);

        // Re-init when a Bootstrap modal opens
        $(document).on('shown.bs.modal', function(e) {
            setTimeout(function() {
                s2Init(e.target);
            }, 0);
        });

        // Manual re-init: $(mySelect).trigger('s2:rebuild')
        $(document).on('s2:rebuild', 'select', function() {
            var $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            setTimeout(function() {
                s2Init($el.parent()[0]);
            }, 0);
        });

        // Auto-focus search box when Select2 opens
        $(document).on('select2:open', function() {
            setTimeout(function() {
                var sf = document.querySelector(
                    '.select2-container--open .select2-search__field');
                if (sf) sf.focus();
            }, 50);
        });

    });
</script>

{{-- ─── Global SweetAlert2 Dark Mode Defaults ─────────────────────────── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swal === 'undefined') return;

        function applySwalTheme() {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            // Override Swal defaults for current theme
            var defaults = isDark ? {
                background: '#2b2c40',
                color: '#cfd3ec',
                customClass: {
                    popup: 'swal2-dark-popup',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary ms-2',
                    title: 'swal2-dark-title',
                    htmlContainer: 'swal2-dark-html',
                }
            } : {
                background: '#fff',
                color: '#566a7f',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary ms-2',
                }
            };
            Swal.mixin(defaults);
            window._swalDefaults = defaults;
        }

        applySwalTheme();

        // Re-apply when theme toggle is clicked
        var toggle = document.getElementById('adminThemeToggle');
        if (toggle) {
            toggle.addEventListener('click', function() {
                setTimeout(applySwalTheme, 50);
            });
        }

        // Patch all Swal.fire calls to auto-inject theme defaults
        var _origFire = Swal.fire.bind(Swal);
        Swal.fire = function(opts) {
            var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            var base = isDark ? {
                background: '#2b2c40',
                color: '#cfd3ec',
                customClass: {
                    popup: 'swal2-dark-popup',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary ms-2',
                }
            } : {
                background: '#fff',
                color: '#566a7f',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary ms-2',
                }
            };

            if (typeof opts === 'object') {
                // Merge customClass carefully
                var merged = Object.assign({}, base, opts);
                if (opts.customClass || base.customClass) {
                    merged.customClass = Object.assign({}, base.customClass, opts.customClass || {});
                }
                return _origFire(merged);
            }
            return _origFire.apply(this, arguments);
        };
    });
</script>

<style>
    /* SweetAlert2 dark mode styles */
    .swal2-dark-popup {
        background: #2b2c40 !important;
        color: #cfd3ec !important;
        border: 1px solid rgba(255, 255, 255, .1);
    }

    .swal2-dark-popup .swal2-title {
        color: #cfd3ec !important;
    }

    .swal2-dark-popup .swal2-html-container {
        color: #a3adc2 !important;
    }

    .swal2-dark-popup .swal2-icon.swal2-warning {
        border-color: #ffab00 !important;
        color: #ffab00 !important;
    }

    .swal2-dark-popup .swal2-icon.swal2-success {
        border-color: #71dd37 !important;
        color: #71dd37 !important;
    }

    .swal2-dark-popup .swal2-icon.swal2-error {
        border-color: #ff3e1d !important;
        color: #ff3e1d !important;
    }

    .swal2-dark-popup .swal2-actions .btn {
        min-width: 100px;
    }
</style>

{{-- ─── Session flash toasts ──────────────────────────────────────────────── --}}
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ addslashes(session('success')) }}', 'success');
        });
    </script>
@endif
@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ addslashes(session('error')) }}', 'error');
        });
    </script>
@endif
@if (session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ addslashes(session('warning')) }}', 'warning');
        });
    </script>
@endif

{{-- ─── Session Timeout Warning (client-side countdown) ──────────────────── --}}
@auth
    @php
        $sessionTimeoutMinutes = (int) \App\Models\Setting::where('key', 'session_timeout')->value('value');
        $viaRemember = \Illuminate\Support\Facades\Auth::viaRemember();
    @endphp
    @if ($sessionTimeoutMinutes > 0 && !$viaRemember)
        <script>
            (function() {
                var timeoutMs = {{ $sessionTimeoutMinutes * 60 * 1000 }};
                var warnBeforeMs = 60 * 1000; // warn 60 seconds before expiry
                var lastActivity = Date.now();
                var warnTimer, logoutTimer, warningShown = false;

                // Track activity
                ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(function(evt) {
                    document.addEventListener(evt, function() {
                        lastActivity = Date.now();
                        if (warningShown) {
                            warningShown = false;
                            var banner = document.getElementById('sessionTimeoutBanner');
                            if (banner) banner.remove();
                        }
                        resetTimers();
                    }, {
                        passive: true
                    });
                });

                function resetTimers() {
                    clearTimeout(warnTimer);
                    clearTimeout(logoutTimer);

                    warnTimer = setTimeout(function() {
                        showWarning();
                    }, timeoutMs - warnBeforeMs);

                    logoutTimer = setTimeout(function() {
                        // Force logout via server
                        window.location.href = '{{ route('logout.session-expired') }}';
                    }, timeoutMs);
                }

                function showWarning() {
                    warningShown = true;
                    // Remove old banner if any
                    var old = document.getElementById('sessionTimeoutBanner');
                    if (old) old.remove();

                    var banner = document.createElement('div');
                    banner.id = 'sessionTimeoutBanner';
                    banner.style.cssText = [
                        'position:fixed', 'bottom:20px', 'left:50%', 'transform:translateX(-50%)',
                        'z-index:99999', 'background:linear-gradient(135deg,#696cff,#9c3fe4)',
                        'color:#fff', 'padding:14px 24px', 'border-radius:12px',
                        'box-shadow:0 8px 24px rgba(0,0,0,.25)',
                        'display:flex', 'align-items:center', 'gap:12px',
                        'font-size:.9rem', 'font-weight:500', 'max-width:420px', 'width:90%'
                    ].join(';');

                    banner.innerHTML = [
                        '<i class="bx bx-time-five" style="font-size:1.4rem;flex-shrink:0;"></i>',
                        '<div style="flex:1;">',
                        '{{ __('messages.session_expiring_in') }} <strong id="sessionCountdown">60</strong> {{ __('messages.session_seconds_suffix') }} ',
                        '<a href="javascript:void(0)" onclick="location.reload()" ',
                        'style="color:#fff;text-decoration:underline;">{{ __('messages.stay_logged_in') }}</a>',
                        '</div>',
                        '<button onclick="document.getElementById(\'sessionTimeoutBanner\').remove()" ',
                        'style="background:rgba(255,255,255,.2);border:none;color:#fff;',
                        'border-radius:50%;width:24px;height:24px;cursor:pointer;',
                        'display:flex;align-items:center;justify-content:center;flex-shrink:0;">',
                        '&times;',
                        '</button>'
                    ].join('');

                    document.body.appendChild(banner);

                    // Countdown
                    var secs = 60;
                    var interval = setInterval(function() {
                        secs--;
                        var el = document.getElementById('sessionCountdown');
                        if (el) el.textContent = secs;
                        if (secs <= 0) clearInterval(interval);
                    }, 1000);
                }

                resetTimers();
            })
            ();
        </script>
    @endif
@endauth
