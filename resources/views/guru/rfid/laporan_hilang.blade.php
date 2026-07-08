@extends('layouts.guru')
@section('title', 'Laporan Kartu RFID Hilang')

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

    /* ── Warning Banner ─────────────────── */
    .warning-banner {
        background: #fffbeb;
        border: 1.5px solid #f59f00;
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        margin-bottom: 22px;
        display: flex; align-items: flex-start; gap: 14px;
    }
    .warning-banner-icon {
        width: 40px; height: 40px; flex-shrink: 0;
        background: var(--brand-warning-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-warning); font-size: 1.1rem;
        border: 1px solid rgba(245,159,0,.3);
    }
    .warning-banner-title { font-size: .85rem; font-weight: 800; color: #7c5a00; margin-bottom: 3px; }
    .warning-banner-text  { font-size: .78rem; color: #8a6000; line-height: 1.5; margin: 0; }
    .warning-steps { margin: 8px 0 0; padding-left: 0; list-style: none; display: flex; flex-direction: column; gap: 4px; }
    .warning-steps li {
        font-size: .76rem; color: #7c5a00; font-weight: 600;
        display: flex; align-items: center; gap: 6px;
    }
    .warning-steps li::before {
        content: ''; width: 6px; height: 6px; border-radius: 50%;
        background: var(--brand-warning); flex-shrink: 0;
    }

    /* ── Search Bar ─────────────────────── */
    .search-wrap {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        margin-bottom: 18px;
        box-shadow: var(--shadow-sm);
        display: flex; align-items: center; gap: 12px;
    }
    .search-icon {
        color: var(--brand-primary); font-size: 1.05rem; flex-shrink: 0;
    }
    .search-input {
        flex: 1; border: none; outline: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .875rem; font-weight: 500;
        color: var(--text-primary); background: transparent;
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-count {
        font-size: .72rem; font-weight: 800;
        color: var(--text-muted); white-space: nowrap;
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: 50px; padding: 3px 10px;
    }

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
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1rem;
        border: 1px solid rgba(47,158,68,.15);
        flex-shrink: 0;
    }
    .data-card-title    { font-size: .9rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .72rem; color: var(--text-muted); margin: 0; font-weight: 500; }
    .result-count-badge {
        background: var(--brand-primary-light);
        color: var(--brand-primary-dark);
        border: 1px solid rgba(47,158,68,.2);
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
    .table-pro tbody tr.hidden-row { display: none; }

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

    .rfid-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--surface-soft);
        color: var(--text-secondary);
        padding: 4px 12px; border-radius: 50px;
        font-size: .75rem; font-weight: 700; font-family: monospace; letter-spacing: 1.5px;
        border: 1px solid var(--surface-border);
    }

    .btn-report {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--brand-danger-light);
        color: var(--brand-danger);
        border: 1.5px solid rgba(224,49,49,.25);
        padding: 7px 14px;
        border-radius: 50px;
        font-size: .75rem; font-weight: 800;
        cursor: pointer;
        transition: all var(--transition);
        font-family: 'Plus Jakarta Sans', sans-serif;
        white-space: nowrap;
    }
    .btn-report:hover {
        background: var(--brand-danger);
        color: #fff;
        border-color: var(--brand-danger);
        box-shadow: 0 4px 12px rgba(224,49,49,.25);
        transform: translateY(-1px);
    }

    /* ── Empty State ────────────────────── */
    .empty-state {
        text-align: center; padding: 72px 24px;
    }
    .empty-state-icon-wrap {
        width: 72px; height: 72px;
        background: var(--brand-primary-light);
        border-radius: 50%; margin: 0 auto 16px;
        display: grid; place-items: center;
        font-size: 1.8rem; color: var(--brand-primary);
    }
    .empty-state h6 { font-weight: 800; color: var(--text-secondary); margin-bottom: 6px; font-size: .95rem; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* No-search-result (hidden by default) */
    #no-search-result { display: none; }

    /* Scrollbar */
    .table-responsive::-webkit-scrollbar { height: 4px; }
    .table-responsive::-webkit-scrollbar-thumb { background: rgba(47,158,68,.25); border-radius: 4px; }

    /* SweetAlert2 custom */
    .swal2-popup.swal-pro-popup {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        border-radius: 20px !important;
        padding: 2rem !important;
    }
    .swal2-popup.swal-pro-popup .swal2-title {
        font-size: 1.15rem !important;
        font-weight: 800 !important;
        color: var(--text-primary) !important;
    }
    .swal2-popup.swal-pro-popup .swal2-html-container {
        font-size: .855rem !important;
        color: var(--text-secondary) !important;
        line-height: 1.6 !important;
    }
    .swal2-popup.swal-pro-popup .swal2-confirm,
    .swal2-popup.swal-pro-popup .swal2-cancel {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 700 !important;
        border-radius: 50px !important;
        padding: 10px 24px !important;
        font-size: .845rem !important;
    }

    @media (max-width: 767px) {
        .page-hero { padding: 22px 22px; }
        .hero-right { display: none; }
        .warning-banner { flex-direction: column; }
    }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon-wrap"><i class="bi bi-shield-exclamation"></i></div>
        <h1>Laporan Kartu RFID Hilang</h1>
        <div class="hero-chips">
            <span class="hero-chip"><i class="bi bi-credit-card-2-front"></i> Manajemen Kartu</span>
            <span class="hero-chip"><i class="bi bi-people-fill"></i> {{ $siswas->count() }} Siswa Terdaftar</span>
        </div>
    </div>
    <div class="hero-right">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item">Kartu RFID</li>
                <li class="breadcrumb-item active">Laporkan Hilang</li>
            </ol>
        </nav>
    </div>
</div>

<!-- WARNING BANNER: panduan sebelum lapor -->
<div class="warning-banner">
    <div class="warning-banner-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <div>
        <div class="warning-banner-title">Baca sebelum melaporkan kartu hilang</div>
        <p class="warning-banner-text">Melaporkan kartu hilang akan <strong>langsung menghapus UID RFID</strong> siswa dari sistem dan notifikasi dikirim ke Admin. Pastikan langkah berikut sudah dilakukan:</p>
        <ul class="warning-steps">
            <li>Siswa sudah benar-benar kehilangan kartu fisiknya (bukan lupa bawa)</li>
            <li>Kartu tidak ditemukan setelah dicari di tas, rumah, atau lingkungan sekolah</li>
            <li>Sudah dikonfirmasi langsung kepada siswa yang bersangkutan</li>
            <li>Siswa siap menuju ruang Admin / TU untuk mendaftar kartu pengganti</li>
        </ul>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert"
     style="border-radius:var(--radius-md); font-size:.875rem; background:#e6fcf5; color:#0ca678; border:1px solid #b2f0d0;">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<section class="section">

    @if($siswas->count() > 0)

    <!-- SEARCH BAR -->
    <div class="search-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="searchInput" class="search-input" placeholder="Cari nama siswa atau kelas…" autocomplete="off">
        <span class="search-count" id="searchCount">{{ $siswas->count() }} siswa</span>
    </div>

    <!-- DATA TABLE -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-header-left">
                <div class="header-icon"><i class="bi bi-credit-card-2-front"></i></div>
                <div>
                    <p class="data-card-title">Daftar Siswa Terdaftar RFID</p>
                    <p class="data-card-subtitle">Gunakan fitur ini hanya jika kartu RFID siswa benar-benar hilang</p>
                </div>
            </div>
            <span class="result-count-badge" id="tableCount">{{ $siswas->count() }} data</span>
        </div>

        <div class="table-responsive">
            <table class="table-pro" id="siswaTable">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Nama Siswa</th>
                        <th width="15%">Kelas</th>
                        <th width="28%">UID RFID Aktif</th>
                        <th width="22%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="siswaTableBody">
                    @foreach($siswas as $index => $siswa)
                    <tr class="siswa-row"
                        data-nama="{{ strtolower($siswa->nama) }}"
                        data-kelas="{{ strtolower($siswa->kelas->nama ?? '') }}">
                        <td><div class="row-num">{{ $index + 1 }}</div></td>
                        <td>
                            <div class="student-cell">
                                <div class="student-avatar">{{ strtoupper(substr($siswa->nama ?? 'S', 0, 1)) }}</div>
                                <span class="student-name">{{ $siswa->nama }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="kelas-tag"><i class="bi bi-door-open"></i>{{ $siswa->kelas->nama ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="rfid-badge"><i class="bi bi-upc-scan"></i>{{ $siswa->rfid }}</span>
                        </td>
                        <td>
                            <form action="{{ route('guru.rfid.submit-laporan-hilang', $siswa->id) }}" method="POST" class="form-hilang">
                                @csrf
                                <button type="button" class="btn-report btn-laporkan"
                                    data-nama="{{ $siswa->nama }}"
                                    data-rfid="{{ $siswa->rfid }}"
                                    data-kelas="{{ $siswa->kelas->nama ?? '-' }}">
                                    <i class="bi bi-x-octagon-fill"></i> Laporkan Hilang
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- No result saat search kosong -->
            <div id="no-search-result" class="empty-state">
                <div class="empty-state-icon-wrap"><i class="bi bi-search"></i></div>
                <h6>Siswa tidak ditemukan</h6>
                <small>Coba ketik nama atau kelas yang berbeda.</small>
            </div>
        </div>
    </div>

    @else
    <div class="data-card">
        <div class="empty-state">
            <div class="empty-state-icon-wrap"><i class="bi bi-inbox"></i></div>
            <h6>Belum Ada Data</h6>
            <small>Saat ini tidak ada siswa di kelas Anda yang memiliki kartu RFID terdaftar.</small>
        </div>
    </div>
    @endif

</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Search ── */
    const searchInput  = document.getElementById('searchInput');
    const searchCount  = document.getElementById('searchCount');
    const tableCount   = document.getElementById('tableCount');
    const noResult     = document.getElementById('no-search-result');
    const rows         = document.querySelectorAll('.siswa-row');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            rows.forEach(row => {
                const nama  = row.dataset.nama  || '';
                const kelas = row.dataset.kelas || '';
                const match = nama.includes(q) || kelas.includes(q);
                row.classList.toggle('hidden-row', !match);
                if (match) visible++;
            });
            const label = visible + ' siswa';
            if (searchCount) searchCount.textContent = label;
            if (tableCount)  tableCount.textContent  = visible + ' data';
            if (noResult)    noResult.style.display  = visible === 0 ? 'block' : 'none';
        });
    }

    /* ── SweetAlert konfirmasi ── */
    document.querySelectorAll('.btn-laporkan').forEach(button => {
        button.addEventListener('click', function () {
            const form      = this.closest('.form-hilang');
            const namaSiswa = this.dataset.nama;
            const rfid      = this.dataset.rfid;
            const kelas     = this.dataset.kelas;

            Swal.fire({
                title: 'Laporkan Kartu Hilang?',
                html: `
                    <div style="text-align:left; background:#f6fdf8; border:1px solid #c3e6cb; border-radius:12px; padding:14px 16px; margin-bottom:14px; font-size:.83rem;">
                        <div style="font-weight:800; color:#1a1d23; margin-bottom:8px; font-size:.85rem;">Detail Siswa</div>
                        <div style="display:flex; flex-direction:column; gap:5px; color:#495057;">
                            <span><b>Nama&nbsp;&nbsp;:</b> ${namaSiswa}</span>
                            <span><b>Kelas&nbsp;&nbsp;:</b> ${kelas}</span>
                            <span><b>UID RFID :</b> <code style="background:#ebfbee; padding:2px 8px; border-radius:4px; font-size:.8rem;">${rfid}</code></span>
                        </div>
                    </div>
                    <div style="background:#fff5f5; border:1px solid #ffc9c9; border-radius:10px; padding:12px 14px; font-size:.8rem; color:#c92a2a; line-height:1.6;">
                        <b>⚠ Perhatian:</b> Tindakan ini akan <b>langsung menghapus UID RFID</b> dari sistem dan mengirim notifikasi ke Admin. Pastikan kartu benar-benar hilang.
                    </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e03131',
                cancelButtonColor: '#8fa89b',
                confirmButtonText: '<i class="bi bi-x-octagon-fill me-1"></i> Ya, Laporkan Hilang',
                cancelButtonText: 'Batal, Periksa Lagi',
                customClass: { popup: 'swal-pro-popup' },
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endsection