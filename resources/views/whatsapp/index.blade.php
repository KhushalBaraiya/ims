@extends('layouts.admin')
@section('title', 'WhatsApp Broadcast')

@section('content')

    <style>
        /* ── Customer row — theme-aware highlight ── */
        .customer-row {
            cursor: pointer;
            border-bottom: 1px solid var(--bs-border-color);
            transition: background .12s;
        }

        .customer-row:last-child {
            border-bottom: none;
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

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="bx bxl-whatsapp text-success" style="font-size:1.6rem;"></i>
                WhatsApp Broadcast
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">WhatsApp</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('whatsapp.create') }}" class="btn btn-success">
            <i class="bx bx-plus me-1"></i> New Template
        </a>
    </div>

    <div class="row g-4">

        {{-- ── LEFT : Templates ─────────────────────────────────────────── --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="wa-card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bx-file-blank text-success me-2"></i>Message Templates
                    </h6>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        {{ $templates->count() }} templates
                    </span>
                </div>
                <div class="card-body p-0">
                    @if ($templates->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-message-square-dots" style="font-size:2.5rem;opacity:.3;"></i>
                            <p class="small mt-2 mb-0">No templates yet.
                                <a href="{{ route('whatsapp.create') }}">Create one</a>
                            </p>
                        </div>
                    @else
                        <div class="list-group list-group-flush" id="templateList">
                            @foreach ($templates as $tpl)
                                <div class="list-group-item list-group-item-action template-item px-4 py-3"
                                    data-id="{{ $tpl->id }}" data-message="{{ $tpl->message }}">
                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong class="text-truncate"
                                                    style="max-width:160px;">{{ $tpl->name }}</strong>
                                                <span
                                                    class="badge rounded-pill {{ $tpl->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}"
                                                    style="font-size:10px;">
                                                    {{ ucfirst($tpl->status) }}
                                                </span>
                                            </div>
                                            <p class="text-muted small mb-0 text-truncate" style="max-width:220px;">
                                                {{ $tpl->message }}
                                            </p>
                                        </div>
                                        <div class="d-flex gap-1 flex-shrink-0">
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-success rounded-circle use-template-btn"
                                                title="Use this template" data-message="{{ $tpl->message }}"
                                                style="width:28px;height:28px;padding:0;">
                                                <i class="bx bx-check" style="font-size:.9rem;"></i>
                                            </button>
                                            <a href="{{ route('whatsapp.edit', $tpl->id) }}"
                                                class="btn btn-sm btn-icon btn-outline-primary rounded-circle"
                                                title="Edit" style="width:28px;height:28px;padding:0;">
                                                <i class="bx bx-edit" style="font-size:.9rem;"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-danger rounded-circle delete-tpl-btn"
                                                title="Delete" data-id="{{ $tpl->id }}"
                                                data-name="{{ $tpl->name }}" style="width:28px;height:28px;padding:0;">
                                                <i class="bx bx-trash" style="font-size:.9rem;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── RIGHT : Broadcast Panel ───────────────────────────────────── --}}
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="wa-card-header">
                    <h6 class="fw-semibold mb-0">
                        <i class="bx bxl-whatsapp text-success me-2"></i>Send Broadcast Message
                    </h6>
                </div>
                <div class="card-body p-4">

                    {{-- Message textarea --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Message
                            <span class="text-muted fw-normal small">(click ✓ on a template to auto-fill)</span>
                        </label>
                        <textarea id="broadcastMessage" class="form-control" rows="4"
                            placeholder="Type your message or select a template..."></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="form-text">Message will open WhatsApp for each selected customer.</div>
                            <div class="form-text"><span id="msgCharCount">0</span> chars</div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Customers --}}
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-semibold mb-0">
                                Customers
                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">
                                    <span
                                        id="selectedCount">{{ $customers->count() }}</span>&nbsp;/&nbsp;{{ $customers->count() }}
                                    selected
                                </span>
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-success" id="selectAllCustomers">
                                    <i class="bx bx-check-double me-1"></i>All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllCustomers">
                                    <i class="bx bx-x me-1"></i>None
                                </button>
                            </div>
                        </div>

                        @if ($customers->isEmpty())
                            <div class="text-center py-4 text-muted border rounded-3">
                                <i class="bx bx-user-x" style="font-size:2rem;opacity:.3;"></i>
                                <p class="small mt-2 mb-0">No active customers with phone numbers found.</p>
                            </div>
                        @else
                            {{-- Search --}}
                            <div class="input-group mb-2">
                                <span class="input-group-text border-end-0">
                                    <i class="bx bx-search text-muted"></i>
                                </span>
                                <input type="text" id="customerSearch" class="form-control border-start-0"
                                    placeholder="Search name or phone...">
                            </div>

                            {{-- List --}}
                            <div id="customerListBox">
                                @foreach ($customers as $customer)
                                    <label class="customer-row is-checked d-flex align-items-center gap-3 px-3 py-2 mb-0"
                                        data-name="{{ strtolower($customer->name) }}"
                                        data-phone="{{ $customer->phone }}">
                                        <input type="checkbox" class="form-check-input flex-shrink-0 customer-checkbox"
                                            value="{{ $customer->phone }}" data-name="{{ $customer->name }}" checked
                                            style="width:17px;height:17px;accent-color:#25d366;cursor:pointer;flex-shrink:0;">
                                        <span class="wa-avatar">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                        <span class="flex-grow-1" style="min-width:0;">
                                            <span class="fw-semibold d-block text-truncate"
                                                style="font-size:.875rem;">{{ $customer->name }}</span>
                                            <span class="text-muted"
                                                style="font-size:.78rem;">{{ $customer->phone }}</span>
                                        </span>
                                        <i class="bx bx-check-circle text-success check-icon ms-auto"
                                            style="font-size:1.15rem;flex-shrink:0;"></i>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <button type="button" id="clearMsgBtn" class="btn btn-outline-secondary">
                            <i class="bx bx-eraser me-1"></i> Clear
                        </button>
                        <button type="button" id="broadcastSendBtn" class="btn btn-success fw-semibold px-4"
                            style="background:#25d366;border-color:#25d366;">
                            <i class="bx bxl-whatsapp me-1" style="font-size:1.1rem;"></i>
                            Send to Selected
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Send Progress Modal --}}
    <div class="modal fade" id="sendProgressModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 px-4 pt-4 pb-3"
                    style="background:linear-gradient(135deg,#25d366,#128c7e);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bxl-whatsapp text-white" style="font-size:1.5rem;"></i>
                        <h6 class="modal-title text-white fw-bold mb-0">Sending Messages</h6>
                    </div>
                </div>
                <div class="modal-body px-4 py-4 text-center">
                    <p class="text-muted small mb-3">
                        WhatsApp opens for each customer one by one.<br>
                        Send the message and click <strong>Next</strong>.
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
                        <i class="bx bx-chevron-left me-1"></i> Prev
                    </button>
                    <button type="button" class="btn btn-success" id="nextRecipient"
                        style="min-width:90px;background:#25d366;border-color:#25d366;">
                        Next <i class="bx bx-chevron-right ms-1"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger d-none" id="closeSendModal"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

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
                const q = $(this).val().toLowerCase();
                $('.customer-row').each(function() {
                    const match = ($(this).data('name') || '').includes(q) ||
                        ($(this).data('phone') || '').includes(q);
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
                    title: 'Delete Template?',
                    text: `"${name}" will be permanently deleted.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                }).then(r => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '/whatsapp/' + id,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: res => {
                                if (res.success) {
                                    row.fadeOut(300, () => row.remove());
                                    showAdminToast('Template deleted.', 'success');
                                }
                            },
                            error: () => showAdminToast('Error deleting template.',
                                'error'),
                        });
                    }
                });
            });

            // ── Broadcast Send ────────────────────────────────────────────────────
            $('#broadcastSendBtn').on('click', function() {
                const message = $('#broadcastMessage').val().trim();
                if (!message) {
                    showAdminToast('Please enter a message first.', 'warning');
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
                    showAdminToast('Please select at least one customer.', 'warning');
                    return;
                }

                let current = 0;
                const total = recipients.length;

                function updateProgress() {
                    const pct = Math.round((current / total) * 100);
                    $('#sendProgressBar').css('width', pct + '%');
                    $('#sendProgressText').text(current + ' / ' + total);
                    if (current >= total) {
                        $('#sendProgressName').text('All done! 🎉');
                        $('#nextRecipient, #prevRecipient').addClass('d-none');
                        $('#closeSendModal').removeClass('d-none');
                    } else {
                        const r = recipients[current];
                        $('#sendProgressName').text('→ ' + r.name + '  (' + r.phone + ')');
                        $('#closeSendModal').addClass('d-none');
                        $('#nextRecipient').removeClass('d-none');
                    }
                }

                function openWA(idx) {
                    if (idx >= total) return;
                    const phone = recipients[idx].phone.toString().replace(/[\s\-\+\(\)]/g, '');
                    window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message),
                    '_blank');
                }

                updateProgress();
                openWA(0);
                new bootstrap.Modal(document.getElementById('sendProgressModal')).show();

                $('#nextRecipient').off('click').on('click', function() {
                    current++;
                    updateProgress();
                    if (current < total) openWA(current);
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
@endpush
