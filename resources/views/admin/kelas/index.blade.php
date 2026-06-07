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

    .btn-hero {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600; font-size: .82rem;
        border-radius: 50px; padding: 9px 22px;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        transition: all .2s; z-index: 1; position: relative;
        background: #fff; color: var(--brand-primary);
    }
    .btn-hero:hover {
        background: #f0f4ff; color: var(--brand-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.12);
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

    /* ── Data Card ────────────────────────── */
    .data-card {
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
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

    /* ── Table ────────────────────────────── */
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

    /* row number */
    .row-num {
        font-size: .72rem; font-weight: 700; color: var(--text-muted);
        width: 32px; height: 32px;
        background: var(--surface-soft);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
    }

    /* class name cell & wali badge */
    .kelas-cell {
        display: flex; align-items: center; gap: 12px;
    }
    .kelas-icon {
        width: 36px; height: 36px;
        background: var(--brand-primary-light);
        border-radius: var(--radius-sm);
        display: grid; place-items: center;
        color: var(--brand-primary); font-size: .9rem;
        flex-shrink: 0;
    }
    .kelas-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }
    .wali-badge {
        background: var(--surface-soft);
        border: 1px solid var(--surface-border);
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-size: .8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* action buttons */
    .action-wrap { display: flex; gap: 6px; align-items: center; }
    .btn-act {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        border: 1.5px solid; background: transparent;
        display: grid; place-items: center;
        font-size: .82rem; cursor: pointer;
        transition: all .2s;
    }
    .btn-act-edit {
        border-color: #74c0fc; color: var(--brand-primary);
        background: var(--brand-primary-light);
    }
    .btn-act-edit:hover {
        background: var(--brand-primary); border-color: var(--brand-primary); color: #fff;
    }
    .btn-act-delete {
        border-color: #ffa8a8; color: var(--brand-danger);
        background: var(--brand-danger-light);
    }
    .btn-act-delete:hover {
        background: var(--brand-danger); border-color: var(--brand-danger); color: #fff;
    }

    /* empty state */
    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    /* ── Modals ───────────────────────────── */
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
        font-size: .95rem; font-weight: 700; color: var(--text-primary);
        display: flex; align-items: center; gap: 10px;
    }
    .mti {
        width: 34px; height: 34px; border-radius: var(--radius-sm);
        display: grid; place-items: center; font-size: .9rem; flex-shrink: 0;
    }
    .mti-primary { background: var(--brand-primary-light); color: var(--brand-primary); }
    .mti-warning { background: var(--brand-warning-light); color: var(--brand-warning); }

    .modal-pro .modal-body { padding: 22px 24px; }
    .modal-pro .modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--surface-border);
        background: var(--surface-soft);
    }

    .flabel {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
        color: var(--text-secondary); margin-bottom: 6px; display: block;
    }
    /* Ditambahkan class .form-select agar desainnya sama persis dengan form-control */
    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .875rem;
        border: 1.5px solid var(--surface-border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        padding: 9px 13px;
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
        background-color: var(--surface);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(59,91,219,.1);
        outline: none;
    }
    .form-control.is-invalid, .form-select.is-invalid { border-color: var(--brand-danger); }

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
</style>

<div class="page-hero">
    <div>
        <h1><i class="bi bi-building me-2" style="opacity:.9"></i>Data Kelas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Master Data</li>
                <li class="breadcrumb-item active">Kelas</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn-hero" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
        <i class="bi bi-plus-lg"></i> Tambah Kelas
    </button>
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
                    <div class="header-icon"><i class="bi bi-building"></i></div>
                    <div>
                        <p class="data-card-title">Daftar Kelas</p>
                        <p class="data-card-subtitle">{{ count($kelas) }} kelas terdaftar</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-pro datatable">
                        <thead>
                            <tr>
                                <th style="width:6%">#</th>
                                <th style="width:34%">Nama Kelas</th>
                                <th style="width:40%">Wali Kelas</th>
                                <th style="width:20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas as $index => $k)
                                <tr>
                                    <td><div class="row-num">{{ $index + 1 }}</div></td>
                                    <td>
                                        <div class="kelas-cell">
                                            <div class="kelas-icon"><i class="bi bi-door-open"></i></div>
                                            <span class="kelas-name">{{ $k->nama }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($k->waliKelas)
                                            <span class="wali-badge">
                                                <i class="bi bi-person-fill text-primary"></i> 
                                                {{ $k->waliKelas->nama }}
                                            </span>
                                        @else
                                            <span style="color: var(--text-muted); font-style: italic; font-size: .8rem;">Belum Diatur</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-wrap">
                                            <button type="button"
                                                    class="btn-act btn-act-edit"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editKelasModal{{ $k->id }}"
                                                    title="Edit Kelas">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn-act btn-act-delete delete-btn"
                                                    data-url="{{ route('kelas.destroy', $k->id) }}"
                                                    data-id="{{ $k->id }}"
                                                    data-nama="{{ $k->nama }}"
                                                    title="Hapus Kelas">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade modal-pro" id="editKelasModal{{ $k->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <span class="mti mti-warning"><i class="bi bi-pencil-square"></i></span>
                                                    Edit Kelas
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label for="nama{{ $k->id }}" class="flabel">Nama Kelas</label>
                                                    <input type="text"
                                                           name="nama"
                                                           id="nama{{ $k->id }}"
                                                           class="form-control mb-3"
                                                           value="{{ old('nama', $k->nama) }}"
                                                           placeholder="Contoh: X IPA 1"
                                                           required>

                                                    <label for="guru_id{{ $k->id }}" class="flabel">Wali Kelas (Opsional)</label>
                                                    <select name="guru_id" id="guru_id{{ $k->id }}" class="form-select">
                                                        <option value="">-- Belum Diatur / Kosongkan --</option>
                                                        @foreach($gurus as $guru)
                                                            <option value="{{ $guru->id }}" {{ (old('guru_id', $k->guru_id) == $guru->id) ? 'selected' : '' }}>
                                                                {{ $guru->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-lg"></i> Batal
                                                    </button>
                                                    <button type="submit" class="btn-modal btn-mp">
                                                        <i class="bi bi-check-lg"></i> Update Kelas
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-building"></i></div>
                                            <h6>Belum ada data kelas</h6>
                                            <small>Klik tombol "Tambah Kelas" untuk menambahkan kelas baru</small>
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

<div class="modal fade modal-pro" id="tambahKelasModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="mti mti-primary"><i class="bi bi-plus-circle"></i></span>
                    Tambah Kelas Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label for="nama" class="flabel">Nama Kelas</label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           class="form-control mb-3 @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Contoh: X IPA 1"
                           required>
                    @error('nama')
                        <div class="invalid-feedback d-block mt-1" style="font-size:.78rem;color:var(--brand-danger);">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    <label for="guru_id" class="flabel">Wali Kelas (Opsional)</label>
                    <select name="guru_id" id="guru_id" class="form-select @error('guru_id') is-invalid @enderror">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-modal btn-mp">
                        <i class="bi bi-check-lg"></i> Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/kelas.js') }}"></script>
@endpush