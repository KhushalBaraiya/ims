
<?php $__env->startSection('title', __('messages.sales_invoices')); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.sales_invoices')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_sales')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-1" id="toggleFiltersBtn" type="button">
                <i class="bx bx-filter-alt"></i> <?php echo e(__('messages.filters')); ?>

                <i class="bx bx-chevron-down" id="filtersChevron"></i>
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.delete')): ?>
                <button class="btn btn-danger d-none" id="bulkDeleteBtn" type="button"><i class="bx bx-trash me-1"></i>
                    <?php echo e(__('messages.delete_multiples')); ?>

                </button>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.create')): ?>
                <a class="btn btn-outline-primary d-flex align-items-center gap-1" href="<?php echo e(route('sales.create')); ?>">
                    <i class="bx bx-plus"></i> <?php echo e(__('messages.add_sale')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <?php
        use App\Models\Sale;
        $totalSales = Sale::count();
        $completedSales = Sale::where('status', 'Completed')->count();
        $draftSales = Sale::where('status', 'Draft')->count();
        $totalSaleAmount = Sale::where('status', 'Completed')->sum('grand_total');
        $totalSaleDue = Sale::where('status', 'Completed')->sum('due_amount');
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.th_total')); ?></p>
                        <h4 class="fw-bold text-primary mb-0"><?php echo e($totalSales); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.1rem;">
                        <i class="bx bx-receipt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.completed')); ?></p>
                        <h4 class="fw-bold text-success mb-0"><?php echo e($completedSales); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.1rem;">
                        <i class="bx bx-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.total_revenue')); ?></p>
                        <h4 class="fw-bold text-info mb-0"><?php echo e(format_currency($totalSaleAmount)); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.1rem;">
                        <i class="bx bx-rupee"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <p class="text-muted small mb-0"><?php echo e(__('messages.total_due')); ?></p>
                        <h4 class="fw-bold <?php echo e($totalSaleDue > 0 ? 'text-danger' : 'text-success'); ?> mb-0">
                            <?php echo e(format_currency($totalSaleDue)); ?></h4>
                    </div>
                    <span class="avatar-initial rounded-circle bg-label-danger p-3" style="font-size:1.1rem;">
                        <i class="bx bx-time-five"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none mb-4" id="filtersCard">
        <div class="card shadow-sm">
            <div class="card-header border-bottom bg-white py-3">
                <h6 class="fw-semibold mb-0"><i
                        class="bx bx-filter-alt text-primary me-2"></i><?php echo e(__('messages.filter_sales')); ?></h6>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo e(route('sales.index')); ?>" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.invoice_no_label')); ?></label>
                            <input class="form-control form-control-sm" name="invoice_no"
                                placeholder="<?php echo e(__('messages.ph_invoice_no_format')); ?>" type="text"
                                value="<?php echo e(request('invoice_no')); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.customer')); ?></label>
                            <select class="form-select form-select-sm" name="customer_id">
                                <option value=""><?php echo e(__('messages.all_customers')); ?></option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e(request('customer_id') == $c->id ? 'selected' : ''); ?>

                                        value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.status')); ?></label>
                            <select class="form-select form-select-sm" name="status">
                                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                <?php $__currentLoopData = ['Completed', 'Pending', 'Draft', 'Repair', 'Ordered']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusOpt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e(request('status') === $statusOpt ? 'selected' : ''); ?>

                                        value="<?php echo e($statusOpt); ?>">
                                        <?php echo e(__('messages.' . strtolower($statusOpt))); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.payment_status_label')); ?></label>
                            <select class="form-select form-select-sm" name="payment_status">
                                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                <option <?php echo e(request('payment_status') === 'Unpaid' ? 'selected' : ''); ?> value="Unpaid">
                                    <?php echo e(__('messages.unpaid')); ?></option>
                                <option <?php echo e(request('payment_status') === 'Partial' ? 'selected' : ''); ?> value="Partial">
                                    <?php echo e(__('messages.partial')); ?></option>
                                <option <?php echo e(request('payment_status') === 'Paid' ? 'selected' : ''); ?> value="Paid">
                                    <?php echo e(__('messages.paid')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.date_from')); ?></label>
                            <input class="form-control form-control-sm flatpickr-filter-date" name="start_date"
                                type="date" value="<?php echo e(request('start_date')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small"><?php echo e(__('messages.date_to')); ?></label>
                            <input class="form-control form-control-sm flatpickr-filter-date" name="end_date"
                                type="date" value="<?php echo e(request('end_date')); ?>">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a class="btn btn-outline-secondary" href="<?php echo e(route('sales.index')); ?>"><i
                                class="bx bx-reset me-1"></i><?php echo e(__('messages.reset')); ?></a>
                        <button class="btn btn-primary" type="submit"><i class="bx bx-search me-1"></i>
                            <?php echo e(__('messages.apply')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table-hover mb-0 table align-middle" id="salesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px"><input class="form-check-input" id="selectAll" type="checkbox"></th>
                            <th><?php echo e(__('messages.th_no')); ?></th>
                            <th><?php echo e(__('messages.th_invoice')); ?></th>
                            <th><?php echo e(__('messages.th_date')); ?></th>
                            <th><?php echo e(__('messages.th_customer')); ?></th>
                            <th><?php echo e(__('messages.th_items')); ?></th>
                            <th class="text-end"><?php echo e(__('messages.th_total')); ?></th>
                            <th class="text-end"><?php echo e(__('messages.th_paid')); ?></th>
                            <th class="text-end"><?php echo e(__('messages.th_due')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.th_status')); ?></th>
                            <th class="text-center"><?php echo e(__('messages.payment_label')); ?></th>
                            <th><?php echo e(__('messages.th_created_by')); ?></th>
                            <th class="no-sort text-center"><?php echo e(__('messages.th_actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input class="form-check-input row-checkbox" type="checkbox"
                                        value="<?php echo e($sale->id); ?>"></td>
                                <td class="text-muted fw-semibold"><?php echo e($index + 1); ?></td>
                                <td><code class="fw-bold"><?php echo e($sale->invoice_no); ?></code></td>
                                <td class="text-muted"><?php echo e($sale->invoice_date); ?></td>
                                <td><strong><?php echo e($sale->customer->name); ?></strong></td>
                                <td class="text-muted"><?php echo e($sale->items->count()); ?> <?php echo e(__('messages.items_count')); ?></td>
                                <td class="fw-bold text-end"><?php echo e(format_currency($sale->grand_total)); ?></td>
                                <td class="text-success fw-semibold text-end"><?php echo e(format_currency($sale->paid_amount)); ?>

                                </td>
                                <td class="text-danger fw-semibold text-end"><?php echo e(format_currency($sale->due_amount)); ?></td>
                                <td class="text-center">
                                    <?php if($sale->status === 'Completed'): ?>
                                        <span class="badge rounded-pill bg-success"><?php echo e(__('messages.completed')); ?></span>
                                    <?php elseif($sale->status === 'Pending'): ?>
                                        <span
                                            class="badge rounded-pill bg-info text-dark"><?php echo e(__('messages.pending')); ?></span>
                                    <?php elseif($sale->status === 'Draft'): ?>
                                        <span
                                            class="badge rounded-pill bg-warning text-dark"><?php echo e(__('messages.draft')); ?></span>
                                    <?php elseif($sale->status === 'Repair'): ?>
                                        <span class="badge rounded-pill bg-secondary"><?php echo e(__('messages.repair')); ?></span>
                                    <?php elseif($sale->status === 'Ordered'): ?>
                                        <span
                                            class="badge rounded-pill bg-primary"><?php echo e(__('messages.ordered_badge')); ?></span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary"><?php echo e($sale->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($sale->payment_status === 'Paid'): ?>
                                        <span class="badge rounded-pill bg-success"><?php echo e(__('messages.paid')); ?></span>
                                    <?php elseif($sale->payment_status === 'Partial'): ?>
                                        <span
                                            class="badge rounded-pill bg-warning text-dark"><?php echo e(__('messages.partial')); ?></span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-danger"><?php echo e(__('messages.unpaid')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?php echo e($sale->user->name ?? '-'); ?></td>
                                <td class="text-center">
                                    <div class="tbl-action-wrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.view')): ?>
                                            <a class="btn btn-sm btn-icon btn-outline-info rounded-circle btn-action"
                                                href="<?php echo e(route('sales.show', $sale->id)); ?>"
                                                style="width:30px;height:30px;padding:0;" title="<?php echo e(__('messages.view')); ?>">
                                                <i class="bx bx-show" style="font-size:1rem;"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action"
                                                href="<?php echo e(route('sales.print', $sale->id)); ?>"
                                                style="width:30px;height:30px;padding:0;" target="_blank"
                                                title="<?php echo e(__('messages.print')); ?>">
                                                <i class="bx bx-printer" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.update')): ?>
                                            <?php if($sale->status === 'Completed' && $sale->returns->isEmpty()): ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale_returns.create')): ?>
                                                    <a class="btn btn-sm btn-icon btn-outline-warning rounded-circle btn-action"
                                                        href="<?php echo e(route('sale-returns.create', ['sale_id' => $sale->id])); ?>"
                                                        style="width:30px;height:30px;padding:0;" title="Sale Return">
                                                        <i class="bx bx-undo" style="font-size:1rem;"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle btn-action btn-payment-modal"
                                                data-action="<?php echo e(route('sales.update-payment', $sale->id)); ?>"
                                                data-due-amount="<?php echo e($sale->due_amount); ?>"
                                                data-grand-total="<?php echo e($sale->grand_total); ?>" data-id="<?php echo e($sale->id); ?>"
                                                data-invoice="<?php echo e($sale->invoice_no); ?>"
                                                data-paid-amount="<?php echo e($sale->paid_amount); ?>"
                                                data-payment-method="<?php echo e($sale->payment_method); ?>"
                                                style="width:30px;height:30px;padding:0;"
                                                title="<?php echo e(__('messages.set_update_payment')); ?>">
                                                <i class="bx bx-credit-card" style="font-size:1rem;"></i>
                                            </button>
                                            <a class="btn btn-sm btn-icon btn-outline-primary rounded-circle btn-action"
                                                href="<?php echo e(route('sales.edit', $sale->id)); ?>"
                                                style="width:30px;height:30px;padding:0;" title="<?php echo e(__('messages.edit')); ?>">
                                                <i class="bx bx-edit" style="font-size:1rem;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales.delete')): ?>
                                            <form action="<?php echo e(route('sales.destroy', $sale->id)); ?>" class="d-inline" style="display:contents;"
                                                id="delete-form-<?php echo e($sale->id); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button
                                                    class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action delete-btn"
                                                    data-id="<?php echo e($sale->id); ?>" data-invoice="<?php echo e($sale->invoice_no); ?>"
                                                    style="width:30px;height:30px;padding:0;"
                                                    title="<?php echo e(__('messages.delete')); ?>" type="button">
                                                    <i class="bx bx-trash" style="font-size:1rem;"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div aria-hidden="true" aria-labelledby="paymentModalLabel" class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius:16px;">

                
                <div class="modal-header border-0 text-white py-4 px-4"
                    style="background:linear-gradient(135deg,#696cff 0%,#9c3fe4 100%);position:relative;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:42px;height:42px;background:rgba(255,255,255,.2);">
                            <i class="bx bx-credit-card text-white" style="font-size:1.3rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="modal-title fw-bold mb-0" id="paymentModalLabel">
                                <?php echo e(__('messages.manage_payment')); ?></h5>
                            <input class="bg-transparent border-0 text-white opacity-75 small p-0 w-100"
                                id="modal_invoice_no" readonly style="outline:none;" type="text">
                        </div>
                        <button aria-label="Close" data-bs-dismiss="modal" type="button"
                            style="width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.2);
                                   border:none;color:#fff;display:flex;align-items:center;justify-content:center;
                                   flex-shrink:0;transition:background .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,.35)'"
                            onmouseout="this.style.background='rgba(255,255,255,.2)'">
                            <i class="bx bx-x" style="font-size:1.2rem;"></i>
                        </button>
                    </div>
                </div>

                <form id="paymentForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4">

                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="rounded-3 p-3 text-center h-100"
                                    style="background:#f0f4ff;border:1.5px solid #d0d8ff;">
                                    <div class="text-muted small fw-semibold mb-1">
                                        <i class="bx bx-receipt me-1"></i><?php echo e(__('messages.grand_total')); ?>

                                    </div>
                                    <div class="fw-bold text-primary" style="font-size:1.4rem;"
                                        id="modal_grand_total_text">
                                        <?php echo e(optional(current_currency())->symbol ?? '?'); ?>0.00
                                    </div>
                                    <input id="modal_grand_total" type="hidden">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-3 p-3 text-center h-100 balance-due-card"
                                    style="background:#fff0f0;border:1.5px solid #ffd0d0;">
                                    <div class="small fw-semibold mb-1 text-danger">
                                        <i class="bx bx-time-five me-1"></i><?php echo e(__('messages.balance_due')); ?>

                                    </div>
                                    <div class="fw-bold text-danger" style="font-size:1.4rem;"
                                        id="modal_balance_due_text">
                                        <?php echo e(optional(current_currency())->symbol ?? '?'); ?>0.00
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">
                                <?php echo e(__('messages.paid_amount_field')); ?> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 fw-bold text-primary"
                                    style="font-size:1rem;">
                                    <?php echo e(optional(current_currency())->symbol ?? '?'); ?>

                                </span>
                                <input class="form-control border-start-0 fw-bold ps-0" id="modal_paid_amount"
                                    min="0" name="paid_amount" required step="0.01" type="number"
                                    style="font-size:1.15rem;">
                            </div>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">
                                <?php echo e(__('messages.payment_method_lbl')); ?> <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="modal_payment_method" name="payment_method"
                                data-no-select2="1" required style="height:46px;">
                                <option value="Cash">💵 <?php echo e(__('messages.pm_cash')); ?></option>
                                <option value="Razorpay">⚡ <?php echo e(__('messages.razorpay_option')); ?></option>
                            </select>
                        </div>

                        
                        <div class="rounded-3 p-3 d-none" id="modal_razorpay_info"
                            style="background:linear-gradient(135deg,#eef2ff,#f5f0ff);border:1.5px solid #c7d2fe;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span
                                    class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:28px;height:28px;background:linear-gradient(135deg,#696cff,#9c3fe4);">
                                    <i class="bx bx-lock-alt text-white" style="font-size:.85rem;"></i>
                                </span>
                                <strong class="text-primary small"><?php echo e(__('messages.set_secure_razorpay')); ?></strong>
                            </div>
                            <p class="text-muted small mb-0 ps-1">
                                Click <strong style="color:#696cff;">"Pay via Razorpay"</strong> to open the secure payment
                                gateway.
                                <?php echo e(__('messages.set_payment_auto_recorded')); ?>

                            </p>
                        </div>

                        
                        <input type="hidden" id="modal_razorpay_order_id" name="razorpay_order_id">
                        <input type="hidden" id="modal_razorpay_payment_id" name="razorpay_payment_id">
                        <input type="hidden" id="modal_razorpay_signature" name="razorpay_signature">
                        <input type="hidden" id="modal_sale_id" name="sale_id">
                    </div>

                    
                    <div class="modal-footer border-top px-4 py-3 gap-2 bg-light" style="border-radius:0 0 16px 16px;">
                        <button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal"
                            type="button">
                            <i class="bx bx-x me-1"></i> <?php echo e(__('messages.cancel')); ?>

                        </button>
                        <button class="btn btn-primary rounded-pill px-4 ms-auto" id="btnSavePayment" type="submit">
                            <i class="bx bx-save me-1"></i> <?php echo e(__('messages.update_payment')); ?>

                        </button>
                        <button class="btn rounded-pill px-4 ms-auto d-none" id="btnModalPayRazorpay" type="button"
                            style="background:linear-gradient(135deg,#696cff,#9c3fe4);color:#fff;border:none;">
                            <i class="bx bx-bolt-circle me-1"></i> <?php echo e(__('messages.pay_via_razorpay')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            const sym = '<?php echo e(optional(current_currency())->symbol ?? '?'); ?>';
            $('#salesTable').DataTable({
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
                    emptyTable: '<div style="width:100%;text-align:center;padding:2.5rem 0;"><div class="text-muted" style="display:inline-flex;flex-direction:column;align-items:center;gap:8px;"><i class="bx bx-receipt" style="font-size:3rem;opacity:.3;line-height:1;"></i><?php echo e(__('messages.no_records')); ?></div></div>',
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            // Filters toggle
            let filtersOpen = localStorage.getItem('sales_filters_open') === 'true';
            if (filtersOpen) {
                $('#filtersCard').removeClass('d-none');
                $('#filtersChevron').addClass('bx-chevron-up').removeClass('bx-chevron-down');
            }
            $('#toggleFiltersBtn').on('click', function() {
                $('#filtersCard').toggleClass('d-none');
                const isOpen = !$('#filtersCard').hasClass('d-none');
                $('#filtersChevron').toggleClass('bx-chevron-up', isOpen).toggleClass('bx-chevron-down', !
                    isOpen);
                localStorage.setItem('sales_filters_open', isOpen);
            });

            // Delete handler
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id'),
                    invoice = $(this).data('invoice'),
                    form = $(`#delete-form-${id}`);
                Swal.fire({
                    title: '<?php echo e(__('messages.confirm_delete')); ?>',
                    text: `<?php echo e(__('messages.delete')); ?> "${invoice}"?`,
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
                            url: '<?php echo e(route('sales.bulk-destroy')); ?>',
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

            // -- Payment Modal ---------------------------------------------
            $(document).on('click', '.btn-payment-modal', function() {
                const btn = $(this);
                const grandTotal = parseFloat(btn.data('grand-total')) || 0;
                const paidAmount = parseFloat(btn.data('paid-amount')) || 0;
                const paymentMethod = btn.data('payment-method') || 'Cash';

                $('#modal_invoice_no').val(btn.data('invoice'));
                $('#modal_grand_total').val(grandTotal);
                $('#modal_grand_total_text').text(sym + grandTotal.toFixed(2));
                $('#modal_paid_amount').val(paidAmount.toFixed(2));
                $('#modal_payment_method').val(paymentMethod || 'Cash');
                $('#modal_sale_id').val(btn.data('id'));
                $('#paymentForm').attr('action', btn.data('action'));

                // Clear previous Razorpay fields
                $('#modal_razorpay_order_id, #modal_razorpay_payment_id, #modal_razorpay_signature').val(
                    '');

                updateModalDue();
                toggleModalRazorpay();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('paymentModal')).show();
            });

            $('#modal_paid_amount').on('input change', updateModalDue);

            $('#modal_payment_method').on('change', function() {
                toggleModalRazorpay();
                if ($(this).val() === 'Razorpay') {
                    const gt = parseFloat($('#modal_grand_total').val()) || 0;
                    $('#modal_paid_amount').val(gt.toFixed(2)).prop('readonly', true);
                    updateModalDue();
                } else {
                    $('#modal_paid_amount').prop('readonly', false);
                }
            });

            function toggleModalRazorpay() {
                const isRazorpay = $('#modal_payment_method').val() === 'Razorpay';
                $('#btnSavePayment').toggleClass('d-none', isRazorpay);
                $('#btnModalPayRazorpay').toggleClass('d-none', !isRazorpay);
                $('#modal_razorpay_info').toggleClass('d-none', !isRazorpay);
            }

            function updateModalDue() {
                const total = parseFloat($('#modal_grand_total').val()) || 0;
                const paid = parseFloat($('#modal_paid_amount').val()) || 0;
                const due = Math.max(0, total - paid);
                $('#modal_balance_due_text').text(sym + due.toFixed(2));
                const $card = $('.balance-due-card');
                if (due > 0) {
                    $card.css({
                        'background': '#fff0f0',
                        'border': '1.5px solid #ffd0d0'
                    });
                    $card.find('.small').removeClass('text-success').addClass('text-danger');
                    $('#modal_balance_due_text').removeClass('text-success').addClass('text-danger');
                } else {
                    $card.css({
                        'background': '#f0fff4',
                        'border': '1.5px solid #c6f6d5'
                    });
                    $card.find('.small').removeClass('text-danger').addClass('text-success');
                    $('#modal_balance_due_text').removeClass('text-danger').addClass('text-success');
                }
            }

            // -- Razorpay Pay button in modal ----------------------------
            $('#btnModalPayRazorpay').on('click', function() {
                const grandTotal = parseFloat($('#modal_grand_total').val()) || 0;
                if (grandTotal <= 0) {
                    showAdminToast('Grand total must be greater than 0.', 'warning');
                    return;
                }
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Processing�');

                $.ajax({
                    url: "<?php echo e(route('razorpay.create-order')); ?>",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        amount: grandTotal
                    },
                    success: function(res) {
                        const options = {
                            key: res.key_id,
                            amount: res.amount,
                            currency: res.currency,
                            name: '<?php echo e(addslashes(config('app.name'))); ?>',
                            description: 'Sale Payment � ' + $('#modal_invoice_no').val(),
                            order_id: res.order_id,
                            prefill: {
                                name: '<?php echo e(addslashes(auth()->user()->name ?? '')); ?>',
                                email: '<?php echo e(addslashes(auth()->user()->email ?? '')); ?>',
                            },
                            theme: {
                                color: '#696cff'
                            },
                            handler: function(response) {
                                const saleId = $('#modal_sale_id').val();
                                $.ajax({
                                    url: '/sales/' + saleId + '/payment',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]')
                                            .attr('content'),
                                        paid_amount: grandTotal.toFixed(2),
                                        payment_method: 'Razorpay',
                                        razorpay_order_id: response
                                            .razorpay_order_id,
                                        razorpay_payment_id: response
                                            .razorpay_payment_id,
                                        razorpay_signature: response
                                            .razorpay_signature,
                                    },
                                    success: function(res) {
                                        bootstrap.Modal.getOrCreateInstance(
                                            document.getElementById(
                                                'paymentModal')).hide();
                                        showAdminToast(res.message ||
                                            'Payment received via Razorpay.',
                                            'success');
                                        setTimeout(() => window.location
                                            .reload(), 1400);
                                    },
                                    error: function(xhr) {
                                        showAdminToast(xhr.responseJSON
                                            ?.message ||
                                            'Payment verified but update failed.',
                                            'danger');
                                        btn.prop('disabled', false).html(
                                            '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay'
                                        );
                                    }
                                });
                            },
                            modal: {
                                ondismiss: function() {
                                    btn.prop('disabled', false).html(
                                        '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay'
                                    );
                                    showAdminToast('Payment cancelled.', 'warning');
                                }
                            }
                        };
                        const rzp = new Razorpay(options);
                        rzp.on('payment.failed', function(r) {
                            showAdminToast('Payment failed: ' + r.error.description,
                                'danger');
                            btn.prop('disabled', false).html(
                                '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay'
                            );
                        });
                        rzp.open();
                    },
                    error: function(xhr) {
                        showAdminToast(xhr.responseJSON?.message ||
                            'Could not create Razorpay order.', 'danger');
                        btn.prop('disabled', false).html(
                            '<i class="bx bx-bolt-circle me-1"></i> Pay via Razorpay');
                    }
                });
            });

            $('#paymentForm').on('submit', function(e) {
                e.preventDefault();
                if ($('#modal_payment_method').val() === 'Razorpay') return;
                const form = $(this);
                const btn = $('#btnSavePayment');
                const orig = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.success) {
                            bootstrap.Modal.getOrCreateInstance(document.getElementById(
                                'paymentModal')).hide();
                            showAdminToast(res.message, 'success');
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            showAdminToast(res.message, 'error');
                            btn.prop('disabled', false).html(orig);
                        }
                    },
                    error: function(xhr) {
                        showAdminToast(xhr.responseJSON?.message ?? 'An error occurred.',
                            'error');
                        btn.prop('disabled', false).html(orig);
                    }
                });
            });
        });
    </script>

    
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/sales/index.blade.php ENDPATH**/ ?>