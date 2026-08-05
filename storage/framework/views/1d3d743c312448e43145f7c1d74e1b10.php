<?php $__env->startSection('title', __('messages.menu_customers')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.menu_customers')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_customers')); ?></li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.create')): ?>
            <div class="d-flex gap-2 align-items-center">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.delete')): ?>
                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                        <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_multiples')); ?>

                    </button>
                <?php endif; ?>
                <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-outline-primary">
                    <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_customer')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filters')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('customers.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.ph_search_customer')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.status')); ?></label>
                        <select name="status" class="form-select form-select-sm">
                            <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.active')); ?></option>
                            <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>
                                <?php echo e(__('messages.inactive')); ?></option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('customers.index')); ?>" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-success flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-group"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-success"><?php echo e($customers->count()); ?></div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.th_total')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-primary flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-primary" id="statActiveCount">
                            <?php echo e($customers->where('status', 'active')->count()); ?>

                        </div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.active')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-danger flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-x-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-danger" id="statInactiveCount">
                            <?php echo e($customers->where('status', 'inactive')->count()); ?>

                        </div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.inactive')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-info flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-receipt"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-info">
                            <?php echo e($customers->sum(fn($c) => $c->sales->count())); ?>

                        </div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.total_sales')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="customersTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.th_name')); ?></th>
                            <th><?php echo e(__('messages.th_phone')); ?></th>
                            <th><?php echo e(__('messages.th_email')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.total_sales')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                            <th><?php echo e(__('messages.th_created')); ?></th>
                            <th class="text-center no-sort"><?php echo e(__('messages.th_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="<?php echo e($customer->id); ?>"></td>
                                <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-success">
                                                <i class="bx bx-user-circle" style="font-size:1rem;"></i>
                                            </span>
                                        </div>
                                        <strong><?php echo e($customer->name); ?></strong>
                                    </div>
                                </td>
                                <td class="fw-semibold"><?php echo e($customer->phone); ?></td>
                                <td class="text-muted"><?php echo e($customer->email ?: '-'); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-label-primary"><?php echo e($customer->sales->count()); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.update')): ?>
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1
                                                <?php echo e($customer->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;cursor:pointer;" data-id="<?php echo e($customer->id); ?>"
                                            data-status="<?php echo e($customer->status); ?>"
                                            title="<?php echo e(__('messages.click_to_toggle')); ?>">
                                            <?php echo e($customer->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                        </button>
                                    <?php else: ?>
                                        <span
                                            class="badge rounded-pill border fw-semibold px-3 py-1
                                            <?php echo e($customer->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;">
                                            <?php echo e($customer->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?php echo e($customer->created_at->format('d M Y')); ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.view')): ?>
                                            <a href="<?php echo e(route('customers.show', $customer->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="<?php echo e(__('messages.view')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.update')): ?>
                                            <a href="<?php echo e(route('customers.edit', $customer->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="<?php echo e(__('messages.edit')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.delete')): ?>
                                            <form id="delete-form-<?php echo e($customer->id); ?>"
                                                action="<?php echo e(route('customers.destroy', $customer->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="<?php echo e($customer->id); ?>" data-name="<?php echo e($customer->name); ?>"
                                                    title="<?php echo e(__('messages.delete')); ?>"
                                                    style="width:30px;height:30px;padding:0;">
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('#customersTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-user-circle" style="font-size:3rem;opacity:.3;line-height:1;"></i><?php echo e(__('messages.no_records')); ?></div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            $(document).on('click', '.status-toggle-btn', function() {
                const btn = $(this),
                    id = btn.data('id'),
                    cur = btn.data('status');
                $.ajax({
                    url: `/customers/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    beforeSend: () => btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span>'),
                    success: (res) => {
                        btn.prop('disabled', false);
                        if (res.success) {
                            btn.data('status', res.status);
                            btn.removeClass(
                                'border-success text-success border-danger text-danger');
                            btn.addClass(res.status === 'active' ?
                                'border-success text-success' : 'border-danger text-danger');
                            btn.text(res.status === 'active' ? '<?php echo e(__('messages.active')); ?>' :
                                '<?php echo e(__('messages.inactive')); ?>');
                            showAdminToast(res.message, 'success');
                            // -- Update stat cards live ------------------
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                            $('#statInactiveCount').text($('.status-toggle-btn.border-danger')
                                .length);
                        } else {
                            btn.text(cur === 'active' ? '<?php echo e(__('messages.active')); ?>' :
                                '<?php echo e(__('messages.inactive')); ?>');
                            showAdminToast(res.message ||
                                '<?php echo e(__('messages.error_occurred')); ?>', 'error');
                        }
                    },
                    error: () => {
                        btn.prop('disabled', false).text(cur === 'active' ?
                            '<?php echo e(__('messages.active')); ?>' :
                            '<?php echo e(__('messages.inactive')); ?>');
                        showAdminToast('<?php echo e(__('messages.error_occurred')); ?>', 'error');
                    }
                });
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
                            url: '<?php echo e(route('customers.bulk-destroy')); ?>',
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/customers/index.blade.php ENDPATH**/ ?>