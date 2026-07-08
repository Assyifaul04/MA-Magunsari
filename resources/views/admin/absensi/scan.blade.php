<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi RFID</title>
    <link href="{{ asset('image/logo.png') }}" rel="icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --brand:          #2f9e44;
            --brand-dark:     #237032;
            --brand-deeper:   #1a5c26;
            --brand-mid:      #40c057;
            --brand-light:    #ebfbee;
            --brand-glow:     rgba(47, 158, 68, 0.18);
            --ink:            #0d2b14;
            --ink-soft:       #2d4a33;
            --surface:        #ffffff;
            --bg-page:        #f0faf2;
            --border-subtle:  rgba(47, 158, 68, 0.14);
            --success:        #0ca678;
            --shadow-card:    0 20px 60px rgba(47, 158, 68, 0.13), 0 4px 16px rgba(0,0,0,0.06);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg-page);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* ─── Decorative background ─── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 900px 600px at 110% 10%, rgba(47,158,68,0.10) 0%, transparent 70%),
                radial-gradient(ellipse 700px 500px at -10% 90%, rgba(47,158,68,0.07) 0%, transparent 65%);
            pointer-events: none;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(47,158,68,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(47,158,68,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* ─── Page wrapper ─── */
        .page-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* ─── Brand strip ─── */
        .brand-strip {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--brand), var(--brand-deeper));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px var(--brand-glow);
        }

        .brand-logo i { color: white; font-size: 18px; }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: 0.02em;
        }

        .brand-name span { color: var(--brand); }

        /* ─── Main card ─── */
        .scan-card {
            background: var(--surface);
            border-radius: 24px;
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-card);
            width: 100%;
            max-width: 460px;
            overflow: hidden;
            position: relative;
        }

        /* Top accent bar */
        .card-accent {
            height: 4px;
            background: linear-gradient(90deg, var(--brand-deeper), var(--brand), var(--brand-mid), var(--brand));
            background-size: 200% 100%;
            animation: slide-gradient 3s linear infinite;
        }

        @keyframes slide-gradient {
            0%   { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }

        .card-body-inner {
            padding: 36px 40px 40px;
        }

        /* ─── Header section ─── */
        .header-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .rfid-icon-wrap {
            width: 80px;
            height: 80px;
            margin: 0 auto 18px;
            position: relative;
        }

        .rfid-icon-bg {
            width: 80px;
            height: 80px;
            background: var(--brand-light);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: icon-float 3.5s ease-in-out infinite;
        }

        .rfid-icon-bg i {
            font-size: 36px;
            color: var(--brand);
        }

        /* Ripple rings */
        .rfid-icon-bg::before,
        .rfid-icon-bg::after {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 28px;
            border: 1.5px solid rgba(47,158,68,0.22);
            animation: ring-pulse 2.8s ease-out infinite;
        }
        .rfid-icon-bg::after {
            inset: -16px;
            border-radius: 34px;
            border-color: rgba(47,158,68,0.10);
            animation-delay: 0.5s;
        }

        @keyframes ring-pulse {
            0%   { opacity: 1; transform: scale(1); }
            70%  { opacity: 0; transform: scale(1.12); }
            100% { opacity: 0; transform: scale(1.12); }
        }

        @keyframes icon-float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-6px); }
        }

        .page-title {
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 13px;
            color: #8898aa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* ─── Status badge ─── */
        .status-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 14px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: var(--success);
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(12,166,120,0.4);
            animation: dot-ping 1.8s ease-in-out infinite;
        }

        @keyframes dot-ping {
            0%, 100% { box-shadow: 0 0 0 0 rgba(12,166,120,0.5); }
            50%       { box-shadow: 0 0 0 6px rgba(12,166,120,0); }
        }

        .status-label {
            font-size: 11.5px;
            color: #8898aa;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .jenis-badge {
            display: inline-block;
            padding: 5px 18px;
            border-radius: 100px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: white;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 4px 14px var(--brand-glow);
        }

        /* ─── Divider ─── */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-subtle), transparent);
            margin: 24px 0;
        }

        /* ─── RFID image ─── */
        .rfid-visual-wrap {
            text-align: center;
            margin-bottom: 20px;
        }

        .rfid-image {
            width: 140px;
            height: auto;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(47,158,68,0.16), 0 2px 8px rgba(0,0,0,0.08);
            border: 3px solid white;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            display: inline-block;
        }

        .rfid-image:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 16px 40px rgba(47,158,68,0.24), 0 4px 12px rgba(0,0,0,0.1);
        }

        .tap-hint {
            margin-top: 10px;
            font-size: 13px;
            color: #8898aa;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .tap-hint i { color: var(--brand); font-size: 15px; }

        /* ─── Progress bar ─── */
        .progress-track {
            height: 5px;
            background: var(--brand-light);
            border-radius: 100px;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--brand-dark), var(--brand), var(--brand-mid));
            border-radius: 100px;
            transition: width 0.35s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.55) 50%, transparent 100%);
            animation: shimmer 1.6s infinite;
        }

        @keyframes shimmer {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* ─── Input ─── */
        .input-wrap {
            position: relative;
            margin-bottom: 6px;
        }

        .rfid-input {
            width: 100%;
            padding: 13px 50px 13px 18px;
            border: 2px solid #d3f0da;
            border-radius: 12px;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
            background: #f6fdf8;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
            text-align: center;
            caret-color: var(--brand);
        }

        .rfid-input::placeholder { color: #9ec9a4; font-weight: 500; }

        .rfid-input:focus {
            border-color: var(--brand);
            background: white;
            box-shadow: 0 0 0 4px rgba(47,158,68,0.12);
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ec9a4;
            font-size: 18px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .rfid-input:focus ~ .input-icon { color: var(--brand); }

        /* Loading spinner */
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2.5px solid #d3f0da;
            border-top-color: var(--brand);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

        /* ─── Status message ─── */
        #statusMessage {
            min-height: 22px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
        }

        /* ─── Footer strip ─── */
        .footer-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 40px;
            background: #f6fdf8;
            border-top: 1px solid var(--border-subtle);
        }

        .footer-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #8898aa;
        }

        .footer-badge i.bi-shield-check { color: var(--success); }

        #currentTime {
            font-family: 'Sora', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: 0.03em;
        }

        /* ─── Alert toast container ─── */
        #alertContainer { min-width: 300px; }

        /* ─── Responsive ─── */
        @media (max-width: 520px) {
            .card-body-inner { padding: 28px 22px 30px; }
            .footer-strip { padding: 12px 22px; }
            .page-title { font-size: 19px; }
        }
    </style>
</head>

<body>
    <div class="bg-grid"></div>

    <div class="page-wrap">

        <!-- Brand -->
        <div class="brand-strip">
            <div class="brand-logo"><i class="bi bi-building"></i></div>
            <div class="brand-name">Smart<span>Absensi</span></div>
        </div>

        <!-- Main card -->
        <div class="scan-card">

            <!-- Top gradient bar -->
            <div class="card-accent"></div>

            <!-- Body -->
            <div class="card-body-inner">

                <!-- Header -->
                <div class="header-section">
                    <div class="rfid-icon-wrap">
                        <div class="rfid-icon-bg">
                            <i class="bi bi-credit-card-2-front"></i>
                        </div>
                    </div>

                    <h1 class="page-title">Sistem Absensi RFID</h1>
                    <p class="page-subtitle">Tap kartu untuk mencatat kehadiran</p>

                    <div class="status-wrap mt-3">
                        <div class="status-dot"></div>
                        <span class="status-label">Status Absen</span>
                        <span class="jenis-badge" id="jenisAbsen">MEMUAT...</span>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- RFID visual -->
                <div class="rfid-visual-wrap">
                    <img src="{{ asset('image/RFID.jpeg') }}" alt="RFID Card" class="rfid-image">
                    <div class="tap-hint">
                        <i class="bi bi-hand-index-thumb"></i>
                        Tempelkan kartu Anda pada reader
                    </div>
                </div>

                <!-- Progress bar -->
                <div class="progress-track">
                    <div id="progressBar" class="progress-fill" style="width: 0%;"></div>
                </div>

                <!-- Form -->
                <form id="rfidForm" autocomplete="off">
                    <div class="input-wrap">
                        <input
                            type="text"
                            name="rfid"
                            id="rfidInput"
                            placeholder="Menunggu scan kartu..."
                            class="rfid-input"
                            inputmode="none"
                            autocomplete="off"
                            autofocus
                        />
                        <input type="hidden" name="jenis" id="jenisInput">
                        <i class="bi bi-wifi input-icon" id="inputIcon"></i>
                        <div class="loading-spinner" id="loadingSpinner"></div>
                    </div>
                </form>

                <!-- Status message -->
                <div id="statusMessage" class="mt-2"></div>

            </div><!-- /card-body-inner -->

            <!-- Footer strip -->
            <div class="footer-strip">
                <div class="footer-badge">
                    <i class="bi bi-shield-check"></i>
                    Sistem Aktif
                </div>
                <div id="currentTime"></div>
            </div>

        </div><!-- /scan-card -->

    </div><!-- /page-wrap -->

    <!-- Alert toast -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="alertContainer"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/absensi-form.js') }}"></script>
</body>

</html>