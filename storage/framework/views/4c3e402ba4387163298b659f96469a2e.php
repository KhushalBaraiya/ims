
<?php $__env->startSection('title', __('messages.permission_management')); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.permission_management')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_permissions')); ?></li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.create')): ?>
            <div class="d-flex align-items-center gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.delete')): ?>
                    <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button">
                        <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_multiples')); ?>

                    </button>
                <?php endif; ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('permissions.create')); ?>">
                    <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_permission')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filters')); ?></h6>
            <?php if($paginatedPermissions): ?>
                <span class="badge bg-label-primary">Total: <?php echo e($paginatedPermissions->total()); ?> Permissions</span>
            <?php endif; ?>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('permissions.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.ph_search_permission')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.module_label')); ?></label>
                        <select name="module" class="form-select form-select-sm">
                            <option value="">-- <?php echo e(__('messages.all')); ?> --</option>
                            <?php $__currentLoopData = $allModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($mod); ?>" <?php if(request('module') === $mod): echo 'selected'; endif; ?>>
                                    <?php echo e(ucwords(str_replace('_', ' ', $mod))); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Per Page</label>
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="5" <?php if(request('per_page') == '5'): echo 'selected'; endif; ?>>5</option>
                            <option value="10" <?php if(request('per_page', 10) == '10'): echo 'selected'; endif; ?>>10</option>
                            <option value="15" <?php if(request('per_page') == '15'): echo 'selected'; endif; ?>>15</option>
                            <option value="25" <?php if(request('per_page') == '25'): echo 'selected'; endif; ?>>25</option>
                            <option value="50" <?php if(request('per_page') == '50'): echo 'selected'; endif; ?>>50</option>
                            <option value="100" <?php if(request('per_page') == '100'): echo 'selected'; endif; ?>>100</option>
                            <option value="all" <?php if(request('per_page') == 'all'): echo 'selected'; endif; ?>>All</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('permissions.index')); ?>" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-cube me-2 text-primary"></i>
                    <?php echo e(ucwords(str_replace('_', ' ', $module))); ?>

                    <span class="badge bg-label-primary ms-1"><?php echo e(count($perms)); ?></span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 permission-table" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px"><input class="form-check-input module-select-all" type="checkbox"
                                        data-module="<?php echo e($module); ?>"></th>
                                <th><?php echo e(__('messages.permission_name')); ?></th>
                                <th><?php echo e(__('messages.th_actions_label')); ?></th>
                                <th><?php echo e(__('messages.roles_count')); ?></th>
                                <th><?php echo e(__('messages.th_created')); ?></th>
                                <th class="text-center no-sort"><?php echo e(__('messages.th_actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    [$mod, $act] = str_contains($permission->name, '.')
                                        ? explode('.', $permission->name, 2)
                                        : [$permission->name, '—'];
                                    $badgeColor = match ($act) {
                                        'view' => 'info',
                                        'create' => 'success',
                                        'update' => 'warning',
                                        'delete' => 'danger',
                                        'own' => 'secondary',
                                        default => 'primary',
                                    };
                                ?>
                                <tr>
                                    <td><input class="form-check-input row-checkbox" type="checkbox"
                                            value="<?php echo e($permission->id); ?>"></td>
                                    <td>
                                        <code class="small fw-semibold text-primary"><?php echo e($permission->name); ?></code>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-<?php echo e($badgeColor); ?>"><?php echo e(ucfirst($act)); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-warning">
                                            <i class="bx bx-shield me-1"></i><?php echo e($permission->roles()->count()); ?>

                                        </span>
                                    </td>
                                    <td class="text-muted small"><?php echo e($permission->created_at->format('d M Y')); ?></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.view')): ?>
                                                <a class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                    href="<?php echo e(route('permissions.show', $permission->id)); ?>"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="<?php echo e(__('messages.view')); ?>">
                                                    <i class="bx bx-show" style="font-size:1rem;"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.update')): ?>
                                                <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                    href="<?php echo e(route('permissions.edit', $permission->id)); ?>"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="<?php echo e(__('messages.edit')); ?>">
                                                    <i class="bx bx-edit" style="font-size:1rem;"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.delete')): ?>
                                                <form action="<?php echo e(route('permissions.destroy', $permission->id)); ?>"
                                                    class="d-inline" id="delete-form-<?php echo e($permission->id); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="<?php echo e($permission->id); ?>" data-name="<?php echo e($permission->name); ?>"
                                                        style="width:30px;height:30px;padding:0;"
                                                        title="<?php echo e(__('messages.delete')); ?>" type="button">
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
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bx bx-key" style="font-size:3rem;opacity:.3;"></i>
                <p class="mt-2 mb-0"><?php echo e(__('messages.no_records')); ?></p>
            </div>
    <?php endif; ?>

    
    <?php if(isset($paginatedPermissions) && $paginatedPermissions && $paginatedPermissions->hasPages()): ?>
        <div class="card shadow-sm mt-4">
            <div class="card-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="text-muted small">
                    Showing <strong><?php echo e($paginatedPermissions->firstItem()); ?></strong> to
                    <strong><?php echo e($paginatedPermissions->lastItem()); ?></strong> of
                    <strong><?php echo e($paginatedPermissions->total()); ?></strong> permissions
                </div>
                <div>
                    <?php echo e($paginatedPermissions->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {

            // -- Delete single
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name');
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
                    if (r.isConfirmed) {
                        $.ajax({
                            url: $(`#delete-form-${id}`).attr('action'),
                            type: 'POST',
                            data: $(`#delete-form-${id}`).serialize(),
                            success: (res) => {
                                if (res.success) Swal.fire({
                                    title: '<?php echo e(__('messages.deleted_title')); ?>',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => location.reload());
                                else showAdminToast(res.message, 'error');
                            },
                            error: () => showAdminToast(
                                '<?php echo e(__('messages.error_occurred')); ?>', 'error')
                        });
                    }
                });
            });

            // -- Module select-all checkbox
            $(document).on('change', '.module-select-all', function() {
                const $table = $(this).closest('table');
                $table.find('.row-checkbox').prop('checked', this.checked);
                syncBulkBtn();
            });

            // -- Individual row checkboxes
            $(document).on('change', '.row-checkbox', function() {
                syncBulkBtn();
            });

            function syncBulkBtn() {
                const count = $('.row-checkbox:checked').length;
                count > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            // -- Bulk Delete
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: ids.length + ' <?php echo e(__('messages.permissions')); ?>?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('permissions.bulk-destroy')); ?>',
                            type: 'DELETE',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>',
                                ids: ids
                            },
                            success: (res) => {
                                if (res.success) Swal.fire({
                                    title: '<?php echo e(__('messages.deleted_title')); ?>',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonColor: '#696cff'
                                }).then(() => location.reload());
                                else showAdminToast(res.message, 'error');
                            },
                            error: () => showAdminToast(
                                '<?php echo e(__('messages.error_occurred')); ?>', 'error')
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/permissions/index.blade.php ENDPATH**/ ?>