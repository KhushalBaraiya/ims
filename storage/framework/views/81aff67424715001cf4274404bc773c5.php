<?php $__env->startSection('title', __('messages.menu_settings')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .settings-nav .nav-link {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--bs-body-color);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all .18s;
            border: none;
            background: none;
        }

        .settings-nav .nav-link:hover {
            background: rgba(105, 108, 255, .08);
            color: #696cff;
        }

        .settings-nav .nav-link.active {
            background: linear-gradient(135deg, rgba(105, 108, 255, .15), rgba(105, 108, 255, .06));
            color: #696cff;
            font-weight: 600;
        }

        .settings-nav .nav-link .nav-icon {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .settings-nav .nav-link.active .nav-icon {
            background: #696cff;
            color: #fff;
        }

        .settings-nav .nav-link:not(.active) .nav-icon {
            background: rgba(105, 108, 255, .1);
            color: #696cff;
        }

        .settings-section {
            display: none;
        }

        .settings-section.active {
            display: block;
        }

        .field-group {
            background: rgba(105, 108, 255, .03);
            border: 1px solid rgba(105, 108, 255, .1);
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 16px;
        }

        .field-group-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #696cff;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .save-sticky {
            position: sticky;
            top: 80px;
        }

        .logo-preview-wrap {
            width: 80px;
            height: 80px;
            border: 2px dashed rgba(105, 108, 255, .3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(105, 108, 255, .03);
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo-preview-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.settings')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.menu_settings')); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 px-3 py-2">
            <i class="bx bx-check-circle fs-5 flex-shrink-0"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 px-3 py-2">
            <i class="bx bx-error-circle fs-5 flex-shrink-0"></i>
            <span><?php echo e($errors->first()); ?></span>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data" id="settingsForm">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div class="row g-4">

            
            <div class="col-lg-3 col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-2">
                        <nav class="settings-nav nav flex-column gap-1">
                            <a href="#" class="nav-link active" data-tab="company">
                                <span class="nav-icon"><i class="bx bx-building"></i></span>
                                <?php echo e(__('messages.set_tab_company')); ?>

                            </a>
                            <a href="#" class="nav-link" data-tab="invoice">
                                <span class="nav-icon"><i class="bx bx-receipt"></i></span>
                                <?php echo e(__('messages.set_tab_invoice')); ?>

                            </a>
                            <a href="#" class="nav-link" data-tab="email">
                                <span class="nav-icon"><i class="bx bx-envelope"></i></span>
                                <?php echo e(__('messages.set_tab_email')); ?>

                            </a>
                            <a href="#" class="nav-link" data-tab="security">
                                <span class="nav-icon"><i class="bx bx-shield-alt-2"></i></span>
                                <?php echo e(__('messages.set_tab_security')); ?>

                            </a>
                        </nav>
                    </div>
                </div>

                
                <div class="save-sticky">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body p-3 d-grid gap-2">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings.update')): ?>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bx bx-save me-1"></i> <?php echo e(__('messages.save_settings')); ?>

                                </button>
                            <?php endif; ?>
                            <a class="btn btn-outline-secondary" href="<?php echo e(route('dashboard')); ?>">
                                <i class="bx bx-x me-1"></i> <?php echo e(__('messages.cancel')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom bg-transparent py-2 px-3">
                            <span class="small fw-semibold text-muted"><i
                                    class="bx bx-info-circle me-1"></i><?php echo e(__('messages.set_current_values')); ?></span>
                        </div>
                        <ul class="list-unstyled mb-0 px-3 py-2">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small"><?php echo e(__('messages.set_currency_label')); ?></span>
                                <span class="fw-bold small"><?php echo e($settings->get('currency_code', 'INR')); ?>

                                    <?php echo e($settings->get('currency_symbol', '₹')); ?></span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small"><?php echo e(__('messages.set_tax_label')); ?></span>
                                <span class="fw-bold small"><?php echo e($settings->get('tax_name', 'N/A')); ?>

                                    (<?php echo e($settings->get('tax_percentage', '0')); ?>%)</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small"><?php echo e(__('messages.set_inv_prefix_label')); ?></span>
                                <code class="small"><?php echo e($settings->get('invoice_prefix', 'INV')); ?></code>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted small"><?php echo e(__('messages.set_pur_prefix_label')); ?></span>
                                <code class="small"><?php echo e($settings->get('purchase_prefix', 'PO')); ?></code>
                            </li>
                        </ul>
                        <div class="card-footer text-muted small text-center py-2">
                            <i class="bx bx-coin me-1"></i><?php echo e(__('messages.currency_managed_separately')); ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-9 col-md-8">

                
                <div class="settings-section active" id="tab-company">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom bg-transparent py-3 px-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded p-1" style="background:rgba(105,108,255,.12);">
                                    <i class="bx bx-building text-primary fs-5"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.company_information')); ?></h6>
                                    <p class="text-muted small mb-0"><?php echo e(__('messages.set_company_desc')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            
                            <div class="field-group">
                                <div class="field-group-title"><i class="bx bx-id-card"></i>
                                    <?php echo e(__('messages.set_basic_details')); ?></div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold"><?php echo e(__('messages.company_name')); ?> <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="company_name" placeholder="<?php echo e(__('messages.ph_company_name_set')); ?>" required
                                        type="text" value="<?php echo e(old('company_name', $settings->get('company_name'))); ?>">
                                    <?php $__errorArgs = ['company_name'];
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
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.email_address')); ?> <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-envelope text-muted"></i></span>
                                            <input class="form-control <?php $__errorArgs = ['company_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="company_email" placeholder="<?php echo e(__('messages.ph_company_email')); ?>"
                                                required type="email"
                                                value="<?php echo e(old('company_email', $settings->get('company_email'))); ?>">
                                        </div>
                                        <?php $__errorArgs = ['company_email'];
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
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.phone_label')); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-phone text-muted"></i></span>
                                            <input class="form-control <?php $__errorArgs = ['company_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="company_phone" placeholder="<?php echo e(__('messages.ph_company_phone')); ?>"
                                                type="text"
                                                value="<?php echo e(old('company_phone', $settings->get('company_phone'))); ?>">
                                        </div>
                                        <?php $__errorArgs = ['company_phone'];
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

                            
                            <div class="field-group">
                                <div class="field-group-title"><i class="bx bx-map-pin"></i>
                                    <?php echo e(__('messages.set_address')); ?></div>
                                <textarea class="form-control <?php $__errorArgs = ['company_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="company_address"
                                    placeholder="<?php echo e(__('messages.ph_company_address')); ?>" rows="3"><?php echo e(old('company_address', $settings->get('company_address'))); ?></textarea>
                                <?php $__errorArgs = ['company_address'];
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

                            
                            <div class="field-group mb-0">
                                <div class="field-group-title"><i class="bx bx-image"></i>
                                    <?php echo e(__('messages.set_company_logo')); ?></div>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="logo-preview-wrap" id="logoPreviewWrap">
                                        <?php if($settings->get('company_logo')): ?>
                                            <img src="<?php echo e(asset('uploads/settings/' . $settings->get('company_logo'))); ?>"
                                                alt="Logo" id="logoPreview">
                                        <?php else: ?>
                                            <i class="bx bx-image text-muted fs-3" id="logoPlaceholder"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input class="form-control <?php $__errorArgs = ['company_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="company_logo" type="file" id="logoInput"
                                            accept="image/png,image/jpeg,image/svg+xml,image/webp">
                                        <div class="form-text"><?php echo e(__('messages.set_logo_hint')); ?></div>
                                        <?php $__errorArgs = ['company_logo'];
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
                </div>

                
                <div class="settings-section" id="tab-invoice">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom bg-transparent py-3 px-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded p-1" style="background:rgba(255,171,0,.12);">
                                    <i class="bx bx-receipt text-warning fs-5"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.invoice_tax_settings')); ?></h6>
                                    <p class="text-muted small mb-0"><?php echo e(__('messages.set_invoice_desc')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            
                            <div class="field-group">
                                <div class="field-group-title"><i class="bx bx-hash"></i>
                                    <?php echo e(__('messages.set_number_prefixes')); ?></div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.invoice_prefix')); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold text-primary">INV-</span>
                                            <input class="form-control <?php $__errorArgs = ['invoice_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="invoice_prefix"
                                                placeholder="<?php echo e(__('messages.ph_invoice_prefix')); ?>" type="text"
                                                value="<?php echo e(old('invoice_prefix', $settings->get('invoice_prefix'))); ?>">
                                        </div>
                                        <div class="form-text"><?php echo e(__('messages.invoice_prefix_hint')); ?></div>
                                        <?php $__errorArgs = ['invoice_prefix'];
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
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.purchase_prefix')); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold text-success">PO-</span>
                                            <input class="form-control <?php $__errorArgs = ['purchase_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="purchase_prefix"
                                                placeholder="<?php echo e(__('messages.ph_purchase_prefix')); ?>" type="text"
                                                value="<?php echo e(old('purchase_prefix', $settings->get('purchase_prefix'))); ?>">
                                        </div>
                                        <div class="form-text"><?php echo e(__('messages.purchase_prefix_hint')); ?></div>
                                        <?php $__errorArgs = ['purchase_prefix'];
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

                            
                            <div class="field-group">
                                <div class="field-group-title"><i class="bx bx-percent"></i>
                                    <?php echo e(__('messages.set_tax_config')); ?></div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.tax_name')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['tax_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="tax_name" placeholder="<?php echo e(__('messages.ph_tax_name')); ?>"
                                            type="text" value="<?php echo e(old('tax_name', $settings->get('tax_name'))); ?>">
                                        <?php $__errorArgs = ['tax_name'];
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
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.tax_percentage')); ?>

                                            (%)</label>
                                        <div class="input-group">
                                            <input class="form-control <?php $__errorArgs = ['tax_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                max="100" min="0" name="tax_percentage"
                                                placeholder="<?php echo e(__('messages.ph_tax_percent')); ?>" step="0.01"
                                                type="number"
                                                value="<?php echo e(old('tax_percentage', $settings->get('tax_percentage'))); ?>">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <?php $__errorArgs = ['tax_percentage'];
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

                            
                            <div class="field-group mb-0">
                                <div class="field-group-title"><i class="bx bx-package"></i>
                                    <?php echo e(__('messages.set_product_display')); ?></div>
                                <div class="d-flex align-items-center justify-content-between p-3 rounded"
                                    style="background:rgba(0,0,0,.03); border:1px solid rgba(0,0,0,.07);">
                                    <div>
                                        <div class="fw-semibold small"><?php echo e(__('messages.show_out_of_stock_products')); ?>

                                        </div>
                                        <div class="text-muted" style="font-size:12px;">
                                            <?php echo e(__('messages.show_out_of_stock_products_hint')); ?></div>
                                    </div>
                                    <div class="form-check form-switch mb-0 ms-3">
                                        <input
                                            <?php echo e(old('show_out_of_stock_products', $settings->get('show_out_of_stock_products', '0')) == '1' ? 'checked' : ''); ?>

                                            class="form-check-input" id="show_out_of_stock_products"
                                            name="show_out_of_stock_products" role="switch" type="checkbox"
                                            value="1" style="width:40px;height:22px;">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div class="settings-section" id="tab-email">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom bg-transparent py-3 px-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded p-1" style="background:rgba(3,195,236,.12);">
                                    <i class="bx bx-envelope text-info fs-5"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.set_tab_email')); ?>

                                        <?php echo e(__('messages.menu_settings')); ?></h6>
                                    <p class="text-muted small mb-0"><?php echo e(__('messages.set_email_desc')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            <div class="alert alert-info d-flex gap-2 align-items-start py-2 px-3 mb-4 rounded-3"
                                style="font-size:.82rem;">
                                <i class="bx bx-info-circle mt-1 flex-shrink-0 fs-6"></i>
                                <div><?php echo e(__('messages.set_gmail_hint')); ?></div>
                            </div>

                            <div class="field-group">
                                <div class="field-group-title"><i class="bx bx-server"></i>
                                    <?php echo e(__('messages.set_server_config')); ?></div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-semibold"><?php echo e(__('messages.set_mailer_driver')); ?></label>
                                        <select class="form-select <?php $__errorArgs = ['mail_mailer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_mailer">
                                            <option value="smtp"
                                                <?php echo e(old('mail_mailer', $settings->get('mail_mailer', 'smtp')) === 'smtp' ? 'selected' : ''); ?>>
                                                SMTP</option>
                                            <option value="log"
                                                <?php echo e(old('mail_mailer', $settings->get('mail_mailer', 'smtp')) === 'log' ? 'selected' : ''); ?>>
                                                Log (debug)</option>
                                            <option value="array"
                                                <?php echo e(old('mail_mailer', $settings->get('mail_mailer', 'smtp')) === 'array' ? 'selected' : ''); ?>>
                                                Array (testing)</option>
                                        </select>
                                        <?php $__errorArgs = ['mail_mailer'];
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
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.set_smtp_host')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_host" type="text"
                                            placeholder="<?php echo e(__('messages.ph_smtp_host')); ?>"
                                            value="<?php echo e(old('mail_host', $settings->get('mail_host', env('MAIL_HOST', '')))); ?>">
                                        <?php $__errorArgs = ['mail_host'];
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
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.set_smtp_port')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_port" type="number"
                                            placeholder="<?php echo e(__('messages.ph_smtp_port')); ?>"
                                            value="<?php echo e(old('mail_port', $settings->get('mail_port', env('MAIL_PORT', '587')))); ?>">
                                        <?php $__errorArgs = ['mail_port'];
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
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.set_encryption')); ?></label>
                                        <select class="form-select <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_encryption">
                                            <option value="tls"
                                                <?php echo e(old('mail_encryption', $settings->get('mail_encryption', 'tls')) === 'tls' ? 'selected' : ''); ?>>
                                                TLS (587)</option>
                                            <option value="ssl"
                                                <?php echo e(old('mail_encryption', $settings->get('mail_encryption', 'tls')) === 'ssl' ? 'selected' : ''); ?>>
                                                SSL (465)</option>
                                            <option value=""
                                                <?php echo e(old('mail_encryption', $settings->get('mail_encryption', 'tls')) === '' ? 'selected' : ''); ?>>
                                                None</option>
                                        </select>
                                        <?php $__errorArgs = ['mail_encryption'];
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
                                    <div class="col-md-5">
                                        <label
                                            class="form-label fw-semibold"><?php echo e(__('messages.set_smtp_username')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_username" type="text"
                                            placeholder="<?php echo e(__('messages.ph_smtp_user')); ?>"
                                            value="<?php echo e(old('mail_username', $settings->get('mail_username', env('MAIL_USERNAME', '')))); ?>">
                                        <?php $__errorArgs = ['mail_username'];
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
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-semibold"><?php echo e(__('messages.set_app_password')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_password" type="password"
                                            placeholder="<?php echo e(__('messages.set_app_password_hint')); ?>"
                                            autocomplete="new-password">
                                        <div class="form-text"><?php echo e(__('messages.set_app_password_hint')); ?></div>
                                        <?php $__errorArgs = ['mail_password'];
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

                            <div class="field-group mb-0">
                                <div class="field-group-title"><i class="bx bx-at"></i>
                                    <?php echo e(__('messages.set_from_address')); ?></div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.set_from_email')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_from_address" type="email"
                                            placeholder="<?php echo e(__('messages.ph_from_email')); ?>"
                                            value="<?php echo e(old('mail_from_address', $settings->get('mail_from_address', env('MAIL_FROM_ADDRESS', '')))); ?>">
                                        <?php $__errorArgs = ['mail_from_address'];
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
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold"><?php echo e(__('messages.set_from_name')); ?></label>
                                        <input class="form-control <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="mail_from_name" type="text"
                                            placeholder="<?php echo e(__('messages.ph_from_name')); ?>"
                                            value="<?php echo e(old('mail_from_name', $settings->get('mail_from_name', env('MAIL_FROM_NAME', '')))); ?>">
                                        <?php $__errorArgs = ['mail_from_name'];
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

                            
                            <div class="field-group mb-0"
                                style="border-color:rgba(3,195,236,.2); background:rgba(3,195,236,.03);">
                                <div class="field-group-title" style="color:#03c3ec;">
                                    <i class="bx bx-send"></i> <?php echo e(__('messages.set_test_email')); ?>

                                </div>
                                <div class="d-flex gap-2 align-items-end">
                                    <div class="flex-grow-1">
                                        <label
                                            class="form-label fw-semibold small"><?php echo e(__('messages.set_test_email_label')); ?></label>
                                        <input type="email" id="testEmailAddress" class="form-control"
                                            placeholder="<?php echo e(__('messages.ph_test_email')); ?>"
                                            value="<?php echo e(auth()->user()->email); ?>">
                                    </div>
                                    <button type="button" id="btnTestEmail" class="btn btn-outline-info flex-shrink-0">
                                        <i class="bx bx-send me-1"></i> <?php echo e(__('messages.set_send_test')); ?>

                                    </button>
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bx bx-info-circle me-1"></i><?php echo e(__('messages.set_test_email_hint')); ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div class="settings-section" id="tab-security">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom bg-transparent py-3 px-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded p-1" style="background:rgba(255,62,29,.1);">
                                    <i class="bx bx-shield-alt-2 text-danger fs-5"></i>
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0"><?php echo e(__('messages.set_tab_security')); ?>

                                        <?php echo e(__('messages.menu_settings')); ?></h6>
                                    <p class="text-muted small mb-0"><?php echo e(__('messages.set_security_desc')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            
                            <div class="field-group">
                                <div class="field-group-title"><i class="bx bx-time-five"></i>
                                    <?php echo e(__('messages.set_session')); ?></div>
                                <div class="d-flex align-items-start gap-4 flex-wrap">
                                    <div style="min-width:200px;">
                                        <label
                                            class="form-label fw-semibold"><?php echo e(__('messages.set_session_timeout')); ?></label>
                                        <div class="input-group" style="max-width:200px;">
                                            <input class="form-control <?php $__errorArgs = ['session_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="session_timeout" type="number" min="0" max="1440"
                                                step="1" placeholder="<?php echo e(__('messages.ph_session_timeout')); ?>"
                                                value="<?php echo e(old('session_timeout', $settings->get('session_timeout', '0'))); ?>">
                                            <span class="input-group-text"><?php echo e(__('messages.set_minutes_short')); ?></span>
                                        </div>
                                        <?php $__errorArgs = ['session_timeout'];
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
                                    <div class="flex-grow-1 pt-1">
                                        <p class="text-muted small mb-0 mt-4">
                                            <?php echo e(__('messages.set_session_hint')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="field-group mb-0">
                                <div class="field-group-title"><i class="bx bx-lock-alt"></i>
                                    <?php echo e(__('messages.set_lockout')); ?></div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-semibold"><?php echo e(__('messages.set_max_attempts')); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-key text-muted"></i></span>
                                            <input class="form-control <?php $__errorArgs = ['max_login_attempts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="max_login_attempts" type="number" min="0" max="20"
                                                step="1" placeholder="<?php echo e(__('messages.ph_max_attempts')); ?>"
                                                value="<?php echo e(old('max_login_attempts', $settings->get('max_login_attempts', '5'))); ?>">
                                            <span class="input-group-text"><?php echo e(__('messages.set_tries')); ?></span>
                                        </div>
                                        <div class="form-text"><?php echo e(__('messages.set_max_attempts_hint')); ?></div>
                                        <?php $__errorArgs = ['max_login_attempts'];
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
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-semibold"><?php echo e(__('messages.set_lockout_duration')); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-timer text-muted"></i></span>
                                            <input class="form-control <?php $__errorArgs = ['lockout_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lockout_duration" type="number" min="1" max="1440"
                                                step="1" placeholder="<?php echo e(__('messages.ph_lockout_duration')); ?>"
                                                value="<?php echo e(old('lockout_duration', $settings->get('lockout_duration', '15'))); ?>">
                                            <span class="input-group-text"><?php echo e(__('messages.set_minutes_short')); ?></span>
                                        </div>
                                        <div class="form-text"><?php echo e(__('messages.set_lockout_hint')); ?>

                                        </div>
                                        <?php $__errorArgs = ['lockout_duration'];
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

                                
                                <div class="mt-3 p-3 rounded d-flex align-items-center gap-3"
                                    style="background:rgba(255,62,29,.05); border:1px solid rgba(255,62,29,.15);">
                                    <i class="bx bx-error-circle text-danger fs-4 flex-shrink-0"></i>
                                    <div class="small text-muted">
                                        <?php echo e(__('messages.set_lockout_alert', ['attempts' => $settings->get('max_login_attempts', '5'), 'duration' => $settings->get('lockout_duration', '15')])); ?>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            // ── Tab switching ────────────────────────────────────────────
            $('.settings-nav .nav-link').on('click', function(e) {
                e.preventDefault();
                const tab = $(this).data('tab');
                $('.settings-nav .nav-link').removeClass('active');
                $(this).addClass('active');
                $('.settings-section').removeClass('active');
                $('#tab-' + tab).addClass('active');
                sessionStorage.setItem('settingsTab', tab);
            });

            // Auto-open tab that has a validation error, else restore last visited
            <?php if($errors->any()): ?>
                const errorFields = <?php echo json_encode($errors->keys(), 15, 512) ?>;
                const tabFieldMap = {
                    company: ['company_name', 'company_email', 'company_phone', 'company_address',
                        'company_logo'
                    ],
                    invoice: ['invoice_prefix', 'purchase_prefix', 'tax_name', 'tax_percentage',
                        'show_out_of_stock_products'
                    ],
                    email: ['mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username',
                        'mail_password', 'mail_from_address', 'mail_from_name'
                    ],
                    security: ['session_timeout', 'max_login_attempts', 'lockout_duration'],
                };
                let errorTab = null;
                for (const [t, fields] of Object.entries(tabFieldMap)) {
                    if (errorFields.some(f => fields.includes(f))) {
                        errorTab = t;
                        break;
                    }
                }
                if (errorTab) $('.settings-nav .nav-link[data-tab="' + errorTab + '"]').trigger('click');
            <?php else: ?>
                const savedTab = sessionStorage.getItem('settingsTab');
                if (savedTab) $('.settings-nav .nav-link[data-tab="' + savedTab + '"]').trigger('click');
            <?php endif; ?>

            // ── Logo preview ─────────────────────────────────────────────
            $('#logoInput').on('change', function() {
                const file = this.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logoPreviewWrap').html(
                        '<img src="' + e.target.result +
                        '" id="logoPreview" style="max-width:100%;max-height:100%;object-fit:contain;">'
                    );
                };
                reader.readAsDataURL(file);
            });

            // ── Send Test Email ───────────────────────────────────────────
            $('#btnTestEmail').on('click', function() {
                const btn = $(this);
                const toEmail = $('#testEmailAddress').val().trim();
                if (!toEmail) {
                    showAdminToast('<?php echo e(__('messages.please_enter_test_email')); ?>', 'warning');
                    return;
                }
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> <?php echo e(__('messages.sending_spinner')); ?>'
                    );
                $.ajax({
                    url: '<?php echo e(route('settings.test-email')); ?>',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        to: toEmail,
                        mail_host: $('[name="mail_host"]').val(),
                        mail_port: $('[name="mail_port"]').val(),
                        mail_username: $('[name="mail_username"]').val(),
                        mail_encryption: $('[name="mail_encryption"]').val(),
                        mail_from_address: $('[name="mail_from_address"]').val(),
                        mail_from_name: $('[name="mail_from_name"]').val(),
                    },
                    success: function(res) {
                        showAdminToast(res.message || 'Test email sent successfully!',
                            'success');
                    },
                    error: function(xhr) {
                        showAdminToast(xhr.responseJSON?.message ||
                            'Failed to send test email.', 'danger');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="bx bx-send me-1"></i> Send Test');
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/settings/index.blade.php ENDPATH**/ ?>