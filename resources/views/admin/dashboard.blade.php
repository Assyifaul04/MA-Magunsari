@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════
   CSS VARIABLES — warna tidak diubah
═══════════════════════════════════════════════ */
:root {
    --brand-primary:        #3b5bdb;
    --brand-primary-dark:   #2f4ac2;
    --brand-primary-light:  #eef2ff;
    --brand-success:        #0ca678;
    --brand-success-light:  #e6fcf5;
    --brand-warning:        #f59f00;
    --brand-warning-light:  #fff9db;
    --brand-danger:         #e03131;
    --brand-danger-light:   #fff5f5;
    --brand-info:           #1098ad;
    --brand-info-light:     #e3fafc;
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

body, .section, .card, .modal-content {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* ═══════════════════════════════════════════════
   PAGE HERO
═══════════════════════════════════════════════ */
.page-hero {
    background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%);
    border-radius: var(--radius-xl);
    padding: 28px 32px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 8px 32px rgba(59,91,219,.28);
    position: relative;
    overflow: hidden;
}
.page-hero::before {
    content: '';
    position: absolute; right: -60px; top: -60px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,.06); border-radius: 50%;
    pointer-events: none;
}
.page-hero::after {
    content: '';
    position: absolute; right: 80px; bottom: -70px;
    width: 150px; height: 150px;
    background: rgba(255,255,255,.04); border-radius: 50%;
    pointer-events: none;
}
.page-hero-left {}
.page-hero h1 {
    font-size: 1.5rem; font-weight: 800;
    color: #fff; margin: 0 0 6px;
    letter-spacing: -.01em;
}
.page-hero .breadcrumb {
    margin: 0; background: transparent; padding: 0; font-size: .78rem;
}
.page-hero .breadcrumb-item a,
.page-hero .breadcrumb-item.active { color: rgba(255,255,255,.7); text-decoration: none; }
.page-hero .breadcrumb-item a:hover { color: #fff; }
.page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.35); }

.hero-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; position: relative; z-index: 1; }
.hero-time-pill {
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 50px;
    padding: 9px 18px;
    color: #fff;
    font-size: .82rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 8px;
    backdrop-filter: blur(8px);
}
.hero-status-pill {
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 50px;
    padding: 9px 16px;
    color: #fff;
    font-size: .8rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 7px;
    backdrop-filter: blur(8px);
}
.hero-status-pill .status-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #51cf66;
    box-shadow: 0 0 0 2px rgba(81,207,102,.35);
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%,100% { box-shadow: 0 0 0 2px rgba(81,207,102,.35); }
    50%      { box-shadow: 0 0 0 5px rgba(81,207,102,.15); }
}

/* ═══════════════════════════════════════════════
   STAT CARDS
═══════════════════════════════════════════════ */
.stat-card {
    background: var(--surface);
    border: 1px solid var(--surface-border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform var(--transition), box-shadow var(--transition);
    height: 100%;
    position: relative;
    overflow: hidden;
}
.stat-card::after {
    content: '';
    position: absolute; top: 0; left: 0;
    width: 4px; height: 100%;
    border-radius: 4px 0 0 4px;
}
.stat-card.accent-blue::after  { background: var(--brand-primary); }
.stat-card.accent-green::after { background: var(--brand-success); }
.stat-card.accent-yellow::after{ background: var(--brand-warning); }
.stat-card.accent-teal::after  { background: var(--brand-info); }
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}
.stat-icon {
    width: 52px; height: 52px;
    border-radius: var(--radius-md);
    display: grid; place-items: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.stat-icon-blue   { background: var(--brand-primary-light); color: var(--brand-primary); }
.stat-icon-green  { background: var(--brand-success-light); color: var(--brand-success); }
.stat-icon-yellow { background: var(--brand-warning-light); color: var(--brand-warning); }
.stat-icon-teal   { background: var(--brand-info-light);    color: var(--brand-info); }

.stat-body { flex: 1; min-width: 0; }
.stat-label {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em;
    color: var(--text-muted); margin-bottom: 2px;
}
.stat-value {
    font-size: 1.85rem; font-weight: 800;
    color: var(--text-primary); line-height: 1.1;
    margin-bottom: 4px; letter-spacing: -.02em;
}
.stat-sub {
    font-size: .76rem; font-weight: 500;
    color: var(--text-muted);
    display: flex; align-items: center; gap: 4px;
}
.stat-sub .dot-green { color: var(--brand-success); }

/* ═══════════════════════════════════════════════
   REKAP BULAN INI — mini badges
═══════════════════════════════════════════════ */
.rekap-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 28px;
}
.rekap-badge {
    background: var(--surface);
    border: 1px solid var(--surface-border);
    border-radius: var(--radius-md);
    padding: 12px 14px;
    display: flex; align-items: center; gap: 10px;
    box-shadow: var(--shadow-xs);
}
.rekap-badge-icon {
    width: 30px; height: 30px;
    border-radius: var(--radius-sm);
    display: grid; place-items: center;
    font-size: .85rem; flex-shrink: 0;
}
.rekap-badge-label {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--text-muted); line-height: 1;
}
.rekap-badge-value {
    font-size: 1.15rem; font-weight: 800;
    color: var(--text-primary); line-height: 1.2;
}

/* ═══════════════════════════════════════════════
   DATA CARD
═══════════════════════════════════════════════ */
.data-card {
    background: var(--surface);
    border: 1px solid var(--surface-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    height: 100%;
    display: flex; flex-direction: column;
}
.data-card-header {
    padding: 16px 22px;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid var(--surface-border);
    background: var(--surface-soft);
    flex-shrink: 0;
}
.data-card-header-left { display: flex; align-items: center; gap: 10px; }
.header-icon {
    width: 36px; height: 36px;
    background: var(--brand-primary-light);
    border-radius: var(--radius-sm);
    display: grid; place-items: center;
    color: var(--brand-primary); font-size: .9rem; flex-shrink: 0;
}
.header-icon-green  { background: var(--brand-success-light); color: var(--brand-success); }
.header-icon-yellow { background: var(--brand-warning-light); color: var(--brand-warning); }
.header-icon-danger { background: var(--brand-danger-light);  color: var(--brand-danger); }
.header-icon-info   { background: var(--brand-info-light);    color: var(--brand-info); }
.data-card-title    { font-size: .9rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.data-card-subtitle { font-size: .7rem; color: var(--text-muted); margin: 0; }
.data-card-body     { padding: 20px 22px; flex: 1; }

/* ── Kehadiran big number inside card ── */
.attendance-ring-wrap {
    display: flex; align-items: center; justify-content: center;
    gap: 24px; flex-wrap: wrap;
    padding: 8px 0 0;
}
.attendance-number {
    font-size: 3rem; font-weight: 800; color: var(--brand-primary);
    letter-spacing: -.04em; line-height: 1;
}
.attendance-label { font-size: .78rem; color: var(--text-muted); font-weight: 500; }

/* ── Kelas Progress List ── */
.kelas-item { margin-bottom: 14px; }
.kelas-item:last-child { margin-bottom: 0; }
.kelas-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.kelas-meta-name { font-size: .83rem; font-weight: 700; color: var(--text-primary); }
.kelas-meta-count { font-size: .73rem; color: var(--text-muted); font-weight: 500; }
.kelas-progress-wrap { display: flex; align-items: center; gap: 10px; }
.kelas-bar {
    flex: 1; height: 7px;
    background: #f1f3f7; border-radius: 10px; overflow: hidden;
}
.kelas-bar-fill { height: 100%; border-radius: 10px; transition: width .7s cubic-bezier(.22,1,.36,1); }
.bar-success { background: linear-gradient(90deg, var(--brand-success), #38d9a9); }
.bar-warning { background: linear-gradient(90deg, var(--brand-warning), #fcc419); }
.bar-danger  { background: linear-gradient(90deg, var(--brand-danger),  #ff6b6b); }
.kelas-pct { font-size: .72rem; font-weight: 700; min-width: 36px; text-align: right; }
.pct-success { color: var(--brand-success); }
.pct-warning { color: var(--brand-warning); }
.pct-danger  { color: var(--brand-danger); }

/* ── Late Students List ── */
.late-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--surface-border);
}
.late-item:last-child { border-bottom: none; padding-bottom: 0; }
.late-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--brand-warning-light);
    color: var(--brand-warning);
    display: grid; place-items: center;
    font-size: .83rem; font-weight: 800; flex-shrink: 0;
}
.late-rank {
    font-size: .68rem; font-weight: 800; color: var(--text-muted);
    min-width: 18px; text-align: center;
}
.late-name  { font-size: .83rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1px; }
.late-kelas { font-size: .72rem; color: var(--text-muted); }
.late-badge {
    margin-left: auto; flex-shrink: 0;
    background: var(--brand-warning-light); color: var(--brand-warning);
    font-size: .7rem; font-weight: 800;
    padding: 3px 10px; border-radius: 50px;
}

/* ── Live Feed ── */
.feed-row {
    display: flex; align-items: center; gap: 12px;
    padding: 9px 0;
    border-bottom: 1px solid var(--surface-border);
    font-size: .8rem;
}
.feed-row:last-child { border-bottom: none; padding-bottom: 0; }
.feed-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--brand-primary-light); color: var(--brand-primary);
    display: grid; place-items: center;
    font-size: .75rem; font-weight: 800; flex-shrink: 0;
}
.feed-name   { font-weight: 700; color: var(--text-primary); }
.feed-kelas  { color: var(--text-muted); font-size: .72rem; }
.feed-time   { margin-left: auto; color: var(--text-muted); font-size: .72rem; flex-shrink: 0; }
.feed-status {
    font-size: .68rem; font-weight: 700; padding: 2px 8px;
    border-radius: 50px; flex-shrink: 0;
}
.feed-hadir    { background: var(--brand-success-light); color: var(--brand-success); }
.feed-terlambat{ background: var(--brand-warning-light); color: var(--brand-warning); }
.feed-izin     { background: var(--brand-info-light);    color: var(--brand-info); }

/* ── Jam Pengaturan Info ── */
.jam-info-row {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 14px;
    border-radius: var(--radius-sm);
    background: var(--surface-soft);
    border: 1px solid var(--surface-border);
    margin-bottom: 8px;
    font-size: .8rem;
}
.jam-info-row:last-child { margin-bottom: 0; }
.jam-info-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: grid; place-items: center;
    font-size: .78rem; flex-shrink: 0;
}
.jam-info-label { color: var(--text-muted); font-weight: 600; flex: 1; }
.jam-info-value { font-weight: 800; color: var(--text-primary); }

/* ── Empty state ── */
.empty-state { text-align: center; padding: 32px 16px; }
.empty-state-icon { font-size: 2rem; color: var(--brand-success); margin-bottom: 8px; }
.empty-state p { font-size: .82rem; color: var(--text-muted); margin: 0; }

/* ── Scrollable list ── */
.scroll-list { max-height: 290px; overflow-y: auto; padding-right: 2px; }
.scroll-list::-webkit-scrollbar { width: 3px; }
.scroll-list::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 4px; }

/* ── Tren chip ── */
.tren-chip {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .7rem; font-weight: 700;
    padding: 3px 9px; border-radius: 50px;
}
.tren-up   { background: var(--brand-success-light); color: var(--brand-success); }
.tren-down { background: var(--brand-danger-light);  color: var(--brand-danger); }
.tren-flat { background: var(--surface-soft); color: var(--text-muted); }

/* ── Section divider label ── */
.section-label {
    font-size: .7rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .1em;
    color: var(--text-muted);
    padding: 4px 0 12px;
}

@media (max-width: 767px) {
    .rekap-strip { grid-template-columns: repeat(2, 1fr); }
    .hero-right  { display: none; }
    .page-hero   { padding: 20px 20px; }
}
</style>

<!-- ══════════════════════════════════════════════
     PAGE HERO
══════════════════════════════════════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-speedometer2 me-2" style="opacity:.85"></i>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="hero-right">
        <div class="hero-status-pill">
            <span class="status-dot"></span>
            {{ $statusWaktu }} &mdash; {{ $jenisAbsensi }}
        </div>
        <div class="hero-time-pill">
            <i class="bi bi-clock"></i>
            <span id="currentTime">--:--:--</span>
            &nbsp;·&nbsp;
            <span>{{ now()->isoFormat('ddd, D MMM Y') }}</span>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card accent-blue">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-people-fill"></i></div>
            <div class="stat-body">
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
                <div class="stat-sub">
                    <span class="dot-green"><i class="bi bi-circle-fill" style="font-size:.45rem;"></i></span>
                    {{ $siswaAktif }} aktif &bull; {{ $siswaPending }} pending
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card accent-green">
            <div class="stat-icon stat-icon-green"><i class="bi bi-building"></i></div>
            <div class="stat-body">
                <div class="stat-label">Total Kelas</div>
                <div class="stat-value">{{ $totalKelas }}</div>
                <div class="stat-sub">kelas terdaftar</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card accent-yellow">
            <div class="stat-icon stat-icon-yellow"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="stat-body">
                <div class="stat-label">Absensi Hari Ini</div>
                <div class="stat-value">{{ $absensiHariIni }}</div>
                <div class="stat-sub">{{ $hadirHariIni }} hadir &bull; {{ $persentaseHadir }}%</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card accent-teal">
            <div class="stat-icon stat-icon-teal"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-body">
                <div class="stat-label">Status Sistem</div>
                <div class="stat-value" style="font-size:1.05rem;padding-top:4px;text-transform:capitalize;">{{ $jenisAbsensi }}</div>
                <div class="stat-sub">{{ $statusWaktu }}</div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     REKAP STRIP BULAN INI
══════════════════════════════════════════════ -->
<div class="section-label"><i class="bi bi-calendar3 me-1"></i>Rekap Bulan Ini</div>
<div class="rekap-strip mb-4">
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-success-light);color:var(--brand-success);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Hadir</div>
            <div class="rekap-badge-value">{{ $rekapBulanIni['hadir'] }}</div>
        </div>
    </div>
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-warning-light);color:var(--brand-warning);">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Terlambat</div>
            <div class="rekap-badge-value">{{ $rekapBulanIni['terlambat'] }}</div>
        </div>
    </div>
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-info-light);color:var(--brand-info);">
            <i class="bi bi-file-earmark-text-fill"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Izin</div>
            <div class="rekap-badge-value">{{ $rekapBulanIni['izin'] }}</div>
        </div>
    </div>
    <div class="rekap-badge">
        <div class="rekap-badge-icon" style="background:var(--brand-danger-light);color:var(--brand-danger);">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div>
            <div class="rekap-badge-label">Tidak Hadir</div>
            <div class="rekap-badge-value">{{ $rekapBulanIni['tidakHadir'] }}</div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MAIN GRID
══════════════════════════════════════════════ -->
<section class="section dashboard">
    <div class="row g-3 g-md-4">

        <!-- ─── KOLOM KIRI (8/12) ─────────────────── -->
        <div class="col-lg-8">
            <div class="row g-3 g-md-4">

                <!-- Chart Statistik 7 Hari -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Statistik Absensi</p>
                                    <p class="data-card-subtitle">7 hari terakhir — hadir, terlambat, izin, pulang</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-bottom:10px;">
                            <div id="reportsChart" style="min-height:310px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart Masuk vs Pulang -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-green"><i class="bi bi-arrow-left-right"></i></div>
                                <div>
                                    <p class="data-card-title">Absensi Masuk vs Pulang</p>
                                    <p class="data-card-subtitle">Perbandingan minggu ini (per hari)</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-bottom:10px;">
                            <div id="trafficChart" style="min-height:290px;"></div>
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
                                    <p class="data-card-subtitle">10 absensi masuk terakhir hari ini</p>
                                </div>
                            </div>
                            <a href="{{ route('absensi.hariIni') }}"
                               class="btn btn-sm"
                               style="font-size:.72rem;font-weight:700;background:var(--brand-primary-light);color:var(--brand-primary);border:none;border-radius:8px;padding:5px 12px;">
                                Lihat semua <i class="bi bi-arrow-right ms-1"></i>
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
                                    <span class="feed-status feed-{{ $item->status === 'terlambat' ? 'terlambat' : ($item->status === 'izin' ? 'izin' : 'hadir') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                    <span class="feed-time">{{ \Carbon\Carbon::parse($item->jam)->format('H:i') }}</span>
                                </div>
                            @empty
                                <div class="empty-state" style="padding:24px;">
                                    <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                                    <p>Belum ada absensi hari ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ─── KOLOM KANAN (4/12) ───────────────── -->
        <div class="col-lg-4">
            <div class="row g-3 g-md-4">

                <!-- Donut: Status Hari Ini -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon"><i class="bi bi-pie-chart-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Status Absensi</p>
                                    <p class="data-card-subtitle">Distribusi hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-top:6px;padding-bottom:14px;">
                            <div id="budgetChart" style="min-height:240px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Absensi Per Kelas -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-green"><i class="bi bi-diagram-3-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Kehadiran Per Kelas</p>
                                    <p class="data-card-subtitle">Hari ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body">
                            <div class="scroll-list">
                                @forelse($absensiPerKelas as $kelas)
                                    @php
                                        $pct   = $kelas['persentase'];
                                        $color = $pct >= 80 ? 'success' : ($pct >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="kelas-item">
                                        <div class="kelas-meta">
                                            <span class="kelas-meta-name">{{ $kelas['nama'] }}</span>
                                            <span class="kelas-meta-count">{{ $kelas['hadir'] }}/{{ $kelas['total_siswa'] }}</span>
                                        </div>
                                        <div class="kelas-progress-wrap">
                                            <div class="kelas-bar">
                                                <div class="kelas-bar-fill bar-{{ $color }}"
                                                     style="width:{{ $pct }}%"></div>
                                            </div>
                                            <span class="kelas-pct pct-{{ $color }}">{{ $pct }}%</span>
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

                <!-- Siswa Paling Terlambat -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-yellow"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <p class="data-card-title">Sering Terlambat</p>
                                    <p class="data-card-subtitle">Top 5 — bulan ini</p>
                                </div>
                            </div>
                        </div>
                        <div class="data-card-body" style="padding-top:10px;">
                            <div class="scroll-list">
                                @forelse($siswaSeringTerlambat as $idx => $siswa)
                                    <div class="late-item">
                                        <span class="late-rank">#{{ $idx + 1 }}</span>
                                        <div class="late-avatar">
                                            {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                        </div>
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

                <!-- Info Jam Pengaturan -->
                <div class="col-12">
                    <div class="data-card">
                        <div class="data-card-header">
                            <div class="data-card-header-left">
                                <div class="header-icon header-icon-info"><i class="bi bi-gear-fill"></i></div>
                                <div>
                                    <p class="data-card-title">Jam Absensi Hari Ini</p>
                                    <p class="data-card-subtitle">Pengaturan aktif</p>
                                </div>
                            </div>
                            <a href="{{ route('pengaturan.edit') }}"
                               style="font-size:.72rem;font-weight:700;color:var(--brand-primary);text-decoration:none;">
                                Edit <i class="bi bi-pencil-fill ms-1" style="font-size:.65rem;"></i>
                            </a>
                        </div>
                        <div class="data-card-body" style="padding-top:14px;padding-bottom:14px;">
                            <div class="jam-info-row">
                                <div class="jam-info-icon" style="background:var(--brand-success-light);color:var(--brand-success);">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                <span class="jam-info-label">Masuk Awal</span>
                                <span class="jam-info-value">{{ \Carbon\Carbon::parse($pengaturanHariIni->jam_masuk_awal)->format('H:i') }}</span>
                            </div>
                            <div class="jam-info-row">
                                <div class="jam-info-icon" style="background:var(--brand-warning-light);color:var(--brand-warning);">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <span class="jam-info-label">Batas Masuk</span>
                                <span class="jam-info-value">{{ \Carbon\Carbon::parse($pengaturanHariIni->jam_masuk_akhir)->format('H:i') }}</span>
                            </div>
                            <div class="jam-info-row">
                                <div class="jam-info-icon" style="background:var(--brand-danger-light);color:var(--brand-danger);">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                                <span class="jam-info-label">Jam Pulang</span>
                                <span class="jam-info-value">{{ \Carbon\Carbon::parse($pengaturanHariIni->jam_pulang)->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const absensi7Hari     = @json($absensi7Hari);
    const absensiStatus    = @json($absensiHariIniStatus);
    const absensiMingguIni = @json($absensiMingguIni);

    /* ── Chart 1: Statistik 7 Hari — ApexCharts area ────── */
    new ApexCharts(document.querySelector("#reportsChart"), {
        series: [
            { name: 'Hadir',        data: absensi7Hari.map(i => i.hadir),        color: '#0ca678' },
            { name: 'Terlambat',    data: absensi7Hari.map(i => i.terlambat),    color: '#f59f00' },
            { name: 'Izin',         data: absensi7Hari.map(i => i.izin),         color: '#1098ad' },
            { name: 'Pulang',       data: absensi7Hari.map(i => i.pulang),       color: '#adb5bd' },
            { name: 'Tidak Hadir',  data: absensi7Hari.map(i => i.tidak_hadir), color: '#e03131' },
        ],
        chart: {
            type: 'area', height: 310,
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
            labels: {
                style: { fontSize: '11px', colors: '#868e96', fontFamily: 'Plus Jakarta Sans' },
                formatter: v => Math.round(v),
            },
            min: 0,
        },
        grid:   { borderColor: '#f1f3f7', strokeDashArray: 5, xaxis: { lines: { show: false } } },
        legend: {
            position: 'top', horizontalAlign: 'left',
            fontSize: '12px', fontWeight: 600,
            fontFamily: 'Plus Jakarta Sans',
            markers: { width: 8, height: 8, radius: 4 },
        },
        tooltip: { theme: 'light', x: { show: true } },
    }).render();

    /* ── Chart 2: Status Absensi — ECharts donut ────────── */
    const totalDonut = Object.values(absensiStatus).reduce((a, b) => a + b, 0) || 0;
    echarts.init(document.querySelector("#budgetChart")).setOption({
        tooltip: {
            trigger: 'item',
            formatter: '{b}: <b>{c}</b> ({d}%)',
            textStyle: { fontFamily: 'Plus Jakarta Sans', fontSize: 12 }
        },
        legend: {
            top: '5%', left: 'center',
            textStyle: { fontSize: 11, fontFamily: 'Plus Jakarta Sans', color: '#495057' },
            icon: 'circle',
        },
        graphic: [{
            type: 'text', left: 'center', top: '42%',
            style: {
                text: totalDonut + '\ntotal',
                textAlign: 'center',
                fill: '#1a1d23',
                fontSize: 20, fontWeight: 800, fontFamily: 'Plus Jakarta Sans',
                lineHeight: 24,
            }
        }],
        series: [{
            name: 'Status', type: 'pie',
            radius: ['44%', '68%'],
            center: ['50%', '55%'],
            avoidLabelOverlap: true,
            label: { show: false },
            emphasis: {
                scale: true, scaleSize: 6,
                label: { show: true, fontSize: 13, fontWeight: 800, fontFamily: 'Plus Jakarta Sans' }
            },
            itemStyle: { borderRadius: 5, borderColor: '#fff', borderWidth: 2 },
            data: [
                { value: absensiStatus.hadir     || 0, name: 'Hadir',       itemStyle: { color: '#0ca678' } },
                { value: absensiStatus.terlambat || 0, name: 'Terlambat',   itemStyle: { color: '#f59f00' } },
                { value: absensiStatus.izin      || 0, name: 'Izin',        itemStyle: { color: '#1098ad' } },
                { value: absensiStatus.pulang    || 0, name: 'Pulang',      itemStyle: { color: '#adb5bd' } },
                { value: absensiStatus['tidak hadir'] || 0, name: 'Tdk Hadir', itemStyle: { color: '#e03131' } },
            ]
        }]
    });

    /* ── Chart 3: Masuk vs Pulang — ECharts bar+line ────── */
    echarts.init(document.querySelector("#trafficChart")).setOption({
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' },
            textStyle: { fontFamily: 'Plus Jakarta Sans', fontSize: 12 }
        },
        legend: {
            data: ['Masuk', 'Pulang'],
            top: '2%', left: 'left',
            textStyle: { fontSize: 12, fontFamily: 'Plus Jakarta Sans', color: '#495057', fontWeight: 600 },
            icon: 'circle',
        },
        grid: { left: '2%', right: '2%', bottom: '8%', top: '14%', containLabel: true },
        xAxis: [{
            type: 'category',
            data: absensiMingguIni.map(i => i.hari + '\n' + i.tanggal),
            axisLabel: {
                fontSize: 10, fontFamily: 'Plus Jakarta Sans',
                color: '#868e96', interval: 0,
            },
            axisBorder: { show: false }, axisTick: { show: false },
        }],
        yAxis: [{
            type: 'value',
            splitLine: { lineStyle: { color: '#f1f3f7', type: 'dashed' } },
            axisLabel: { fontSize: 11, fontFamily: 'Plus Jakarta Sans', color: '#868e96' },
        }],
        series: [
            {
                name: 'Masuk', type: 'bar',
                data: absensiMingguIni.map(i => i.masuk),
                barMaxWidth: 28, barGap: '15%',
                itemStyle: {
                    color: { type: 'linear', x: 0, y: 0, x2: 0, y2: 1,
                        colorStops: [
                            { offset: 0, color: '#4f75ff' },
                            { offset: 1, color: '#3b5bdb' }
                        ]
                    },
                    borderRadius: [5, 5, 0, 0],
                },
                emphasis: { focus: 'series' },
            },
            {
                name: 'Pulang', type: 'bar',
                data: absensiMingguIni.map(i => i.pulang),
                barMaxWidth: 28,
                itemStyle: {
                    color: { type: 'linear', x: 0, y: 0, x2: 0, y2: 1,
                        colorStops: [
                            { offset: 0, color: '#38d9a9' },
                            { offset: 1, color: '#0ca678' }
                        ]
                    },
                    borderRadius: [5, 5, 0, 0],
                },
                emphasis: { focus: 'series' },
            }
        ]
    });

    /* ── Real-time clock ─────────────────────────────────── */
    function tick() {
        const el = document.getElementById('currentTime');
        if (el) el.textContent = new Date().toLocaleTimeString('id-ID');
    }
    tick();
    setInterval(tick, 1000);

});
</script>
@endpush