<?php $__env->startSection('title', __('messages.add_supplier')); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><?php echo e(__('messages.add_supplier')); ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo e(route('suppliers.index')); ?>"><?php echo e(__('messages.menu_suppliers')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.add')); ?></li>
                </ol>
            </nav>
        </div>
        <a class="btn btn-outline-secondary" href="<?php echo e(route('suppliers.index')); ?>">
            <i class="bx bx-arrow-back me-1"></i> <?php echo e(__('messages.back')); ?>

        </a>
    </div>

    <form action="<?php echo e(route('suppliers.store')); ?>" data-validate="true" id="supplierForm" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row g-4">

            
            <div class="col-lg-8 col-md-8">

                
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-store text-primary me-2"></i><?php echo e(__('messages.supplier_details')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><?php echo e(__('messages.supplier_name')); ?> <span
                                        class="text-danger">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name"
                                    placeholder="<?php echo e(__('messages.ph_supplier_name')); ?>" required type="text"
                                    value="<?php echo e(old('name')); ?>">
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
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><?php echo e(__('messages.company_name')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="company_name"
                                    placeholder="<?php echo e(__('messages.ph_company_name')); ?>" type="text"
                                    value="<?php echo e(old('company_name')); ?>">
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
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><?php echo e(__('messages.contact_person')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['contact_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="contact_person" placeholder="<?php echo e(__('messages.ph_contact_person')); ?>"
                                    type="text" value="<?php echo e(old('contact_person')); ?>">
                                <?php $__errorArgs = ['contact_person'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.ph_phone')); ?> <span
                                        class="text-danger">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone"
                                    placeholder="<?php echo e(__('messages.ph_phone')); ?>" required type="text"
                                    value="<?php echo e(old('phone')); ?>">
                                <?php $__errorArgs = ['phone'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.alt_phone')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['alt_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="alt_phone"
                                    placeholder="<?php echo e(__('messages.ph_alt_phone')); ?>" type="text"
                                    value="<?php echo e(old('alt_phone')); ?>">
                                <?php $__errorArgs = ['alt_phone'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.email_address')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                                    placeholder="<?php echo e(__('messages.ph_email')); ?>" type="email"
                                    value="<?php echo e(old('email')); ?>">
                                <?php $__errorArgs = ['email'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.gst_number')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['gst_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="gst_number"
                                    placeholder="<?php echo e(__('messages.ph_gst_number')); ?>" type="text"
                                    value="<?php echo e(old('gst_number')); ?>">
                                <?php $__errorArgs = ['gst_number'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.pan_number')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['pan_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="pan_number"
                                    placeholder="<?php echo e(__('messages.ph_pan_number')); ?>" type="text"
                                    value="<?php echo e(old('pan_number')); ?>">
                                <?php $__errorArgs = ['pan_number'];
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

                
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-map text-info me-2"></i><?php echo e(__('messages.address_details')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold"><?php echo e(__('messages.address_label')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="address"
                                    placeholder="<?php echo e(__('messages.ph_address')); ?>" rows="2"><?php echo e(old('address')); ?></textarea>
                                <?php $__errorArgs = ['address'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.city')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="city"
                                    placeholder="<?php echo e(__('messages.city')); ?>" type="text" value="<?php echo e(old('city')); ?>">
                                <?php $__errorArgs = ['city'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.state')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="state"
                                    placeholder="<?php echo e(__('messages.state')); ?>" type="text"
                                    value="<?php echo e(old('state')); ?>">
                                <?php $__errorArgs = ['state'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.pincode')); ?></label>
                                <input class="form-control <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="pincode"
                                    placeholder="<?php echo e(__('messages.pincode')); ?>" type="text"
                                    value="<?php echo e(old('pincode')); ?>">
                                <?php $__errorArgs = ['pincode'];
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
                                <label class="form-label fw-semibold"><?php echo e(__('messages.country')); ?></label>
                                <?php
                                    $allCountries = [
                                        'Afghanistan',
                                        'Albania',
                                        'Algeria',
                                        'Andorra',
                                        'Angola',
                                        'Argentina',
                                        'Armenia',
                                        'Australia',
                                        'Austria',
                                        'Azerbaijan',
                                        'Bahrain',
                                        'Bangladesh',
                                        'Belarus',
                                        'Belgium',
                                        'Bhutan',
                                        'Bolivia',
                                        'Bosnia and Herzegovina',
                                        'Brazil',
                                        'Bulgaria',
                                        'Cambodia',
                                        'Cameroon',
                                        'Canada',
                                        'Chile',
                                        'China',
                                        'Colombia',
                                        'Croatia',
                                        'Cuba',
                                        'Cyprus',
                                        'Czech Republic',
                                        'Denmark',
                                        'Ecuador',
                                        'Egypt',
                                        'Estonia',
                                        'Ethiopia',
                                        'Finland',
                                        'France',
                                        'Georgia',
                                        'Germany',
                                        'Ghana',
                                        'Greece',
                                        'Guatemala',
                                        'Hong Kong',
                                        'Hungary',
                                        'India',
                                        'Indonesia',
                                        'Iran',
                                        'Iraq',
                                        'Ireland',
                                        'Israel',
                                        'Italy',
                                        'Japan',
                                        'Jordan',
                                        'Kazakhstan',
                                        'Kenya',
                                        'Kuwait',
                                        'Latvia',
                                        'Lebanon',
                                        'Libya',
                                        'Lithuania',
                                        'Luxembourg',
                                        'Malaysia',
                                        'Malta',
                                        'Mexico',
                                        'Moldova',
                                        'Morocco',
                                        'Mozambique',
                                        'Myanmar',
                                        'Nepal',
                                        'Netherlands',
                                        'New Zealand',
                                        'Nigeria',
                                        'Norway',
                                        'Oman',
                                        'Pakistan',
                                        'Panama',
                                        'Paraguay',
                                        'Peru',
                                        'Philippines',
                                        'Poland',
                                        'Portugal',
                                        'Qatar',
                                        'Romania',
                                        'Russia',
                                        'Saudi Arabia',
                                        'Serbia',
                                        'Singapore',
                                        'Slovakia',
                                        'Slovenia',
                                        'South Africa',
                                        'South Korea',
                                        'Spain',
                                        'Sri Lanka',
                                        'Sweden',
                                        'Switzerland',
                                        'Syria',
                                        'Taiwan',
                                        'Tanzania',
                                        'Thailand',
                                        'Tunisia',
                                        'Turkey',
                                        'Uganda',
                                        'Ukraine',
                                        'United Arab Emirates',
                                        'United Kingdom',
                                        'United States',
                                        'Uruguay',
                                        'Uzbekistan',
                                        'Venezuela',
                                        'Vietnam',
                                        'Yemen',
                                        'Zambia',
                                        'Zimbabwe',
                                    ];
                                ?>
                                <select class="form-select <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="countryInput"
                                    name="country">
                                    <option value="">— Select Country —</option>
                                    <?php $__currentLoopData = $allCountries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c); ?>"
                                            <?php echo e(old('country', 'India') === $c ? 'selected' : ''); ?>><?php echo e($c); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['country'];
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
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-coin me-1 text-warning"></i><?php echo e(__('messages.supplier_currency')); ?>

                                </label>
                                <select class="form-select <?php $__errorArgs = ['currency_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="currency_id"
                                    id="supplierCurrency">
                                    <option value="">— Same as system default —</option>
                                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cur->id); ?>" data-code="<?php echo e($cur->code); ?>"
                                            <?php echo e(old('currency_id') == $cur->id ? 'selected' : ''); ?>>
                                            <?php echo e($cur->symbol); ?> <?php echo e($cur->name); ?> (<?php echo e($cur->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="form-text" id="currencyAutoNote">
                                    <?php echo e(__('messages.supplier_currency_hint')); ?>

                                </div>
                                <?php $__errorArgs = ['currency_id'];
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

                
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-note text-secondary me-2"></i><?php echo e(__('messages.notes')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="notes"
                            placeholder="<?php echo e(__('messages.ph_notes')); ?>" rows="3"><?php echo e(old('notes')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
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

            
            <div class="col-lg-4 col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="bx bx-send text-primary me-2"></i><?php echo e(__('messages.publish')); ?>

                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block"><?php echo e(__('messages.status')); ?></label>
                            <div class="d-flex align-items-center gap-3">
                                <input name="status" type="hidden" value="inactive">
                                <div class="form-check form-switch mb-0">
                                    <input <?php echo e(old('status', 'active') === 'active' ? 'checked' : ''); ?>

                                        class="form-check-input" id="statusToggle" name="status" role="switch"
                                        type="checkbox" value="active">
                                </div>
                                <span
                                    class="fw-semibold <?php echo e(old('status', 'active') === 'active' ? 'text-success' : 'text-danger'); ?>"
                                    id="statusLabel">
                                    <?php echo e(old('status', 'active') === 'active' ? __('messages.active') : __('messages.inactive')); ?>

                                </span>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1"></i> <?php echo e(__('messages.save')); ?>

                            </button>
                            <a class="btn btn-outline-secondary" href="<?php echo e(route('suppliers.index')); ?>">
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
        const st = document.getElementById('statusToggle');
        if (st) {
            st.addEventListener('change', function() {
                const lbl = document.getElementById('statusLabel');
                lbl.textContent = this.checked ? '<?php echo e(__('messages.active')); ?>' : '<?php echo e(__('messages.inactive')); ?>';
                lbl.className = 'fw-semibold ' + (this.checked ? 'text-success' : 'text-danger');
            });
        }

        // ── Country → Currency auto-suggest ───────────────────────────────
        // Maps country name keywords (lowercase) to currency ISO code
        const countryCurrencyMap = {
            // Asia
            'india': 'INR',
            'bharat': 'INR',
            'china': 'CNY',
            'peoples republic of china': 'CNY',
            'japan': 'JPY',
            'south korea': 'KRW',
            'korea': 'KRW',
            'singapore': 'SGD',
            'hong kong': 'HKD',
            'pakistan': 'PKR',
            'bangladesh': 'BDT',
            'sri lanka': 'LKR',
            'ceylon': 'LKR',
            'nepal': 'NPR',
            'malaysia': 'MYR',
            'thailand': 'THB',
            'indonesia': 'IDR',
            'philippines': 'PHP',
            'vietnam': 'VND',
            'viet nam': 'VND',
            'uae': 'AED',
            'united arab emirates': 'AED',
            'dubai': 'AED',
            'abu dhabi': 'AED',
            'saudi arabia': 'SAR',
            'ksa': 'SAR',
            'qatar': 'QAR',
            'kuwait': 'KWD',
            'bahrain': 'BHD',
            'oman': 'OMR',
            'israel': 'ILS',
            'turkey': 'TRY',
            'turkiye': 'TRY',
            'iran': 'IRR',
            // Europe
            'germany': 'EUR',
            'france': 'EUR',
            'italy': 'EUR',
            'spain': 'EUR',
            'netherlands': 'EUR',
            'belgium': 'EUR',
            'austria': 'EUR',
            'portugal': 'EUR',
            'greece': 'EUR',
            'finland': 'EUR',
            'ireland': 'EUR',
            'luxembourg': 'EUR',
            'slovakia': 'EUR',
            'slovenia': 'EUR',
            'estonia': 'EUR',
            'latvia': 'EUR',
            'lithuania': 'EUR',
            'malta': 'EUR',
            'cyprus': 'EUR',
            'croatia': 'EUR',
            'united kingdom': 'GBP',
            'uk': 'GBP',
            'britain': 'GBP',
            'england': 'GBP',
            'great britain': 'GBP',
            'scotland': 'GBP',
            'wales': 'GBP',
            'switzerland': 'CHF',
            'norway': 'NOK',
            'sweden': 'SEK',
            'denmark': 'DKK',
            'poland': 'PLN',
            'czech republic': 'CZK',
            'czechia': 'CZK',
            'hungary': 'HUF',
            'romania': 'RON',
            'russia': 'RUB',
            'russian federation': 'RUB',
            'ukraine': 'UAH',
            // Americas
            'united states': 'USD',
            'usa': 'USD',
            'us': 'USD',
            'america': 'USD',
            'united states of america': 'USD',
            'canada': 'CAD',
            'mexico': 'MXN',
            'brazil': 'BRL',
            'argentina': 'ARS',
            'chile': 'CLP',
            'colombia': 'COP',
            // Oceania
            'australia': 'AUD',
            'new zealand': 'NZD',
            // Africa
            'south africa': 'ZAR',
            'nigeria': 'NGN',
            'kenya': 'KES',
            'egypt': 'EGP',
            'morocco': 'MAD',
            'ghana': 'GHS',
            'tanzania': 'TZS',
        };

        function autoSelectCurrency() {
            const country = (document.getElementById('countryInput').value || '').trim().toLowerCase();
            const sel = document.getElementById('supplierCurrency');
            const note = document.getElementById('currencyAutoNote');

            if (!country) {
                note.textContent = '<?php echo e(__('messages.supplier_currency_hint')); ?>';
                note.className = 'form-text';
                return;
            }

            // Exact match first, then partial fallback
            let matchedCode = countryCurrencyMap[country] || null;
            if (!matchedCode) {
                for (const [key, code] of Object.entries(countryCurrencyMap)) {
                    if (country.includes(key) || key.includes(country)) {
                        matchedCode = code;
                        break;
                    }
                }
            }

            if (!matchedCode) {
                note.textContent = '<?php echo e(__('messages.no_auto_match_currency')); ?>';
                note.className = 'form-text text-warning';
                return;
            }

            // Find the <option> with matching data-code and select it
            let matched = false;
            for (const opt of sel.options) {
                if (opt.dataset.code === matchedCode) {
                    sel.value = opt.value;
                    matched = true;
                    break;
                }
            }

            if (matched) {
                note.innerHTML =
                    '<span class="text-success fw-semibold"><i class="bx bx-check-circle me-1"></i>Auto-matched: <strong>' +
                    matchedCode + '</strong> based on country.</span>';
                // Highlight the select briefly
                sel.classList.add('border-success');
                setTimeout(() => sel.classList.remove('border-success'), 2000);
            } else {
                note.innerHTML = '<span class="text-warning">Currency code <strong>' + matchedCode +
                    '</strong> not found in DB — add it first.</span>';
            }
        }

        const countryInput = document.getElementById('countryInput');
        if (countryInput) {
            countryInput.addEventListener('change', function() {
                autoSelectCurrency();
            });
            // Also run on load if country already filled (old() value)
            if (countryInput.value.trim()) {
                autoSelectCurrency();
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/suppliers/create.blade.php ENDPATH**/ ?>