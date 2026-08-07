
<?php $__env->startSection('title', __('messages.menu_currencies')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.menu_currencies')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_currencies')); ?></li>
                </ol>
            </nav>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.create')): ?>
            <a href="<?php echo e(route('currencies.create')); ?>" class="btn btn-outline-primary">
                <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_currency')); ?>

            </a>
        <?php endif; ?>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filters')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('currencies.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="<?php echo e(request('search')); ?>"
                            placeholder="<?php echo e(__('messages.currency_name')); ?>, <?php echo e(__('messages.currency_code')); ?>...">
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
                        <a href="<?php echo e(route('currencies.index')); ?>" class="btn btn-outline-secondary btn-sm"
                            title="<?php echo e(__('messages.reset')); ?>">
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
                    <span class="avatar-initial rounded-circle bg-label-primary flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-dollar"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-primary"><?php echo e($currencies->count()); ?></div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.th_total')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-success flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-success" id="statActiveCount">
                            <?php echo e($currencies->where('status', 'active')->count()); ?></div>
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
                            <?php echo e($currencies->where('status', 'inactive')->count()); ?></div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.inactive')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="avatar-initial rounded-circle bg-label-warning flex-shrink-0"
                        style="width:44px;height:44px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-star"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-warning">
                            <?php echo e($currencies->where('is_default', true)->count()); ?>

                        </div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.th_default')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="currenciesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.currency_name')); ?></th>
                            <th><?php echo e(__('messages.th_code')); ?></th>
                            <th><?php echo e(__('messages.th_symbol')); ?></th>
                            <th><?php echo e(__('messages.th_exchange_rate')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_default')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                            <th><?php echo e(__('messages.th_created')); ?></th>
                            <th class="text-center no-sort"><?php echo e(__('messages.th_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class="bx bx-dollar" style="font-size:1rem;"></i>
                                            </span>
                                        </div>
                                        <strong><?php echo e($currency->name); ?></strong>
                                    </div>
                                </td>
                                <td><code class="text-primary"><?php echo e($currency->code); ?></code></td>
                                <td><span class="fw-semibold fs-5"><?php echo e($currency->symbol); ?></span></td>
                                <td><?php echo e(number_format($currency->exchange_rate, 4)); ?></td>
                                <td class="text-center">
                                    <?php if($currency->is_default): ?>
                                        <span class="badge rounded-pill bg-label-warning fw-semibold px-3 py-1">
                                            <i class="bx bx-star me-1"></i><?php echo e(__('messages.th_default')); ?>

                                        </span>
                                    <?php else: ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.update')): ?>
                                            <button type="button"
                                                class="set-default-btn btn btn-sm btn-outline-secondary rounded-pill"
                                                data-id="<?php echo e($currency->id); ?>" style="font-size:11px;padding:2px 12px;">
                                                <?php echo e(__('messages.set_default')); ?>

                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.update')): ?>
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1 <?php echo e($currency->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;cursor:pointer;" data-id="<?php echo e($currency->id); ?>"
                                            data-status="<?php echo e($currency->status); ?>"
                                            title="<?php echo e(__('messages.click_to_toggle')); ?>">
                                            <?php echo e($currency->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                        </button>
                                    <?php else: ?>
                                        <span
                                            class="badge rounded-pill border fw-semibold px-3 py-1 <?php echo e($currency->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;">
                                            <?php echo e($currency->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?php echo e($currency->created_at->format('d M Y')); ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.view')): ?>
                                            <a href="<?php echo e(route('currencies.show', $currency->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="<?php echo e(__('messages.view')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.update')): ?>
                                            <a href="<?php echo e(route('currencies.edit', $currency->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="<?php echo e(__('messages.edit')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('currencies.delete')): ?>
                                            <form id="delete-form-<?php echo e($currency->id); ?>"
                                                action="<?php echo e(route('currencies.destroy', $currency->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="<?php echo e($currency->id); ?>" data-name="<?php echo e($currency->name); ?>"
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
            $('#currenciesTable').DataTable({
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-dollar" style="font-size:3rem;opacity:.3;line-height:1;"></i><?php echo e(__('messages.no_records')); ?></div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            // Status toggle
            $(document).on('click', '.status-toggle-btn', function() {
                const btn = $(this);
                const id = btn.data('id');
                const currentStatus = btn.data('status');

                $.ajax({
                    url: `/currencies/${id}/toggle-status`,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    beforeSend: function() {
                        btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm"></span>');
                    },
                    success: function(res) {
                        if (res.success) {
                            const newStatus = res.status;
                            btn.data('status', newStatus);
                            if (newStatus === 'active') {
                                btn.removeClass('border-danger text-danger').addClass(
                                    'border-success text-success');
                                btn.text('<?php echo e(__('messages.active')); ?>');
                            } else {
                                btn.removeClass('border-success text-success').addClass(
                                    'border-danger text-danger');
                                btn.text('<?php echo e(__('messages.inactive')); ?>');
                            }
                            showAdminToast(res.message, 'success');
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                            $('#statInactiveCount').text($('.status-toggle-btn.border-danger')
                                .length);
                        } else {
                            showAdminToast(res.message ||
                                '<?php echo e(__('messages.error_occurred')); ?>', 'error');
                        }
                        btn.prop('disabled', false);
                    },
                    error: function() {
                        showAdminToast('<?php echo e(__('messages.error_occurred')); ?>', 'error');
                        btn.prop('disabled', false);
                        btn.text(currentStatus === 'active' ? '<?php echo e(__('messages.active')); ?>' :
                            '<?php echo e(__('messages.inactive')); ?>');
                    }
                });
            });

            // Set default
            $(document).on('click', '.set-default-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: '<?php echo e(__('messages.set_default_q')); ?>',
                    text: '<?php echo e(__('messages.set_default_text')); ?>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#696cff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes')); ?>!',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: `/currencies/${id}/set-default`,
                            type: 'POST',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(res) {
                                if (res.success) {
                                    showAdminToast(
                                        '<?php echo e(__('messages.default_changed')); ?>',
                                        'success');
                                    setTimeout(() => window.location.reload(), 800);
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

            // Delete
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
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        title: '<?php echo e(__('messages.deleted_title')); ?>',
                                        text: res.message,
                                        icon: 'success',
                                        confirmButtonColor: '#696cff'
                                    }).then(() => window.location.reload());
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/currencies/index.blade.php ENDPATH**/ ?>