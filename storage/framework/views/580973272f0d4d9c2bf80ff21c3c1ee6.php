
<?php $__env->startSection('title', __('messages.brands')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.brands')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.brands')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.delete')): ?>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_multiples')); ?>

                </button>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.create')): ?>
                <a href="<?php echo e(route('brands.create')); ?>" class="btn btn-outline-primary">
                    <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_brand')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filters')); ?></h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="<?php echo e(route('brands.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small"><?php echo e(__('messages.search')); ?></label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.ph_search_brand')); ?>">
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
                        <a href="<?php echo e(route('brands.index')); ?>" class="btn btn-outline-secondary btn-sm" title="Reset">
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
                        <i class="bx bx-award"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-primary"><?php echo e($brands->count()); ?></div>
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
                            <?php echo e($brands->where('status', 'active')->count()); ?>

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
                            <?php echo e($brands->where('status', 'inactive')->count()); ?>

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
                        <i class="bx bx-package"></i>
                    </span>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-info">
                            <?php echo e($brands->sum(fn($b) => $b->products->count())); ?>

                        </div>
                        <div class="text-muted small mt-1"><?php echo e(__('messages.total_products')); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="brandsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.brand_name')); ?></th>
                            <th><?php echo e(__('messages.th_code')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.total_products')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                            <th><?php echo e(__('messages.th_created')); ?></th>
                            <th class="text-center no-sort"><?php echo e(__('messages.th_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="<?php echo e($brand->id); ?>"></td>
                                <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        
                                        <?php if($brand->image): ?>
                                            <img src="<?php echo e(asset('uploads/brands/' . $brand->image)); ?>"
                                                class="rounded-circle flex-shrink-0"
                                                style="width:40px;height:40px;object-fit:cover;border:2px solid #e7e7ff;"
                                                onerror="this.outerHTML='<div class=\'rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center bg-label-primary\' style=\'width:40px;height:40px;\'><i class=\'bx bx-award\' style=\'font-size:1rem;\'></i></div>'"
                                                alt="<?php echo e($brand->name); ?>">
                                        <?php else: ?>
                                            <div class="avatar avatar-sm flex-shrink-0">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <i class="bx bx-award" style="font-size:1rem;"></i>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo e($brand->name); ?></strong>
                                            <?php if($brand->description): ?>
                                                <small class="d-block text-muted text-truncate"
                                                    style="max-width:200px;"><?php echo e($brand->description); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-primary"><?php echo e($brand->slug); ?></code></td>
                                <td class="text-center">
                                    <span class="badge bg-label-info"><?php echo e($brand->products->count()); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.update')): ?>
                                        <button type="button"
                                            class="status-toggle-btn badge rounded-pill border fw-semibold px-3 py-1
                                                <?php echo e($brand->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;cursor:pointer;" data-id="<?php echo e($brand->id); ?>"
                                            data-status="<?php echo e($brand->status); ?>" title="<?php echo e(__('messages.click_to_toggle')); ?>">
                                            <?php echo e($brand->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                        </button>
                                    <?php else: ?>
                                        <span
                                            class="badge rounded-pill border fw-semibold px-3 py-1
                                            <?php echo e($brand->status === 'active' ? 'border-success text-success' : 'border-danger text-danger'); ?>"
                                            style="background:transparent;">
                                            <?php echo e($brand->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?php echo e($brand->created_at->format('d M Y')); ?></td>
                                <td class="text-center">
                                    <div class="tbl-action-wrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.view')): ?>
                                            <a href="<?php echo e(route('brands.show', $brand->id)); ?>"
                                                class="btn btn-sm btn-outline-info btn-action"
                                                title="<?php echo e(__('messages.view')); ?>"
                                                style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;flex-shrink:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.update')): ?>
                                            <a href="<?php echo e(route('brands.edit', $brand->id)); ?>"
                                                class="btn btn-sm btn-outline-primary btn-action"
                                                title="<?php echo e(__('messages.edit')); ?>"
                                                style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;flex-shrink:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('brands.delete')): ?>
                                            <form id="delete-form-<?php echo e($brand->id); ?>"
                                                action="<?php echo e(route('brands.destroy', $brand->id)); ?>" method="POST"
                                                class="d-inline" style="display:contents;">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-id="<?php echo e($brand->id); ?>" data-name="<?php echo e($brand->name); ?>"
                                                    title="<?php echo e(__('messages.delete')); ?>"
                                                    style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px;flex-shrink:0;">
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
            $('#brandsTable').DataTable({
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-award" style="font-size:3rem;opacity:.3;line-height:1;"></i><?php echo e(__('messages.no_records')); ?></div></div>',
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
                    url: `/brands/${id}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    beforeSend: () => btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span>'),
                    success: (res) => {
                        btn.prop('disabled', false);
                        if (res.success) {
                            btn.data('status', res.status)
                                .removeClass(
                                    'border-success text-success border-danger text-danger')
                                .addClass(res.status === 'active' ?
                                    'border-success text-success' : 'border-danger text-danger')
                                .text(res.status === 'active' ? '<?php echo e(__('messages.active')); ?>' :
                                    '<?php echo e(__('messages.inactive')); ?>');
                            showAdminToast(res.message, 'success');
                            $('#statActiveCount').text($('.status-toggle-btn.border-success')
                                .length);
                            $('#statInactiveCount').text($('.status-toggle-btn.border-danger')
                                .length);
                        } else {
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
                }).then(r => {
                    if (!r.isConfirmed) return;
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: res => {
                            if (res.success) Swal.fire({
                                title: '<?php echo e(__('messages.deleted_title')); ?>',
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#696cff'
                            }).then(() => location.reload());
                            else showAdminToast(res.message, 'error');
                        },
                        error: () => showAdminToast('<?php echo e(__('messages.error_occurred')); ?>',
                            'error')
                    });
                });
            });

            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                toggleBulk();
            });
            $(document).on('change', '.row-checkbox', function() {
                $('#selectAll').prop('checked', !$('.row-checkbox:not(:checked)').length);
                toggleBulk();
            });

            function toggleBulk() {
                const c = $('.row-checkbox:checked').length;
                c > 0 ? $('#bulkDeleteBtn').removeClass('d-none') : $('#bulkDeleteBtn').addClass('d-none');
            }

            $('#bulkDeleteBtn').on('click', function() {
                const ids = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (!ids.length) return;
                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: `<?php echo e(__('messages.bulk_delete_items')); ?>`.replace(':count', ids.length),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then(r => {
                    if (!r.isConfirmed) return;
                    $.ajax({
                        url: '<?php echo e(route('brands.bulk-destroy')); ?>',
                        type: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            ids: ids
                        },
                        success: res => {
                            if (res.success) Swal.fire({
                                title: '<?php echo e(__('messages.deleted_title')); ?>',
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#696cff'
                            }).then(() => location.reload());
                            else showAdminToast(res.message, 'error');
                        },
                        error: () => showAdminToast('<?php echo e(__('messages.error_occurred')); ?>',
                            'error')
                    });
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/brands/index.blade.php ENDPATH**/ ?>