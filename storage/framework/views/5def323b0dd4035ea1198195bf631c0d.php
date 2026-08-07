
<?php $__env->startSection('title', __('messages.wa_broadcast_title')); ?>

<?php $__env->startSection('content'); ?>

    <style>
        /* ── Customer row — theme-aware highlight ── */
        .customer-row-wrapper {
            border-bottom: 1px solid var(--bs-border-color);
        }

        .customer-row-wrapper:last-child {
            border-bottom: none;
        }

        .customer-row {
            cursor: pointer;
            transition: background .12s;
            width: 100%;
        }

        .customer-row:hover {
            background: var(--bs-tertiary-bg) !important;
        }

        .customer-row.is-checked {
            background: rgba(37, 211, 102, .12) !important;
        }

        /* Avatar circle */
        .wa-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #25d366;
            color: #fff;
            font-size: .78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Customer list box */
        #customerListBox {
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            max-height: 230px;
            overflow-y: auto;
        }

        /* Search */
        #customerSearch {
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        /* Template list item hover */
        .template-item:hover {
            background: var(--bs-tertiary-bg);
        }

        /* Card headers — let theme handle bg */
        .wa-card-header {
            border-bottom: 1px solid var(--bs-border-color);
            padding: .85rem 1.25rem;
        }
    </style>

    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="bx bxl-whatsapp text-success" style="font-size:1.6rem;"></i>
                <?php echo e(__('messages.wa_broadcast_title')); ?>

            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.wa_whatsapp_breadcrumb')); ?></li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('whatsapp.create')); ?>" class="btn btn-success">
            <i class="bx bx-plus me-1"></i> <?php echo e(__('messages.wa_new_template')); ?>

        </a>
    </div>

    <div class="row g-4">

        
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="wa-card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-file-blank text-success me-2"></i><?php echo e(__('messages.wa_message_templates')); ?>

                    </h6>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <?php echo e($templates->count()); ?> <?php echo e(__('messages.wa_templates_count')); ?>

                    </span>
                </div>
                <div class="card-body p-0">
                    <?php if($templates->isEmpty()): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-message-square-dots" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="small mt-2 mb-0"><?php echo e(__('messages.wa_no_templates')); ?>

                                <a href="<?php echo e(route('whatsapp.create')); ?>"><?php echo e(__('messages.wa_create_one')); ?></a>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush" id="templateList">
                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tpl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item list-group-item-action template-item px-4 py-3"
                                    data-id="<?php echo e($tpl->id); ?>" data-message="<?php echo e($tpl->message); ?>">
                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong class="text-truncate"
                                                    style="max-width:160px;"><?php echo e($tpl->name); ?></strong>
                                                <span
                                                    class="badge rounded-pill <?php echo e($tpl->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'); ?>"
                                                    style="font-size:10px;">
                                                    <?php echo e($tpl->status === 'active' ? __('messages.wa_active_option') : __('messages.wa_inactive_option')); ?>

                                                </span>
                                            </div>
                                            <p class="text-muted small mb-0 text-truncate" style="max-width:220px;">
                                                <?php echo e($tpl->message); ?>

                                            </p>
                                        </div>
                                        <div class="d-flex gap-1 flex-shrink-0">
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle use-template-btn"
                                                title="<?php echo e(__('messages.wa_send_btn')); ?>"
                                                data-message="<?php echo e($tpl->message); ?>"
                                                style="width:28px;height:28px;padding:0;">
                                                <i class="bx bx-check" style="font-size:.9rem;"></i>
                                            </button>
                                            <a href="<?php echo e(route('whatsapp.edit', $tpl->id)); ?>"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle"
                                                title="<?php echo e(__('messages.edit')); ?>"
                                                style="width:28px;height:28px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:.9rem;"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-danger rounded-circle delete-tpl-btn"
                                                title="<?php echo e(__('messages.delete')); ?>" data-id="<?php echo e($tpl->id); ?>"
                                                data-name="<?php echo e($tpl->name); ?>" style="width:28px;height:28px;padding:0;">
                                                <i class="bx bx-trash" style="font-size:.9rem;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="wa-card-header">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bxl-whatsapp text-success me-2"></i><?php echo e(__('messages.wa_send_broadcast')); ?>

                    </h6>
                </div>
                <div class="card-body p-4">

                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <?php echo e(__('messages.wa_message_label')); ?>

                            <span class="text-muted fw-normal small"><?php echo e(__('messages.wa_message_hint')); ?></span>
                        </label>
                        <textarea id="broadcastMessage" class="form-control" rows="4"
                            placeholder="<?php echo e(__('messages.wa_message_placeholder')); ?>"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="form-text"><?php echo e(__('messages.wa_message_info')); ?></div>
                            <div class="form-text"><span id="msgCharCount">0</span> <?php echo e(__('messages.wa_chars_label')); ?>

                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-semibold mb-0">
                                <?php echo e(__('messages.wa_customers_label')); ?>

                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">
                                    <span
                                        id="selectedCount"><?php echo e($customers->count()); ?></span>&nbsp;/&nbsp;<?php echo e($customers->count()); ?>

                                    <?php echo e(__('messages.wa_selected_label')); ?>

                                </span>
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-success" id="selectAllCustomers">
                                    <i class="bx bx-check-double me-1"></i><?php echo e(__('messages.wa_select_all')); ?>

                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllCustomers">
                                    <i class="bx bx-x me-1"></i><?php echo e(__('messages.wa_deselect_all')); ?>

                                </button>
                            </div>
                        </div>

                        <?php if($customers->isEmpty()): ?>
                            <div class="text-center py-4 text-muted border rounded-3">
                                <i class="bx bx-user-x" style="font-size:2rem;opacity:.3;"></i>
                                <p class="small mt-2 mb-0"><?php echo e(__('messages.wa_no_customers')); ?></p>
                            </div>
                        <?php else: ?>
                            
                            <div class="input-group mb-2">
                                <span class="input-group-text border-end-0">
                                    <i class="bx bx-search text-muted"></i>
                                </span>
                                <input type="text" id="customerSearch" class="form-control border-start-0"
                                    placeholder="<?php echo e(__('messages.wa_search_placeholder')); ?>">
                            </div>

                            
                            <div id="customerListBox">
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="customer-row-wrapper" data-name="<?php echo e(strtolower($customer->name)); ?>"
                                        data-phone="<?php echo e($customer->phone); ?>">
                                        <label
                                            class="customer-row is-checked d-flex align-items-center gap-3 px-3 py-2 mb-0">
                                            <input type="checkbox"
                                                class="form-check-input flex-shrink-0 customer-checkbox"
                                                value="<?php echo e($customer->phone); ?>" data-name="<?php echo e($customer->name); ?>" checked
                                                style="width:17px;height:17px;accent-color:#25d366;cursor:pointer;flex-shrink:0;">
                                            <span class="wa-avatar">
                                                <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

                                            </span>
                                            <span class="flex-grow-1" style="min-width:0;">
                                                <span class="fw-semibold d-block text-truncate"
                                                    style="font-size:.875rem;"><?php echo e($customer->name); ?></span>
                                                <span class="text-muted"
                                                    style="font-size:.78rem;"><?php echo e($customer->phone); ?></span>
                                            </span>
                                            <i class="bx bx-check-circle text-success check-icon ms-auto"
                                                style="font-size:1.15rem;flex-shrink:0;"></i>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <button type="button" id="clearMsgBtn" class="btn btn-outline-secondary">
                            <i class="bx bx-eraser me-1"></i> <?php echo e(__('messages.wa_clear_btn')); ?>

                        </button>
                        <button type="button" id="broadcastSendBtn" class="btn btn-success fw-semibold px-4"
                            style="background:#25d366;border-color:#25d366;">
                            <i class="bx bxl-whatsapp me-1" style="font-size:1.1rem;"></i>
                            <?php echo e(__('messages.wa_send_btn')); ?>

                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    
    <div class="modal fade" id="sendProgressModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 px-4 pt-4 pb-3"
                    style="background:linear-gradient(135deg,#25d366,#128c7e);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bxl-whatsapp text-white" style="font-size:1.5rem;"></i>
                        <h6 class="modal-title text-white fw-bold mb-0"><?php echo e(__('messages.wa_sending_title')); ?></h6>
                    </div>
                </div>
                <div class="modal-body px-4 py-4 text-center">
                    <p class="text-muted small mb-3">
                        <?php echo e(__('messages.wa_send_info')); ?><br>
                        <?php echo e(__('messages.wa_send_next_hint')); ?> <strong><?php echo e(__('messages.wa_next_btn')); ?></strong>.
                    </p>
                    <div class="progress mb-3" style="height:8px;border-radius:8px;">
                        <div class="progress-bar" id="sendProgressBar" role="progressbar"
                            style="width:0%;border-radius:8px;background:#25d366;"></div>
                    </div>
                    <div class="fw-semibold fs-6" id="sendProgressText">0 / 0</div>
                    <div class="text-muted small mt-1" id="sendProgressName">—</div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="prevRecipient" style="min-width:90px;">
                        <i class="bx bx-chevron-left me-1"></i> <?php echo e(__('messages.wa_prev_btn')); ?>

                    </button>
                    <button type="button" class="btn btn-success" id="nextRecipient"
                        style="min-width:90px;background:#25d366;border-color:#25d366;">
                        <?php echo e(__('messages.wa_next_btn')); ?> <i class="bx bx-chevron-right ms-1"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger d-none" id="closeSendModal"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> <?php echo e(__('messages.wa_done_btn')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {

            const waAllDone = <?php echo json_encode(__('messages.wa_all_done'), 15, 512) ?>;
            const waDeleteTitle = <?php echo json_encode(__('messages.wa_delete_template_title'), 15, 512) ?>;
            const waYesDelete = <?php echo json_encode(__('messages.wa_yes_delete'), 15, 512) ?>;
            const waCancel = <?php echo json_encode(__('messages.cancel'), 15, 512) ?>;
            const waTplDeleted = <?php echo json_encode(__('messages.wa_template_deleted'), 15, 512) ?>;
            const waDeleteErr = <?php echo json_encode(__('messages.wa_delete_error'), 15, 512) ?>;
            const waEnterMsg = <?php echo json_encode(__('messages.wa_enter_message'), 15, 512) ?>;
            const waSelectCust = <?php echo json_encode(__('messages.wa_select_customer'), 15, 512) ?>;

            // ── Template → fill message ───────────────────────────────────────────
            $(document).on('click', '.use-template-btn', function() {
                $('#broadcastMessage').val($(this).data('message'));
                updateCharCount();
                $(this).addClass('btn-success').removeClass('btn-outline-success');
                setTimeout(() => $(this).removeClass('btn-success').addClass('btn-outline-success'), 900);
            });

            // ── Char count ────────────────────────────────────────────────────────
            function updateCharCount() {
                $('#msgCharCount').text($('#broadcastMessage').val().length);
            }
            $('#broadcastMessage').on('input', updateCharCount);

            // ── Row style (checked = highlighted, unchecked = normal) ────────────
            function applyRowStyle(cb) {
                const row = $(cb).closest('.customer-row');
                const icon = row.find('.check-icon');
                if (cb.checked) {
                    row.addClass('is-checked');
                    icon.show();
                } else {
                    row.removeClass('is-checked');
                    icon.hide();
                }
            }

            // Init on load
            $('.customer-checkbox').each(function() {
                applyRowStyle(this);
            });

            $(document).on('change', '.customer-checkbox', function() {
                applyRowStyle(this);
                updateCount();
            });

            function updateCount() {
                $('#selectedCount').text($('.customer-checkbox:checked').length);
            }

            // ── Select / None ─────────────────────────────────────────────────────
            $('#selectAllCustomers').on('click', function() {
                $('.customer-checkbox').prop('checked', true).each(function() {
                    applyRowStyle(this);
                });
                updateCount();
            });
            $('#deselectAllCustomers').on('click', function() {
                $('.customer-checkbox').prop('checked', false).each(function() {
                    applyRowStyle(this);
                });
                updateCount();
            });

            // ── Search ────────────────────────────────────────────────────────────
            $('#customerSearch').on('input', function() {
                const q = $(this).val().toLowerCase().trim();
                if (q === '') {
                    $('.customer-row-wrapper').show();
                    return;
                }
                $('.customer-row-wrapper').each(function() {
                    const name = ($(this).attr('data-name') || '').toLowerCase();
                    const phone = ($(this).attr('data-phone') || '').replace(/\s/g, '')
                        .toLowerCase();
                    const qClean = q.replace(/\s/g, '');
                    const match = name.includes(q) || phone.includes(qClean);
                    $(this).toggle(match);
                });
            });

            // ── Clear message ─────────────────────────────────────────────────────
            $('#clearMsgBtn').on('click', function() {
                $('#broadcastMessage').val('');
                updateCharCount();
            });

            // ── Delete template ───────────────────────────────────────────────────
            $(document).on('click', '.delete-tpl-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const row = $(this).closest('.list-group-item');
                Swal.fire({
                    title: waDeleteTitle,
                    text: `"${name}" ${<?php echo json_encode(__('messages.wa_delete_template_text'), 15, 512) ?>}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: waYesDelete,
                    cancelButtonText: waCancel,
                }).then(r => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '/whatsapp/' + id,
                            type: 'POST',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>',
                                _method: 'DELETE'
                            },
                            success: res => {
                                if (res.success) {
                                    row.fadeOut(300, () => row.remove());
                                    showAdminToast(waTplDeleted, 'success');
                                }
                            },
                            error: () => showAdminToast(waDeleteErr, 'error'),
                        });
                    }
                });
            });

            // ── Broadcast Send ────────────────────────────────────────────────────
            $('#broadcastSendBtn').on('click', function() {
                const message = $('#broadcastMessage').val().trim();
                if (!message) {
                    showAdminToast(waEnterMsg, 'warning');
                    return;
                }

                const recipients = [];
                $('.customer-checkbox:checked').each(function() {
                    recipients.push({
                        phone: $(this).val(),
                        name: $(this).data('name')
                    });
                });
                if (!recipients.length) {
                    showAdminToast(waSelectCust, 'warning');
                    return;
                }

                let current = 0;
                const total = recipients.length;

                function updateProgress() {
                    const pct = Math.round(((current + 1) / total) * 100);
                    $('#sendProgressBar').css('width', pct + '%');
                    if (current >= total) {
                        $('#sendProgressText').text(total + ' / ' + total);
                        $('#sendProgressName').text(waAllDone);
                        $('#nextRecipient, #prevRecipient').addClass('d-none');
                        $('#closeSendModal').removeClass('d-none');
                        $('#sendProgressBar').css('width', '100%');
                    } else {
                        const r = recipients[current];
                        $('#sendProgressText').text((current + 1) + ' / ' + total);
                        $('#sendProgressName').text('→ ' + r.name + '  (' + r.phone + ')');
                        $('#closeSendModal').addClass('d-none');
                        $('#nextRecipient').removeClass('d-none');
                        // Hide prev on first item
                        if (current === 0) {
                            $('#prevRecipient').addClass('d-none');
                        } else {
                            $('#prevRecipient').removeClass('d-none');
                        }
                    }
                }

                function openWA(idx) {
                    if (idx >= total) return;
                    const raw = recipients[idx].phone.toString().replace(/[\s\-\+\(\)]/g, '');
                    // Ensure phone starts with country code (add 91 if 10 digits for India)
                    const phone = (raw.length === 10 && /^[6-9]/.test(raw)) ? '91' + raw : raw;
                    window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message),
                    '_blank');
                }

                updateProgress();
                openWA(0);
                new bootstrap.Modal(document.getElementById('sendProgressModal')).show();

                $('#nextRecipient').off('click').on('click', function() {
                    current++;
                    if (current < total) {
                        updateProgress();
                        openWA(current);
                    } else {
                        updateProgress(); // show done state
                    }
                });
                $('#prevRecipient').off('click').on('click', function() {
                    if (current > 0) {
                        current--;
                        updateProgress();
                        openWA(current);
                    }
                });
            });

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/whatsapp/index.blade.php ENDPATH**/ ?>