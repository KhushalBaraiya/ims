
<?php $__env->startSection('title', __('messages.purchase_report')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.purchase_report')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('reports.index')); ?>"><?php echo e(__('messages.all_reports')); ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.purchase_report')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('reports.purchases.export', request()->query())); ?>" class="btn btn-outline-success btn-sm">
                <i class="bx bx-download me-1"></i> <?php echo e(__('messages.export')); ?>

            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-printer me-1"></i> <?php echo e(__('messages.print')); ?>

            </button>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.all_reports')); ?>

            </a>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i
                    class="bx bx-filter-alt me-2 text-info"></i><?php echo e(__('messages.filter_purchases')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_from')); ?></label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="<?php echo e(request('date_from')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_to')); ?></label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="<?php echo e(request('date_to')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.supplier')); ?></label>
                        <select name="supplier_id" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.all_suppliers')); ?></option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->id); ?>"
                                    <?php echo e(request('supplier_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.status')); ?></label>
                        <select name="status" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                            <option value="received" <?php echo e(request('status') === 'received' ? 'selected' : ''); ?>>Received
                            </option>
                            <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.pending')); ?></option>
                            <option value="ordered" <?php echo e(request('status') === 'ordered' ? 'selected' : ''); ?>>Ordered
                            </option>
                            <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.draft')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.payment_method')); ?></label>
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.rpt_all_methods')); ?></option>
                            <option value="Cash" <?php echo e(request('payment_method') === 'Cash' ? 'selected' : ''); ?>>💵 Cash
                            </option>
                            <option value="Razorpay" <?php echo e(request('payment_method') === 'Razorpay' ? 'selected' : ''); ?>>⚡
                                Razorpay</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-info btn-sm flex-fill text-white"><i
                                class="bx bx-search"></i></button>
                        <a href="<?php echo e(route('reports.purchases')); ?>" class="btn btn-outline-secondary btn-sm"><i
                                class="bx bx-reset"></i></a>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <span
                        class="text-muted small fw-semibold me-1 align-self-center"><?php echo e(__('messages.rpt_quick')); ?>:</span>
                    <a href="?date_from=<?php echo e(now()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>"
                        class="btn btn-outline-secondary btn-sm py-0"><?php echo e(__('messages.today')); ?></a>
                    <a href="?date_from=<?php echo e(now()->startOfWeek()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>"
                        class="btn btn-outline-secondary btn-sm py-0"><?php echo e(__('messages.rpt_this_week')); ?></a>
                    <a href="?date_from=<?php echo e(now()->startOfMonth()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>"
                        class="btn btn-outline-secondary btn-sm py-0"><?php echo e(__('messages.rpt_this_month')); ?></a>
                    <a href="?date_from=<?php echo e(now()->subMonth()->startOfMonth()->toDateString()); ?>&date_to=<?php echo e(now()->subMonth()->endOfMonth()->toDateString()); ?>"
                        class="btn btn-outline-secondary btn-sm py-0"><?php echo e(__('messages.rpt_last_month')); ?></a>
                    <a href="?date_from=<?php echo e(now()->startOfYear()->toDateString()); ?>&date_to=<?php echo e(now()->toDateString()); ?>"
                        class="btn btn-outline-secondary btn-sm py-0"><?php echo e(__('messages.rpt_this_year')); ?></a>
                </div>
            </form>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.rpt_total_orders')); ?></p>
                        <h5 class="mb-0 fw-bold text-info"><?php echo e($totals['count']); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1rem;"><i
                            class="bx bx-receipt"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.grand_total')); ?></p>
                        <h5 class="mb-0 fw-bold text-primary"><?php echo e(format_currency($totals['grand_total'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1rem;"><i
                            class="bx bx-rupee"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.paid_amount')); ?></p>
                        <h5 class="mb-0 fw-bold text-success"><?php echo e(format_currency($totals['paid_amount'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1rem;"><i
                            class="bx bx-check-circle"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.due_amount')); ?></p>
                        <h5 class="mb-0 fw-bold <?php echo e($totals['due_amount'] > 0 ? 'text-danger' : 'text-success'); ?>">
                            <?php echo e(format_currency($totals['due_amount'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;"><i
                            class="bx bx-time"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.rpt_tax_paid')); ?></p>
                        <h5 class="mb-0 fw-bold text-secondary"><?php echo e(format_currency($totals['tax_amount'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-secondary p-3" style="font-size:1rem;"><i
                            class="bx bx-percent"></i></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.rpt_discounts')); ?></p>
                        <h5 class="mb-0 fw-bold text-warning"><?php echo e(format_currency($totals['discount_amount'])); ?></h5>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1rem;"><i
                            class="bx bx-tag"></i></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-list-ul me-2 text-info"></i><?php echo e(__('messages.purchase_orders')); ?>

                <span class="badge bg-label-info ms-1"><?php echo e($totals['count']); ?></span>
            </h6>
            <?php if(request()->hasAny(['date_from', 'date_to', 'supplier_id', 'status', 'payment_method'])): ?>
                <span class="badge bg-label-secondary small fw-normal">
                    <i class="bx bx-filter-alt me-1"></i><?php echo e(__('messages.rpt_filtered_results')); ?>

                </span>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">

            <?php if($purchases->isEmpty()): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-cart-download" style="font-size:2.5rem;opacity:.3;"></i>
                    <p class="mt-2 mb-0"><?php echo e(__('messages.no_records')); ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="purReportTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th><?php echo e(__('messages.th_purchase_no')); ?></th>
                                <th><?php echo e(__('messages.th_date')); ?></th>
                                <th><?php echo e(__('messages.supplier')); ?></th>
                                <th><?php echo e(__('messages.payment_method')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.subtotal')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.tax')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.discount')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.grand_total')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.th_paid')); ?></th>
                                <th class="text-end"><?php echo e(__('messages.th_due')); ?></th>
                                <th class="text-center no-sort"><?php echo e(__('messages.status')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-muted small ps-3"><?php echo e($i + 1); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('purchases.show', $p->id)); ?>" class="fw-semibold text-info">
                                            <code><?php echo e($p->purchase_no); ?></code>
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo e(\Carbon\Carbon::parse($p->purchase_date)->format('d M Y')); ?>

                                    </td>
                                    <td class="fw-semibold small"><?php echo e($p->supplier->name ?? '—'); ?></td>
                                    <td>
                                        <?php if($p->payment_method): ?>
                                            <span class="badge bg-label-secondary"><?php echo e($p->payment_method); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end small"><?php echo e(format_currency($p->sub_total)); ?></td>
                                    <td class="text-end small text-warning"><?php echo e(format_currency($p->tax_amount)); ?></td>
                                    <td class="text-end small text-danger">-<?php echo e(format_currency($p->discount_amount)); ?>

                                    </td>
                                    <td class="text-end fw-bold"><?php echo e(format_currency($p->grand_total)); ?></td>
                                    <td class="text-end small text-success fw-semibold">
                                        <?php echo e(format_currency($p->paid_amount)); ?></td>
                                    <td
                                        class="text-end small <?php echo e($p->due_amount > 0 ? 'text-danger fw-bold' : 'text-muted'); ?>">
                                        <?php echo e(format_currency($p->due_amount)); ?>

                                    </td>
                                    <td class="text-center">
                                        <?php if($p->status === 'received' || $p->status === 'Completed'): ?>
                                            <span
                                                class="badge bg-success rounded-pill"><?php echo e(__('messages.received_badge')); ?></span>
                                        <?php elseif($p->status === 'pending' || $p->status === 'Pending'): ?>
                                            <span
                                                class="badge bg-warning text-dark rounded-pill"><?php echo e(__('messages.pending')); ?></span>
                                        <?php elseif($p->status === 'ordered'): ?>
                                            <span
                                                class="badge bg-primary rounded-pill"><?php echo e(__('messages.ordered_badge')); ?></span>
                                        <?php elseif($p->status === 'draft'): ?>
                                            <span
                                                class="badge bg-secondary rounded-pill"><?php echo e(__('messages.draft')); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-pill"><?php echo e($p->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end ps-3">
                                    <?php echo e(__('messages.rpt_totals')); ?> <span
                                        class="text-muted fw-normal">(<?php echo e($totals['count']); ?>

                                        <?php echo e(__('messages.rpt_orders')); ?>)</span>
                                </td>
                                <td class="text-end"><?php echo e(format_currency($totals['sub_total'])); ?></td>
                                <td class="text-end text-warning"><?php echo e(format_currency($totals['tax_amount'])); ?></td>
                                <td class="text-end text-danger">-<?php echo e(format_currency($totals['discount_amount'])); ?></td>
                                <td class="text-end text-primary"><?php echo e(format_currency($totals['grand_total'])); ?></td>
                                <td class="text-end text-success"><?php echo e(format_currency($totals['paid_amount'])); ?></td>
                                <td class="text-end text-danger"><?php echo e(format_currency($totals['due_amount'])); ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            if ($('#purReportTable').length) {
                $('#purReportTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [2, 'desc']
                    ],
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search purchases...",
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
            form,
            #purReportTable_wrapper .row:first-child,
            #purReportTable_wrapper .row:last-child {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/reports/purchases.blade.php ENDPATH**/ ?>