
<?php $__env->startSection('title', __('messages.products_catalog')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .prod-img-cell {
            position: relative;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            line-height: 0;
        }

        .prod-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            border: 1.5px solid #dee2e6;
            border-bottom: none;
            display: block;
            transition: filter .2s, transform .2s;
        }

        .prod-img-cell:hover .prod-thumb {
            filter: brightness(1.04);
            transform: translateY(-2px);
        }

        .prod-thumb.img-fallback {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f0f2f5;
            color: #adb5bd;
            font-size: 1.3rem;
            border-radius: 10px 10px 0 0;
            border: 1.5px solid #dee2e6;
            border-bottom: none;
        }

        .prod-price-tag {
            display: block;
            width: 56px;
            text-align: center;
            background: linear-gradient(90deg, #696cff, #9c3fe4);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            line-height: 1;
            padding: 3px 3px 4px;
            border-radius: 0 0 7px 7px;
            letter-spacing: .3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 3px 8px rgba(105, 108, 255, .3);
        }

        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .stock-pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .55rem;
            border-radius: 99px;
            font-size: .73rem;
            font-weight: 600;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.products_catalog')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_products')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i>
                <?php echo e(__('messages.filters')); ?>

                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            <a href="<?php echo e(route('products.gallery')); ?>" class="btn btn-outline-info d-flex align-items-center gap-1">
                <i class="bx bx-grid-alt"></i> <?php echo e(__('messages.prod_gallery')); ?>

            </a>
            <a href="<?php echo e(route('products.by-category')); ?>" class="btn btn-outline-success d-flex align-items-center gap-1">
                <i class="bx bx-category"></i> <?php echo e(__('messages.prod_by_category')); ?>

            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.delete')): ?>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_multiples')); ?>

                </button>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.create')): ?>
                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> <?php echo e(__('messages.add_product')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <?php
            // Use DB aggregates � $products is now paginated, not a full collection
            $total = \App\Models\Product::count();
            $active = \App\Models\Product::where('status', 'active')->count();
            $lowStock = \App\Models\Product::whereHas(
                'stock',
                fn($q) => $q->where('quantity', '>', 0)->whereRaw('stocks.quantity <= products.minimum_stock_alert'),
            )->count();
            $outStock = \App\Models\Product::where(
                fn($q) => $q->whereHas('stock', fn($sq) => $sq->where('quantity', '<=', 0))->orWhereDoesntHave('stock'),
            )->count();
        ?>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_total_products')); ?></p>
                        <h4 class="mb-0 fw-bold text-primary"><?php echo e($total); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;"><i
                            class="bx bx-package"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_active')); ?></p>
                        <h4 class="mb-0 fw-bold text-success" id="statActiveCount"><?php echo e($active); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;"><i
                            class="bx bx-check-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_low_stock')); ?></p>
                        <h4 class="mb-0 fw-bold text-warning"><?php echo e($lowStock); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;"><i
                            class="bx bx-error-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.prod_out_of_stock')); ?></p>
                        <h4 class="mb-0 fw-bold text-danger"><?php echo e($outStock); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;"><i
                            class="bx bx-x-circle"></i></span>
                </div>
            </div>
        </div>
    </div>

    
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold"><i
                        class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filter_products')); ?></h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="<?php echo e(route('products.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.ph_search_name_sku')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.brand')); ?></label>
                            <select name="brand_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.prod_all_brands')); ?></option>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b->id); ?>"
                                        <?php echo e(request('brand_id') == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.category')); ?></label>
                            <select name="main_category_id" id="filter_main_category_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.prod_all_categories')); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->id); ?>"
                                        <?php echo e(request('main_category_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.sub_category')); ?></label>
                            <select name="sub_category_id" id="filter_sub_category_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.prod_all_subcats')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.status')); ?></label>
                            <select name="status" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.prod_all_statuses')); ?></option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.active')); ?></option>
                                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.inactive')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.stock_alert_menu')); ?></label>
                            <select name="stock_filter" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_stock')); ?></option>
                                <option value="low" <?php echo e(request('stock_filter') === 'low' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.low_stock_only')); ?></option>
                                <option value="out" <?php echo e(request('stock_filter') === 'out' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.out_of_stock_only')); ?></option>
                            </select>
                        </div>
                        <div class="col-6 col-md-1">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.prod_price_min')); ?>

                                <?php echo e(optional(current_currency())->symbol ?? '?'); ?></label>
                            <input type="number" step="0.01" name="price_min" class="form-control form-control-sm"
                                value="<?php echo e(request('price_min')); ?>" placeholder="<?php echo e(__('messages.ph_price_min')); ?>">
                        </div>
                        <div class="col-6 col-md-1">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.prod_price_max')); ?>

                                <?php echo e(optional(current_currency())->symbol ?? '?'); ?></label>
                            <input type="number" step="0.01" name="price_max" class="form-control form-control-sm"
                                value="<?php echo e(request('price_max')); ?>" placeholder="<?php echo e(__('messages.ph_price_max')); ?>">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary"><i
                                class="bx bx-reset me-1"></i><?php echo e(__('messages.reset')); ?></a>
                        <button type="submit" class="btn btn-primary"><i
                                class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">

            
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 border-bottom">
                
                <form method="GET" action="<?php echo e(route('products.index')); ?>" id="perPageForm"
                    class="d-flex align-items-center gap-2 mb-0">
                    <?php $__currentLoopData = request()->except('per_page', 'page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($val); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <label class="text-muted small mb-0"><?php echo e(__('messages.show')); ?></label>
                    <select name="per_page" class="form-select form-select-sm" style="width:75px;" data-no-select2
                        onchange="document.getElementById('perPageForm').submit()">
                        <?php $__currentLoopData = [10, 20, 50, 100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($n); ?>" <?php echo e(request('per_page', 10) == $n ? 'selected' : ''); ?>>
                                <?php echo e($n); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="text-muted small"><?php echo e(__('messages.entries')); ?></span>
                </form>

                
                <form method="GET" action="<?php echo e(route('products.index')); ?>" class="d-flex align-items-center gap-1">
                    <?php $__currentLoopData = request()->except('search', 'page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($val); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="input-group input-group-sm" style="width:220px;">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bx bx-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="<?php echo e(__('messages.search')); ?>..." value="<?php echo e(request('search')); ?>">
                        <?php if(request('search')): ?>
                            <a href="<?php echo e(route('products.index', request()->except('search', 'page'))); ?>"
                                class="btn btn-outline-secondary" title="<?php echo e(__('messages.clear_search')); ?>">
                                <i class="bx bx-x"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th class="no-sort" style="width:80px"><?php echo e(__('messages.prod_image_col')); ?></th>
                            <th><?php echo e(__('messages.th_name')); ?></th>
                            <th><?php echo e(__('messages.sku')); ?></th>
                            <th><?php echo e(__('messages.prod_brand_category')); ?></th>
                            <th class="text-end"><?php echo e(__('messages.prod_cost')); ?></th>
                            <th class="text-end"><?php echo e(__('messages.prod_sell')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.prod_stock_col')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                            <th class="text-center no-sort" style="width:130px"><?php echo e(__('messages.prod_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $sq = (float) ($product->stock->quantity ?? 0);
                                $sal = (float) ($product->minimum_stock_alert ?? 0);
                                $sOut = $sq <= 0;
                                $sLow = !$sOut && $sal > 0 && $sq <= $sal;
                                $sCls = $sOut ? 'text-danger' : ($sLow ? 'text-warning' : 'text-success');
                                $sBg = $sOut
                                    ? 'rgba(234,84,85,.1)'
                                    : ($sLow
                                        ? 'rgba(255,171,0,.1)'
                                        : 'rgba(40,199,111,.1)');
                            ?>
                            <tr>
                                <td class="text-muted small fw-semibold"><?php echo e($products->firstItem() + $i); ?></td>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="<?php echo e($product->id); ?>"></td>
                                <td>
                                    <div class="prod-img-cell">
                                        <?php if($product->image): ?>
                                            <img src="<?php echo e(asset('uploads/products/' . $product->image)); ?>"
                                                class="prod-thumb"
                                                onerror="this.outerHTML='<div class=\'prod-thumb img-fallback\'><i class=\'bx bx-package\'></i></div>'">
                                        <?php else: ?>
                                            <div class="prod-thumb img-fallback"><i class="bx bx-package"></i></div>
                                        <?php endif; ?>
                                        <span class="prod-price-tag"><?php echo e(format_currency($product->selling_price)); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="max-width:200px;"><?php echo e($product->name); ?></div>
                                    <?php if($product->subCategory): ?>
                                        <small class="text-muted"><?php echo e($product->subCategory->name); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><code class="small"><?php echo e($product->code); ?></code></td>
                                <td>
                                    <div class="small fw-semibold"><?php echo e($product->brand->name ?? '�'); ?></div>
                                    <small class="text-muted"><?php echo e($product->mainCategory->name ?? '�'); ?></small>
                                </td>
                                <td class="text-end fw-semibold small"><?php echo e(format_currency($product->purchase_price)); ?>

                                </td>
                                <td class="text-end fw-bold text-primary"><?php echo e(format_currency($product->selling_price)); ?>

                                </td>
                                <td class="text-center">
                                    <?php if($sq > 0 || $product->stock): ?>
                                        <span class="stock-pill" style="background:<?php echo e($sBg); ?>;">
                                            <i class="bx bx-cube <?php echo e($sCls); ?>" style="font-size:.8rem;"></i>
                                            <span class="<?php echo e($sCls); ?>"><?php echo e(number_format($sq, 0)); ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">�</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.update')): ?>
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1 <?php echo e($product->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;cursor:pointer;" data-id="<?php echo e($product->id); ?>"
                                            data-status="<?php echo e($product->status); ?>"
                                            title="<?php echo e(__('messages.toggle_status')); ?>">
                                            <?php echo e(ucfirst($product->status)); ?>

                                        </button>
                                    <?php else: ?>
                                        <span
                                            class="badge rounded-pill <?php echo e($product->status === 'active' ? 'bg-success' : 'bg-secondary'); ?>">
                                            <?php echo e(ucfirst($product->status)); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="tbl-action-wrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
                                            <a href="<?php echo e(route('products.show', $product->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="<?php echo e(__('messages.view')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.update')): ?>
                                            <a href="<?php echo e(route('products.edit', $product->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="<?php echo e(__('messages.edit')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.create')): ?>
                                            <a href="<?php echo e(route('products.copy', $product->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                title="<?php echo e(__('messages.copy')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-copy" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.delete')): ?>
                                            <form id="del-<?php echo e($product->id); ?>"
                                                action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST"
                                                class="d-inline" style="display:contents;">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="<?php echo e($product->id); ?>" data-name="<?php echo e($product->name); ?>"
                                                    title="<?php echo e(__('messages.delete')); ?>"
                                                    style="width:30px;height:30px;padding:0;">
                                                    <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10">
                                    <div class="text-center py-5">
                                        <div class="mb-3"
                                            style="width:72px;height:72px;border-radius:50%;background:rgba(105,108,255,.08);display:inline-flex;align-items:center;justify-content:center;">
                                            <i class="bx bx-package text-primary" style="font-size:2rem;opacity:.5;"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-body">
                                            <?php if(request()->hasAny([
                                                    'search',
                                                    'brand_id',
                                                    'main_category_id',
                                                    'sub_category_id',
                                                    'status',
                                                    'price_min',
                                                    'price_max',
                                                    'stock_filter',
                                                ])): ?>
                                                <?php echo e(__('messages.prod_no_match_filters')); ?>

                                            <?php else: ?>
                                                <?php echo e(__('messages.prod_no_products_yet')); ?>

                                            <?php endif; ?>
                                        </h6>
                                        <p class="text-muted small mb-3">
                                            <?php if(request()->hasAny([
                                                    'search',
                                                    'brand_id',
                                                    'main_category_id',
                                                    'sub_category_id',
                                                    'status',
                                                    'price_min',
                                                    'price_max',
                                                    'stock_filter',
                                                ])): ?>
                                                <?php echo e(__('messages.prod_clear_filters_hint')); ?>

                                            <?php else: ?>
                                                <?php echo e(__('messages.prod_get_started_hint')); ?>

                                            <?php endif; ?>
                                        </p>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <?php if(request()->hasAny([
                                                    'search',
                                                    'brand_id',
                                                    'main_category_id',
                                                    'sub_category_id',
                                                    'status',
                                                    'price_min',
                                                    'price_max',
                                                    'stock_filter',
                                                ])): ?>
                                                <a href="<?php echo e(route('products.index')); ?>"
                                                    class="btn btn-outline-secondary">
                                                    <i class="bx bx-reset me-1"></i>
                                                    <?php echo e(__('messages.prod_clear_filters_btn')); ?>

                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.create')): ?>
                                                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                                                    <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.prod_add_product_btn')); ?>

                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 border-top">
                <p class="text-muted small mb-0">
                    <?php if($products->total() > 0): ?>
                        <?php echo e(__('messages.prod_showing_results')); ?>

                        <strong><?php echo e($products->firstItem()); ?></strong>�<strong><?php echo e($products->lastItem()); ?></strong>
                        <?php echo e(__('messages.prod_of')); ?> <strong><?php echo e($products->total()); ?></strong>
                        <?php echo e(__('messages.prod_results')); ?>

                    <?php else: ?>
                        <?php echo e(__('messages.prod_no_results')); ?>

                    <?php endif; ?>
                </p>
                <?php echo e($products->appends(request()->query())->links()); ?>

            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {

            // -- Filter toggle -------------------------------------------------
            let open = localStorage.getItem('prod_filters_open') === 'true';
            if (open) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('prod_filters_open', isOpen);
            });

            // -- Sub-category filter -------------------------------------------
            const subs = <?php echo json_encode($subCategories, 15, 512) ?>;
            const selSub = "<?php echo e(request('sub_category_id')); ?>";

            function loadSubs(catId, pre = '') {
                const $s = $('#filter_sub_category_id');
                if ($s.hasClass('select2-hidden-accessible')) $s.select2('destroy');
                $s.html('<option value="">' + '<?php echo e(__('messages.prod_all_subcats')); ?>' + '</option>');
                if (!catId) {
                    $s.select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });
                    return;
                }
                subs.filter(s => s.main_category_id == catId).forEach(s => {
                    $s.append(
                        `<option value="${s.id}" ${s.id == pre ? 'selected' : ''}>${s.name}</option>`);
                });
                $s.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    placeholder: '<?php echo e(__('messages.prod_all_subcats')); ?>'
                });
            }

            $('#filter_main_category_id').on('change', function() {
                loadSubs($(this).val());
            });

            // On page load: if sub_category_id is pre-selected, also infer main cat to populate dropdown
            const initCat = "<?php echo e(request('main_category_id')); ?>";
            if (initCat) loadSubs(initCat, selSub);
            else if (selSub) {
                // sub selected but no main selected � find the matching main and pre-load
                const matchedSub = subs.find(s => s.id == selSub);
                if (matchedSub) {
                    // Set main category dropdown value then load subs
                    $('#filter_main_category_id').val(matchedSub.main_category_id).trigger('change');
                    loadSubs(matchedSub.main_category_id, selSub);
                }
            }

            // -- AJAX Status Toggle -----------------------------------------
            $(document).on('click', '.status-toggle-btn', function() {
                const btn = $(this);
                const id = btn.data('id');
                const cur = btn.data('status');
                $.ajax({
                    url: `/products/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    beforeSend: () => btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span>'),
                    success: (res) => {
                        btn.prop('disabled', false);
                        if (res.success) {
                            btn.data('status', res.status);
                            btn.removeClass(
                                'border-success text-success border-danger text-danger');
                            btn.addClass(res.status === 'active' ?
                                'border-success text-success' : 'border-danger text-danger');
                            btn.text(res.status === 'active' ? '<?php echo e(__('messages.active')); ?>' :
                                '<?php echo e(__('messages.inactive')); ?>');
                            showAdminToast(res.message, 'success');
                            // -- Update Active stat card live --------------
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                        } else {
                            btn.text(cur === 'active' ? '<?php echo e(__('messages.active')); ?>' :
                                '<?php echo e(__('messages.inactive')); ?>');
                            showAdminToast(res.message ||
                                '<?php echo e(__('messages.error_occurred')); ?>', 'error');
                        }
                    },
                    error: () => {
                        btn.prop('disabled', false).text(cur === 'active' ?
                            '<?php echo e(__('messages.active')); ?>' :
                            '<?php echo e(__('messages.inactive')); ?>');
                        showAdminToast('<?php echo e(__('messages.error_occurred')); ?>', 'error');
                    }
                });
            });

            // -- AJAX Delete with SweetAlert2 ----------------------------------
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const form = $(`#del-${id}`);

                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: `Delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then(r => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: res => {
                                if (res.success) {
                                    Swal.fire({
                                        title: '<?php echo e(__('messages.deleted_title')); ?>',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#696cff'
                                    }).then(() => location.reload());
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: () => showAdminToast(
                                '<?php echo e(__('messages.error_occurred')); ?>', 'error')
                        });
                    }
                });
            });

            // -- Bulk Select ----------------------------------------------
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleBulkBtn();
            });
            $(document).on('change', '.row-checkbox', function() {
                $('#selectAll').prop('checked', $('.row-checkbox:not(:checked)').length === 0);
                toggleBulkBtn();
            });

            function toggleBulkBtn() {
                const count = $('.row-checkbox:checked').length;
                count > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            // -- Bulk Delete ----------------------------------------------
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: '<?php echo e(__('messages.confirm_delete')); ?>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('products.bulk-destroy')); ?>',
                            type: 'DELETE',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>',
                                ids: ids
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                            title: '<?php echo e(__('messages.deleted_title')); ?>',
                                            text: res.message,
                                            icon: 'success',
                                            confirmButtonColor: '#696cff'
                                        })
                                        .then(() => window.location.reload());
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: function() {
                                showAdminToast('<?php echo e(__('messages.error_occurred')); ?>',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/products/index.blade.php ENDPATH**/ ?>