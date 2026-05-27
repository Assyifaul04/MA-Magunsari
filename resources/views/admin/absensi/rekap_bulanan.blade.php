@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #3b5bdb;
        --brand-primary-light: #eef2ff;
        --brand-primary-dark:  #2f4ac2;
        --brand-success:       #0ca678;
        --brand-success-light: #e6fcf5;
        --brand-warning:       #f59f00;
        --brand-warning-light: #fff9db;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-info:          #1098ad;
        --brand-info-light:    #e3fafc;
        --surface:             #ffffff;
        --surface-soft:        #f8f9fc;
        --surface-border:      #e9ecef;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-md: 0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --shadow-lg: 0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 20px;
    }

    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 8px 32px rgba(59,91,219,.28);
        position: relative; overflow: hidden;
    }
    .page-hero::before {
        content:''; position:absolute; right:-50px; top:-50px;
        width:200px; height:200px; background:rgba(255,255,255,.07); border-radius:50%;
    }
    .page-hero::after {
        content:''; position:absolute; right:70px; bottom:-65px;
        width:140px; height:140px; background:rgba(255,255,255,.05); border-radius:50%;
    }
    .page-hero h1 { font-size:1.45rem; font-weight:700; color:#fff; margin:0 0 4px; }
    .page-hero .breadcrumb { margin:0; background:transparent; padding:0; font-size:.78rem; }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color:rgba(255,255,255,.75); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color:rgba(255,255,255,.4); }

    /* ── Data Card ─────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 16px 22px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .data-card-header-left { display:flex; align-items:center; gap:10px; }
    .header-icon {
        width:36px; height:36px; background:var(--brand-primary-light);
        border-radius:var(--radius-sm); display:grid; place-items:center;
        color:var(--brand-primary); font-size:.95rem; flex-shrink:0;
    }
    .data-card-title    { font-size:.93rem; font-weight:700; color:var(--text-primary); margin:0; }
    .data-card-subtitle { font-size:.72rem; color:var(--text-muted); margin:0; }
    .data-card-body     { padding:22px; }

    /* ── Filter Form ───────────────────── */
    .flabel {
        font-size:.72rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.05em;
        color:var(--text-secondary); margin-bottom:6px; display:block;
    }
    .form-select {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.875rem;
        border:1.5px solid var(--surface-border);
        border-radius:var(--radius-sm);
        padding:9px 13px;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-select:focus {
        border-color:var(--brand-primary);
        box-shadow:0 0 0 3px rgba(59,91,219,.1); outline:none;
    }
    .form-select:disabled { background:var(--surface-soft); cursor:not-allowed; }

    .btn-filter {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.84rem; font-weight:600;
        padding:9px 20px; border-radius:50px;
        display:inline-flex; align-items:center; gap:6px;
        transition:all .2s; cursor:pointer; text-decoration:none;
        border:1.5px solid;
    }
    .btn-filter-reset {
        border-color:var(--brand-primary); color:var(--brand-primary); background:#fff;
    }
    .btn-filter-reset:hover { background:var(--brand-primary-light); color:var(--brand-primary-dark); }
    .btn-filter-export {
        border-color:var(--brand-success); color:#fff; background:var(--brand-success);
    }
    .btn-filter-export:hover { background:#099268; border-color:#099268; box-shadow:0 4px 12px rgba(12,166,120,.3); }

    /* ── Student Profile Banner ────────── */
    .student-banner {
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:12px;
        padding:16px 22px;
        background:linear-gradient(135deg,var(--brand-primary-light),#f5f8ff);
        border-bottom:1px solid var(--surface-border);
    }
    .student-avatar {
        width:48px; height:48px; border-radius:50%;
        background:var(--brand-primary); color:#fff;
        display:grid; place-items:center;
        font-size:1.25rem; font-weight:700; flex-shrink:0;
    }
    .student-name  { font-size:1rem; font-weight:700; color:var(--text-primary); margin:0; }
    .student-kelas { font-size:.78rem; color:var(--text-secondary); margin:0; }
    .month-pill {
        background:var(--brand-primary); color:#fff;
        border-radius:50px; padding:5px 16px;
        font-size:.78rem; font-weight:600;
        display:inline-flex; align-items:center; gap:6px;
    }

    /* ── Stat Mini Cards ───────────────── */
    .stat-mini {
        border-radius:var(--radius-md);
        padding:14px 18px;
        display:flex; align-items:center; gap:12px;
        height:100%;
    }
    .stat-mini-icon {
        width:42px; height:42px; border-radius:var(--radius-sm);
        display:grid; place-items:center; font-size:1.1rem; flex-shrink:0;
    }
    .stat-mini-value { font-size:1.4rem; font-weight:700; line-height:1; }
    .stat-mini-label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; opacity:.75; }
    .stat-mini-pct   { font-size:.7rem; font-weight:500; opacity:.65; }

    .sm-success { background:var(--brand-success-light); color:var(--brand-success); }
    .sm-warning { background:var(--brand-warning-light); color:var(--brand-warning); }
    .sm-info    { background:var(--brand-info-light);    color:var(--brand-info); }
    .sm-danger  { background:var(--brand-danger-light);  color:var(--brand-danger); }

    /* ── Calendar ──────────────────────── */
    .cal-table { width:100%; border-collapse:separate; border-spacing:3px; table-layout:fixed; }
    .cal-table thead th {
        background:var(--brand-primary);
        color:#fff;
        font-size:.7rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.06em;
        padding:10px 4px; text-align:center;
        border-radius:var(--radius-sm);
    }
    .cal-cell {
        border-radius:var(--radius-sm);
        vertical-align:middle; text-align:center;
        height:76px; position:relative;
        transition:transform .15s, box-shadow .15s;
        cursor:default;
    }
    .cal-cell.has-data { cursor:pointer; }
    .cal-cell.has-data:hover { transform:translateY(-2px); box-shadow:0 4px 10px rgba(0,0,0,.12); }
    .cal-cell.blank { background:var(--surface-soft); }

    .cal-cell-inner { padding:6px 4px; }
    .cal-day { font-size:.78rem; font-weight:700; margin-bottom:4px; }
    .cal-icon { font-size:1.1rem; margin-bottom:2px; display:block; }
    .cal-label { font-size:.6rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; display:block; }

    /* Status colours */
    .cal-hadir   { background:#d3f9d8; }  /* light green */
    .cal-izin    { background:#fff3bf; }  /* light yellow */
    .cal-sakit   { background:#d0ebff; }  /* light blue */
    .cal-absen   { background:#ffe3e3; }  /* light red */
    .cal-empty   { background:var(--surface-soft); }

    .cal-hadir  .cal-day { color:#2b8a3e; }
    .cal-izin   .cal-day { color:#e67700; }
    .cal-sakit  .cal-day { color:#1971c2; }
    .cal-absen  .cal-day { color:#c92a2a; }
    .cal-empty  .cal-day { color:var(--text-muted); }

    /* ── Legend ────────────────────────── */
    .legend-wrap {
        display:flex; flex-wrap:wrap; gap:10px;
        padding:14px 22px;
        border-top:1px solid var(--surface-border);
        background:var(--surface-soft);
    }
    .legend-chip {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.75rem; font-weight:600;
        padding:5px 12px; border-radius:50px;
    }
    .lc-success { background:var(--brand-success-light); color:var(--brand-success); }
    .lc-warning { background:var(--brand-warning-light); color:var(--brand-warning); }
    .lc-info    { background:var(--brand-info-light);    color:var(--brand-info); }
    .lc-danger  { background:var(--brand-danger-light);  color:var(--brand-danger); }

    /* ── Empty State ───────────────────── */
    .empty-hero {
        text-align:center; padding:64px 24px;
    }
    .empty-hero-icon {
        width:80px; height:80px; border-radius:50%;
        background:var(--brand-primary-light);
        color:var(--brand-primary);
        display:grid; place-items:center;
        font-size:2rem; margin:0 auto 20px;
    }
    .empty-hero h5 { font-weight:700; color:var(--text-primary); margin-bottom:8px; }
    .empty-hero p  { color:var(--text-secondary); font-size:.875rem; line-height:1.7; }

    @media (max-width:576px) {
        .cal-cell { height:56px; }
        .cal-label { display:none; }
        .cal-icon  { font-size:.9rem; }
    }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div>
        <h1><i class="bi bi-calendar3-week me-2" style="opacity:.85"></i>Rekap Absensi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Absensi</li>
                <li class="breadcrumb-item active">Rekap Bulanan</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="row g-4">

        <!-- ══ FILTER ══════════════════════════════ -->
        <div class="col-12">
            <div class="data-card">
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-funnel-fill"></i></div>
                        <div>
                            <p class="data-card-title">Filter Data</p>
                            <p class="data-card-subtitle">Pilih tahun, bulan, kelas, dan siswa</p>
                        </div>
                    </div>
                </div>
                <div class="data-card-body">
                    <form method="GET" class="row g-3">

                        <div class="col-md-3 col-sm-6">
                            <label class="flabel"><i class="bi bi-calendar-range me-1"></i>Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" onchange="this.form.submit()">
                                <option value="">— Pilih Tahun —</option>
                                @for ($t = now()->year; $t >= 2020; $t--)
                                    <option value="{{ $t }}" {{ (string)$tahun === (string)$t ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label class="flabel"><i class="bi bi-calendar-month me-1"></i>Bulan</label>
                            <select name="bulan" id="bulan" class="form-select" onchange="this.form.submit()">
                                <option value="">— Pilih Bulan —</option>
                                @for ($b = 1; $b <= 12; $b++)
                                    <option value="{{ $b }}" {{ (string)$bulan === (string)$b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $b, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label class="flabel"><i class="bi bi-building me-1"></i>Kelas</label>
                            <select name="kelas" id="kelas" class="form-select" onchange="this.form.submit()">
                                <option value="">— Pilih Kelas —</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ (string)$kelas === (string)$k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label class="flabel"><i class="bi bi-person me-1"></i>Siswa</label>
                            @if ($kelas)
                                @php $siswaKelas = \App\Models\Siswa::where('kelas_id', $kelas)->get(); @endphp
                                <select name="siswa" id="siswa" class="form-select" onchange="this.form.submit()">
                                    <option value="">— Pilih Siswa —</option>
                                    @foreach ($siswaKelas as $s)
                                        <option value="{{ $s->id }}" {{ (string)$siswaId === (string)$s->id ? 'selected' : '' }}>
                                            {{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select class="form-select" disabled>
                                    <option>— Pilih Kelas Dahulu —</option>
                                </select>
                            @endif
                        </div>

                        <div class="col-12 d-flex gap-2 flex-wrap pt-1">
                            <a href="{{ request()->url() }}" class="btn-filter btn-filter-reset">
                                <i class="bi bi-arrow-clockwise"></i> Reset Filter
                            </a>
                            @if ($siswaId && $jumlahHari)
                                <a href="{{ route('rekap.export', ['tahun'=>$tahun,'bulan'=>$bulan,'kelas'=>$kelas,'siswa'=>$siswaId]) }}"
                                   class="btn-filter btn-filter-export">
                                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                                </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- ══ REKAP KALENDER ══════════════════════ -->
        @if ($siswaId && $jumlahHari)
            @foreach ($rekap as $row)
                @php
                    $start         = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
                    $firstDayOfWeek = $start->dayOfWeek;
                    $weeks          = (int) ceil(($jumlahHari + $firstDayOfWeek) / 7);

                    $totalHadir      = collect($row['data'])->filter(fn($s) => $s === '✔')->count();
                    $totalIzin       = collect($row['data'])->filter(fn($s) => $s === 'I')->count();
                    $totalSakit      = collect($row['data'])->filter(fn($s) => $s === 'S')->count();
                    $totaltidakmasuk = collect($row['data'])->filter(fn($s) => $s === '-')->count();
                    $totalKehadiran  = $totalHadir + $totalIzin + $totalSakit + $totaltidakmasuk;
                    $persentaseHadir = $totalKehadiran > 0 ? round(($totalHadir / $totalKehadiran) * 100, 1) : 0;
                    $pctIzin   = $totalKehadiran > 0 ? round(($totalIzin   / $totalKehadiran) * 100, 1) : 0;
                    $pctSakit  = $totalKehadiran > 0 ? round(($totalSakit  / $totalKehadiran) * 100, 1) : 0;
                    $pctAbsen  = $totalKehadiran > 0 ? round(($totaltidakmasuk / $totalKehadiran) * 100, 1) : 0;
                @endphp

                <div class="col-12">
                    <div class="data-card">

                        <!-- Student Banner -->
                        <div class="student-banner">
                            <div class="d-flex align-items-center gap-3">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($row['siswa']->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="student-name">{{ $row['siswa']->nama }}</p>
                                    <p class="student-kelas">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $row['siswa']->kelas->nama ?? '—' }}
                                    </p>
                                </div>
                            </div>
                            <span class="month-pill">
                                <i class="bi bi-calendar3"></i>
                                {{ $start->translatedFormat('F Y') }}
                            </span>
                        </div>

                        <!-- Stat Mini Cards -->
                        <div class="data-card-body pb-0">
                            <div class="row g-3 mb-4">
                                <div class="col-xl-3 col-md-6 col-sm-6">
                                    <div class="stat-mini sm-success">
                                        <div class="stat-mini-icon"><i class="bi bi-check-circle-fill"></i></div>
                                        <div>
                                            <div class="stat-mini-label">Hadir</div>
                                            <div class="stat-mini-value">{{ $totalHadir }}</div>
                                            <div class="stat-mini-pct">{{ $persentaseHadir }}% dari total</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 col-sm-6">
                                    <div class="stat-mini sm-warning">
                                        <div class="stat-mini-icon"><i class="bi bi-info-circle-fill"></i></div>
                                        <div>
                                            <div class="stat-mini-label">Izin</div>
                                            <div class="stat-mini-value">{{ $totalIzin }}</div>
                                            <div class="stat-mini-pct">{{ $pctIzin }}% dari total</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 col-sm-6">
                                    <div class="stat-mini sm-info">
                                        <div class="stat-mini-icon"><i class="bi bi-heart-pulse-fill"></i></div>
                                        <div>
                                            <div class="stat-mini-label">Sakit</div>
                                            <div class="stat-mini-value">{{ $totalSakit }}</div>
                                            <div class="stat-mini-pct">{{ $pctSakit }}% dari total</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 col-sm-6">
                                    <div class="stat-mini sm-danger">
                                        <div class="stat-mini-icon"><i class="bi bi-x-circle-fill"></i></div>
                                        <div>
                                            <div class="stat-mini-label">Tidak Masuk</div>
                                            <div class="stat-mini-value">{{ $totaltidakmasuk }}</div>
                                            <div class="stat-mini-pct">{{ $pctAbsen }}% dari total</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Calendar -->
                            <div class="table-responsive mb-0">
                                <table class="cal-table">
                                    <thead>
                                        <tr>
                                            @foreach(['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                                <th>{{ $day }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($w = 0; $w < $weeks; $w++)
                                        <tr>
                                            @for ($dow = 0; $dow < 7; $dow++)
                                                @php
                                                    $cellDay = $w * 7 + $dow - $firstDayOfWeek + 1;
                                                @endphp

                                                @if ($cellDay >= 1 && $cellDay <= $jumlahHari)
                                                    @php
                                                        $status = $row['data'][$cellDay] ?? null;
                                                        if ($status === '✔') {
                                                            $calClass  = 'cal-hadir';
                                                            $icon      = '<i class="bi bi-check-circle-fill cal-icon" style="color:#2b8a3e"></i>';
                                                            $labelText = 'Hadir';
                                                        } elseif ($status === 'I') {
                                                            $calClass  = 'cal-izin';
                                                            $icon      = '<i class="bi bi-info-circle-fill cal-icon" style="color:#e67700"></i>';
                                                            $labelText = 'Izin';
                                                        } elseif ($status === 'S') {
                                                            $calClass  = 'cal-sakit';
                                                            $icon      = '<i class="bi bi-heart-pulse-fill cal-icon" style="color:#1971c2"></i>';
                                                            $labelText = 'Sakit';
                                                        } elseif ($status === '-') {
                                                            $calClass  = 'cal-absen';
                                                            $icon      = '<i class="bi bi-x-circle-fill cal-icon" style="color:#c92a2a"></i>';
                                                            $labelText = 'Tidak Masuk';
                                                        } else {
                                                            $calClass  = 'cal-empty';
                                                            $icon      = '';
                                                            $labelText = '';
                                                        }
                                                    @endphp
                                                    <td class="cal-cell {{ $calClass }} {{ $status ? 'has-data' : '' }}"
                                                        title="{{ $labelText ? $labelText.' · ' : '' }}{{ $cellDay }} {{ $start->translatedFormat('F Y') }}">
                                                        <div class="cal-cell-inner">
                                                            <div class="cal-day">{{ $cellDay }}</div>
                                                            @if ($status)
                                                                {!! $icon !!}
                                                                <span class="cal-label">{{ $labelText }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                @else
                                                    <td class="cal-cell blank"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="legend-wrap">
                            <span class="legend-chip lc-success"><i class="bi bi-check-circle-fill"></i> Hadir</span>
                            <span class="legend-chip lc-warning"><i class="bi bi-info-circle-fill"></i> Izin</span>
                            <span class="legend-chip lc-info"><i class="bi bi-heart-pulse-fill"></i> Sakit</span>
                            <span class="legend-chip lc-danger"><i class="bi bi-x-circle-fill"></i> Tidak Masuk</span>
                        </div>

                    </div>
                </div>
            @endforeach

        @else
            <!-- Empty State -->
            <div class="col-12">
                <div class="data-card">
                    <div class="empty-hero">
                        <div class="empty-hero-icon">
                            <i class="bi bi-calendar3-week"></i>
                        </div>
                        <h5>Belum Ada Data Absensi</h5>
                        <p>
                            Silakan pilih <strong>Tahun</strong>, <strong>Bulan</strong>,
                            <strong>Kelas</strong>, dan <strong>Siswa</strong><br>
                            untuk menampilkan rekap kalender absensi.
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>

@endsection