
<?php $__env->startSection('title', __('messages.product_gallery')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* ------------------------------------------------------------
                                                                                                               PRODUCT GALLERY � BASE STYLES
                                                                                                               ------------------------------------------------------------ */

        /* -- Card -- */
        .pg-card {
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, .07);
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #fff;
        }

        .pg-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(105, 108, 255, .17) !important;
            border-color: rgba(105, 108, 255, .25);
        }

        /* -- Image area -- */
        .pg-img-wrap {
            position: relative;
            height: 180px;
            overflow: hidden;
            background: #f4f5f8;
            flex-shrink: 0;
        }

        .pg-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .pg-card:hover .pg-img-wrap img {
            transform: scale(1.05);
        }

        .pg-no-img {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #c8d0da;
            gap: .3rem;
        }

        .pg-no-img i {
            font-size: 2.8rem;
        }

        .pg-no-img span {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* -- Overlay badges -- */
        .pg-tl {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
        }

        .pg-tr {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 3px;
            align-items: flex-end;
        }

        /* -- Card body -- */
        .pg-body {
            padding: .85rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* -- Price box -- */
        .pg-price-box {
            background: linear-gradient(135deg, #f0f1ff, #f5f0ff);
            border-radius: 8px;
            padding: .5rem .7rem;
            margin-bottom: .6rem;
        }

        /* -- Action buttons -- */
        .pg-actions .btn {
            font-size: .75rem;
            padding: .3rem .6rem;
            border-radius: 6px;
        }

        /* -- Gallery thumbs -- */
        .pg-thumb {
            width: 26px;
            height: 26px;
            object-fit: cover;
            border-radius: 4px;
            border: 1.5px solid #dee2e6;
        }

        /* -- Toolbar selects -- */
        .pg-toolbar select.form-select {
            min-width: 0;
            flex: 1 1 auto;
        }

        /* -- Dark mode -- */
        [data-bs-theme="dark"] .pg-card {
            background: #2b2c40;
            border-color: rgba(255, 255, 255, .07);
        }

        [data-bs-theme="dark"] .pg-img-wrap {
            background: #1e1e2e;
        }

        [data-bs-theme="dark"] .pg-no-img {
            color: #3d4460;
        }

        [data-bs-theme="dark"] .pg-price-box {
            background: linear-gradient(135deg, #25264a, #2e1a44);
        }

        /* -- Pagination -- */
        .pg-pagination .pagination {
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pg-pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid #dee2e6;
            color: #697a8d;
            padding: .38rem .72rem;
            font-size: .82rem;
            font-weight: 500;
            line-height: 1.4;
            transition: all .15s;
            min-width: 36px;
            text-align: center;
        }

        .pg-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #696cff, #9c3fe4);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px rgba(105, 108, 255, .4);
        }

        .pg-pagination .page-item:not(.active) .page-link:hover {
            background: rgba(105, 108, 255, .08);
            border-color: rgba(105, 108, 255, .3);
            color: #696cff;
        }

        .pg-pagination .page-item.disabled .page-link {
            opacity: .45;
            background: transparent;
        }

        /* ------------------------------------------------------------
                                                                                                               RESPONSIVE � LARGE TABLET  (768 � 991px)
                                                                                                               ------------------------------------------------------------ */
        @media (min-width: 768px) and (max-width: 991.98px) {

            /* Image shorter on tablet to save vertical space */
            .pg-img-wrap {
                height: 155px;
            }

            /* Card body comfortable */
            .pg-body {
                padding: .75rem;
            }

            /* Filters: 2-col layout */
            #filtersCard .col-md-3,
            #filtersCard .col-md-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* Toolbar wrap on narrow tablet */
            .pg-toolbar {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .5rem;
            }

            .pg-toolbar .d-flex.gap-2 {
                width: 100%;
            }

            .pg-toolbar select.form-select {
                flex: 1 1 0;
            }

            /* Pagination */
            .pg-pagination nav ul.pagination {
                justify-content: flex-start !important;
            }
        }

        /* ------------------------------------------------------------
                                                                                                               RESPONSIVE � SMALL  (576 � 767px)
                                                                                                               ------------------------------------------------------------ */
        @media (min-width: 576px) and (max-width: 767.98px) {

            /* Image height */
            .pg-img-wrap {
                height: 140px;
            }

            /* Card body padding */
            .pg-body {
                padding: .65rem;
            }

            /* Selling price smaller */
            .pg-price-box .fw-bold.text-primary {
                font-size: .85rem !important;
            }

            /* Filters all stacked */
            #filtersCard .col-md-3,
            #filtersCard .col-md-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* Page header buttons wrap */
            .d-flex.gap-2.flex-wrap .btn {
                font-size: .78rem;
            }

            /* Toolbar */
            .pg-toolbar {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .4rem;
            }

            .pg-toolbar .d-flex.gap-2 {
                width: 100%;
            }

            .pg-toolbar select.form-select {
                flex: 1 1 0;
            }

            /* Pagination row stack */
            .d-flex.align-items-center.justify-content-between.flex-wrap.gap-2.px-1.py-3 {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }

        /* ------------------------------------------------------------
                                                                                                               RESPONSIVE � MOBILE  (0 � 575px)
                                                                                                               ------------------------------------------------------------ */
        @media (max-width: 575.98px) {

            /* -- Page header -- */
            .d-flex.align-items-center.justify-content-between.mb-4 {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: .6rem;
            }

            .d-flex.align-items-center.justify-content-between.mb-4 h4 {
                font-size: 1.05rem !important;
            }

            /* Header buttons: 2-col grid */
            .d-flex.align-items-center.justify-content-between.mb-4>div:last-child {
                width: 100%;
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: .4rem;
            }

            .d-flex.align-items-center.justify-content-between.mb-4>div:last-child .btn {
                width: 100%;
                font-size: .76rem !important;
                padding: .3rem .5rem !important;
                justify-content: center;
            }

            /* -- Stats cards � 2 per row -- */
            .row.g-3.mb-4 .col-6.col-xl-3 {
                width: 50% !important;
            }

            /* -- Filters -- */
            #filtersCard .col-md-3,
            #filtersCard .col-md-2,
            #filtersCard [class*="col-md-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            #filtersCard .card-body {
                padding: .85rem !important;
            }

            #filtersCard .d-flex.justify-content-end.gap-2.mt-3 {
                flex-direction: row;
                justify-content: stretch !important;
            }

            #filtersCard .d-flex.justify-content-end.gap-2.mt-3 .btn {
                flex: 1 1 auto;
                justify-content: center;
            }

            /* -- Toolbar -- */
            .pg-toolbar {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: .4rem;
            }

            .pg-toolbar p.text-muted {
                font-size: .75rem !important;
            }

            .pg-toolbar .d-flex.gap-2 {
                width: 100%;
                flex-wrap: nowrap;
            }

            .pg-toolbar select.form-select {
                flex: 1 1 0;
                min-width: 0;
                font-size: .78rem !important;
            }

            /* -- 1-column: horizontal card layout -- */
            .pg-card {
                flex-direction: row !important;
                height: auto !important;
                min-height: 110px;
                border-radius: 10px;
            }

            /* Image = left thumbnail */
            .pg-img-wrap {
                width: 110px !important;
                min-width: 110px !important;
                height: auto !important;
                min-height: 110px;
                border-radius: 10px 0 0 10px !important;
                flex-shrink: 0;
            }

            /* Disable zoom on horizontal */
            .pg-card:hover .pg-img-wrap img {
                transform: none;
            }

            .pg-no-img i {
                font-size: 1.6rem;
            }

            .pg-no-img span {
                font-size: .48rem;
            }

            /* Overlay badges */
            .pg-tl {
                top: 6px;
                left: 6px;
            }

            .pg-tr {
                top: 6px;
                right: 6px;
            }

            .pg-tl .badge,
            .pg-tr .badge {
                font-size: .5rem !important;
                padding: .1em .28em !important;
            }

            /* Body fills rest of width */
            .pg-body {
                padding: .55rem .65rem !important;
                flex: 1;
                min-width: 0;
                justify-content: space-between;
            }

            /* Chips */
            .pg-body .badge {
                font-size: .5rem !important;
                padding: .1em .28em !important;
            }

            /* Product name � 1 line */
            .pg-body h6 {
                font-size: .78rem !important;
                -webkit-line-clamp: 1 !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block !important;
            }

            /* SKU */
            .pg-body p.text-muted {
                font-size: .6rem !important;
                margin-bottom: .3rem !important;
            }

            /* Price box compact */
            .pg-price-box {
                padding: .3rem .45rem !important;
                margin-bottom: .3rem !important;
                border-radius: 6px !important;
            }

            .pg-price-box .fw-bold.text-primary {
                font-size: .82rem !important;
            }

            .pg-price-box .text-muted.fw-semibold {
                font-size: .62rem !important;
            }

            .pg-price-box div[style*="font-size:.58rem"] {
                font-size: .48rem !important;
            }

            /* Hide profit row � save vertical space */
            .pg-price-box>div:last-child {
                display: none !important;
            }

            /* Stock row */
            .pg-body .d-flex.justify-content-between.align-items-center.mb-2 {
                margin-bottom: .25rem !important;
            }

            .pg-body .d-flex.align-items-center.gap-1 span {
                font-size: .62rem !important;
            }

            /* Hide gallery thumbs on horizontal card */
            .pg-body .d-flex.gap-1.mb-2.flex-wrap {
                display: none !important;
            }

            /* Action buttons */
            .pg-actions {
                gap: .3rem !important;
                margin-top: auto !important;
            }

            .pg-actions .btn {
                font-size: .68rem !important;
                padding: .22rem .4rem !important;
                flex: 1 1 auto;
                border-radius: 5px !important;
            }

            /* Show labels on horizontal (space is available) */
            .pg-actions .pg-btn-label {
                display: inline !important;
            }

            /* -- Pagination row -- */
            .d-flex.align-items-center.justify-content-between.flex-wrap.gap-2.px-1.py-3 {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: .5rem !important;
            }

            .d-flex.align-items-center.justify-content-between.flex-wrap.gap-2.px-1.py-3 p {
                font-size: .72rem !important;
            }

            .pg-pagination .page-item .page-link {
                padding: .28rem .5rem !important;
                font-size: .72rem !important;
                min-width: 28px !important;
                border-radius: 6px !important;
            }
        }

        /* ------------------------------------------------------------
                                                                                                               RESPONSIVE � EXTRA SMALL  (0 � 400px)
                                                                                                               ------------------------------------------------------------ */
        @media (max-width: 400px) {

            /* Image thumb narrower on very small */
            .pg-img-wrap {
                width: 90px !important;
                min-width: 90px !important;
                min-height: 100px;
            }

            .pg-body {
                padding: .45rem .5rem !important;
            }

            .pg-body h6 {
                font-size: .72rem !important;
            }

            .pg-price-box .fw-bold.text-primary {
                font-size: .75rem !important;
            }

            .pg-actions .btn {
                font-size: .62rem !important;
                padding: .18rem .3rem !important;
            }

            /* Header buttons single column on very small */
            .d-flex.align-items-center.justify-content-between.mb-4>div:last-child {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.product_gallery')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('products.index')); ?>"><?php echo e(__('messages.products')); ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.prod_gallery')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i> <?php echo e(__('messages.filters')); ?>

                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-list-ul"></i> <?php echo e(__('messages.list_view')); ?>

            </a>
            <a href="<?php echo e(route('products.by-category')); ?>" class="btn btn-outline-success d-flex align-items-center gap-1">
                <i class="bx bx-category"></i> <?php echo e(__('messages.prod_by_category')); ?>

            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.create')): ?>
                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> <?php echo e(__('messages.add_product')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_total_products')); ?></p>
                        <h4 class="mb-0 fw-bold text-primary"><?php echo e($products->total()); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-package"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_active')); ?></p>
                        <h4 class="mb-0 fw-bold text-success"><?php echo e($activeCount); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_low_stock')); ?></p>
                        <h4 class="mb-0 fw-bold text-warning"><?php echo e($lowStockCount); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_out_of_stock')); ?></p>
                        <h4 class="mb-0 fw-bold text-danger"><?php echo e($outOfStockCount); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filter_products')); ?>

                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="<?php echo e(route('products.gallery')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="<?php echo e(__('messages.ph_search_name_sku_short')); ?>"
                                value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.brand')); ?></label>
                            <select name="brand_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_brands')); ?></option>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b->id); ?>"
                                        <?php echo e(request('brand_id') == $b->id ? 'selected' : ''); ?>>
                                        <?php echo e($b->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.category')); ?></label>
                            <select name="main_category_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_categories')); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>"
                                        <?php echo e(request('main_category_id') == $c->id ? 'selected' : ''); ?>>
                                        <?php echo e($c->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.available_stock')); ?></label>
                            <select name="stock_filter" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_stock')); ?></option>
                                <option value="ok" <?php echo e(request('stock_filter') === 'ok' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.in_stock')); ?>

                                </option>
                                <option value="low" <?php echo e(request('stock_filter') === 'low' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.low_stock_badge')); ?>

                                </option>
                                <option value="out" <?php echo e(request('stock_filter') === 'out' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.out_of_stock')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.th_status')); ?></label>
                            <select name="status" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.active')); ?></option>
                                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.inactive')); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="<?php echo e(route('products.gallery')); ?>" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i><?php echo e(__('messages.reset')); ?></a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <?php
        $sortLatest = __('messages.rpt_quick') . ' (' . __('messages.rpt_this_week') . ')';
        $lblLatest = __('messages.updated') . ' ?';
        $lblNameAsc = __('messages.th_name') . ' A�Z';
        $lblNameDesc = __('messages.th_name') . ' Z�A';
        $lblPriceUp = __('messages.prod_sell') . ' ?';
        $lblPriceDown = __('messages.prod_sell') . ' ?';
        $lblStockUp = __('messages.th_stock') . ' ?';
        $lblStockDown = __('messages.th_stock') . ' ?';
        $lblPage = __('messages.entries');
    ?>
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2 pg-toolbar">
        <p class="text-muted small mb-0">
            <?php echo e(__('messages.prod_showing_results')); ?>

            <strong><?php echo e($products->firstItem() ?? 0); ?>�<?php echo e($products->lastItem() ?? 0); ?></strong>
            <?php echo e(__('messages.prod_of')); ?> <strong><?php echo e($products->total()); ?></strong> <?php echo e(__('messages.products')); ?>

        </p>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <select class="form-select form-select-sm" style="width:auto;" data-no-select2
                onchange="applyParam('sort',this.value)">
                <option value="latest" <?php echo e(request('sort', 'latest') === 'latest' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_latest')); ?></option>
                <option value="name_asc" <?php echo e(request('sort') === 'name_asc' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_name_asc')); ?></option>
                <option value="name_desc" <?php echo e(request('sort') === 'name_desc' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_name_desc')); ?></option>
                <option value="price_asc" <?php echo e(request('sort') === 'price_asc' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_price_asc')); ?></option>
                <option value="price_desc" <?php echo e(request('sort') === 'price_desc' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_price_desc')); ?></option>
                <option value="stock_asc" <?php echo e(request('sort') === 'stock_asc' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_stock_asc')); ?></option>
                <option value="stock_desc" <?php echo e(request('sort') === 'stock_desc' ? 'selected' : ''); ?>>
                    <?php echo e(__('messages.sort_stock_desc')); ?></option>
            </select>
            <select class="form-select form-select-sm" style="width:auto;" data-no-select2
                onchange="applyParam('per_page',this.value)">
                <option value="12" <?php echo e(request('per_page', 24) == 12 ? 'selected' : ''); ?>>12 /
                    <?php echo e(__('messages.entries')); ?></option>
                <option value="24" <?php echo e(request('per_page', 24) == 24 ? 'selected' : ''); ?>>24 /
                    <?php echo e(__('messages.entries')); ?></option>
                <option value="48" <?php echo e(request('per_page', 24) == 48 ? 'selected' : ''); ?>>48 /
                    <?php echo e(__('messages.entries')); ?></option>
                <option value="96" <?php echo e(request('per_page', 24) == 96 ? 'selected' : ''); ?>>96 /
                    <?php echo e(__('messages.entries')); ?></option>
            </select>
        </div>
    </div>

    
    <?php if($products->isEmpty()): ?>
        <div class="text-center py-5 text-muted">
            <i class="bx bx-package d-block mb-3" style="font-size:4rem;opacity:.12;"></i>
            <p class="fw-semibold mb-1"><?php echo e(__('messages.prod_no_products_yet')); ?></p>
            <p class="small mb-0"><?php echo e(__('messages.prod_clear_filters_hint')); ?></p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-2 g-sm-3 mb-4">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $qty = (float) ($product->stock->quantity ?? 0);
                    $alert = (float) ($product->minimum_stock_alert ?? 0);
                    $isOut = $qty <= 0;
                    $isLow = !$isOut && $qty <= $alert;
                    $profit = $product->selling_price - $product->purchase_price;
                    $pct = $product->purchase_price > 0 ? round(($profit / $product->purchase_price) * 100, 1) : 0;
                    $gallery = $product->gallery ?? [];
                    $inactive = $product->status !== 'active';
                    $sCls = $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success');
                    $sBadge = $isOut ? 'bg-danger' : ($isLow ? 'bg-warning text-dark' : 'bg-success');
                    $sLabel = $isOut
                        ? __('messages.out_of_stock')
                        : ($isLow
                            ? __('messages.low_stock_badge')
                            : __('messages.in_stock'));
                ?>
                <div class="col">
                    <div class="card pg-card shadow-sm <?php echo e($inactive ? 'opacity-75' : ''); ?>"
                        onclick="window.location='<?php echo e(route('products.show', $product->id)); ?>'">

                        
                        <div class="pg-img-wrap"
                            <?php if(count($gallery)): ?> data-gallery-imgs="<?php echo e(json_encode(array_merge($product->image ? [$product->image] : [], $gallery))); ?>" <?php endif; ?>>
                            <?php $allCardImgs = array_values(array_filter(array_merge($product->image ? [$product->image] : [], $gallery))); ?>
                            <?php if($product->image): ?>
                                <img src="<?php echo e(asset('uploads/products/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>"
                                    class="pg-main-img" loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'pg-no-img\'><i class=\'bx bx-package\'></i><span><?php echo e(__('messages.no_image')); ?></span></div>'">
                            <?php elseif(count($gallery)): ?>
                                <img src="<?php echo e(asset('uploads/products/' . $gallery[0])); ?>" alt="<?php echo e($product->name); ?>"
                                    class="pg-main-img" loading="lazy"
                                    onerror="this.parentElement.innerHTML='<div class=\'pg-no-img\'><i class=\'bx bx-package\'></i><span><?php echo e(__('messages.no_image')); ?></span></div>'">
                            <?php else: ?>
                                <div class="pg-no-img"><i
                                        class="bx bx-package"></i><span><?php echo e(__('messages.no_image')); ?></span></div>
                            <?php endif; ?>

                            
                            <?php if(count($allCardImgs) > 1): ?>
                                <div class="pg-img-dots"
                                    style="position:absolute;bottom:6px;left:50%;transform:translateX(-50%);display:flex;gap:3px;z-index:5;">
                                    <?php $__currentLoopData = $allCardImgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $di => $dImg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span
                                            style="width:5px;height:5px;border-radius:50%;background:<?php echo e($di === 0 ? '#fff' : 'rgba(255,255,255,.45)'); ?>;transition:background .2s;"
                                            data-dot="<?php echo e($di); ?>"></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            
                            <div class="pg-tl">
                                <span class="badge <?php echo e($sBadge); ?>"
                                    style="font-size:.6rem;"><?php echo e($sLabel); ?></span>
                            </div>

                            
                            <div class="pg-tr">
                                <?php if($inactive): ?>
                                    <span class="badge bg-secondary"
                                        style="font-size:.58rem;"><?php echo e(__('messages.inactive')); ?></span>
                                <?php endif; ?>
                                <?php if(count($gallery)): ?>
                                    <span class="badge bg-dark bg-opacity-55" style="font-size:.58rem;">
                                        <i class="bx bx-images"></i> +<?php echo e(count($gallery)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="pg-body">

                            
                            <div class="d-flex gap-1 flex-wrap mb-1" style="min-height:18px;">
                                <?php if($product->brand): ?>
                                    <span class="badge bg-label-primary"
                                        style="font-size:.55rem;padding:.2em .45em;"><?php echo e($product->brand->name); ?></span>
                                <?php endif; ?>
                                <?php if($product->mainCategory): ?>
                                    <span class="badge bg-label-secondary"
                                        style="font-size:.55rem;padding:.2em .45em;"><?php echo e($product->mainCategory->name); ?></span>
                                <?php endif; ?>
                            </div>

                            
                            <h6 class="fw-bold mb-0 lh-sm" title="<?php echo e($product->name); ?>"
                                style="font-size:.8rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                <?php echo e($product->name); ?>

                            </h6>
                            <p class="text-muted mb-2" style="font-size:.65rem;">
                                <code style="font-size:.65rem;"><?php echo e($product->code); ?></code>
                            </p>

                            
                            <div class="pg-price-box">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <div
                                            style="font-size:.58rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                                            <?php echo e(__('messages.prod_sell')); ?></div>
                                        <div class="fw-bold text-primary lh-1" style="font-size:.95rem;">
                                            <?php echo e(format_currency($product->selling_price)); ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div
                                            style="font-size:.58rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                                            <?php echo e(__('messages.prod_cost')); ?></div>
                                        <div class="text-muted fw-semibold lh-1" style="font-size:.75rem;">
                                            <?php echo e(format_currency($product->purchase_price)); ?></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1 pt-1"
                                    style="border-top:1px solid rgba(0,0,0,.07);">
                                    <span
                                        style="font-size:.58rem;color:#bbb;font-weight:600;"><?php echo e(__('messages.profit')); ?></span>
                                    <span class="fw-bold <?php echo e($pct >= 0 ? 'text-success' : 'text-danger'); ?>"
                                        style="font-size:.68rem;">
                                        <?php echo e(format_currency($profit)); ?>

                                        <span class="badge <?php echo e($pct >= 0 ? 'bg-success' : 'bg-danger'); ?>"
                                            style="font-size:.52rem;padding:.15em .38em;">
                                            <?php echo e($pct >= 0 ? '+' : ''); ?><?php echo e($pct); ?>%
                                        </span>
                                    </span>
                                </div>
                            </div>

                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bx bx-cube <?php echo e($sCls); ?>" style="font-size:.85rem;"></i>
                                    <span class="fw-bold <?php echo e($sCls); ?>" style="font-size:.73rem;">
                                        <?php echo e(number_format($qty, 0)); ?> <?php echo e($product->unit_code ?? 'pcs'); ?>

                                    </span>
                                </div>
                                <?php if($product->tax_percentage): ?>
                                    <span class="badge bg-label-warning" style="font-size:.55rem;padding:.18em .42em;">
                                        <?php echo e(__('messages.gst_label')); ?> <?php echo e($product->tax_percentage); ?>%
                                    </span>
                                <?php endif; ?>
                            </div>

                            
                            <?php if(count($gallery)): ?>
                                <div class="d-flex gap-1 mb-2 flex-wrap">
                                    <?php $__currentLoopData = array_slice($gallery, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(asset('uploads/products/' . $gi)); ?>" class="pg-thumb"
                                            loading="lazy" onerror="this.style.display='none'">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($gallery) > 3): ?>
                                        <div class="pg-thumb d-flex align-items-center justify-content-center bg-light text-muted fw-bold"
                                            style="font-size:.6rem;">+<?php echo e(count($gallery) - 3); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            
                            <div class="pg-actions d-flex gap-1 mt-auto" onclick="event.stopPropagation()">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
                                    <a href="<?php echo e(route('products.show', $product->id)); ?>"
                                        class="btn btn-outline-secondary flex-fill">
                                        <i class="bx bx-show"></i><span
                                            class="pg-btn-label ms-1"><?php echo e(__('messages.view')); ?></span>
                                    </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.update')): ?>
                                    <a href="<?php echo e(route('products.edit', $product->id)); ?>"
                                        class="btn btn-outline-primary flex-fill">
                                        <i class="bx bx-edit"></i><span
                                            class="pg-btn-label ms-1"><?php echo e(__('messages.edit')); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($products->hasPages()): ?>
            <div
                class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-1 py-3 mt-2 border-top pg-pagination">
                <p class="text-muted small mb-0">
                    <?php echo e(__('messages.prod_showing_results')); ?>

                    <strong><?php echo e($products->firstItem()); ?></strong>�<strong><?php echo e($products->lastItem()); ?></strong>
                    <?php echo e(__('messages.prod_of')); ?> <strong><?php echo e($products->total()); ?></strong>
                    <?php echo e(__('messages.products')); ?>

                    &nbsp;�&nbsp; <?php echo e(__('messages.show')); ?> <?php echo e($products->currentPage()); ?> /
                    <?php echo e($products->lastPage()); ?>

                </p>
                <?php echo e($products->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function applyParam(key, val) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, val);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        $(document).ready(function() {
            const hasActiveFilter =
                <?php echo e(request()->hasAny(['search', 'brand_id', 'main_category_id', 'stock_filter', 'status']) ? 'true' : 'false'); ?>;
            if (hasActiveFilter) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').removeClass('bx-chevron-down').addClass('bx-chevron-up');
            }

            $('#toggleFiltersBtn').on('click', function() {
                const $card = $('#filtersCard');
                const $chevron = $('#filtersChevron');
                $card.toggleClass('d-none');
                const hidden = $card.hasClass('d-none');
                $chevron.toggleClass('bx-chevron-down', hidden).toggleClass('bx-chevron-up', !hidden);
            });

            // -- Multi-image hover cycling --------------------------------------
            $('.pg-img-wrap[data-gallery-imgs]').each(function() {
                const $wrap = $(this);
                const imgs = JSON.parse($wrap.attr('data-gallery-imgs') || '[]');
                if (imgs.length < 2) return;

                const $img = $wrap.find('.pg-main-img');
                const $dots = $wrap.find('[data-dot]');
                let timer = null;
                let idx = 0;

                function showImg(i) {
                    idx = i;
                    $img.attr('src', `<?php echo e(asset('uploads/products')); ?>/` + imgs[i]);
                    $dots.each(function() {
                        const di = parseInt($(this).attr('data-dot'));
                        $(this).css('background', di === i ? '#fff' : 'rgba(255,255,255,.45)');
                    });
                }

                $wrap.on('mouseenter', function() {
                    let i = 1; // start from second image
                    timer = setInterval(function() {
                        showImg(i);
                        i = (i + 1) % imgs.length;
                    }, 700);
                });

                $wrap.on('mousemove', function(e) {
                    // Also let user scrub by horizontal mouse position
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const pct = x / rect.width;
                    const targetIdx = Math.min(Math.floor(pct * imgs.length), imgs.length - 1);
                    if (targetIdx !== idx) {
                        clearInterval(timer);
                        timer = null;
                        showImg(targetIdx);
                    }
                });

                $wrap.on('mouseleave', function() {
                    clearInterval(timer);
                    timer = null;
                    showImg(0);
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/products/gallery.blade.php ENDPATH**/ ?>