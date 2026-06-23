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
<!-- DataTables Responsive Extension -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery Validate -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')

<script>
    // Global password toggle — used across all admin forms
    function togglePwd(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }

    // Global status toggle — pill badge AJAX click (disabled - route not defined in this project)
    // $(document).on('click', '.status-toggle-btn', function() { });

    (function() {
        const themeToggle = document.getElementById('adminThemeToggle');
        const html = document.documentElement;

        function setTheme(theme) {
            const safeTheme = theme === 'dark' ? 'dark' : 'light';
            const isDark = safeTheme === 'dark';

            html.setAttribute('data-bs-theme', safeTheme);

            if (themeToggle) {
                const icon = themeToggle.querySelector('i');
                themeToggle.setAttribute('title', isDark ? 'Light mode' : 'Dark mode');

                if (icon) {
                    icon.className = isDark ? 'bx bx-sun' : 'bx bx-moon';
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

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ session('success') }}', 'success');
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ session('error') }}', 'error');
        });
    </script>
@endif

@if (session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAdminToast('{{ session('warning') }}', 'warning');
        });
    </script>
@endif

<script>
    function showAdminToast(message, type) {
        const old = document.getElementById('adminToast');
        if (old) old.remove();

        const icons = {
            success: '<i class="bx bx-check-circle"></i>',
            error: '<i class="bx bx-x-circle"></i>',
            warning: '<i class="bx bx-error"></i>',
        };

        const toast = document.createElement('div');
        toast.id = 'adminToast';
        toast.className = 'toast-' + (type || 'success');
        toast.innerHTML =
            '<span class="toast-icon">' + (icons[type] || icons.success) + '</span>' +
            '<span class="toast-msg">' + message + '</span>' +
            '<div class="toast-progress"></div>';
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
</script>
