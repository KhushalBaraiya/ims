<?php $__env->startSection('title', 'Create Purchase Order'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Create Purchase Order</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo e(route('purchases.index')); ?>"><?php echo e(__('messages.purchase_orders')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.add')); ?></li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('purchases.index')); ?>" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

        </a>
    </div>

    <form method="POST" action="<?php echo e(route('purchases.store')); ?>" novalidate>
        <?php echo $__env->make('purchases.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/purchases/create.blade.php ENDPATH**/ ?>