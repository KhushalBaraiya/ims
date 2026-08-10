<?php $__env->startSection('title', __('messages.create_sales_invoice')); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1 h4"><?php echo e(__('messages.create_sales_invoice')); ?></h2>
        <p class="text-muted small"><?php echo e(__('messages.store_desc')); ?></p>
    </div>

    <!-- Form wrapper -->
    <form method="POST" action="<?php echo e(route('sales.store')); ?>" novalidate>
        <?php echo $__env->make('sales.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/sales/create.blade.php ENDPATH**/ ?>