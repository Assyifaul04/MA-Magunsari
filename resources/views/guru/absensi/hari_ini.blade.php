@extends('layouts.guru')
@section('title', 'Pantau Absensi Siswa')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #2f9e44;
        --brand-primary-mid:   #40c057;
        --brand-primary-light: #ebfbee;
        --brand-primary-dark:  #237032;
        --brand-success:       #0ca678;
        --brand-success-light: #e6fcf5;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-warning:       #f59f00;
        --brand-warning-light: #fff9db;
        --surface:             #ffffff;
        --surface-soft:        #f6fdf8;
        --surface-border:      #e3f0e6;
        --text-primary:        #1a1d23;
        --text-secondary:      #495057;
        --text-muted:          #8fa89b;
        --shadow-sm: 0 2px 8px rgba(47,158,68,.07);
        --shadow-md: 0 4px 16px rgba(47,158,68,.10), 0 2px 4px rgba(0,0,0,.04);
        --shadow-lg: 0 12px 36px rgba(47,158,68,.14), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 22px;
        --transition: .2s ease;
    }

    *, *::before, *::after { box-sizing: border-box; }
    body, .card, .table { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Page Hero ───────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1a6b2a 0%, #2f9e44 52%, #52c46a 100%);
        border-radius: var(--radius-xl);
        padding: 28px 36px;
        margin-bottom: 28px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 8px 32px rgba(47,158,68,.30);
        position: relative; overflow: hidden;
    }
    .page-hero::before {
        content: ''; position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,.07); border-radius: 50%; pointer-events: none;
    }
    .page-hero::after {
        content: ''; position: absolute; right: 90px; bottom: -70px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,.05); border-radius: 50%; pointer-events: none;
    }
    .hero-left { position: relative; z-index: 1; }
    .hero-icon-wrap {
        width: 50px; height: 50px;
        background: rgba(255,255,255,.18);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        font-size: 1.35rem; color: #fff;
        margin-bottom: 12px;
        border: 1px solid rgba(255,255,255,.25);
    }
    .page-hero h1 {
        font-size: 1.5rem; font-weight: 800; color: #fff;
        margin: 0 0 8px; letter-spacing: -.02em;
    }
    .hero-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .hero-chip {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 50px; padding: 4px 12px;
        color: rgba(255,255,255,.9);
        font-size: .74rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px;
        backdrop-filter: blur(6px);
    }
    .page-hero .breadcrumb {
        margin: 0; padding: 0; background: transparent; font-size: .76rem;
    }
    .page-hero .breadcrumb-item a { color: rgba(255,255,255,.75); text-decoration: none; }
    .page-hero .breadcrumb-item a:hover { color: #fff; }
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.9); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }
    .hero-right { position: relative; z-index: 1; }

    /* ── Stats Strip ────────────────────── */
    .stats-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 14px; margin-bottom: 24px;
    }
    .stat-chip {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        padding: 16px 18px;
        display: flex; align-items: center; gap: 12px;
        box-shadow: var(--shadow-sm);
        transition: transform var(--transition), box-shadow var(--transition);
        position: relative; overflow: hidden;
    }
    .stat-chip::before {
        content: ''; position: absolute; top: 0; left: 0;
        width: 3px; height: 100%; border-radius: 3px 0 0 3px;
    }
    .stat-chip.sc-green::before  { background: var(--brand-primary); }
    .stat-chip.sc-teal::before   { background: var(--brand-success); }
    .stat-chip.sc-warn::before   { background: var(--brand-warning); }
    .stat-chip.sc-danger::before { background: var(--brand-danger); }
    .stat-chip:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-chip-icon {
        width: 42px; height: 42px; border-radius: var(--radius-md);
        display: grid; place-items: center; font-size: 1.05rem; flex-shrink: 0;
    }
    .sci-green  { background: var(--brand-primary-light); color: var(--brand-primary); }
    .sci-teal   { background: var(--brand-success-light); color: var(--brand-success); }
    .sci-warn   { background: var(--brand-warning-light); color: var(--brand-warning); }
    .sci-danger { background: var(--brand-danger-light);  color: var(--brand-danger); }
    .stat-chip-label { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted); margin-bottom: 2px; }
    .stat-chip-value { font-size: 1.55rem; font-weight: 800; color: var(--text-primary); line-height: 1; letter-spacing: -.02em; }

    /* ── Filter Card ────────────────────── */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: var(--shadow-sm);
    }
    .filter-card-header {
        padding: 14px 22px;
        background: var(--surface-soft);
        border-bottom: 1px solid var(--surface-border);
        display: flex; align-items: center; gap: 10px;
    }
    .filter-card-header-icon {
        width: 34px; height: 34px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem;
        border: 1px solid rgba(47,158,68,.15);
    }
    .filter-card-title { font-size: .88rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .filter-card-body { padding: 20px 22px; }

    .form-label-pro {
        font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--text-muted); margin-bottom: 6px;
        display: block;
    }
    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .855rem; font-weight: 500;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--surface-border);
        padding: 10px 14px;
        color: var(--text-primary);
        background: var(--surface);
        transition: border-color var(--transition), box-shadow var(--transition);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(47,158,68,.12);
        outline: none;
    }
    .btn-filter {
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-mid));
        color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; border: none;
        padding: 10px 24px; border-radius: 50px;
        font-size: .845rem; cursor: pointer;
        transition: all var(--transition);
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(47,158,68,.25);
        width: 100%; justify-content: center;
    }
    .btn-filter:hover {
        background: linear-gradient(135deg, var(--brand-primary-dark), var(--brand-primary));
        box-shadow: 0 6px 18px rgba(47,158,68,.35);
        transform: translateY(-1px);
        color: #fff;
    }

    /* ── Data Card / Table ──────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    .data-card-header {
        padding: 16px 24px;
        background: linear-gradient(to right, var(--surface-soft), var(--surface));
        border-bottom: 1px solid var(--surface-border);
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .data-card-header-left { display: flex; align-items: center; gap: 12px; }
    .table-header-icon {
        width: 40px; height: 40px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1rem;
        border: 1px solid rgba(47,158,68,.15);
    }
    .table-header-title { font-size: .9rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .table-header-sub   { font-size: .7rem; color: var(--text-muted); margin: 0; font-weight: 500; }
    .result-count-badge {
        background: var(--brand-primary-light);
        color: var(--brand-primary-dark);
        border: 1px solid rgba(47,158,68,.2);
        border-radius: 50px; padding: 4px 12px;
        font-size: .75rem; font-weight: 800;
    }

    .table-pro { width: 100%; border-collapse: collapse; margin: 0; }
    .table-pro thead th {
        background: var(--surface-soft);
        color: var(--text-muted);
        font-size: .66rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .08em;
        padding: 13px 20px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody td {
        padding: 13px 20px;
        border-bottom: 1px solid #f1f7f3;
        font-size: .845rem;
        vertical-align: middle;
        color: var(--text-primary);
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }
    .table-pro tbody tr:hover { background: #f0faf2; }

    .row-num {
        width: 28px; height: 28px;
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        font-size: .7rem; font-weight: 800; color: var(--text-muted);
    }

    .date-cell { display: flex; flex-direction: column; gap: 1px; }
    .date-day   { font-weight: 700; font-size: .845rem; color: var(--text-primary); }
    .date-full  { font-size: .72rem; color: var(--text-muted); font-weight: 500; }

    .student-cell { display: flex; align-items: center; gap: 10px; }
    .student-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--brand-primary-light);
        border: 1.5px solid rgba(47,158,68,.2);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .8rem; font-weight: 800;
        flex-shrink: 0;
    }
    .student-name { font-weight: 700; color: var(--text-primary); font-size: .845rem; }

    .kelas-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--surface-soft); border: 1px solid var(--surface-border);
        border-radius: var(--radius-sm); padding: 3px 10px;
        font-size: .76rem; font-weight: 700; color: var(--text-secondary);
    }

    .jam-cell {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--brand-primary-light); border-radius: 50px;
        padding: 3px 10px; font-weight: 700; font-size: .78rem;
        color: var(--brand-primary-dark);
    }

    /* Status pills */
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 50px;
        font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em;
    }
    .sp-hadir     { background: var(--brand-primary-light); color: var(--brand-primary-dark); }
    .sp-terlambat { background: var(--brand-warning-light); color: #7c5a00; }
    .sp-alfa      { background: var(--brand-danger-light);  color: var(--brand-danger); }

    /* Empty State */
    .empty-state { text-align: center; padding: 72px 24px; }
    .empty-state-icon-wrap {
        width: 72px; height: 72px;
        background: var(--brand-primary-light);
        border-radius: 50%; margin: 0 auto 16px;
        display: grid; place-items: center;
        font-size: 1.8rem; color: var(--brand-primary);
    }
    .empty-state h6 { font-weight: 800; color: var(--text-secondary); margin-bottom: 6px; font-size: .95rem; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* Scrollbar */
    .table-responsive::-webkit-scrollbar { height: 4px; }
    .table-responsive::-webkit-scrollbar-thumb { background: rgba(47,158,68,.25); border-radius: 4px; }

    @media (max-width: 767px) {
        .page-hero { padding: 22px 22px; }
        .stats-strip { grid-template-columns: repeat(2, 1fr); }
        .hero-right { display: none; }
    }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon-wrap"><i class="bi bi-clipboard2-pulse-fill"></i></div>
        <h1>Pantau Absensi Siswa</h1>
        <div class="hero-chips">
            <span class="hero-chip"><i class="bi bi-calendar-range"></i> {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d M') }} – {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d M Y') }}</span>
            @if($statusFilter)
                <span class="hero-chip"><i class="bi bi-funnel-fill"></i> {{ ucfirst($statusFilter) }}</span>
            @endif
        </div>
    </div>
    <div class="hero-right">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">By Range &amp; Hari Ini</li>
            </ol>
        </nav>
    </div>
</div>

@php
    $totalHadir    = $absensi->filter(fn($a) => strtolower($a->status) === 'hadir')->count();
    $totalTerlambat= $absensi->filter(fn($a) => strtolower($a->status) === 'terlambat')->count();
    $totalAlfa     = $absensi->filter(fn($a) => in_array(strtolower($a->status), ['alfa','tidak hadir']))->count();
    $totalAll      = $absensi->count();
@endphp

<!-- STATS STRIP -->
<div class="stats-strip">
    <div class="stat-chip sc-green">
        <div class="stat-chip-icon sci-green"><i class="bi bi-list-check"></i></div>
        <div>
            <div class="stat-chip-label">Total</div>
            <div class="stat-chip-value">{{ $totalAll }}</div>
        </div>
    </div>
    <div class="stat-chip sc-teal">
        <div class="stat-chip-icon sci-teal"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="stat-chip-label">Hadir</div>
            <div class="stat-chip-value">{{ $totalHadir }}</div>
        </div>
    </div>
    <div class="stat-chip sc-warn">
        <div class="stat-chip-icon sci-warn"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-chip-label">Terlambat</div>
            <div class="stat-chip-value">{{ $totalTerlambat }}</div>
        </div>
    </div>
    <div class="stat-chip sc-danger">
        <div class="stat-chip-icon sci-danger"><i class="bi bi-x-circle-fill"></i></div>
        <div>
            <div class="stat-chip-label">Alfa</div>
            <div class="stat-chip-value">{{ $totalAlfa }}</div>
        </div>
    </div>
</div>

<section class="section">

    <!-- FILTER CARD -->
    <div class="filter-card">
        <div class="filter-card-header">
            <div class="filter-card-header-icon"><i class="bi bi-funnel-fill"></i></div>
            <p class="filter-card-title">Filter Data Absensi</p>
        </div>
        <div class="filter-card-body">
            <form action="{{ route('guru.absensi.hari-ini') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label-pro">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label-pro">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label-pro">Status Kehadiran</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="hadir"       {{ $statusFilter == 'hadir'       ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat"   {{ $statusFilter == 'terlambat'   ? 'selected' : '' }}>Terlambat</option>
                        <option value="tidak hadir" {{ $statusFilter == 'tidak hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-header-left">
                <div class="table-header-icon"><i class="bi bi-table"></i></div>
                <div>
                    <p class="table-header-title">Data Absensi</p>
                    <p class="table-header-sub">{{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d M Y') }} – {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d M Y') }}</p>
                </div>
            </div>
            <span class="result-count-badge">{{ $totalAll }} data</span>
        </div>

        <div class="table-responsive">
            <table class="table-pro">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="14%">Tanggal</th>
                        <th width="24%">Nama Siswa</th>
                        <th width="13%">Kelas</th>
                        <th width="10%">Jam Tap</th>
                        <th width="10%">Status</th>
                        <th width="24%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $index => $item)
                        <tr>
                            <td><div class="row-num">{{ $index + 1 }}</div></td>
                            <td>
                                <div class="date-cell">
                                    <span class="date-day">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span class="date-full">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">{{ strtoupper(substr($item->siswa->nama ?? 'S', 0, 1)) }}</div>
                                    <span class="student-name">{{ $item->siswa->nama ?? 'Siswa Terhapus' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="kelas-tag"><i class="bi bi-door-open"></i>{{ $item->siswa->kelas->nama ?? '-' }}</span>
                            </td>
                            <td>
                                @if($item->jam == '00:00:00' || !$item->jam)
                                    <span class="text-muted" style="font-size:.8rem;">—</span>
                                @else
                                    <span class="jam-cell"><i class="bi bi-clock-fill" style="font-size:.7rem;"></i>{{ $item->jam }}</span>
                                @endif
                            </td>
                            <td>
                                @php $st = strtolower($item->status); @endphp
                                @if($st == 'hadir')
                                    <span class="status-pill sp-hadir"><i class="bi bi-check-circle-fill" style="font-size:.65rem;"></i>Hadir</span>
                                @elseif($st == 'terlambat')
                                    <span class="status-pill sp-terlambat"><i class="bi bi-clock-history" style="font-size:.65rem;"></i>Terlambat</span>
                                @elseif($st == 'tidak hadir' || $st == 'alfa')
                                    <span class="status-pill sp-alfa"><i class="bi bi-x-circle-fill" style="font-size:.65rem;"></i>Tidak Hadir</span>
                                @else
                                    <span class="status-pill sp-alfa">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td style="color:var(--text-secondary);font-size:.8rem;">
                                {{ $item->keterangan ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon-wrap"><i class="bi bi-clipboard-x"></i></div>
                                    <h6>Tidak ada data absensi ditemukan</h6>
                                    <small>Sesuaikan rentang tanggal atau filter status di atas.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection