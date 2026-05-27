@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary: #3b5bdb;
        --brand-primary-light: #eef2ff;
        --brand-primary-dark: #2f4ac2;
        --brand-success: #0ca678;
        --brand-success-light: #e6fcf5;
        --brand-warning: #f59f00;
        --brand-warning-light: #fff9db;
        --brand-danger: #e03131;
        --brand-danger-light: #fff5f5;
        --surface: #ffffff;
        --surface-soft: #f8f9fc;
        --surface-border: #e9ecef;
        --text-primary: #1a1d23;
        --text-secondary: #6c757d;
        --text-muted: #adb5bd;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md: 0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --shadow-lg: 0 10px 30px rgba(0,0,0,.10), 0 4px 8px rgba(0,0,0,.06);
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 20px;
    }

    body, .section, .card, .modal-content { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Page Header ─────────────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, var(--brand-primary) 0%, #4f75ff 60%, #6c8fff 100%);
        border-radius: var(--radius-xl);
        padding: 28px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(59,91,219,.25);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.07);
        border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute;
        right: 60px; bottom: -60px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }
    .page-hero-left h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px;
    }
    .page-hero-left .breadcrumb {
        margin: 0;
        background: transparent;
        padding: 0;
        font-size: .8rem;
    }
    .page-hero-left .breadcrumb-item a,
    .page-hero-left .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero-left .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }
    .page-hero-actions { display: flex; gap: 10px; z-index: 1; }

    /* ── Buttons ─────────────────────────────────────── */
    .btn-hero {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: .8rem;
        border-radius: 50px;
        padding: 9px 20px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s ease;
        cursor: pointer;
    }
    .btn-hero-white {
        background: rgba(255,255,255,.2);
        color: #fff;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.3);
    }
    .btn-hero-white:hover {
        background: rgba(255,255,255,.3);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }
    .btn-hero-solid {
        background: #fff;
        color: var(--brand-primary);
    }
    .btn-hero-solid:hover {
        background: #f0f4ff;
        color: var(--brand-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    /* ── Alerts ──────────────────────────────────────── */
    .alert-pro {
        border: none;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        font-size: .875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: var(--shadow-sm);
    }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro-danger  { background: var(--brand-danger-light);  color: #c92a2a; }
    .alert-pro i { font-size: 1rem; }
    .alert-pro .btn-close { margin-left: auto; }

    /* ── Main Card ───────────────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .data-card-header {
        padding: 20px 28px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .data-card-header-left { display: flex; align-items: center; gap: 12px; }
    .header-icon {
        width: 42px; height: 42px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--brand-primary);
        font-size: 1.1rem;
    }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .78rem; color: var(--text-muted); margin: 0; }

    /* ── Table ───────────────────────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead tr th {
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 14px 20px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody tr {
        transition: background .15s ease;
    }
    .table-pro tbody tr:hover { background: var(--surface-soft); }
    .table-pro tbody td {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .875rem;
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    /* avatar+name cell */
    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: var(--brand-primary-light);
        display: grid; place-items: center;
        font-weight: 700;
        font-size: .85rem;
        color: var(--brand-primary);
        flex-shrink: 0;
    }
    .user-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }

    /* whatsapp badge */
    .wa-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--brand-success-light);
        color: var(--brand-success);
        font-size: .78rem;
        font-weight: 600;
        padding: 5px 11px;
        border-radius: 50px;
        text-decoration: none;
        transition: all .2s;
    }
    .wa-badge:hover {
        background: var(--brand-success);
        color: #fff;
    }

    /* address */
    .addr-text {
        color: var(--text-secondary);
        font-size: .82rem;
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* action buttons */
    .action-wrap { display: flex; align-items: center; gap: 6px; justify-content: center; }
    .btn-act {
        width: 34px; height: 34px;
        border-radius: var(--radius-sm);
        border: 1px solid;
        display: grid; place-items: center;
        font-size: .85rem;
        transition: all .2s;
        cursor: pointer;
        background: transparent;
    }
    .btn-act-edit {
        border-color: #ffd43b;
        color: #e67700;
        background: var(--brand-warning-light);
    }
    .btn-act-edit:hover { background: var(--brand-warning); border-color: var(--brand-warning); color: #fff; }
    .btn-act-delete {
        border-color: #ffa8a8;
        color: var(--brand-danger);
        background: var(--brand-danger-light);
    }
    .btn-act-delete:hover { background: var(--brand-danger); border-color: var(--brand-danger); color: #fff; }

    /* ── Modals ──────────────────────────────────────── */
    .modal-pro .modal-content {
        border: none;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }
    .modal-pro .modal-header {
        padding: 20px 28px;
        border-bottom: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }
    .modal-pro .modal-title {
        font-size: .975rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-title-icon {
        width: 34px; height: 34px;
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        font-size: .95rem;
    }
    .modal-title-icon.edit    { background: var(--brand-warning-light); color: var(--brand-warning); }
    .modal-title-icon.create  { background: var(--brand-primary-light); color: var(--brand-primary); }
    .modal-title-icon.import  { background: var(--brand-success-light); color: var(--brand-success); }

    .modal-pro .modal-body { padding: 24px 28px; }
    .modal-pro .modal-footer {
        padding: 16px 28px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    /* Form elements */
    .form-label-pro {
        font-size: .8rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 7px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .form-control-pro, .form-control {
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-sm);
        padding: 9px 13px;
        font-size: .875rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-primary);
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control-pro:focus, .form-control:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        outline: none;
    }
    .input-group .input-group-text {
        border: 1.5px solid var(--surface-border);
        background: var(--surface-soft);
        color: var(--text-secondary);
    }
    .input-group .form-control { border-left: none; }
    .input-group:focus-within .input-group-text { border-color: var(--brand-primary); }
    .input-group:focus-within .form-control { border-color: var(--brand-primary); }

    /* import info box */
    .import-info {
        background: #eff6ff;
        border-radius: var(--radius-md);
        padding: 14px 16px;
        border-left: 4px solid var(--brand-primary);
    }
    .import-info h6 { font-size: .8rem; font-weight: 700; color: var(--brand-primary); margin: 0 0 8px; }
    .import-info .col-tags { display: flex; gap: 6px; flex-wrap: wrap; }
    .col-tag {
        background: var(--brand-primary);
        color: #fff;
        font-size: .72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
        font-family: monospace;
    }

    /* modal footer buttons */
    .btn-modal {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .85rem;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 50px;
        border: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-modal-cancel { background: var(--surface-border); color: var(--text-secondary); }
    .btn-modal-cancel:hover { background: #dee2e6; color: var(--text-primary); }
    .btn-modal-primary { background: var(--brand-primary); color: #fff; }
    .btn-modal-primary:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 12px rgba(59,91,219,.3); }
    .btn-modal-success { background: var(--brand-success); color: #fff; }
    .btn-modal-success:hover { background: #099268; box-shadow: 0 4px 12px rgba(12,166,120,.3); }

    /* peta button */
    .btn-map {
        font-size: .72rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 50px;
        border: 1.5px solid var(--surface-border);
        background: var(--surface);
        color: var(--text-secondary);
        display: inline-flex; align-items: center; gap: 5px;
        transition: all .2s;
        cursor: pointer;
    }
    .btn-map:hover { border-color: var(--brand-primary); color: var(--brand-primary); background: var(--brand-primary-light); }

    /* map container */
    .map-container { border-radius: var(--radius-md); overflow: hidden; border: 1.5px solid var(--surface-border); }

    /* empty state */
    .empty-state { text-align: center; padding: 48px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 12px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); }
    .empty-state p { font-size: .85rem; color: var(--text-muted); }
</style>

<!-- ═══════════════════════════════ PAGE HERO ═══════════════════════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-people-fill me-2" style="opacity:.9"></i>Data Orang Tua</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Master Data</li>
                <li class="breadcrumb-item active">Orang Tua</li>
            </ol>
        </nav>
    </div>
    <div class="page-hero-actions">
        <button class="btn-hero btn-hero-white"
                data-bs-toggle="modal"
                data-bs-target="#modalImport">
            <i class="bi bi-upload"></i> Import Excel
        </button>
        <button class="btn-hero btn-hero-solid"
                data-bs-toggle="modal"
                data-bs-target="#modalCreate">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-pro alert-pro-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- ── Main Data Card ── -->
            <div class="data-card">
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-people"></i></div>
                        <div>
                            <p class="data-card-title">Daftar Orang Tua</p>
                            <p class="data-card-subtitle">{{ $orangTuas->count() ?? count($orangTuas) }} data terdaftar</p>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-pro datatable">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:28%">Nama</th>
                                <th style="width:22%">No WhatsApp</th>
                                <th style="width:35%">Alamat</th>
                                <th style="width:10%;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orangTuas as $i => $o)
                            <tr>
                                <td style="color:var(--text-muted);font-size:.78rem;font-weight:600;">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($o->nama, 0, 1)) }}
                                        </div>
                                        <span class="user-name">{{ $o->nama }}</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="https://wa.me/{{ $o->nomor_whatsapp }}"
                                       target="_blank"
                                       class="wa-badge">
                                        <i class="bi bi-whatsapp"></i>
                                        {{ $o->nomor_whatsapp }}
                                    </a>
                                </td>
                                <td>
                                    <span class="addr-text" title="{{ $o->alamat ?? '-' }}">
                                        {{ $o->alamat ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-wrap">
                                        <button type="button"
                                                class="btn-act btn-act-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $o->id }}"
                                                title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('orangtua.destroy', $o) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-act btn-act-delete" title="Hapus Data">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                        <h6>Belum ada data orang tua</h6>
                                        <p>Klik tombol "Tambah Data" untuk menambahkan data baru</p>
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


<!-- ═══════════════════════════════ EDIT MODALS ═══════════════════════════════ -->
@foreach($orangTuas as $o)
<div class="modal fade modal-pro" id="edit{{ $o->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('orangtua.update', $o) }}" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="modal-title-icon edit"><i class="bi bi-pencil-square"></i></span>
                    Edit Data Orang Tua
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-pro">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" class="form-control" value="{{ $o->nama }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-pro">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white border-success"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="nomor_whatsapp" class="form-control" value="{{ $o->nomor_whatsapp }}" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-pro">
                            Alamat
                            <button type="button" class="btn-map"
                                    onclick="toggleMap('mapEdit{{ $o->id }}', 'alamatEdit{{ $o->id }}')">
                                <i class="bi bi-map"></i> Pilih dari Peta
                            </button>
                        </label>
                        <div id="mapEdit{{ $o->id }}" class="map-container mb-2" style="height:0;display:none;"></div>
                        <textarea class="form-control" id="alamatEdit{{ $o->id }}" name="alamat" rows="3">{{ $o->alamat }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal btn-modal-success">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach


<!-- ═══════════════════════════════ IMPORT MODAL ═══════════════════════════════ -->
<div class="modal fade modal-pro" id="modalImport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('orangtua.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="modal-title-icon import"><i class="bi bi-upload"></i></span>
                    Import Data Orang Tua
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-pro">Upload File Excel</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    <small class="text-muted d-block mt-1">Format yang didukung: .xlsx, .xls, .csv</small>
                </div>
                <div class="import-info">
                    <h6><i class="bi bi-info-circle me-1"></i>Format Kolom Excel</h6>
                    <div class="col-tags">
                        <span class="col-tag">nama</span>
                        <span class="col-tag">nomor_whatsapp</span>
                        <span class="col-tag">alamat</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal btn-modal-success">
                    <i class="bi bi-upload"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════ CREATE MODAL ═══════════════════════════════ -->
<div class="modal fade modal-pro" id="modalCreate" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('orangtua.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="modal-title-icon create"><i class="bi bi-person-plus"></i></span>
                    Tambah Orang Tua Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-pro">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-pro">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white border-success"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="nomor_whatsapp" class="form-control" value="{{ old('nomor_whatsapp') }}" placeholder="Contoh: 628123456789" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-pro">
                            Alamat
                            <button type="button" class="btn-map"
                                    onclick="toggleMap('mapCreate', 'alamatCreate')">
                                <i class="bi bi-map"></i> Pilih dari Peta Indonesia
                            </button>
                        </label>
                        <div id="mapCreate" class="map-container mb-2" style="height:0;display:none;"></div>
                        <textarea class="form-control" id="alamatCreate" name="alamat" rows="3" placeholder="Masukkan alamat lengkap...">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn-modal btn-modal-primary">
                    <i class="bi bi-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const maps = {};
    const markers = {};

    function toggleMap(mapId, inputId) {
        const mapContainer = document.getElementById(mapId);

        if (mapContainer.style.display === "none") {
            mapContainer.style.display = "block";
            mapContainer.style.height = "250px";

            if (!maps[mapId]) {
                maps[mapId] = L.map(mapId).setView([-0.789275, 113.921327], 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(maps[mapId]);

                maps[mapId].on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    if (markers[mapId]) {
                        markers[mapId].setLatLng(e.latlng);
                    } else {
                        markers[mapId] = L.marker(e.latlng).addTo(maps[mapId]);
                    }

                    document.getElementById(inputId).value = "Mencari alamat...";

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.display_name) {
                                document.getElementById(inputId).value = data.display_name;
                            } else {
                                document.getElementById(inputId).value = "Alamat tidak ditemukan";
                            }
                        })
                        .catch(err => {
                            document.getElementById(inputId).value = "Gagal mengambil alamat";
                        });
                });
            }

            setTimeout(() => {
                maps[mapId].invalidateSize();
            }, 300);

        } else {
            mapContainer.style.display = "none";
            mapContainer.style.height = "0px";
        }
    }
</script>

@endsection