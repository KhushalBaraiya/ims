
<?php $__env->startSection('title', __('messages.stock_adjustments')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.stock_adjustments')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.stock_adjustments')); ?></li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
            <a href="<?php echo e(route('stocks.adjust')); ?>" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.create_adjustment')); ?>

            </a>
        <?php endif; ?>
    </div>

    <?php if($errors->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm border-0"
            role="alert">
            <i class="bx bx-error-circle fs-4 text-danger"></i>
            <div><?php echo e($errors->first('error')); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-slider fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold"><?php echo e(__('messages.total_vouchers')); ?></div>
                        <div class="fw-bold fs-4"><?php echo e($adjustmentsGrouped->count()); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-warning flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-error-circle fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold"><?php echo e(__('messages.low_stock_badge')); ?></div>
                        <div class="fw-bold fs-4 text-warning"><?php echo e($lowStock); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-danger flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-x-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold"><?php echo e(__('messages.out_of_stock')); ?></div>
                        <div class="fw-bold fs-4 text-danger"><?php echo e($outOfStock); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-success flex-shrink-0"
                        style="width:52px;height:52px;">
                        <i class="bx bx-package fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold"><?php echo e(__('messages.total_products')); ?></div>
                        <div class="fw-bold fs-4 text-success"><?php echo e($totalProducts); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold text-primary">
                <i class="bx bx-filter-alt me-2"></i><?php echo e(__('messages.filters')); ?>

            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('stocks.history')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.product')); ?></label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.all_products')); ?></option>
                            <?php $__currentLoopData = $allProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"
                                    <?php echo e(request('product_id') == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->name); ?> (<?php echo e($p->code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.adj_directions')); ?></label>
                        <select name="adjustment_type" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.all_directions')); ?></option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>"
                                    <?php echo e(request('adjustment_type') === $type ? 'selected' : ''); ?>>
                                    <?php echo e($type); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_from')); ?></label>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                            value="<?php echo e(request('start_date')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_to')); ?></label>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                            value="<?php echo e(request('end_date')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('stocks.history')); ?>" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold text-primary">
                <i class="bx bx-slider me-2"></i><?php echo e(__('messages.adjustment_vouchers')); ?>

            </h6>
            <span class="badge bg-label-primary"><?php echo e($adjustmentsGrouped->count()); ?> <?php echo e(__('messages.vouchers')); ?></span>
        </div>
        <div class="card-body p-0">

            <?php if($adjustmentsGrouped->isEmpty()): ?>
                
                <div style="width:100%;text-align:center;padding:2.5rem 0;">
                    <div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                        <i class="bx bx-slider" style="font-size:3rem;opacity:.3;line-height:1;"></i>
                        <?php echo e(__('messages.no_records')); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="historyTable" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th><?php echo e(__('messages.th_date')); ?></th>
                                <th><?php echo e(__('messages.voucher_no')); ?></th>
                                <th><?php echo e(__('messages.adjusted_products')); ?></th>
                                <th><?php echo e(__('messages.th_created_by')); ?></th>
                                <th><?php echo e(__('messages.notes')); ?></th>
                                <th class="text-center no-sort" style="width:90px;"><?php echo e(__('messages.th_actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $adjustmentsGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucherNo => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $firstAdj = $group->first();
                                    $date = $firstAdj->transaction_date
                                        ? \Carbon\Carbon::parse($firstAdj->transaction_date)
                                        : $firstAdj->created_at;
                                    $user = $firstAdj->user->name ?? 'System';
                                    $notes = $firstAdj->notes;
                                    $slug = \Str::slug($voucherNo);
                                ?>
                                <tr>
                                    <td class="text-muted fw-semibold"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-muted small"><?php echo e($date->format('d M Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('stocks.show_adjustment', $voucherNo)); ?>"
                                            class="fw-bold text-primary text-decoration-none">
                                            <code><?php echo e($voucherNo ?: '—'); ?></code>
                                        </a>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0" style="padding-left:0;">
                                            <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $isPositive = $adj->quantity_change >= 0;
                                                    $qtySign = $isPositive ? '+' : '';
                                                    $colorClass = $isPositive ? 'text-success' : 'text-danger';
                                                ?>
                                                <li class="mb-1" style="font-size:0.85rem;">
                                                    <i class="bx bx-subdirectory-right text-muted me-1"></i>
                                                    <strong><?php echo e($adj->product->name ?? __('messages.deleted_product')); ?></strong>
                                                    (<code
                                                        class="small text-muted"><?php echo e($adj->product->code ?? '-'); ?></code>)
                                                    &rarr;
                                                    <span class="fw-bold <?php echo e($colorClass); ?>">
                                                        <?php echo e($qtySign); ?><?php echo e(number_format($adj->quantity_change, 2)); ?>

                                                    </span>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </td>
                                    <td class="fw-semibold small"><?php echo e($user); ?></td>
                                    <td class="text-muted small" title="<?php echo e($notes); ?>">
                                        <?php echo e($notes ? \Str::limit($notes, 40) : '—'); ?>

                                    </td>
                                    <td class="text-center">
                                        <div class="tbl-action-wrap">
                                            <a href="<?php echo e(route('stocks.show_adjustment', $voucherNo)); ?>"
                                                class="btn btn-sm btn-outline-info btn-action"
                                                title="<?php echo e(__('messages.voucher_details')); ?>"
                                                style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;flex-shrink:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stocks.create')): ?>
                                                <a href="<?php echo e(route('stocks.edit_adjustment', $voucherNo)); ?>"
                                                    class="btn btn-sm btn-outline-primary btn-action"
                                                    title="<?php echo e(__('messages.edit_adjustment')); ?>"
                                                    style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;flex-shrink:0;">
                                                    <i class="bx bx-edit" style="font-size:1rem;"></i>
                                                </a>
                                                <form id="delete-form-<?php echo e($slug); ?>"
                                                    action="<?php echo e(route('stocks.destroy_adjustment', $voucherNo)); ?>"
                                                    method="POST" class="d-inline" style="display:contents;">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-id="<?php echo e($slug); ?>" data-no="<?php echo e($voucherNo); ?>"
                                                        title="<?php echo e(__('messages.delete')); ?>"
                                                        style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;flex-shrink:0;">
                                                        <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Only init DataTable when records exist
            if ($('#historyTable').length) {
                $('#historyTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 25,
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false
                    }],
                    dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row px-3 py-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "<?php echo e(__('messages.search')); ?>...",
                        lengthMenu: "<?php echo e(__('messages.show')); ?> _MENU_ <?php echo e(__('messages.entries')); ?>",
                        info: "<?php echo e(__('messages.showing')); ?> _START_ <?php echo e(__('messages.to')); ?> _END_ <?php echo e(__('messages.of')); ?> _TOTAL_ <?php echo e(__('messages.entries')); ?>",
                        infoEmpty: "<?php echo e(__('messages.no_entries')); ?>",
                        infoFiltered: "(<?php echo e(__('messages.filtered_from')); ?> _MAX_ <?php echo e(__('messages.total_entries')); ?>)",
                        emptyTable: '<div class="text-center py-5 text-muted"><i class="bx bx-inbox" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:8px;"></i><?php echo e(__('messages.no_records')); ?></div>',
                        paginate: {
                            previous: '<i class="bx bx-chevron-left"></i>',
                            next: '<i class="bx bx-chevron-right"></i>'
                        }
                    }
                });
            }

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    no = $(this).data('no'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '<?php echo e(__('messages.delete_voucher_title')); ?>',
                    text: `<?php echo e(__('messages.delete_voucher_text')); ?> "${no}". <?php echo e(__('messages.confirm')); ?>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then((r) => {
                    if (r.isConfirmed) form.submit();
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/stocks/history.blade.php ENDPATH**/ ?>