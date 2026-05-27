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
        --brand-info:          #1c7ed6;
        --brand-info-light:    #e7f5ff;
        --surface:             #ffffff;
        --surface-soft:        #f8f9fc;
        --surface-border:      #e9ecef;
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
        background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(59,91,219,.28);
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
    .page-hero-actions { display: flex; gap: 10px; z-index: 1; }

    /* ── Hero Buttons ─────────────────────────────── */
    .btn-hero {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600; font-size: .8rem;
        border-radius: 50px; padding: 9px 20px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s ease; cursor: pointer;
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
        background: #f0f4ff; color: var(--brand-primary-dark);
        transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    /* ── Alert ────────────────────────────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 14px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: var(--shadow-sm); margin-bottom: 16px;
    }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro .btn-close { margin-left: auto; }

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

    /* ── Search bar ───────────────────────────────── */
    .search-wrap {
        padding: 14px 24px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface);
        display: flex; justify-content: flex-end;
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
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
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
    .table-pro tbody tr:hover { background: #f5f8ff; }
    .table-pro tbody td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .855rem;
        color: var(--text-primary);
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

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
    .badge-aktif {
        background: var(--brand-success-light); color: var(--brand-success);
        font-size: .72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-pending {
        background: var(--brand-warning-light); color: #b45309;
        font-size: .72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
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
    .mti { /* modal title icon */
        width: 34px; height: 34px; border-radius: var(--radius-sm);
        display: grid; place-items: center; font-size: .9rem;
    }
    .mti-primary { background: var(--brand-primary-light); color: var(--brand-primary); }
    .mti-warning { background: var(--brand-warning-light); color: var(--brand-warning); }
    .mti-info    { background: var(--brand-info-light);    color: var(--brand-info); }
    .mti-success { background: var(--brand-success-light); color: var(--brand-success); }

    .modal-pro .modal-body { padding: 22px 24px; }
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
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
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
    .import-info p  { font-size: .82rem; color: var(--brand-primary); margin: 0; }
    .import-info small { font-size: .75rem; opacity: .75; }

    /* rfid info box */
    .rfid-info {
        background: #f8f9fc;
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
    .btn-mp:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 12px rgba(59,91,219,.3); }
    .btn-ms { background: var(--brand-success); color: #fff; }
    .btn-ms:hover { background: #099268; box-shadow: 0 4px 12px rgba(12,166,120,.25); }
    .btn-mw { background: var(--brand-warning); color: #fff; }
    .btn-mw:hover { background: #e08e00; box-shadow: 0 4px 12px rgba(245,159,0,.3); }
    .btn-mi { background: var(--brand-info); color: #fff; }
    .btn-mi:hover { background: #1565b8; box-shadow: 0 4px 12px rgba(28,126,214,.3); }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-mortarboard me-2" style="opacity:.9"></i>Data Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Master Data</li>
                <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
        </nav>
    </div>
    <div class="page-hero-actions">
        <button type="button" class="btn-hero btn-hero-white" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
            <i class="bi bi-upload"></i> Import Excel
        </button>
        <button type="button" class="btn-hero btn-hero-solid" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
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
                            <p class="data-card-subtitle">{{ count($siswas) }} siswa terdaftar</p>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="search-wrap">
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
                                <th style="width:12%">NISN</th>
                                <th style="width:22%">Nama Siswa</th>
                                <th style="width:10%">Kelas</th>
                                <th style="width:14%">Orang Tua</th>
                                <th style="width:14%">RFID</th>
                                <th style="width:10%">Status</th>
                                <th style="width:14%;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr>
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
                                <td style="font-size:.82rem;color:var(--text-secondary)">
                                    {{ $siswa->orang_tua_id ?? '-' }}
                                </td>
                                <td id="rfid-{{ $siswa->id }}">
                                    @if($siswa->rfid)
                                        <span class="rfid-code">{{ $siswa->rfid }}</span>
                                    @else
                                        <span style="color:var(--text-muted);font-size:.8rem">—</span>
                                    @endif
                                </td>
                                <td class="status-cell">
                                    @if($siswa->status === 'aktif')
                                        <span class="badge-aktif"><i class="bi bi-check-circle-fill"></i>Aktif</span>
                                    @else
                                        <span class="badge-pending"><i class="bi bi-clock-fill"></i>Pending</span>
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
                                <td colspan="8">
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


<!-- ══════════════ MODAL: IMPORT ══════════════ -->
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
                            Kolom wajib: <strong>nama</strong>, <strong>kelas</strong><br>
                            <small>Kolom opsional: rfid, status (default: aktif)</small>
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


<!-- ══════════════ MODAL: SCAN RFID ══════════════ -->
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
                    <button type="submit" class="btn-modal btn-mp">
                        <i class="bi bi-check-lg"></i> Simpan RFID
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ══════════════ MODAL: TAMBAH SISWA ══════════════ -->
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
                    <div class="mb-3">
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
                    <div class="mb-3">
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


<!-- ══════════════ MODAL: EDIT SISWA ══════════════ -->
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
                        <div class="col-12">
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
        document.getElementById("searchInput").addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#siswaTable tbody tr");
            rows.forEach(row => {
                let nisn = row.cells[1]?.textContent.toLowerCase() || "";
                let nama = row.cells[2]?.textContent.toLowerCase() || "";
                row.style.display = (nisn.includes(filter) || nama.includes(filter)) ? "" : "none";
            });
        });
    </script>
@endpush