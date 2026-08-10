<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sending WhatsApp Messages...</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0d1117;
            color: #e6edf3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 16px;
            padding: 36px 32px;
            width: 100%;
            max-width: 460px;
            text-align: center;
        }

        .wa-icon {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #25d366, #128c7e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.2rem;
            color: #fff;
            box-shadow: 0 4px 20px #25d36640;
        }

        h2 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #8b949e;
            font-size: .875rem;
            margin-bottom: 24px;
        }

        .progress-wrap {
            background: #21262d;
            border-radius: 8px;
            height: 8px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .progress-fill {
            height: 8px;
            background: linear-gradient(90deg, #25d366, #128c7e);
            border-radius: 8px;
            transition: width .5s ease;
            width: 0%;
        }

        .counter {
            font-size: 2rem;
            font-weight: 700;
            color: #25d366;
            margin-bottom: 4px;
        }

        .current-name {
            font-size: .875rem;
            color: #8b949e;
            margin-bottom: 24px;
            min-height: 20px;
        }

        .list {
            text-align: left;
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .list-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 4px;
            font-size: .875rem;
            color: #8b949e;
            border: 1px solid transparent;
            transition: all .3s;
        }

        .list-item.done {
            background: #25d36612;
            color: #25d366;
            border-color: #25d36630;
        }

        .list-item.active {
            background: #25d36622;
            color: #25d366;
            border-color: #25d366;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #30363d;
            flex-shrink: 0;
            transition: .3s;
        }

        .done .dot {
            background: #25d366;
        }

        .active .dot {
            background: #25d366;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: .9rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
        }

        .btn-green {
            background: #25d366;
            color: #fff;
        }

        .btn-green:hover {
            background: #1da851;
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: #8b949e;
            border: 1px solid #30363d;
        }

        .btn-outline:hover {
            color: #e6edf3;
            border-color: #8b949e;
        }

        #doneSection {
            display: none;
        }

        .sending-count {
            font-size: .8rem;
            color: #8b949e;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="wa-icon"><i class='bx bxl-whatsapp'></i></div>

        {{-- Sending Section --}}
        <div id="sendingSection">
            <h2>Sending Messages</h2>
            <p class="subtitle">Opening WhatsApp for each customer one by one...</p>
            <div class="progress-wrap">
                <div class="progress-fill" id="bar"></div>
            </div>
            <div class="counter" id="counter">0 / 0</div>
            <div class="current-name" id="currentName">Loading...</div>
            <div class="list" id="list"></div>
            <a href="{{ route('whatsapp.index') }}" class="btn btn-outline">
                <i class='bx bx-arrow-back'></i> Cancel
            </a>
        </div>

        {{-- Done Section --}}
        <div id="doneSection">
            <h2 style="color:#25d366; margin-bottom:8px;">✅ All Sent!</h2>
            <p class="subtitle" id="doneMsg">WhatsApp opened for all customers.</p>
            <a href="{{ route('whatsapp.index') }}" class="btn btn-green">
                <i class='bx bx-arrow-back'></i> Back to Broadcast
            </a>
        </div>
    </div>

    <script>
        (function() {
            const KEY = 'wa_broadcast';
            const IDX_KEY = 'wa_idx';
            const raw = sessionStorage.getItem(KEY);

            if (!raw) {
                window.location.href = '{{ route('whatsapp.index') }}';
                return;
            }

            const data = JSON.parse(raw);
            const recipients = data.recipients; // [{phone, name}]
            const message = data.message;
            const total = recipients.length;

            // Build phone URL
            function waUrl(phone) {
                const p = phone.toString().replace(/[\s\-\+\(\)]/g, '');
                const n = (p.length === 10 && /^[6-9]/.test(p)) ? '91' + p : p;
                return 'https://wa.me/' + n + '?text=' + encodeURIComponent(message);
            }

            // Build list items
            const listEl = document.getElementById('list');
            recipients.forEach(function(r, i) {
                const d = document.createElement('div');
                d.className = 'list-item';
                d.id = 'row' + i;
                d.innerHTML = '<span class="dot"></span><span>' + r.name +
                    ' <span style="opacity:.6;font-size:.8rem;">(' + r.phone + ')</span></span>';
                listEl.appendChild(d);
            });

            // Read current index
            let idx = parseInt(sessionStorage.getItem(IDX_KEY) || '0', 10);

            function updateUI(cur) {
                document.getElementById('bar').style.width = Math.round((cur / total) * 100) + '%';
                document.getElementById('counter').textContent = cur + ' / ' + total;

                for (let i = 0; i < cur; i++) {
                    const el = document.getElementById('row' + i);
                    if (el) el.className = 'list-item done';
                }
                if (cur < total) {
                    document.getElementById('currentName').textContent =
                        '→ ' + recipients[cur].name + ' (' + recipients[cur].phone + ')';
                    const el = document.getElementById('row' + cur);
                    if (el) {
                        el.className = 'list-item active';
                        el.scrollIntoView({
                            block: 'nearest'
                        });
                    }
                }
            }

            function showDone() {
                sessionStorage.removeItem(KEY);
                sessionStorage.removeItem(IDX_KEY);
                document.getElementById('sendingSection').style.display = 'none';
                document.getElementById('doneSection').style.display = 'block';
                document.getElementById('doneMsg').textContent =
                    'WhatsApp opened for all ' + total + ' customers.';
            }

            function sendNext() {
                if (idx >= total) {
                    showDone();
                    return;
                }
                updateUI(idx);

                // Save next index BEFORE navigating away
                sessionStorage.setItem(IDX_KEY, String(idx + 1));
                idx++;

                // Navigate the current tab to WhatsApp Web — never blocked!
                window.location.href = waUrl(recipients[idx - 1].phone);
            }

            // If we are coming back from WhatsApp (page loaded again)
            updateUI(idx);

            if (idx >= total) {
                showDone();
                return;
            }

            // Auto-start after 1.5 seconds so user can see the UI
            setTimeout(sendNext, 1500);

            // Also advance when user comes back to this tab (pressed browser back)
            window.addEventListener('pageshow', function(e) {
                idx = parseInt(sessionStorage.getItem(IDX_KEY) || '0', 10);
                if (idx >= total) {
                    showDone();
                    return;
                }
                updateUI(idx);
                setTimeout(sendNext, 1200);
            });
        })();
    </script>
</body>

</html>
