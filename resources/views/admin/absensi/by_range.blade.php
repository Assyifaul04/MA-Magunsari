@extends('layouts.app')

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
        pointer-events: none;
    }
    .page-hero::after {
        content: '';
        position: absolute; right: 70px; bottom: -65px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.05); border-radius: 50%;
        pointer-events: none;
    }
    .page-hero h1 {
        font-size: 1.45rem; font-weight: 700;
        color: #fff; margin: 0 0 4px;
        display: flex; align-items: center; gap: .5rem;
    }
    .page-hero .breadcrumb {
        margin: 0; background: transparent; padding: 0; font-size: .78rem;
    }
    .page-hero .breadcrumb-item a,
    .page-hero .breadcrumb-item { color: rgba(255,255,255,.75); text-decoration: none; }
    .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.95); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* hero badge */
    .hero-count {
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        color: #fff;
        border-radius: 50px;
        padding: 6px 18px;
        font-size: .8rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
        backdrop-filter: blur(4px);
        z-index: 1; position: relative;
    }

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
        flex-wrap: wrap;
    }
    .header-icon {
        width: 42px; height: 42px;
        border-radius: var(--radius-md);
        display: grid; place-items: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .header-icon-primary { background: var(--brand-primary-light); color: var(--brand-primary); }
    .header-icon-success { background: var(--brand-success-light); color: var(--brand-success); }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    /* ── Filter Section ───────────────────── */
    .filter-body { padding: 20px 24px; }

    .flabel {
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--text-secondary); margin-bottom: 6px; display: block;
    }
    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .875rem;
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        padding: 9px 13px;
        transition: border-color .2s, box-shadow .2s;
        background-color: var(--surface);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        outline: none;
    }

    /* action buttons in filter */
    .btn-filter {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .8rem; font-weight: 600;
        padding: 9px 18px; border-radius: 50px; border: none;
        display: inline-flex; align-items: center; gap: 6px;
        cursor: pointer; transition: all .2s; white-space: nowrap;
    }
    .btn-filter-primary { background: var(--brand-primary); color: #fff; }
    .btn-filter-primary:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 12px rgba(59,91,219,.3); transform: translateY(-1px); }
    .btn-filter-outline { background: var(--surface); color: var(--text-secondary); border: 1.5px solid var(--surface-border) !important; }
    .btn-filter-outline:hover { background: var(--surface-soft); color: var(--text-primary); border-color: #ced4da !important; }
    .btn-filter-success { background: var(--brand-success); color: #fff; }
    .btn-filter-success:hover { background: #099268; box-shadow: 0 4px 12px rgba(12,166,120,.3); transform: translateY(-1px); }
    .btn-filter-info { background: var(--brand-info); color: #fff; }
    .btn-filter-info:hover { background: #0c8599; box-shadow: 0 4px 12px rgba(16,152,173,.3); transform: translateY(-1px); }
    .btn-filter:active { transform: translateY(0); }

    /* ── Alert Pro ────────────────────────── */
    .alert-pro {
        border: none; border-radius: var(--radius-md);
        padding: 12px 18px; font-size: .875rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        margin: 0 24px 0;
    }
    .alert-pro-info { background: var(--brand-info-light); color: var(--brand-info); }
    .alert-pro .btn-close { margin-left: auto; }

    /* ── Table ────────────────────────────── */
    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead th {
        background: var(--surface-soft);
        color: var(--text-secondary);
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        padding: 13px 16px;
        border-bottom: 1px solid var(--surface-border);
        white-space: nowrap;
    }
    .table-pro tbody tr { transition: background .15s; }
    .table-pro tbody tr:hover { background: #f5f8ff; }
    .table-pro tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f3f7;
        vertical-align: middle;
        font-size: .84rem;
        color: var(--text-primary);
    }
    .table-pro tbody tr:last-child td { border-bottom: none; }

    /* row number */
    .row-num {
        font-size: .7rem; font-weight: 700; color: var(--text-muted);
        width: 28px; height: 28px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    /* date cell */
    .date-cell { font-weight: 600; color: var(--text-primary); font-size: .8rem; }
    .date-cell small { font-weight: 400; color: var(--text-muted); display: block; font-size: .72rem; }

    /* student cell */
    .student-cell { display: flex; align-items: center; gap: 10px; }
    .student-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--brand-primary-light);
        color: var(--brand-primary);
        font-size: .7rem; font-weight: 700;
        display: grid; place-items: center;
        flex-shrink: 0; text-transform: uppercase;
    }
    .student-name { font-weight: 600; font-size: .84rem; color: var(--text-primary); }
    .student-rfid { font-size: .72rem; color: var(--text-muted); font-family: monospace; }

    /* kelas badge */
    .kelas-pill {
        display: inline-flex; align-items: center; gap: 4px;
        background: var(--brand-primary-light); color: var(--brand-primary);
        border-radius: 50px; padding: 3px 10px;
        font-size: .72rem; font-weight: 700;
    }

    /* jenis badge */
    .jenis-pill {
        display: inline-flex; align-items: center; gap: 4px;
        border-radius: 50px; padding: 4px 10px;
        font-size: .72rem; font-weight: 700; text-transform: capitalize;
    }
    .jenis-masuk  { background: #e6fcf5; color: #087f5b; }
    .jenis-pulang { background: #e3fafc; color: #0c8599; }
    .jenis-izin   { background: #fff9db; color: #856404; }

    /* status badge */
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 50px; padding: 4px 12px;
        font-size: .72rem; font-weight: 700;
    }
    .status-hadir      { background: #e6fcf5; color: #087f5b; }
    .status-terlambat  { background: #fff9db; color: #856404; }
    .status-pulang     { background: #e3fafc; color: #0c8599; }
    .status-izin       { background: var(--brand-info-light); color: var(--brand-info); }
    .status-sakit      { background: var(--brand-primary-light); color: var(--brand-primary); }
    .status-tidak_hadir{ background: var(--brand-danger-light); color: var(--brand-danger); }

    /* jam cell */
    .jam-cell {
        font-family: 'Courier New', monospace;
        font-weight: 700; font-size: .8rem;
        color: var(--text-primary);
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        padding: 3px 8px; display: inline-block;
    }

    /* empty state */
    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* date range label in table header */
    .range-label {
        font-size: .78rem; font-weight: 500;
        color: var(--text-muted); margin-left: 8px;
    }

    @media (max-width: 768px) {
        .page-hero { flex-direction: column; align-items: flex-start; gap: 12px; padding: 20px 20px; }
        .filter-body { padding: 16px; }
        .data-card-header { padding: 14px 16px; }
    }
</style>

<!-- ══════════════ PAGE HERO ══════════════ -->
<div class="page-hero">
    <div style="position:relative;z-index:1;">
        <h1><i class="bi bi-calendar-range" style="opacity:.9"></i> Data Absensi By Range</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">By Range</li>
            </ol>
        </nav>
    </div>
    <div class="hero-count" style="z-index:1;">
        <i class="bi bi-table"></i>
        {{ $absensi->count() }} Data Ditemukan
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <!-- ── Filter Card ── -->
            <div class="data-card">
                <div class="data-card-header">
                    <div class="header-icon header-icon-primary"><i class="bi bi-funnel"></i></div>
                    <div>
                        <p class="data-card-title">Filter Data Absensi</p>
                        <p class="data-card-subtitle">Saring data berdasarkan rentang waktu, kelas, atau status</p>
                    </div>
                </div>

                <div class="filter-body">
                    <form method="GET" action="{{ route('absensi.byRange') }}" id="filterForm">
                        <div class="row g-3">

                            <div class="col-md-6 col-lg-3">
                                <label class="flabel"><i class="bi bi-calendar-event me-1"></i>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                       class="form-control"
                                       value="{{ request('tanggal_mulai') }}"
                                       max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <label class="flabel"><i class="bi bi-calendar-check me-1"></i>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                       class="form-control"
                                       value="{{ request('tanggal_selesai') }}"
                                       max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <label class="flabel"><i class="bi bi-building me-1"></i>Kelas</label>
                                <select name="kelas" id="kelas" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach (\App\Models\Kelas::all() as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <label class="flabel"><i class="bi bi-tags me-1"></i>Jenis Absensi</label>
                                <select name="jenis" id="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="masuk"  {{ request('jenis') == 'masuk'  ? 'selected' : '' }}>Masuk</option>
                                    <option value="pulang" {{ request('jenis') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                                    <option value="izin"   {{ request('jenis') == 'izin'   ? 'selected' : '' }}>Izin</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="flabel"><i class="bi bi-person-search me-1"></i>Nama Siswa</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                       placeholder="Cari nama siswa..."
                                       value="{{ request('nama') }}">
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label class="flabel"><i class="bi bi-flag me-1"></i>Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="hadir"       {{ request('status') == 'hadir'       ? 'selected' : '' }}>Hadir</option>
                                    <option value="terlambat"   {{ request('status') == 'terlambat'   ? 'selected' : '' }}>Terlambat</option>
                                    <option value="pulang"      {{ request('status') == 'pulang'      ? 'selected' : '' }}>Pulang</option>
                                    <option value="izin"        {{ request('status') == 'izin'        ? 'selected' : '' }}>Izin</option>
                                    <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                </select>
                            </div>

                            <div class="col-md-12 col-lg-4">
                                <label class="flabel">&nbsp;</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn-filter btn-filter-primary">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                    <button type="button" class="btn-filter btn-filter-outline" onclick="resetForm()">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                    <button type="button" id="btnExport" class="btn-filter btn-filter-success">
                                        <i class="bi bi-file-earmark-excel"></i> Excel
                                    </button>
                                    <button type="button" id="btnPrint" class="btn-filter btn-filter-info">
                                        <i class="bi bi-printer"></i> Print
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- ── Data Table Card ── -->
            <div class="data-card">
                <div class="data-card-header">
                    <div class="header-icon header-icon-success"><i class="bi bi-table"></i></div>
                    <div style="flex:1;">
                        <p class="data-card-title">
                            Data Absensi
                            <span class="range-label">
                                @if(request('tanggal_mulai') && request('tanggal_selesai'))
                                    {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->locale('id')->translatedFormat('d F Y') }}
                                    &ndash;
                                    {{ \Carbon\Carbon::parse(request('tanggal_selesai'))->locale('id')->translatedFormat('d F Y') }}
                                @elseif(request('tanggal_mulai'))
                                    Mulai {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->locale('id')->translatedFormat('d F Y') }}
                                @elseif(request('tanggal_selesai'))
                                    Sampai {{ \Carbon\Carbon::parse(request('tanggal_selesai'))->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                                @endif
                            </span>
                        </p>
                        <p class="data-card-subtitle">{{ $absensi->count() }} record ditampilkan</p>
                    </div>
                </div>

                @php $totalData = $totalData ?? $absensi->count(); @endphp

                @if(request()->hasAny(['tanggal_mulai','tanggal_selesai','kelas','jenis','nama','status']))
                    <div class="px-0 pt-3 pb-0">
                        <div class="alert-pro alert-pro-info alert-dismissible fade show mx-4 mb-3" role="alert">
                            <i class="bi bi-info-circle-fill"></i>
                            <span><strong>Filter aktif:</strong> {{ $totalData }} data ditemukan</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table-pro">
                        <thead>
                            <tr>
                                <th style="width:4%">#</th>
                                <th style="width:11%">Tanggal</th>
                                <th style="width:22%">Siswa</th>
                                <th style="width:12%">Kelas</th>
                                <th style="width:10%">Jenis</th>
                                <th style="width:12%">Status</th>
                                <th style="width:8%">Jam</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $index => $item)
                                <tr>
                                    <td><div class="row-num">{{ $index + 1 }}</div></td>

                                    <td>
                                        <div class="date-cell">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                            <small>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('l') }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="student-cell">
                                            <div class="student-avatar">
                                                {{ substr($item->siswa->nama ?? '?', 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="student-name">{{ $item->siswa->nama ?? '-' }}</div>
                                                <div class="student-rfid">{{ $item->rfid ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($item->siswa?->kelas)
                                            <span class="kelas-pill">
                                                <i class="bi bi-door-open" style="font-size:.7rem;"></i>
                                                {{ $item->siswa->kelas->nama }}
                                            </span>
                                        @else
                                            <span style="color:var(--text-muted);">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @php $jenis = strtolower($item->jenis ?? ''); @endphp
                                        <span class="jenis-pill jenis-{{ $jenis }}">
                                            {{ ucfirst($item->jenis ?? '-') }}
                                        </span>
                                    </td>

                                    <td>
                                        @switch($item->status)
                                            @case('hadir')
                                                <span class="status-pill status-hadir"><i class="bi bi-check-circle-fill" style="font-size:.65rem;"></i>Hadir</span>
                                                @break
                                            @case('terlambat')
                                                <span class="status-pill status-terlambat"><i class="bi bi-clock-fill" style="font-size:.65rem;"></i>Terlambat</span>
                                                @break
                                            @case('pulang')
                                                <span class="status-pill status-pulang"><i class="bi bi-box-arrow-right" style="font-size:.65rem;"></i>Pulang</span>
                                                @break
                                            @case('izin')
                                                <span class="status-pill status-izin"><i class="bi bi-file-text-fill" style="font-size:.65rem;"></i>Izin</span>
                                                @break
                                            @case('sakit')
                                                <span class="status-pill status-sakit"><i class="bi bi-heart-pulse-fill" style="font-size:.65rem;"></i>Sakit</span>
                                                @break
                                            @case('tidak hadir')
                                            @case('tidak_hadir')
                                                <span class="status-pill status-tidak_hadir"><i class="bi bi-x-circle-fill" style="font-size:.65rem;"></i>Tidak Hadir</span>
                                                @break
                                            @default
                                                <span style="color:var(--text-muted);font-size:.8rem;">—</span>
                                        @endswitch
                                    </td>

                                    <td><span class="jam-cell">{{ $item->jam }}</span></td>

                                    <td style="color:var(--text-secondary);font-size:.82rem;">
                                        {{ $item->keterangan ?: '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-calendar-x"></i></div>
                                            <h6>Tidak ada data absensi ditemukan</h6>
                                            <small>Coba ubah filter atau pilih rentang tanggal yang berbeda</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div><!-- /.data-card -->

        </div>
    </div>
</section>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

@endsection

@push('scripts')
<script>
    const exportRoute  = "{{ route('absensi.export') }}";
    const printRoute   = "{{ route('absensi.print') }}";
    const byRangeRoute = "{{ route('absensi.byRange') }}";

    @if(request()->hasAny(['tanggal_mulai','tanggal_selesai','kelas','jenis','nama','status']) && $totalData > 0)
        var showSuccessToast = true;
        var successMessage = "Filter berhasil diterapkan. Ditemukan {{ $totalData }} data.";
    @endif
    @if(request()->hasAny(['tanggal_mulai','tanggal_selesai','kelas','jenis','nama','status']) && $totalData === 0)
        var showWarningToast = true;
        var warningMessage = "Tidak ada data yang sesuai dengan filter yang dipilih.";
    @endif
</script>
<script src="{{ asset('js/by-range.js') }}"></script>
@endpush