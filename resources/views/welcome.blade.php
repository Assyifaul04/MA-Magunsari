<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>MAS Nurul Huda — Sistem Absensi RFID</title>
    <meta content="Sistem Absensi RFID MAS Nurul Huda Mangunsari" name="description">
    <link href="image/logo.png" rel="icon">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --emerald: #065f46;
            --emerald-mid: #047857;
            --emerald-light: #10b981;
            --emerald-pale: #d1fae5;
            --emerald-ghost: #ecfdf5;
            --gold: #b45309;
            --gold-light: #d97706;
            --gold-pale: #fef3c7;
            --ink: #0a1628;
            --ink-mid: #1e3a5f;
            --ink-soft: #334155;
            --slate: #64748b;
            --mist: #f1f5f9;
            --white: #ffffff;
            --border: rgba(6, 95, 70, 0.12);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--white);
            color: var(--ink);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* LOADER */
        #loader {
            position: fixed; inset: 0;
            background: var(--emerald);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        #loader.out { opacity: 0; visibility: hidden; }
        .loader-inner { text-align: center; color: white; }
        .loader-logo {
            width: 72px; height: 72px; border-radius: 18px;
            margin: 0 auto 1rem; overflow: hidden;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
        }
        .loader-logo img { width: 60px; height: 60px; object-fit: contain; }
        .loader-name { font-size: 1rem; font-weight: 700; letter-spacing: 0.04em; margin-bottom: 0.25rem; }
        .loader-sub { font-size: 0.75rem; color: rgba(255,255,255,0.6); letter-spacing: 0.08em; text-transform: uppercase; }
        .loader-ring {
            width: 32px; height: 32px;
            border: 2.5px solid rgba(255,255,255,0.25);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
            margin: 1.25rem auto 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* HEADER */
        .header {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 0.875rem 0;
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 12px rgba(10,22,40,0.06);
            transition: all 0.3s ease;
        }
        .header.scrolled { padding: 0.6rem 0; box-shadow: 0 2px 20px rgba(10,22,40,0.1); }
        .nav-wrap {
            max-width: 1200px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .logo-mark {
            display: flex; align-items: center; gap: 0.75rem;
            text-decoration: none;
        }
        .logo-img-wrap {
            width: 42px; height: 42px; border-radius: 10px; overflow: hidden;
            border: 1px solid var(--border);
            background: var(--emerald-ghost);
            display: flex; align-items: center; justify-content: center;
        }
        .logo-img-wrap img { width: 36px; height: 36px; object-fit: contain; }
        .logo-text { display: flex; flex-direction: column; line-height: 1.25; }
        .logo-name { font-size: 0.95rem; font-weight: 700; color: var(--emerald); letter-spacing: -0.01em; }
        .logo-sub { font-size: 0.68rem; font-weight: 500; color: var(--slate); text-transform: uppercase; letter-spacing: 0.08em; }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn-ghost {
            padding: 0.5rem 1.25rem; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            color: var(--ink-soft); border: 1px solid rgba(10,22,40,0.15);
            background: transparent; text-decoration: none;
            transition: all 0.2s ease;
            display: flex; align-items: center; gap: 0.375rem;
        }
        .btn-ghost:hover { background: var(--mist); color: var(--ink); border-color: rgba(10,22,40,0.25); }
        .btn-solid {
            padding: 0.5rem 1.375rem; border-radius: 8px;
            font-size: 0.875rem; font-weight: 600;
            color: white; background: var(--emerald);
            border: 1px solid var(--emerald);
            text-decoration: none; transition: all 0.22s ease;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .btn-solid:hover {
            background: var(--emerald-mid); color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(6,95,70,0.3);
        }

        /* HERO */
        .hero {
            min-height: 100vh; display: flex; align-items: center;
            padding: 7rem 0 4rem; position: relative;
            background: linear-gradient(155deg, #f0fdf4 0%, #ffffff 45%, #f8fafc 100%);
            overflow: hidden;
        }
        .hero-bg-decor {
            position: absolute; inset: 0; pointer-events: none;
            background:
                radial-gradient(circle at 12% 30%, rgba(16,185,129,0.09) 0%, transparent 48%),
                radial-gradient(circle at 88% 65%, rgba(180,83,9,0.055) 0%, transparent 45%),
                radial-gradient(circle at 55% 95%, rgba(6,95,70,0.045) 0%, transparent 48%);
        }
        .hero-grid {
            position: absolute; inset: 0; pointer-events: none; overflow: hidden;
            opacity: 0.04;
            background-image:
                linear-gradient(var(--emerald) 1px, transparent 1px),
                linear-gradient(90deg, var(--emerald) 1px, transparent 1px);
            background-size: 72px 72px;
        }
        .hero-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 2rem;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 4.5rem; align-items: center;
        }
        .hero-left { position: relative; z-index: 2; }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--emerald-ghost);
            border: 1px solid rgba(6,95,70,0.2);
            border-radius: 50px; padding: 0.375rem 1rem;
            font-size: 0.78rem; font-weight: 700;
            color: var(--emerald);
            text-transform: uppercase; letter-spacing: 0.07em;
            margin-bottom: 1.75rem;
            opacity: 0; animation: rise 0.7s ease forwards 0.25s;
        }
        .eyebrow-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--emerald-light);
            animation: pulse-dot 2s ease infinite;
        }
        @keyframes pulse-dot {
            0%,100%{opacity:1;transform:scale(1)}
            50%{opacity:0.5;transform:scale(1.5)}
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 3.8vw, 3.4rem);
            font-weight: 700; line-height: 1.16;
            color: var(--ink); margin-bottom: 1.5rem;
            opacity: 0; animation: rise 0.8s ease forwards 0.4s;
        }
        .hero-title .em {
            color: var(--emerald); position: relative; display: inline-block;
        }
        .hero-title .em::after {
            content: ''; position: absolute; bottom: -3px; left: 0; right: 0;
            height: 3px; border-radius: 2px;
            background: linear-gradient(90deg, var(--emerald-light), var(--emerald));
        }

        .hero-desc {
            font-size: 1.05rem; color: var(--slate); line-height: 1.75;
            margin-bottom: 2.5rem; max-width: 480px;
            opacity: 0; animation: rise 0.8s ease forwards 0.6s;
        }

        .hero-cta {
            display: flex; gap: 1rem; flex-wrap: wrap;
            opacity: 0; animation: rise 0.8s ease forwards 0.8s;
        }
        .btn-hero-p {
            padding: 0.875rem 2rem; background: var(--emerald);
            color: white; border: none; border-radius: 10px;
            font-size: 0.95rem; font-weight: 600; text-decoration: none;
            display: flex; align-items: center; gap: 0.5rem;
            transition: all 0.25s ease; cursor: pointer;
            box-shadow: 0 4px 20px rgba(6,95,70,0.28);
        }
        .btn-hero-p:hover {
            background: var(--emerald-mid); color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(6,95,70,0.38);
        }
        .btn-hero-s {
            padding: 0.875rem 2rem; background: white;
            color: var(--ink-soft); border: 1.5px solid rgba(10,22,40,0.18);
            border-radius: 10px; font-size: 0.95rem; font-weight: 600;
            text-decoration: none; display: flex; align-items: center; gap: 0.5rem;
            transition: all 0.25s ease;
        }
        .btn-hero-s:hover {
            border-color: var(--emerald); color: var(--emerald);
            transform: translateY(-2px); box-shadow: 0 4px 16px rgba(10,22,40,0.1);
        }

        /* HERO RIGHT CARD */
        .hero-right {
            position: relative; z-index: 2;
            opacity: 0; animation: rise 1s ease forwards 0.5s;
        }
        .hero-card {
            background: white; border-radius: 20px;
            border: 1px solid rgba(6,95,70,0.12);
            box-shadow: 0 20px 60px rgba(10,22,40,0.12), 0 4px 16px rgba(6,95,70,0.07);
            overflow: hidden;
        }
        .card-topbar {
            background: var(--emerald);
            padding: 1.125rem 1.625rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-topbar .ct-title { color: white; font-weight: 600; font-size: 0.875rem; }
        .card-topbar .ct-clock { color: rgba(255,255,255,0.75); font-size: 0.8rem; font-variant-numeric: tabular-nums; }
        .card-body { padding: 1.375rem; }

        .scan-zone {
            background: var(--emerald-ghost);
            border: 2px dashed rgba(6,95,70,0.28);
            border-radius: 14px; padding: 1.75rem;
            text-align: center; margin-bottom: 1.25rem;
        }
        .scan-icon {
            width: 52px; height: 52px; background: var(--emerald); border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.875rem; font-size: 1.4rem; color: white;
        }
        .scan-lbl { font-size: 0.85rem; font-weight: 600; color: var(--ink-soft); }
        .scan-sub { font-size: 0.75rem; color: var(--slate); margin-top: 0.2rem; }

        .log-list { display: flex; flex-direction: column; gap: 0.6rem; }
        .log-item {
            display: flex; align-items: center; gap: 0.8rem;
            padding: 0.7rem 0.875rem;
            background: var(--mist); border-radius: 10px;
        }
        .log-av {
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
        }
        .av-g { background: var(--emerald-pale); color: var(--emerald); }
        .av-o { background: var(--gold-pale); color: var(--gold); }
        .log-info { flex: 1; }
        .log-name { font-size: 0.85rem; font-weight: 600; color: var(--ink); }
        .log-meta { font-size: 0.73rem; color: var(--slate); }
        .log-badge {
            font-size: 0.68rem; font-weight: 700;
            padding: 0.22rem 0.6rem; border-radius: 50px;
        }
        .b-hadir { background: var(--emerald-pale); color: var(--emerald); }
        .b-izin  { background: var(--gold-pale); color: var(--gold); }

        .card-foot {
            border-top: 1px solid var(--border);
            padding: 0.875rem 1.625rem;
            display: grid; grid-template-columns: repeat(3, 1fr);
        }
        .foot-stat { text-align: center; }
        .foot-stat .fv {
            display: block; font-size: 1.3rem; font-weight: 700;
            color: var(--emerald); line-height: 1.2;
        }
        .foot-stat .fv.orange { color: var(--gold); }
        .foot-stat .fv.red    { color: #dc2626; }
        .foot-stat .fl {
            font-size: 0.68rem; color: var(--slate);
            text-transform: uppercase; letter-spacing: 0.06em;
        }

        /* TRUST */
        .trust {
            padding: 1.25rem 0;
            border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            background: var(--mist);
        }
        .trust-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: center;
            gap: 2.5rem; flex-wrap: wrap;
        }
        .trust-item {
            display: flex; align-items: center; gap: 0.55rem;
            font-size: 0.83rem; font-weight: 500; color: var(--slate);
        }
        .trust-item i { color: var(--emerald); font-size: 1rem; }

        /* STATS */
        .stats-bar {
            padding: 4rem 0;
            background: linear-gradient(135deg, var(--emerald) 0%, #064e3b 100%);
            position: relative; overflow: hidden;
        }
        .stats-bar::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 75% 50%, rgba(255,255,255,0.05) 0%, transparent 55%);
        }
        .stats-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 2rem;
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 1rem; position: relative; z-index: 2; align-items: center;
        }
        .stat-block { text-align: center; }
        .stat-block .sn {
            font-family: 'Playfair Display', serif;
            font-size: 2.75rem; font-weight: 700; color: white;
            line-height: 1; margin-bottom: 0.5rem; display: block;
        }
        .stat-block .su { font-size: 1rem; color: rgba(255,255,255,0.6); }
        .stat-block .sl {
            font-size: 0.78rem; font-weight: 500;
            color: rgba(255,255,255,0.65);
            text-transform: uppercase; letter-spacing: 0.08em;
        }
        .stat-div {
            width: 1px; background: rgba(255,255,255,0.15);
            height: 60px; margin: 0 auto;
        }

        /* FEATURES */
        .features { padding: 5.5rem 0; background: white; }
        .section-wrap { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        .section-head { text-align: center; margin-bottom: 3.5rem; }
        .sec-label {
            display: inline-block;
            font-size: 0.73rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--emerald);
            background: var(--emerald-ghost);
            border: 1px solid rgba(6,95,70,0.2);
            border-radius: 50px; padding: 0.3rem 0.9rem; margin-bottom: 1rem;
        }
        .sec-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.75rem, 3vw, 2.4rem);
            font-weight: 700; color: var(--ink);
            line-height: 1.25; margin-bottom: 0.875rem;
        }
        .sec-desc {
            font-size: 1rem; color: var(--slate);
            max-width: 540px; margin: 0 auto; line-height: 1.7;
        }
        .feat-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.375rem;
        }
        .feat-card {
            padding: 1.875rem; border: 1px solid var(--border); border-radius: 16px;
            background: white; transition: all 0.3s ease; position: relative; overflow: hidden;
        }
        .feat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--emerald-light), var(--emerald));
            transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease;
        }
        .feat-card:hover { border-color: rgba(6,95,70,0.25); transform: translateY(-5px); box-shadow: 0 12px 40px rgba(6,95,70,0.1); }
        .feat-card:hover::before { transform: scaleX(1); }
        .feat-ico {
            width: 50px; height: 50px; border-radius: 13px;
            background: var(--emerald-ghost); border: 1px solid rgba(6,95,70,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: var(--emerald); margin-bottom: 1.2rem;
        }
        .feat-title { font-size: 1.025rem; font-weight: 700; color: var(--ink); margin-bottom: 0.6rem; }
        .feat-desc { font-size: 0.86rem; color: var(--slate); line-height: 1.65; }

        /* FOOTER */
        .footer { background: var(--ink); padding: 2.5rem 0; }
        .foot-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;
        }
        .foot-brand { display: flex; align-items: center; gap: 0.75rem; }
        .foot-logo-wrap {
            width: 38px; height: 38px; border-radius: 9px;
            background: rgba(255,255,255,0.1); overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .foot-logo-wrap img { width: 32px; height: 32px; object-fit: contain; filter: brightness(1.2); }
        .foot-brand-text .fn { font-size: 0.875rem; font-weight: 600; color: white; }
        .foot-brand-text .fs { font-size: 0.72rem; color: rgba(255,255,255,0.4); }
        .foot-copy { font-size: 0.78rem; color: rgba(255,255,255,0.35); }

        /* ANIMATIONS */
        @keyframes rise { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { opacity:0; transform:translateY(28px); transition:opacity 0.7s ease,transform 0.7s ease; }
        .fade-up.visible { opacity:1; transform:translateY(0); }

        /* RESPONSIVE */
        @media(max-width:920px){
            .hero-inner{grid-template-columns:1fr;gap:3rem}
            .hero-right{display:none}
            .stats-inner{grid-template-columns:repeat(2,1fr)}
            .feat-grid{grid-template-columns:repeat(2,1fr)}
            .stat-div{display:none}
        }
        @media(max-width:600px){
            .feat-grid{grid-template-columns:1fr}
            .stats-inner{grid-template-columns:1fr 1fr}
            .trust-inner{gap:1.25rem}
            .nav-actions .btn-ghost{display:none}
        }
    </style>
</head>
<body>

<!-- LOADER -->
<div id="loader">
    <div class="loader-inner">
        <div class="loader-logo">
            <img src="image/logo.png" alt="Logo MAS Nurul Huda">
        </div>
        <div class="loader-name">MAS Nurul Huda</div>
        <div class="loader-sub">Sistem Absensi RFID</div>
        <div class="loader-ring"></div>
    </div>
</div>

<!-- HEADER -->
<header class="header" id="mainHeader">
    <div class="nav-wrap">
        <a href="#" class="logo-mark">
            <div class="logo-img-wrap">
                <img src="image/logo.png" alt="Logo MAS Nurul Huda">
            </div>
            <div class="logo-text">
                <span class="logo-name">MAS Nurul Huda</span>
                <span class="logo-sub">Mangunsari</span>
            </div>
        </a>
        <div class="nav-actions">
            <a href="#features" class="btn-ghost">
                <i class="bi bi-grid-3x3-gap"></i> Fitur
            </a>
            <a href="/login" class="btn-solid">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </a>
        </div>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg-decor"></div>
    <div class="hero-grid"></div>
    <div class="hero-inner">
        <div class="hero-left">
            <div class="eyebrow">
                <span class="eyebrow-dot"></span>
                Sistem Absensi Digital
            </div>
            <h1 class="hero-title">
                Absensi Presisi<br>dengan <span class="em">Teknologi RFID</span>
            </h1>
            <p class="hero-desc">
                Platform manajemen kehadiran modern untuk MAS Nurul Huda Mangunsari.
                Rekam, pantau, dan analisis data absensi siswa &amp; guru secara real-time
                dengan akurasi tinggi.
            </p>
            <div class="hero-cta">
                <a href="/login" class="btn-hero-p">
                    <i class="bi bi-shield-lock-fill"></i>
                    Masuk ke Sistem
                </a>
                <a href="#features" class="btn-hero-s">
                    <i class="bi bi-arrow-down-circle"></i>
                    Pelajari Fitur
                </a>
            </div>
        </div>

        <div class="hero-right">
            <div class="hero-card">
                <div class="card-topbar">
                    <span class="ct-title"><i class="bi bi-activity"></i>&nbsp; Dashboard Absensi</span>
                    <span class="ct-clock" id="live-clock">00:00:00</span>
                </div>
                <div class="card-body">
                    <div class="scan-zone">
                        <div class="scan-icon"><i class="bi bi-wifi"></i></div>
                        <div class="scan-lbl">Tap Kartu RFID</div>
                        <div class="scan-sub">Menunggu pemindaian...</div>
                    </div>
                    <div class="log-list">
                        <div class="log-item">
                            <div class="log-av av-g">AH</div>
                            <div class="log-info">
                                <div class="log-name">Ahmad Hakim</div>
                                <div class="log-meta">XII IPA 1 &nbsp;·&nbsp; 06:52</div>
                            </div>
                            <span class="log-badge b-hadir">Hadir</span>
                        </div>
                        <div class="log-item">
                            <div class="log-av av-g">SF</div>
                            <div class="log-info">
                                <div class="log-name">Siti Fatimah</div>
                                <div class="log-meta">XI IPS 2 &nbsp;·&nbsp; 06:55</div>
                            </div>
                            <span class="log-badge b-hadir">Hadir</span>
                        </div>
                        <div class="log-item">
                            <div class="log-av av-o">MR</div>
                            <div class="log-info">
                                <div class="log-name">M. Rizky</div>
                                <div class="log-meta">X IPA 3 &nbsp;·&nbsp; 06:59</div>
                            </div>
                            <span class="log-badge b-izin">Izin</span>
                        </div>
                    </div>
                </div>
                <div class="card-foot">
                    <div class="foot-stat">
                        <span class="fv" id="c-hadir">0</span>
                        <span class="fl">Hadir</span>
                    </div>
                    <div class="foot-stat">
                        <span class="fv orange" id="c-izin">0</span>
                        <span class="fl">Izin</span>
                    </div>
                    <div class="foot-stat">
                        <span class="fv red" id="c-alpha">0</span>
                        <span class="fl">Alpha</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TRUST STRIP -->
<div class="trust">
    <div class="trust-inner">
        <div class="trust-item"><i class="bi bi-shield-fill-check"></i> Data Terenkripsi</div>
        <div class="trust-item"><i class="bi bi-cloud-check-fill"></i> Backup Otomatis</div>
        <div class="trust-item"><i class="bi bi-clock-history"></i> Real-time Monitoring</div>
        <div class="trust-item"><i class="bi bi-phone-fill"></i> Notifikasi Orang Tua</div>
        <div class="trust-item"><i class="bi bi-printer-fill"></i> Laporan Siap Cetak</div>
    </div>
</div>

<!-- STATS -->
<section class="stats-bar">
    <div class="stats-inner">
        <div class="stat-block">
            <span class="sn" id="n-siswa">0</span>
            <div class="sl">Total Siswa</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat-block">
            <span class="sn" id="n-guru">0</span>
            <div class="sl">Tenaga Pendidik</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat-block">
            <span class="sn">99.9<span class="su">%</span></span>
            <div class="sl">Akurasi Sistem</div>
        </div>
        <div class="stat-div"></div>
        <div class="stat-block">
            <span class="sn">24<span class="su">/7</span></span>
            <div class="sl">Operasional</div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features" id="features">
    <div class="section-wrap">
        <div class="section-head fade-up">
            <span class="sec-label">Keunggulan Sistem</span>
            <h2 class="sec-title">Fitur Dirancang untuk<br>Kemudahan Pengelolaan</h2>
            <p class="sec-desc">Teknologi modern yang memudahkan administrasi kehadiran di lingkungan madrasah dengan akurasi dan efisiensi tinggi</p>
        </div>
        <div class="feat-grid">
            <div class="feat-card fade-up">
                <div class="feat-ico"><i class="bi bi-wifi"></i></div>
                <h3 class="feat-title">Teknologi RFID</h3>
                <p class="feat-desc">Absensi contactless menggunakan kartu RFID untuk kecepatan proses dan akurasi tinggi tanpa antrian panjang.</p>
            </div>
            <div class="feat-card fade-up">
                <div class="feat-ico"><i class="bi bi-graph-up-arrow"></i></div>
                <h3 class="feat-title">Dashboard Real-time</h3>
                <p class="feat-desc">Pantau kehadiran siswa dan guru secara langsung dengan visualisasi data interaktif yang mudah dipahami.</p>
            </div>
            <div class="feat-card fade-up">
                <div class="feat-ico"><i class="bi bi-bell-fill"></i></div>
                <h3 class="feat-title">Notifikasi Otomatis</h3>
                <p class="feat-desc">Orang tua menerima pemberitahuan otomatis via WhatsApp saat anak tidak hadir atau terlambat masuk.</p>
            </div>
            <div class="feat-card fade-up">
                <div class="feat-ico"><i class="bi bi-file-earmark-bar-graph"></i></div>
                <h3 class="feat-title">Laporan Lengkap</h3>
                <p class="feat-desc">Generate rekap absensi harian, mingguan, dan bulanan dalam format PDF atau Excel hanya dengan satu klik.</p>
            </div>
            <div class="feat-card fade-up">
                <div class="feat-ico"><i class="bi bi-shield-lock"></i></div>
                <h3 class="feat-title">Keamanan Data</h3>
                <p class="feat-desc">Data tersimpan aman dengan enkripsi tingkat enterprise dan sistem backup otomatis yang berjalan setiap hari.</p>
            </div>
            <div class="feat-card fade-up">
                <div class="feat-ico"><i class="bi bi-phone"></i></div>
                <h3 class="feat-title">Akses Multiplatform</h3>
                <p class="feat-desc">Dapat diakses dari perangkat apapun — komputer, tablet, atau smartphone — kapan saja dan di mana saja.</p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="foot-inner">
        <div class="foot-brand">
            <div class="foot-logo-wrap">
                <img src="image/logo.png" alt="Logo MAS Nurul Huda">
            </div>
            <div class="foot-brand-text">
                <div class="fn">MAS Nurul Huda Mangunsari</div>
                <div class="fs">Sistem Absensi RFID</div>
            </div>
        </div>
        <div class="foot-copy">
            &copy; <span id="yr"></span> MAS Nurul Huda. Hak cipta dilindungi.
        </div>
    </div>
</footer>

<script>
    window.addEventListener('load',()=>{ setTimeout(()=>document.getElementById('loader').classList.add('out'),950); });
    document.getElementById('yr').textContent = new Date().getFullYear();
    window.addEventListener('scroll',()=>{ document.getElementById('mainHeader').classList.toggle('scrolled',window.scrollY>40); });

    function updateClock(){
        const n=new Date();
        const pad=v=>String(v).padStart(2,'0');
        const el=document.getElementById('live-clock');
        if(el) el.textContent=`${pad(n.getHours())}:${pad(n.getMinutes())}:${pad(n.getSeconds())}`;
    }
    updateClock(); setInterval(updateClock,1000);

    function countUp(el,target,dur=1800){
        let v=0; const step=target/(dur/16);
        const tick=()=>{ v=Math.min(v+step,target); el.textContent=Math.round(v); if(v<target)requestAnimationFrame(tick); };
        requestAnimationFrame(tick);
    }

    let statsDone=false;
    new IntersectionObserver(entries=>{
        if(entries[0].isIntersecting && !statsDone){
            statsDone=true;
            countUp(document.getElementById('n-siswa'),850);
            countUp(document.getElementById('n-guru'),45);
        }
    },{threshold:0.4}).observe(document.querySelector('.stats-bar'));

    let cardDone=false;
    setTimeout(()=>{
        if(cardDone)return; cardDone=true;
        let h=0,i=0,a=0;
        const cH=document.getElementById('c-hadir'),cI=document.getElementById('c-izin'),cA=document.getElementById('c-alpha');
        const t=setInterval(()=>{
            h=Math.min(h+4,312); cH.textContent=h;
            i=Math.min(i+1,28);  cI.textContent=i;
            a=Math.min(a+1,12);  cA.textContent=a;
            if(h>=312&&i>=28&&a>=12) clearInterval(t);
        },28);
    },1600);

    const fadeObs=new IntersectionObserver(entries=>{
        entries.forEach((e,idx)=>{ if(e.isIntersecting){ setTimeout(()=>e.target.classList.add('visible'),idx*90); fadeObs.unobserve(e.target); } });
    },{threshold:0.12,rootMargin:'0px 0px -40px 0px'});
    document.querySelectorAll('.fade-up').forEach(el=>fadeObs.observe(el));

    document.querySelectorAll('a[href^="#"]').forEach(a=>{
        a.addEventListener('click',e=>{ const t=document.querySelector(a.getAttribute('href')); if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'});} });
    });

    const ps=document.createElement('style');
    ps.textContent='@keyframes pf{to{opacity:0;transform:translateY(-18px) scale(0)}}';
    document.head.appendChild(ps);
    document.addEventListener('mousemove',e=>{
        if(Math.random()>0.994){
            const p=document.createElement('div');
            p.style.cssText=`position:fixed;width:5px;height:5px;background:rgba(6,95,70,0.4);border-radius:50%;pointer-events:none;left:${e.clientX}px;top:${e.clientY}px;z-index:1;animation:pf 0.8s ease-out forwards`;
            document.body.appendChild(p); setTimeout(()=>p.remove(),800);
        }
    });
</script>
</body>
</html>