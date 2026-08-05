<?php $__env->startSection('title', __('messages.add_currency')); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.add_currency')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo e(route('currencies.index')); ?>"><?php echo e(__('messages.menu_currencies')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.add')); ?></li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('currencies.index')); ?>" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

        </a>
    </div>

    <form id="currencyForm" method="POST" action="<?php echo e(route('currencies.store')); ?>" data-validate="true">
        <?php echo csrf_field(); ?>
        <div class="row g-4">

            
            <div class="col-lg-8 col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-dollar me-2 text-primary"></i><?php echo e(__('messages.currency_details')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <?php echo e(__('messages.currency_name')); ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('name')); ?>" placeholder="<?php echo e(__('messages.currency_placeholder_name')); ?>"
                                required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <?php echo e(__('messages.currency_code')); ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('code')); ?>"
                                placeholder="<?php echo e(__('messages.currency_placeholder_code')); ?>" required maxlength="10">
                            <div class="form-text"><?php echo e(__('messages.currency_code_hint')); ?></div>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <?php echo e(__('messages.currency_symbol_label')); ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="symbol"
                                    class="form-control <?php $__errorArgs = ['symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('symbol')); ?>"
                                    placeholder="<?php echo e(__('messages.currency_placeholder_sym')); ?>" required maxlength="10">
                                <?php $__errorArgs = ['symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <?php echo e(__('messages.exchange_rate')); ?> <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.0001" min="0" name="exchange_rate"
                                    class="form-control <?php $__errorArgs = ['exchange_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('exchange_rate', '1.0000')); ?>"
                                    placeholder="<?php echo e(__('messages.currency_placeholder_rate')); ?>" required>
                                <?php $__errorArgs = ['exchange_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            
            <div class="col-lg-4 col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bx bx-send me-2 text-primary"></i><?php echo e(__('messages.publish')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block"><?php echo e(__('messages.status')); ?> <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="hidden" name="status" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="statusToggle"
                                        name="status" value="active"
                                        <?php echo e(old('status', 'active') === 'active' ? 'checked' : ''); ?>>
                                </div>
                                <span id="statusLabel"
                                    class="fw-semibold <?php echo e(old('status', 'active') === 'active' ? 'text-success' : 'text-danger'); ?>">
                                    <?php echo e(old('status', 'active') === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="isDefault"
                                    name="is_default" value="1" <?php echo e(old('is_default') ? 'checked' : ''); ?>>
                                <label class="form-check-label fw-semibold" for="isDefault">
                                    <?php echo e(__('messages.set_as_default_switch')); ?>

                                </label>
                            </div>
                            <div class="form-text"><?php echo e(__('messages.set_default_text')); ?></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> <?php echo e(__('messages.save_currency')); ?>

                            </button>
                            <a href="<?php echo e(route('currencies.index')); ?>" class="btn btn-outline-secondary">
                                <i class="bx bx-x me-1"></i> <?php echo e(__('messages.cancel')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Status toggle label
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                if (this.checked) {
                    lbl.textContent = '<?php echo e(__('messages.active')); ?>';
                    lbl.className = 'fw-semibold text-success';
                } else {
                    lbl.textContent = '<?php echo e(__('messages.inactive')); ?>';
                    lbl.className = 'fw-semibold text-danger';
                }
            });
        }

        // Auto-uppercase code
        document.getElementById('code').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/currencies/create.blade.php ENDPATH**/ ?>