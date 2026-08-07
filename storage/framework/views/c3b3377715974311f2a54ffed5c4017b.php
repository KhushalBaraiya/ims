<?php $__env->startSection('title', __('messages.activity_logs')); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">
                <?php echo e(__('messages.activity_logs')); ?>

                <?php if($restrictToOwn ?? false): ?>
                    <span class="badge bg-label-info ms-2"
                        style="font-size:.7rem;vertical-align:middle;"><?php echo e(__('messages.own') ?? 'My Logs'); ?></span>
                <?php endif; ?>
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.activity_logs')); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filters')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('activity-logs.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.search_activity_placeholder')); ?>">
                    </div>
                    <?php if (! ($restrictToOwn ?? false)): ?>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.user')); ?></label>
                            <select name="user_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_users')); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"
                                        <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="<?php echo e($restrictToOwn ?? false ? 'col-md-3' : 'col-md-2'); ?>">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_from')); ?></label>
                        <input type="date" name="date_from" class="form-control form-control-sm flatpickr-filter-date"
                            value="<?php echo e(request('date_from')); ?>">
                    </div>
                    <div class="<?php echo e($restrictToOwn ?? false ? 'col-md-3' : 'col-md-2'); ?>">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.date_to')); ?></label>
                        <input type="date" name="date_to" class="form-control form-control-sm flatpickr-filter-date"
                            value="<?php echo e(request('date_to')); ?>">
                    </div>
                    <div class="<?php echo e($restrictToOwn ?? false ? 'col-md-3' : 'col-md-2'); ?> d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('activity-logs.index')); ?>" class="btn btn-outline-secondary btn-sm">
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
                <i class="bx bx-history me-2 text-info"></i><?php echo e(__('messages.activity_log_list')); ?>

                <span class="badge bg-label-info ms-1"><?php echo e($logs->total()); ?></span>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4"><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.activity')); ?></th>
                            <th><?php echo e(__('messages.description_label')); ?></th>
                            <th><?php echo e(__('messages.user')); ?></th>
                            <th><?php echo e(__('messages.ip_address')); ?></th>
                            <th><?php echo e(__('messages.th_date')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 text-muted fw-semibold"><?php echo e($logs->firstItem() + $loop->index); ?></td>
                                <td>
                                    <span class="badge bg-label-primary"><?php echo e($log->activity); ?></span>
                                </td>
                                <td class="text-muted small" style="max-width:300px;">
                                    <?php echo e($log->description ?? '—'); ?>

                                </td>
                                <td>
                                    <?php if($log->user): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-label-primary fw-bold"
                                                style="width:28px;height:28px;font-size:.75rem;">
                                                <?php echo e(strtoupper(substr($log->user->name, 0, 1))); ?>

                                            </div>
                                            <span class="small fw-semibold"><?php echo e($log->user->name); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small"><?php echo e(__('messages.system')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?php echo e($log->ip_address ?? '—'); ?></td>
                                <td class="small text-muted">
                                    <span title="<?php echo e($log->created_at->format('Y-m-d H:i:s')); ?>">
                                        <?php echo e($log->created_at->diffForHumans()); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" style="padding:0;">
                                    <div style="width:100%;text-align:center;padding:2.5rem 0;">
                                        <div class="text-muted"
                                            style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                            <i class="bx bx-history" style="font-size:3rem;opacity:.3;line-height:1;"></i>
                                            <span><?php echo e(__('messages.no_records')); ?></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($logs->hasPages()): ?>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 py-2 border-top">
                <p class="text-muted small mb-0">
                    <?php echo e(__('messages.showing')); ?>

                    <strong><?php echo e($logs->firstItem()); ?></strong>–<strong><?php echo e($logs->lastItem()); ?></strong>
                    <?php echo e(__('messages.of')); ?> <strong><?php echo e($logs->total()); ?></strong> <?php echo e(__('messages.entries')); ?>

                </p>
                <?php echo e($logs->links()); ?>

            </div>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/activity_logs/index.blade.php ENDPATH**/ ?>