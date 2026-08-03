@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')

    <div class="brand-wrap">
        <div class="brand-icon"><i class="bx bx-bolt-circle"></i></div>
        <span class="brand-name">Kalathiya POS</span>
    </div>

    <div class="auth-title">Set New Password 🔑</div>
    <div class="auth-subtitle">{{ __('messages.reset_password_desc') }}</div>

    {{-- Validation errors summary --}}
    @if ($errors->any())
        <div class="reset-error-box mb-4">
            <i class="bx bx-error-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" id="resetForm" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email (readonly) --}}
        <div class="mb-field">
            <label class="form-label-dark" for="email">{{ __('messages.email_address_label') }}</label>
            <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" readonly
                class="input-dark @error('email') is-invalid @enderror" />
            @error('email')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- New password --}}
        <div class="mb-field">
            <label class="form-label-dark" for="password">{{ __('messages.new_password_label') }}</label>
            <div class="pw-wrap">
                <input type="password" id="password" name="password" placeholder="{{ __('messages.ph_min_8_chars') }}"
                    autocomplete="new-password" required class="input-dark @error('password') is-invalid @enderror"
                    oninput="checkStrength(this.value); checkMatch();" />
                <button type="button" class="pw-toggle" id="togglePw" tabindex="-1">
                    <i class="bx bx-hide" id="pwIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error"><i class="bx bx-info-circle"></i> {{ $message }}</div>
            @enderror

            {{-- Strength meter --}}
            <div class="strength-wrap" id="strengthWrap" style="display:none;">
                <div class="strength-bar-track">
                    <div class="strength-bar-fill" id="strengthFill"></div>
                </div>
                <span class="strength-label" id="strengthLabel"></span>
            </div>

            {{-- Requirements checklist --}}
            <ul class="pw-reqs" id="pwReqs">
                <li id="req-len"><i class="bx bx-circle"></i> At least 8 characters</li>
                <li id="req-upper"><i class="bx bx-circle"></i> One uppercase letter</li>
                <li id="req-lower"><i class="bx bx-circle"></i> One lowercase letter</li>
                <li id="req-num"><i class="bx bx-circle"></i> One number</li>
            </ul>
        </div>

        {{-- Confirm password --}}
        <div class="mb-field">
            <label class="form-label-dark" for="password_confirmation">Confirm New Password</label>
            <div class="pw-wrap">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('messages.ph_password_dots') }}"
                    autocomplete="new-password" required class="input-dark" oninput="checkMatch();" />
                <button type="button" class="pw-toggle" id="toggleConfirm" tabindex="-1">
                    <i class="bx bx-hide" id="confirmIcon"></i>
                </button>
            </div>
            <div class="match-msg" id="matchMsg" style="display:none;"></div>
        </div>

        <button type="submit" class="btn-submit" id="resetBtn" style="margin-bottom:.75rem;">
            <i class="bx bx-check-shield" id="resetBtnIcon"></i>
            <span id="resetBtnText">{{ __('messages.reset_password_btn') }}</span>
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="back-link">
                <i class="bx bx-chevron-left"></i> {{ __('messages.back_to_sign_in') }}
            </a>
        </div>
    </form>

    <style>
        /* ── Error box ───────────────────────────────────────────────────── */
        .reset-error-box {
            display: flex;
            align-items: center;
            gap: .65rem;
            background: rgba(248, 113, 113, .1);
            border: 1.5px solid rgba(248, 113, 113, .3);
            border-radius: 10px;
            padding: .75rem 1rem;
            color: var(--error);
            font-size: .82rem;
            font-weight: 500;
        }

        .reset-error-box i {
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* ── Password requirements ───────────────────────────────────────── */
        .pw-reqs {
            list-style: none;
            padding: 0;
            margin: .55rem 0 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .25rem .5rem;
        }

        .pw-reqs li {
            font-size: .72rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: .3rem;
            transition: color .2s;
        }

        .pw-reqs li i {
            font-size: .75rem;
            transition: color .2s;
        }

        .pw-reqs li.ok {
            color: #34d399;
        }

        .pw-reqs li.ok i {
            color: #34d399;
        }

        .pw-reqs li.ok i::before {
            content: "\ed6f";
        }

        /* bx-check-circle */

        /* ── Strength bar ────────────────────────────────────────────────── */
        .strength-wrap {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-top: .55rem;
        }

        .strength-bar-track {
            flex: 1;
            height: 5px;
            background: rgba(255, 255, 255, .08);
            border-radius: 99px;
            overflow: hidden;
        }

        .strength-bar-fill {
            height: 100%;
            width: 0;
            border-radius: 99px;
            transition: width .3s ease, background .3s ease;
        }

        .strength-label {
            font-size: .7rem;
            font-weight: 600;
            white-space: nowrap;
            min-width: 52px;
            text-align: right;
        }

        /* ── Match message ───────────────────────────────────────────────── */
        .match-msg {
            font-size: .73rem;
            font-weight: 500;
            margin-top: .4rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .match-msg.ok {
            color: #34d399;
        }

        .match-msg.bad {
            color: var(--error);
        }
    </style>

    <script>
        /* ── Toggle visibility ─────────────────────────────────────────── */
        function toggleField(inputId, iconId) {
            var inp = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            icon.className = inp.type === 'text' ? 'bx bx-show' : 'bx bx-hide';
        }
        document.getElementById('togglePw').addEventListener('click', function() {
            toggleField('password', 'pwIcon');
        });
        document.getElementById('toggleConfirm').addEventListener('click', function() {
            toggleField('password_confirmation', 'confirmIcon');
        });

        /* ── Requirement helper ────────────────────────────────────────── */
        function setReq(id, ok) {
            var li = document.getElementById(id);
            if (ok) {
                li.classList.add('ok');
                li.classList.remove('fail');
            } else {
                li.classList.remove('ok');
            }
        }

        /* ── Strength checker ──────────────────────────────────────────── */
        function checkStrength(val) {
            var wrap = document.getElementById('strengthWrap');
            var fill = document.getElementById('strengthFill');
            var label = document.getElementById('strengthLabel');

            if (!val) {
                wrap.style.display = 'none';
                return;
            }
            wrap.style.display = 'flex';

            var hasLen = val.length >= 8;
            var hasUpper = /[A-Z]/.test(val);
            var hasLower = /[a-z]/.test(val);
            var hasNum = /[0-9]/.test(val);
            var hasSpec = /[^A-Za-z0-9]/.test(val);

            setReq('req-len', hasLen);
            setReq('req-upper', hasUpper);
            setReq('req-lower', hasLower);
            setReq('req-num', hasNum);

            var score = [hasLen, hasUpper, hasLower, hasNum, hasSpec].filter(Boolean).length;

            var configs = [{
                    w: '20%',
                    bg: '#f87171',
                    txt: 'Weak',
                    color: '#f87171'
                },
                {
                    w: '40%',
                    bg: '#fb923c',
                    txt: 'Fair',
                    color: '#fb923c'
                },
                {
                    w: '60%',
                    bg: '#facc15',
                    txt: 'Good',
                    color: '#facc15'
                },
                {
                    w: '80%',
                    bg: '#4ade80',
                    txt: 'Strong',
                    color: '#4ade80'
                },
                {
                    w: '100%',
                    bg: '#34d399',
                    txt: 'Great',
                    color: '#34d399'
                },
            ];
            var c = configs[score - 1] || configs[0];
            fill.style.width = c.w;
            fill.style.background = c.bg;
            label.textContent = c.txt;
            label.style.color = c.color;
        }

        /* ── Match checker ─────────────────────────────────────────────── */
        function checkMatch() {
            var pw = document.getElementById('password').value;
            var conf = document.getElementById('password_confirmation').value;
            var msg = document.getElementById('matchMsg');

            if (!conf) {
                msg.style.display = 'none';
                return;
            }
            msg.style.display = 'flex';

            if (pw === conf) {
                msg.className = 'match-msg ok';
                msg.innerHTML = '<i class="bx bx-check-circle"></i> Passwords match';
            } else {
                msg.className = 'match-msg bad';
                msg.innerHTML = '<i class="bx bx-x-circle"></i> Passwords do not match';
            }
        }

        /* ── Loading state on submit ───────────────────────────────────── */
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            var pw = document.getElementById('password').value;
            var conf = document.getElementById('password_confirmation').value;

            if (pw !== conf) {
                e.preventDefault();
                var msg = document.getElementById('matchMsg');
                msg.style.display = 'flex';
                msg.className = 'match-msg bad';
                msg.innerHTML = '<i class="bx bx-x-circle"></i> Passwords do not match';
                document.getElementById('password_confirmation').focus();
                return;
            }

            var btn = document.getElementById('resetBtn');
            var icon = document.getElementById('resetBtnIcon');
            var text = document.getElementById('resetBtnText');
            btn.disabled = true;
            btn.style.opacity = '.75';
            icon.className = 'bx bx-loader-alt bx-spin';
            text.textContent = 'Resetting…';
        });
    </script>

@endsection
