@extends('layouts.guru')
@section('title', 'Rekap Bulanan Kelas')

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

    /* ── Filter Card ────────────────────── */
    .filter-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: var(--shadow-sm);
    }
    .filter-card-header {
        padding: 14px 22px;
        background: var(--surface-soft);
        border-bottom: 1px solid var(--surface-border);
        display: flex; align-items: center; gap: 10px;
    }
    .filter-card-header-icon {
        width: 34px; height: 34px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem;
        border: 1px solid rgba(47,158,68,.15);
    }
    .filter-card-title { font-size: .88rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .filter-card-body { padding: 20px 22px; }

    .form-label-pro {
        font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--text-muted); margin-bottom: 6px;
        display: block;
    }
    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .855rem; font-weight: 500;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--surface-border);
        padding: 10px 14px;
        color: var(--text-primary);
        background: var(--surface);
        transition: border-color var(--transition), box-shadow var(--transition);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(47,158,68,.12);
        outline: none;
    }
    .btn-filter {
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-mid));
        color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; border: none;
        padding: 10px 24px; border-radius: 50px;
        font-size: .845rem; cursor: pointer;
        transition: all var(--transition);
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(47,158,68,.25);
        width: 100%; justify-content: center;
        text-decoration: none;
    }
    .btn-filter:hover {
        background: linear-gradient(135deg, var(--brand-primary-dark), var(--brand-primary));
        box-shadow: 0 6px 18px rgba(47,158,68,.35);
        transform: translateY(-1px);
        color: #fff;
    }
    .btn-export {
        background: var(--surface);
        color: var(--brand-primary-dark); font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; border: 1.5px solid var(--brand-primary);
        padding: 10px 24px; border-radius: 50px;
        font-size: .845rem; cursor: pointer;
        transition: all var(--transition);
        display: inline-flex; align-items: center; gap: 8px;
        width: 100%; justify-content: center;
        text-decoration: none;
    }
    .btn-export:hover {
        background: var(--brand-primary-light);
        box-shadow: 0 4px 12px rgba(47,158,68,.18);
        transform: translateY(-1px);
        color: var(--brand-primary-dark);
    }

    /* ── Data Card / Table ──────────────── */
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
    .table-header-icon {
        width: 40px; height: 40px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: 1rem;
        border: 1px solid rgba(47,158,68,.15);
    }
    .table-header-title { font-size: .9rem; font-weight: 800; color: var(--text-primary); margin: 0; }
    .table-header-sub   { font-size: .7rem; color: var(--text-muted); margin: 0; font-weight: 500; }

    /* ── Rekap Table ────────────────────── */
    .table-responsive::-webkit-scrollbar { height: 5px; }
    .table-responsive::-webkit-scrollbar-thumb { background: rgba(47,158,68,.25); border-radius: 4px; }

    .table-rekap {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }
    .table-rekap th, .table-rekap td {
        border: 1px solid var(--surface-border);
        padding: 9px 10px;
        text-align: center;
        font-size: .82rem;
    }

    /* Sticky columns */
    .table-rekap th:nth-child(1), .table-rekap td:nth-child(1) {
        position: sticky; left: 0; z-index: 2; width: 44px;
        background: var(--surface-soft);
    }
    .table-rekap th:nth-child(2), .table-rekap td:nth-child(2) {
        position: sticky; left: 44px; z-index: 2; min-width: 190px; text-align: left;
        background: var(--surface-soft);
    }
    .table-rekap tbody td:nth-child(1),
    .table-rekap tbody td:nth-child(2) { background: #fff; }
    .table-rekap tbody tr:hover td:nth-child(1),
    .table-rekap tbody tr:hover td:nth-child(2) { background: #f0faf2; }

    .table-rekap thead th {
        font-size: .66rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .06em;
        color: var(--text-muted);
        white-space: nowrap;
    }
    .table-rekap tbody tr:hover td { background: #f0faf2; }

    /* Header groups */
    .th-date-group {
        background: var(--brand-primary-light) !important;
        color: var(--brand-primary-dark) !important;
        border-color: rgba(47,158,68,.2) !important;
    }
    .th-total-group {
        background: var(--brand-warning-light) !important;
        color: #7c5a00 !important;
        border-color: rgba(245,159,0,.2) !important;
    }

    /* Cell value colors */
    .cell-h { color: var(--brand-primary-dark); font-weight: 800; }
    .cell-t { color: var(--brand-warning);      font-weight: 800; }
    .cell-a { color: var(--brand-danger);        font-weight: 800; }
    .cell-dash { color: var(--text-muted); }

    .total-hadir { color: var(--brand-primary-dark); font-weight: 800; }
    .total-alfa  { color: var(--brand-danger);        font-weight: 800; }

    .row-num {
        width: 28px; height: 28px;
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        font-size: .7rem; font-weight: 800; color: var(--text-muted);
        margin: 0 auto;
    }
    .student-name { font-weight: 700; color: var(--text-primary); font-size: .845rem; padding-left: 4px; }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px 24px; }
    .empty-state-icon-wrap {
        width: 66px; height: 66px;
        background: var(--brand-primary-light);
        border-radius: 50%; margin: 0 auto 14px;
        display: grid; place-items: center;
        font-size: 1.6rem; color: var(--brand-primary);
    }
    .empty-state h6 { font-weight: 800; color: var(--text-secondary); margin-bottom: 5px; font-size: .9rem; }
    .empty-state small { color: var(--text-muted); font-size: .78rem; }

    @media (max-width: 767px) {
        .page-hero { padding: 22px; }
        .hero-right { display: none; }
    }
</style>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon-wrap"><i class="bi bi-journal-check"></i></div>
        <h1>Rekap Absensi Bulanan</h1>
        <div class="hero-chips">
            <span class="hero-chip"><i class="bi bi-calendar-month"></i>
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
            </span>
            @foreach($kelasList as $kls)
                @if($kls->id == $kelas_id)
                    <span class="hero-chip"><i class="bi bi-door-open"></i>{{ $kls->nama }}</span>
                @endif
            @endforeach
        </div>
    </div>
    <div class="hero-right">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">Rekap Bulanan</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section">

    <!-- FILTER CARD -->
    <div class="filter-card">
        <div class="filter-card-header">
            <div class="filter-card-header-icon"><i class="bi bi-funnel-fill"></i></div>
            <p class="filter-card-title">Filter Rekap Absensi</p>
        </div>
        <div class="filter-card-body">
            <form action="{{ route('guru.absensi.rekap') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label-pro">Pilih Kelas</label>
                    <select name="kelas_id" class="form-select">
                        @foreach($kelasList as $kls)
                            <option value="{{ $kls->id }}" {{ $kelas_id == $kls->id ? 'selected' : '' }}>{{ $kls->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-pro">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-pro">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('guru.absensi.rekap.export', ['kelas_id' => $kelas_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                       class="btn-export">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="data-card">
        <div class="data-card-header">
            <div class="data-card-header-left">
                <div class="table-header-icon"><i class="bi bi-table"></i></div>
                <div>
                    <p class="table-header-title">Rekap Kehadiran Siswa</p>
                    <p class="table-header-sub">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>
                </div>
            </div>
            <span style="background:var(--brand-primary-light);color:var(--brand-primary-dark);border:1px solid rgba(47,158,68,.2);border-radius:50px;padding:4px 14px;font-size:.75rem;font-weight:800;">
                {{ $jumlahHari }} hari
            </span>
        </div>

        <div class="table-responsive">
            <table class="table-rekap">
                <thead>
                    <tr>
                        <th rowspan="2" class="th-date-group">No.</th>
                        <th rowspan="2" class="th-date-group">Nama Siswa</th>
                        <th colspan="{{ $jumlahHari }}" class="th-date-group">Tanggal (1 – {{ $jumlahHari }})</th>
                        <th colspan="2" class="th-total-group">Total</th>
                    </tr>
                    <tr>
                        @for($i = 1; $i <= $jumlahHari; $i++)
                            <th class="th-date-group" style="min-width:30px;">{{ $i }}</th>
                        @endfor
                        <th class="th-total-group">Hadir</th>
                        <th class="th-total-group">Alfa</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($rekap as $id_siswa => $row)
                        <tr>
                            <td><div class="row-num">{{ $no++ }}</div></td>
                            <td><span class="student-name">{{ $row['siswa']->nama }}</span></td>
                            @for($i = 1; $i <= $jumlahHari; $i++)
                                @php $val = $row['data'][$i]; $valLower = strtolower($val); @endphp
                                <td class="@if($valLower === 'h') cell-h @elseif($valLower === 't') cell-t @elseif($valLower === 'a') cell-a @else cell-dash @endif">
                                    {{ $val ?: '·' }}
                                </td>
                            @endfor
                            <td class="total-hadir">{{ $row['total']['hadir'] }}</td>
                            <td class="total-alfa">{{ $row['total']['alfa'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $jumlahHari + 4 }}">
                                <div class="empty-state">
                                    <div class="empty-state-icon-wrap"><i class="bi bi-clipboard-x"></i></div>
                                    <h6>Belum ada data siswa di kelas ini</h6>
                                    <small>Pilih kelas, bulan, dan tahun lalu klik Tampilkan.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection