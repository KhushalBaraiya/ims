
<?php $__env->startSection('title', __('messages.dashboard')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* ── Welcome Banner ─────────────────────────── */
        .dash-banner {
            background: linear-gradient(135deg, #696cff 0%, #9155fd 55%, #c053d8 100%);
            border-radius: 14px;
            overflow: hidden;
            position: relative;
        }

        .dash-banner::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
            pointer-events: none;
        }

        .dash-banner::after {
            content: '';
            position: absolute;
            bottom: -70px;
            right: 120px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        /* ── KPI Cards ─────────────────────────────── */
        .kpi-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            transition: transform .2s, box-shadow .2s;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .13);
        }

        .kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .kpi-accent {
            height: 3px;
            border-radius: 12px 12px 0 0;
        }

        /* ── Section Header ─────────────────────────── */
        .sec-hd {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #8592a3;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: .85rem;
        }

        .sec-hd::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(0, 0, 0, .06);
        }

        [data-bs-theme="dark"] .sec-hd::after {
            background: rgba(255, 255, 255, .08);
        }

        /* ── Inner card ─────────────────────────────── */
        .inner-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        /* ── Table ──────────────────────────────────── */
        .d-tbl thead th {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #8592a3;
            padding: .55rem .85rem;
            border-bottom: 1px solid #eef0f8;
            background: #f8f9ff;
        }

        [data-bs-theme="dark"] .d-tbl thead th {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .08);
        }

        .d-tbl tbody td {
            padding: .55rem .85rem;
            font-size: .82rem;
            vertical-align: middle;
        }

        .d-tbl tbody tr:hover td {
            background: rgba(105, 108, 255, .025);
        }

        /* ── Status pills ───────────────────────────── */
        .s-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .7rem;
            font-weight: 700;
            padding: .2rem .55rem;
            border-radius: 99px;
        }

        /* ── Activity feed ──────────────────────────── */
        .act-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #696cff;
            flex-shrink: 0;
            margin-top: 5px;
        }

        /* ── Clock ──────────────────────────────────── */
        #dashClock {
            font-variant-numeric: tabular-nums;
        }

        #dashClock-time {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.1;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $sym = optional(current_currency())->symbol ?? '₹';
        $user = auth()->user();
    ?>

    
    <div class="dash-banner card border-0 mb-4">
        <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
            style="position:relative;z-index:1;">
            <div>
                <h4 class="fw-bold text-white mb-1">
                    👋 <?php echo e(__('messages.welcome_back')); ?>, <?php echo e($user->name); ?>!
                </h4>
                <p class="text-white mb-3" style="opacity:.82;font-size:.88rem;">
                    <?php echo e(now()->format('l, d F Y')); ?> &nbsp;·&nbsp; <?php echo e(__('messages.store_overview')); ?>

                </p>
                <div class="d-flex flex-wrap gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchases.create')): ?>
                        <a href="<?php echo e(route('purchases.create')); ?>" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bx bx-cart-download me-1"></i> <?php echo e(__('messages.add_purchase')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.create')): ?>
                        <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-sm fw-semibold"
                            style="background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.3);">
                            <i class="bx bx-receipt me-1"></i> <?php echo e(__('messages.add_sale')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.create')): ?>
                        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-sm fw-semibold"
                            style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);">
                            <i class="bx bx-package me-1"></i> <?php echo e(__('messages.add_products')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
                        <a href="<?php echo e(route('stocks.adjust')); ?>" class="btn btn-sm fw-semibold"
                            style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.15);">
                            <i class="bx bx-slider me-1"></i> <?php echo e(__('messages.dash_adjust_stock_title')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div id="dashClock" class="text-white text-center d-none d-sm-block" style="min-width:150px;">
                <div id="dashClock-time">--:--:--</div>
                <div id="dashClock-ampm" style="font-size:.8rem;opacity:.75;letter-spacing:.1em;font-weight:600;">--</div>
                <div id="dashClock-date" style="font-size:.77rem;opacity:.82;margin-top:4px;font-weight:500;">-- -- ----
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.view')): ?>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent bg-success w-100"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;"><?php echo e(__('messages.kpi_total_revenue')); ?></span>
                            <div class="kpi-icon bg-label-success"><i class="bx bx-trending-up text-success fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-success" style="font-size:1.1rem;"><?php echo e(format_currency($totalRevenue ?? 0)); ?>

                        </div>
                        <small class="text-muted"><?php echo e($completedSalesCount ?? 0); ?> <?php echo e(__('messages.kpi_completed')); ?></small>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.view')): ?>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent w-100" style="background:linear-gradient(90deg,#71dd37,#29c770);"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;"><?php echo e(__('messages.kpi_today_sales')); ?></span>
                            <div class="kpi-icon bg-label-success"><i class="bx bx-dollar-circle text-success fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-success" style="font-size:1.1rem;"><?php echo e(format_currency($todaySales ?? 0)); ?>

                        </div>
                        <small class="text-muted"><?php echo e($todaySalesCount ?? 0); ?> <?php echo e(__('messages.kpi_today_sales')); ?></small>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchases.view')): ?>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent bg-info w-100"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;"><?php echo e(__('messages.kpi_purchases')); ?></span>
                            <div class="kpi-icon bg-label-info"><i class="bx bx-cart-download text-info fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-info" style="font-size:1.1rem;">
                            <?php echo e(format_currency($totalPurchaseValue ?? 0)); ?></div>
                        <small class="text-muted"><?php echo e($totalPurchases ?? 0); ?> <?php echo e(__('messages.kpi_orders')); ?></small>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card card h-100">
                <div class="kpi-accent bg-warning w-100"></div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"
                            style="font-size:.73rem;font-weight:600;"><?php echo e(__('messages.kpi_inventory')); ?></span>
                        <div class="kpi-icon bg-label-warning"><i class="bx bx-store text-warning fs-5"></i></div>
                    </div>
                    <div class="fw-bold text-warning" style="font-size:1.1rem;"><?php echo e(format_currency($inventoryValue ?? 0)); ?>

                    </div>
                    <small class="text-muted"><?php echo e($totalProducts ?? 0); ?> <?php echo e(__('messages.kpi_products_label')); ?></small>
                </div>
            </div>
        </div>

        
        <div class="col-6 col-md-4 col-xl-2">
            <div class="kpi-card card h-100">
                <div class="kpi-accent bg-primary w-100"></div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"
                            style="font-size:.73rem;font-weight:600;"><?php echo e(__('messages.kpi_customers_label')); ?></span>
                        <div class="kpi-icon bg-label-primary"><i class="bx bx-user-circle text-primary fs-5"></i></div>
                    </div>
                    <div class="fw-bold text-primary" style="font-size:1.1rem;"><?php echo e($totalCustomers ?? 0); ?></div>
                    <small class="text-muted"><?php echo e($totalSuppliers ?? 0); ?> <?php echo e(__('messages.kpi_suppliers_label')); ?></small>
                </div>
            </div>
        </div>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.view')): ?>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card card h-100">
                    <div class="kpi-accent bg-danger w-100"></div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"
                                style="font-size:.73rem;font-weight:600;"><?php echo e(__('messages.kpi_pending')); ?></span>
                            <div class="kpi-icon bg-label-danger"><i class="bx bx-time-five text-danger fs-5"></i></div>
                        </div>
                        <div class="fw-bold text-danger" style="font-size:1.1rem;"><?php echo e($pendingSales ?? 0); ?></div>
                        <small class="text-muted"><?php echo e(__('messages.kpi_draft_invoices')); ?></small>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-primary"><i class="bx bx-package text-primary fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-primary" style="font-size:1.3rem;line-height:1;">
                            <?php echo e($totalProducts ?? 0); ?></div>
                        <div class="text-muted small"><?php echo e(__('messages.qs_products')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-warning"><i class="bx bx-award text-warning fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-warning" style="font-size:1.3rem;line-height:1;"><?php echo e($totalBrands ?? 0); ?>

                        </div>
                        <div class="text-muted small"><?php echo e(__('messages.qs_brands')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-info"><i class="bx bx-category text-info fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-info" style="font-size:1.3rem;line-height:1;">
                            <?php echo e($totalCategories ?? 0); ?></div>
                        <div class="text-muted small"><?php echo e(__('messages.qs_categories')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="kpi-icon bg-label-secondary"><i class="bx bx-group text-secondary fs-5"></i></div>
                    <div>
                        <div class="fw-bold text-secondary" style="font-size:1.3rem;line-height:1;">
                            <?php echo e($totalUsers ?? 0); ?></div>
                        <div class="text-muted small"><?php echo e(__('messages.qs_users')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($user->can('sales.view') || $user->can('purchases.view')): ?>
        <div class="sec-hd"><i class="bx bx-bar-chart-alt-2"></i> <?php echo e(__('messages.dash_analytics')); ?></div>
        <div class="row g-3 mb-4">

            
            <div class="col-lg-8 col-md-12">
                <div class="inner-card card h-100">
                    <div
                        class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-bar-chart-alt-2 text-primary me-2"></i>
                            <?php echo e(__('messages.dash_week_chart_title')); ?>

                        </h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-label-primary" style="font-size:.65rem;">●
                                <?php echo e(__('messages.dash_sales_legend')); ?></span>
                            <span class="badge bg-label-info" style="font-size:.65rem;">●
                                <?php echo e(__('messages.dash_purchases_legend')); ?></span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <canvas id="weekChart" style="height:230px;max-height:230px;"></canvas>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-4 col-md-12">
                <div class="inner-card card h-100">
                    <div class="card-header border-0 py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-trophy text-warning me-2"></i>
                            <?php echo e(__('messages.dash_top_products_title')); ?> · <?php echo e(now()->format('M Y')); ?>

                        </h6>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-2">
                        <?php if(isset($topProducts) && $topProducts->count()): ?>
                            <canvas id="topProductsChart" style="max-height:200px;"></canvas>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="bx bx-package d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                <p class="small mb-0"><?php echo e(__('messages.dash_no_sales_data')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

    
    <?php if($user->can('sales.view') || $user->can('customers.view')): ?>
        <div class="sec-hd"><i class="bx bx-receipt"></i> <?php echo e(__('messages.dash_sales_customers')); ?></div>
        <div class="row g-3 mb-4">

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.view')): ?>
                <div class="col-lg-7 col-md-12">
                    <div class="inner-card card h-100">
                        <div
                            class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="fw-semibold mb-0">
                                <i class="bx bx-receipt text-primary me-2"></i> <?php echo e(__('messages.dash_recent_sales')); ?>

                            </h6>
                            <a href="<?php echo e(route('sales.index')); ?>"
                                class="btn btn-sm btn-outline-primary"><?php echo e(__('messages.dash_view_all')); ?></a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table d-tbl mb-0">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('messages.dash_col_invoice')); ?></th>
                                            <th><?php echo e(__('messages.dash_col_customer')); ?></th>
                                            <th><?php echo e(__('messages.dash_col_amount')); ?></th>
                                            <th><?php echo e(__('messages.dash_col_payment')); ?></th>
                                            <th><?php echo e(__('messages.dash_col_status')); ?></th>
                                            <th><?php echo e(__('messages.dash_col_date')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $recentSales ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('sales.show', $sale->id)); ?>"
                                                        class="fw-bold text-primary text-decoration-none small">
                                                        <?php echo e($sale->invoice_no); ?>

                                                    </a>
                                                </td>
                                                <td class="fw-semibold small">
                                                    <?php echo e(Str::limit($sale->customer->name ?? '-', 18)); ?></td>
                                                <td class="fw-bold small"><?php echo e(format_currency($sale->grand_total)); ?></td>
                                                <td>
                                                    <?php if($sale->payment_status === 'Paid'): ?>
                                                        <span
                                                            class="s-pill bg-success bg-opacity-10 text-success"><?php echo e(__('messages.dash_status_paid')); ?></span>
                                                    <?php elseif($sale->payment_status === 'Partial'): ?>
                                                        <span
                                                            class="s-pill bg-warning bg-opacity-10 text-warning"><?php echo e(__('messages.dash_status_partial')); ?></span>
                                                    <?php else: ?>
                                                        <span
                                                            class="s-pill bg-danger bg-opacity-10 text-danger"><?php echo e(__('messages.dash_status_unpaid')); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($sale->status === 'Completed'): ?>
                                                        <span class="s-pill bg-success bg-opacity-10 text-success"><i
                                                                class="bx bx-check-circle"></i>
                                                            <?php echo e(__('messages.dash_status_done')); ?></span>
                                                    <?php elseif($sale->status === 'Draft'): ?>
                                                        <span class="s-pill bg-warning bg-opacity-10 text-warning"><i
                                                                class="bx bx-edit"></i>
                                                            <?php echo e(__('messages.dash_status_draft')); ?></span>
                                                    <?php else: ?>
                                                        <span class="s-pill bg-secondary bg-opacity-10 text-secondary"><i
                                                                class="bx bx-dots-horizontal"></i> <?php echo e($sale->status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-muted" style="font-size:.75rem;"><?php echo e($sale->invoice_date); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">
                                                    <i class="bx bx-receipt d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.2;"></i>
                                                    <?php echo e(__('messages.dash_no_recent_sales')); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.view')): ?>
                <div class="col-lg-5 col-md-12">
                    <div class="inner-card card h-100">
                        <div
                            class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 class="fw-semibold mb-0">
                                <i class="bx bx-crown text-warning me-2"></i>
                                <?php echo e(__('messages.dash_top_customers_title')); ?> · <?php echo e(now()->format('M Y')); ?>

                            </h6>
                            <a href="<?php echo e(route('customers.index')); ?>"
                                class="btn btn-sm btn-outline-warning"><?php echo e(__('messages.dash_all_btn')); ?></a>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center py-2">
                            <?php if(isset($topCustomers) && $topCustomers->count()): ?>
                                <canvas id="topCustomersChart" style="max-height:240px;"></canvas>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bx bx-user d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                                    <p class="small mb-0"><?php echo e(__('messages.dash_no_customer_data')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>

    
    <div class="sec-hd"><i class="bx bx-error-circle"></i> <?php echo e(__('messages.dash_alerts_activity')); ?></div>
    <div class="row g-3 mb-4">

        
        <div class="col-lg-8 col-md-12">
            <div class="inner-card card h-100">
                <div class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-error-circle text-danger me-2"></i>
                        <?php echo e(__('messages.dash_low_stock_alert')); ?>

                        <?php $stockCount = ($lowStockProducts ?? collect())->count(); ?>
                        <?php if($stockCount > 0): ?>
                            <span class="badge bg-danger ms-1"
                                style="font-size:.62rem;border-radius:99px;"><?php echo e($stockCount); ?></span>
                        <?php endif; ?>
                    </h6>
                    <a href="<?php echo e(route('stocks.index')); ?>"
                        class="btn btn-sm btn-outline-danger"><?php echo e(__('messages.dash_manage_btn')); ?></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table d-tbl mb-0">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.dash_col_product')); ?></th>
                                    <th><?php echo e(__('messages.dash_col_sku')); ?></th>
                                    <th><?php echo e(__('messages.dash_col_alert')); ?></th>
                                    <th><?php echo e(__('messages.dash_col_stock')); ?></th>
                                    <th><?php echo e(__('messages.dash_col_status')); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $product = $p instanceof \App\Models\Product ? $p : null;
                                        $qty = (float) (optional(optional($product)->stock)->quantity ?? 0);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if(!empty(optional($product)->image)): ?>
                                                    <img src="<?php echo e(asset('uploads/products/' . optional($product)->image)); ?>"
                                                        class="rounded flex-shrink-0"
                                                        style="width:30px;height:30px;object-fit:cover;"
                                                        onerror="imgError(this)">
                                                <?php else: ?>
                                                    <div class="rounded d-flex align-items-center justify-content-center bg-light flex-shrink-0"
                                                        style="width:30px;height:30px;">
                                                        <i class="bx bx-package text-muted" style="font-size:.8rem;"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="fw-semibold small"
                                                    style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e(optional($product)->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td><code style="font-size:.68rem;"><?php echo e(optional($product)->code ?? '-'); ?></code>
                                        </td>
                                        <td class="text-muted small"><?php echo e(optional($product)->minimum_stock_alert ?? 0); ?>

                                        </td>
                                        <td>
                                            <span class="fw-bold <?php echo e($qty <= 0 ? 'text-danger' : 'text-warning'); ?> small">
                                                <?php echo e(number_format($qty, 2)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($qty <= 0): ?>
                                                <span class="s-pill bg-danger bg-opacity-10 text-danger"><i
                                                        class="bx bx-x-circle"></i>
                                                    <?php echo e(__('messages.dash_stock_out')); ?></span>
                                            <?php else: ?>
                                                <span class="s-pill bg-warning bg-opacity-10 text-warning"><i
                                                        class="bx bx-error"></i>
                                                    <?php echo e(__('messages.dash_stock_low')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
                                                <?php if($product): ?>
                                                    <a href="<?php echo e(route('stocks.adjust', ['product_id' => $product->id])); ?>"
                                                        class="btn btn-outline-primary btn-sm"
                                                        style="padding:.15rem .4rem;font-size:.7rem;"
                                                        title="<?php echo e(__('messages.dash_adjust_stock_title')); ?>">
                                                        <i class="bx bx-slider"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bx bx-check-shield text-success d-block mb-2"
                                                style="font-size:2.5rem;"></i>
                                            <?php echo e(__('messages.dash_all_stock_healthy')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 col-md-12">
            <div class="inner-card card h-100">
                <div class="card-header border-0 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-history text-secondary me-2"></i><?php echo e(__('messages.dash_recent_activity')); ?>

                    </h6>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activity_logs.view')): ?>
                        <a href="<?php echo e(route('activity-logs.index')); ?>"
                            class="btn btn-sm btn-outline-secondary"><?php echo e(__('messages.dash_all_btn')); ?></a>
                    <?php endif; ?>
                </div>
                <div class="card-body py-1 px-3" style="max-height:360px;overflow-y:auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex gap-3 py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <div class="act-dot mt-1"></div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 fw-semibold small text-truncate"><?php echo e($log->user->name ?? 'System'); ?></p>
                                <p class="mb-0 text-muted small text-truncate" style="font-size:.77rem;">
                                    <?php echo e($log->activity ?? ($log->description ?? '')); ?>

                                </p>
                                <span class="text-muted"
                                    style="font-size:.7rem;"><?php echo e($log->created_at->diffForHumans()); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bx bx-history d-block mb-2" style="font-size:2.5rem;opacity:.2;"></i>
                            <p class="small mb-0"><?php echo e(__('messages.dash_no_recent_activity')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // ── Live Clock ─────────────────────────────────────────────────────
        (function() {
            const M = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tick() {
                const n = new Date(),
                    h = n.getHours(),
                    ap = h >= 12 ? 'PM' : 'AM',
                    hh = h % 12 || 12;
                const t = document.getElementById('dashClock-time');
                const a = document.getElementById('dashClock-ampm');
                const d = document.getElementById('dashClock-date');
                if (t) t.textContent = pad(hh) + ':' + pad(n.getMinutes()) + ':' + pad(n.getSeconds());
                if (a) a.textContent = ap;
                if (d) d.textContent = n.getDate() + ' ' + M[n.getMonth()] + ' ' + n.getFullYear();
            }
            tick();
            setInterval(tick, 1000);
        })();

        // ── Chart defaults ─────────────────────────────────────────────────
        Chart.defaults.font.family = "'Public Sans',sans-serif";
        const _dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const _grid = _dark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
        const _tick = _dark ? '#a3a4cc' : '#697a8d';
        const _sym = '<?php echo e(addslashes($sym)); ?>';

        // ── 1. Weekly Bar ──────────────────────────────────────────────────
        (function() {
            const ctx = document.getElementById('weekChart');
            if (!ctx) return;
            const raw = <?php echo json_encode($weekDates ?? [], 15, 512) ?>;
            const salesD = <?php echo json_encode($weekSalesData ?? [], 15, 512) ?>;
            const purchD = <?php echo json_encode($weekPurchasesData ?? [], 15, 512) ?>;
            const labels = raw.map(d => {
                const dt = new Date(d + 'T00:00:00');
                return dt.toLocaleDateString('en', {
                    weekday: 'short',
                    day: 'numeric'
                });
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: '<?php echo e(__('messages.menu_sales')); ?>',
                            data: salesD,
                            backgroundColor: 'rgba(105,108,255,.78)',
                            borderRadius: 6,
                            borderSkipped: false
                        },
                        {
                            label: '<?php echo e(__('messages.menu_purchases')); ?>',
                            data: purchD,
                            backgroundColor: 'rgba(3,195,236,.7)',
                            borderRadius: 6,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: c => _sym + Number(c.parsed.y).toLocaleString('en-IN', {
                                    minimumFractionDigits: 2
                                })
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: _grid
                            },
                            ticks: {
                                color: _tick,
                                callback: v => {
                                    if (v >= 1e5) return _sym + (v / 1e5).toFixed(1) + 'L';
                                    if (v >= 1e3) return _sym + (v / 1e3).toFixed(0) + 'K';
                                    return _sym + v;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: _tick
                            }
                        }
                    }
                }
            });
        })();

        // ── 2. Top Products Doughnut ───────────────────────────────────────
        (function() {
            const ctx = document.getElementById('topProductsChart');
            if (!ctx) return;
            const labels = <?php echo json_encode(($topProducts ?? collect())->map(fn($i) => Str::limit($i->product?->name ?? 'Unknown', 18)), 512) ?>;
            const data = <?php echo json_encode(($topProducts ?? collect())->pluck('total_qty'), 15, 512) ?>;
            const clrs = ['#696cff', '#03c3ec', '#71dd37', '#ffab00', '#ff3e1d', '#9c3fe4'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: clrs,
                        borderWidth: 2,
                        borderColor: _dark ? '#2b2c40' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: _tick,
                                font: {
                                    size: 10
                                },
                                boxWidth: 10,
                                padding: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: c => ' ' + c.label + ': ' + c.parsed + ' units'
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        })();

        // ── 3. Top Customers Doughnut ──────────────────────────────────────
        (function() {
            const ctx = document.getElementById('topCustomersChart');
            if (!ctx) return;
            const labels = <?php echo json_encode(($topCustomers ?? collect())->map(fn($i) => Str::limit($i->customer?->name ?? 'Unknown', 15)), 512) ?>;
            const data = <?php echo json_encode(($topCustomers ?? collect())->pluck('total_spent'), 15, 512) ?>;
            const clrs = ['#ffab00', '#696cff', '#03c3ec', '#71dd37', '#ff3e1d'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: clrs,
                        borderWidth: 2,
                        borderColor: _dark ? '#2b2c40' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: _tick,
                                font: {
                                    size: 10
                                },
                                boxWidth: 10,
                                padding: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: c => ' ' + c.label + ': ' + _sym + Number(c.parsed).toLocaleString(
                                    'en-IN', {
                                        minimumFractionDigits: 2
                                    })
                            }
                        }
                    },
                    cutout: '62%'
                }
            });
        })();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/dashboard.blade.php ENDPATH**/ ?>