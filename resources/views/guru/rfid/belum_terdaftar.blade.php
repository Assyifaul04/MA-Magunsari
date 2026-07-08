@extends('layouts.guru')
@section('title', 'Siswa Belum Terdaftar RFID')

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
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    .stat-chip.sc-danger::before  { background: var(--brand-danger); }
    .stat-chip.sc-green::before   { background: var(--brand-primary); }
    .stat-chip:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-chip-icon {
        width: 42px; height: 42px; border-radius: var(--radius-md);
        display: grid; place-items: center; font-size: 1.05rem; flex-shrink: 0;
    }
    .sci-danger { background: var(--brand-danger-light);  color: var(--brand-danger); }
    .sci-green  { background: var(--brand-primary-light); color: var(--brand-primary); }
    .stat-chip-label { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted); margin-bottom: 2px; }
    .stat-chip-value { font-size: 1.55rem; font-weight: 800; color: var(--text-primary); line-height: 1; letter-spacing: -.02em; }

    /* ── Data Card ──────────────────────── */
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
    .header-icon {
        width: 40px; height: 40px;
        background: var(--brand-danger-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-danger); font-size: 1rem;
        border: 1px solid rgba(224,49,49,.15);
        flex-shrink: 0;
    }
    .data-card-title    { font-size: .9rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .72rem; color: var(--text-muted); margin: 0; font-weight: 500; }

    .result-count-badge {
        background: var(--brand-danger-light);
        color: var(--brand-danger);
        border: 1px solid rgba(224,49,49,.2);
        border-radius: 50px; padding: 4px 12px;
        font-size: .75rem; font-weight: 800;
        white-space: nowrap;
    }

    /* ── Table ──────────────────────────── */
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
        padding: 14px 20px;
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

    .status-alert {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--brand-danger-light);
        color: var(--brand-danger);
        padding: 4px 12px; border-radius: 50px;
        font-size: .72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .04em;
    }

    .action-hint {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--brand-warning-light);
        color: #7c5a00;
        border: 1px solid rgba(245,159,0,.2);
        padding: 4px 12px; border-radius: var(--radius-sm);
        font-size: .75rem; font-weight: 600;
    }

    /* ── Empty State (Positive) ─────────── */
    .empty-state-positive {
        text-align: center;
        padding: 72px 24px;
    }
    .empty-state-positive .icon-circle {
        width: 80px; height: 80px;
        background: var(--brand-primary-light);
        color: var(--brand-primary);
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 2rem;
        margin-bottom: 16px;
        border: 2px solid rgba(47,158,68,.15);
    }
    .empty-state-positive h5 { font-weight: 800; color: var(--text-primary); margin-bottom: 8px; font-size: 1rem; }
    .empty-state-positive p  { color: var(--text-muted); font-size: .85rem; max-width: 400px; margin: 0 auto; }

    /* Scrollbar */
    .table-responsive::-webkit-scrollbar { height: 4px; }
    .table-responsive::-webkit-scrollbar-thumb { background: rgba(47,158,68,.25); border-radius: 4px; }

    @media (max-width: 767px) {
        .page-hero { padding: 22px 22px; }
        .hero-right { display: none; }
        .stats-strip { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon-wrap"><i class="bi bi-person-vcard"></i></div>
        <h1>Siswa Belum Terdaftar RFID</h1>
        <div class="hero-chips">
            <span class="hero-chip"><i class="bi bi-exclamation-triangle-fill"></i> Perlu Tindak Lanjut</span>
            @if($siswas->count() > 0)
                <span class="hero-chip"><i class="bi bi-people-fill"></i> {{ $siswas->count() }} Siswa</span>
            @endif
        </div>
    </div>
    <div class="hero-right">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item">Kartu RFID</li>
                <li class="breadcrumb-item active">Belum Terdaftar</li>
            </ol>
        </nav>
    </div>
</div>

<!-- STATS STRIP -->
<div class="stats-strip">
    <div class="stat-chip sc-danger">
        <div class="stat-chip-icon sci-danger"><i class="bi bi-person-x-fill"></i></div>
        <div>
            <div class="stat-chip-label">Belum Terdaftar</div>
            <div class="stat-chip-value">{{ $siswas->count() }}</div>
        </div>
    </div>
    <div class="stat-chip sc-green">
        <div class="stat-chip-icon sci-green"><i class="bi bi-info-circle-fill"></i></div>
        <div>
            <div class="stat-chip-label">Tindakan</div>
            <div class="stat-chip-value" style="font-size:.9rem; padding-top:2px;">Ke Ruang TU</div>
        </div>
    </div>
</div>

<section class="section">
    <div class="data-card">
        @if($siswas->count() > 0)
            <div class="data-card-header">
                <div class="data-card-header-left">
                    <div class="header-icon"><i class="bi bi-person-x"></i></div>
                    <div>
                        <p class="data-card-title">Daftar Siswa Tanpa Kartu RFID</p>
                        <p class="data-card-subtitle">{{ $siswas->count() }} siswa di kelas Anda belum memiliki kartu RFID terdaftar</p>
                    </div>
                </div>
                <span class="result-count-badge"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $siswas->count() }} perlu tindakan</span>
            </div>
            <div class="table-responsive">
                <table class="table-pro">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Nama Lengkap</th>
                            <th width="20%">Kelas</th>
                            <th width="18%">Status Kartu</th>
                            <th width="22%">Saran Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                        <tr>
                            <td><div class="row-num">{{ $index + 1 }}</div></td>
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">{{ strtoupper(substr($siswa->nama ?? 'S', 0, 1)) }}</div>
                                    <span class="student-name">{{ $siswa->nama }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="kelas-tag">
                                    <i class="bi bi-door-open"></i>{{ $siswa->kelas->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="status-alert">
                                    <i class="bi bi-x-circle-fill" style="font-size:.65rem;"></i> Kosong
                                </span>
                            </td>
                            <td>
                                <span class="action-hint">
                                    <i class="" style="font-size:.7rem;"></i>
                                    Registrasi di ruang Admin / TU
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-positive">
                <div class="icon-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h5>Luar Biasa!</h5>
                <p>Semua siswa di kelas yang Anda ampu sudah memiliki dan terdaftar kartu RFID. Tidak ada tindakan yang diperlukan.</p>
            </div>
        @endif
    </div>
</section>
@endsection