@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #16a34a;
        --brand-primary-light: #dcfce7;
        --brand-primary-dark:  #15803d;
        --brand-success:       #16a34a;
        --brand-success-light: #dcfce7;
        --brand-warning:       #f59f00;
        --brand-warning-light: #fff9db;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-info:          #0891b2;
        --brand-info-light:    #e0f2fe;
        --surface:             #ffffff;
        --surface-soft:        #f8faf9;
        --surface-border:      #e4ede7;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --shadow-lg:  0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  14px;
        --radius-xl:  20px;
    }

    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #14532d 0%, #16a34a 55%, #4ade80 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: 0 8px 32px rgba(22,163,74,.30);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute; right: -50px; top: -50px;
        width: 210px; height: 210px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute; right: 80px; bottom: -70px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .page-hero-left h1 {
        font-size: 1.45rem; font-weight: 700;
        color: #fff; margin: 0 0 4px;
    }
    .page-hero-left .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero-left .breadcrumb-item a,
    .page-hero-left .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero-left .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }
    .page-hero-actions { display: flex; gap: 10px; z-index: 1; flex-wrap: wrap; }

    /* ── Hero Buttons ─────────────────────────────── */
    .btn-hero {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600; font-size: .8rem;
        border-radius: 50px; padding: 9px 20px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s ease; cursor: pointer;
        text-decoration: none;
    }
    .btn-hero-white {
        background: rgba(255,255,255,.2); color: #fff;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.3);
    }
    .btn-hero-white:hover {
        background: rgba(255,255,255,.3); color: #fff;
        transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }
    .btn-hero-solid { background: #fff; color: var(--brand-primary); }
    .btn-hero-solid:hover {
        background: #f0fdf4; color: var(--brand-primary-dark);
        transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    /* ── Alert ────────────────────────────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 14px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: var(--shadow-sm); margin-bottom: 16px;
    }
    .alert-pro-success { background: var(--brand-success-light); color: #15803d; }
    .alert-pro .btn-close { margin-left: auto; }

    /* ── Banner dari Laporan RFID ─────────────────── */
    .banner-from-laporan {
        border: none;
        border-radius: var(--radius-md);
        padding: 14px 20px;
        font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 12px;
        box-shadow: var(--shadow-sm); margin-bottom: 16px;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe4e4 100%);
        border-left: 4px solid #e03131;
        color: #c92a2a;
    }
    .banner-from-laporan i { font-size: 1.2rem; flex-shrink: 0; }
    .banner-from-laporan .banner-title { font-weight: 700; margin-bottom: 2px; }
    .banner-from-laporan .banner-desc  { font-size: .8rem; opacity: .8; }
    .banner-from-laporan .btn-close    { margin-left: auto; filter: invert(30%) sepia(80%) saturate(700%) hue-rotate(320deg); }

    /* ── Data Card ────────────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 18px 24px 14px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .data-card-header-left { display: flex; align-items: center; gap: 12px; }
    .header-icon {
        width: 42px; height: 42px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1.1rem;
    }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    /* ── Search & filter bar ──────────────────────── */
    .search-wrap {
        padding: 14px 24px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface);
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
        flex-wrap: wrap;
    }
    .filter-inner select {
        padding: 8px 34px 8px 14px;
        border: 1.5px solid var(--surface-border);
        border-radius: 50px;
        font-size: .8rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-primary);
        background: var(--surface-soft);
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-inner select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        outline: none;
    }
    .search-inner {
        position: relative; width: 280px;
    }
    .search-inner i {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); font-size: .9rem; pointer-events: none;
    }
    .search-inner input {
        padding: 8px 14px 8px 36px;
        border: 1.5px solid var(--surface-border);
        border-radius: 50px;
        font-size: .84rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-primary);
        width: 100%;
        transition: border-color .2s, box-shadow .2s;
    }
    .search-inner input:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        outline: none;
    }

    /* ── Table ────────────────────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead th {
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        padding: 13px 18px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody tr { transition: background .15s; }
    .table-pro tbody tr:hover { background: #f0fdf4; }
    .table-pro tbody td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .855rem;
        color: var(--text-primary);
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    /* ── Highlight row dari laporan ─────────────── */
    @keyframes row-highlight-pulse {
        0%   { background: #ffe4e4; box-shadow: inset 0 0 0 2px #e03131; }
        50%  { background: #fff5f5; box-shadow: inset 0 0 0 2px rgba(224,49,49,.4); }
        100% { background: #ffe4e4; box-shadow: inset 0 0 0 2px #e03131; }
    }
    .row-highlight {
        animation: row-highlight-pulse 1.2s ease 3;
    }
    .row-highlight td { position: relative; }

    /* row number */
    .row-num {
        font-size: .72rem; font-weight: 700;
        color: var(--text-muted);
        width: 32px; height: 32px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    /* nisn chip */
    .nisn-chip {
        font-family: monospace;
        font-size: .78rem; font-weight: 600;
        background: var(--surface-soft);
        color: var(--text-secondary);
        padding: 4px 10px; border-radius: 50px;
        border: 1px solid var(--surface-border);
        letter-spacing: .03em;
    }

    /* student name link */
    .student-link {
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; color: var(--text-primary); font-weight: 600;
        transition: color .2s;
    }
    .student-link .avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--brand-primary-light);
        color: var(--brand-primary);
        font-size: .8rem; font-weight: 700;
        display: grid; place-items: center; flex-shrink: 0;
        transition: background .2s;
    }
    .student-link:hover { color: var(--brand-primary); }
    .student-link:hover .avatar { background: var(--brand-primary); color: #fff; }

    /* badges */
    .badge-kelas {
        background: var(--brand-info-light); color: var(--brand-info);
        font-size: .72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 50px;
        display: inline-block;
    }
    .badge-angkatan {
        background: #f3f0ff; color: #6741d9;
        font-size: .72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 50px;
        display: inline-block;
    }

    /* ── Animated status icons (no text) ─────────── */
    .status-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px;
    }

    /* Animated checkmark SVG */
    .check-svg { width: 28px; height: 28px; }
    .check-circle {
        fill: none;
        stroke: #16a34a;
        stroke-width: 2.5;
        stroke-dasharray: 66;
        stroke-dashoffset: 66;
        animation: draw-circle .5s ease forwards;
        transform-origin: center;
    }
    .check-tick {
        fill: none;
        stroke: #16a34a;
        stroke-width: 2.8;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 20;
        stroke-dashoffset: 20;
        animation: draw-tick .35s ease .45s forwards;
    }
    @keyframes draw-circle { to { stroke-dashoffset: 0; } }
    @keyframes draw-tick   { to { stroke-dashoffset: 0; } }

    /* Pending clock pulse */
    .pending-svg { width: 26px; height: 26px; }
    .pending-circle {
        fill: none;
        stroke: #d97706;
        stroke-width: 2.5;
        animation: pulse-clock 2s ease-in-out infinite;
    }
    .pending-hands {
        stroke: #d97706;
        stroke-width: 2.2;
        stroke-linecap: round;
    }
    @keyframes pulse-clock {
        0%, 100% { opacity: 1; }
        50%       { opacity: .45; }
    }

    .rfid-code {
        font-family: monospace; font-size: .78rem;
        color: var(--text-secondary);
        background: var(--surface-soft);
        padding: 3px 8px; border-radius: var(--radius-sm);
        border: 1px solid var(--surface-border);
    }

    /* action buttons */
    .action-wrap { display: flex; gap: 6px; align-items: center; justify-content: center; }
    .btn-act {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        border: 1.5px solid; background: transparent;
        display: grid; place-items: center;
        font-size: .82rem; cursor: pointer;
        transition: all .2s;
    }
    .btn-act-edit {
        border-color: #ffd43b; color: #c07f00;
        background: var(--brand-warning-light);
    }
    .btn-act-edit:hover { background: var(--brand-warning); border-color: var(--brand-warning); color: #fff; }
    .btn-act-delete {
        border-color: #ffa8a8; color: var(--brand-danger);
        background: var(--brand-danger-light);
    }
    .btn-act-delete:hover { background: var(--brand-danger); border-color: var(--brand-danger); color: #fff; }

    /* empty state */
    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3.2rem; color: var(--text-muted); margin-bottom: 12px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* ── Modals ───────────────────────────────────── */
    .modal-pro .modal-content {
        border: none; border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg); overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .modal-pro .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .modal-pro .modal-title {
        font-size: .95rem; font-weight: 700;
        color: var(--text-primary);
        display: flex; align-items: center; gap: 10px;
    }
    .mti {
        width: 34px; height: 34px; border-radius: var(--radius-sm);
        display: grid; place-items: center; font-size: .9rem;
    }
    .mti-primary { background: var(--brand-primary-light); color: var(--brand-primary); }
    .mti-warning { background: var(--brand-warning-light); color: var(--brand-warning); }
    .mti-info    { background: var(--brand-info-light);    color: var(--brand-info); }
    .mti-success { background: var(--brand-success-light); color: var(--brand-success); }

    .modal-pro .modal-body   { padding: 22px 24px; }
    .modal-pro .modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    /* form labels in modals */
    .flabel {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
        color: var(--text-secondary); margin-bottom: 6px; display: block;
    }
    .flabel .req { color: var(--brand-danger); }

    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .855rem;
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        outline: none;
    }
    .input-group .input-group-text {
        border: 1.5px solid var(--surface-border);
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .9rem;
    }
    .input-group .form-control,
    .input-group .form-select { border-left: none; }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control,
    .input-group:focus-within .form-select {
        border-color: var(--brand-primary);
    }

    /* import info box */
    .import-info {
        background: var(--brand-primary-light);
        border-left: 4px solid var(--brand-primary);
        border-radius: var(--radius-md);
        padding: 14px 16px;
    }
    .import-info h6 { font-size: .78rem; font-weight: 700; color: var(--brand-primary); margin: 0 0 8px; }
    .import-info p  { font-size: .82rem; color: #15803d; margin: 0; }
    .import-info small { font-size: .75rem; opacity: .75; }

    /* rfid info box */
    .rfid-info {
        background: #f8faf9;
        border: 1.5px dashed var(--surface-border);
        border-radius: var(--radius-md);
        padding: 12px 14px;
        display: flex; align-items: center; gap: 10px;
        font-size: .84rem; color: var(--text-secondary);
    }
    .rfid-info i { font-size: 1.1rem; color: var(--brand-primary); }
    .rfid-info strong { color: var(--text-primary); }

    /* modal buttons */
    .btn-modal {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .84rem; font-weight: 600;
        padding: 9px 20px; border-radius: 50px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s; cursor: pointer;
    }
    .btn-mc { background: var(--surface-border); color: var(--text-secondary); }
    .btn-mc:hover { background: #dee2e6; color: var(--text-primary); }
    .btn-mp { background: var(--brand-primary); color: #fff; }
    .btn-mp:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 12px rgba(22,163,74,.3); }
    .btn-ms { background: var(--brand-success); color: #fff; }
    .btn-ms:hover { background: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,.25); }
    .btn-mw { background: var(--brand-warning); color: #fff; }
    .btn-mw:hover { background: #e08e00; box-shadow: 0 4px 12px rgba(245,159,0,.3); }
    .btn-mi { background: var(--brand-info); color: #fff; }
    .btn-mi:hover { background: #0e7490; box-shadow: 0 4px 12px rgba(8,145,178,.3); }

    /* ── RFID inline flash ─────────────────────── */
    @keyframes rfid-flash {
        0%   { background: #bbf7d0; }
        100% { background: transparent; }
    }
    .rfid-updated {
        animation: rfid-flash .8s ease forwards;
        border-radius: 4px;
    }

    /* ── Tombol kembali ke laporan (banner) ──────── */
    .btn-back-laporan {
        flex-shrink: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .78rem; font-weight: 700;
        padding: 8px 16px; border-radius: 50px;
        background: #e03131; color: #fff;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s; white-space: nowrap;
    }
    .btn-back-laporan:hover {
        background: #c92a2a; color: #fff;
        transform: translateY(-1px); box-shadow: 0 4px 10px rgba(224,49,49,.3);
    }
    .banner-from-laporan .banner-text { flex: 1; }

    .filter-inner {
        display: flex;
        align-items: center;
        gap: 10px; /* Jarak antara dropdown dan tombol */
    }
    .btn-filter-action {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .78rem; font-weight: 700;
        padding: 8px 16px; border-radius: 50px;
        background: var(--brand-warning-light);
        color: #b7791f;
        border: 1px solid #f6e05e;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s;
        white-space: nowrap;
    }
    .btn-filter-action:hover {
        background: var(--brand-warning);
        border-color: var(--brand-warning);
        color: #fff;
        transform: translateY(-1px);
    }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-mortarboard me-2" style="opacity:.9"></i>Data Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                @if(request('from_laporan'))
                    <li class="breadcrumb-item"><a href="{{ route('rfid.laporan-hilang') }}" style="color:rgba(255,255,255,.75)">Laporan RFID</a></li>
                @else
                    <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Master Data</li>
                @endif
                <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
        </nav>
    </div>
   <div class="page-hero-actions">
        <a href="{{ route('siswa.non_aktif') }}" class="btn-hero btn-hero-white">
            <i class="bi bi-mortarboard-fill"></i> Data non_aktif
        </a>
        <button type="button" class="btn-hero btn-hero-white" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
            <i class="bi bi-upload"></i> Import Excel
        </button>
        <button type="button" class="btn-hero btn-hero-solid" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    </div>
</div>

<section class="section"
    id="siswaSection"
    data-from-laporan="{{ request('from_laporan') ? '1' : '0' }}"
    data-laporan-url="{{ route('rfid.laporan-hilang') }}">
    <div class="row">
        <div class="col-lg-12">

            {{-- ── Banner: datang dari halaman laporan RFID ─── --}}
            @if(request('from_laporan') && request('highlight_id'))
                @php $targetSiswa = $siswas->firstWhere('id', request('highlight_id')); @endphp
                @if($targetSiswa)
                <div class="banner-from-laporan alert-dismissible fade show" role="alert">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                    <div class="banner-text">
                        <div class="banner-title">
                            Anda sedang memperbaiki RFID dari Laporan RFID Hilang
                        </div>
                        <div class="banner-desc">
                            Kartu RFID milik <strong>{{ $targetSiswa->nama }}</strong> dilaporkan hilang/bermasalah.
                            Silakan scan RFID baru pada baris yang ditandai, lalu klik tombol di samping untuk kembali ke laporan.
                        </div>
                    </div>
                    <a href="{{ route('rfid.laporan-hilang') }}" class="btn-back-laporan">
                        <i class="bi bi-arrow-left"></i> Kembali ke Laporan
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
            @endif

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-pro" style="background:var(--brand-danger-light);color:#c92a2a;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="data-card">

                <!-- Header -->
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-people"></i></div>
                        <div>
                            <p class="data-card-title">Management Data Siswa</p>
                            <p class="data-card-subtitle">{{ count($siswas) }} siswa aktif &amp; pending terdaftar</p>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter -->
               <div class="search-wrap">
                    <div class="filter-inner">
                        <select id="angkatanFilter">
                            <option value="">Semua Angkatan</option>
                            @foreach($daftarAngkatan as $angkatan)
                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                            @endforeach
                        </select>
                        
                        <a href="{{ route('siswa.luluskan.form') }}" class="btn-filter-action" title="Proses Alih Status Siswa ke non_aktif">
                            <i class=""></i> Nonaktifkan Data
                        </a>
                    </div>
                    
                    <div class="search-inner">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari NISN atau Nama Siswa…">
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table-pro" id="siswaTable">
                        <thead>
                            <tr>
                                <th style="width:4%">#</th>
                                <th style="width:11%">NISN</th>
                                <th style="width:19%">Nama Siswa</th>
                                <th style="width:9%">Kelas</th>
                                <th style="width:9%">Angkatan</th>
                                <th style="width:13%">Orang Tua</th>
                                <th style="width:13%">RFID</th>
                                <th style="width:9%;text-align:center">Status</th>
                                <th style="width:13%;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr id="siswa-row-{{ $siswa->id }}" data-angkatan="{{ $siswa->angkatan }}">
                                <td><div class="row-num">{{ $index + 1 }}</div></td>
                                <td><span class="nisn-chip">{{ $siswa->nisn }}</span></td>
                                <td>
                                    <a href="javascript:void(0)"
                                       class="student-link"
                                       data-bs-toggle="modal"
                                       data-bs-target="#scanRfidModal"
                                       data-siswa-id="{{ $siswa->id }}"
                                       data-siswa-nama="{{ $siswa->nama }}"
                                       title="Klik untuk scan RFID">
                                        <div class="avatar">{{ strtoupper(substr($siswa->nama,0,1)) }}</div>
                                        {{ $siswa->nama }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-kelas">{{ $siswa->kelas->nama ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge-angkatan">{{ $siswa->angkatan }}</span>
                                </td>
                                <td style="font-size:.82rem;color:var(--text-secondary)">
                                    {{ $siswa->orangTua->nama ?? '-' }}
                                </td>
                                <td id="rfid-{{ $siswa->id }}">
                                    @if($siswa->rfid)
                                        <span class="rfid-code">{{ $siswa->rfid }}</span>
                                    @else
                                        <span style="color:var(--text-muted);font-size:.8rem">—</span>
                                    @endif
                                </td>
                                <td class="status-cell" style="text-align:center">
                                    @if($siswa->status === 'aktif')
                                        <span class="status-icon" title="Aktif">
                                            <svg class="check-svg" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="check-circle" cx="14" cy="14" r="10.5"/>
                                                <polyline class="check-tick" points="9,14 12.5,17.5 19,11"/>
                                            </svg>
                                        </span>
                                    @else
                                        <span class="status-icon" title="Pending">
                                            <svg class="pending-svg" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle class="pending-circle" cx="13" cy="13" r="10"/>
                                                <line class="pending-hands" x1="13" y1="8" x2="13" y2="13.5"/>
                                                <line class="pending-hands" x1="13" y1="13.5" x2="16.5" y2="16"/>
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-wrap">
                                        <button type="button"
                                                class="btn-act btn-act-edit editSiswaBtn"
                                                data-id="{{ $siswa->id }}"
                                                data-nisn="{{ $siswa->nisn }}"
                                                data-nama="{{ $siswa->nama }}"
                                                data-kelas="{{ $siswa->kelas_id }}"
                                                data-angkatan="{{ $siswa->angkatan }}"
                                                data-orang-tua-id="{{ $siswa->orang_tua_id }}"
                                                data-rfid="{{ $siswa->rfid ?? '' }}"
                                                data-status="{{ $siswa->status }}"
                                                data-update-url="{{ route('siswa.update', $siswa->id) }}"
                                                title="Edit Siswa">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('siswa.destroy', $siswa->id) }}"
                                              method="POST" class="d-inline deleteSiswaForm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn-act btn-act-delete deleteSiswaBtn"
                                                    title="Hapus Siswa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                        <h6>Belum ada data siswa</h6>
                                        <small>Silakan tambah siswa baru atau import dari Excel</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- ══════════════ MODAL: IMPORT ══════════════ --}}
<div class="modal fade modal-pro" id="importSiswaModal" tabindex="-1" aria-labelledby="importSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importSiswaLabel">
                    <span class="mti mti-warning"><i class="bi bi-upload"></i></span>
                    Import Siswa dari Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="flabel">Pilih File Excel</label>
                        <input type="file" name="file" id="file" class="form-control"
                               accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted d-block mt-1">Format didukung: .xlsx, .xls, .csv</small>
                    </div>
                    <div class="import-info">
                        <h6><i class="bi bi-info-circle me-1"></i>Format Kolom yang Diperlukan</h6>
                        <p>
                            Kolom wajib: <strong>nisn</strong>, <strong>nama</strong>, <strong>kelas</strong>, <strong>angkatan</strong><br>
                            <small>Kolom opsional: rfid, orang_tua, status (default: pending)</small>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mw">
                        <i class="bi bi-upload"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════ MODAL: SCAN RFID ══════════════ --}}
<div class="modal fade modal-pro" id="scanRfidModal" tabindex="-1" aria-labelledby="scanRfidLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="scanRfidForm" method="POST" action="{{ route('siswa.scan') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="scanRfidLabel">
                        <span class="mti mti-info"><i class="bi bi-credit-card"></i></span>
                        Scan RFID Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="siswa_id" id="siswa_id">

                    <div class="rfid-info mb-3">
                        <i class="bi bi-person-circle"></i>
                        <div>
                            <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;font-weight:700;">Siswa</div>
                            <strong class="nama-siswa" style="font-size:.9rem;color:var(--text-primary)"></strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rfid" class="flabel">Masukkan / Scan RFID</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                            <input type="text" class="form-control" id="rfid" name="rfid"
                                   required autocomplete="off" placeholder="Tempelkan kartu atau ketik RFID…">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mp" id="simpanRfidBtn">
                        <i class="bi bi-check-lg"></i> Simpan RFID
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════ MODAL: TAMBAH SISWA ══════════════ --}}
<div class="modal fade modal-pro" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSiswaLabel">
                    <span class="mti mti-primary"><i class="bi bi-person-plus"></i></span>
                    Tambah Siswa Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSiswaForm" action="{{ route('siswa.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert-pro" style="background:#fff5f5;color:#c92a2a;margin-bottom:16px;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <div style="font-weight:700;margin-bottom:4px;">Terjadi Kesalahan</div>
                                <ul style="margin:0;padding-left:16px;font-size:.82rem;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="nisn" class="flabel">NISN <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-card-list"></i></span>
                            <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                   class="form-control" required placeholder="Masukkan NISN">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="flabel">Nama Lengkap <span class="req">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                   class="form-control" required placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-7">
                            <label for="kelas_id" class="flabel">Kelas <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house"></i></span>
                                <select name="kelas_id" id="kelas_id" class="form-select" required>
                                    <option value="">— Pilih Kelas —</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <label for="angkatan" class="flabel">Angkatan <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="number" name="angkatan" id="angkatan"
                                       value="{{ old('angkatan', date('Y')) }}"
                                       class="form-control" required min="2000" max="2100"
                                       placeholder="cth. 2026">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="orang_tua_id" class="flabel">Orang Tua <span style="font-weight:400;text-transform:none;font-size:.72rem;">(Opsional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                            <select name="orang_tua_id" id="orang_tua_id" class="form-select">
                                <option value="">— Pilih Orang Tua —</option>
                                @foreach($orangTuas as $ortu)
                                    <option value="{{ $ortu->id }}" {{ old('orang_tua_id') == $ortu->id ? 'selected' : '' }}>
                                        {{ $ortu->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mp">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════ MODAL: EDIT SISWA ══════════════ --}}
<div class="modal fade modal-pro" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSiswaLabel">
                    <span class="mti mti-warning"><i class="bi bi-pencil-square"></i></span>
                    Edit Data Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSiswaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_siswa_id" name="siswa_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="edit_nisn" class="flabel">NISN <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-list"></i></span>
                                <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="edit_nama" class="flabel">Nama Lengkap <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-7">
                            <label for="edit_kelas_id" class="flabel">Kelas <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house"></i></span>
                                <select name="kelas_id" id="edit_kelas_id" class="form-select" required>
                                    <option value="">— Pilih Kelas —</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <label for="edit_angkatan" class="flabel">Angkatan <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="number" name="angkatan" id="edit_angkatan" class="form-control" required min="2000" max="2100">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="edit_orang_tua_id" class="flabel">Orang Tua <span style="font-weight:400;text-transform:none;font-size:.72rem;">(Opsional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <select name="orang_tua_id" id="edit_orang_tua_id" class="form-select">
                                    <option value="">— Pilih Orang Tua —</option>
                                    @foreach($orangTuas as $ortu)
                                        <option value="{{ $ortu->id }}">{{ $ortu->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="edit_rfid" class="flabel">RFID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                <input type="text" name="rfid" id="edit_rfid" class="form-control"
                                       placeholder="Kosongkan jika tidak ada RFID">
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="edit_status" class="flabel">Status <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Status otomatis mengikuti ada/tidaknya RFID, kecuali diatur manual di sini.
                                Untuk meluluskan siswa, gunakan tombol <strong>Luluskan Angkatan</strong>.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mi">
                        <i class="bi bi-check-lg"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/siswa.js') }}"></script>
    <script>
        /* ── Search + Filter Angkatan (gabungan) ─────────────── */
        function applyTableFilters() {
            const keyword   = (document.getElementById("searchInput").value || "").toLowerCase();
            const angkatan  = document.getElementById("angkatanFilter").value;
            const rows      = document.querySelectorAll("#siswaTable tbody tr[id^='siswa-row-']");

            rows.forEach(row => {
                const nisn        = row.cells[1]?.textContent.toLowerCase() || "";
                const nama        = row.cells[2]?.textContent.toLowerCase() || "";
                const rowAngkatan = row.getAttribute('data-angkatan') || "";

                const matchKeyword  = !keyword || nisn.includes(keyword) || nama.includes(keyword);
                const matchAngkatan = !angkatan || rowAngkatan === angkatan;

                row.style.display = (matchKeyword && matchAngkatan) ? "" : "none";
            });
        }

        document.getElementById("searchInput").addEventListener("keyup", applyTableFilters);
        document.getElementById("angkatanFilter").addEventListener("change", applyTableFilters);

        /* Catatan: submit form #scanRfidForm ditangani di public/js/siswa.js
           (fungsi doScanRfid), termasuk SweetAlert "Kembali ke Laporan RFID"
           jika halaman dibuka dari Laporan RFID Hilang. Jangan tambahkan
           handler submit lain di sini agar tidak terjadi double-submit. */

        /* ════════════════════════════════════════════════════════════
           AUTO-HIGHLIGHT & BUKA MODAL SCAN RFID
           Jika URL punya ?highlight_id=xxx, scroll ke baris siswa tsb
           lalu langsung buka modal Scan RFID.
        ════════════════════════════════════════════════════════════ */
        (function () {
            const params      = new URLSearchParams(window.location.search);
            const highlightId = params.get('highlight_id');
            if (!highlightId) return;

            const targetRow = document.getElementById('siswa-row-' + highlightId);
            if (!targetRow) return;

            // ── Scroll & highlight baris ──
            setTimeout(function () {
                targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetRow.classList.add('row-highlight');
                // Hapus class setelah animasi selesai (3 × 1.2 s = 3.6 s)
                setTimeout(() => targetRow.classList.remove('row-highlight'), 3700);

                // ── Cari link student di baris ini dan trigger modal scan ──
                const studentLink = targetRow.querySelector('.student-link[data-siswa-id]');
                if (studentLink) {
                    // Tunda sedikit agar scroll selesai dulu
                    setTimeout(function () {
                        // Isi modal secara manual (sama seperti yang dilakukan siswa.js)
                        const siswaId   = studentLink.getAttribute('data-siswa-id');
                        const siswaNama = studentLink.getAttribute('data-siswa-nama');

                        document.getElementById('siswa_id').value = siswaId;
                        document.querySelector('.nama-siswa').textContent = siswaNama || '';
                        document.getElementById('rfid').value = '';

                        const modal = new bootstrap.Modal(document.getElementById('scanRfidModal'));
                        modal.show();
                    }, 600);
                }
            }, 300);
        })();
    </script>
@endpush