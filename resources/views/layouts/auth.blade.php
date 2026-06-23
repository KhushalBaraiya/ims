<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="htmlRoot">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Auth') — Kalathiya POS</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" />

    <!-- Bootstrap 5 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

    <style>
        /* ═══════════════════════════════════════════════
       DESIGN TOKENS  —  pure black base theme
    ═══════════════════════════════════════════════ */
        :root {
            --bg-page: #0d0d12;
            /* deepest black */
            --bg-card: #13131c;
            /* card surface  */
            --bg-input: #0f0f18;
            /* input bg      */
            --bg-hover: #1a1a28;

            --border: rgba(255, 255, 255, .08);
            --border-focus: #696cff;

            --text-primary: #e8e9f4;
            --text-secondary: #8c8fa8;
            --text-muted: #55576a;

            --primary: #696cff;
            --primary-2: #9155fd;
            --primary-glow: rgba(105, 108, 255, .35);

            --success-text: #34d399;
            --success-bg: rgba(52, 211, 153, .08);
            --success-bdr: rgba(52, 211, 153, .20);

            --error: #f87171;

            --dot: rgba(105, 108, 255, .18);
            --radius: 14px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: 'Public Sans', sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Dot-grid page background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image: radial-gradient(circle, var(--dot) 1.2px, transparent 1.2px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ── Purple glow blobs ── */
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
            background: rgba(105, 108, 255, .07);
            top: -150px;
            right: -100px;
        }

        .glow-2 {
            width: 400px;
            height: 400px;
            background: rgba(145, 85, 253, .06);
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
            box-shadow: 0 8px 48px rgba(0, 0, 0, .55), 0 1px 0 rgba(255, 255, 255, .04) inset;
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

        /* ── Status alert ── */
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

        /* ── Form label ── */
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
            transition: border-color .2s, box-shadow .2s;
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

        /* ── Password group ── */
        .pw-wrap {
            position: relative;
        }

        .pw-wrap .input-dark {
            padding-right: 2.6rem;
        }

        .pw-toggle {
            position: absolute;
            inset-y: 0;
            right: 0;
            display: flex;
            align-items: center;
            padding-right: .85rem;
            color: var(--text-muted);
            background: none;
            border: none;
            cursor: pointer;
            font-size: .9rem;
            transition: color .15s;
        }

        .pw-toggle:hover {
            color: var(--primary);
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

        /* ── Remember + Forgot row ── */
        .form-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .check-wrap {
            display: flex;
            align-items: center;
            gap: .45rem;
            cursor: pointer;
        }

        .check-wrap input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--primary);
            cursor: pointer;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 4px;
        }

        .check-wrap span {
            font-size: .8rem;
            color: var(--text-secondary);
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
            letter-spacing: .01em;
        }

        .btn-submit:hover {
            opacity: .9;
            box-shadow: 0 6px 28px rgba(105, 108, 255, .5);
        }

        .btn-submit:active {
            transform: scale(.99);
        }

        /* ── Back / helper link ── */
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

        /* ── Readonly input ── */
        .input-dark[readonly] {
            opacity: .6;
            cursor: not-allowed;
        }

        /* ── Spacing utils ── */
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
            @yield('content')
        </div>
    </main>

    <p class="auth-footer" style="position:relative;z-index:1;">
        &copy; {{ date('Y') }} Kalathiya POS. All rights reserved.
    </p>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
