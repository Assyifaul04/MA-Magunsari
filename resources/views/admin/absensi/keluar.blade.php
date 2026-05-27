@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #3b5bdb;
        --brand-primary-light: #eef2ff;
        --brand-primary-dark:  #2f4ac2;
        --brand-success-light: #e6fcf5;
        --brand-success:       #0ca678;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-warning-light: #fff9db;
        --brand-warning:       #f59f00;
        --surface:             #ffffff;
        --surface-soft:        #f8f9fc;
        --surface-border:      #e9ecef;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-md: 0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 20px;
    }

    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #92400e 0%, #b45309 45%, #d97706 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(180,83,9,.28);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute; right: -50px; top: -50px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute; right: 70px; bottom: -65px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .page-hero h1 {
        font-size: 1.45rem; font-weight: 700;
        color: #fff; margin: 0 0 4px;
        display: flex; align-items: center; gap: 10px;
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); text-decoration: none; }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ── Data Card ────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .data-card-header-left { display: flex; align-items: center; gap: 12px; }
    .header-icon {
        width: 42px; height: 42px;
        background: #fff9db;
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: #b45309; font-size: 1.1rem;
        flex-shrink: 0;
    }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    /* ── Filter Bar ───────────────────────── */
    .filter-bar {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        padding: 12px 24px;
        background: #fffbeb;
        border-bottom: 1px solid #fde68a;
        font-size: .82rem;
        flex-wrap: wrap;
    }
    .filter-bar-left { display: flex; align-items: center; gap: 8px; color: #92400e; }
    .filter-bar-left i { font-size: .9rem; }
    .filter-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 50px;
        background: #d97706; color: #fff;
        font-size: .72rem; font-weight: 700;
    }
    .time-chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 4px;
        font-weight: 700;
    }
    .time-chip-jam { background: #fee2e2; color: #b91c1c; }

    /* ── Table ────────────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead th {
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        padding: 13px 20px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody tr { transition: background .15s; }
    .table-pro tbody tr:hover { background: #fffbeb; }
    .table-pro tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .875rem;
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    .row-num {
        font-size: .72rem; font-weight: 700; color: var(--text-muted);
        width: 32px; height: 32px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    /* avatar — amber tint untuk pulang */
    .avatar-circle {
        width: 38px; height: 38px; border-radius: 50%;
        background: #fff9db;
        color: #b45309;
        font-size: .78rem; font-weight: 700;
        display: grid; place-items: center;
        flex-shrink: 0;
        border: 2px solid rgba(217,119,6,.15);
    }
    .student-cell { display: flex; align-items: center; gap: 10px; }
    .student-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }
    .student-sub  { font-size: .72rem; color: var(--text-muted); }

    .kelas-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: var(--radius-sm);
        background: #e0f2fe; color: #0369a1;
        font-size: .75rem; font-weight: 600;
    }

    .jam-cell { text-align: center; }
    .jam-val { font-weight: 700; color: #b45309; font-size: .9rem; }
    .jam-sub { font-size: .68rem; color: var(--text-muted); }

    .tgl-val { font-weight: 600; color: var(--text-primary); font-size: .82rem; }
    .tgl-sub { font-size: .68rem; color: var(--text-muted); }

    /* badges */
    .badge-pro {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 50px;
        font-size: .72rem; font-weight: 700; letter-spacing: .02em;
    }
    .badge-masuk              { background: #dbeafe; color: #1d4ed8; }
    .badge-pulang             { background: #fef9c3; color: #92400e; }
    .badge-pulang-tepat-waktu { background: #d1fae5; color: #065f46; }
    .badge-pulang-lebih-awal  { background: #e0f2fe; color: #075985; }
    .badge-pulang-terlambat   { background: #fef9c3; color: #92400e; }
    .badge-belum-pulang       { background: #fee2e2; color: #991b1b; }
    .badge-izin-pulang-awal   { background: #ede9fe; color: #5b21b6; }
    .badge-hadir              { background: #d1fae5; color: #065f46; }
    .badge-terlambat          { background: #fef9c3; color: #92400e; }
    .badge-alpha              { background: #fee2e2; color: #991b1b; }
    .badge-izin               { background: #e0f2fe; color: #075985; }
    .badge-sakit              { background: #ede9fe; color: #5b21b6; }
    .badge-default            { background: var(--surface-soft); color: var(--text-secondary); }

    .total-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; border-radius: 50px;
        background: #fff9db; color: #b45309;
        font-size: .8rem; font-weight: 700;
        border: 1px solid rgba(217,119,6,.2);
    }

    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div style="position:relative;z-index:1;">
        <h1><i class="bi bi-box-arrow-right" style="opacity:.9"></i>Absensi Pulang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Dashboard</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Absensi</li>
                <li class="breadcrumb-item active">Absensi Pulang</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="data-card">

                <!-- Card Header -->
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <p class="data-card-title">Data Absensi Pulang Siswa</p>
                            <p class="data-card-subtitle">Rekap absensi pulang siswa hari ini</p>
                        </div>
                    </div>
                    <div class="total-chip">
                        <i class="bi bi-database"></i>
                        Total: {{ count($absensi) }} data
                    </div>
                </div>

                <!-- Filter Bar -->
                @if ($pengaturan)
                    <div class="filter-bar">
                        <div class="filter-bar-left">
                            <i class="bi bi-funnel-fill"></i>
                            <strong>Filter Hari Ini:</strong>
                            <span>{{ \Carbon\Carbon::parse($pengaturan->tanggal)->format('d M Y') }}</span>
                            <span style="color:#d97706;">|</span>
                            <span>Jam Pulang:</span>
                            <span class="time-chip time-chip-jam">{{ $pengaturan->jam_pulang }}</span>
                        </div>
                        <span class="filter-badge">
                            <i class="bi bi-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($pengaturan->tanggal)->format('l') }}
                        </span>
                    </div>
                @endif

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table-pro">
                        <thead>
                            <tr>
                                <th style="width:5%;">#</th>
                                <th style="width:24%;">Nama Siswa</th>
                                <th style="width:12%;">Kelas</th>
                                <th style="width:16%;text-align:center;">Tanggal</th>
                                <th style="width:10%;text-align:center;">Jam</th>
                                <th style="width:12%;text-align:center;">Jenis</th>
                                <th style="width:15%;text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absensi as $i => $a)
                                @php
                                    $nama    = $a->siswa->nama ?? '';
                                    $inisial = strtoupper(substr($nama, 0, 1)) . strtoupper(substr($nama, 1, 1));

                                    $statusMap = [
                                        'pulang_tepat_waktu' => ['badge-pulang-tepat-waktu', 'check-circle-fill',        'Pulang Tepat Waktu'],
                                        'pulang_lebih_awal'  => ['badge-pulang-lebih-awal',  'arrow-up-circle-fill',     'Pulang Lebih Awal'],
                                        'pulang_terlambat'   => ['badge-pulang-terlambat',   'clock-fill',               'Pulang Terlambat'],
                                        'belum_pulang'       => ['badge-belum-pulang',        'exclamation-triangle-fill','Belum Pulang'],
                                        'izin_pulang_awal'   => ['badge-izin-pulang-awal',   'info-circle-fill',         'Izin Pulang Awal'],
                                        'hadir'              => ['badge-hadir',               'check-circle-fill',        'Hadir'],
                                        'terlambat'          => ['badge-terlambat',           'clock-fill',               'Terlambat'],
                                        'alpha'              => ['badge-alpha',               'x-circle-fill',            'Alpha'],
                                        'izin'               => ['badge-izin',                'info-circle-fill',         'Izin'],
                                        'sakit'              => ['badge-sakit',               'heart-pulse-fill',         'Sakit'],
                                    ];
                                    $statusBadge = $statusMap[$a->status] ?? ['badge-default', 'question-circle-fill', ucfirst(str_replace('_', ' ', $a->status))];

                                    $jenisBadge = $a->jenis === 'pulang'
                                        ? ['badge-pulang', 'box-arrow-right',    'Pulang']
                                        : ['badge-masuk',  'box-arrow-in-right', 'Masuk'];
                                @endphp
                                <tr>
                                    <td><div class="row-num">{{ $i + 1 }}</div></td>

                                    <td>
                                        <div class="student-cell">
                                            <div class="avatar-circle">{{ $inisial ?: 'NA' }}</div>
                                            <div>
                                                <div class="student-name">{{ $a->siswa->nama ?? '-' }}</div>
                                                <div class="student-sub">Siswa Aktif</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="kelas-badge">
                                            <i class="bi bi-door-closed" style="font-size:.7rem;"></i>
                                            {{ $a->siswa->kelas->nama ?? '-' }}
                                        </span>
                                    </td>

                                    <td style="text-align:center;">
                                        <div class="tgl-val">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</div>
                                        <div class="tgl-sub">{{ \Carbon\Carbon::parse($a->tanggal)->format('l') }}</div>
                                    </td>

                                    <td class="jam-cell">
                                        <div class="jam-val">{{ $a->jam }}</div>
                                        <div class="jam-sub">WIB</div>
                                    </td>

                                    <td style="text-align:center;">
                                        <span class="badge-pro {{ $jenisBadge[0] }}">
                                            <i class="bi bi-{{ $jenisBadge[1] }}"></i>
                                            {{ $jenisBadge[2] }}
                                        </span>
                                    </td>

                                    <td style="text-align:center;">
                                        <span class="badge-pro {{ $statusBadge[0] }}">
                                            <i class="bi bi-{{ $statusBadge[1] }}"></i>
                                            {{ $statusBadge[2] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-door-open"></i></div>
                                            <h6>Tidak Ada Data</h6>
                                            <small>Belum ada data absensi pulang untuk ditampilkan</small>
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

@endsection

@push('scripts')
    <script src="{{ asset('js/absensi.js') }}"></script>
@endpush