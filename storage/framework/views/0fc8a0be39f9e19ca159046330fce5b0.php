<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    
    <div class="app-brand demo">
        <a href="<?php echo e(route('dashboard')); ?>" class="app-brand-link">
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
        
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="bx bx-x align-middle" style="font-size:1.4rem;"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        
        <li class="menu-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('dashboard')); ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div><?php echo e(__('messages.menu_dashboard')); ?></div>
            </a>
        </li>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['main_categories.view', 'sub_categories.view', 'brands.view', 'products.view'])): ?>
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text"><?php echo e(__('messages.section_inventory')); ?></span>
            </li>
        <?php endif; ?>

        
        <?php
            $catActive = request()->routeIs('main-categories.*', 'sub-categories.*');
            $showCatMenu = auth()->user()->can('main_categories.view') || auth()->user()->can('sub_categories.view');
        ?>
        <?php if($showCatMenu): ?>
            <li class="menu-item <?php echo e($catActive ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div><?php echo e(__('messages.menu_categories')); ?></div>
                </a>
                <ul class="menu-sub">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('main_categories.view')): ?>
                        <li class="menu-item <?php echo e(request()->routeIs('main-categories.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('main-categories.index')); ?>" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-category" style="font-size:0.9rem;"></i>
                                <div><?php echo e(__('messages.menu_categories')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub_categories.view')): ?>
                        <li class="menu-item <?php echo e(request()->routeIs('sub-categories.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('sub-categories.index')); ?>" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-sitemap" style="font-size:0.9rem;"></i>
                                <div><?php echo e(__('messages.menu_sub_categories')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('brands.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('brands.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-award"></i>
                    <div><?php echo e(__('messages.menu_brands')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
            <?php $productsActive = request()->routeIs('products.*', 'stocks.*'); ?>
            <li class="menu-item <?php echo e($productsActive ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div><?php echo e(__('messages.menu_products')); ?></div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?php echo e(request()->routeIs('products.index') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('products.index')); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-ul" style="font-size:0.9rem;"></i>
                            <div><?php echo e(__('messages.list_view')); ?></div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo e(request()->routeIs('products.gallery') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('products.gallery')); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-grid-alt" style="font-size:0.9rem;"></i>
                            <div><?php echo e(__('messages.gallery_view')); ?></div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo e(request()->routeIs('products.by-category') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('products.by-category')); ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-category-alt" style="font-size:0.9rem;"></i>
                            <div><?php echo e(__('messages.prod_by_category')); ?></div>
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.view')): ?>
                        <li class="menu-item <?php echo e(request()->routeIs('stocks.*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('stocks.history')); ?>" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-slider" style="font-size:0.9rem;"></i>
                                <div><?php echo e(__('messages.stock_adjustments')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['purchases.view', 'purchase_returns.view'])): ?>
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text"><?php echo e(__('messages.menu_purchases')); ?></span>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchases.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('purchases.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('purchases.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-download"></i>
                    <div>
                        <?php echo e(__('messages.menu_purchases')); ?>

                        <span class="sidebar-sub-label">(<?php echo e(__('messages.stock_in')); ?>)</span>
                    </div>
                    <?php if(($sidebarStockIn ?? 0) > 0): ?>
                        <span
                            class="badge bg-success rounded-pill ms-auto sidebar-stock-badge">+<?php echo e($sidebarStockIn); ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_returns.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('purchase-returns.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('purchase-returns.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-revision"></i>
                    <div><?php echo e(__('messages.menu_purchase_returns')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['sales.view', 'sale_returns.view'])): ?>
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text"><?php echo e(__('messages.menu_sales')); ?></span>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('sales.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('sales.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                    <div>
                        <?php echo e(__('messages.menu_sales')); ?>

                        <span class="sidebar-sub-label">(<?php echo e(__('messages.stock_out')); ?>)</span>
                    </div>
                    <?php if(($sidebarStockOut ?? 0) > 0): ?>
                        <span
                            class="badge bg-danger rounded-pill ms-auto sidebar-stock-badge">-<?php echo e($sidebarStockOut); ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('sale-returns.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('sale-returns.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-transfer"></i>
                    <div><?php echo e(__('messages.menu_sale_returns')); ?></div>
                </a>
            </li>
        <?php endif; ?>



        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['suppliers.view', 'customers.view', 'users.view'])): ?>
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text"><?php echo e(__('messages.menu_suppliers')); ?> &amp;
                    <?php echo e(__('messages.menu_customers')); ?></span>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('suppliers.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('suppliers.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('suppliers.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div><?php echo e(__('messages.menu_suppliers')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('customers.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('customers.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div><?php echo e(__('messages.menu_customers')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        <li class="menu-item <?php echo e(request()->routeIs('whatsapp.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('whatsapp.index')); ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bxl-whatsapp" style="color:#25d366;"></i>
                <div>WhatsApp Broadcast</div>
            </a>
        </li>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('users.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div><?php echo e(__('messages.user_accounts')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reports.view')): ?>
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text"><?php echo e(__('messages.section_reports')); ?></span>
            </li>
            <li class="menu-item <?php echo e(request()->routeIs('reports.index') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('reports.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                    <div><?php echo e(__('messages.all_reports')); ?></div>
                </a>
            </li>
            <li class="menu-item <?php echo e(request()->routeIs('reports.sales') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('reports.sales')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                    <div><?php echo e(__('messages.sales_report')); ?></div>
                </a>
            </li>
            <li class="menu-item <?php echo e(request()->routeIs('reports.purchases') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('reports.purchases')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-download"></i>
                    <div><?php echo e(__('messages.purchase_report')); ?></div>
                </a>
            </li>
            <li class="menu-item <?php echo e(request()->routeIs('reports.profit-loss') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('reports.profit-loss')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trending-up"></i>
                    <div><?php echo e(__('messages.profit_loss')); ?></div>
                </a>
            </li>
            <li class="menu-item <?php echo e(request()->routeIs('reports.top-selling') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('reports.top-selling')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-trophy"></i>
                    <div><?php echo e(__('messages.top_selling')); ?></div>
                </a>
            </li>
            <li class="menu-item <?php echo e(request()->routeIs('reports.stock-alert') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('reports.stock-alert')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-error"></i>
                    <div><?php echo e(__('messages.stock_alert_menu')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['roles.view', 'activity_logs.view', 'settings.view', 'currencies.view'])): ?>
            <li class="menu-header small text-uppercase mt-1">
                <span class="menu-header-text"><?php echo e(__('messages.section_management')); ?></span>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('roles.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('roles.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shield"></i>
                    <div><?php echo e(__('messages.menu_roles')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('permissions.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('permissions.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-key"></i>
                    <div><?php echo e(__('messages.menu_permissions')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activity_logs.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('activity-logs.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('activity-logs.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div><?php echo e(__('messages.activity_logs')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('currencies.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('currencies.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div><?php echo e(__('messages.menu_currencies')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings.view')): ?>
            <li class="menu-item <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('settings.index')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div><?php echo e(__('messages.menu_settings')); ?></div>
                </a>
            </li>
        <?php endif; ?>

        
        <li class="menu-header small text-uppercase mt-1">
            <span class="menu-header-text"><?php echo e(__('messages.profile')); ?></span>
        </li>

        <li
            class="menu-item nav-item dropdown sidebar-user-dropdown <?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>">
            <a href="javascript:void(0);"
                class="menu-link d-flex align-items-center gap-2 px-3 py-2 sidebar-profile-trigger"
                data-bs-toggle="dropdown" aria-expanded="false">
                <?php if(Auth::user()->profile_photo): ?>
                    <img src="<?php echo e(asset('uploads/profiles/' . Auth::user()->profile_photo)); ?>"
                        class="rounded-circle flex-shrink-0 sidebar-avatar" alt="<?php echo e(Auth::user()->name); ?>"
                        style="width:32px;height:32px;object-fit:cover;">
                <?php else: ?>
                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center sidebar-avatar-placeholder"
                        style="width:32px;height:32px;">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate sidebar-user-name"
                        style="font-size:.87rem;max-width:120px;">
                        <?php echo e(Auth::user()->name); ?>

                    </div>
                    <div class="text-truncate sidebar-user-role" style="font-size:.73rem;max-width:120px;">
                        <?php echo e(Auth::user()->getRoleNames()->first() ?? 'User'); ?>

                    </div>
                </div>
                <i class="bx bx-chevron-right sidebar-user-caret ms-auto flex-shrink-0" style="font-size:1rem;"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-end sidebar-profile-dropdown">
                <li>
                    <div class="px-3 py-2 border-bottom sidebar-profile-header">
                        <div class="fw-semibold" style="font-size:.88rem;"><?php echo e(Auth::user()->name); ?></div>
                        <div class="text-muted" style="font-size:.75rem;"><?php echo e(Auth::user()->email); ?></div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="<?php echo e(route('profile.show')); ?>">
                        <i class="bx bx-user"></i> <?php echo e(__('messages.profile')); ?>

                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="<?php echo e(route('profile.show')); ?>#change-password">
                        <i class="bx bx-lock-alt"></i> <?php echo e(__('messages.change_password')); ?>

                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider my-1">
                </li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" id="sidebar-profile-logout">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                            <i class="bx bx-power-off"></i> <?php echo e(__('messages.logout')); ?>

                        </button>
                    </form>
                </li>
            </ul>
        </li>

    </ul>

    <form id="sidebar-logout-form" method="POST" action="<?php echo e(route('logout')); ?>" style="display:none;"><?php echo csrf_field(); ?>
    </form>

</aside>

<?php $__env->startPush('styles'); ?>
    <style>
        /* ════════════════════════════════════════════
                                               SIDEBAR — Sneat overrides
                                               ════════════════════════════════════════════ */

        /* Section headers — no icon, clean text only */
        .menu-header-text {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            opacity: 0.55;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        /* "(Stock In) / (Stock Out)" sub-label under menu item text */
        .sidebar-sub-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 400;
            opacity: 0.55;
            line-height: 1.2;
            margin-top: 1px;
        }

        /* Today's qty badge next to Purchases / Sales */
        .sidebar-stock-badge {
            flex-shrink: 0;
            font-size: 0.62rem !important;
            padding: 0.18em 0.5em;
            font-weight: 700;
            line-height: 1.5;
        }

        /* ── Sub-menu: show icons using menu-icon class ─────────────────────── */

        /* Sneat hides .menu-icon inside .menu-sub by default — override it */
        .menu-vertical .menu-sub .menu-item .menu-link .menu-icon {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 1.25rem !important;
            flex-shrink: 0;
            margin-right: 0.5rem;
            opacity: 0.7;
            font-size: 0.9rem !important;
        }

        /* Remove the default bullet ::before on sub-items */
        .menu-vertical .menu-sub .menu-item .menu-link::before {
            display: none !important;
            content: none !important;
        }

        /* Reduce sub-item left padding so icon aligns nicely */
        .menu-vertical .menu-sub .menu-item .menu-link {
            padding-left: 1.25rem !important;
        }

        /* Active sub-item icon: full opacity + primary colour */
        .menu-vertical .menu-sub .menu-item.active>.menu-link .menu-icon,
        .menu-vertical .menu-sub .menu-item.active>.menu-link:hover .menu-icon {
            opacity: 1;
            color: #696cff;
        }

        /* Hover */
        .menu-vertical .menu-sub .menu-item>.menu-link:hover .menu-icon {
            opacity: 0.9;
        }

        /* Dark mode */
        [data-bs-theme="dark"] .menu-vertical .menu-sub .menu-item.active>.menu-link .menu-icon {
            color: #696cff;
        }

        /* ── Profile trigger ────────────────────────────────────────────────── */
        .sidebar-profile-trigger {
            border-radius: 8px !important;
            transition: background .15s;
            cursor: pointer;
        }

        .sidebar-profile-trigger:hover {
            background: rgba(105, 108, 255, .09) !important;
        }

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

        .sidebar-user-name {
            color: inherit;
            line-height: 1.2;
        }

        .sidebar-user-role {
            opacity: .6;
            line-height: 1.2;
        }

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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>