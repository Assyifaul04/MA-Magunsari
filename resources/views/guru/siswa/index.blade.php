@extends('layouts.guru')
@section('title', 'Daftar Siswa & RFID')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #2f9e44;
        --brand-primary-light: #ebfbee;
        --brand-primary-mid:   #40c057;
        --brand-primary-dark:  #237032;
        --brand-success-light: #e6fcf5;
        --brand-success:       #0ca678;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-warning-light: #fff9db;
        --brand-warning:       #f59f00;
        --brand-info:          #1098ad;
        --brand-info-light:    #e3fafc;
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
    }

    *, *::before, *::after { box-sizing: border-box; }
    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1a6b2a 0%, #2f9e44 52%, #52c46a 100%);
        border-radius: var(--radius-xl);
        padding: 28px 36px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(47,158,68,.30);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute; right: -60px; top: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,.07); border-radius: 50%;
        pointer-events: none;
    }
    .page-hero::after {
        content: '';
        position: absolute; right: 90px; bottom: -70px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,.05); border-radius: 50%;
        pointer-events: none;
    }
    .hero-left { position: relative; z-index: 1; }
    .hero-icon-wrap {
        width: 52px; height: 52px;
        background: rgba(255,255,255,.18);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        font-size: 1.4rem; color: #fff;
        margin-bottom: 12px;
        border: 1px solid rgba(255,255,255,.25);
    }
    .page-hero h1 {
        font-size: 1.5rem; font-weight: 800;
        color: #fff; margin: 0 0 6px; letter-spacing: -.02em;
    }
    .hero-meta {
        display: flex; align-items: center; gap: 10px;
        flex-wrap: wrap;
    }
    .hero-chip {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 50px;
        padding: 4px 12px;
        color: rgba(255,255,255,.9);
        font-size: .76rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px;
        backdrop-filter: blur(6px);
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .76rem;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); text-decoration: none; }
    .page-hero .breadcrumb-item a:hover { color: #fff; }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ── Stats Strip ────────────────────────── */
    .stats-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }
    .stat-chip {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        display: flex; align-items: center; gap: 14px;
        box-shadow: var(--shadow-sm);
        transition: transform .2s, box-shadow .2s;
    }
    .stat-chip:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .stat-chip-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .sci-green  { background: var(--brand-primary-light); color: var(--brand-primary); }
    .sci-teal   { background: var(--brand-success-light); color: var(--brand-success); }
    .sci-warn   { background: var(--brand-warning-light); color: var(--brand-warning); }
    .sci-danger { background: var(--brand-danger-light);  color: var(--brand-danger); }
    .stat-chip-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted); margin-bottom: 2px; }
    .stat-chip-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); line-height: 1; letter-spacing: -.02em; }

    /* ── Data Card ────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 18px;
        transition: box-shadow .2s;
    }
    .data-card:hover { box-shadow: var(--shadow-lg); }
    .data-card-header {
        padding: 16px 24px;
        display: flex; align-items: center; gap: 14px;
        border-bottom: 1px solid var(--surface-border);
        background: linear-gradient(to right, var(--surface-soft), var(--surface));
    }
    .header-icon {
        width: 44px; height: 44px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1.1rem;
        flex-shrink: 0;
        border: 1px solid rgba(47,158,68,.15);
    }
    .data-card-title { font-size: .95rem; font-weight: 800; color: var(--text-primary); margin: 0; letter-spacing: -.01em; }
    .data-card-subtitle { font-size: .72rem; color: var(--text-muted); margin: 0; margin-top: 2px; font-weight: 500; }

    /* ── Table Pro ──────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead th {
        background: var(--surface-soft);
        color: var(--text-muted);
        font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .08em;
        padding: 12px 20px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody tr { transition: background .15s; }
    .table-pro tbody tr:hover { background: #f0faf2; }
    .table-pro tbody td {
        padding: 13px 20px;
        border-bottom: 1px solid #f1f7f3;
        vertical-align: middle;
        font-size: .845rem;
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    .row-num {
        font-size: .7rem; font-weight: 800; color: var(--text-muted);
        width: 30px; height: 30px;
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    .student-cell { display: flex; align-items: center; gap: 12px; }
    .student-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: var(--brand-primary-light);
        border: 2px solid rgba(47,158,68,.2);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem; font-weight: 800;
        flex-shrink: 0;
    }
    .student-name { font-weight: 700; color: var(--text-primary); font-size: .875rem; }

    .rfid-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 50px;
        font-size: .75rem; font-weight: 700;
        white-space: nowrap;
    }
    .rfid-ok   { background: var(--brand-primary-light); color: var(--brand-primary-dark); border: 1px solid rgba(47,158,68,.2); }
    .rfid-none { background: var(--brand-danger-light);  color: var(--brand-danger); border: 1px solid rgba(224,49,49,.15); }

    /* ── Buttons ────────────────────────── */
    .btn-act {
        height: 34px;
        padding: 0 16px;
        border-radius: 50px;
        border: 1.5px solid;
        background: transparent;
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .76rem; font-weight: 700; cursor: pointer;
        transition: all .2s; white-space: nowrap;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-act-detail {
        border-color: rgba(47,158,68,.35);
        color: var(--brand-primary);
        background: var(--brand-primary-light);
    }
    .btn-act-detail:hover {
        background: var(--brand-primary);
        border-color: var(--brand-primary);
        color: #fff;
        box-shadow: 0 4px 12px rgba(47,158,68,.25);
    }
    .btn-toggle-kelas {
        border-color: rgba(47,158,68,.35);
        color: var(--brand-primary);
        background: var(--brand-primary-light);
    }
    .btn-toggle-kelas.open {
        background: var(--brand-primary);
        border-color: var(--brand-primary);
        color: #fff;
    }

    /* ── Empty State ───────────────────── */
    .empty-state { text-align: center; padding: 64px 24px; }
    .empty-state-icon {
        font-size: 3.5rem; color: var(--brand-primary-light);
        margin-bottom: 14px;
        display: block;
    }
    .empty-state h6 { font-weight: 800; color: var(--text-secondary); margin-bottom: 6px; font-size: .95rem; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* ── Modal Pro ─────────────────────── */
    .modal-pro .modal-content {
        border: none; border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg); overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .modal-pro .modal-header {
        padding: 20px 28px;
        border-bottom: 1px solid rgba(255,255,255,.15);
        background: linear-gradient(135deg, #1a6b2a 0%, #2f9e44 55%, #52c46a 100%);
        position: relative; overflow: hidden;
    }
    .modal-pro .modal-header::before {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,.07); border-radius: 50%;
    }
    .modal-pro .modal-title {
        font-size: .98rem; font-weight: 800; color: #fff;
        display: flex; align-items: center; gap: 10px;
        position: relative; z-index: 1;
    }
    .modal-pro .btn-close { filter: brightness(0) invert(1); position: relative; z-index: 1; }
    .modal-pro .modal-body { padding: 24px 28px; }
    .modal-pro .modal-footer {
        padding: 14px 28px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    /* Profile strip */
    .profile-strip {
        background: linear-gradient(to right, var(--surface-soft), #f0faf2);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        padding: 18px 22px;
        display: flex; flex-wrap: wrap; gap: 24px;
        margin-bottom: 22px;
    }
    .profile-item { display: flex; flex-direction: column; gap: 3px; }
    .profile-label { font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted); }
    .profile-value { font-size: .875rem; font-weight: 700; color: var(--text-primary); }

    /* Section label */
    .section-label {
        font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--brand-primary);
        margin-bottom: 12px;
        display: flex; align-items: center; gap: 10px;
    }
    .section-label::after {
        content: ''; flex: 1; height: 1.5px;
        background: linear-gradient(to right, rgba(47,158,68,.25), transparent);
    }

    /* Inner table */
    .table-inner { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-inner thead th {
        background: #1e3a23;
        color: rgba(255,255,255,.85);
        font-size: .66rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .07em;
        padding: 11px 16px;
        position: sticky; top: 0; z-index: 1;
    }
    .table-inner thead th:first-child { border-radius: var(--radius-sm) 0 0 0; }
    .table-inner thead th:last-child  { border-radius: 0 var(--radius-sm) 0 0; }
    .table-inner tbody td {
        padding: 11px 16px;
        border-bottom: 1px solid #f1f7f3;
        font-size: .82rem; vertical-align: middle;
    }
    .table-inner tbody tr:last-child td { border-bottom: none; }
    .table-inner tbody tr:hover { background: #f0faf2; }

    /* Status pills */
    .status-pill {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 50px;
        font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em;
    }
    .sp-hadir     { background: var(--brand-primary-light); color: var(--brand-primary-dark); }
    .sp-terlambat { background: var(--brand-warning-light); color: #7c5a00; }
    .sp-sakit,
    .sp-izin      { background: var(--brand-info-light); color: var(--brand-info); }
    .sp-alfa      { background: var(--brand-danger-light); color: var(--brand-danger); }
    .sp-default   { background: var(--surface-soft); color: var(--text-muted); }

    /* Close button */
    .btn-modal-close {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .82rem; font-weight: 700;
        padding: 9px 22px; border-radius: 50px; border: 1.5px solid var(--surface-border);
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s; cursor: pointer;
        background: var(--surface); color: var(--text-secondary);
    }
    .btn-modal-close:hover { background: var(--surface-soft); border-color: var(--brand-primary); color: var(--brand-primary); }

    /* Scrollbar */
    .table-scroll::-webkit-scrollbar { width: 4px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(47,158,68,.25); border-radius: 4px; }
    .table-scroll::-webkit-scrollbar-track { background: transparent; }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon-wrap"><i class="bi bi-people-fill"></i></div>
        <h1>Siswa &amp; RFID</h1>
        <div class="hero-meta">
            <span class="hero-chip"><i class="bi bi-person-badge"></i> Wali Kelas</span>
            <span class="hero-chip"><i class="bi bi-calendar3"></i> {{ now()->isoFormat('D MMM Y') }}</span>
        </div>
    </div>
    <div style="position:relative;z-index:1;">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Data</li>
                <li class="breadcrumb-item active">Daftar Siswa</li>
            </ol>
        </nav>
    </div>
</div>

@php
    $grouped    = $siswas->groupBy(fn($s) => $s->kelas->nama ?? 'Tanpa Kelas');
    $totalSiswa = $siswas->count();
    $rfidTerdaftar = $siswas->filter(fn($s) => $s->rfid)->count();
    $rfidBelum    = $totalSiswa - $rfidTerdaftar;
@endphp

<!-- STATS STRIP -->
<div class="stats-strip">
    <div class="stat-chip">
        <div class="stat-chip-icon sci-green"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-chip-label">Total Siswa</div>
            <div class="stat-chip-value">{{ $totalSiswa }}</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon sci-teal"><i class="bi bi-diagram-3-fill"></i></div>
        <div>
            <div class="stat-chip-label">Jumlah Kelas</div>
            <div class="stat-chip-value">{{ $grouped->count() }}</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon sci-green"><i class="bi bi-credit-card-2-front-fill"></i></div>
        <div>
            <div class="stat-chip-label">RFID Terdaftar</div>
            <div class="stat-chip-value">{{ $rfidTerdaftar }}</div>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-chip-icon sci-danger"><i class="bi bi-x-circle-fill"></i></div>
        <div>
            <div class="stat-chip-label">Belum RFID</div>
            <div class="stat-chip-value">{{ $rfidBelum }}</div>
        </div>
    </div>
</div>

<section class="section">
    @forelse($grouped as $namaKelas => $siswasKelas)
    <div class="data-card">
        <div class="data-card-header">
            <div class="header-icon"><i class="bi bi-door-open-fill"></i></div>
            <div style="flex:1;">
                <p class="data-card-title">{{ $namaKelas }}</p>
                <p class="data-card-subtitle">
                    <i class="bi bi-people me-1"></i>{{ count($siswasKelas) }} siswa
                    &nbsp;·&nbsp;
                    <i class="bi bi-credit-card-2-front me-1"></i>{{ $siswasKelas->filter(fn($s) => $s->rfid)->count() }} RFID terdaftar
                </p>
            </div>
            <button class="btn-act btn-toggle-kelas"
                    data-kelas="{{ Str::slug($namaKelas) }}"
                    type="button">
                <i class="bi bi-chevron-down" id="chevron-{{ Str::slug($namaKelas) }}"></i>
                Lihat Siswa
            </button>
        </div>

        <div class="kelas-body" id="body-{{ Str::slug($namaKelas) }}" style="display:none;">
            <div class="table-responsive">
                <table class="table-pro">
                    <thead>
                        <tr>
                            <th style="width:6%">#</th>
                            <th style="width:38%">Nama Siswa</th>
                            <th style="width:32%">UID Kartu RFID</th>
                            <th style="width:24%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswasKelas->values() as $index => $siswa)
                        <tr>
                            <td><div class="row-num">{{ $index + 1 }}</div></td>
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</div>
                                    <span class="student-name">{{ $siswa->nama }}</span>
                                </div>
                            </td>
                            <td>
                                @if($siswa->rfid)
                                    <span class="rfid-badge rfid-ok">
                                        <i class="bi bi-credit-card-2-front-fill"></i>
                                        {{ $siswa->rfid }}
                                    </span>
                                @else
                                    <span class="rfid-badge rfid-none">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Belum Terdaftar
                                    </span>
                                @endif
                            </td>
                            <td>
                                <button type="button"
                                        class="btn-act btn-act-detail btn-detail-siswa"
                                        data-id="{{ $siswa->id }}">
                                    <i class="bi bi-clock-history"></i> Riwayat RFID
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
        <div class="data-card">
            <div class="empty-state">
                <span class="empty-state-icon"><i class="bi bi-people" style="color:var(--brand-primary-light);"></i></span>
                <h6>Belum ada data siswa</h6>
                <small>Anda belum ditugaskan sebagai wali kelas, atau kelas belum memiliki siswa.</small>
            </div>
        </div>
    @endforelse
</section>

{{-- Modal Detail Siswa --}}
<div class="modal fade modal-pro" id="modalDetailSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clock-history"></i> Riwayat Absensi RFID
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="profile-strip">
                    <div class="profile-item">
                        <span class="profile-label">Nama Siswa</span>
                        <span class="profile-value" id="modal-nama">-</span>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Kelas</span>
                        <span class="profile-value" id="modal-kelas">-</span>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">UID RFID</span>
                        <span class="profile-value" id="modal-rfid">-</span>
                    </div>
                </div>

                <div class="section-label">30 Absensi Terakhir</div>

                <div class="table-responsive table-scroll" style="max-height:360px; overflow-y:auto; border-radius: var(--radius-md); border: 1px solid var(--surface-border);">
                    <table class="table-inner">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Tap</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-riwayat-absensi">
                            <tr><td colspan="4" class="text-center text-muted py-4">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Toggle Kelas Body ─────────────────────────────────────
    document.querySelectorAll('.btn-toggle-kelas').forEach(btn => {
        btn.addEventListener('click', function () {
            const key  = this.dataset.kelas;
            const body = document.getElementById('body-' + key);
            const chev = document.getElementById('chevron-' + key);
            const open = body.style.display === 'none';

            body.style.display = open ? 'block' : 'none';
            chev.className     = open ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
            this.classList.toggle('open', open);
            this.textContent = '';
            const i = document.createElement('i');
            i.className = open ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
            this.appendChild(i);
            this.append(' ' + (open ? 'Sembunyikan' : 'Lihat Siswa'));
            chev.id = 'chevron-' + key;
        });
    });

    // ── Detail Siswa ──────────────────────────────────────────
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailSiswa'));

    document.querySelectorAll('.btn-detail-siswa').forEach(button => {
        button.addEventListener('click', function () {
            const siswaId   = this.dataset.id;
            const origInner = this.innerHTML;
            this.innerHTML  = `<span class="spinner-border spinner-border-sm" role="status"></span> Memuat...`;
            this.disabled   = true;

            fetch(`/guru/siswa/${siswaId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil data');
                    return res.json();
                })
                .then(data => {
                    document.getElementById('modal-nama').textContent  = data.siswa.nama;
                    document.getElementById('modal-kelas').textContent = data.kelas;
                    document.getElementById('modal-rfid').textContent  = data.siswa.rfid ?? 'Belum Ada';

                    const tbody = document.getElementById('tabel-riwayat-absensi');
                    tbody.innerHTML = '';

                    if (!data.absensi || data.absensi.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4" style="font-size:.82rem">Belum ada riwayat absensi RFID.</td></tr>`;
                    } else {
                        data.absensi.forEach(item => {
                            const status = (item.status ?? '').toLowerCase();
                            let cls = 'sp-default';
                            if (status === 'hadir')                      cls = 'sp-hadir';
                            else if (status === 'terlambat')             cls = 'sp-terlambat';
                            else if (status === 'sakit' || status === 'izin') cls = 'sp-sakit';
                            else if (status === 'alfa')                  cls = 'sp-alfa';

                            tbody.insertAdjacentHTML('beforeend', `
                                <tr>
                                    <td>${item.tanggal ?? '-'}</td>
                                    <td>${item.jam_masuk ?? '-'}</td>
                                    <td><span class="status-pill ${cls}">${status.toUpperCase()}</span></td>
                                    <td style="color:var(--text-secondary);font-size:.78rem">${item.keterangan ?? '-'}</td>
                                </tr>
                            `);
                        });
                    }
                    modalDetail.show();
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat mengambil data siswa. Silakan coba lagi.',
                        confirmButtonColor: '#2f9e44',
                        confirmButtonText: 'Oke',
                        customClass: {
                            popup: 'swal-pro-popup',
                            confirmButton: 'swal-pro-btn'
                        }
                    });
                })
                .finally(() => {
                    this.innerHTML = origInner;
                    this.disabled  = false;
                });
        });
    });
});
</script>

<style>
.swal2-popup.swal-pro-popup {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    border-radius: 20px !important;
    padding: 2rem !important;
}
.swal2-popup.swal-pro-popup .swal2-title {
    font-size: 1.1rem !important; font-weight: 800 !important; color: #1a1d23 !important;
}
.swal2-popup.swal-pro-popup .swal2-html-container {
    font-size: .875rem !important; color: #6c757d !important;
}
.swal2-popup.swal-pro-popup .swal2-confirm {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 700 !important; border-radius: 50px !important;
    padding: 9px 24px !important; font-size: .84rem !important;
}
</style>

@endsection