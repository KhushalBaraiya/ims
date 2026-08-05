<?php
    $currentLocale = app()->getLocale();
    $currentLang = $supportedLanguages[$currentLocale] ?? $supportedLanguages['en'];
    $activeCurrency = current_currency();
?>

<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-xl-0 d-xl-none me-3">
        <a class="nav-item nav-link px-2 d-flex align-items-center justify-content-center menu-toggle-btn"
            href="javascript:void(0)" style="width:38px;height:38px;border-radius:8px;">
            <i class="bx bx-menu" style="font-size:1.4rem;line-height:1;"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end w-100" id="navbar-collapse">
        <ul class="navbar-nav align-items-center ms-auto flex-row gap-1">

            
            <li class="nav-item d-none d-lg-flex align-items-center me-2">
                <div class="d-flex flex-column align-items-end" style="line-height:1.2;">
                    <span class="fw-bold" id="navClock-time"
                        style="font-size:.93rem;font-variant-numeric:tabular-nums;letter-spacing:.02em;"></span>
                    <span class="text-muted" id="navClock-date" style="font-size:.71rem;"></span>
                </div>
            </li>

            
            <li class="nav-item dropdown me-1">
                
                <a aria-expanded="false" class="nav-link d-flex align-items-center cs-trigger gap-1 px-2"
                    data-bs-toggle="dropdown" href="javascript:void(0);" style="height:36px;"
                    title="<?php echo e(__('messages.currency') ?? 'Currency'); ?>">
                    <i class="bx bx-dollar-circle" style="font-size:1.2rem;"></i>
                    <span class="fw-bold" style="font-size:.85rem;letter-spacing:.04em;">
                        <?php echo e($activeCurrency ? $activeCurrency->code : 'INR'); ?>

                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end cs-dropdown-menu" id="currencyDropdown">
                    <?php $__empty_1 = true; $__currentLoopData = $activeCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $isActive = $activeCurrency && $activeCurrency->id === $curr->id; ?>
                        <li>
                            <a class="dropdown-item d-flex align-items-center cs-currency-item <?php echo e($isActive ? 'active' : ''); ?> gap-2 px-3 py-2"
                                data-currency-code="<?php echo e($curr->code); ?>" data-currency-id="<?php echo e($curr->id); ?>"
                                data-currency-name="<?php echo e($curr->name); ?>" data-currency-symbol="<?php echo e($curr->symbol); ?>"
                                href="javascript:void(0);">
                                
                                <span class="cs-curr-symbol"><?php echo e($curr->symbol); ?></span>
                                
                                <span class="cs-curr-name"><?php echo e($curr->name); ?></span>
                                <span class="cs-curr-code ms-auto"><?php echo e($curr->code); ?></span>
                                <?php if($isActive): ?>
                                    <i class="bx bx-check" style="font-size:1rem;color:#696cff;"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li>
                            <span
                                class="dropdown-item text-muted small py-2"><?php echo e(__('messages.no_currencies_available')); ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>

            
            <li class="nav-item dropdown me-1">
                <a aria-expanded="false" class="nav-link d-flex align-items-center ls-trigger gap-1 px-2"
                    data-bs-toggle="dropdown" href="javascript:void(0);" style="height:36px;" title="Language">
                    <i class="bx bx-globe" style="font-size:1.2rem;"></i>
                    <span class="fw-bold" style="font-size:.85rem;letter-spacing:.04em;">
                        <?php echo e(strtoupper($currentLang['code'] ?? app()->getLocale())); ?>

                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end ls-dropdown-menu">
                    <?php $__currentLoopData = \App\Http\Controllers\LanguageController::SUPPORTED; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $isActiveLang = app()->getLocale() === $locale; ?>
                        <li>
                            <a class="dropdown-item d-flex align-items-center ls-lang-item <?php echo e($isActiveLang ? 'active' : ''); ?> px-3 py-2"
                                href="<?php echo e(route('language.switch', $locale)); ?>">
                                <span class="ls-lang-name"><?php echo e($lang['label']); ?></span>
                                <span class="ms-auto ls-lang-code text-muted"
                                    style="font-size:.72rem;font-weight:700;letter-spacing:.04em;"><?php echo e($lang['code']); ?></span>
                                <?php if($isActiveLang): ?>
                                    <i class="bx bx-check ms-1" style="font-size:1rem;color:#696cff;"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </li>

            
            <li class="nav-item me-1">
                <a class="nav-link d-flex align-items-center justify-content-center theme-toggle-btn px-2"
                    href="javascript:void(0);" id="adminThemeToggle" style="height:36px;width:36px;border-radius:8px;"
                    title="<?php echo e(__('messages.dark_mode')); ?>">
                    <i class="bx bx-moon" style="font-size:1.2rem;"></i>
                </a>
            </li>

            
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" href="javascript:void(0);">
                    <div class="avatar avatar-online">
                        <?php if(Auth::user()->profile_photo): ?>
                            <img alt="avatar" class="rounded-circle"
                                src="<?php echo e(asset('uploads/profiles/' . Auth::user()->profile_photo)); ?>"
                                style="object-fit:cover;width:40px;height:40px;" />
                        <?php else: ?>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px;height:40px;background:#696cff;">
                                <span class="fw-bold text-white" style="font-size:.95rem;">
                                    <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu"
                    style="min-width:260px;border-radius:16px;padding:0;overflow:hidden;">

                    
                    <li>
                        <div class="text-center px-4 pt-4 pb-3" style="border-bottom:1px solid rgba(0,0,0,.07);">
                            <?php if(Auth::user()->profile_photo): ?>
                                <img class="rounded-circle d-block mx-auto mb-3"
                                    src="<?php echo e(asset('uploads/profiles/' . Auth::user()->profile_photo)); ?>"
                                    style="object-fit:cover;width:72px;height:72px;" />
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width:72px;height:72px;background:#696cff;">
                                    <span class="fw-bold text-white" style="font-size:1.5rem;">
                                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="fw-bold" style="font-size:1.05rem;"><?php echo e(Auth::user()->name); ?></div>
                            <div class="text-muted mt-1" style="font-size:.8rem;"><?php echo e(Auth::user()->email); ?></div>
                        </div>
                    </li>

                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-3 px-4 py-2 user-dd-item"
                            href="<?php echo e(route('profile.show')); ?>">
                            <i class="bx bx-user user-dd-icon"></i>
                            <span><?php echo e(__('messages.profile')); ?></span>
                        </a>
                    </li>

                    
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-3 px-4 py-2 user-dd-item"
                            href="<?php echo e(route('profile.change-password')); ?>">
                            <i class="bx bx-lock user-dd-icon"></i>
                            <span><?php echo e(__('messages.change_password')); ?></span>
                        </a>
                    </li>

                    
                    <li>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0">
                            <?php echo csrf_field(); ?>
                            <button class="dropdown-item d-flex align-items-center gap-3 px-4 py-2 user-dd-item"
                                type="submit">
                                <i class="bx bx-log-out user-dd-icon"></i>
                                <span><?php echo e(__('messages.logout')); ?></span>
                            </button>
                        </form>
                    </li>
                    <li class="pb-2"></li>

                </ul>
            </li>

        </ul>
    </div>
</nav>

<?php $__env->startPush('styles'); ?>
    <style>
        /* ── Currency switcher trigger ── */
        .cs-trigger {
            border-radius: 8px;
            transition: background .15s;
        }

        .cs-trigger:hover,
        .cs-trigger[aria-expanded="true"] {
            background: rgba(105, 108, 255, .09) !important;
        }

        /* ── Currency dropdown ── */
        .cs-dropdown-menu {
            min-width: 200px !important;
            border-radius: 10px !important;
            border: 1px solid rgba(0, 0, 0, .08) !important;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .12) !important;
            overflow: hidden;
            padding: 4px 0 !important;
            animation: csDrop .13s ease;
        }

        [data-bs-theme="dark"] .cs-dropdown-menu {
            border-color: rgba(255, 255, 255, .1) !important;
        }

        @keyframes csDrop {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cs-currency-item {
            min-height: 42px;
            gap: 8px !important;
            cursor: pointer;
        }

        .cs-currency-item:hover {
            background: rgba(105, 108, 255, .07) !important;
        }

        .cs-currency-item.active {
            background: rgba(105, 108, 255, .12) !important;
            color: #696cff !important;
        }

        .cs-curr-symbol {
            font-size: .8rem;
            font-weight: 800;
            color: #8592a3;
            width: 22px;
            flex-shrink: 0;
            text-align: center;
        }

        .cs-currency-item.active .cs-curr-symbol {
            color: #696cff;
        }

        .cs-curr-name {
            font-size: .9rem;
            font-weight: 500;
            color: #566a7f;
            flex: 1;
        }

        .cs-currency-item.active .cs-curr-name {
            color: #696cff;
            font-weight: 600;
        }

        [data-bs-theme="dark"] .cs-curr-name {
            color: #cfd8e3;
        }

        .cs-curr-code {
            font-size: .72rem;
            font-weight: 700;
            color: #a1b0c0;
            letter-spacing: .04em;
        }

        .cs-currency-item.active .cs-curr-code {
            color: #696cff;
        }

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

        /* ── User Dropdown ── */
        .user-dropdown-menu {
            border: 1px solid rgba(0, 0, 0, .07) !important;
            box-shadow: 0 10px 32px rgba(100, 116, 139, .15) !important;
            animation: userDDrop .15s ease;
        }

        [data-bs-theme="dark"] .user-dropdown-menu {
            border-color: rgba(255, 255, 255, .1) !important;
            box-shadow: 0 10px 32px rgba(0, 0, 0, .35) !important;
        }

        @keyframes userDDrop {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-dd-item {
            font-size: .95rem;
            font-weight: 500;
            color: #566a7f;
            transition: background .12s;
        }

        .user-dd-item:hover {
            background: rgba(105, 108, 255, .07) !important;
            color: #566a7f !important;
        }

        .user-dd-icon {
            font-size: 1.2rem;
            color: #8592a3;
            width: 20px;
            flex-shrink: 0;
            text-align: center;
        }

        [data-bs-theme="dark"] .user-dd-item {
            color: #cfd8e3;
        }

        [data-bs-theme="dark"] .user-dd-item:hover {
            color: #cfd8e3 !important;
        }

        [data-bs-theme="dark"] .user-dd-icon {
            color: #7983bb;
        }

        [data-bs-theme="dark"] .user-dropdown-menu .text-center {
            border-bottom-color: rgba(255, 255, 255, .1) !important;
        }

        /* ── Language Dropdown menu ── */
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // ── Live Clock ──────────────────────────────────────────────────────
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

        // ── Currency Switcher ────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const csItems = document.querySelectorAll('.cs-currency-item');
            const csTrigger = document.querySelector('.cs-trigger');

            csItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    const currencyId = this.dataset.currencyId;
                    const currencyCode = this.dataset.currencyCode;
                    const currencyName = this.dataset.currencyName;

                    // AJAX call to switch currency
                    fetch('<?php echo e(route('currencies.switch')); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]') ?
                                    document.querySelector('meta[name="csrf-token"]').content :
                                    '<?php echo e(csrf_token()); ?>'
                            },
                            body: JSON.stringify({
                                currency_id: currencyId
                            })
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                // Update trigger label
                                if (csTrigger) {
                                    const codeSpan = csTrigger.querySelector('span');
                                    if (codeSpan) codeSpan.textContent = currencyCode;
                                }

                                // Update active states in dropdown
                                csItems.forEach(function(el) {
                                    el.classList.remove('active');
                                    // Remove check icon
                                    const check = el.querySelector('.bx-check');
                                    if (check) check.remove();
                                });

                                this.classList.add('active');
                                // Add check icon
                                const icon = document.createElement('i');
                                icon.className = 'bx bx-check';
                                icon.style.fontSize = '1rem';
                                icon.style.color = '#696cff';
                                this.appendChild(icon);

                                // Show toast if available
                                if (typeof showAdminToast === 'function') {
                                    showAdminToast(
                                        '<?php echo e(__('messages.currency_changed_to')); ?> ' +
                                        currencyName,
                                        'success');
                                }

                                // Reload page to recalculate all amounts in new currency
                                setTimeout(function() {
                                    window.location.reload();
                                }, 600);
                            }
                        }.bind(this))
                        .catch(function() {
                            if (typeof showAdminToast === 'function') {
                                showAdminToast('<?php echo e(__('messages.currency_switch_failed')); ?>',
                                    'error');
                            }
                        });
                });
            });

            // ── "Change Language" in user dropdown → trigger navbar lang dropdown ──
            var langTrigger = document.getElementById('userDDLangTrigger');
            if (langTrigger) {
                langTrigger.addEventListener('click', function() {
                    // Close user dropdown first
                    var userDDToggle = document.querySelector('.navbar-dropdown .dropdown-toggle');
                    if (userDDToggle) {
                        var bsDD = bootstrap.Dropdown.getInstance(userDDToggle);
                        if (bsDD) bsDD.hide();
                    }
                    // Open language dropdown
                    setTimeout(function() {
                        var lsTriggerEl = document.querySelector('.ls-trigger');
                        if (lsTriggerEl) {
                            var bsLang = bootstrap.Dropdown.getOrCreateInstance(lsTriggerEl);
                            bsLang.toggle();
                        }
                    }, 150);
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/partials/navbar.blade.php ENDPATH**/ ?>