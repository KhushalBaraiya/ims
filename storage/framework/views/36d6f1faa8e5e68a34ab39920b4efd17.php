<?php
    $qty = (float) ($product->stock->quantity ?? 0);
    $alert = (float) ($product->minimum_stock_alert ?? 0);
    $isOut = $qty <= 0;
    $isLow = !$isOut && $qty <= $alert;
    $inactive = $product->status !== 'active';
    $profit = $product->selling_price - $product->purchase_price;
    $pct = $product->purchase_price > 0 ? round(($profit / $product->purchase_price) * 100, 1) : 0;

    $sCls = $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-success');
    $sBadge = $isOut ? 'bg-danger' : ($isLow ? 'bg-warning text-dark' : 'bg-success');
    $sLabel = $isOut ? __('messages.out_of_stock') : ($isLow ? __('messages.low_stock') : __('messages.in_stock'));
?>

<div class="col pc-item" data-subcat="<?php echo e($product->sub_category_id ?? ''); ?>">
    <div class="card pc-card shadow-sm <?php echo e($inactive ? 'opacity-75' : ''); ?>"
        onclick="window.location='<?php echo e(route('products.show', $product->id)); ?>'">

        
        <div class="pc-img-wrap">
            <?php if($product->image): ?>
                <img src="<?php echo e(asset('uploads/products/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" loading="lazy"
                    onerror="this.parentElement.innerHTML='<div class=\'pc-no-img\'><i class=\'bx bx-package\'></i><span><?php echo e(__('messages.no_image')); ?></span></div>'">
            <?php else: ?>
                <div class="pc-no-img">
                    <i class="bx bx-package"></i>
                    <span><?php echo e(__('messages.no_image')); ?></span>
                </div>
            <?php endif; ?>

            
            <div class="pc-tl">
                <span class="badge <?php echo e($sBadge); ?>" style="font-size:.6rem;"><?php echo e($sLabel); ?></span>
            </div>

            
            <?php if($inactive): ?>
                <div class="pc-tr">
                    <span class="badge bg-secondary" style="font-size:.58rem;"><?php echo e(__('messages.inactive')); ?></span>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="pc-body">
            
            <div class="mb-1" style="min-height:16px;">
                <?php if($product->subCategory): ?>
                    <span class="badge bg-label-secondary" style="font-size:.55rem;padding:.18em .42em;">
                        <?php echo e($product->subCategory->name); ?>

                    </span>
                <?php endif; ?>
                <?php if($product->brand): ?>
                    <span class="badge bg-label-primary" style="font-size:.55rem;padding:.18em .42em;">
                        <?php echo e($product->brand->name); ?>

                    </span>
                <?php endif; ?>
            </div>

            
            <h6 class="fw-bold mb-0 lh-sm" title="<?php echo e($product->name); ?>"
                style="font-size:.8rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                <?php echo e($product->name); ?>

            </h6>
            <p class="text-muted mb-2" style="font-size:.65rem;">
                <code style="font-size:.65rem;"><?php echo e($product->code); ?></code>
            </p>

            
            <div class="pc-price-box">
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <div
                            style="font-size:.57rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                            <?php echo e(__('messages.sell_label')); ?>

                        </div>
                        <div class="fw-bold text-primary lh-1" style="font-size:.92rem;">
                            <?php echo e(format_currency($product->selling_price)); ?>

                        </div>
                    </div>
                    <div class="text-end">
                        <div
                            style="font-size:.57rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.05em;line-height:1.2;">
                            <?php echo e(__('messages.cost_label')); ?>

                        </div>
                        <div class="text-muted fw-semibold lh-1" style="font-size:.73rem;">
                            <?php echo e(format_currency($product->purchase_price)); ?>

                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1 pt-1"
                    style="border-top:1px solid rgba(0,0,0,.07);">
                    <span style="font-size:.57rem;color:#bbb;font-weight:600;"><?php echo e(__('messages.profit_label')); ?></span>
                    <span class="fw-bold <?php echo e($pct >= 0 ? 'text-success' : 'text-danger'); ?>" style="font-size:.67rem;">
                        <?php echo e(format_currency($profit)); ?>

                        <span class="badge <?php echo e($pct >= 0 ? 'bg-success' : 'bg-danger'); ?>"
                            style="font-size:.52rem;padding:.14em .36em;">
                            <?php echo e($pct >= 0 ? '+' : ''); ?><?php echo e($pct); ?>%
                        </span>
                    </span>
                </div>
            </div>

            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-1">
                    <i class="bx bx-cube <?php echo e($sCls); ?>" style="font-size:.82rem;"></i>
                    <span class="fw-bold <?php echo e($sCls); ?>" style="font-size:.72rem;">
                        <?php echo e(number_format($qty, 0)); ?> <?php echo e($product->unit_code ?? 'pcs'); ?>

                    </span>
                </div>
                <?php if($product->tax_percentage): ?>
                    <span class="badge bg-label-warning" style="font-size:.54rem;padding:.16em .4em;">
                        GST <?php echo e($product->tax_percentage); ?>%
                    </span>
                <?php endif; ?>
            </div>

            
            <div class="d-flex gap-1 mt-auto" onclick="event.stopPropagation()">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-outline-secondary flex-fill"
                        style="font-size:.72rem;padding:.3rem .5rem;">
                        <i class="bx bx-show me-1"></i>View
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.update')): ?>
                    <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn btn-outline-primary flex-fill"
                        style="font-size:.72rem;padding:.3rem .5rem;">
                        <i class="bx bx-edit me-1"></i>Edit
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/products/_category_card.blade.php ENDPATH**/ ?>