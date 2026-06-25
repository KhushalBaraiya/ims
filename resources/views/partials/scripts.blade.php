<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

<!-- Github Button -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery Validate -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- ─── Global utilities ──────────────────────────────────────────────────── --}}
<script>
    function togglePwd(id, btn) {
        var input = document.getElementById(id);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }

    function showAdminToast(message, type) {
        var old = document.getElementById('adminToast');
        if (old) old.remove();
        var icons = {
            success: '<i class="bx bx-check-circle"></i>',
            error: '<i class="bx bx-x-circle"></i>',
            warning: '<i class="bx bx-error"></i>'
        };
        var toast = document.createElement('div');
        toast.id = 'adminToast';
        toast.className = 'toast-' + (type || 'success');
        toast.innerHTML = '<span class="toast-icon">' + (icons[type] || icons.success) +
            '</span><span class="toast-msg">' + message + '</span><div class="toast-progress"></div>';
        toast.onclick = function() {
            toast.remove();
        };
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.style.transition = 'opacity 0.4s ease';
            toast.style.opacity = '0';
            setTimeout(function() {
                if (toast.parentNode) toast.remove();
            }, 400);
        }, 3000);
    }

    (function() {
        var themeToggle = document.getElementById('adminThemeToggle');
        var html = document.documentElement;

        function setTheme(theme) {
            var safeTheme = theme === 'dark' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', safeTheme);
            if (themeToggle) {
                var icon = themeToggle.querySelector('i');
                themeToggle.setAttribute('title', safeTheme === 'dark' ? 'Light mode' : 'Dark mode');
                if (icon) {
                    icon.className = safeTheme === 'dark' ? 'bx bx-sun' : 'bx bx-moon';
                    icon.style.fontSize = '1.2rem';
                }
            }
            try {
                localStorage.setItem('admin-theme', safeTheme);
            } catch (e) {}
        }
        setTheme(html.getAttribute('data-bs-theme'));
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                setTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
            });
        }
    })();
</script>

{{-- ─── Global Flatpickr Auto-Init ───────────────────────────────────────── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function fpConfig(el) {
            // Copy all Bootstrap classes (form-control, form-control-sm etc.) to altInput
            var classes = Array.from(el.classList)
                .filter(function(c) {
                    return c !== 'flatpickr-date' && c !== 'flatpickr-filter-date';
                })
                .join(' ');
            return {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-m-Y',
                altInputClass: classes,
                allowInput: true,
                disableMobile: true,
                appendTo: document.body,
                locale: {
                    firstDayOfWeek: 1
                },
                onReady: function(selectedDates, dateStr, instance) {
                    instance.altInput.placeholder = 'Select Date';
                    instance.altInput.style.cursor = 'pointer';
                    // Wrap in input-group to add calendar icon
                    var wrapper = instance.altInput.parentNode;
                    if (wrapper && !wrapper.classList.contains('fp-wrapper')) {
                        var grp = document.createElement('div');
                        grp.className = 'input-group fp-wrapper';
                        wrapper.insertBefore(grp, instance.altInput);
                        grp.appendChild(instance.altInput);
                        var span = document.createElement('span');
                        span.className = 'input-group-text';
                        span.style.cursor = 'pointer';
                        span.innerHTML =
                            '<i class="bx bx-calendar" style="font-size:1rem;color:#697a8d;"></i>';
                        span.addEventListener('click', function() {
                            instance.open();
                        });
                        grp.appendChild(span);
                    }
                }
            };
        }

        function initFlatpickr(context) {
            var root = context || document;

            root.querySelectorAll('.flatpickr-date:not([data-fp]), .flatpickr-filter-date:not([data-fp])')
                .forEach(function(el) {
                    el.setAttribute('data-fp', '1');
                    flatpickr(el, fpConfig(el));
                });
        }

        initFlatpickr(null);

        document.addEventListener('shown.bs.modal', function(e) {
            initFlatpickr(e.target);
        });
    });
</script>

{{-- ─── Page-specific scripts ──────────────────────────────────────────────── --}}
@stack('scripts')

{{-- ─── Global Select2 Auto-Init ─────────────────────────────────────────── --}}
<script>
    $(document).ready(function() {

        function s2Init(context) {
            var $selects = context ? $(context).find('select').addBack('select') : $('select');
            $selects
                .not('[data-no-select2]')
                .not('.dataTables_filter select')
                .not('.dataTables_length select')
                .not('.select2-hidden-accessible')
                .each(function() {
                    var $el = $(this);
                    var $modal = $el.closest('.modal');
                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: true,
                        placeholder: $el.find('option[value=""]').first().text().trim() ||
                            'Select…',
                        dropdownParent: $modal.length ? $modal : $('body'),
                    });
                    if ($el.hasClass('is-invalid')) {
                        $el.next('.select2-container').addClass('is-invalid');
                    }
                });
        }

        // Run after a tiny delay so all other $(document).ready() callbacks
        // (DataTables, page-specific scripts) complete first
        setTimeout(function() {
            s2Init(null);
        }, 0);

        // Re-init when a Bootstrap modal opens
        $(document).on('shown.bs.modal', function(e) {
            setTimeout(function() {
                s2Init(e.target);
            }, 0);
        });

        // Manual re-init: $(mySelect).trigger('s2:rebuild')
        $(document).on('s2:rebuild', 'select', function() {
            var $el = $(this);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            setTimeout(function() {
                s2Init($el.parent()[0]);
            }, 0);
        });

        // Auto-focus search box when Select2 opens
        $(document).on('select2:open', function() {
            setTimeout(function() {
                var sf = document.querySelector(
                    '.select2-container--open .select2-search__field');
                if (sf) sf.focus();
            }, 50);
        });

    });
</script>

{{-- ─── Session flash toasts ──────────────────────────────────────────────── --}}
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ addslashes(session('success')) }}', 'success');
        });
    </script>
@endif
@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ addslashes(session('error')) }}', 'error');
        });
    </script>
@endif
@if (session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ addslashes(session('warning')) }}', 'warning');
        });
    </script>
@endif
