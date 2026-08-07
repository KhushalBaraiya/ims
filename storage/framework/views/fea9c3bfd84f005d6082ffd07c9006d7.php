
<?php $__env->startSection('title', __('messages.top_selling_title')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.top_selling')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('reports.index')); ?>">Reports</a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.top_selling')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> Print
            </button>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> All Reports
            </a>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-filter-alt me-2 text-warning"></i><?php echo e(__('messages.filter_sort_lbl')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_from')); ?></label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="<?php echo e($dateFrom); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_to')); ?></label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="<?php echo e($dateTo); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.sort_by_label')); ?></label>
                        <select name="sort_by" class="form-select form-select-sm">
                            <option value="quantity" <?php echo e($sortBy === 'quantity' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.most_sold_qty')); ?>

                            </option>
                            <option value="revenue" <?php echo e($sortBy === 'revenue' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.highest_revenue')); ?></option>
                            <option value="profit" <?php echo e($sortBy === 'profit' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.highest_profit')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.show_top')); ?></label>
                        <select name="limit" class="form-select form-select-sm">
                            <option value="10" <?php echo e($limit == 10 ? 'selected' : ''); ?>><?php echo e(__('messages.show_top')); ?> 10
                            </option>
                            <option value="20" <?php echo e($limit == 20 ? 'selected' : ''); ?>><?php echo e(__('messages.show_top')); ?> 20
                            </option>
                            <option value="50" <?php echo e($limit == 50 ? 'selected' : ''); ?>><?php echo e(__('messages.show_top')); ?> 50
                            </option>
                            <option value="100" <?php echo e($limit == 100 ? 'selected' : ''); ?>><?php echo e(__('messages.show_top')); ?> 100
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-warning btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('reports.top-selling')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <a href="?date_from=<?php echo e(now()->startOfMonth()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>&sort_by=<?php echo e($sortBy); ?>&limit=<?php echo e($limit); ?>"
                            class="btn btn-outline-secondary btn-sm flex-fill"><?php echo e(__('messages.rpt_this_month')); ?></a>
                        <a href="?date_from=<?php echo e(now()->startOfYear()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>&sort_by=<?php echo e($sortBy); ?>&limit=<?php echo e($limit); ?>"
                            class="btn btn-outline-secondary btn-sm flex-fill"><?php echo e(__('messages.rpt_this_year')); ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.products_ranked')); ?></p>
                        <h5 class="mb-0 fw-bold text-warning"><?php echo e($topProducts->count()); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;">
                        <i class="bx bx-trophy"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.total_qty_sold')); ?></p>
                        <h5 class="mb-0 fw-bold text-info"><?php echo e(number_format($grandTotal['qty'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;">
                        <i class="bx bx-cube"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.total_revenue')); ?></p>
                        <h5 class="mb-0 fw-bold text-primary"><?php echo e(format_currency($grandTotal['revenue'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.total_cost')); ?></p>
                        <h5 class="mb-0 fw-bold text-danger"><?php echo e(format_currency($grandTotal['cost'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1rem;">
                        <i class="bx bx-cart"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.rpt_net_profit')); ?></p>
                        <h5 class="mb-0 fw-bold <?php echo e($grandTotal['profit'] >= 0 ? 'text-success' : 'text-danger'); ?>">
                            <?php echo e(format_currency($grandTotal['profit'])); ?></h5>
                    </div>
                    <span
                        class="avatar-initial rounded-circle <?php echo e($grandTotal['profit'] >= 0 ? 'bg-label-success' : 'bg-label-danger'); ?> p-3"
                        style="font-size:1rem;">
                        <i class="bx bx-trending-up"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-trophy me-2 text-warning"></i>
                <?php echo e(__('messages.show_top')); ?> <?php echo e($limit); ?> <?php echo e(__('messages.products')); ?>

                <?php if($dateFrom || $dateTo): ?>
                    <span class="badge bg-label-secondary ms-2 fw-normal">
                        <?php echo e($dateFrom ?? 'All time'); ?> → <?php echo e($dateTo ?? 'Today'); ?>

                    </span>
                <?php endif; ?>
            </h6>
            <span class="badge bg-label-warning"><?php echo e(__('messages.sorted_by_lbl')); ?>: <?php echo e(ucfirst($sortBy)); ?></span>
        </div>
        <div class="card-body p-0">
            <?php if($topProducts->count()): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="topSellingTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-center" style="width:60px;"><?php echo e(__('messages.rank_label')); ?></th>
                                <th><?php echo e(__('messages.product')); ?></th>
                                <th><?php echo e(__('messages.brand')); ?></th>
                                <th><?php echo e(__('messages.category')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.qty_sold')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.rpt_orders')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.revenue_label')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.prod_cost')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.rpt_net_profit')); ?></th>
                                <th class="text-center"><?php echo e(__('messages.margin_label')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.th_stock')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $rank = $i + 1;
                                    $medalColor =
                                        $rank === 1
                                            ? 'text-warning'
                                            : ($rank === 2
                                                ? 'text-secondary'
                                                : ($rank === 3
                                                    ? 'text-danger'
                                                    : 'text-muted'));
                                    $stock = $item->product?->stock?->quantity ?? 0;
                                    $minAlert = $item->product?->minimum_stock_alert ?? 0;
                                    $stockClass =
                                        $stock <= 0
                                            ? 'text-danger fw-bold'
                                            : ($stock <= $minAlert
                                                ? 'text-warning fw-semibold'
                                                : 'text-success');
                                ?>
                                <tr>
                                    <td class="text-center ps-3">
                                        <?php if($rank <= 3): ?>
                                            <i class="bx bx-medal <?php echo e($medalColor); ?>" style="font-size:1.3rem;"></i>
                                            <div class="fw-bold <?php echo e($medalColor); ?> small"><?php echo e($rank); ?></div>
                                        <?php else: ?>
                                            <span class="text-muted fw-semibold"><?php echo e($rank); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if($item->product?->image): ?>
                                                <img src="<?php echo e(asset('uploads/products/' . $item->product->image)); ?>"
                                                    class="tbl-img rounded" onerror="imgError(this)">
                                            <?php else: ?>
                                                <div
                                                    class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
                                                    <i class="bx bx-package text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold small">
                                                    <?php echo e($item->product?->name ?? 'Deleted Product'); ?></div>
                                                <code style="font-size:.65rem;"><?php echo e($item->product?->code); ?></code>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-muted"><?php echo e($item->product?->brand?->name ?? '—'); ?></td>
                                    <td class="small text-muted"><?php echo e($item->product?->mainCategory?->name ?? '—'); ?></td>
                                    <td class="text-end fw-bold"><?php echo e(number_format($item->total_qty, 0)); ?></td>
                                    <td class="text-end small text-muted"><?php echo e($item->order_count); ?></td>
                                    <td class="text-end fw-bold text-primary"><?php echo e(format_currency($item->total_revenue)); ?>

                                    </td>
                                    <td class="text-end small text-danger"><?php echo e(format_currency($item->total_cost)); ?></td>
                                    <td
                                        class="text-end fw-bold <?php echo e($item->total_profit >= 0 ? 'text-success' : 'text-danger'); ?>">
                                        <?php echo e(format_currency($item->total_profit)); ?>

                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill <?php echo e($item->profit_pct >= 30 ? 'bg-success' : ($item->profit_pct >= 10 ? 'bg-warning text-dark' : 'bg-danger')); ?>">
                                            <?php echo e($item->profit_pct); ?>%
                                        </span>
                                    </td>
                                    <td class="text-end small <?php echo e($stockClass); ?>">
                                        <?php echo e(number_format($stock, 0)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end ps-3"><?php echo e(__('messages.totals_label')); ?></td>
                                <td class="text-end"><?php echo e(number_format($grandTotal['qty'], 0)); ?></td>
                                <td></td>
                                <td class="text-end text-primary"><?php echo e(format_currency($grandTotal['revenue'])); ?></td>
                                <td class="text-end text-danger"><?php echo e(format_currency($grandTotal['cost'])); ?></td>
                                <td class="text-end text-success"><?php echo e(format_currency($grandTotal['profit'])); ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-trophy d-block mb-2" style="font-size:3.5rem;opacity:.12;"></i>
                    <p class="mt-2 fw-semibold mb-1"><?php echo e(__('messages.no_sales_data')); ?></p>
                    <p class="small mb-0"><?php echo e(__('messages.add_sales_for_top')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            if ($('#topSellingTable').length) {
                $('#topSellingTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    columnDefs: [{
                        targets: [0, 9, 10],
                        orderable: false
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "<?php echo e(__('messages.search_products_ph')); ?>",
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/reports/top-selling.blade.php ENDPATH**/ ?>