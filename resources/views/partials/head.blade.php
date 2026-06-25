<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        name="viewport" />
    <meta content="{{ csrf_token() }}" name="csrf-token" />

    <title>@yield('title', 'Admin Dashboard')</title>
    <meta content="" name="description" />

    <script>
        (function() {
            try {
                var theme = localStorage.getItem('admin-theme') || 'light';
                document.documentElement.setAttribute('data-bs-theme', theme === 'dark' ? 'dark' : 'light');
            } catch (e) {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>

    <!-- Favicon -->
    <link href="{{ asset('assets/img/favicon/favicon.ico') }}" rel="icon" type="image/x-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Noto+Sans+Gujarati:wght@400;500;600;700&family=Noto+Sans+Devanagari:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    {{-- Apply a font stack that covers Latin + Gujarati + Devanagari --}}
    <style>
        :root {
            --bs-body-font-family: 'Public Sans', 'Noto Sans Gujarati', 'Noto Sans Devanagari', sans-serif;
        }

        body,
        .menu-text,
        .card,
        .table,
        .form-control,
        .form-select,
        .btn {
            font-family: var(--bs-body-font-family) !important;
        }
    </style>

    <!-- Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- Template Icons -->
    <link href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" rel="stylesheet" />

    <!-- Core CSS -->
    <link href="{{ asset('assets/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/admin-custom.css') }}" rel="stylesheet" />

    <!-- Vendors CSS -->
    <link href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" rel="stylesheet" />

    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        /* ===== Flatpickr — altInput Bootstrap style ===== */
        .flatpickr-input[readonly] {
            display: none !important;
        }

        input.flatpickr-input.form-control,
        input.flatpickr-input.form-control-sm {
            background-color: #fff !important;
            cursor: pointer !important;
        }

        /* Calendar popup */
        .flatpickr-calendar {
            border-radius: 0.75rem !important;
            box-shadow: 0 8px 30px rgba(100, 116, 139, .2) !important;
            font-family: 'Public Sans', 'Noto Sans Gujarati', sans-serif !important;
            font-size: .875rem !important;
            border: 1px solid #e2e8f0 !important;
            padding: .75rem !important;
            width: 310px !important;
        }

        /* Month nav */
        .flatpickr-months {
            padding: 0 0 .5rem !important;
            align-items: center !important;
            position: relative !important;
        }

        .flatpickr-months .flatpickr-month {
            height: 36px !important;
            overflow: visible !important;
            flex: 1 !important;
        }

        .flatpickr-current-month {
            font-size: .9rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            padding-top: 0 !important;
            line-height: 36px !important;
            width: 100% !important;
            left: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Remove the ugly box around month select */
        .flatpickr-monthDropdown-months {
            -webkit-appearance: none !important;
            appearance: none !important;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            font-weight: 700 !important;
            font-size: .9rem !important;
            color: #1e293b !important;
            cursor: pointer !important;
            padding: 0 4px 0 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }

        .flatpickr-current-month input.cur-year {
            font-weight: 700 !important;
            font-size: .9rem !important;
            color: #1e293b !important;
            border: none !important;
            background: transparent !important;
            outline: none !important;
            padding: 0 0 0 2px !important;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            fill: #64748b !important;
            padding: 5px 10px !important;
            top: 0 !important;
            height: 36px !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            flex-shrink: 0 !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #696cff !important;
        }

        /* Weekdays */
        .flatpickr-weekdays {
            margin-bottom: 2px !important;
            background: transparent !important;
        }

        span.flatpickr-weekday {
            font-weight: 700 !important;
            color: #94a3b8 !important;
            font-size: .68rem !important;
            text-transform: uppercase !important;
            letter-spacing: .04em !important;
            background: transparent !important;
        }

        /* Day container — full width */
        .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
        }

        .flatpickr-days {
            width: 100% !important;
        }

        /* Day cells — perfect circles */
        .flatpickr-day {
            border-radius: 50% !important;
            height: 34px !important;
            width: 34px !important;
            line-height: 34px !important;
            font-size: .85rem !important;
            color: #334155 !important;
            font-weight: 500 !important;
            border: none !important;
            max-width: 34px !important;
            flex-basis: 34px !important;
        }

        /* Today — solid purple */
        .flatpickr-day.today,
        .flatpickr-day.today:hover {
            background: #696cff !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 10px rgba(105, 108, 255, .35) !important;
        }

        /* Selected */
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #696cff !important;
            border-color: transparent !important;
            color: #fff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 10px rgba(105, 108, 255, .35) !important;
        }

        /* Hover */
        .flatpickr-day:hover:not(.selected):not(.disabled):not(.today) {
            background: rgba(105, 108, 255, .1) !important;
            color: #696cff !important;
        }

        /* Other month / disabled */
        .flatpickr-day.nextMonthDay,
        .flatpickr-day.prevMonthDay {
            color: #cbd5e1 !important;
        }

        .flatpickr-day.disabled,
        .flatpickr-day.disabled:hover {
            color: #e2e8f0 !important;
            background: transparent !important;
        }

        /* Input group wrapper */
        .fp-wrapper {
            flex-wrap: nowrap !important;
        }

        .fp-wrapper input.flatpickr-input {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-right: 0 !important;
        }

        .fp-wrapper .input-group-text {
            border-left: 0 !important;
            background: #fff !important;
            border-color: #d9dee3 !important;
            color: #697a8d;
        }

        .fp-wrapper input.flatpickr-input:focus {
            border-color: #696cff !important;
            box-shadow: none !important;
            z-index: 1 !important;
        }

        .fp-wrapper input.flatpickr-input:focus~.input-group-text {
            border-color: #696cff !important;
        }

        /* Ensure calendar always renders on top */
        .flatpickr-calendar {
            z-index: 99999 !important;
        }

        /* Dark mode */
        [data-bs-theme="dark"] .flatpickr-calendar {
            background: #2b2c40 !important;
            border-color: #3d3e5c !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .45) !important;
        }

        [data-bs-theme="dark"] .flatpickr-months .flatpickr-month,
        [data-bs-theme="dark"] .flatpickr-current-month {
            background: #2b2c40 !important;
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .flatpickr-monthDropdown-months,
        [data-bs-theme="dark"] .flatpickr-current-month input.cur-year {
            color: #e2e8f0 !important;
            background: #2b2c40 !important;
        }

        [data-bs-theme="dark"] span.flatpickr-weekday {
            background: #2b2c40 !important;
            color: #4a5568 !important;
        }

        [data-bs-theme="dark"] .flatpickr-weekdays {
            background: #2b2c40 !important;
        }

        [data-bs-theme="dark"] .flatpickr-days,
        [data-bs-theme="dark"] .dayContainer {
            background: #2b2c40 !important;
        }

        [data-bs-theme="dark"] .flatpickr-day {
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .flatpickr-day:hover:not(.selected):not(.disabled):not(.today) {
            background: rgba(105, 108, 255, .2) !important;
            color: #a5b4fc !important;
        }

        [data-bs-theme="dark"] .flatpickr-day.today,
        [data-bs-theme="dark"] .flatpickr-day.today:hover {
            background: #696cff !important;
            color: #fff !important;
        }

        [data-bs-theme="dark"] .flatpickr-day.nextMonthDay,
        [data-bs-theme="dark"] .flatpickr-day.prevMonthDay {
            color: #3d3e5c !important;
        }

        [data-bs-theme="dark"] .flatpickr-months .flatpickr-prev-month,
        [data-bs-theme="dark"] .flatpickr-months .flatpickr-next-month {
            fill: #94a3b8 !important;
        }

        [data-bs-theme="dark"] input.flatpickr-input {
            background-color: #2b2c40 !important;
            border-color: #444564 !important;
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] .fp-wrapper .input-group-text {
            background: #2b2c40 !important;
            border-color: #444564 !important;
            color: #7983bb !important;
        }
    </style>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">

    <style>
        /* ===== Status Badge — Outlined Style (Active/Inactive) ===== */
        .badge.rounded-pill.bg-success:not(.bg-label-success):not(.status-solid) {
            background-color: transparent !important;
            border: 1.5px solid #71dd37 !important;
            color: #71dd37 !important;
        }

        .badge.rounded-pill.bg-danger:not(.bg-label-danger):not(.status-solid) {
            background-color: transparent !important;
            border: 1.5px solid #ff3e1d !important;
            color: #ff3e1d !important;
        }

        .badge.rounded-pill.bg-warning:not(.bg-label-warning):not(.status-solid) {
            background-color: transparent !important;
            border: 1.5px solid #ffab00 !important;
            color: #ffab00 !important;
        }

        .badge.rounded-pill.bg-info:not(.bg-label-info):not(.status-solid) {
            background-color: transparent !important;
            border: 1.5px solid #03c3ec !important;
            color: #03c3ec !important;
        }

        .badge.rounded-pill.bg-primary:not(.bg-label-primary):not(.status-solid) {
            background-color: transparent !important;
            border: 1.5px solid #696cff !important;
            color: #696cff !important;
        }

        .badge.rounded-pill.bg-secondary:not(.bg-label-secondary):not(.status-solid) {
            background-color: transparent !important;
            border: 1.5px solid #8592a3 !important;
            color: #8592a3 !important;
        }

        /* Dark mode badge outlines */
        [data-bs-theme="dark"] .badge.rounded-pill.bg-success:not(.bg-label-success):not(.status-solid) {
            border-color: #71dd37 !important;
            color: #71dd37 !important;
        }

        [data-bs-theme="dark"] .badge.rounded-pill.bg-danger:not(.bg-label-danger):not(.status-solid) {
            border-color: #ff3e1d !important;
            color: #ff3e1d !important;
        }

        [data-bs-theme="dark"] .badge.rounded-pill.bg-warning:not(.bg-label-warning):not(.status-solid) {
            border-color: #ffab00 !important;
            color: #ffab00 !important;
        }

        /* ===== DataTables — Sneat Theme Override ===== */

        /* Length (Show X entries) */
        .dataTables_length label {
            display: flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            font-size: 0.875rem !important;
            color: #697a8d !important;
            font-weight: 500 !important;
        }

        .dataTables_length select {
            display: inline-block !important;
            width: auto !important;
            padding: 0.25rem 1.8rem 0.25rem 0.6rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #697a8d !important;
            background-color: #fff !important;
            border: 1px solid #d9dee3 !important;
            border-radius: 0.375rem !important;
            appearance: auto !important;
            box-shadow: none !important;
            outline: none !important;
            cursor: pointer !important;
            transition: border-color .15s ease-in-out !important;
        }

        .dataTables_length select:focus {
            border-color: #696cff !important;
            box-shadow: 0 0 0 0.15rem rgba(105, 108, 255, .15) !important;
        }

        /* Search */
        .dataTables_filter label {
            display: flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            font-size: 0.875rem !important;
            color: #697a8d !important;
            font-weight: 500 !important;
        }

        .dataTables_filter input {
            display: inline-block !important;
            width: 200px !important;
            padding: 0.25rem 0.75rem !important;
            font-size: 0.875rem !important;
            color: #697a8d !important;
            background-color: #fff !important;
            border: 1px solid #d9dee3 !important;
            border-radius: 0.375rem !important;
            box-shadow: none !important;
            outline: none !important;
            transition: border-color .15s ease-in-out !important;
        }

        .dataTables_filter input:focus {
            border-color: #696cff !important;
            box-shadow: 0 0 0 0.15rem rgba(105, 108, 255, .15) !important;
        }

        .dataTables_filter input::placeholder {
            color: #b4bdc6 !important;
        }

        /* Info text (Showing 1 to N of N entries) */
        .dataTables_info {
            font-size: 0.8125rem !important;
            color: #8592a3 !important;
            padding-top: 0.5rem !important;
        }

        /* Pagination */
        .dataTables_paginate {
            padding-top: 0.4rem !important;
        }

        .dataTables_paginate .paginate_button {
            padding: 0.3rem 0.7rem !important;
            margin: 0 2px !important;
            font-size: 0.8125rem !important;
            border-radius: 0.375rem !important;
            border: 1px solid transparent !important;
            color: #697a8d !important;
            background: transparent !important;
            cursor: pointer !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: rgba(105, 108, 255, .08) !important;
            color: #696cff !important;
            border-color: transparent !important;
        }

        .dataTables_paginate .paginate_button.current,
        .dataTables_paginate .paginate_button.current:hover {
            background: #696cff !important;
            color: #fff !important;
            border-color: #696cff !important;
        }

        .dataTables_paginate .paginate_button.disabled,
        .dataTables_paginate .paginate_button.disabled:hover {
            color: #c5cdd6 !important;
            cursor: default !important;
        }

        /* Table header sort icons */
        table.dataTable thead th {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: #8592a3 !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        table.dataTable thead th.sorting::after,
        table.dataTable thead th.sorting_asc::after,
        table.dataTable thead th.sorting_desc::after {
            color: #696cff !important;
        }

        table.dataTable tbody tr:hover>td {
            background: rgba(105, 108, 255, .04) !important;
        }

        /* Wrapper spacing */
        .dataTables_wrapper .row:first-child {
            padding: 0.75rem 1rem 0.5rem !important;
            align-items: center !important;
            border-bottom: 1px solid #e9ecef;
        }

        .dataTables_wrapper .row:last-child {
            padding: 0.5rem 1rem 0.75rem !important;
            align-items: center !important;
            border-top: 1px solid #e9ecef;
        }

        /* ===== Dark Mode — DataTables ===== */
        [data-bs-theme="dark"] .dataTables_length label,
        [data-bs-theme="dark"] .dataTables_filter label,
        [data-bs-theme="dark"] .dataTables_info {
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] .dataTables_length select,
        [data-bs-theme="dark"] .dataTables_filter input {
            background-color: #2b2c40 !important;
            border-color: #444564 !important;
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] .dataTables_paginate .paginate_button {
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] table.dataTable thead th {
            color: #7983bb !important;
            border-bottom-color: #444564 !important;
        }

        [data-bs-theme="dark"] table.dataTable tbody tr:hover>td {
            background: rgba(105, 108, 255, .08) !important;
        }
    </style>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">

    @stack('styles')

    <!-- Admin Custom Styles -->
    <style>
        .btn-action {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .tbl-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .tbl-img-wide {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .tbl-img-round {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
        }

        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #ccc;
            display: inline-block;
            flex-shrink: 0;
        }

        /* ===== Image Preview in create/edit forms ===== */
        .img-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }

        .img-preview-contain {
            width: 120px;
            height: 120px;
            object-fit: contain;
            display: block;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .img-preview-service {
            width: 120px;
            height: 120px;
            object-fit: contain;
            display: block;
            background: #f8f9fa;
            border-radius: 6px;
        }

        /* ===== Image Remove Button ===== */
        .img-remove-btn {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            transform: translate(40%, -40%);
            line-height: 1;
        }

        .icon-sm {
            font-size: 14px;
        }

        /* ===== Status Toggle ===== */
        /* .status-toggle-btn {
            cursor: pointer;
            font-size: 0.8rem;
        }

        .toggle-switch-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        } */

        /* ===== Avatar Initials ===== */
        .avatar-initials {
            background: #696cff;
            font-size: 1.1rem;
        }

        .avatar-initials-hidden {
            display: none !important;
        }

        .icon-md-tbl {
            font-size: 1.3rem;
        }

        .img-fallback {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f0f2f5;
            color: #adb5bd;
            flex-shrink: 0;
            font-size: 1.3rem;
        }

        .img-fallback.tbl-img {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .img-fallback.tbl-img-round {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
        }

        .img-fallback.tbl-img-wide {
            width: 80px;
            height: 50px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        /* SweetAlert2 z-index fix for Sneat template */
        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-toast {
            z-index: 99999 !important;
        }

        .swal-top-toast {
            z-index: 99999 !important;
            top: 1rem !important;
            right: 1rem !important;
        }

        /* ===== Custom Admin Toast ===== */
        #adminToast {
            position: fixed;
            top: 1.2rem;
            right: 1.2rem;
            z-index: 99999;
            min-width: 260px;
            max-width: 400px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.13);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 1rem 1.3rem;
            font-size: 1.15rem;
            font-weight: 600;
            color: #333;
            border-left: 4px solid #28a745;
            animation: toastSlideIn 0.3s ease;
            cursor: pointer;
        }

        #adminToast.toast-error {
            border-left-color: #dc3545;
        }

        #adminToast.toast-warning {
            border-left-color: #ffc107;
        }

        #adminToast.toast-success {
            border-left-color: #28a745;
        }

        #adminToast .toast-icon {
            font-size: 1.8rem;
            flex-shrink: 0;
            line-height: 1;
        }

        #adminToast.toast-success .toast-icon {
            color: #28a745;
        }

        #adminToast.toast-error .toast-icon {
            color: #dc3545;
        }

        #adminToast.toast-warning .toast-icon {
            color: #ffc107;
        }

        #adminToast .toast-msg {
            flex: 1;
            line-height: 1.4;
            word-break: break-word;
        }

        #adminToast .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 10px 10px;
            background: #28a745;
            animation: toastProgress 3s linear forwards;
        }

        #adminToast.toast-error .toast-progress {
            background: #dc3545;
        }

        #adminToast.toast-warning .toast-progress {
            background: #ffc107;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(60px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastProgress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* ===== jQuery Validate — field icons ===== */
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='10' fill='none' stroke='%23dc3545' stroke-width='2'/%3E%3Cline x1='12' y1='8' x2='12' y2='12' stroke='%23dc3545' stroke-width='2' stroke-linecap='round'/%3E%3Ccircle cx='12' cy='16' r='1' fill='%23dc3545'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1.2rem !important;
            padding-right: 2.5rem !important;
        }

        .form-control.is-valid,
        .form-select.is-valid {
            border-color: #28a745 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpolyline points='20 6 9 17 4 12' fill='none' stroke='%2328a745' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1.2rem !important;
            padding-right: 2.5rem !important;
        }

        /* input-group ની અંદર icon ન show થાય — last input પર show */
        .input-group .form-control.is-invalid,
        .input-group .form-control.is-valid {
            background-position: right 0.75rem center !important;
        }

        /* ===== Select2 — Sneat Theme Match ===== */
        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #d9dee3 !important;
            border-radius: 0.375rem !important;
            min-height: 42px !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.9375rem !important;
            font-family: 'Public Sans', sans-serif !important;
            color: #697a8d !important;
            background-color: #fff !important;
            box-shadow: none !important;
            transition: border-color 0.15s ease-in-out !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            min-height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #697a8d !important;
            padding: 0 !important;
            line-height: 1.5 !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
            color: #b4bdc6 !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #696cff !important;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.15) !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: #696cff !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45) !important;
            font-family: 'Public Sans', sans-serif !important;
            font-size: 0.9375rem !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            color: #697a8d !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #696cff !important;
            color: #fff !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--selected {
            background-color: rgba(105, 108, 255, 0.08) !important;
            color: #696cff !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border: 1px solid #d9dee3 !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.9375rem !important;
            color: #697a8d !important;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
            border-color: #696cff !important;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.15) !important;
            outline: none !important;
        }

        .select2-container--bootstrap-5 .select2-selection__clear {
            color: #a1acb8 !important;
        }

        .select2-container--bootstrap-5 .select2-selection__clear:hover {
            color: #697a8d !important;
        }

        /* lg size */
        .select2-lg .select2-container--bootstrap-5 .select2-selection {
            min-height: 48px !important;
            font-size: 1rem !important;
            padding: 0.5rem 1rem !important;
        }

        /* ===== Select2 — Small size (form-select-sm) ===== */
        .form-select-sm+.select2-container--bootstrap-5 .select2-selection,
        select.form-select-sm~.select2-container--bootstrap-5 .select2-selection {
            min-height: 31px !important;
            font-size: 0.8125rem !important;
            padding: 0.25rem 0.5rem !important;
        }

        /* Target select2 containers next to small selects */
        .select2-container--bootstrap-5.select2-sm .select2-selection {
            min-height: 31px !important;
            font-size: 0.8125rem !important;
            padding: 0.25rem 0.5rem !important;
        }

        /* ===== Select2 — Z-index fix (above sidebar overlay) ===== */
        .select2-container--open .select2-dropdown {
            z-index: 99999 !important;
        }

        /* ===== Select2 — Dark mode ===== */
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
            background-color: #2b2c40 !important;
            border-color: #444564 !important;
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown {
            background-color: #2b2c40 !important;
            border-color: #444564 !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option {
            color: #a3a4cc !important;
            background-color: #2b2c40 !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #696cff !important;
            color: #fff !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            background-color: #2b2c40 !important;
            border-color: #444564 !important;
            color: #a3a4cc !important;
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection__placeholder {
            color: #6d6f8a !important;
        }
    </style>

    <!-- imgError: called inline onerror on all tbl-img tags -->
    <script>
        function imgError(img) {
            var cls = img.className;
            var d = document.createElement('div');
            d.className = 'img-fallback ' + cls;
            d.innerHTML = '<i class="bx bx-image img-fallback-icon"></i>';
            img.parentNode.replaceChild(d, img);
        }

        function imgPreview(input, previewId) {
            var file = input.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                var el = document.getElementById(previewId);
                if (el) {
                    el.src = e.target.result;
                    el.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    </script>

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
