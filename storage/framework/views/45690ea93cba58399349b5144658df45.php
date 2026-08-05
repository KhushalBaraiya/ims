<?php $__env->startSection('title', __('messages.user_details') . ' � ' . $user->name); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.user_details')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('users.index')); ?>"><?php echo e(__('messages.menu_users')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e($user->name); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.update')): ?>
                <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit')); ?>

                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

            </a>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <?php if($user->profile_photo): ?>
                <img src="<?php echo e(asset('uploads/profiles/' . $user->profile_photo)); ?>"
                    class="rounded-circle border border-2 border-white flex-shrink-0"
                    style="width:54px;height:54px;object-fit:cover;"
                    onerror="this.outerHTML='<div class=\'rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center\' style=\'width:54px;height:54px;background:rgba(255,255,255,.2)\'><span class=\'text-white fw-bold fs-5\'><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span></div>'">
            <?php else: ?>
                <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                    style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                    <span class="text-white fw-bold fs-5"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm"><?php echo e($user->name); ?></div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-envelope me-1"></i><?php echo e($user->email); ?></span>
                    <?php if($user->phone): ?>
                        <span>� <?php echo e($user->phone); ?></span>
                    <?php endif; ?>
                    <span>· <?php echo e($user->roles->pluck('name')->implode(', ') ?: __('messages.role')); ?></span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold <?php echo e($user->status === 'active' ? 'text-success' : 'text-secondary'); ?>">
                    <i
                        class="bx <?php echo e($user->status === 'active' ? 'bx-check' : 'bx-x'); ?> me-1"></i><?php echo e(ucfirst($user->status)); ?>

                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-user me-2 text-primary"></i><?php echo e(__('messages.user_details')); ?>

                    </h6>
                    <span class="badge rounded-pill <?php echo e($user->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                        <?php echo e($user->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="flex-shrink-0">
                            <?php if($user->profile_photo): ?>
                                <img src="<?php echo e(asset('uploads/profiles/' . $user->profile_photo)); ?>"
                                    class="rounded-circle shadow-sm"
                                    style="width:80px;height:80px;object-fit:cover;border:3px solid #e0e0e0;"
                                    onerror="imgError(this)">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-label-primary"
                                    style="width:80px;height:80px;">
                                    <span class="fw-bold text-primary" style="font-size:2rem;">
                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1"><?php echo e($user->name); ?></h4>
                            <p class="text-muted mb-1 small"><i class="bx bx-envelope me-1"></i><?php echo e($user->email); ?></p>
                            <?php if($user->phone): ?>
                                <p class="text-muted mb-1 small"><i class="bx bx-phone me-1"></i><?php echo e($user->phone); ?></p>
                            <?php endif; ?>
                            <span class="badge bg-label-primary">
                                <?php echo e($user->roles->pluck('name')->implode(', ') ?: __('messages.role')); ?>

                            </span>
                        </div>
                    </div>

                    
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary">
                                    <?php echo e($user->roles->count()); ?>

                                </div>
                                <div class="text-muted small"><?php echo e(__('messages.th_role')); ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-4 text-success">
                                    <?php echo e($user->last_login_at ? $user->last_login_at->diffForHumans() : '�'); ?>

                                </div>
                                <div class="text-muted small"><?php echo e(__('messages.last_login')); ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-info">
                                <div class="fw-bold fs-4 text-info">
                                    <?php echo e($user->created_at->format('d M Y')); ?>

                                </div>
                                <div class="text-muted small"><?php echo e(__('messages.th_created')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($user->roles->isNotEmpty()): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-shield me-2 text-warning"></i><?php echo e(__('messages.role')); ?> &amp;
                            <?php echo e(__('messages.th_permissions')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3">
                                <h6 class="fw-bold mb-2">
                                    <span class="badge bg-label-primary me-2"><?php echo e($role->name); ?></span>
                                </h6>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $role->permissions->take(20); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-label-secondary small"><?php echo e($perm->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($role->permissions->count() > 20): ?>
                                        <span class="badge bg-label-info">+<?php echo e($role->permissions->count() - 20); ?>

                                            more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-info-circle me-2 text-primary"></i><?php echo e(__('messages.information')); ?>

                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#<?php echo e($user->id); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_name')); ?></span>
                            <span class="fw-bold"><?php echo e($user->name); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.role')); ?></span>
                            <span class="badge bg-label-primary">
                                <?php echo e($user->roles->pluck('name')->implode(', ') ?: '�'); ?>

                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_status')); ?></span>
                            <span class="badge rounded-pill <?php echo e($user->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($user->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_created')); ?></span>
                            <span class="small"><?php echo e($user->created_at->format('d M Y')); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.updated')); ?></span>
                            <span class="small"><?php echo e($user->updated_at->format('d M Y')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-bolt-circle me-2 text-warning"></i><?php echo e(__('messages.quick_actions')); ?>

                    </h6>
                </div>
                <div class="card-body p-4 d-grid gap-2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.update')): ?>
                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit_user')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.delete')): ?>
                        <?php if(auth()->id() !== $user->id): ?>
                            <form id="deleteForm" action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                    data-name="<?php echo e($user->name); ?>">
                                    <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_user')); ?>

                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on('click', '.delete-btn', function() {
            const name = $(this).data('name');
            Swal.fire({
                title: '<?php echo e(__('messages.confirm_delete')); ?>',
                text: `<?php echo e(__('messages.delete')); ?> "${name}"?`,
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/users/show.blade.php ENDPATH**/ ?>