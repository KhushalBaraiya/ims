<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" id="htmlRoot">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php echo $__env->yieldContent('title', 'Auth'); ?> — Kalathiya POS</title>

    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/img/favicon/favicon.ico')); ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" />

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

    <script>
        /* Apply saved theme before paint to avoid flash */
        (function() {
            try {
                var t = localStorage.getItem('auth-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <style>
        /* ═══════════════════════════════════════════════
           DESIGN TOKENS — Dark (default)
        ═══════════════════════════════════════════════ */
        :root,
        [data-theme="dark"] {
            --bg-page: #0d0d12;
            --bg-card: #13131c;
            --bg-input: #0f0f18;
            --bg-toggle: #0f0f18;
            /* pw-toggle bg = input bg */
            --border: rgba(255, 255, 255, .08);
            --border-focus: #696cff;

            --text-primary: #e8e9f4;
            --text-secondary: #8c8fa8;
            --text-muted: #55576a;
            --text-toggle: #55576a;

            --primary: #696cff;
            --primary-2: #9155fd;
            --primary-glow: rgba(105, 108, 255, .35);

            --success-text: #34d399;
            --success-bg: rgba(52, 211, 153, .08);
            --success-bdr: rgba(52, 211, 153, .20);
            --error: #f87171;

            --dot: rgba(105, 108, 255, .18);
            --blob1: rgba(105, 108, 255, .07);
            --blob2: rgba(145, 85, 253, .06);
            --radius: 14px;

            --card-shadow: 0 8px 48px rgba(0, 0, 0, .55), 0 1px 0 rgba(255, 255, 255, .04) inset;
        }

        /* ═══════════════════════════════════════════════
           DESIGN TOKENS — Light
        ═══════════════════════════════════════════════ */
        [data-theme="light"] {
            --bg-page: #f0f2f8;
            --bg-card: #ffffff;
            --bg-input: #f5f6fa;
            --bg-toggle: #f5f6fa;
            --border: rgba(0, 0, 0, .09);
            --border-focus: #696cff;

            --text-primary: #1e2130;
            --text-secondary: #697a8d;
            --text-muted: #a1aab8;
            --text-toggle: #a1aab8;

            --primary: #696cff;
            --primary-2: #9155fd;
            --primary-glow: rgba(105, 108, 255, .28);

            --success-text: #059669;
            --success-bg: rgba(5, 150, 105, .07);
            --success-bdr: rgba(5, 150, 105, .18);
            --error: #dc2626;

            --dot: rgba(105, 108, 255, .07);
            --blob1: rgba(105, 108, 255, .06);
            --blob2: rgba(145, 85, 253, .05);

            --card-shadow: 0 4px 32px rgba(100, 116, 139, .12), 0 1px 0 rgba(255, 255, 255, .8) inset;
        }

        /* ── Reset ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Override Bootstrap default button background/border for non-btn buttons */
        button:not([class*="btn"]) {
            background-color: transparent;
            border-color: transparent;
        }

        html,
        body {
            height: 100%;
            font-family: 'Public Sans', sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            transition: background .3s, color .3s;
        }

        /* ── Dot-grid background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: radial-gradient(circle, var(--dot) 1.2px, transparent 1.2px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ── Glow blobs ── */
        .glow-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }

        .glow-1 {
            width: 500px;
            height: 500px;
            background: var(--blob1);
            top: -150px;
            right: -100px;
        }

        .glow-2 {
            width: 400px;
            height: 400px;
            background: var(--blob2);
            bottom: -100px;
            left: -80px;
        }

        /* ── Page wrapper ── */
        .auth-page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* ── Card ── */
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.5rem 2.25rem;
            box-shadow: var(--card-shadow);
            position: relative;
        }

        /* ── Theme toggle button (top-right of card) ── */
        .auth-theme-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg-input);
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: background .2s, color .2s, border-color .2s;
        }

        .auth-theme-btn:hover {
            background: var(--border);
            color: var(--primary);
            border-color: var(--primary);
        }

        /* ── Brand ── */
        .brand-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            margin-bottom: 2rem;
        }

        .brand-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow: 0 0 18px var(--primary-glow);
            color: #fff;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -.4px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ── Headings ── */
        .auth-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: .35rem;
        }

        .auth-subtitle {
            font-size: .85rem;
            color: var(--text-secondary);
            margin-bottom: 1.75rem;
        }

        /* ── Success alert ── */
        .alert-success-dark {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            background: var(--success-bg);
            border: 1px solid var(--success-bdr);
            border-radius: 10px;
            padding: .85rem 1rem;
            color: var(--success-text);
            font-size: .82rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .alert-success-dark i {
            margin-top: .1rem;
            flex-shrink: 0;
        }

        /* ── Label ── */
        .form-label-dark {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: .45rem;
            letter-spacing: .02em;
        }

        /* ── Input ── */
        .input-dark {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .72rem 1rem;
            font-size: .875rem;
            color: var(--text-primary);
            font-family: 'Public Sans', sans-serif;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .3s, color .3s;
        }

        .input-dark::placeholder {
            color: var(--text-muted);
        }

        .input-dark:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(105, 108, 255, .15);
        }

        .input-dark.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(248, 113, 113, .12);
        }

        .input-dark[readonly] {
            opacity: .6;
            cursor: not-allowed;
        }

        /* ── Password group ── */
        .pw-wrap {
            position: relative;
        }

        .pw-wrap .input-dark {
            padding-right: 2.8rem;
        }

        /* Complete Bootstrap button reset for the toggle */
        .pw-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            -webkit-appearance: none;
            appearance: none;
            cursor: pointer;
            color: var(--text-toggle);
            font-size: 1rem;
            line-height: 1;
            border-radius: 0 10px 10px 0;
            transition: color .15s;
            z-index: 2;
        }

        .pw-toggle:hover,
        .pw-toggle:focus,
        .pw-toggle:active,
        .pw-toggle:focus-visible {
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            color: var(--primary) !important;
        }

        /* ── Field error ── */
        .field-error {
            font-size: .75rem;
            color: var(--error);
            margin-top: .4rem;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        /* ── Remember + Forgot ── */
        .form-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        /* Bootstrap form-check override for dark/light theme */
        .form-check {
            margin: 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            background-color: var(--bg-input);
            border: 1.5px solid var(--border);
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(105, 108, 255, .15);
        }

        .form-check-label {
            font-size: .82rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color .2s;
        }

        .form-check-label:hover {
            color: var(--text-primary);
        }

        .forgot-link {
            font-size: .78rem;
            color: var(--primary);
            text-decoration: none;
            transition: color .15s;
        }

        .forgot-link:hover {
            color: #8385ff;
            text-decoration: underline;
        }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            padding: .82rem 1rem;
            border-radius: 11px;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            box-shadow: 0 4px 22px var(--primary-glow);
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            font-family: 'Public Sans', sans-serif;
            transition: opacity .2s, box-shadow .2s, transform .1s;
        }

        .btn-submit:hover {
            opacity: .9;
            box-shadow: 0 6px 28px rgba(105, 108, 255, .5);
        }

        .btn-submit:active {
            transform: scale(.99);
        }

        /* ── Back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: 1.4rem;
            font-size: .78rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color .15s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        /* ── Footer ── */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .72rem;
            color: var(--text-muted);
        }

        /* ── Spacing ── */
        .mb-field {
            margin-bottom: 1.15rem;
        }

        /* ── Autofill ── */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 100px var(--bg-input) inset !important;
            -webkit-text-fill-color: var(--text-primary) !important;
            caret-color: var(--text-primary);
        }
    </style>
</head>

<body>
    <div class="glow-blob glow-1"></div>
    <div class="glow-blob glow-2"></div>

    <main class="auth-page">
        <div class="auth-card">

            
            <button class="auth-theme-btn" id="authThemeBtn" title="Toggle theme" type="button">
                <i class="bx bx-moon" id="authThemeIcon"></i>
            </button>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <p class="auth-footer" style="position:relative;z-index:1;">
        &copy; <?php echo e(date('Y')); ?> Kalathiya POS. All rights reserved.
    </p>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var html = document.documentElement;
            var btn = document.getElementById('authThemeBtn');
            var icon = document.getElementById('authThemeIcon');
            var theme = localStorage.getItem('auth-theme') || 'dark';

            function applyTheme(t) {
                theme = t;
                html.setAttribute('data-theme', t);
                localStorage.setItem('auth-theme', t);
                if (t === 'dark') {
                    icon.className = 'bx bx-moon';
                    btn.title = 'Switch to light mode';
                } else {
                    icon.className = 'bx bx-sun';
                    btn.title = 'Switch to dark mode';
                }
            }

            applyTheme(theme);

            btn.addEventListener('click', function() {
                applyTheme(theme === 'dark' ? 'light' : 'dark');
            });
        })();
    </script>
</body>

</html>
<?php /**PATH C:\Users\Linux\Desktop\ims\resources\views/layouts/auth.blade.php ENDPATH**/ ?>