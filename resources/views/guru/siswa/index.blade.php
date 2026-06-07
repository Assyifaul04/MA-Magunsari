@extends('layouts.guru')
@section('title', 'Daftar Siswa & RFID')

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

    /* ── Page Hero ─────────────────────────── */
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
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

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

    /* ── Class Summary Table ──────────────── */
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
    .table-pro tbody tr:hover { background: #f5f8ff; }
    .table-pro tbody td,
    .table-pro tbody th {
        padding: 14px 20px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .875rem;
    }
    .table-pro tbody tr:last-child td,
    .table-pro tbody tr:last-child th { border-bottom: none; }

    .row-num {
        font-size: .72rem; font-weight: 700; color: var(--text-muted);
        width: 32px; height: 32px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    .kelas-cell { display: flex; align-items: center; gap: 12px; }
    .kelas-icon {
        width: 36px; height: 36px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem;
        flex-shrink: 0;
    }
    .kelas-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }

    .count-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--brand-primary-light);
        color: var(--brand-primary);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: .78rem; font-weight: 700;
    }

    .btn-act {
        height: 34px;
        padding: 0 14px;
        border-radius: var(--radius-sm);
        border: 1.5px solid; background: transparent;
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .78rem; font-weight: 600; cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }
    .btn-act-detail {
        border-color: #74c0fc; color: var(--brand-primary);
        background: var(--brand-primary-light);
    }
    .btn-act-detail:hover {
        background: var(--brand-primary); border-color: var(--brand-primary); color: #fff;
    }

    /* empty state */
    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* ── Modal Pro ────────────────────────── */
    .modal-pro .modal-content {
        border: none; border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg); overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .modal-pro .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--surface-border);
        background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%);
    }
    .modal-pro .modal-title {
        font-size: .95rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 10px;
    }
    .modal-pro .btn-close { filter: brightness(0) invert(1); }
    .modal-pro .modal-body { padding: 22px 24px; }
    .modal-pro .modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    /* Info profile strip */
    .profile-strip {
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
        display: flex; flex-wrap: wrap; gap: 20px;
        margin-bottom: 20px;
    }
    .profile-item { display: flex; flex-direction: column; gap: 2px; }
    .profile-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); }
    .profile-value { font-size: .875rem; font-weight: 600; color: var(--text-primary); }

    /* rfid badge */
    .rfid-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px; border-radius: var(--radius-sm);
        font-size: .78rem; font-weight: 600;
    }
    .rfid-ok { background: var(--brand-success-light); color: var(--brand-success); }
    .rfid-none { background: var(--brand-danger-light); color: var(--brand-danger); }

    /* inner table */
    .table-inner { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-inner thead th {
        background: #1a1d23;
        color: rgba(255,255,255,.85);
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        padding: 10px 14px;
        position: sticky; top: 0; z-index: 1;
    }
    .table-inner tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f3f7;
        font-size: .82rem; vertical-align: middle;
    }
    .table-inner tbody tr:last-child td { border-bottom: none; }
    .table-inner tbody tr:hover { background: #f5f8ff; }

    .status-pill {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 50px;
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
    }
    .sp-hadir   { background: var(--brand-success-light); color: var(--brand-success); }
    .sp-terlambat { background: var(--brand-warning-light); color: var(--brand-warning); }
    .sp-sakit,
    .sp-izin    { background: var(--brand-info-light); color: var(--brand-info); }
    .sp-alfa    { background: var(--brand-danger-light); color: var(--brand-danger); }
    .sp-default { background: var(--surface-soft); color: var(--text-muted); }

    .btn-modal-close {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .84rem; font-weight: 600;
        padding: 9px 20px; border-radius: 50px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s; cursor: pointer;
        background: var(--surface-border); color: var(--text-secondary);
    }
    .btn-modal-close:hover { background: #dee2e6; color: var(--text-primary); }

    /* Spinner inside button */
    .btn-loading .bi { display: none; }

    /* Section label */
    .section-label {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-secondary);
        margin-bottom: 10px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-label::after {
        content: ''; flex: 1; height: 1px; background: var(--surface-border);
    }
</style>

<div class="page-hero">
    <div>
        <h1><i class="bi bi-people-fill me-2" style="opacity:.9"></i>Siswa & RFID</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Data</li>
                <li class="breadcrumb-item active">Daftar Siswa</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @php
                // Group siswa by kelas
                $grouped = $siswas->groupBy(fn($s) => $s->kelas->nama ?? 'Tanpa Kelas');
            @endphp

            @forelse($grouped as $namaKelas => $siswasKelas)
            <div class="data-card">
                <div class="data-card-header">
                    <div class="header-icon"><i class="bi bi-door-open"></i></div>
                    <div>
                        <p class="data-card-title">{{ $namaKelas }}</p>
                        <p class="data-card-subtitle">{{ count($siswasKelas) }} siswa terdaftar</p>
                    </div>
                    <div class="ms-auto">
                        <button class="btn-act btn-act-detail btn-toggle-kelas"
                                data-kelas="{{ Str::slug($namaKelas) }}"
                                type="button">
                            <i class="bi bi-chevron-down" id="chevron-{{ Str::slug($namaKelas) }}"></i>
                            Lihat Siswa
                        </button>
                    </div>
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
                                        <div class="kelas-cell">
                                            <div class="kelas-icon"><i class="bi bi-person"></i></div>
                                            <span class="kelas-name">{{ $siswa->nama }}</span>
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
                        <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                        <h6>Belum ada data siswa</h6>
                        <small>Anda belum ditugaskan sebagai wali kelas, atau kelas belum memiliki siswa.</small>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</section>

{{-- Modal Detail Siswa --}}
<div class="modal fade modal-pro" id="modalDetailSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clock-history me-2"></i> Riwayat Absensi RFID
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

                <div class="table-responsive" style="max-height:360px; overflow-y:auto; border-radius: var(--radius-md); border: 1px solid var(--surface-border);">
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

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Toggle Kelas Body ─────────────────────────────────────
    document.querySelectorAll('.btn-toggle-kelas').forEach(btn => {
        btn.addEventListener('click', function () {
            const key   = this.dataset.kelas;
            const body  = document.getElementById('body-' + key);
            const chev  = document.getElementById('chevron-' + key);
            const open  = body.style.display === 'none';

            body.style.display = open ? 'block' : 'none';
            chev.className     = open ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
            this.style.background = open ? 'var(--brand-primary)' : '';
            this.style.color      = open ? '#fff' : '';
            this.style.borderColor = open ? 'var(--brand-primary)' : '';
        });
    });

    // ── Detail Siswa ──────────────────────────────────────────
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailSiswa'));

    document.querySelectorAll('.btn-detail-siswa').forEach(button => {
        button.addEventListener('click', function () {
            const siswaId    = this.dataset.id;
            const origInner  = this.innerHTML;
            this.innerHTML   = `<span class="spinner-border spinner-border-sm" role="status"></span> Memuat...`;
            this.disabled    = true;

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
                            if (status === 'hadir')              cls = 'sp-hadir';
                            else if (status === 'terlambat')     cls = 'sp-terlambat';
                            else if (status === 'sakit' || status === 'izin') cls = 'sp-sakit';
                            else if (status === 'alfa')          cls = 'sp-alfa';

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
                        confirmButtonColor: '#3b5bdb',
                        confirmButtonText: 'Oke',
                        borderRadius: '14px',
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
/* SweetAlert2 custom style to match design system */
.swal2-popup.swal-pro-popup {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    border-radius: 20px !important;
    padding: 2rem !important;
}
.swal2-popup.swal-pro-popup .swal2-title {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    color: #1a1d23 !important;
}
.swal2-popup.swal-pro-popup .swal2-html-container {
    font-size: .875rem !important;
    color: #6c757d !important;
}
.swal2-popup.swal-pro-popup .swal2-confirm {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 600 !important;
    border-radius: 50px !important;
    padding: 9px 24px !important;
    font-size: .84rem !important;
}
</style>

@endsection