@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-primary:       #2f9e44;
        --brand-primary-light: #e6f7ec;
        --brand-primary-dark:  #1e7e34;
        --brand-primary-soft:  #d3f9d8;
        --brand-danger:        #e03131;
        --brand-danger-light:  #fff5f5;
        --brand-info:          #0891b2;
        --brand-info-light:    #e0f2fe;
        --brand-gold:          #e08e00;
        --brand-gold-light:    #fff9db;
        --surface:             #ffffff;
        --surface-soft:        #f6faf7;
        --surface-border:      #e1ede3;
        --text-primary:        #1a1d23;
        --text-secondary:      #6c757d;
        --text-muted:          #adb5bd;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:  0 4px 12px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.05);
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-xl:  20px;
    }
    body, .section, .card { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ══════════════ HERO ══════════════ */
    .page-hero {
        background: linear-gradient(135deg, #0b3d24 0%, #1e7e34 55%, #40c463 100%);
        border-radius: var(--radius-xl);
        padding: 26px 32px; margin-bottom: 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px;
        box-shadow: 0 8px 32px rgba(30,126,52,.28);
        position: relative; overflow: hidden;
    }
    .page-hero::before { content:''; position:absolute; right:-50px; top:-50px; width:210px; height:210px; background:rgba(255,255,255,.08); border-radius:50%; }
    .page-hero::after  { content:''; position:absolute; right:80px; bottom:-70px; width:150px; height:150px; background:rgba(255,255,255,.06); border-radius:50%; }
    .page-hero-left h1 { font-size:1.45rem; font-weight:700; color:#fff; margin:0 0 4px; }
    .page-hero-left .breadcrumb { margin:0; background:transparent; padding:0; font-size:.78rem; }
    .page-hero-left .breadcrumb-item a, .page-hero-left .breadcrumb-item.active { color:rgba(255,255,255,.8); }
    .page-hero-left .breadcrumb-item + .breadcrumb-item::before { color:rgba(255,255,255,.4); }
    .page-hero-actions { display:flex; gap:10px; z-index:1; flex-wrap:wrap; }

    .btn-hero {
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; font-size:.8rem;
        border-radius:50px; padding:9px 20px; border:none; text-decoration:none;
        display:inline-flex; align-items:center; gap:6px; transition:all .2s ease; cursor:pointer;
    }
    .btn-hero-white { background:rgba(255,255,255,.18); color:#fff; backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.32); }
    .btn-hero-white:hover { background:rgba(255,255,255,.3); color:#fff; transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.15); }
    .btn-hero-solid { background:#fff; color:var(--brand-primary-dark); }
    .btn-hero-solid:hover { background:#f1fdf4; color:var(--brand-primary-dark); transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.15); }

    .alert-pro { border:none; border-radius:var(--radius-md); padding:14px 18px; font-size:.875rem; font-weight:500; display:flex; align-items:center; gap:10px; box-shadow:var(--shadow-sm); margin-bottom:16px; }
    .alert-pro-success { background:var(--brand-primary-light); color:var(--brand-primary-dark); }
    .alert-pro .btn-close { margin-left:auto; }

    /* ══════════════ CARD ══════════════ */
    .data-card { background:var(--surface); border:1px solid var(--surface-border); border-radius:var(--radius-xl); box-shadow:var(--shadow-md); overflow:hidden; }
    .data-card-header { padding:18px 24px 14px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--surface-border); background:var(--surface-soft); flex-wrap:wrap; gap:12px; }
    .data-card-header-left { display:flex; align-items:center; gap:12px; }
    .header-icon { width:42px; height:42px; background:var(--brand-primary-soft); border-radius:var(--radius-md); display:grid; place-items:center; color:var(--brand-primary-dark); font-size:1.1rem; }
    .data-card-title { font-size:1rem; font-weight:700; color:var(--text-primary); margin:0; }
    .data-card-subtitle { font-size:.75rem; color:var(--text-muted); margin:0; }
    .stat-pill { background:var(--brand-primary-light); color:var(--brand-primary-dark); font-size:.75rem; font-weight:700; padding:6px 14px; border-radius:50px; display:inline-flex; align-items:center; gap:6px; }

    /* ══════════════ FILTER / SEARCH BAR ══════════════ */
    .filter-wrap { padding:14px 24px; border-bottom:1px solid var(--surface-border); background:var(--surface); display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; }

    .search-box { position:relative; flex:1; min-width:220px; max-width:340px; }
    .search-box i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.85rem; pointer-events:none; }
    .search-box input {
        width:100%; padding:9px 14px 9px 36px; border:1.5px solid var(--surface-border); border-radius:50px;
        font-size:.8rem; font-family:'Plus Jakarta Sans',sans-serif; color:var(--text-primary); background:var(--surface-soft);
        transition: all .2s;
    }
    .search-box input:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(47,158,68,.13); outline:none; background:#fff; }
    .search-box .clear-search { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-muted); cursor:pointer; display:none; font-size:.85rem; padding:2px; }
    .search-box .clear-search:hover { color:var(--brand-danger); }

    .filter-right { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filter-wrap select {
        padding:8px 34px 8px 14px; border:1.5px solid var(--surface-border); border-radius:50px;
        font-size:.8rem; font-family:'Plus Jakarta Sans',sans-serif; color:var(--text-primary); background:var(--surface-soft);
    }
    .filter-wrap select:focus { border-color:var(--brand-primary); box-shadow:0 0 0 3px rgba(47,158,68,.13); outline:none; }

    /* ══════════════ TABLE ══════════════ */
    .table-pro { width:100%; border-collapse:separate; border-spacing:0; }
    .table-pro thead th { background:var(--surface-soft); color:var(--text-secondary); font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; padding:13px 18px; border-bottom:1px solid var(--surface-border); white-space:nowrap; }
    .table-pro tbody tr { transition:background .15s; }
    .table-pro tbody tr:hover { background:var(--surface-soft); }
    .table-pro tbody td { padding:13px 18px; border-bottom:1px solid #f1f3f7; vertical-align:middle; font-size:.855rem; color:var(--text-primary); }
    .table-pro tbody tr:last-child td { border-bottom:none; }

    .row-num { font-size:.72rem; font-weight:700; color:var(--text-muted); width:32px; height:32px; background:var(--surface-soft); border-radius:var(--radius-sm); display:grid; place-items:center; }
    .nisn-chip { font-family:monospace; font-size:.78rem; font-weight:600; background:var(--surface-soft); color:var(--text-secondary); padding:4px 10px; border-radius:50px; border:1px solid var(--surface-border); letter-spacing:.03em; }
    .student-name { display:inline-flex; align-items:center; gap:8px; font-weight:600; color:var(--text-primary); }
    .avatar { width:34px; height:34px; border-radius:50%; background:var(--brand-primary-soft); color:var(--brand-primary-dark); font-size:.8rem; font-weight:700; display:grid; place-items:center; flex-shrink:0; }
    .badge-kelas { background:var(--brand-info-light); color:var(--brand-info); font-size:.72rem; font-weight:700; padding:4px 10px; border-radius:50px; display:inline-block; }
    .badge-angkatan { background:var(--brand-primary-light); color:var(--brand-primary-dark); font-size:.72rem; font-weight:700; padding:4px 10px; border-radius:50px; display:inline-block; }
    .badge-non_aktif { 
        background: var(--brand-danger-light);
        color: var(--brand-danger);
        font-size: 1rem; 
        width: 32px; 
        height: 32px;
        border-radius: 50%; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
    }
    .rfid-code { font-family:monospace; font-size:.78rem; color:var(--text-secondary); background:var(--surface-soft); padding:3px 8px; border-radius:var(--radius-sm); border:1px solid var(--surface-border); }

    .btn-act { height:32px; padding:0 12px; border-radius:var(--radius-sm); border:1.5px solid; background:transparent; display:inline-flex; align-items:center; gap:6px; font-size:.76rem; font-weight:600; cursor:pointer; transition:all .2s; }
    .btn-act-restore { border-color:#8ce99a; color:var(--brand-primary-dark); background:var(--brand-primary-light); }
    .btn-act-restore:hover { background:var(--brand-primary); border-color:var(--brand-primary); color:#fff; }

    .empty-state { text-align:center; padding:56px 24px; }
    .empty-state-icon { font-size:3.2rem; color:var(--text-muted); margin-bottom:12px; }
    .empty-state h6 { font-weight:700; color:var(--text-secondary); margin-bottom:6px; }
    .empty-state small { color:var(--text-muted); font-size:.8rem; }

    #noSearchResults { display:none; }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div class="page-hero-left">
        <h1><i class="bi bi-mortarboard-fill me-2" style="opacity:.9"></i>Data non_aktif</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}" style="color:rgba(255,255,255,.8)">Data Siswa</a></li>
                <li class="breadcrumb-item active">non_aktif</li>
            </ol>
        </nav>
    </div>
    <div class="page-hero-actions">
        <!-- <a href="{{ route('siswa.luluskan.form') }}" class="btn-hero btn-hero-solid">
            <i class="bi bi-award"></i> Luluskan Angkatan
        </a> -->
        <a href="{{ route('siswa.index') }}" class="btn-hero btn-hero-white">
            <i class="bi bi-arrow-left"></i> Kembali ke Data Siswa
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
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-pro" style="background:var(--brand-danger-light);color:#c92a2a;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="data-card">

                <!-- Header -->
                <div class="data-card-header">
                    <div class="data-card-header-left">
                        <div class="header-icon"><i class="bi bi-mortarboard"></i></div>
                        <div>
                            <p class="data-card-title">Daftar non_aktif</p>
                            <p class="data-card-subtitle">{{ count($siswas) }} non_aktif terdaftar{{ request('angkatan') ? ' — Angkatan ' . request('angkatan') : '' }}</p>
                        </div>
                    </div>
                    <span class="stat-pill"><i class="bi bi-people-fill"></i> {{ count($siswas) }} Total</span>
                </div>

                <!-- Filter + Search -->
                <div class="filter-wrap">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="tableSearchInput" placeholder="Cari nama, NISN, atau kelas...">
                        <button type="button" class="clear-search" id="clearSearchBtn" title="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                    <div class="filter-right">
                        <form method="GET" action="{{ route('siswa.non_aktif') }}" id="filterAngkatanForm">
                            <select name="angkatan" id="angkatanSelect" onchange="document.getElementById('filterAngkatanForm').submit()">
                                <option value="">Semua Angkatan</option>
                                @foreach($daftarAngkatan as $angkatan)
                                    <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                                        Angkatan {{ $angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table-pro" id="alumniTable">
                        <thead>
                            <tr>
                                <th style="width:4%">#</th>
                                <th style="width:12%">NISN</th>
                                <th style="width:22%">Nama Siswa</th>
                                <th style="width:10%">Kelas Terakhir</th>
                                <th style="width:10%">Angkatan</th>
                                <th style="width:15%">Orang Tua</th>
                                <th style="width:13%">RFID</th>
                                <th style="width:8%;text-align:center">Status</th>
                                <th style="width:12%;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            <tr id="non_aktif-row-{{ $siswa->id }}"
                                data-search="{{ strtolower($siswa->nisn.' '.$siswa->nama.' '.($siswa->kelas->nama ?? '').' '.($siswa->orangTua->nama ?? '').' '.$siswa->rfid) }}">
                                <td><div class="row-num">{{ $index + 1 }}</div></td>
                                <td><span class="nisn-chip">{{ $siswa->nisn }}</span></td>
                                <td>
                                    <span class="student-name">
                                        <span class="avatar">{{ strtoupper(substr($siswa->nama,0,1)) }}</span>
                                        {{ $siswa->nama }}
                                    </span>
                                </td>
                                <td><span class="badge-kelas">{{ $siswa->kelas->nama ?? '-' }}</span></td>
                                <td><span class="badge-angkatan">{{ $siswa->angkatan }}</span></td>
                                <td style="font-size:.82rem;color:var(--text-secondary)">
                                    {{ $siswa->orangTua->nama ?? '-' }}
                                </td>
                                <td>
                                    @if($siswa->rfid)
                                        <span class="rfid-code">{{ $siswa->rfid }}</span>
                                    @else
                                        <span style="color:var(--text-muted);font-size:.8rem">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center">
                                    <span class="badge-non_aktif" title="Status: Non Aktif">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </span>
                                </td>
                                <td style="text-align:center">
                                    <form action="{{ route('siswa.batalkan-non_aktif', $siswa->id) }}"
                                          method="POST" class="d-inline batalkanAlumniForm">
                                        @csrf
                                        <button type="button" class="btn-act btn-act-restore batalkanAlumniBtn" title="Kembalikan menjadi siswa aktif">
                                            <i class="bi bi-arrow-counterclockwise"></i> Aktifkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-mortarboard"></i></div>
                                        <h6>Belum ada data non_aktif</h6>
                                        <small>
                                            @if(request('angkatan'))
                                                Tidak ada non_aktif untuk angkatan {{ request('angkatan') }}
                                            @else
                                                Gunakan menu <strong>Luluskan Angkatan</strong> untuk meluluskan siswa
                                            @endif
                                        </small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                            <!-- Shown when a search query matches nothing -->
                            <tr id="noSearchResults">
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-search"></i></div>
                                        <h6>Tidak ditemukan</h6>
                                        <small>Coba kata kunci lain atau hapus pencarian</small>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ═══════ Live search (client-side, filters by NISN / Nama / Kelas / Orang Tua / RFID) ═══════
            const searchInput   = document.getElementById('tableSearchInput');
            const clearBtn      = document.getElementById('clearSearchBtn');
            const noResultsRow  = document.getElementById('noSearchResults');
            const dataRows      = Array.from(document.querySelectorAll('#alumniTable tbody tr[data-search]'));

            function runSearch() {
                const q = searchInput.value.trim().toLowerCase();
                clearBtn.style.display = q ? 'inline-flex' : 'none';

                let visibleCount = 0;
                dataRows.forEach(function (row) {
                    const match = row.dataset.search.includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                noResultsRow.style.display = (q && visibleCount === 0) ? '' : 'none';
            }

            if (searchInput) {
                searchInput.addEventListener('input', runSearch);
                clearBtn.addEventListener('click', function () {
                    searchInput.value = '';
                    runSearch();
                    searchInput.focus();
                });
            }

            // ═══════ Batalkan status non_aktif ═══════
            document.querySelectorAll('.batalkanAlumniBtn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const form = btn.closest('form');
                    const row  = btn.closest('tr');
                    const nama = row?.querySelector('.student-name')?.textContent.trim() || 'siswa ini';

                    Swal.fire({
                        title: 'Kembalikan ke Aktif?',
                        html: '<strong>' + nama + '</strong> akan dikembalikan menjadi siswa aktif dan tidak lagi berstatus non_aktif.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, aktifkan lagi',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#2f9e44',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    }).then(function (result) {
                        if (!result.isConfirmed) return;

                        btn.disabled = true;
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept': 'application/json'
                            },
                            body: new FormData(form)
                        })
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function () { window.location.reload(); });
                            } else {
                                btn.disabled = false;
                                btn.innerHTML = originalHtml;
                                Swal.fire({ title: 'Gagal!', text: data.message || 'Terjadi kesalahan', icon: 'error' });
                            }
                        })
                        .catch(function () {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                            Swal.fire({ title: 'Error!', text: 'Tidak dapat terhubung ke server', icon: 'error' });
                        });
                    });
                });
            });
        });
    </script>
@endpush