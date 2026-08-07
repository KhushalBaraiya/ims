
<?php $__env->startSection('title', 'Sales Return � ' . $saleReturn->return_no); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.sales_return_details')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('sale-returns.index')); ?>">Sale Returns</a></li>
                    <li class="breadcrumb-item active"><?php echo e($saleReturn->return_no); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.view')): ?>
                <a href="<?php echo e(route('sale-returns.print', $saleReturn->id)); ?>" target="_blank" class="btn btn-outline-success">
                    <i class="bx bx-printer me-1"></i> <?php echo e(__('messages.print')); ?>

                </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.update')): ?>
                <a href="<?php echo e(route('sale-returns.edit', $saleReturn->id)); ?>" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit_return')); ?>

                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('sale-returns.index')); ?>" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

            </a>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-undo text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm"><?php echo e(__('messages.sale_return_badge')); ?></div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-hash me-1"></i><?php echo e($saleReturn->return_no); ?></span>
                    <span>� <?php echo e($saleReturn->customer->name ?? '�'); ?></span>
                    <span>� <?php echo e($saleReturn->return_date); ?></span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold <?php echo e($saleReturn->status === 'Completed' ? 'text-success' : 'text-warning'); ?>">
                    <i
                        class="bx <?php echo e($saleReturn->status === 'Completed' ? 'bx-check' : 'bx-time'); ?> me-1"></i><?php echo e($saleReturn->status); ?>

                </span>
                <span
                    class="badge bg-white text-primary fw-semibold"><?php echo e(format_currency($saleReturn->grand_total)); ?></span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-lg-3 show-sidebar">

            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle me-2 text-primary"></i><?php echo e(__('messages.return_summary_lbl')); ?>

                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.return_no_label')); ?></span>
                            <code class="fw-bold text-primary"><?php echo e($saleReturn->return_no); ?></code>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.return_date')); ?></span>
                            <span class="fw-semibold"><?php echo e($saleReturn->return_date); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.original_invoice')); ?></span>
                            <?php if($saleReturn->sale): ?>
                                <a href="<?php echo e(route('sales.show', $saleReturn->sale_id)); ?>" class="fw-bold text-primary">
                                    <code><?php echo e($saleReturn->sale->invoice_no); ?></code>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">�</span>
                            <?php endif; ?>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.customer')); ?></span>
                            <div class="text-end">
                                <span class="fw-bold d-block"><?php echo e($saleReturn->customer->name); ?></span>
                                <?php if($saleReturn->customer->phone): ?>
                                    <small class="text-muted"><?php echo e($saleReturn->customer->phone); ?></small>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.reference_no')); ?></span>
                            <span class="small"><?php echo e($saleReturn->reference_no ?: '�'); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.processed_by')); ?></span>
                            <span class="fw-semibold"><?php echo e($saleReturn->user->name ?? '�'); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.status')); ?></span>
                            <?php if($saleReturn->status === 'Completed'): ?>
                                <span class="badge bg-success rounded-pill"><?php echo e(__('messages.completed_label')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill"><?php echo e(__('messages.pending')); ?></span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>

            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-credit-card me-2 text-success"></i><?php echo e(__('messages.refund_summary')); ?>

                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.return_value')); ?></span>
                            <span class="fw-semibold"><?php echo e(format_currency($saleReturn->sub_total)); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.tax_adjusted')); ?></span>
                            <span class="fw-semibold"><?php echo e(format_currency($saleReturn->tax_amount)); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom bg-label-primary rounded px-2">
                            <span class="fw-bold small"><?php echo e(__('messages.grand_refund_total')); ?></span>
                            <span class="fw-bold text-primary"><?php echo e(format_currency($saleReturn->grand_total)); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.refunded_to_customer')); ?></span>
                            <span class="fw-bold text-success"><?php echo e(format_currency($saleReturn->refunded_amount)); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if($saleReturn->notes): ?>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note me-2 text-warning"></i><?php echo e(__('messages.notes')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0 small text-muted" style="white-space:pre-line;"><?php echo e($saleReturn->notes); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i><?php echo e(__('messages.quick_actions')); ?>

                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.update')): ?>
                        <a href="<?php echo e(route('sale-returns.edit', $saleReturn->id)); ?>" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit_return')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.view')): ?>
                        <a href="<?php echo e(route('sale-returns.print', $saleReturn->id)); ?>" target="_blank"
                            class="btn btn-outline-success">
                            <i class="bx bx-printer me-1"></i> <?php echo e(__('messages.print_return_sheet')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if($saleReturn->sale): ?>
                        <a href="<?php echo e(route('sales.show', $saleReturn->sale_id)); ?>" class="btn btn-outline-info">
                            <i class="bx bx-receipt me-1"></i> <?php echo e(__('messages.view_original_invoice')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.delete')): ?>
                        <form id="deleteForm" action="<?php echo e(route('sale-returns.destroy', $saleReturn->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-return="<?php echo e($saleReturn->return_no); ?>">
                                <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_return')); ?>

                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        
        <div class="col-lg-9 show-main">

            
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-undo me-2 text-primary"></i><?php echo e(__('messages.returned_items')); ?>

                    </h6>
                    <span class="badge bg-label-primary"><?php echo e($saleReturn->items->count()); ?>

                        <?php echo e(__('messages.item_s')); ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.product')); ?></th>
                                    <th><?php echo e(__('messages.sku')); ?></th>
                                    <th class="text-end"><?php echo e(__('messages.unit_price')); ?></th>
                                    <th class="text-center"><?php echo e(__('messages.return_qty')); ?></th>
                                    <th><?php echo e(__('messages.reason')); ?></th>
                                    <th class="text-end"><?php echo e(__('messages.subtotal')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $saleReturn->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if($item->product->image): ?>
                                                    <img src="<?php echo e(asset('uploads/products/' . $item->product->image)); ?>"
                                                        class="tbl-img rounded" onerror="imgError(this)">
                                                <?php else: ?>
                                                    <div
                                                        class="tbl-img d-flex align-items-center justify-content-center bg-light rounded img-fallback">
                                                        <i class="bx bx-package text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?php echo e($item->product->name); ?></strong>
                                                    <small
                                                        class="d-block text-muted"><?php echo e($item->product->unit_code ?? 'PCS'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code class="small text-primary"><?php echo e($item->product->code); ?></code></td>
                                        <td class="text-end fw-semibold"><?php echo e(format_currency($item->unit_price)); ?></td>
                                        <td class="text-center fw-bold text-primary">
                                            <?php echo e(number_format($item->quantity, 0)); ?>

                                            <small
                                                class="text-muted fw-normal"><?php echo e($item->product->unit_code ?? 'PCS'); ?></small>
                                        </td>
                                        <td class="text-muted fst-italic small">
                                            <?php echo e($item->reason ?: '�'); ?>

                                        </td>
                                        <td class="text-end fw-bold"><?php echo e(format_currency($item->total_amount)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold"><?php echo e(__('messages.grand_refund_total')); ?>

                                    </td>
                                    <td class="text-end fw-bold text-primary fs-6">
                                        <?php echo e(format_currency($saleReturn->grand_total)); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-user me-2 text-secondary"></i><?php echo e(__('messages.return_meta')); ?>

                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1"><?php echo e(__('messages.processed_by')); ?></p>
                            <p class="fw-bold mb-0"><?php echo e($saleReturn->user->name ?? '�'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1"><?php echo e(__('messages.created_at')); ?></p>
                            <p class="fw-semibold mb-0"><?php echo e($saleReturn->created_at->format('d M Y, h:i A')); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small fw-semibold mb-1"><?php echo e(__('messages.last_updated')); ?></p>
                            <p class="fw-semibold mb-0"><?php echo e($saleReturn->updated_at->format('d M Y, h:i A')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on('click', '.delete-btn', function() {
            const returnNo = $(this).data('return');
            Swal.fire({
                title: '<?php echo e(__('messages.confirm_delete')); ?>',
                text: `<?php echo e(__('messages.delete')); ?> "${returnNo}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
            }).then((r) => {
                if (r.isConfirmed) document.getElementById('deleteForm').submit();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/sale_returns/show.blade.php ENDPATH**/ ?>