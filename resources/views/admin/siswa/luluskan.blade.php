@extends('layouts.app')

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
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-xl: 20px;
    }

    body, .section, .card, .modal-content {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Page Hero ─────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1a5c2a 0%, var(--brand-primary) 55%, #52c46a 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
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
        color: #fff; margin: 0 0 4px;
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }
    .page-hero-actions { display: flex; gap: 10px; z-index: 1; flex-wrap: wrap; }

    .btn-hero {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600; font-size: .82rem;
        border-radius: 50px; padding: 9px 22px;
        border: none; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px;
        transition: all .2s; z-index: 1; position: relative;
        background: #fff; color: var(--brand-primary);
    }
    .btn-hero:hover {
        background: #f0fdf4; color: var(--brand-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
    }
    .btn-hero-outline {
        background: rgba(255,255,255,.16);
        color: #fff;
        border: 1px solid rgba(255,255,255,.4);
        backdrop-filter: blur(6px);
    }
    .btn-hero-outline:hover {
        background: rgba(255,255,255,.26); color: #fff;
        transform: translateY(-1px);
    }

    /* ── Alert ────────────────────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 14px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        margin-bottom: 16px;
    }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro-danger { background: var(--brand-danger-light); color: var(--brand-danger); }
    .alert-pro .btn-close { margin-left: auto; }

    /* ── Data Card ─────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 24px;
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
    .data-card-body { padding: 24px; }

    .flabel {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
        color: var(--text-secondary); margin-bottom: 8px; display: block;
    }
    .flabel .req { color: var(--brand-danger); }

    .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .9rem;
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        padding: 11px 14px;
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
        background-color: var(--surface-soft);
    }
    .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(47,158,68,.12);
        outline: none;
    }

    .info-box {
        background: var(--brand-primary-light);
        border-left: 4px solid var(--brand-primary);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        margin-bottom: 20px;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .info-box i { color: var(--brand-primary-dark); font-size: 1.1rem; margin-top: 1px; }
    .info-box p { margin: 0; font-size: .82rem; color: #237032; }

    .data-card-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
        display: flex; justify-content: flex-end; gap: 10px;
    }
    .btn-modal {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .84rem; font-weight: 600;
        padding: 9px 20px; border-radius: 50px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all .2s; cursor: pointer; text-decoration: none;
    }
    .btn-mc { background: var(--surface-border); color: var(--text-secondary); }
    .btn-mc:hover { background: #dee2e6; color: var(--text-primary); }
    .btn-mp { background: var(--brand-primary); color: #fff; }
    .btn-mp:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 12px rgba(47,158,68,.3); }
    .btn-mp:disabled { opacity: .6; cursor: not-allowed; box-shadow: none; }

    .empty-hint { text-align: center; padding: 30px 10px; color: var(--text-muted); }
    .empty-hint i { font-size: 2.4rem; margin-bottom: 10px; display: block; }

    /* ── Table ───────────────── */
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
    .table-pro tbody tr:hover { background: #f0fdf4; }
    .table-pro tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .875rem;
    }

    .row-num {
        font-size: .72rem; font-weight: 700; color: var(--text-muted);
        width: 32px; height: 32px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    .siswa-cell { display: flex; align-items: center; gap: 12px; }
    .siswa-icon {
        width: 36px; height: 36px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem;
        flex-shrink: 0;
    }
    .siswa-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }

    .kelas-badge {
        background: var(--surface-soft); border: 1px solid var(--surface-border);
        padding: 5px 10px; border-radius: var(--radius-sm); font-size: .8rem;
        font-weight: 500; display: inline-flex; align-items: center; gap: 6px;
    }

    .angkatan-badge {
        background: var(--brand-primary-light); color: var(--brand-primary-dark);
        font-size: .78rem; font-weight: 700; padding: 5px 12px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 5px;
    }

    .status-pill {
        font-size: .72rem; font-weight: 700; padding: 5px 12px; border-radius: 50px;
        display: inline-flex; align-items: center; gap: 5px; text-transform: capitalize;
    }
    .status-aktif   { background: var(--brand-success-light); color: var(--brand-success); }
    .status-pending { background: var(--brand-warning-light); color: #a17c00; }

    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    .siswa-count-badge {
        background: var(--brand-primary); color: #fff; font-size: .72rem; font-weight: 700;
        padding: 4px 12px; border-radius: 50px; margin-left: auto;
    }
</style>

<div class="page-hero">
    <div>
        <h1><i class="bi bi-person-x me-2" style="opacity:.9"></i>Non-Aktifkan Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}" style="color:rgba(255,255,255,.75)">Data Siswa</a></li>
                <li class="breadcrumb-item active">Update Status Siswa</li>
            </ol>
        </nav>
    </div>
    <div class="page-hero-actions">
        <a href="{{ route('siswa.non_aktif') }}" class="btn-hero btn-hero-outline">
            <i class="bi bi-mortarboard-fill"></i> Lihat Data Alumni (Non-Aktif)
        </a>
        <a href="{{ route('siswa.index') }}" class="btn-hero">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-pro alert-pro-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="data-card">
                <div class="data-card-header">
                    <div class="header-icon"><i class="bi bi-mortarboard"></i></div>
                    <div>
                        <p class="data-card-title">Non-Aktifkan Siswa Selektif</p>
                        <p class="data-card-subtitle">Ubah status seluruh siswa pada angkatan dan kelas yang dipilih menjadi non_aktif (Alumni)</p>
                    </div>
                </div>

                <form id="luluskanForm" action="{{ route('siswa.luluskan') }}" method="POST">
                    @csrf
                    <div class="data-card-body">

                        <div class="info-box">
                            <i class="bi bi-info-circle-fill"></i>
                            <p>
                                Sistem akan memfilter dan mengubah status siswa yang berada pada <strong>Angkatan dan Kelas yang Anda pilih</strong> menjadi <strong>non_aktif</strong>. Cocok digunakan agar siswa yang tinggal kelas tidak ikut terproses.
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="angkatan" class="flabel">Pilih Angkatan <span class="req">*</span></label>
                                @if($daftarAngkatan->isEmpty())
                                    <div class="form-select text-muted" style="background:#f8f9fa;">Tidak ada data angkatan tersedia</div>
                                @else
                                    <select name="angkatan" id="angkatan" class="form-select" required>
                                        <option value="">— Pilih Angkatan —</option>
                                        @foreach($daftarAngkatan as $angkatan)
                                            <option value="{{ $angkatan }}">Angkatan {{ $angkatan }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="kelas_id" class="flabel">Pilih Kelas <span class="req">*</span></label>
                                @if($daftarKelas->isEmpty())
                                    <div class="form-select text-muted" style="background:#f8f9fa;">Tidak ada data kelas tersedia</div>
                                @else
                                    <select name="kelas_id" id="kelas_id" class="form-select" required>
                                        <option value="">— Pilih Kelas —</option>
                                        @foreach($daftarKelas as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                    </div>

                    @if($daftarAngkatan->isNotEmpty() && $daftarKelas->isNotEmpty())
                        <div class="data-card-footer">
                            <a href="{{ route('siswa.index') }}" class="btn-modal btn-mc">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn-modal btn-mp" id="submitLuluskanBtn" disabled>
                                <i class="bi bi-person-dash"></i> Non-Aktifkan Siswa Terpilih
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            <div class="data-card" id="siswaPreviewCard" style="display:none;">
                <div class="data-card-header">
                    <div class="header-icon"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <p class="data-card-title">Preview Siswa Siap Dinonaktifkan</p>
                        <p class="data-card-subtitle" id="previewSubtitle">0 siswa ditemukan berdasarkan filter</p>
                    </div>
                    <span class="siswa-count-badge" id="siswaCountBadge">0 siswa</span>
                </div>

                <div class="table-responsive">
                    <table class="table-pro">
                        <thead>
                            <tr>
                                <th style="width:6%">#</th>
                                <th style="width:34%">Nama</th>
                                <th style="width:22%">Kelas</th>
                                <th style="width:18%">Angkatan</th>
                                <th style="width:20%">Status Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTableBody">
                            @forelse($siswas as $siswa)
                                {{-- Sisipkan data attribute kelas-id dan angkatan --}}
                                <tr class="siswa-row" data-angkatan="{{ $siswa->angkatan }}" data-kelas-id="{{ $siswa->kelas_id }}" style="display:none;">
                                    <td><div class="row-num row-index">-</div></td>
                                    <td>
                                        <div class="siswa-cell">
                                            <div class="siswa-icon"><i class="bi bi-person"></i></div>
                                            <span class="siswa-name">{{ $siswa->nama }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="kelas-badge">
                                            <i class="bi bi-door-open" style="color: var(--brand-primary)"></i>
                                            {{ $siswa->kelas->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="angkatan-badge">
                                            <i class="bi bi-calendar3"></i> {{ $siswa->angkatan }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($siswa->status === 'aktif')
                                            <span class="status-pill status-aktif"><i class="bi bi-check-circle-fill"></i> Aktif</span>
                                        @else
                                            <span class="status-pill status-pending"><i class="bi bi-clock-fill"></i> Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="siswaPreviewEmpty" style="display:none;">
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-shield-lock"></i></div>
                        <h6>Tidak ada siswa yang cocok</h6>
                        <small>Pastikan Anda memilih kombinasi Angkatan dan Kelas yang memiliki siswa aktif.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAngkatan = document.getElementById('angkatan');
            const selectKelas    = document.getElementById('kelas_id');
            const previewCard    = document.getElementById('siswaPreviewCard');
            const tableBody      = document.getElementById('siswaTableBody');
            const emptyState     = document.getElementById('siswaPreviewEmpty');
            const countBadge     = document.getElementById('siswaCountBadge');
            const subtitle       = document.getElementById('previewSubtitle');
            const submitBtn      = document.getElementById('submitLuluskanBtn');
            const allRows        = tableBody ? Array.from(tableBody.querySelectorAll('.siswa-row')) : [];

            function renderPreview() {
                const angkatan = selectAngkatan ? selectAngkatan.value : '';
                const kelasId  = selectKelas ? selectKelas.value : '';

                // Sembunyikan tabel jika kedua dropdown belum terisi
                if (!angkatan || !kelasId) {
                    previewCard.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = true;
                    return;
                }

                let visibleCount = 0;
                let kelasName = selectKelas.options[selectKelas.selectedIndex].text;
                
                // Tampilkan siswa jika angkatan dan kelasnya cocok
                allRows.forEach(function (row) {
                    if (row.dataset.angkatan === String(angkatan) && row.dataset.kelasId === String(kelasId)) {
                        row.style.display = '';
                        visibleCount++;
                        row.querySelector('.row-index').textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                countBadge.textContent = visibleCount + ' Siswa Siap Dinonaktifkan';
                subtitle.textContent = visibleCount + ' siswa ditemukan (Angkatan ' + angkatan + ' - ' + kelasName + ')';

                previewCard.style.display = 'block';

                const table = previewCard.querySelector('.table-responsive');
                if (visibleCount === 0) {
                    table.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    table.style.display = 'block';
                    emptyState.style.display = 'none';
                }

                // Matikan tombol submit kalau datanya kosong
                if (submitBtn) submitBtn.disabled = visibleCount === 0;
            }

            if (selectAngkatan && selectKelas) {
                selectAngkatan.addEventListener('change', renderPreview);
                selectKelas.addEventListener('change', renderPreview);
            }

            const form = document.getElementById('luluskanForm');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                if (!selectAngkatan.value || !selectKelas.value) return; 

                e.preventDefault();

                let kelasName = selectKelas.options[selectKelas.selectedIndex].text;

                Swal.fire({
                    title: 'Non-Aktifkan Siswa Terpilih?',
                    html: 'Siswa pada <strong>Angkatan ' + selectAngkatan.value + '</strong> di <strong>' + kelasName + '</strong> akan diubah statusnya menjadi <strong>non_aktif</strong>. Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Non-Aktifkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#2f9e44',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        const btn = document.getElementById('submitLuluskanBtn');
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses…';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush