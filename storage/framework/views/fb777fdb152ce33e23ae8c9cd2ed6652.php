<?php $__env->startSection('title', __('messages.purchase_returns')); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.purchase_returns')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_purchase_returns')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <button type="button" id="toggleFiltersBtn" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-filter-alt"></i> <?php echo e(__('messages.filters')); ?>

                <i id="filtersChevron" class="bx bx-chevron-down"></i>
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_returns.delete')): ?>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-none">
                    <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_multiples')); ?>

                </button>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_returns.create')): ?>
                <a href="<?php echo e(route('purchase-returns.create')); ?>"
                    class="btn btn-outline-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> <?php echo e(__('messages.new_return')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <?php
        use App\Models\PurchaseReturn;
        $totalReturns = PurchaseReturn::count();
        $completedReturns = PurchaseReturn::where('status', 'Completed')->count();
        $pendingReturns = PurchaseReturn::where('status', 'Pending')->count();
        $totalRefunded = PurchaseReturn::where('status', 'Completed')->sum('refunded_amount');
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.th_total')); ?></p>
                        <h4 class="mb-0 fw-bold text-primary"><?php echo e($totalReturns); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-undo"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.completed')); ?></p>
                        <h4 class="mb-0 fw-bold text-success"><?php echo e($completedReturns); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.pending')); ?></p>
                        <h4 class="mb-0 fw-bold text-warning"><?php echo e($pendingReturns); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-warning p-3" style="font-size:1.1rem;">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small"><?php echo e(__('messages.total_refunded')); ?></p>
                        <h4 class="mb-0 fw-bold text-info"><?php echo e(format_currency($totalRefunded)); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div id="filtersCard" class="d-none mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-filter-alt me-2 text-primary"></i><?php echo e(__('messages.filter_pur_returns')); ?>

                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="<?php echo e(route('purchase-returns.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.return_no_label')); ?></label>
                            <input type="text" name="return_no" class="form-control form-control-sm"
                                value="<?php echo e(request('return_no')); ?>" placeholder="<?php echo e(__('messages.ph_purchase_ret_format')); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.supplier')); ?></label>
                            <select name="supplier_id" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_suppliers')); ?></option>
                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>"
                                        <?php echo e(request('supplier_id') == $s->id ? 'selected' : ''); ?>>
                                        <?php echo e($s->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.status')); ?></label>
                            <select name="status" class="form-select form-select-sm">
                                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                <option value="Completed" <?php echo e(request('status') === 'Completed' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.completed')); ?></option>
                                <option value="Pending" <?php echo e(request('status') === 'Pending' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.pending')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.date_from')); ?></label>
                            <input type="date" name="start_date"
                                class="form-control form-control-sm flatpickr-filter-date"
                                value="<?php echo e(request('start_date')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.date_to')); ?></label>
                            <input type="date" name="end_date" class="form-control form-control-sm flatpickr-filter-date"
                                value="<?php echo e(request('end_date')); ?>">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="<?php echo e(route('purchase-returns.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bx bx-reset me-1"></i><?php echo e(__('messages.reset')); ?>

                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i><?php echo e(__('messages.apply')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="purchaseReturnsTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.th_return_no')); ?></th>
                            <th><?php echo e(__('messages.th_date')); ?></th>
                            <th><?php echo e(__('messages.th_purchase_no')); ?></th>
                            <th><?php echo e(__('messages.th_supplier')); ?></th>
                            <th class="text-center">QTY</th>
                            <th class="text-end"><?php echo e(__('messages.th_total')); ?></th>
                            <th class="text-end"><?php echo e(__('messages.th_refunded')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                            <th><?php echo e(__('messages.th_created_by')); ?></th>
                            <th class="text-center no-sort"><?php echo e(__('messages.th_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="<?php echo e($return->id); ?>"></td>
                                <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                <td><code class="fw-bold"><?php echo e($return->return_no); ?></code></td>
                                <td class="text-muted"><?php echo e($return->return_date); ?></td>
                                <td>
                                    <?php if($return->purchase): ?>
                                        <a href="<?php echo e(route('purchases.show', $return->purchase_id)); ?>" class="text-muted">
                                            <code><?php echo e($return->purchase->purchase_no); ?></code>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo e($return->supplier->name ?? '-'); ?></strong></td>
                                <td class="text-center fw-bold text-danger">
                                    <?php echo e($return->items->sum('quantity')); ?>

                                </td>
                                <td class="text-end fw-bold"><?php echo e(format_currency($return->grand_total)); ?></td>
                                <td class="text-end text-success fw-semibold">
                                    <?php echo e(format_currency($return->refunded_amount)); ?></td>
                                <td class="text-center">
                                    <?php if($return->status === 'Completed'): ?>
                                        <span class="badge rounded-pill bg-success"><?php echo e(__('messages.completed')); ?></span>
                                    <?php else: ?>
                                        <span
                                            class="badge rounded-pill bg-warning text-dark"><?php echo e(__('messages.pending')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?php echo e($return->user->name ?? '-'); ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_returns.view')): ?>
                                            <a href="<?php echo e(route('purchase-returns.show', $return->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                title="<?php echo e(__('messages.view')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                            <a href="<?php echo e(route('purchase-returns.print', $return->id)); ?>" target="_blank"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                title="<?php echo e(__('messages.print')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-printer" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_returns.update')): ?>
                                            <a href="<?php echo e(route('purchase-returns.edit', $return->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                title="<?php echo e(__('messages.edit')); ?>" style="width:30px;height:30px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase_returns.delete')): ?>
                                            <form id="delete-form-<?php echo e($return->id); ?>"
                                                action="<?php echo e(route('purchase-returns.destroy', $return->id)); ?>" method="POST"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="<?php echo e($return->id); ?>" data-no="<?php echo e($return->return_no); ?>"
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
            $('#purchaseReturnsTable').DataTable({
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-cart-download" style="font-size:3rem;opacity:.3;line-height:1;"></i><?php echo e(__('messages.no_records')); ?></div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            // Filters toggle ? persist state
            let filtersOpen = localStorage.getItem('pur_returns_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('pur_returns_filters_open', isOpen);
            });

            // Delete with AJAX
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                const no = $(this).data('no');
                const form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: `<?php echo e(__('messages.delete')); ?> "${no}"?`,
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
                    text: 'Stock will be reversed for Completed returns. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<?php echo e(__('messages.yes_delete')); ?>',
                    cancelButtonText: '<?php echo e(__('messages.cancel')); ?>'
                }).then((r) => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('purchase-returns.bulk-destroy')); ?>',
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
                            error: function(xhr) {
                                const msg = xhr.responseJSON?.message ||
                                    '<?php echo e(__('messages.error_occurred')); ?>';
                                showAdminToast(msg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/purchase_returns/index.blade.php ENDPATH**/ ?>