@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        --wa-green:            #25d366;
        --wa-green-dark:       #1da851;
        --wa-green-light:      #e8fdf0;
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
        background: linear-gradient(135deg, #075e54 0%, var(--wa-green-dark) 55%, var(--wa-green) 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(29,168,81,.3);
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
    .btn-hero-solid { background: #fff; color: var(--wa-green-dark); }
    .btn-hero-solid:hover {
        background: #f0fff5; color: #075e54;
        transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }

    /* ── Alert (untuk fallback jika JS mati) ───────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 14px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: var(--shadow-sm); margin-bottom: 16px;
    }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro-info    { background: var(--brand-info-light);    color: #1565b8; }
    .alert-pro-danger  { background: var(--brand-danger-light);  color: var(--brand-danger); }
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
        background: var(--wa-green-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--wa-green-dark); font-size: 1.1rem;
    }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

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
    .table-pro tbody tr:hover { background: #f5fff9; }
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

    /* template name */
    .tpl-name { font-weight: 700; color: var(--text-primary); font-size: .88rem; }
    .tpl-id   { font-size: .72rem; color: var(--text-muted); margin-top: 2px; }

    /* jenis badge */
    .badge-jenis {
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
        padding: 4px 10px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-rekap { background: var(--brand-primary-light); color: var(--brand-primary); }

    /* wa bubble preview */
    .wa-bubble {
        background: #dcf8c6;
        border-radius: 10px 10px 0 10px;
        padding: 8px 12px;
        font-size: .8rem;
        color: #1a1d23;
        line-height: 1.5;
        max-height: 80px;
        overflow-y: auto;
        white-space: pre-wrap;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,.1);
        font-family: 'Segoe UI', sans-serif;
    }
    .wa-bubble-wrap {
        background: #ece5dd url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' opacity='.03'%3E%3Ccircle cx='60' cy='60' r='50' fill='%23128c7e'/%3E%3C/svg%3E");
        border-radius: var(--radius-md);
        padding: 10px 12px;
        display: flex;
        justify-content: flex-end;
    }
    .wa-bubble-wrap .wa-bubble { max-width: 92%; }

    /* status badges */
    .badge-aktif {
        background: var(--wa-green-light); color: var(--wa-green-dark);
        font-size: .72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-nonaktif {
        background: var(--brand-danger-light); color: var(--brand-danger);
        font-size: .72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 4px;
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
    .mti-success { background: var(--wa-green-light);      color: var(--wa-green-dark); }

    .modal-pro .modal-body { padding: 22px 24px; }
    .modal-pro .modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    /* form labels */
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
        border-color: var(--wa-green);
        box-shadow: 0 0 0 3px rgba(37,211,102,.12);
        outline: none;
    }
    .form-select[disabled] {
        background-color: var(--surface-soft);
        opacity: 1;
        color: var(--text-secondary);
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
    .input-group:focus-within .form-select { border-color: var(--wa-green); }

    /* variable hint box */
    .var-hint {
        background: var(--brand-info-light);
        border-left: 4px solid var(--brand-info);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        margin-top: 10px;
    }
    .var-hint h6 { font-size: .78rem; font-weight: 700; color: var(--brand-info); margin: 0 0 8px; }
    .var-hint ul { margin: 0; padding-left: 16px; font-size: .8rem; color: var(--text-secondary); }
    .var-hint code {
        background: rgba(28,126,214,.1);
        color: var(--brand-info);
        padding: 1px 5px; border-radius: 4px;
        font-size: .78rem;
    }

    /* toggle switch row */
    .toggle-row {
        background: var(--surface-soft);
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        display: flex; align-items: center; gap: 12px;
        margin-top: 4px;
    }
    .toggle-row .form-check-input {
        width: 2.4em; height: 1.2em; cursor: pointer;
        border-color: var(--surface-border);
    }
    .toggle-row .form-check-input:checked { background-color: var(--wa-green); border-color: var(--wa-green); }
    .toggle-row .form-check-input:focus { box-shadow: 0 0 0 3px rgba(37,211,102,.12); }
    .toggle-row label { font-size: .855rem; font-weight: 600; color: var(--text-primary); cursor: pointer; margin: 0; }

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
    .btn-mw { background: var(--brand-warning); color: #fff; }
    .btn-mw:hover { background: #e08e00; box-shadow: 0 4px 12px rgba(245,159,0,.3); }
    .btn-mwa { background: var(--wa-green); color: #fff; }
    .btn-mwa:hover { background: var(--wa-green-dark); box-shadow: 0 4px 12px rgba(37,211,102,.3); }

    /* loading spinner in modal */
    .modal-loading {
        text-align: center; padding: 48px 24px;
    }
    .modal-loading .spinner-border {
        width: 2.8rem; height: 2.8rem;
        color: var(--wa-green);
    }
    .modal-loading p {
        margin-top: 12px; font-weight: 600;
        color: var(--text-secondary); font-size: .88rem;
    }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-whatsapp me-2" style="opacity:.9"></i>Template WhatsApp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active">Template WA</li>
            </ol>
        </nav>
    </div>
    <div class="page-hero-actions">
        {{-- Form Generate Default dengan SweetAlert --}}
        <form id="formGenerateDefault" action="{{ route('templatewa.generate') }}" method="POST" class="d-inline">
            @csrf
            <button type="button" id="btnGenerateDefault" class="btn-hero btn-hero-white">
                <i class="bi bi-magic"></i> Generate Default
            </button>
        </form>
        <button type="button" class="btn-hero btn-hero-solid" data-bs-toggle="modal" data-bs-target="#tambahTemplateModal">
            <i class="bi bi-plus-lg"></i> Tambah Template
        </button>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            {{-- Session flash messages akan ditampilkan oleh SweetAlert via script --}}
            <div id="flashData" data-success="{{ session('success') }}" data-info="{{ session('info') }}" data-error="{{ session('error') }}" data-errors="{{ json_encode($errors->all()) }}"></div>

            <div class="data-card">

                <!-- Header -->
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-chat-square-text"></i></div>
                        <div>
                            <p class="data-card-title">Manajemen Template Pesan</p>
                            <p class="data-card-subtitle">{{ count($templates) }} template terdaftar</p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table-pro">
                        <thead>
                            <tr>
                                <th style="width:4%">#</th>
                                <th style="width:20%">Nama Template</th>
                                <th style="width:12%">Jenis</th>
                                <th style="width:36%">Preview Pesan</th>
                                <th style="width:10%;text-align:center">Status</th>
                                <th style="width:14%;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $index => $template)
                            <tr>
                                <td><div class="row-num">{{ $index + 1 }}</div></td>
                                <td>
                                    <div class="tpl-name">{{ $template->nama_template }}</div>
                                    <div class="tpl-id">ID: #{{ $template->id }}</div>
                                </td>
                                <td>
                                    <span class="badge-jenis badge-rekap">
                                        <i class="bi bi-journal-text"></i>
                                        Rekap Harian
                                    </span>
                                </td>
                                <td>
                                    <div class="wa-bubble-wrap">
                                        <div class="wa-bubble">{{ $template->isi_pesan }}</div>
                                    </div>
                                </td>
                                <td style="text-align:center">
                                    @if($template->is_active)
                                        <span class="badge-aktif"><i class="bi bi-check-circle-fill"></i>Aktif</span>
                                    @else
                                        <span class="badge-nonaktif"><i class="bi bi-x-circle-fill"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-wrap">
                                        <button type="button"
                                                class="btn-act btn-act-edit btn-edit"
                                                data-url="{{ route('templatewa.edit', $template->id) }}"
                                                data-update-url="{{ route('templatewa.update', $template->id) }}"
                                                title="Edit Template">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        {{-- Form hapus dengan SweetAlert --}}
                                        <form class="form-delete" action="{{ route('templatewa.destroy', $template->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-act btn-act-delete btn-delete" title="Hapus Template">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-whatsapp"></i></div>
                                        <h6>Belum ada template WhatsApp</h6>
                                        <small>Silakan tambah secara manual atau gunakan tombol <strong>Generate Default</strong>.</small>
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


<!-- ══════════════ MODAL: TAMBAH TEMPLATE ══════════════ -->
<div class="modal fade modal-pro" id="tambahTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="mti mti-success"><i class="bi bi-whatsapp"></i></span>
                    Tambah Template WA
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahTemplate" action="{{ route('templatewa.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="flabel">Nama Template <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="nama_template" class="form-control" required
                                       placeholder="Contoh: Rekap Absensi Harian">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="flabel">Jenis Absensi <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-list-check"></i></span>
                                <select name="jenis" class="form-select" required>
                                    <option value="rekap_harian" selected>Rekap Harian (Masuk &amp; Pulang)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="flabel">Isi Pesan <span class="req">*</span></label>
                            <textarea name="isi_pesan" class="form-control" rows="6" required
                                      placeholder="Ketik format pesan WhatsApp di sini..."></textarea>
                            <div class="var-hint">
                                <h6><i class="bi bi-info-circle me-1"></i>Variabel Otomatis yang Tersedia</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <ul>
                                            <li><code>{nama_siswa}</code> — Nama Siswa</li>
                                            <li><code>{kelas}</code> — Kelas</li>
                                            <li><code>{tanggal}</code> — Tanggal (ex: 21-05-2026)</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6">
                                        <ul>
                                            <li><code>{jam_masuk}</code> — Jam Masuk (ex: 07:15)</li>
                                            <li><code>{jam_pulang}</code> — Jam Pulang (ex: 15:00)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="toggle-row">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       id="isActiveTambah" value="1" checked>
                                <label for="isActiveTambah">Aktifkan template ini segera</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mwa">
                        <i class="bi bi-send-check"></i> Simpan Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ══════════════ MODAL: EDIT TEMPLATE ══════════════ -->
<div class="modal fade modal-pro" id="editTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="mti mti-warning"><i class="bi bi-pencil-square"></i></span>
                    Edit Template WA
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Loading -->
                    <div id="editLoading" class="modal-loading d-none">
                        <div class="spinner-border" role="status"></div>
                        <p>Mengambil data template...</p>
                    </div>
                    <!-- Form content -->
                    <div id="editFormContent">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="flabel">Nama Template <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="nama_template" id="edit_nama" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="flabel">Jenis Absensi <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-list-check"></i></span>
                                    <select name="jenis" id="edit_jenis" class="form-select" required>
                                        <option value="rekap_harian">Rekap Harian (Masuk &amp; Pulang)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="flabel">Isi Pesan <span class="req">*</span></label>
                                <textarea name="isi_pesan" id="edit_pesan" class="form-control" rows="6" required></textarea>
                                <div class="var-hint">
                                    <h6><i class="bi bi-info-circle me-1"></i>Variabel Otomatis yang Tersedia</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul>
                                                <li><code>{nama_siswa}</code> — Nama Siswa</li>
                                                <li><code>{kelas}</code> — Kelas</li>
                                                <li><code>{tanggal}</code> — Tanggal (ex: 21-05-2026)</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul>
                                                <li><code>{jam_masuk}</code> — Jam Masuk (ex: 07:15)</li>
                                                <li><code>{jam_pulang}</code> — Jam Pulang (ex: 15:00)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="toggle-row">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           id="edit_is_active" value="1">
                                    <label for="edit_is_active">Aktifkan template ini</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mp" id="btnUpdate">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Tooltips Bootstrap
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
        }

        // ========== SWEETALERT UNTUK SESSION FLASH ==========
        let flashDiv = $('#flashData');
        let successMsg = flashDiv.data('success');
        let infoMsg = flashDiv.data('info');
        let errorMsg = flashDiv.data('error');
        let errorsArray = flashDiv.data('errors');

        if (successMsg) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMsg,
                confirmButtonColor: '#25d366',
                timer: 4000,
                showConfirmButton: true
            });
        }
        if (infoMsg) {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: infoMsg,
                confirmButtonColor: '#25d366'
            });
        }
        if (errorMsg) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: errorMsg,
                confirmButtonColor: '#d33'
            });
        }
        if (errorsArray && errorsArray.length > 0) {
            let errorList = '';
            $.each(errorsArray, function(i, err) {
                errorList += `<li>${err}</li>`;
            });
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `<ul style="text-align:left;">${errorList}</ul>`,
                confirmButtonColor: '#d33'
            });
        }

        // ========== KONFIRMASI HAPUS DENGAN SWEETALERT ==========
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let form = $(this).closest('.form-delete');
            Swal.fire({
                title: 'Hapus Template?',
                text: "Template yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // ========== GENERATE DEFAULT DENGAN SWEETALERT ==========
        $('#btnGenerateDefault').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Generate Default Template?',
                text: "Sistem akan membuat template rekap harian otomatis jika belum ada. Lanjutkan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#25d366',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Generate!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formGenerateDefault').submit();
                }
            });
        });

        // ========== EDIT MODAL (AJAX) ==========
        $('.btn-edit').on('click', function () {
            let fetchUrl  = $(this).data('url');
            let updateUrl = $(this).data('update-url');
            let modal = $('#editTemplateModal');

            $('#editForm').attr('action', updateUrl);
            modal.modal('show');
            $('#editFormContent').addClass('d-none');
            $('#editLoading').removeClass('d-none');
            $('#btnUpdate').prop('disabled', true);

            $.ajax({
                url: fetchUrl,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                        let data = response.data;
                        $('#edit_nama').val(data.nama_template);
                        $('#edit_jenis').val(data.jenis);
                        $('#edit_pesan').val(data.isi_pesan);
                        $('#edit_is_active').prop('checked', data.is_active == 1 || data.is_active === true);

                        $('#editLoading').addClass('d-none');
                        $('#editFormContent').removeClass('d-none');
                        $('#btnUpdate').prop('disabled', false);
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mengambil data template dari server.',
                        confirmButtonColor: '#d33'
                    });
                    modal.modal('hide');
                }
            });
        });

        // ========== NOTIFIKASI UNTUK SIMPAN/UPDATE DARI FORM BIASA ==========
        // Karena store/update menggunakan redirect biasa (bukan AJAX),
        // notifikasi sudah ditangani oleh session flash di atas.
    });
</script>
@endpush