
<div class="mb-3">
    <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        placeholder="e.g. Order Confirmed, Delivery Update" value="<?php echo e(old('name', $template->name ?? '')); ?>" required>
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
    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
    <textarea name="message" id="tplMessage" class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="6"
        placeholder="Type your WhatsApp message here..." required><?php echo e(old('message', $template->message ?? '')); ?></textarea>
    <div class="d-flex justify-content-between mt-1">
        <div class="form-text text-muted">Use plain text. Emojis are supported 😊</div>
        <div class="form-text text-muted"><span id="tplCharCount">0</span> chars</div>
    </div>
    <?php $__errorArgs = ['message'];
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


<div class="mb-4">
    <div class="small text-muted fw-semibold mb-2">Quick Insert:</div>
    <div class="d-flex flex-wrap gap-2">
        <?php $__currentLoopData = ['👋', '✅', '🚚', '💰', '🎉', '⚠️', '📦', '🙏', '💬', '📞']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emoji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" class="btn btn-sm btn-outline-secondary emoji-btn" data-emoji="<?php echo e($emoji); ?>"
                style="font-size:1rem;padding:2px 8px;"><?php echo e($emoji); ?></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="mb-4">
    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
        <option value="active" <?php echo e(old('status', $template->status ?? 'active') === 'active' ? 'selected' : ''); ?>>
            Active</option>
        <option value="inactive" <?php echo e(old('status', $template->status ?? '') === 'inactive' ? 'selected' : ''); ?>>Inactive
        </option>
    </select>
    <?php $__errorArgs = ['status'];
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

<div class="d-flex justify-content-end gap-2 border-top pt-3 mt-2">
    <a href="<?php echo e(route('whatsapp.index')); ?>" class="btn btn-outline-secondary">
        <i class="bx bx-x me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-success">
        <i class="bx bx-save me-1"></i>
        <?php echo e(isset($template) && $template->exists ? 'Update Template' : 'Save Template'); ?>

    </button>
</div>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Char count
        const ta = document.getElementById('tplMessage');
        const cc = document.getElementById('tplCharCount');
        if (ta && cc) {
            cc.textContent = ta.value.length;
            ta.addEventListener('input', () => cc.textContent = ta.value.length);
        }

        // Emoji insert
        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const emoji = this.dataset.emoji;
                const pos = ta.selectionStart;
                const val = ta.value;
                ta.value = val.slice(0, pos) + emoji + val.slice(pos);
                ta.selectionStart = ta.selectionEnd = pos + emoji.length;
                ta.focus();
                cc.textContent = ta.value.length;
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/whatsapp/form.blade.php ENDPATH**/ ?>