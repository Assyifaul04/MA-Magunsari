@extends('layouts.guru')
@section('title', 'Dashboard Wali Kelas')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --brand-primary:        #2f9e44;
    --brand-primary-dark:   #237032;
    --brand-primary-light:  #ebfbee;
    --brand-success:        #0ca678;
    --brand-success-light:  #e6fcf5;
    --brand-warning:        #f59f00;
    --brand-warning-light:  #fff9db;
    --brand-danger:         #e03131;
    --brand-danger-light:   #fff5f5;
    --brand-info:           #1098ad;
    --brand-info-light:     #e3fafc;
    --brand-purple:         #7048e8;
    --brand-purple-light:   #f3f0ff;
    --surface:              #ffffff;
    --surface-soft:         #f8f9fc;
    --surface-border:       #e9ecef;
    --text-primary:         #1a1d23;
    --text-secondary:       #495057;
    --text-muted:           #868e96;
    --shadow-xs: 0 1px 3px rgba(0,0,0,.06);
    --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
    --shadow-md: 0 4px 16px rgba(0,0,0,.09), 0 1px 4px rgba(0,0,0,.05);
    --shadow-lg: 0 12px 32px rgba(0,0,0,.11), 0 4px 8px rgba(0,0,0,.06);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --transition: .2s ease;
}
*, *::before, *::after { box-sizing: border-box; }
body, .section, .card, .modal-content { font-family: 'Plus Jakarta Sans', sans-serif; }

/* PAGE HERO */
.page-hero {
    background: linear-gradient(135deg, #1a6b2a 0%, var(--brand-primary) 55%, #52c46a 100%);
    border-radius: var(--radius-xl);
    padding: 28px 32px;
    margin-bottom: 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    box-shadow: 0 8px 32px rgba(47,158,68,.28);
    position: relative; overflow: hidden;
}
.page-hero::before {
    content: ''; position: absolute; right: -60px; top: -60px;
    width: 220px; height: 220px; background: rgba(255,255,255,.06); border-radius: 50%; pointer-events: none;
}
.page-hero::after {
    content: ''; position: absolute; right: 80px; bottom: -70px;
    width: 150px; height: 150px; background: rgba(255,255,255,.04); border-radius: 50%; pointer-events: none;
}
.page-hero h1 { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0 0 4px; letter-spacing: -.01em; }
.page-hero .hero-nip { color: rgba(255,255,255,.75); font-size: .82rem; font-weight: 500; margin: 0 0 6px; }
.page-hero .breadcrumb { margin: 0; background: transparent; padding: 0; font-size: .78rem; }
.page-hero .breadcrumb-item a,
.page-hero .breadcrumb-item.active { color: rgba(255,255,255,.7); text-decoration: none; }
.page-hero .breadcrumb-item a:hover { color: #fff; }
.page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.35); }
.hero-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; position: relative; z-index: 1; }
.hero-time-pill {
    background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
    border-radius: 50px; padding: 9px 18px; color: #fff;
    font-size: .82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;
    backdrop-filter: blur(8px);
}
.hero-status-pill {
    background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
    border-radius: 50px; padding: 9px 16px; color: #fff;
    font-size: .8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 7px;
    backdrop-filter: blur(8px);
}
.hero-status-pill .status-dot {
    width: 7px; height: 7px; border-radius: 50%; background: #51cf66;
    box-shadow: 0 0 0 2px rgba(81,207,102,.35); animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%,100% { box-shadow: 0 0 0 2px rgba(81,207,102,.35); }
    50%      { box-shadow: 0 0 0 5px rgba(81,207,102,.15); }
}

/* STAT CARDS */
.stat-card {
    background: var(--surface); border: 1px solid var(--surface-border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-md);
    padding: 20px 22px; display: flex; align-items: center; gap: 16px;
    transition: transform var(--transition), box-shadow var(--transition);
    height: 100%; position: relative; overflow: hidden;
}
.stat-card::after {
    content: ''; position: absolute; top: 0; left: 0;
    width: 4px; height: 100%; border-radius: 4px 0 0 4px;
}
.stat-card.accent-blue::after   { background: var(--brand-primary); }
.stat-card.accent-green::after  { background: var(--brand-success); }
.stat-card.accent-yellow::after { background: var(--brand-warning); }
.stat-card.accent-danger::after { background: var(--brand-danger); }
.stat-card.accent-purple::after { background: var(--brand-purple); }
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
.stat-icon {
    width: 52px; height: 52px; border-radius: var(--radius-md);
    display: grid; place-items: center; font-size: 1.3rem; flex-shrink: 0;
}
.stat-icon-blue   { background: var(--brand-primary-light); color: var(--brand-primary); }
.stat-icon-green  { background: var(--brand-success-light); color: var(--brand-success); }
.stat-icon-yellow { background: var(--brand-warning-light); color: var(--brand-warning); }
.stat-icon-danger { background: var(--brand-danger-light);  color: var(--brand-danger); }
.stat-icon-purple { background: var(--brand-purple-light);  color: var(--brand-purple); }
.stat-body { flex: 1; min-width: 0; }
.stat-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted); margin-bottom: 2px; }
.stat-value { font-size: 1.85rem; font-weight: 800; color: var(--text-primary); line-height: 1.1; margin-bottom: 4px; letter-spacing: -.02em; }
.stat-sub { font-size: .76rem; font-weight: 500; color: var(--text-muted); display: flex; align-items: center; gap: 4px; }
.stat-kpi-bar { width: 100%; height: 5px; background: #f1f3f7; border-radius: 10px; overflow: hidden; margin-top: 8px; }
.stat-kpi-fill { height: 100%; border-radius: 10px; transition: width .7s cubic-bezier(.22,1,.36,1); }

/* REKAP STRIP */
.rekap-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 28px; }
.rekap-badge {
    background: var(--surface); border: 1px solid var(--surface-border);
    border-radius: var(--radius-md); padding: 12px 14px;
    display: flex; align-items: center; gap: 10px; box-shadow: var(--shadow-xs);
}
.rekap-badge-icon { width: 30px; height: 30px; border-radius: var(--radius-sm); display: grid; place-items: center; font-size: .85rem; flex-shrink: 0; }
.rekap-badge-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); line-height: 1; }
.rekap-badge-value { font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }

/* DATA CARD */
.data-card {
    background: var(--surface); border: 1px solid var(--surface-border);
    border-radius: var(--radius-xl); box-shadow: var(--shadow-md);
    overflow: hidden; height: 100%; display: flex; flex-direction: column;
}
.data-card-header {
    padding: 16px 22px; display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid var(--surface-border); background: var(--surface-soft); flex-shrink: 0;
}
.data-card-header-left { display: flex; align-items: center; gap: 10px; }
.header-icon {
    width: 36px; height: 36px; background: var(--brand-primary-light);
    border-radius: var(--radius-sm); display: grid; place-items: center;
    color: var(--brand-primary); font-size: .9rem; flex-shrink: 0;
}
.header-icon-green  { background: var(--brand-success-light); color: var(--brand-success); }
.header-icon-yellow { background: var(--brand-warning-light); color: var(--brand-warning); }
.header-icon-danger { background: var(--brand-danger-light);  color: var(--brand-danger); }
.header-icon-info   { background: var(--brand-info-light);    color: var(--brand-info); }
.header-icon-purple { background: var(--brand-purple-light);  color: var(--brand-purple); }
.data-card-title    { font-size: .9rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.data-card-subtitle { font-size: .7rem; color: var(--text-muted); margin: 0; }
.data-card-body     { padding: 20px 22px; flex: 1; }

/* Progress bars */
.kelas-item { margin-bottom: 14px; }
.kelas-item:last-child { margin-bottom: 0; }
.kelas-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.kelas-meta-name { font-size: .83rem; font-weight: 700; color: var(--text-primary); }
.kelas-meta-count { font-size: .73rem; color: var(--text-muted); font-weight: 500; }
.kelas-progress-wrap { display: flex; align-items: center; gap: 10px; }
.kelas-bar { flex: 1; height: 7px; background: #f1f3f7; border-radius: 10px; overflow: hidden; }
.kelas-bar-fill { height: 100%; border-radius: 10px; transition: width .7s cubic-bezier(.22,1,.36,1); }
.bar-success { background: linear-gradient(90deg, var(--brand-success), #38d9a9); }
.bar-warning { background: linear-gradient(90deg, var(--brand-warning), #fcc419); }
.bar-danger  { background: linear-gradient(90deg, var(--brand-danger),  #ff6b6b); }
.kelas-pct { font-size: .72rem; font-weight: 700; min-width: 36px; text-align: right; }
.pct-success { color: var(--brand-success); }
.pct-warning { color: var(--brand-warning); }
.pct-danger  { color: var(--brand-danger); }

/* Late list */
.late-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--surface-border); }
.late-item:last-child { border-bottom: none; padding-bottom: 0; }
.late-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--brand-warning-light); color: var(--brand-warning); display: grid; place-items: center; font-size: .83rem; font-weight: 800; flex-shrink: 0; }
.late-rank { font-size: .68rem; font-weight: 800; color: var(--text-muted); min-width: 18px; text-align: center; }
.late-name  { font-size: .83rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1px; }
.late-kelas { font-size: .72rem; color: var(--text-muted); }
.late-badge { margin-left: auto; flex-shrink: 0; background: var(--brand-warning-light); color: var(--brand-warning); font-size: .7rem; font-weight: 800; padding: 3px 10px; border-radius: 50px; }

/* Live Feed */
.feed-row { display: flex; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid var(--surface-border); font-size: .8rem; }
.feed-row:last-child { border-bottom: none; padding-bottom: 0; }
.feed-avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--brand-primary-light); color: var(--brand-primary); display: grid; place-items: center; font-size: .75rem; font-weight: 800; flex-shrink: 0; }
.feed-name   { font-weight: 700; color: var(--text-primary); }
.feed-kelas  { color: var(--text-muted); font-size: .72rem; }
.feed-time   { margin-left: auto; color: var(--text-muted); font-size: .72rem; flex-shrink: 0; }
.feed-status { font-size: .68rem; font-weight: 700; padding: 2px 8px; border-radius: 50px; flex-shrink: 0; }
.feed-hadir     { background: var(--brand-success-light); color: var(--brand-success); }
.feed-terlambat { background: var(--brand-warning-light); color: var(--brand-warning); }
.feed-izin      { background: var(--brand-info-light);    color: var(--brand-info); }
.feed-alpha     { background: var(--brand-danger-light);  color: var(--brand-danger); }

/* Student Table */
.student-table { width: 100%; border-collapse: collapse; }
.student-table th {
    font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em;
    color: var(--text-muted); padding: 8px 12px;
    border-bottom: 2px solid var(--surface-border); background: var(--surface-soft); text-align: left;
}
.student-table td {
    padding: 10px 12px; border-bottom: 1px solid var(--surface-border);
    font-size: .8rem; color: var(--text-primary); vertical-align: middle;
}
.student-table tbody tr:last-child td { border-bottom: none; }
.student-table tbody tr:hover td { background: var(--surface-soft); }
.student-avatar-sm {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--brand-primary-light); color: var(--brand-primary);
    display: inline-grid; place-items: center; font-size: .7rem; font-weight: 800; flex-shrink: 0;
}

/* KPI Donut legend */
.kpi-row { display: flex; align-items: center; gap: 8px; padding: 7px 0; border-bottom: 1px solid var(--surface-border); font-size: .8rem; }
.kpi-row:last-child { border-bottom: none; }
.kpi-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.kpi-label { flex: 1; color: var(--text-secondary); font-weight: 600; }
.kpi-val { font-weight: 800; color: var(--text-primary); }
.kpi-pct { font-size: .7rem; color: var(--text-muted); min-width: 36px; text-align: right; }

/* Misc */
.empty-state { text-align: center; padding: 32px 16px; }
.empty-state-icon { font-size: 2rem; color: var(--brand-success); margin-bottom: 8px; }
.empty-state p { font-size: .82rem; color: var(--text-muted); margin: 0; }
.scroll-list { max-height: 290px; overflow-y: auto; padding-right: 2px; }
.scroll-list::-webkit-scrollbar { width: 3px; }
.scroll-list::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 4px; }
.section-label { font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--text-muted); padding: 4px 0 12px; }
.alert-guru { border-radius: var(--radius-md); padding: 14px 20px; display: flex; align-items: center; gap: 12px; font-size: .83rem; font-weight: 600; margin-bottom: 20px; }
.alert-guru-warning { background: var(--brand-warning-light); border: 1px solid #ffc94d; color: #7c5a00; }
.alert-guru-danger  { background: var(--brand-danger-light);  border: 1px solid #ffa8a8; color: #8c1a1a; }
.kelas-tab-wrap { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px; }
.kelas-tab { font-size: .75rem; font-weight: 700; padding: 6px 14px; border-radius: 50px; background: var(--surface-soft); border: 1px solid var(--surface-border); color: var(--text-muted); cursor: pointer; transition: all var(--transition); }
.kelas-tab.active, .kelas-tab:hover { background: var(--brand-primary); border-color: var(--brand-primary); color: #fff; }
.btn-card-action { font-size: .72rem; font-weight: 700; background: var(--brand-primary-light); color: var(--brand-primary); border: none; border-radius: 8px; padding: 5px 12px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: background var(--transition); }
.btn-card-action:hover { background: var(--brand-primary); color: #fff; }
.btn-card-action-green { background: var(--brand-success-light); color: var(--brand-success); }
.btn-card-action-green:hover { background: var(--brand-success); color: #fff; }

@media (max-width: 767px) {
    .rekap-strip { grid-template-columns: repeat(2, 1fr); }
    .hero-right  { display: none; }
    .page-hero   { padding: 20px 20px; }
}
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-person-badge-fill me-2" style="opacity:.85"></i>{{ $guru->nama }}</h1>
        <p class="hero-nip"><i class="bi bi-credit-card-2-front me-1"></i>NIP: {{ $guru->nip }}</p>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item active">Dashboard Wali Kelas</li>
            </ol>
        </nav>
    </div>
    <div class="hero-right">
        <div class="hero-status-pill">
            <span class="status-dot"></span>
            {{ $statusWaktu }}
        </div>
        <div class="hero-time-pill">
            <i class="bi bi-clock"></i>
            <span id="currentTime">--:--:--</span>
            &nbsp;·&nbsp;
            <span>{{ now()->isoFormat('ddd, D MMM Y') }}</span>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert-guru alert-guru-danger">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        {{ session('error') }}
    </div>
@endif

@if($daftarKelas->isEmpty())
    <div class="alert-guru alert-guru-warning">
        <i class="bi bi-info-circle-fill fs-5"></i>
        Anda belum ditugaskan menjadi Wali Kelas oleh Admin. Hubungi administrator untuk pengaturan lebih lanjut.
    </div>
@else

<!-- KPI STAT CARDS (5 kartu — tanpa Izin) -->
<div class="row g-3 mb-4">
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card accent-blue">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-body">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
                <div class="stat-sub">{{ $daftarKelas->count() }} kelas diampu</div>
            </div>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card accent-green">
            <div class="stat-icon stat-icon-green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-body">
                <div class="stat-label">Hadir Hari Ini</div>
                <div class="stat-value">{{ $hadirHariIni }}</div>
                <div class="stat-sub">{{ $persentaseHadir }}% kehadiran</div>
                <div class="stat-kpi-bar">
                    <div class="stat-kpi-fill bar-success" style="width:{{ $persentaseHadir }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card accent-yellow">
            <div class="stat-icon stat-icon-yellow"><i class="bi bi-clock-history"></i></div>
            <div class="stat-body">
                <div class="stat-label">Terlambat</div>
                <div class="stat-value">{{ $terlambatHariIni }}</div>
                <div class="stat-sub">Hari ini</div>
            </div>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card accent-danger">
            <div class="stat-icon stat-icon-danger"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-body">
                <div class="stat-label">Alpha</div>
                <div class="stat-value">{{ $alphaHariIni }}</div>
                <div class="stat-sub">Tidak hadir</div>
            </div>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card accent-purple">
            <div class="stat-icon stat-icon-purple"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-body">
                <div class="stat-label">KPI Kehadiran</div>
                <div class="stat-value" style="color:{{ $persentaseHadir >= 80 ? 'var(--brand-success)' : ($persentaseHadir >= 60 ? 'var(--brand-warning)' : 'var(--brand-danger)') }};">{{ $persentaseHadir }}%</div>
                <div class="stat-sub">Hari ini</div>
            </div>
        </div>
    </div>
</div>

<!-- REKAP BULAN INI (3 badge — tanpa Izin) -->
<div class="section-label"><i class="bi bi-calendar3 me-1"></i>Rekap Bulan Ini — Seluruh Kelas Diampu</div>
<div class="rekap-strip mb-4">
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-success-light);color:var(--brand-success);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Hadir</div>
            <div class="rekap-badge-value">{{ $rekapBulan['hadir'] }}</div>
        </div>
    </div>
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-warning-light);color:var(--brand-warning);">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Terlambat</div>
            <div class="rekap-badge-value">{{ $rekapBulan['terlambat'] }}</div>
        </div>
    </div>
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-danger-light);color:var(--brand-danger);">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Alpha</div>
            <div class="rekap-badge-value">{{ $rekapBulan['alpha'] }}</div>
        </div>
    </div>
</div>

<!-- MAIN GRID -->
<section class="section">
    <div class="row g-3 g-md-4">

        <!-- KOLOM KIRI (8/12) -->
        <div class="col-lg-8">
            <div class="row g-3 g-md-4">

                <!-- Tren 7 Hari -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Tren Absensi Siswa</p>
                                    <p class="data-card-subtitle">7 hari terakhir — seluruh kelas yang diampu</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-bottom:10px;">
                            <div id="trendChart" style="min-height:300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Perbandingan Kelas -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-green"><i class="bi bi-diagram-3-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Perbandingan Kelas</p>
                                    <p class="data-card-subtitle">Status absensi per kelas — hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-bottom:10px;">
                            <div id="kelasChart" style="min-height:240px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Live Feed -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-info"><i class="bi bi-activity"></i></div>
                                <div>
                                    <p class="data-card-title">Aktivitas Terbaru</p>
                                    <p class="data-card-subtitle">8 absensi masuk terakhir dari siswa Anda</p>
                                </div>
                            </div>
                            <a href="{{ route('guru.absensi.hari-ini') }}" class="btn-card-action">
                                Lihat semua <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="data-card-body" style="padding-top:12px;padding-bottom:12px;">
                            @forelse($absensiTerbaru as $item)
                                <div class="feed-row">
                                    <div class="feed-avatar">
                                        {{ strtoupper(substr($item->siswa->nama ?? 'U', 0, 1)) }}
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="feed-name">{{ $item->siswa->nama ?? '-' }}</div>
                                        <div class="feed-kelas">{{ $item->siswa->kelas->nama ?? '-' }}</div>
                                    </div>
                                    @php
                                        $st  = $item->status;
                                        $cls = match($st) {
                                            'hadir'     => 'feed-hadir',
                                            'terlambat' => 'feed-terlambat',
                                            'izin'      => 'feed-izin',
                                            default     => 'feed-alpha',
                                        };
                                    @endphp
                                    <span class="feed-status {{ $cls }}">{{ ucfirst($st) }}</span>
                                    <span class="feed-time">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }}</span>
                                </div>
                            @empty
                                <div class="empty-state" style="padding:24px;">
                                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                    <p>Belum ada absensi hari ini dari siswa Anda</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Daftar Siswa Per Kelas -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-purple"><i class="bi bi-person-lines-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Daftar Siswa</p>
                                    <p class="data-card-subtitle">Seluruh siswa pada kelas yang Anda ampu</p>
                                </div>
                            </div>
                            <a href="/guru/siswa" class="btn-card-action btn-card-action-green">
                                <i class="bi bi-box-arrow-up-right"></i> Kelola Siswa
                            </a>
                        </div>
                        <div class="data-card-body" style="padding:0;">

                            @if($daftarKelas->count() > 1)
                            <div class="kelas-tab-wrap" style="padding:16px 22px 0;">
                                @foreach($daftarKelas as $kls)
                                    <span class="kelas-tab {{ $loop->first ? 'active' : '' }}"
                                          onclick="switchKelasTab('kelas-{{ $kls->id }}', this)">
                                        {{ $kls->nama }}
                                        <span style="opacity:.7;font-weight:600;font-size:.65rem;">({{ $kls->siswa->count() }})</span>
                                    </span>
                                @endforeach
                            </div>
                            @endif

                            @foreach($daftarKelas as $kls)
                            <div id="kelas-{{ $kls->id }}"
                                 class="kelas-tab-content"
                                 style="{{ !$loop->first ? 'display:none;' : '' }}">
                                <div class="table-responsive">
                                    <table class="student-table">
                                        <thead>
                                            <tr>
                                                <th width="40" class="text-center" style="padding-left:22px;">No</th>
                                                <th>Nama Siswa</th>
                                                <th>NISN / NIS</th>
                                                <th>Status Hari Ini</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kls->siswa as $idx => $siswa)
                                                @php
                                                    $absensiSiswaHariIni = $siswa->absensi
                                                        ->where('tanggal', today()->toDateString())
                                                        ->where('jenis', 'masuk')
                                                        ->first();
                                                    $statusSiswa = $absensiSiswaHariIni?->status ?? 'belum';
                                                    $statusClass = match($statusSiswa) {
                                                        'hadir'     => 'feed-hadir',
                                                        'terlambat' => 'feed-terlambat',
                                                        'izin'      => 'feed-izin',
                                                        'belum'     => 'tren-flat',
                                                        default     => 'feed-alpha',
                                                    };
                                                    $statusLabel = match($statusSiswa) {
                                                        'hadir'     => 'Hadir',
                                                        'terlambat' => 'Terlambat',
                                                        'izin'      => 'Izin',
                                                        'belum'     => 'Belum',
                                                        default     => 'Alpha',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="text-center" style="color:var(--text-muted);font-size:.72rem;font-weight:700;padding-left:22px;">{{ $idx + 1 }}</td>
                                                    <td>
                                                        <div style="display:flex;align-items:center;gap:8px;">
                                                            <div class="student-avatar-sm">
                                                                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                                            </div>
                                                            <span style="font-weight:700;font-size:.82rem;">{{ $siswa->nama }}</span>
                                                        </div>
                                                    </td>
                                                    <td style="color:var(--text-muted);font-size:.78rem;">{{ $siswa->nisn ?? $siswa->nis ?? '-' }}</td>
                                                    <td>
                                                        <span class="feed-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" style="text-align:center;color:var(--text-muted);padding:28px;font-size:.82rem;">
                                                        Belum ada siswa pada kelas ini
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if($kls->siswa->count() > 0)
                                <div style="padding:12px 22px;border-top:1px solid var(--surface-border);display:flex;justify-content:flex-end;">
                                    <a href="/guru/siswa" class="btn-card-action">
                                        Kelola semua siswa <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN (4/12) -->
        <div class="col-lg-4">
            <div class="row g-3 g-md-4">

                <!-- Donut Status Hari Ini (tanpa Izin) -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon"><i class="bi bi-pie-chart-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Status Absensi</p>
                                    <p class="data-card-subtitle">Distribusi siswa hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-top:6px;padding-bottom:6px;">
                            <div id="statusDonut" style="min-height:230px;"></div>
                            @php
                                $totalKpi = ($statusHariIni['hadir'] ?? 0) + ($statusHariIni['terlambat'] ?? 0) + ($statusHariIni['alpha'] ?? 0);
                            @endphp
                            <div style="margin-top:8px;">
                                <div class="kpi-row">
                                    <div class="kpi-dot" style="background:var(--brand-success);"></div>
                                    <span class="kpi-label">Hadir</span>
                                    <span class="kpi-val">{{ $statusHariIni['hadir'] }}</span>
                                    <span class="kpi-pct">{{ $totalKpi > 0 ? round($statusHariIni['hadir']/$totalKpi*100) : 0 }}%</span>
                                </div>
                                <div class="kpi-row">
                                    <div class="kpi-dot" style="background:var(--brand-warning);"></div>
                                    <span class="kpi-label">Terlambat</span>
                                    <span class="kpi-val">{{ $statusHariIni['terlambat'] }}</span>
                                    <span class="kpi-pct">{{ $totalKpi > 0 ? round($statusHariIni['terlambat']/$totalKpi*100) : 0 }}%</span>
                                </div>
                                <div class="kpi-row">
                                    <div class="kpi-dot" style="background:var(--brand-danger);"></div>
                                    <span class="kpi-label">Alpha</span>
                                    <span class="kpi-val">{{ $statusHariIni['alpha'] }}</span>
                                    <span class="kpi-pct">{{ $totalKpi > 0 ? round($statusHariIni['alpha']/$totalKpi*100) : 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- % Kehadiran Per Kelas -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-green"><i class="bi bi-bar-chart-fill"></i></div>
                                <div>
                                    <p class="data-card-title">% Kehadiran Per Kelas</p>
                                    <p class="data-card-subtitle">Hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body">
                            <div class="scroll-list">
                                @forelse($kelasChartData as $kelas)
                                    @php
                                        $hdPct = round(($kelas['hadir'] + $kelas['terlambat']) / max($daftarKelas->firstWhere('nama', $kelas['nama'])?->siswa->count(), 1) * 100);
                                        $color = $hdPct >= 80 ? 'success' : ($hdPct >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="kelas-item">
                                        <div class="kelas-meta">
                                            <span class="kelas-meta-name">{{ $kelas['nama'] }}</span>
                                            <span class="kelas-meta-count">{{ $kelas['hadir'] + $kelas['terlambat'] }}/{{ $daftarKelas->firstWhere('nama', $kelas['nama'])?->siswa->count() ?? 0 }}</span>
                                        </div>
                                        <div class="kelas-progress-wrap">
                                            <div class="kelas-bar">
                                                <div class="kelas-bar-fill bar-{{ $color }}" style="width:{{ $hdPct }}%"></div>
                                            </div>
                                            <span class="kelas-pct pct-{{ $color }}">{{ $hdPct }}%</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-building-x"></i></div>
                                        <p>Tidak ada data kelas</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Siswa Sering Terlambat -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-yellow"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <p class="data-card-title">Sering Terlambat</p>
                                    <p class="data-card-subtitle">Top 5 siswa — bulan ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-top:10px;">
                            <div class="scroll-list">
                                @forelse($siswaSeringTerlambat as $idx => $siswa)
                                    <div class="late-item">
                                        <span class="late-rank">#{{ $idx + 1 }}</span>
                                        <div class="late-avatar">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</div>
                                        <div style="flex:1;min-width:0;">
                                            <div class="late-name">{{ $siswa->nama }}</div>
                                            <div class="late-kelas">{{ $siswa->kelas->nama ?? 'Kelas N/A' }}</div>
                                        </div>
                                        <span class="late-badge">{{ $siswa->terlambat_count }}×</span>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-emoji-smile-fill"></i></div>
                                        <p>Tidak ada keterlambatan bulan ini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-info"><i class="bi bi-lightning-charge-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Aksi Cepat</p>
                                    <p class="data-card-subtitle">Navigasi menu utama</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding:16px 22px;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                <a href="{{ route('guru.absensi.hari-ini') }}"
                                   style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:var(--brand-primary-light);border-radius:var(--radius-sm);color:var(--brand-primary);font-size:.78rem;font-weight:700;text-decoration:none;transition:background var(--transition);"
                                   onmouseover="this.style.background='var(--brand-primary)';this.style.color='#fff';"
                                   onmouseout="this.style.background='var(--brand-primary-light)';this.style.color='var(--brand-primary)';">
                                    <i class="bi bi-camera-fill"></i> Absensi Hari Ini
                                </a>
                                <a href="{{ route('guru.absensi.rekap') }}"
                                   style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:var(--brand-success-light);border-radius:var(--radius-sm);color:var(--brand-success);font-size:.78rem;font-weight:700;text-decoration:none;transition:background var(--transition);"
                                   onmouseover="this.style.background='var(--brand-success)';this.style.color='#fff';"
                                   onmouseout="this.style.background='var(--brand-success-light)';this.style.color='var(--brand-success)';">
                                    <i class="bi bi-file-earmark-bar-graph-fill"></i> Rekap Bulanan
                                </a>
                                <a href="{{ route('guru.siswa.index') }}"
                                   style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:var(--brand-info-light);border-radius:var(--radius-sm);color:var(--brand-info);font-size:.78rem;font-weight:700;text-decoration:none;transition:background var(--transition);"
                                   onmouseover="this.style.background='var(--brand-info)';this.style.color='#fff';"
                                   onmouseout="this.style.background='var(--brand-info-light)';this.style.color='var(--brand-info)';">
                                    <i class="bi bi-people-fill"></i> Data Siswa
                                </a>
                                <a href="/guru/siswa"
                                   style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:var(--brand-warning-light);border-radius:var(--radius-sm);color:var(--brand-warning);font-size:.78rem;font-weight:700;text-decoration:none;transition:background var(--transition);"
                                   onmouseover="this.style.background='var(--brand-warning)';this.style.color='#fff';"
                                   onmouseout="this.style.background='var(--brand-warning-light)';this.style.color='var(--brand-warning)';">
                                    <i class="bi bi-pencil-square"></i> Kelola Siswa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

@endif

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const absensi7Hari   = @json($absensi7Hari ?? []);
    const statusHariIni  = @json($statusHariIni ?? []);
    const kelasChartData = @json($kelasChartData ?? []);

    /* Chart 1: Tren 7 Hari */
    if (document.querySelector("#trendChart") && absensi7Hari.length) {
        new ApexCharts(document.querySelector("#trendChart"), {
            series: [
                { name: 'Hadir',     data: absensi7Hari.map(i => i.hadir),     color: '#0ca678' },
                { name: 'Terlambat', data: absensi7Hari.map(i => i.terlambat), color: '#f59f00' },
                { name: 'Alpha',     data: absensi7Hari.map(i => i.alpha),     color: '#e03131' },
            ],
            chart: {
                type: 'area', height: 300,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                zoom: { enabled: false },
                animations: { enabled: true, speed: 600 },
            },
            stroke: { curve: 'smooth', width: 2 },
            fill:   { type: 'gradient', gradient: { opacityFrom: .3, opacityTo: .03, type: 'vertical' } },
            dataLabels: { enabled: false },
            markers: { size: 4, hover: { size: 6 } },
            xaxis: {
                categories: absensi7Hari.map(i => i.tanggal),
                labels: { style: { fontSize: '11px', colors: '#868e96', fontFamily: 'Plus Jakarta Sans' } },
                axisBorder: { show: false }, axisTicks: { show: false },
            },
            yaxis: {
                labels: { style: { fontSize: '11px', colors: '#868e96', fontFamily: 'Plus Jakarta Sans' }, formatter: v => Math.round(v) },
                min: 0,
            },
            grid:   { borderColor: '#f1f3f7', strokeDashArray: 5, xaxis: { lines: { show: false } } },
            legend: { position: 'top', horizontalAlign: 'left', fontSize: '12px', fontWeight: 600, fontFamily: 'Plus Jakarta Sans', markers: { width: 8, height: 8, radius: 4 } },
            tooltip: { theme: 'light' },
        }).render();
    }

    /* Chart 2: Donut Status (tanpa Izin) */
    const totalDonut = (statusHariIni.hadir || 0) + (statusHariIni.terlambat || 0) + (statusHariIni.alpha || 0);
    if (document.querySelector("#statusDonut")) {
        echarts.init(document.querySelector("#statusDonut")).setOption({
            tooltip: { trigger: 'item', formatter: '{b}: <b>{c}</b> ({d}%)', textStyle: { fontFamily: 'Plus Jakarta Sans', fontSize: 12 } },
            graphic: [{
                type: 'text', left: 'center', top: '38%',
                style: { text: totalDonut + '\ntotal', textAlign: 'center', fill: '#1a1d23', fontSize: 18, fontWeight: 800, fontFamily: 'Plus Jakarta Sans', lineHeight: 22 }
            }],
            series: [{
                name: 'Status', type: 'pie',
                radius: ['42%', '65%'], center: ['50%', '52%'],
                avoidLabelOverlap: true, label: { show: false },
                emphasis: { scale: true, scaleSize: 6, label: { show: true, fontSize: 12, fontWeight: 800, fontFamily: 'Plus Jakarta Sans' } },
                itemStyle: { borderRadius: 5, borderColor: '#fff', borderWidth: 2 },
                data: [
                    { value: statusHariIni.hadir     || 0, name: 'Hadir',     itemStyle: { color: '#0ca678' } },
                    { value: statusHariIni.terlambat || 0, name: 'Terlambat', itemStyle: { color: '#f59f00' } },
                    { value: statusHariIni.alpha     || 0, name: 'Alpha',     itemStyle: { color: '#e03131' } },
                ]
            }]
        });
    }

    /* Chart 3: Perbandingan Kelas (tanpa Izin) */
    if (document.querySelector("#kelasChart") && kelasChartData.length) {
        echarts.init(document.querySelector("#kelasChart")).setOption({
            tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' }, textStyle: { fontFamily: 'Plus Jakarta Sans', fontSize: 12 } },
            legend: {
                data: ['Hadir', 'Terlambat', 'Alpha'],
                top: '2%', left: 'left',
                textStyle: { fontSize: 11, fontFamily: 'Plus Jakarta Sans', color: '#495057', fontWeight: 600 },
                icon: 'circle',
            },
            grid: { left: '2%', right: '2%', bottom: '8%', top: '14%', containLabel: true },
            xAxis: [{ type: 'category', data: kelasChartData.map(k => k.nama), axisLabel: { fontSize: 11, fontFamily: 'Plus Jakarta Sans', color: '#868e96' }, axisBorder: { show: false }, axisTick: { show: false } }],
            yAxis: [{ type: 'value', splitLine: { lineStyle: { color: '#f1f3f7', type: 'dashed' } }, axisLabel: { fontSize: 11, fontFamily: 'Plus Jakarta Sans', color: '#868e96' } }],
            series: [
                { name: 'Hadir',     type: 'bar', data: kelasChartData.map(k => k.hadir),     barMaxWidth: 22, itemStyle: { color: '#0ca678', borderRadius: [4,4,0,0] } },
                { name: 'Terlambat', type: 'bar', data: kelasChartData.map(k => k.terlambat), barMaxWidth: 22, itemStyle: { color: '#f59f00', borderRadius: [4,4,0,0] } },
                { name: 'Alpha',     type: 'bar', data: kelasChartData.map(k => k.alpha),     barMaxWidth: 22, itemStyle: { color: '#e03131', borderRadius: [4,4,0,0] } },
            ]
        });
    }

    /* Real-time clock */
    function tick() {
        const el = document.getElementById('currentTime');
        if (el) el.textContent = new Date().toLocaleTimeString('id-ID');
    }
    tick();
    setInterval(tick, 1000);
});

function switchKelasTab(targetId, el) {
    document.querySelectorAll('.kelas-tab-content').forEach(c => c.style.display = 'none');
    document.querySelectorAll('.kelas-tab').forEach(t => t.classList.remove('active'));
    document.getElementById(targetId).style.display = '';
    el.classList.add('active');
}
</script>
@endpush