<?php $__env->startSection('title', __('messages.supplier_details') . ' � ' . $supplier->name); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.supplier_details')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo e(route('suppliers.index')); ?>"><?php echo e(__('messages.menu_suppliers')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e($supplier->name); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('suppliers.update')): ?>
                <a class="btn btn-primary" href="<?php echo e(route('suppliers.edit', $supplier->id)); ?>">
                    <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit')); ?>

                </a>
            <?php endif; ?>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('suppliers.index')); ?>">
                <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

            </a>
        </div>
    </div>

    
    <div class="card mb-4 border-0 shadow-sm" style="background:linear-gradient(135deg,#696cff,#9c3fe4);">
        <div class="card-body d-flex align-items-center flex-wrap gap-3 px-4 py-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 border border-2 border-white"
                style="width:54px;height:54px;background:rgba(255,255,255,.2)">
                <i class="bx bx-truck fs-4 text-white"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold fs-6 lh-sm text-white"><?php echo e($supplier->name); ?></div>
                <div class="small d-flex mt-1 flex-wrap gap-2 text-white opacity-75">
                    <?php if($supplier->company_name): ?>
                        <span><i class="bx bx-buildings me-1"></i><?php echo e($supplier->company_name); ?></span>
                    <?php endif; ?>
                    <span><i class="bx bx-phone me-1"></i><?php echo e($supplier->phone); ?></span>
                    <span>� <?php echo e($supplier->purchases->count()); ?> <?php echo e(__('messages.total_purchases')); ?></span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span
                    class="badge fw-semibold <?php echo e($supplier->status === 'active' ? 'text-success' : 'text-secondary'); ?> bg-white">
                    <i
                        class="bx <?php echo e($supplier->status === 'active' ? 'bx-check' : 'bx-x'); ?> me-1"></i><?php echo e(ucfirst($supplier->status)); ?>

                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-lg-8">

            
            <div class="card mb-4 shadow-sm">
                <div
                    class="card-header border-bottom d-flex justify-content-between align-items-center bg-transparent py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-store text-primary me-2"></i><?php echo e(__('messages.supplier_details')); ?>

                    </h6>
                    <span class="badge rounded-pill <?php echo e($supplier->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                        <?php echo e($supplier->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 gap-4">
                        <div class="avatar flex-shrink-0" style="width:72px;height:72px;">
                            <span
                                class="avatar-initial rounded-circle bg-label-warning w-100 h-100 d-flex align-items-center justify-content-center"
                                style="font-size:2rem;">
                                <i class="bx bx-store"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1"><?php echo e($supplier->name); ?></h4>
                            <?php if($supplier->company_name): ?>
                                <p class="text-muted mb-1"><i
                                        class="bx bx-buildings me-1"></i><?php echo e($supplier->company_name); ?></p>
                            <?php endif; ?>
                            <?php if($supplier->contact_person): ?>
                                <p class="text-muted small mb-0"><i
                                        class="bx bx-user me-1"></i><?php echo e($supplier->contact_person); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="rounded-3 bg-label-info p-3 text-center">
                                <div class="fw-bold fs-4 text-info"><?php echo e($supplier->purchases->count()); ?></div>
                                <div class="text-muted small"><?php echo e(__('messages.total_purchases')); ?></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 bg-label-warning p-3 text-center">
                                <div class="fw-bold fs-4 text-warning"><?php echo e($supplier->purchaseReturns->count()); ?></div>
                                <div class="text-muted small"><?php echo e(__('messages.purchase_returns')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-transparent py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-phone text-success me-2"></i><?php echo e(__('messages.contact_details')); ?>

                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.ph_phone')); ?></span>
                            <span class="fw-semibold"><?php echo e($supplier->phone); ?></span>
                        </li>
                        <?php if($supplier->alt_phone): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold"><?php echo e(__('messages.alt_phone')); ?></span>
                                <span><?php echo e($supplier->alt_phone); ?></span>
                            </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.email_address')); ?></span>
                            <span><?php echo e($supplier->email ?: '�'); ?></span>
                        </li>
                        <?php if($supplier->gst_number): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold"><?php echo e(__('messages.gst_number')); ?></span>
                                <code><?php echo e($supplier->gst_number); ?></code>
                            </li>
                        <?php endif; ?>
                        <?php if($supplier->pan_number): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted small fw-semibold"><?php echo e(__('messages.pan_number')); ?></span>
                                <code><?php echo e($supplier->pan_number); ?></code>
                            </li>
                        <?php endif; ?>
                        <?php if($supplier->address || $supplier->city): ?>
                            <li class="list-group-item px-4 py-3">
                                <span
                                    class="text-muted small fw-semibold d-block mb-1"><?php echo e(__('messages.address_label')); ?></span>
                                <p class="small mb-0">
                                    <?php echo e(implode(', ', array_filter([$supplier->address, $supplier->city, $supplier->state, $supplier->pincode, $supplier->country])) ?: '�'); ?>

                                </p>
                            </li>
                        <?php endif; ?>
                        <?php if($supplier->notes): ?>
                            <li class="list-group-item px-4 py-3">
                                <span class="text-muted small fw-semibold d-block mb-1"><?php echo e(__('messages.notes')); ?></span>
                                <p class="small text-muted mb-0"><?php echo e($supplier->notes); ?></p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            
            <div class="card shadow-sm">
                <div
                    class="card-header border-bottom d-flex justify-content-between align-items-center bg-transparent py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-receipt text-info me-2"></i><?php echo e(__('messages.recent_purchases')); ?>

                        <span class="badge bg-label-info ms-1"><?php echo e($supplier->purchases->count()); ?></span>
                    </h6>
                    <a class="btn btn-sm btn-outline-info"
                        href="<?php echo e(route('purchases.index')); ?>"><?php echo e(__('messages.view_all')); ?></a>
                </div>
                <div class="card-body p-0">
                    <?php $recentPurchases = $supplier->purchases->sortByDesc('created_at')->take(5); ?>
                    <?php if($recentPurchases->count()): ?>
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4"><?php echo e(__('messages.th_purchase_no')); ?></th>
                                        <th><?php echo e(__('messages.th_date')); ?></th>
                                        <th class="text-end"><?php echo e(__('messages.th_total')); ?></th>
                                        <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentPurchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <a class="fw-semibold text-info"
                                                    href="<?php echo e(route('purchases.show', $p->id)); ?>">
                                                    <code><?php echo e($p->purchase_no); ?></code>
                                                </a>
                                            </td>
                                            <td class="small text-muted"><?php echo e($p->purchase_date); ?></td>
                                            <td class="fw-bold text-end"><?php echo e(format_currency($p->grand_total)); ?></td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill <?php echo e($p->status === 'Completed' ? 'bg-success' : ($p->status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger')); ?>">
                                                    <?php echo e($p->status); ?>

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

            
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom bg-transparent py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-info-circle text-primary me-2"></i><?php echo e(__('messages.information')); ?>

                    </h6>
                </div>
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold">ID</span>
                            <span class="fw-bold">#<?php echo e($supplier->id); ?></span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.supplier_name')); ?></span>
                            <span class="fw-bold"><?php echo e($supplier->name); ?></span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_status')); ?></span>
                            <span
                                class="badge rounded-pill <?php echo e($supplier->status === 'active' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($supplier->status === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                            </span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.total_purchases')); ?></span>
                            <span class="badge bg-label-info"><?php echo e($supplier->purchases->count()); ?></span>
                        </li>
                        <li class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.th_created')); ?></span>
                            <span class="small"><?php echo e($supplier->created_at->format('d M Y')); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted small fw-semibold"><?php echo e(__('messages.updated')); ?></span>
                            <span class="small"><?php echo e($supplier->updated_at->format('d M Y')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            
            <div class="card shadow-sm">
                <div class="card-header border-bottom bg-transparent py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-bolt-circle text-warning me-2"></i><?php echo e(__('messages.quick_actions')); ?>

                    </h6>
                </div>
                <div class="card-body d-grid gap-2 p-4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('suppliers.update')): ?>
                        <a class="btn btn-primary" href="<?php echo e(route('suppliers.edit', $supplier->id)); ?>">
                            <i class="bx bx-edit me-1"></i> <?php echo e(__('messages.edit_supplier')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchases.create')): ?>
                        <a class="btn btn-outline-info" href="<?php echo e(route('purchases.create')); ?>">
                            <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.add_purchase')); ?>

                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('suppliers.delete')): ?>
                        <form action="<?php echo e(route('suppliers.destroy', $supplier->id)); ?>" id="deleteForm" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-outline-danger w-100 delete-btn" data-name="<?php echo e($supplier->name); ?>"
                                type="button">
                                <i class="bx bx-trash me-1"></i> <?php echo e(__('messages.delete_supplier')); ?>

                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </d iv>

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




<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/suppliers/show.blade.php ENDPATH**/ ?>