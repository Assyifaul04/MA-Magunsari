<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>MAS Nurul Huda — Sistem Absensi RFID</title>
    <meta content="Sistem Absensi RFID MAS Nurul Huda Mangunsari" name="description">
    <link href="image/Logo.png" rel="icon">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --g-900: #052e16;
            --g-800: #065f46;
            --g-700: #047857;
            --g-500: #10b981;
            --g-300: #6ee7b7;
            --g-100: #d1fae5;
            --g-50:  #ecfdf5;
            --amber: #f59e0b;
            --amber-light: #fef3c7;
            --navy: #0f172a;
            --navy-600: #1e3a5f;
            --slate-500: #64748b;
            --slate-300: #cbd5e1;
            --slate-100: #f1f5f9;
            --white: #ffffff;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 22px;
            --shadow-card: 0 2px 8px rgba(15,23,42,.06), 0 8px 32px rgba(15,23,42,.08);
            --shadow-pop:  0 8px 24px rgba(6,95,70,.18), 0 24px 64px rgba(6,95,70,.12);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--white);
            color: var(--navy);
            overflow-x: hidden;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── LOADER ─────────────────────────────────── */
        #loader {
            position: fixed; inset: 0; z-index: 9999;
            background: var(--g-900);
            display: flex; align-items: center; justify-content: center;
            flex-direction: column; gap: 1.5rem;
            transition: opacity .55s ease, visibility .55s ease;
        }
        #loader.out { opacity: 0; visibility: hidden; }

        .ld-logo {
            width: 76px; height: 76px; border-radius: 20px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .ld-logo img { width: 60px; height: 60px; object-fit: contain; }
        .ld-name {
            font-size: .875rem; font-weight: 700; color: rgba(255,255,255,.9);
            letter-spacing: .04em; text-align: center;
        }
        .ld-sub {
            font-size: .72rem; color: rgba(255,255,255,.4);
            letter-spacing: .1em; text-transform: uppercase; margin-top: .2rem; text-align: center;
        }
        .ld-bar {
            width: 160px; height: 2px;
            background: rgba(255,255,255,.1); border-radius: 99px; overflow: hidden;
        }
        .ld-fill {
            height: 100%; background: var(--g-500); border-radius: 99px;
            animation: ldprog 1.1s ease forwards;
        }
        @keyframes ldprog { from { width: 0% } to { width: 100% } }

        /* ─── HEADER ─────────────────────────────────── */
        .hdr {
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
            padding: .9rem 0;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(20px) saturate(1.6);
            border-bottom: 1px solid rgba(6,95,70,.1);
            transition: padding .3s ease, box-shadow .3s ease;
        }
        .hdr.stuck { padding: .6rem 0; box-shadow: 0 2px 20px rgba(15,23,42,.08); }

        .nav {
            max-width: 1240px; margin: 0 auto; padding: 0 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
        }

        .brand {
            display: flex; align-items: center; gap: .75rem; text-decoration: none;
        }
        .brand-badge {
            width: 44px; height: 44px; border-radius: 12px; overflow: hidden;
            border: 1.5px solid rgba(6,95,70,.15);
            background: var(--g-50);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-badge img { width: 36px; height: 36px; object-fit: contain; }
        .brand-text { display: flex; flex-direction: column; gap: .05rem; }
        .brand-name { font-size: .92rem; font-weight: 700; color: var(--g-800); letter-spacing: -.01em; line-height: 1.2; }
        .brand-sub  { font-size: .65rem; font-weight: 500; color: var(--slate-500); letter-spacing: .08em; text-transform: uppercase; }

        .nav-menu { display: flex; align-items: center; gap: .5rem; }

        .nav-link {
            padding: .48rem 1rem; border-radius: var(--radius-sm);
            font-size: .83rem; font-weight: 500; color: var(--slate-500);
            text-decoration: none; transition: all .18s ease;
            display: flex; align-items: center; gap: .35rem;
        }
        .nav-link:hover { background: var(--slate-100); color: var(--navy); }

        .btn-login {
            padding: .52rem 1.25rem; border-radius: var(--radius-sm);
            font-size: .83rem; font-weight: 600; color: var(--white);
            background: var(--g-800); border: none; text-decoration: none;
            display: flex; align-items: center; gap: .4rem; cursor: pointer;
            transition: all .2s ease;
        }
        .btn-login:hover {
            background: var(--g-700); color: var(--white);
            transform: translateY(-1px); box-shadow: 0 4px 16px rgba(6,95,70,.3);
        }

        /* ─── HERO ───────────────────────────────────── */
        .hero {
            min-height: 100vh; display: flex; align-items: center;
            padding: 8rem 0 5rem; position: relative; overflow: hidden;
            background:
                radial-gradient(ellipse 70% 55% at 5% 60%, rgba(16,185,129,.07) 0%, transparent 60%),
                radial-gradient(ellipse 55% 50% at 95% 30%, rgba(245,158,11,.05) 0%, transparent 55%),
                var(--white);
        }

        /* Subtle geometric accent */
        .hero::before {
            content: '';
            position: absolute; top: 0; right: 0;
            width: 52%; height: 100%;
            background: linear-gradient(145deg, #f0fdf4 0%, #f8fafc 100%);
            clip-path: polygon(12% 0, 100% 0, 100% 100%, 0% 100%);
            z-index: 0;
        }

        .hero-wrap {
            max-width: 1240px; margin: 0 auto; padding: 0 1.75rem;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 5rem; align-items: center; position: relative; z-index: 1;
        }

        /* LEFT */
        .hero-left { position: relative; }

        .pill {
            display: inline-flex; align-items: center; gap: .45rem;
            background: var(--g-50); border: 1px solid rgba(6,95,70,.2);
            border-radius: 50px; padding: .32rem .875rem;
            font-size: .72rem; font-weight: 700; color: var(--g-800);
            letter-spacing: .07em; text-transform: uppercase;
            margin-bottom: 1.5rem;
            opacity: 0; animation: slideUp .65s ease forwards .2s;
        }
        .pill-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--g-500); flex-shrink: 0;
            animation: breathe 2.4s ease infinite;
        }
        @keyframes breathe { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(1.6)} }

        .hero-h1 {
            font-family: 'DM Serif Display', Georgia, serif;
            font-size: clamp(2.4rem, 4vw, 3.6rem);
            font-weight: 400; line-height: 1.12;
            color: var(--navy); margin-bottom: 1.375rem;
            opacity: 0; animation: slideUp .75s ease forwards .36s;
        }
        .hero-h1 .accent {
            color: var(--g-800);
            background: linear-gradient(135deg, var(--g-800), var(--g-500));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
        }

        .hero-body {
            font-size: 1rem; color: var(--slate-500); line-height: 1.78;
            max-width: 460px; margin-bottom: 2.25rem;
            opacity: 0; animation: slideUp .75s ease forwards .52s;
        }

        .hero-btns {
            display: flex; gap: .875rem; flex-wrap: wrap;
            opacity: 0; animation: slideUp .75s ease forwards .68s;
        }
        .btn-primary {
            padding: .825rem 1.875rem; border-radius: 10px;
            background: var(--g-800); color: var(--white);
            font-size: .9rem; font-weight: 600; text-decoration: none;
            display: flex; align-items: center; gap: .45rem;
            transition: all .22s ease;
            box-shadow: 0 4px 20px rgba(6,95,70,.25);
        }
        .btn-primary:hover {
            background: var(--g-700); color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(6,95,70,.35);
        }
        .btn-outline {
            padding: .825rem 1.875rem; border-radius: 10px;
            background: transparent; color: var(--navy-600);
            border: 1.5px solid var(--slate-300);
            font-size: .9rem; font-weight: 600; text-decoration: none;
            display: flex; align-items: center; gap: .45rem;
            transition: all .22s ease;
        }
        .btn-outline:hover {
            border-color: var(--g-500); color: var(--g-800);
            transform: translateY(-2px); background: var(--g-50);
        }

        /* HERO METRICS ROW */
        .hero-metrics {
            display: flex; gap: 2rem; margin-top: 2.5rem; padding-top: 2rem;
            border-top: 1px solid rgba(6,95,70,.1);
            opacity: 0; animation: slideUp .75s ease forwards .84s;
        }
        .metric-item {}
        .metric-val {
            font-family: 'DM Serif Display', serif;
            font-size: 1.75rem; color: var(--navy); line-height: 1;
        }
        .metric-lbl {
            font-size: .72rem; color: var(--slate-500); font-weight: 500;
            text-transform: uppercase; letter-spacing: .07em; margin-top: .2rem;
        }

        /* RIGHT — DASHBOARD CARD */
        .hero-right {
            position: relative;
            opacity: 0; animation: slideUp .85s ease forwards .4s;
        }

        /* Decorative blobs behind card */
        .card-glow {
            position: absolute; border-radius: 50%; filter: blur(48px); pointer-events: none;
        }
        .glow-g { width: 280px; height: 280px; background: rgba(16,185,129,.14); top: -40px; right: -30px; }
        .glow-a { width: 180px; height: 180px; background: rgba(245,158,11,.09); bottom: -20px; left: -20px; }

        .dashboard-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(6,95,70,.1);
            box-shadow: var(--shadow-pop);
            overflow: hidden; position: relative; z-index: 1;
        }

        /* Card header bar */
        .dc-header {
            background: linear-gradient(135deg, var(--g-900) 0%, var(--g-800) 100%);
            padding: 1rem 1.375rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .dc-header-left {
            display: flex; align-items: center; gap: .6rem;
        }
        .dc-logo {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(255,255,255,.12); overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .dc-logo img { width: 26px; height: 26px; object-fit: contain; }
        .dc-title { font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.95); }
        .dc-sub   { font-size: .68rem; color: rgba(255,255,255,.45); margin-top: .05rem; }
        .dc-clock {
            font-size: .78rem; color: rgba(255,255,255,.7);
            font-variant-numeric: tabular-nums; letter-spacing: .03em;
            background: rgba(255,255,255,.08); padding: .3rem .65rem; border-radius: 6px;
        }

        /* Tabs */
        .dc-tabs {
            display: flex; border-bottom: 1px solid rgba(6,95,70,.1);
            padding: 0 1.375rem;
        }
        .dc-tab {
            padding: .7rem .875rem; font-size: .75rem; font-weight: 500;
            color: var(--slate-500); border-bottom: 2px solid transparent;
            cursor: pointer; transition: all .18s ease; user-select: none;
        }
        .dc-tab.active { color: var(--g-800); border-color: var(--g-700); font-weight: 600; }

        .dc-body { padding: 1.125rem 1.375rem; }

        /* Stat chips row */
        .stat-row {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem;
            margin-bottom: 1.125rem;
        }
        .stat-chip {
            padding: .7rem .75rem; border-radius: 10px;
            text-align: center; position: relative; overflow: hidden;
        }
        .chip-g { background: var(--g-50); border: 1px solid rgba(6,95,70,.14); }
        .chip-a { background: var(--amber-light); border: 1px solid rgba(245,158,11,.2); }
        .chip-r { background: #fff1f2; border: 1px solid rgba(220,38,38,.15); }
        .chip-val {
            display: block; font-family: 'DM Serif Display', serif;
            font-size: 1.5rem; line-height: 1.1; margin-bottom: .1rem;
        }
        .chip-g .chip-val { color: var(--g-800); }
        .chip-a .chip-val { color: #b45309; }
        .chip-r .chip-val { color: #dc2626; }
        .chip-lbl { font-size: .67rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-500); }

        /* Progress bar mini */
        .progress-row { margin-bottom: 1.125rem; }
        .pr-label { display: flex; justify-content: space-between; font-size: .73rem; margin-bottom: .35rem; }
        .pr-label span:first-child { font-weight: 500; color: var(--navy); }
        .pr-label span:last-child  { color: var(--slate-500); }
        .pr-track { height: 6px; background: var(--slate-100); border-radius: 99px; overflow: hidden; }
        .pr-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--g-500), var(--g-700)); transition: width 1.4s ease; }

        /* Scan zone */
        .scan-box {
            background: var(--g-50);
            border: 2px dashed rgba(6,95,70,.25);
            border-radius: 12px; padding: 1.375rem;
            text-align: center; margin-bottom: 1.125rem;
        }
        .scan-icon {
            width: 44px; height: 44px; border-radius: 11px; margin: 0 auto .7rem;
            background: var(--g-800); display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: white; animation: rfidPulse 2.5s ease infinite;
        }
        @keyframes rfidPulse {
            0%,100%{box-shadow:0 0 0 0 rgba(6,95,70,.35)}
            50%{box-shadow:0 0 0 10px rgba(6,95,70,0)}
        }
        .scan-ttl { font-size: .82rem; font-weight: 600; color: var(--navy); margin-bottom: .15rem; }
        .scan-hint { font-size: .7rem; color: var(--slate-500); }

        /* Log items */
        .log-scroll { display: flex; flex-direction: column; gap: .5rem; }
        .log-row {
            display: flex; align-items: center; gap: .7rem;
            padding: .6rem .75rem; background: var(--slate-100);
            border-radius: 9px; transition: background .15s ease;
        }
        .log-row:hover { background: var(--g-50); }
        .av {
            width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .7rem; font-weight: 700;
        }
        .av-g { background: var(--g-100); color: var(--g-800); }
        .av-a { background: var(--amber-light); color: #92400e; }
        .log-info { flex: 1; min-width: 0; }
        .log-n { font-size: .8rem; font-weight: 600; color: var(--navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .log-m { font-size: .68rem; color: var(--slate-500); }
        .badge {
            padding: .2rem .55rem; border-radius: 50px;
            font-size: .64rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
        }
        .b-hadir { background: var(--g-100); color: var(--g-800); }
        .b-izin  { background: var(--amber-light); color: #92400e; }
        .b-alpha { background: #fee2e2; color: #991b1b; }

        /* ─── TRUST STRIP ─────────────────────────────── */
        .trust {
            padding: 1rem 0;
            background: var(--slate-100);
            border-top: 1px solid var(--slate-300);
            border-bottom: 1px solid var(--slate-300);
        }
        .trust-inner {
            max-width: 1240px; margin: 0 auto; padding: 0 1.75rem;
            display: flex; align-items: center; justify-content: center;
            gap: 2.25rem; flex-wrap: wrap;
        }
        .trust-chip {
            display: flex; align-items: center; gap: .45rem;
            font-size: .78rem; font-weight: 500; color: var(--slate-500);
        }
        .trust-chip i { color: var(--g-700); font-size: .9rem; }

        /* ─── STATS SECTION ───────────────────────────── */
        .stats {
            padding: 4.5rem 0;
            background: linear-gradient(135deg, var(--g-900) 0%, #064e3b 55%, #053a2e 100%);
            position: relative; overflow: hidden;
        }
        .stats::after {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .stats-inner {
            max-width: 1240px; margin: 0 auto; padding: 0 1.75rem;
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: .5rem; position: relative; z-index: 1;
        }
        .stat-card {
            padding: 2rem 1.5rem; text-align: center; border-radius: var(--radius-md);
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
            transition: background .2s ease, transform .2s ease;
        }
        .stat-card:hover { background: rgba(255,255,255,.07); transform: translateY(-3px); }
        .stat-num {
            font-family: 'DM Serif Display', serif;
            font-size: 2.8rem; color: var(--white); line-height: 1;
            margin-bottom: .5rem;
        }
        .stat-unit { font-size: 1.6rem; color: var(--g-300); }
        .stat-lbl {
            font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.45);
            text-transform: uppercase; letter-spacing: .1em;
        }

        /* ─── FEATURES ────────────────────────────────── */
        .features { padding: 6rem 0; background: var(--white); }
        .sec-wrap { max-width: 1240px; margin: 0 auto; padding: 0 1.75rem; }

        .sec-head { text-align: center; margin-bottom: 3.5rem; }
        .sec-eyebrow {
            display: inline-flex; align-items: center; gap: .4rem;
            background: var(--g-50); border: 1px solid rgba(6,95,70,.18);
            border-radius: 50px; padding: .28rem .85rem;
            font-size: .7rem; font-weight: 700; color: var(--g-800);
            text-transform: uppercase; letter-spacing: .08em; margin-bottom: 1rem;
        }
        .sec-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 400;
            color: var(--navy); line-height: 1.22; margin-bottom: .75rem;
        }
        .sec-body { font-size: .95rem; color: var(--slate-500); max-width: 520px; margin: 0 auto; line-height: 1.7; }

        .feat-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.125rem;
        }
        .feat-card {
            padding: 1.875rem; border: 1px solid rgba(6,95,70,.1);
            border-radius: var(--radius-md); background: var(--white);
            transition: all .28s ease; position: relative; overflow: hidden;
            cursor: default;
        }
        .feat-card:hover {
            border-color: rgba(6,95,70,.22); transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(6,95,70,.1);
        }
        .feat-card::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 2.5px;
            background: linear-gradient(90deg, var(--g-500), var(--g-700));
            transform: scaleX(0); transform-origin: left; transition: transform .28s ease;
        }
        .feat-card:hover::after { transform: scaleX(1); }

        .feat-ico {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--g-50); border: 1px solid rgba(6,95,70,.14);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: var(--g-800); margin-bottom: 1.125rem;
        }
        .feat-t { font-size: .975rem; font-weight: 700; color: var(--navy); margin-bottom: .5rem; }
        .feat-d { font-size: .83rem; color: var(--slate-500); line-height: 1.65; }

        /* ─── CTA SECTION ─────────────────────────────── */
        .cta-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, var(--g-50) 0%, var(--white) 100%);
            border-top: 1px solid rgba(6,95,70,.1);
        }
        .cta-inner {
            max-width: 680px; margin: 0 auto; padding: 0 1.75rem; text-align: center;
        }
        .cta-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.75rem, 3vw, 2.25rem); color: var(--navy);
            line-height: 1.22; margin-bottom: 1rem;
        }
        .cta-body { font-size: .95rem; color: var(--slate-500); line-height: 1.7; margin-bottom: 2rem; }
        .cta-btns { display: flex; gap: .875rem; justify-content: center; flex-wrap: wrap; }

        /* ─── FOOTER ──────────────────────────────────── */
        .footer { background: var(--navy); padding: 2rem 0; }
        .footer-inner {
            max-width: 1240px; margin: 0 auto; padding: 0 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1.25rem;
        }
        .foot-brand { display: flex; align-items: center; gap: .65rem; }
        .foot-logo {
            width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,.08); overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .foot-logo img { width: 28px; height: 28px; object-fit: contain; }
        .foot-name { font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.88); }
        .foot-sub  { font-size: .67rem; color: rgba(255,255,255,.35); margin-top: .1rem; }
        .foot-links {
            display: flex; gap: 1.25rem;
        }
        .foot-link {
            font-size: .75rem; color: rgba(255,255,255,.35);
            text-decoration: none; transition: color .15s ease;
        }
        .foot-link:hover { color: var(--g-300); }
        .foot-copy { font-size: .73rem; color: rgba(255,255,255,.28); }

        /* ─── ANIMATIONS ──────────────────────────────── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fu {
            opacity: 0; transform: translateY(24px);
            transition: opacity .65s ease, transform .65s ease;
        }
        .fu.in { opacity: 1; transform: translateY(0); }

        /* ─── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 960px) {
            .hero-wrap { grid-template-columns: 1fr; gap: 3rem; }
            .hero-right { display: none; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            .feat-grid   { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .feat-grid   { grid-template-columns: 1fr; }
            .stats-inner { grid-template-columns: 1fr 1fr; }
            .trust-inner { gap: 1rem; }
            .hero-metrics { gap: 1.25rem; }
            .nav-menu .nav-link { display: none; }
        }
    </style>
</head>
<body>

<!-- ─── LOADER ──────────────────────────────────────────── -->
<div id="loader">
    <div class="ld-logo">
        <img src="image/Logo.png" alt="Logo MAS Nurul Huda" onerror="this.style.display='none'">
    </div>
    <div>
        <div class="ld-name">MAS Nurul Huda</div>
        <div class="ld-sub">Sistem Absensi RFID</div>
    </div>
    <div class="ld-bar"><div class="ld-fill"></div></div>
</div>

<!-- ─── HEADER ──────────────────────────────────────────── -->
<header class="hdr" id="hdr">
    <nav class="nav">
        <a href="#" class="brand">
            <div class="brand-badge">
                <img src="image/Logo.png" alt="Logo" onerror="this.parentElement.innerHTML='<i class=\'bi bi-mortarboard-fill\' style=\'color:var(--g-800);font-size:1.2rem\'></i>'">
            </div>
            <div class="brand-text">
                <span class="brand-name">MAS Nurul Huda</span>
                <span class="brand-sub">Mangunsari</span>
            </div>
        </a>
        <div class="nav-menu">
            <a href="#features" class="nav-link"><i class="bi bi-grid"></i> Fitur</a>
            <a href="#stats" class="nav-link"><i class="bi bi-bar-chart"></i> Statistik</a>
            <a href="/login" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
            </a>
        </div>
    </nav>
</header>

<!-- ─── HERO ─────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero-wrap">
        <!-- LEFT -->
        <div class="hero-left">
            <div class="pill">
                <span class="pill-dot"></span>
                Sistem Absensi Digital
            </div>

            <h1 class="hero-h1">
                Kelola Kehadiran<br>dengan <span class="accent">Presisi RFID</span>
            </h1>

            <p class="hero-body">
                Platform manajemen kehadiran modern untuk MAS Nurul Huda Mangunsari.
                Rekam, pantau, dan analisis data absensi siswa &amp; tenaga pendidik
                secara real-time dengan akurasi tinggi.
            </p>

            <div class="hero-btns">
                <a href="/login" class="btn-primary">
                    <i class="bi bi-shield-lock-fill"></i> Masuk ke Sistem
                </a>
                <a href="#features" class="btn-outline">
                    <i class="bi bi-play-circle"></i> Lihat Fitur
                </a>
            </div>

            <div class="hero-metrics">
                <div class="metric-item">
                    <div class="metric-val" id="m-siswa">—</div>
                    <div class="metric-lbl">Siswa Aktif</div>
                </div>
                <div class="metric-item">
                    <div class="metric-val" id="m-guru">—</div>
                    <div class="metric-lbl">Pendidik</div>
                </div>
                <div class="metric-item">
                    <div class="metric-val">99.9%</div>
                    <div class="metric-lbl">Akurasi</div>
                </div>
            </div>
        </div>

        <!-- RIGHT — DASHBOARD MOCKUP -->
        <div class="hero-right">
            <div class="card-glow glow-g"></div>
            <div class="card-glow glow-a"></div>

            <div class="dashboard-card">
                <!-- Header bar -->
                <div class="dc-header">
                    <div class="dc-header-left">
                        <div class="dc-logo">
                            <img src="image/Logo.png" alt="" onerror="this.parentElement.innerHTML='<i class=\'bi bi-mortarboard-fill\' style=\'color:white;font-size:.9rem\'></i>'">
                        </div>
                        <div>
                            <div class="dc-title">Dashboard Absensi</div>
                            <div class="dc-sub">Hari ini · Senin, 17 Juni 2026</div>
                        </div>
                    </div>
                    <div class="dc-clock" id="live-clock">--:--:--</div>
                </div>

                <!-- Tabs -->
                <div class="dc-tabs">
                    <div class="dc-tab active">Ringkasan</div>
                    <div class="dc-tab">Log Masuk</div>
                    <div class="dc-tab">Laporan</div>
                </div>

                <div class="dc-body">
                    <!-- Stat chips -->
                    <div class="stat-row">
                        <div class="stat-chip chip-g">
                            <span class="chip-val" id="c-hadir">0</span>
                            <div class="chip-lbl">Hadir</div>
                        </div>
                        <div class="stat-chip chip-a">
                            <span class="chip-val" id="c-izin">0</span>
                            <div class="chip-lbl">Izin</div>
                        </div>
                        <div class="stat-chip chip-r">
                            <span class="chip-val" id="c-alpha">0</span>
                            <div class="chip-lbl">Alpha</div>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="progress-row">
                        <div class="pr-label">
                            <span>Tingkat Kehadiran Hari Ini</span>
                            <span>87%</span>
                        </div>
                        <div class="pr-track">
                            <div class="pr-fill" id="pr-bar" style="width:0%"></div>
                        </div>
                    </div>

                    <!-- Scan zone -->
                    <div class="scan-box">
                        <div class="scan-icon"><i class="bi bi-wifi"></i></div>
                        <div class="scan-ttl">Tap Kartu RFID</div>
                        <div class="scan-hint">Menunggu pemindaian kartu...</div>
                    </div>

                    <!-- Recent log -->
                    <div class="log-scroll">
                        <div class="log-row">
                            <div class="av av-g">AH</div>
                            <div class="log-info">
                                <div class="log-n">Ahmad Hakim</div>
                                <div class="log-m">XII IPA 1 · 06:52 WIB</div>
                            </div>
                            <span class="badge b-hadir">Hadir</span>
                        </div>
                        <div class="log-row">
                            <div class="av av-g">SF</div>
                            <div class="log-info">
                                <div class="log-n">Siti Fatimah</div>
                                <div class="log-m">XI IPS 2 · 06:55 WIB</div>
                            </div>
                            <span class="badge b-hadir">Hadir</span>
                        </div>
                        <div class="log-row">
                            <div class="av av-a">MR</div>
                            <div class="log-info">
                                <div class="log-n">M. Rizky Pratama</div>
                                <div class="log-m">X IPA 3 · 06:59 WIB</div>
                            </div>
                            <span class="badge b-izin">Izin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── TRUST STRIP ───────────────────────────────────────── -->
<div class="trust">
    <div class="trust-inner">
        <div class="trust-chip"><i class="bi bi-shield-fill-check"></i> Data Terenkripsi</div>
        <div class="trust-chip"><i class="bi bi-cloud-check-fill"></i> Backup Otomatis</div>
        <div class="trust-chip"><i class="bi bi-clock-history"></i> Real-time Monitoring</div>
        <div class="trust-chip"><i class="bi bi-phone-fill"></i> Notifikasi WhatsApp</div>
        <div class="trust-chip"><i class="bi bi-printer-fill"></i> Laporan Siap Cetak</div>
    </div>
</div>

<!-- ─── STATS ─────────────────────────────────────────────── -->
<section class="stats" id="stats">
    <div class="stats-inner">
        <div class="stat-card fu">
            <div class="stat-num" id="n-siswa">0</div>
            <div class="stat-lbl">Total Siswa</div>
        </div>
        <div class="stat-card fu">
            <div class="stat-num" id="n-guru">0</div>
            <div class="stat-lbl">Tenaga Pendidik</div>
        </div>
        <div class="stat-card fu">
            <div class="stat-num">99.9<span class="stat-unit">%</span></div>
            <div class="stat-lbl">Akurasi Sistem</div>
        </div>
        <div class="stat-card fu">
            <div class="stat-num">24<span class="stat-unit">/7</span></div>
            <div class="stat-lbl">Operasional</div>
        </div>
    </div>
</section>

<!-- ─── FEATURES ─────────────────────────────────────────── -->
<section class="features" id="features">
    <div class="sec-wrap">
        <div class="sec-head fu">
            <div class="sec-eyebrow"><i class="bi bi-stars"></i> Keunggulan Platform</div>
            <h2 class="sec-title">Fitur yang Dirancang<br>untuk Efisiensi Nyata</h2>
            <p class="sec-body">Teknologi modern yang memudahkan administrasi kehadiran di lingkungan madrasah dengan akurasi dan transparansi tinggi.</p>
        </div>

        <div class="feat-grid">
            <div class="feat-card fu">
                <div class="feat-ico"><i class="bi bi-wifi"></i></div>
                <h3 class="feat-t">Teknologi RFID</h3>
                <p class="feat-d">Absensi contactless dengan kartu RFID — proses dalam hitungan detik tanpa antrian panjang di pintu masuk.</p>
            </div>
            <div class="feat-card fu">
                <div class="feat-ico"><i class="bi bi-speedometer2"></i></div>
                <h3 class="feat-t">Dashboard Real-time</h3>
                <p class="feat-d">Pantau kehadiran siswa dan guru secara langsung dengan visualisasi data interaktif yang mudah dibaca siapa saja.</p>
            </div>
            <div class="feat-card fu">
                <div class="feat-ico"><i class="bi bi-bell-fill"></i></div>
                <h3 class="feat-t">Notifikasi Otomatis</h3>
                <p class="feat-d">Orang tua menerima pemberitahuan via WhatsApp saat siswa tidak hadir atau terlambat — tanpa perlu telepon.</p>
            </div>
            <div class="feat-card fu">
                <div class="feat-ico"><i class="bi bi-file-earmark-bar-graph"></i></div>
                <h3 class="feat-t">Laporan Lengkap</h3>
                <p class="feat-d">Rekap harian, mingguan, dan bulanan dalam format PDF atau Excel, siap cetak dengan satu klik.</p>
            </div>
            <div class="feat-card fu">
                <div class="feat-ico"><i class="bi bi-shield-lock"></i></div>
                <h3 class="feat-t">Keamanan Data</h3>
                <p class="feat-d">Enkripsi tingkat enterprise dengan backup otomatis setiap hari — data absensi aman dan tidak pernah hilang.</p>
            </div>
            <div class="feat-card fu">
                <div class="feat-ico"><i class="bi bi-layout-text-window-reverse"></i></div>
                <h3 class="feat-t">Akses Multiplatform</h3>
                <p class="feat-d">Bisa diakses dari komputer, tablet, maupun smartphone kapan saja — tanpa perlu install aplikasi apapun.</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── CTA ───────────────────────────────────────────────── -->
<section class="cta-section">
    <div class="cta-inner fu">
        <h2 class="cta-title">Siap Memulai Absensi<br>yang Lebih Efisien?</h2>
        <p class="cta-body">Masuk ke sistem dan nikmati kemudahan pengelolaan kehadiran seluruh warga madrasah dalam satu platform.</p>
        <div class="cta-btns">
            <a href="/login" class="btn-primary">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
            </a>
            <a href="#features" class="btn-outline">
                <i class="bi bi-question-circle"></i> Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
</section>

<!-- ─── FOOTER ─────────────────────────────────────────────── -->
<footer class="footer">
    <div class="footer-inner">
        <div class="foot-brand">
            <div class="foot-logo">
                <img src="image/Logo.png" alt="" onerror="this.parentElement.innerHTML='<i class=\'bi bi-mortarboard-fill\' style=\'color:var(--g-300);font-size:.9rem\'></i>'">
            </div>
            <div>
                <div class="foot-name">MAS Nurul Huda Mangunsari</div>
                <div class="foot-sub">Sistem Absensi RFID</div>
            </div>
        </div>
        <div class="foot-links">
            <a href="#features" class="foot-link">Fitur</a>
            <a href="#stats" class="foot-link">Statistik</a>
            <a href="/login" class="foot-link">Login</a>
        </div>
        <div class="foot-copy">
            &copy; <span id="yr"></span> MAS Nurul Huda. Hak cipta dilindungi.
        </div>
    </div>
</footer>

<script>
    /* LOADER */
    window.addEventListener('load', () => {
        setTimeout(() => document.getElementById('loader').classList.add('out'), 1100);
    });

    /* YEAR */
    document.getElementById('yr').textContent = new Date().getFullYear();

    /* STICKY HEADER */
    const hdr = document.getElementById('hdr');
    window.addEventListener('scroll', () => {
        hdr.classList.toggle('stuck', window.scrollY > 40);
    }, { passive: true });

    /* LIVE CLOCK */
    const clockEl = document.getElementById('live-clock');
    function tick() {
        const n = new Date(), p = v => String(v).padStart(2, '0');
        if (clockEl) clockEl.textContent = `${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`;
    }
    tick(); setInterval(tick, 1000);

    /* COUNT UP */
    function countUp(el, target, dur = 1800) {
        if (!el) return;
        let v = 0; const step = target / (dur / 16);
        const run = () => { v = Math.min(v + step, target); el.textContent = Math.round(v); if (v < target) requestAnimationFrame(run); };
        requestAnimationFrame(run);
    }

    /* STATS SECTION COUNT UP */
    let statsDone = false;
    new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && !statsDone) {
            statsDone = true;
            countUp(document.getElementById('n-siswa'), 850);
            countUp(document.getElementById('n-guru'), 45);
        }
    }, { threshold: 0.3 }).observe(document.querySelector('.stats'));

    /* HERO METRICS */
    setTimeout(() => {
        countUp(document.getElementById('m-siswa'), 850, 1400);
        countUp(document.getElementById('m-guru'), 45, 1400);
    }, 1300);

    /* DASHBOARD CARD NUMBERS + PROGRESS BAR */
    let cardDone = false;
    setTimeout(() => {
        if (cardDone) return; cardDone = true;
        let h = 0, i = 0, a = 0;
        const cH = document.getElementById('c-hadir');
        const cI = document.getElementById('c-izin');
        const cA = document.getElementById('c-alpha');
        const bar = document.getElementById('pr-bar');
        if (bar) bar.style.width = '87%';
        const t = setInterval(() => {
            h = Math.min(h + 5, 312); if (cH) cH.textContent = h;
            i = Math.min(i + 1, 28);  if (cI) cI.textContent = i;
            a = Math.min(a + 1, 12);  if (cA) cA.textContent = a;
            if (h >= 312 && i >= 28 && a >= 12) clearInterval(t);
        }, 24);
    }, 1600);

    /* FADE UP on scroll */
    const fuObs = new IntersectionObserver((entries) => {
        entries.forEach((e, idx) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('in'), idx * 80);
                fuObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
    document.querySelectorAll('.fu').forEach(el => fuObs.observe(el));

    /* SMOOTH SCROLL */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const t = document.querySelector(a.getAttribute('href'));
            if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

    /* DASHBOARD TABS (decorative) */
    document.querySelectorAll('.dc-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.dc-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });
</script>
</body>
</html> 