<?php $__env->startSection('title', __('messages.role_management')); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.role_management')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_roles')); ?></li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.create')): ?>
            <div class="d-flex align-items-center gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.delete')): ?>
                    <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button">
                        <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_multiples')); ?>

                    </button>
                <?php endif; ?>
                <a class="btn btn-outline-primary" href="<?php echo e(route('roles.create')); ?>">
                    <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_role')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filters')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('roles.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?>

                            <?php echo e(__('messages.role')); ?></label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.ph_search_role')); ?>">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-hover mb-0 table align-middle" id="rolesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input class="form-check-input" id="selectAll" type="checkbox"></th>
                            <th><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.role')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.permissions')); ?></th>
                            <th><?php echo e(__('messages.th_created')); ?></th>
                            <th class="no-sort text-center"><?php echo e(__('messages.th_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><input class="form-check-input row-checkbox" type="checkbox"
                                        value="<?php echo e($role->id); ?>"></td>
                                <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-warning">
                                                <i class="bx bx-shield" style="font-size:1rem;"></i>
                                            </span>
                                        </div>
                                        <strong><?php echo e($role->name); ?></strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-primary">
                                        <i class="bx bx-key me-1"></i><?php echo e($role->permissions_count); ?>

                                        <?php echo e(__('messages.permissions')); ?>

                                    </span>
                                </td>
                                <td class="text-muted small"><?php echo e($role->created_at->format('d M Y')); ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.view')): ?>
                                            <a class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                href="<?php echo e(route('roles.show', $role->id)); ?>"
                                                style="width:30px;height:30px;padding:0;" title="<?php echo e(__('messages.view')); ?>">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.update')): ?>
                                            <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                href="<?php echo e(route('roles.edit', $role->id)); ?>"
                                                style="width:30px;height:30px;padding:0;" title="<?php echo e(__('messages.edit')); ?>">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.delete')): ?>
                                            <?php if($role->name !== 'super_admin'): ?>
                                                <form action="<?php echo e(route('roles.destroy', $role->id)); ?>" class="d-inline"
                                                    id="delete-form-<?php echo e($role->id); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                        data-id="<?php echo e($role->id); ?>" data-name="<?php echo e($role->name); ?>"
                                                        style="width:30px;height:30px;padding:0;"
                                                        title="<?php echo e(__('messages.delete')); ?>" type="button">
                                                        <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="text-muted py-5 text-center" colspan="5">
                                    <i class="bx bx-shield" style="font-size:2.5rem;opacity:.3;"></i>
                                    <p class="mb-0 mt-2"><?php echo e(__('messages.no_records')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                responsive: true,
                pageLength: 10,
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-shield" style="font-size:3rem;opacity:.3;line-height:1;"></i><?php echo e(__('messages.no_records')); ?></div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name'),
                    form = $(`#delete-form-${id}`);
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
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
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

            // -- Bulk Select ----------------------------------------------
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleBulkBtn();
            });
            $(document).on('change', '.row-checkbox', function() {
                $('#selectAll').prop('checked', $('.row-checkbox:not(:checked)').length === 0);
                toggleBulkBtn();
            });

            function toggleBulkBtn() {
                const count = $('.row-checkbox:checked').length;
                count > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            // -- Bulk Delete ----------------------------------------------
            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: '<?php echo e(__('messages.confirm_delete')); ?>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('roles.bulk-destroy')); ?>',
                            type: 'DELETE',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>',
                                ids: ids
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                            title: '<?php echo e(__('messages.deleted_title')); ?>',
                                            text: res.message,
                                            icon: 'success',
                                            confirmButtonColor: '#696cff'
                                        })
                                        .then(() => window.location.reload());
                                } else {
                                    showAdminToast(res.message, 'error');
                                }
                            },
                            error: function() {
                                showAdminToast('<?php echo e(__('messages.error_occurred')); ?>',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/roles/index.blade.php ENDPATH**/ ?>