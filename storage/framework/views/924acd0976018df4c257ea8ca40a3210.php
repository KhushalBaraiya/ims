<?php $__env->startSection('title', __('messages.customer_details') . ' — ' . $customer->name); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.customer_details')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo e(route('customers.index')); ?>"><?php echo e(__('messages.menu_customers')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e($customer->name); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.update')): ?>
                <a href="<?php echo e(route('customers.edit', $customer->id)); ?>" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit')); ?>

                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('customers.index')); ?>" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

            </a>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body py-3 px-4 d-flex align-items-center gap-3 flex-wrap">
            <div class="rounded-circle border border-2 border-white flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-user-circle text-white fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-white fw-bold fs-6 lh-sm"><?php echo e($customer->name); ?></div>
                <div class="text-white opacity-75 small d-flex flex-wrap gap-2 mt-1">
                    <span><i class="bx bx-phone me-1"></i><?php echo e($customer->phone); ?></span>
                    <?php if($customer->email): ?>
                        <span>· <?php echo e($customer->email); ?></span>
                    <?php endif; ?>
                    <span>· <?php echo e($customer->sales->count()); ?> <?php echo e(__('messages.total_sales')); ?></span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span
                    class="badge bg-white fw-semibold <?php echo e($customer->status === 'active' ? 'text-success' : 'text-secondary'); ?>">
                    <i
                        class="bx <?php echo e($customer->status === 'active' ? 'bx-check' : 'bx-x'); ?> me-1"></i><?php echo e(ucfirst($customer->status)); ?>

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
                        <i class="bx bx-user-circle me-2 text-primary"></i><?php echo e(__('messages.customer_details')); ?>

                    </h6>
                    <span class="badge rounded-pill <?php echo e($customer->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                        <?php echo e($customer->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="avatar flex-shrink-0" style="width:72px;height:72px;">
                            <span
                                class="avatar-initial rounded-circle bg-label-success w-100 h-100 d-flex align-items-center justify-content-center"
                                style="font-size:2rem;">
                                <i class="bx bx-user-circle"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1"><?php echo e($customer->name); ?></h4>
                            <p class="text-muted mb-1"><i class="bx bx-phone me-1"></i><?php echo e($customer->phone); ?></p>
                            <?php if($customer->email): ?>
                                <p class="text-muted small mb-0"><i class="bx bx-envelope me-1"></i><?php echo e($customer->email); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-primary">
                                <div class="fw-bold fs-4 text-primary"><?php echo e($customer->sales->count()); ?></div>
                                <div class="text-muted small"><?php echo e(__('messages.total_sales')); ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-warning">
                                <div class="fw-bold fs-4 text-warning"><?php echo e($customer->saleReturns->count()); ?></div>
                                <div class="text-muted small"><?php echo e(__('messages.sale_returns')); ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-3 text-center bg-label-success">
                                <div class="fw-bold fs-4 text-success">
                                    <?php echo e(format_currency($customer->opening_balance ?? 0)); ?></div>
                                <div class="text-muted small"><?php echo e(__('messages.opening_balance')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-phone me-2 text-success"></i><?php echo e(__('messages.contact_details')); ?>

                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.ph_phone')); ?></span>
                            <span class="fw-semibold"><?php echo e($customer->phone); ?></span>
                        </li>
                        <?php if($customer->alt_phone): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold"><?php echo e(__('messages.alt_phone')); ?></span>
                                <span><?php echo e($customer->alt_phone); ?></span>
                            </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.email_address')); ?></span>
                            <span><?php echo e($customer->email ?: '—'); ?></span>
                        </li>
                        <?php if($customer->gst_number): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold"><?php echo e(__('messages.gst_number')); ?></span>
                                <code><?php echo e($customer->gst_number); ?></code>
                            </li>
                        <?php endif; ?>
                        <?php if($customer->address || $customer->city): ?>
                            <li class="list-group-item px-4 py-3">
                                <span
                                    class="text-muted small fw-semibold d-block mb-1"><?php echo e(__('messages.address_label')); ?></span>
                                <p class="mb-0 small">
                                    <?php echo e(implode(', ', array_filter([$customer->address, $customer->city, $customer->state, $customer->pincode, $customer->country])) ?: '—'); ?>

                                </p>
                            </li>
                        <?php endif; ?>
                        <?php if($customer->notes): ?>
                            <li class="list-group-item px-4 py-3">
                                <span class="text-muted small fw-semibold d-block mb-1"><?php echo e(__('messages.notes')); ?></span>
                                <p class="mb-0 small text-muted"><?php echo e($customer->notes); ?></p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            
            <div class="card shadow-sm">
                <div
                    class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-receipt me-2 text-primary"></i><?php echo e(__('messages.recent_sales')); ?>

                        <span class="badge bg-label-primary ms-1"><?php echo e($customer->sales->count()); ?></span>
                    </h6>
                    <a href="<?php echo e(route('sales.index')); ?>"
                        class="btn btn-sm btn-outline-primary"><?php echo e(__('messages.view_all')); ?></a>
                </div>
                <div class="card-body p-0">
                    <?php $recentSales = $customer->sales->sortByDesc('created_at')->take(5); ?>
                    <?php if($recentSales->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4"><?php echo e(__('messages.th_invoice')); ?></th>
                                        <th><?php echo e(__('messages.th_date')); ?></th>
                                        <th class="text-end"><?php echo e(__('messages.th_total')); ?></th>
                                        <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <a href="<?php echo e(route('sales.show', $s->id)); ?>"
                                                    class="fw-semibold text-primary">
                                                    <code><?php echo e($s->invoice_no); ?></code>
                                                </a>
                                            </td>
                                            <td class="small text-muted"><?php echo e($s->invoice_date); ?></td>
                                            <td class="text-end fw-bold"><?php echo e(format_currency($s->grand_total)); ?></td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill <?php echo e($s->status === 'Completed' ? 'bg-success' : ($s->status === 'Draft' ? 'bg-warning text-dark' : 'bg-danger')); ?>">
                                                    <?php echo e($s->status); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="width:100%;text-align:center;padding:2.5rem 0;">
                            <div class="text-muted"
                                style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;">
                                <i class="bx bx-receipt" style="font-size:3rem;opacity:.3;line-height:1;"></i>
                                <span class="small"><?php echo e(__('messages.no_records')); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

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
                            <span class="fw-bold">#<?php echo e($customer->id); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.customer_name')); ?></span>
                            <span class="fw-bold"><?php echo e($customer->name); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_status')); ?></span>
                            <span
                                class="badge rounded-pill <?php echo e($customer->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($customer->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                            </span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.total_sales')); ?></span>
                            <span class="badge bg-label-primary"><?php echo e($customer->sales->count()); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_created')); ?></span>
                            <span class="small"><?php echo e($customer->created_at->format('d M Y')); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.updated')); ?></span>
                            <span class="small"><?php echo e($customer->updated_at->format('d M Y')); ?></span>
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
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.update')): ?>
                        <a href="<?php echo e(route('customers.edit', $customer->id)); ?>" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit_customer')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.create')): ?>
                        <a href="<?php echo e(route('sales.create')); ?>" class="btn btn-outline-primary">
                            <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_sale')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customers.delete')): ?>
                        <form id="deleteForm" action="<?php echo e(route('customers.destroy', $customer->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="button" class="btn btn-outline-danger w-100 delete-btn"
                                data-name="<?php echo e($customer->name); ?>">
                                <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_customer')); ?>

                            </button>
                        </form>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/customers/show.blade.php ENDPATH**/ ?>