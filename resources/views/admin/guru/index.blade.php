@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

    body, .section, .card, .modal-content { font-family: 'Plus Jakarta Sans', sans-serif; }

    .page-hero { background: linear-gradient(135deg, #1c3faa 0%, var(--brand-primary) 55%, #4f75ff 100%); border-radius: var(--radius-xl); padding: 26px 32px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 32px rgba(59,91,219,.28); position: relative; overflow: hidden; }
    .page-hero::before { content: ''; position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,.07); border-radius: 50%; }
    .page-hero::after { content: ''; position: absolute; right: 70px; bottom: -65px; width: 140px; height: 140px; background: rgba(255,255,255,.05); border-radius: 50%; }
    .page-hero h1 { font-size: 1.45rem; font-weight: 700; color: #fff; margin: 0 0 4px; }
    .page-hero .breadcrumb { margin: 0; background: transparent; padding: 0; font-size: .78rem; }
    .page-hero .breadcrumb-item a, .page-hero .breadcrumb-item.active { color: rgba(255,255,255,.75); }
    .page-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    .btn-hero { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: .82rem; border-radius: 50px; padding: 9px 22px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: all .2s; z-index: 1; position: relative; background: #fff; color: var(--brand-primary); }
    .btn-hero:hover { background: #f0f4ff; color: var(--brand-primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.12); }

    .alert-pro { border: none; border-radius: var(--radius-md); padding: 14px 18px; font-size: .875rem; font-weight: 500; display: flex; align-items: center; gap: 10px; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 16px; }
    .alert-pro-success { background: var(--brand-success-light); color: #087f5b; }
    .alert-pro-danger { background: var(--brand-danger-light); color: var(--brand-danger); }
    .alert-pro .btn-close { margin-left: auto; }

    .data-card { background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); overflow: hidden; }
    .data-card-header { padding: 18px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--surface-border); background: var(--surface-soft); }
    .header-icon { width: 42px; height: 42px; background: var(--brand-primary-light); border-radius: var(--radius-md); display: grid; place-items: center; color: var(--brand-primary); font-size: 1.1rem; flex-shrink: 0; }
    .data-card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .data-card-subtitle { font-size: .75rem; color: var(--text-muted); margin: 0; }

    .table-pro { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-pro thead th { background: var(--surface-soft); color: var(--text-secondary); font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; padding: 13px 20px; border-bottom: 1px solid var(--surface-border); white-space: nowrap; }
    .table-pro tbody tr { transition: background .15s; }
    .table-pro tbody tr:hover { background: #f5f8ff; }
    .table-pro tbody td, .table-pro tbody th { padding: 14px 20px; border-bottom: 1px solid #f1f3f7; vertical-align: middle; font-size: .875rem; }
    .table-pro tbody tr:last-child td, .table-pro tbody tr:last-child th { border-bottom: none; }

    .row-num { font-size: .72rem; font-weight: 700; color: var(--text-muted); width: 32px; height: 32px; background: var(--surface-soft); border-radius: var(--radius-sm); display: grid; place-items: center; }

    .kelas-cell { display: flex; align-items: center; gap: 12px; }
    .kelas-icon { width: 36px; height: 36px; background: var(--brand-primary-light); border-radius: var(--radius-sm); display: grid; place-items: center; color: var(--brand-primary); font-size: .9rem; flex-shrink: 0; }
    .kelas-name { font-weight: 600; color: var(--text-primary); font-size: .875rem; }

    .badge-kelas { background: var(--brand-primary-light); color: var(--brand-primary); font-size: 0.75rem; padding: 5px 8px; border-radius: var(--radius-sm); font-weight: 600; margin-right: 4px; display: inline-block; margin-bottom: 4px; }

    .action-wrap { display: flex; gap: 6px; align-items: center; }
    .btn-act { width: 32px; height: 32px; border-radius: var(--radius-sm); border: 1.5px solid; background: transparent; display: grid; place-items: center; font-size: .82rem; cursor: pointer; transition: all .2s; }
    .btn-act-edit { border-color: #74c0fc; color: var(--brand-primary); background: var(--brand-primary-light); }
    .btn-act-edit:hover { background: var(--brand-primary); border-color: var(--brand-primary); color: #fff; }
    .btn-act-delete { border-color: #ffa8a8; color: var(--brand-danger); background: var(--brand-danger-light); }
    .btn-act-delete:hover { background: var(--brand-danger); border-color: var(--brand-danger); color: #fff; }

    .empty-state { text-align: center; padding: 56px 24px; }
    .empty-state-icon { font-size: 3rem; color: var(--text-muted); margin-bottom: 10px; }
    .empty-state h6 { font-weight: 700; color: var(--text-secondary); margin-bottom: 4px; }
    .empty-state small { color: var(--text-muted); font-size: .8rem; }

    .modal-pro .modal-content { border: none; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }
    .modal-pro .modal-header { padding: 18px 24px; border-bottom: 1px solid var(--surface-border); background: var(--surface-soft); }
    .modal-pro .modal-title { font-size: .95rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
    .mti { width: 34px; height: 34px; border-radius: var(--radius-sm); display: grid; place-items: center; font-size: .9rem; flex-shrink: 0; }
    .mti-primary { background: var(--brand-primary-light); color: var(--brand-primary); }
    .mti-warning { background: var(--brand-warning-light); color: var(--brand-warning); }

    .modal-pro .modal-body { padding: 22px 24px; }
    .modal-pro .modal-footer { padding: 14px 24px; border-top: 1px solid var(--surface-border); background: var(--surface-soft); }

    .flabel { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--text-secondary); margin-bottom: 6px; display: block; }
    .form-control, .form-select { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .875rem; border: 1.5px solid var(--surface-border); border-radius: var(--radius-sm); color: var(--text-primary); padding: 9px 13px; transition: border-color .2s, box-shadow .2s; width: 100%; background-color: var(--surface); }
    .form-control:focus, .form-select:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(59,91,219,.1); outline: none; }
    .form-control.is-invalid, .form-select.is-invalid { border-color: var(--brand-danger); }

    .btn-modal { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .84rem; font-weight: 600; padding: 9px 20px; border-radius: 50px; border: none; display: inline-flex; align-items: center; gap: 6px; transition: all .2s; cursor: pointer; }
    .btn-mc { background: var(--surface-border); color: var(--text-secondary); }
    .btn-mc:hover { background: #dee2e6; color: var(--text-primary); }
    .btn-mp { background: var(--brand-primary); color: #fff; }
    .btn-mp:hover { background: var(--brand-primary-dark); box-shadow: 0 4px 12px rgba(59,91,219,.3); }

    /* SweetAlert2 custom theme */
    .swal2-popup { font-family: 'Plus Jakarta Sans', sans-serif !important; border-radius: 16px !important; }
    .swal2-title { font-size: 1.1rem !important; font-weight: 700 !important; color: var(--text-primary) !important; }
    .swal2-html-container { font-size: .875rem !important; color: var(--text-secondary) !important; }
    .swal2-confirm { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; border-radius: 50px !important; padding: 9px 22px !important; font-size: .84rem !important; }
    .swal2-cancel { font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; border-radius: 50px !important; padding: 9px 22px !important; font-size: .84rem !important; }
</style>

<div class="page-hero">
    <div>
        <h1><i class="bi bi-person-badge me-2" style="opacity:.9"></i>Data Guru</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item" style="color:rgba(255,255,255,.5)">Master Data</li>
                <li class="breadcrumb-item active">Guru</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn-hero" data-bs-toggle="modal" data-bs-target="#tambahGuruModal">
        <i class="bi bi-plus-lg"></i> Tambah Guru
    </button>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if ($errors->any())
                <div class="alert-pro alert-pro-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Gagal menyimpan data!</strong>
                        <ul class="mb-0 mt-1 pl-3" style="padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-pro alert-pro-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="data-card">
                <div class="data-card-header">
                    <div class="header-icon"><i class="bi bi-person-video3"></i></div>
                    <div>
                        <p class="data-card-title">Daftar Guru</p>
                        <p class="data-card-subtitle">{{ $gurus->count() }} guru terdaftar</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-pro datatable">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:15%">NIP (Akun)</th>
                                <th style="width:30%">Nama Guru</th>
                                <th style="width:15%">No HP</th>
                                <th style="width:20%">Wali Kelas</th>
                                <th style="width:15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $index => $g)
                                <tr>
                                    <td><div class="row-num">{{ $index + 1 }}</div></td>
                                    <td>
                                        <span class="d-block">{{ $g->nip ?? '-' }}</span>
                                        @if($g->user_id)
                                            <small style="color:var(--brand-success); font-weight:600;"><i class="bi bi-person-check"></i> Terhubung: {{ $g->user->name ?? 'Akun Aktif' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="kelas-cell">
                                            <div class="kelas-icon"><i class="bi bi-person"></i></div>
                                            <span class="kelas-name">{{ $g->nama }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $g->no_hp ?? '-' }}</td>
                                    <td>
                                        @if($g->kelas->count() > 0)
                                            @foreach($g->kelas as $kls)
                                                <span class="badge-kelas"><i class="bi bi-building"></i> {{ $kls->nama }}</span>
                                            @endforeach
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">Bukan Wali Kelas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-wrap">
                                            <button type="button" class="btn-act btn-act-edit" data-bs-toggle="modal" data-bs-target="#editGuruModal{{ $g->id }}" title="Edit Guru">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn-act btn-act-delete delete-btn" data-url="{{ route('guru.destroy', $g->id) }}" data-id="{{ $g->id }}" data-nama="{{ $g->nama }}" title="Hapus Guru">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade modal-pro" id="editGuruModal{{ $g->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <span class="mti mti-warning"><i class="bi bi-pencil-square"></i></span> Edit Data Guru
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('guru.update', $g->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="flabel">Kaitkan ke Akun Existing (Opsional)</label>
                                                        <select name="user_id" class="form-control">
                                                            <option value="">-- Biarkan Seperti Saat Ini / Tidak Ada --</option>

                                                            @if($g->user_id && $g->user)
                                                                <option value="{{ $g->user_id }}" selected>Akun Saat Ini: {{ $g->user->name }} ({{ $g->user->email }})</option>
                                                            @endif

                                                            @foreach($availableUsers as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Jika diganti, nama & email akun terkait otomatis diperbarui menjadi NIP.</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nip{{ $g->id }}" class="flabel">NIP (Juga jadi Username) <span class="text-danger">*</span></label>
                                                        <input type="text" name="nip" id="nip{{ $g->id }}" class="form-control mb-3" value="{{ old('nip', $g->nip) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nama{{ $g->id }}" class="flabel">Nama Lengkap <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama" id="nama{{ $g->id }}" class="form-control mb-3" value="{{ old('nama', $g->nama) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="no_hp{{ $g->id }}" class="flabel">No HP (Opsional)</label>
                                                        <input type="text" name="no_hp" id="no_hp{{ $g->id }}" class="form-control" value="{{ old('no_hp', $g->no_hp) }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                                                    <button type="submit" class="btn-modal btn-mp"><i class="bi bi-check-lg"></i> Update Guru</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="bi bi-person-badge"></i></div>
                                            <h6>Belum ada data guru</h6>
                                            <small>Klik tombol "Tambah Guru" untuk menambahkan data guru baru</small>
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

<div class="modal fade modal-pro" id="tambahGuruModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="mti mti-primary"><i class="bi bi-plus-circle"></i></span> Tambah Guru & Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="user_id" class="flabel">Kaitkan ke Akun Existing (Opsional)</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            <option value="">-- Buat Akun Baru Otomatis --</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Pilih jika akun sudah ada. Jika pilih "Buat Akun Baru", Passwordnya adalah: <strong>password123</strong></small>
                    </div>

                    <div class="mb-3">
                        <label for="nip" class="flabel">NIP (Username Login) <span class="text-danger">*</span></label>
                        <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai" required>
                        @error('nip')
                            <div class="invalid-feedback d-block mt-1" style="font-size:.78rem;color:var(--brand-danger);">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="flabel">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Nama Guru beserta Gelar" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="flabel">No HP / WhatsApp (Opsional)</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-mc" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                    <button type="submit" class="btn-modal btn-mp"><i class="bi bi-check-lg"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any() && !request()->isMethod('put'))
    <span id="flag-error-tambah" class="d-none" style="display: none;"></span>
@endif

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // 1. Membaca flag error untuk otomatis membuka modal tambah jika validasi gagal
            if ($('#flag-error-tambah').length > 0) {
                var myModal = new bootstrap.Modal(document.getElementById('tambahGuruModal'));
                myModal.show();
            }

            // 2. Handler AJAX untuk tombol Hapus Data Guru
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                var url      = $(this).data('url');
                var namaGuru = $(this).data('nama');

                // Konfirmasi menggunakan SweetAlert2 (menggantikan confirm() bawaan browser)
                Swal.fire({
                    title: 'Hapus Data Guru?',
                    html: 'Anda akan menghapus data guru:<br><strong>' + namaGuru + '</strong><br><span style="font-size:.82rem;color:#6c757d;">Akun user yang terhubung juga akan ikut terhapus.</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e03131',
                    cancelButtonColor: '#868e96',
                    confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: '<i class="bi bi-x-lg me-1"></i> Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        popup:         'swal2-popup',
                        title:         'swal2-title',
                        htmlContainer: 'swal2-html-container',
                        confirmButton: 'swal2-confirm',
                        cancelButton:  'swal2-cancel',
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',           // Beritahu Laravel ini adalah request DELETE
                                _token: '{{ csrf_token() }}' // Token CSRF keamanan Laravel
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Notifikasi sukses menggunakan SweetAlert2 (menggantikan alert() bawaan)
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonColor: '#3b5bdb',
                                        confirmButtonText: 'OK',
                                        timer: 2000,
                                        timerProgressBar: true,
                                    }).then(function() {
                                        location.reload(); // Refresh halaman otomatis untuk memperbarui tabel
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Gagal menghapus data.',
                                        icon: 'error',
                                        confirmButtonColor: '#3b5bdb',
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Antisipasi jika ada error database/sistem tersembunyi
                                var pesan = xhr.status === 500
                                    ? 'Terjadi kesalahan pada server. Pastikan relasi database aman.'
                                    : 'Terjadi kesalahan sistem saat mencoba menghapus data.';

                                Swal.fire({
                                    title: 'Error!',
                                    text: pesan,
                                    icon: 'error',
                                    confirmButtonColor: '#3b5bdb',
                                });
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush