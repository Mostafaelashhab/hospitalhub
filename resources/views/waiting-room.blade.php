<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.waiting_room') }} — {{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</title>

    {{-- Auto-refresh every 15 seconds --}}
    <meta http-equiv="refresh" content="15">

    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-from:     #0b0c2a;
            --bg-to:       #12153d;
            --accent:      #6c63ff;
            --accent-light:#a89dff;
            --card-bg:     rgba(255,255,255,0.05);
            --card-border: rgba(255,255,255,0.10);
            --text-main:   #e8eaf6;
            --text-muted:  #9499c4;
            --green:       #00c896;
            --green-bg:    rgba(0,200,150,0.12);
            --green-border:rgba(0,200,150,0.35);
            --amber:       #ffc14d;
            --amber-bg:    rgba(255,193,77,0.12);
            --amber-border:rgba(255,193,77,0.35);
            --wait-bg:     rgba(255,255,255,0.04);
            --wait-border: rgba(255,255,255,0.08);
        }

        html, body {
            height: 100%;
            background: linear-gradient(135deg, var(--bg-from) 0%, var(--bg-to) 100%);
            background-attachment: fixed;
            color: var(--text-main);
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            overflow-x: hidden;
        }

        /* Subtle animated background blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.18;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 600px; height: 600px;
            background: var(--accent);
            top: -150px; left: -150px;
            animation: blobFloat 14s ease-in-out infinite alternate;
        }
        body::after {
            width: 500px; height: 500px;
            background: #00c8e0;
            bottom: -100px; right: -100px;
            animation: blobFloat 18s ease-in-out infinite alternate-reverse;
        }

        @keyframes blobFloat {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(60px, 40px) scale(1.12); }
        }

        /* ── Top Bar ── */
        .topbar {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 36px;
            background: rgba(255,255,255,0.04);
            border-bottom: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            flex-wrap: wrap;
            gap: 12px;
        }

        .topbar-clinic {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-clinic img {
            height: 52px;
            width: auto;
            border-radius: 10px;
            object-fit: contain;
            background: rgba(255,255,255,0.1);
            padding: 4px;
        }

        .clinic-name-block .name-primary {
            font-size: clamp(18px, 2.5vw, 26px);
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.3px;
        }
        .clinic-name-block .name-secondary {
            font-size: clamp(12px, 1.5vw, 15px);
            color: var(--text-muted);
            margin-top: 2px;
        }

        .topbar-center {
            text-align: center;
            flex: 1;
        }

        .waiting-room-label {
            font-size: clamp(16px, 2vw, 22px);
            font-weight: 700;
            color: var(--accent-light);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0,200,150,0.15);
            border: 1px solid var(--green-border);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 11px;
            color: var(--green);
            font-weight: 600;
            letter-spacing: 1px;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .live-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse-dot 1.4s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.7); }
        }

        .topbar-time {
            text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};
        }

        .clock-time {
            font-size: clamp(22px, 3vw, 36px);
            font-weight: 700;
            color: #fff;
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }

        .clock-date {
            font-size: clamp(11px, 1.2vw, 14px);
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ── Main Content ── */
        .main {
            position: relative;
            z-index: 10;
            padding: 28px 28px 40px;
            min-height: calc(100vh - 110px);
        }

        /* ── Empty State ── */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            gap: 20px;
            text-align: center;
        }

        .empty-icon {
            width: 90px; height: 90px;
            border-radius: 50%;
            background: rgba(108,99,255,0.15);
            border: 2px solid rgba(108,99,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg { width: 44px; height: 44px; color: var(--accent-light); }

        .empty-title {
            font-size: clamp(20px, 2.5vw, 30px);
            font-weight: 700;
            color: var(--text-muted);
        }

        /* ── Doctor Columns Grid ── */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(280px, 100%), 1fr));
            gap: 22px;
        }

        .doctor-column {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            overflow: hidden;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .doctor-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, rgba(108,99,255,0.25) 0%, rgba(108,99,255,0.10) 100%);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .doctor-avatar {
            width: 46px; height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #a89dff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .doctor-info .doctor-name {
            font-size: clamp(14px, 1.6vw, 17px);
            font-weight: 700;
            color: #fff;
        }

        .doctor-info .doctor-specialty {
            font-size: clamp(11px, 1.1vw, 13px);
            color: var(--accent-light);
            margin-top: 2px;
        }

        .queue-count-badge {
            margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: auto;
            background: rgba(108,99,255,0.25);
            border: 1px solid rgba(108,99,255,0.4);
            color: var(--accent-light);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* ── Queue Items ── */
        .queue-list {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .queue-item {
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform 0.2s ease;
        }

        .queue-item:hover { transform: scale(1.01); }

        /* in_room */
        .queue-item.in-room {
            background: var(--green-bg);
            border: 1px solid var(--green-border);
            animation: glow-green 2.5s ease-in-out infinite;
        }

        @keyframes glow-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0,200,150,0); }
            50%       { box-shadow: 0 0 18px 4px rgba(0,200,150,0.20); }
        }

        /* called */
        .queue-item.called {
            background: var(--amber-bg);
            border: 1px solid var(--amber-border);
            animation: glow-amber 1.8s ease-in-out infinite;
        }

        @keyframes glow-amber {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255,193,77,0); }
            50%       { box-shadow: 0 0 18px 4px rgba(255,193,77,0.25); }
        }

        /* waiting */
        .queue-item.waiting {
            background: var(--wait-bg);
            border: 1px solid var(--wait-border);
        }

        /* Queue number */
        .queue-number {
            width: 46px; height: 46px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(16px, 2vw, 20px);
            font-weight: 800;
            flex-shrink: 0;
        }

        .in-room  .queue-number { background: rgba(0,200,150,0.2);  color: var(--green); }
        .called   .queue-number { background: rgba(255,193,77,0.2); color: var(--amber); }
        .waiting  .queue-number { background: rgba(255,255,255,0.06); color: var(--text-muted); }

        /* Patient info */
        .patient-info { flex: 1; min-width: 0; }

        .patient-name {
            font-size: clamp(13px, 1.5vw, 15px);
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .in-room  .patient-name { color: var(--green); }
        .called   .patient-name { color: var(--amber); }
        .waiting  .patient-name { color: var(--text-main); }

        .status-text {
            font-size: clamp(10px, 1vw, 11px);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 3px;
        }

        .in-room  .status-text { color: rgba(0,200,150,0.75); }
        .called   .status-text { color: rgba(255,193,77,0.75); }
        .waiting  .status-text { color: var(--text-muted); }

        /* Status icon */
        .status-icon {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .in-room  .status-icon { background: rgba(0,200,150,0.2);  color: var(--green); }
        .called   .status-icon { background: rgba(255,193,77,0.2); color: var(--amber); }
        .waiting  .status-icon { background: rgba(255,255,255,0.07); color: var(--text-muted); }

        .status-icon svg { width: 14px; height: 14px; }

        /* ── Footer Bar ── */
        .footer-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 20;
            background: rgba(11,12,42,0.85);
            border-top: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 8px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
        }

        .footer-bar .refresh-info {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-bar .refresh-info svg {
            width: 13px; height: 13px;
            animation: spin 4s linear infinite;
        }

        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* responsive font for very large screens / TVs */
        @media (min-width: 1920px) {
            .queue-number  { width: 58px; height: 58px; font-size: 24px; }
            .patient-name  { font-size: 18px; }
            .status-text   { font-size: 13px; }
            .doctor-name   { font-size: 20px; }
            .doctor-avatar { width: 56px; height: 56px; font-size: 22px; }
        }
    </style>
</head>
<body>

    {{-- ── TOP BAR ── --}}
    <div class="topbar">
        <div class="topbar-clinic">
            @if($clinic->logo)
                <img src="{{ asset('storage/' . $clinic->logo) }}" alt="logo">
            @endif
            <div class="clinic-name-block">
                <div class="name-primary">{{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</div>
                @if($clinic->name_ar && $clinic->name_en)
                    <div class="name-secondary">
                        {{ app()->getLocale() === 'ar' ? $clinic->name_en : $clinic->name_ar }}
                    </div>
                @endif
            </div>
        </div>

        <div class="topbar-center">
            <div class="waiting-room-label">{{ __('app.waiting_room') }}</div>
            <div style="display:flex; justify-content:center; margin-top:5px;">
                <span class="live-badge">
                    <span class="live-dot"></span>
                    {{ __('app.live_queue') }}
                </span>
            </div>
        </div>

        <div class="topbar-time">
            <div class="clock-time" id="clock-time">--:--:--</div>
            <div class="clock-date" id="clock-date">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- ── MAIN AREA ── --}}
    <div class="main">
        @if($byDoctor->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <div class="empty-title">{{ __('app.no_queue') }}</div>
            </div>
        @else
            <div class="doctors-grid">
                @foreach($byDoctor as $group)
                    @php
                        $doctor = $group['doctor'];
                        $appointments = $group['appointments'];
                        $initials = $doctor
                            ? collect(explode(' ', $doctor->name))->map(fn($w) => mb_substr($w,0,1))->take(2)->implode('')
                            : '?';
                        $specialtyName = $doctor && $doctor->specialty
                            ? (app()->getLocale() === 'ar' && isset($doctor->specialty->name_ar)
                                ? $doctor->specialty->name_ar
                                : ($doctor->specialty->name_en ?? $doctor->specialty->name ?? ''))
                            : '';
                    @endphp

                    <div class="doctor-column">
                        <div class="doctor-header">
                            <div class="doctor-avatar">{{ strtoupper($initials) }}</div>
                            <div class="doctor-info">
                                <div class="doctor-name">{{ $doctor ? $doctor->name : '—' }}</div>
                                @if($specialtyName)
                                    <div class="doctor-specialty">{{ $specialtyName }}</div>
                                @endif
                            </div>
                            <span class="queue-count-badge">{{ $appointments->count() }}</span>
                        </div>

                        <div class="queue-list">
                            @foreach($appointments as $appt)
                                @php
                                    $statusClass = match($appt->queue_status) {
                                        'in_room' => 'in-room',
                                        'called'  => 'called',
                                        default   => 'waiting',
                                    };
                                    $statusLabel = match($appt->queue_status) {
                                        'in_room' => __('app.currently_seeing'),
                                        'called'  => __('app.called_next'),
                                        default   => __('app.waiting_in_queue'),
                                    };
                                @endphp

                                <div class="queue-item {{ $statusClass }}">
                                    <div class="queue-number">{{ $appt->queue_number }}</div>

                                    <div class="patient-info">
                                        <div class="patient-name">{{ $appt->patient->name }}</div>
                                        <div class="status-text">{{ $statusLabel }}</div>
                                    </div>

                                    <div class="status-icon">
                                        @if($appt->queue_status === 'in_room')
                                            {{-- Stethoscope --}}
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75H6a2.25 2.25 0 00-2.25 2.25v4.5A7.5 7.5 0 0011.25 18v0a3.75 3.75 0 007.5 0v-1.5"/>
                                                <circle cx="18.75" cy="16.5" r="1.5" fill="currentColor"/>
                                            </svg>
                                        @elseif($appt->queue_status === 'called')
                                            {{-- Bell --}}
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                            </svg>
                                        @else
                                            {{-- Clock --}}
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── FOOTER BAR ── --}}
    <div class="footer-bar">
        <span>{{ app()->getLocale() === 'ar' ? $clinic->name_ar : $clinic->name_en }}</span>
        <span class="refresh-info">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>
            {{ __('app.auto_refresh') }} — 15s
        </span>
        <span id="footer-time"></span>
    </div>

    <script>
        // Live clock
        function updateClock() {
            const now = new Date();
            const hh  = String(now.getHours()).padStart(2, '0');
            const mm  = String(now.getMinutes()).padStart(2, '0');
            const ss  = String(now.getSeconds()).padStart(2, '0');
            const timeStr = hh + ':' + mm + ':' + ss;

            const clockEl = document.getElementById('clock-time');
            if (clockEl) clockEl.textContent = timeStr;

            const footerEl = document.getElementById('footer-time');
            if (footerEl) footerEl.textContent = timeStr;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
