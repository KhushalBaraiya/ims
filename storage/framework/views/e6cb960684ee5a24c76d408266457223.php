
<?php $__env->startSection('title', 'Edit Template — ' . $template->name); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="bx bxl-whatsapp text-success" style="font-size:1.5rem;"></i>
                Edit Template
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('whatsapp.index')); ?>">WhatsApp</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('whatsapp.index')); ?>" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-edit text-primary me-2"></i>Edit: <?php echo e($template->name); ?>

                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('whatsapp.update', $template->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <?php echo $__env->make('whatsapp.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/whatsapp/edit.blade.php ENDPATH**/ ?>