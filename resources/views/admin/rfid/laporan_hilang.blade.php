@extends('layouts.app')
@section('title', 'Laporan RFID Hilang')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #2f9e44;
        --brand-primary-light: #ebfbee;
        --brand-primary-dark:  #237032;
        --brand-success-light: #e6fcf5;
        --brand-success:       #0ca678;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-warning-light: #fff9db;
        --brand-warning:       #f59f00;
        --surface:             #ffffff;
        --surface-soft:        #f8fdf9;
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

    body, .section, .card { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Page Hero ─────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1a5c2a 0%, var(--brand-primary) 55%, #52c46a 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(47,158,68,.28);
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
        color: #fff; margin: 0 0 4px; position: relative; z-index: 1;
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0;
        font-size: .78rem; position: relative; z-index: 1;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); text-decoration: none; }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    .btn-hero {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600; font-size: .82rem;
        border-radius: 50px; padding: 9px 22px;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        transition: all .2s; z-index: 1; position: relative;
        background: #fff; color: var(--brand-primary);
        text-decoration: none;
    }
    .btn-hero:hover {
        background: #f0fdf4; color: var(--brand-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    /* ── Data Card ────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .data-card-header {
        padding: 18px 24px;
        display: flex; align-items: center; gap: 12px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
        flex-wrap: wrap;
    }
    .header-icon {
        width: 42px; height: 42px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1.1rem;
        flex-shrink: 0;
    }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    /* ── Table ────────────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; margin: 0; }
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
    .table-pro tbody tr:hover { background: #f0fdf4; }
    .table-pro tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .875rem;
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    /* row number */
    .row-num {
        font-size: .72rem; font-weight: 700; color: var(--text-muted);
        width: 32px; height: 32px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    /* siswa name cell */
    .siswa-cell {
        display: flex; align-items: center; gap: 12px;
    }
    .siswa-icon {
        width: 36px; height: 36px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem;
        flex-shrink: 0;
    }
    .siswa-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }

    /* kelas badge */
    .kelas-badge {
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-size: .8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-secondary);
    }

    /* status badge */
    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .75rem; font-weight: 700;
        padding: 5px 12px;
        border-radius: 50px;
        background: #fff3bf;
        color: #e67700;
        border: 1px solid #ffe066;
    }

    /* action button */
    .btn-lanjut {
        display: inline-flex; align-items: center; gap: 6px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .78rem; font-weight: 700;
        color: var(--brand-primary);
        background: var(--brand-primary-light);
        border: 1.5px solid #8ce99a;
        border-radius: 50px;
        padding: 6px 16px;
        text-decoration: none;
        transition: all .2s ease;
        white-space: nowrap;
    }
    .btn-lanjut:hover {
        background: var(--brand-primary);
        color: #fff;
        border-color: var(--brand-primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(47,158,68,.3);
    }

    /* empty state */
    .empty-state { text-align: center; padding: 70px 20px; }
    .empty-state-icon { font-size: 3rem; color: var(--brand-primary); margin-bottom: 10px; }
    .empty-state h5 { font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
    .empty-state p { color: var(--text-secondary); font-size: .875rem; }
</style>

<div class="page-hero">
    <div>
        <h1><i class="bi bi-shield-exclamation me-2" style="opacity:.9"></i>Laporan RFID Hilang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Laporan RFID</li>
                <li class="breadcrumb-item active">RFID Hilang</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('siswa.index') }}" class="btn-hero">
        <i class="bi bi-credit-card"></i> Buka Data Siswa
    </a>
</div>

<section class="section">
    <div class="data-card">
        @if($siswas->count() > 0)
            <div class="data-card-header">
                <div class="header-icon"><i class="bi bi-person-x"></i></div>
                <div>
                    <p class="data-card-title">Daftar Siswa Perlu Kartu Baru</p>
                    <p class="data-card-subtitle">{{ $siswas->count() }} siswa membutuhkan kartu RFID baru &mdash; klik <strong>Lanjut</strong> untuk scan kartu.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-pro">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:32%">Nama Lengkap</th>
                            <th style="width:25%">Kelas</th>
                            <th style="width:22%">Status</th>
                            <th style="width:16%; text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                        <tr>
                            <td><div class="row-num">{{ $index + 1 }}</div></td>
                            <td>
                                <div class="siswa-cell">
                                    <div class="siswa-icon"><i class="bi bi-person"></i></div>
                                    <span class="siswa-name">{{ $siswa->nama }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="kelas-badge">
                                    <i class="bi bi-door-open" style="color:var(--brand-primary)"></i>
                                    {{ $siswa->kelas->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Kartu Hilang
                                </span>
                            </td>
                            <td style="text-align:center">
                                <a href="{{ route('siswa.index', ['highlight_id' => $siswa->id, 'from_laporan' => 1]) }}"
                                   class="btn-lanjut"
                                   title="Scan RFID untuk {{ $siswa->nama }}">
                                    <i class="bi bi-arrow-right-circle"></i> Lanjut
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-check-circle-fill"></i></div>
                <h5>Semua Aman!</h5>
                <p>Tidak ada siswa yang kehilangan kartu RFID saat ini.</p>
            </div>
        @endif
    </div>
</section>
@endsection