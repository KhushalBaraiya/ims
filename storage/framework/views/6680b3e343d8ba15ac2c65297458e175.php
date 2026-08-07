
<?php $__env->startSection('title', __('messages.stock_alert_report_title')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.stock_alert_menu')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('reports.index')); ?>"><?php echo e(__('messages.all_reports')); ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.stock_alert_menu')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('reports.stock-alert.export', request()->query())); ?>" class="btn btn-outline-success btn-sm">
                <i class="bx bx-download me-1"></i> <?php echo e(__('messages.export_csv')); ?>

            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> <?php echo e(__('messages.print_btn')); ?>

            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
                <a href="<?php echo e(route('stocks.adjust')); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-store-alt me-1"></i> <?php echo e(__('messages.manage_stock_btn')); ?>

                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.all_reports')); ?>

            </a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.total_alerts')); ?></p>
                        <h4 class="fw-bold text-danger mb-0"><?php echo e($summary['total']); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-error"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.out_of_stock')); ?></p>
                        <h4 class="fw-bold text-danger mb-0"><?php echo e($summary['out_of_stock']); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.low_stock')); ?></p>
                        <h4 class="fw-bold text-warning mb-0"><?php echo e($summary['low_stock']); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-error-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.restock_value')); ?></p>
                        <h4 class="fw-bold text-primary mb-0"><?php echo e(format_currency($summary['restock_value'])); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-filter-alt me-2 text-danger"></i><?php echo e(__('messages.filter_alerts')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.alert_type')); ?></label>
                        <select name="filter" class="form-select form-select-sm">
                            <option value="all" <?php echo e($filter === 'all' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.all_alerts')); ?></option>
                            <option value="out" <?php echo e($filter === 'out' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.out_of_stock_only')); ?></option>
                            <option value="low" <?php echo e($filter === 'low' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.low_stock_only')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.brand')); ?></label>
                        <select name="brand_id" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.all_brands')); ?></option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>" <?php echo e(request('brand_id') == $b->id ? 'selected' : ''); ?>>
                                    <?php echo e($b->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3 d-flex gap-1">
                        <button type="submit" class="btn btn-danger btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('reports.stock-alert')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-error me-2 text-danger"></i><?php echo e(__('messages.alert_products_title')); ?>

                <span class="badge bg-danger ms-1"><?php echo e($summary['total']); ?></span>
            </h6>
            <div class="d-flex gap-2">
                <?php if($summary['out_of_stock'] > 0): ?>
                    <span class="badge bg-label-danger"><i class="bx bx-x-circle me-1"></i><?php echo e($summary['out_of_stock']); ?>

                        <?php echo e(__('messages.out_of_stock')); ?></span>
                <?php endif; ?>
                <?php if($summary['low_stock'] > 0): ?>
                    <span class="badge bg-label-warning"><i class="bx bx-error-circle me-1"></i><?php echo e($summary['low_stock']); ?>

                        <?php echo e(__('messages.low_stock')); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if($products->count()): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="stockAlertTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th><?php echo e(__('messages.product')); ?></th>
                                <th><?php echo e(__('messages.brand')); ?></th>
                                <th><?php echo e(__('messages.category')); ?></th>
                                <th class="text-center"><?php echo e(__('messages.status')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.current_qty')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.th_alert_level')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.qty_needed')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.purchase_price')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.restock_value')); ?></th>
                                <th class="text-center no-sort"><?php echo e(__('messages.action_label')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-muted small ps-3"><?php echo e($i + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if($p->image): ?>
                                                <img src="<?php echo e(asset('uploads/products/' . $p->image)); ?>"
                                                    class="tbl-img rounded" onerror="imgError(this)">
                                            <?php else: ?>
                                                <div
                                                    class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
                                                    <i class="bx bx-package text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold small"><?php echo e($p->name); ?></div>
                                                <code style="font-size:.65rem;"><?php echo e($p->code); ?></code>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-muted"><?php echo e($p->brand?->name ?? '—'); ?></td>
                                    <td class="small text-muted"><?php echo e($p->mainCategory?->name ?? '—'); ?></td>
                                    <td class="text-center">
                                        <?php if($p->stock_status === 'out'): ?>
                                            <span class="badge bg-danger rounded-pill">
                                                <i class="bx bx-x-circle me-1"></i><?php echo e(__('messages.out_of_stock')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark rounded-pill">
                                                <i class="bx bx-error-circle me-1"></i><?php echo e(__('messages.low_stock')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td
                                        class="text-end fw-bold <?php echo e($p->stock_status === 'out' ? 'text-danger' : 'text-warning'); ?>">
                                        <?php echo e(number_format($p->current_qty, 0)); ?>

                                    </td>
                                    <td class="text-end small text-muted"><?php echo e(number_format($p->alert_qty, 0)); ?></td>
                                    <td class="text-end small fw-semibold text-primary">
                                        <?php echo e($p->qty_needed > 0 ? '+' . number_format($p->qty_needed, 0) : '—'); ?>

                                    </td>
                                    <td class="text-end small"><?php echo e(format_currency($p->purchase_price)); ?></td>
                                    <td
                                        class="text-end fw-bold <?php echo e($p->restock_value > 0 ? 'text-primary' : 'text-muted'); ?>">
                                        <?php echo e($p->restock_value > 0 ? format_currency($p->restock_value) : '—'); ?>

                                    </td>
                                    <td class="text-center">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
                                            <a href="<?php echo e(route('stocks.adjust')); ?>"
                                                class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2"
                                                style="font-size:.7rem;">
                                                <i class="bx bx-plus-medical me-1"></i><?php echo e(__('messages.restock_btn')); ?>

                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="9" class="text-end ps-3"><?php echo e(__('messages.total_restock_value')); ?></td>
                                <td class="text-end text-primary"><?php echo e(format_currency($summary['restock_value'])); ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bx bx-check-circle text-success d-block mb-2" style="font-size:4rem;opacity:.4;"></i>
                    <p class="mt-2 fw-semibold text-success mb-1"><?php echo e(__('messages.all_stock_healthy')); ?></p>
                    <p class="text-muted small mb-0"><?php echo e(__('messages.no_products_alert')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            if ($('#stockAlertTable').length) {
                $('#stockAlertTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [5, 'asc']
                    ],
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search products...",
                        lengthMenu: "<?php echo e(__('messages.show')); ?> _MENU_ <?php echo e(__('messages.entries')); ?>",
                        info: "<?php echo e(__('messages.showing')); ?> _START_ <?php echo e(__('messages.to')); ?> _END_ <?php echo e(__('messages.of')); ?> _TOTAL_ <?php echo e(__('messages.entries')); ?>",
                        infoEmpty: "<?php echo e(__('messages.no_entries')); ?>",
                        infoFiltered: "(<?php echo e(__('messages.filtered_from')); ?> _MAX_ <?php echo e(__('messages.total_entries')); ?>)",
                        paginate: {
                            previous: '<i class="bx bx-chevron-left"></i>',
                            next: '<i class="bx bx-chevron-right"></i>'
                        }
                    }
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        @media print {

            .layout-menu,
            .layout-navbar,
            .breadcrumb,
            .card-header .d-flex .btn,
            form {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/reports/stock-alert.blade.php ENDPATH**/ ?>