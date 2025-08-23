@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Data Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Management Data Siswa</h5>
                            <div class="d-flex gap-2">
                                <!-- Tombol Tambah Siswa -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tambahSiswaModal">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Siswa
                                </button>

                                <!-- Tombol Import Excel -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#importSiswaModal">
                                    <i class="bi bi-upload me-1"></i> Import Excel
                                </button>
                            </div>
                        </div>

                        <!-- Default Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Siswa</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">RFID</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($siswas as $index => $siswa)
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}</th>
                                            <td>
                                                <a href="javascript:void(0)"
                                                    class="text-primary fw-bold text-decoration-none" data-bs-toggle="modal"
                                                    data-bs-target="#scanRfidModal" data-siswa-id="{{ $siswa->id }}"
                                                    data-siswa-nama="{{ $siswa->nama }}" title="Klik untuk scan RFID">
                                                    <i class="bi bi-person-circle me-1"></i>{{ $siswa->nama }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $siswa->kelas->nama ?? '-' }}</span>
                                            </td>
                                            <td id="rfid-{{ $siswa->id }}">
                                                @if ($siswa->rfid)
                                                    <code class="text-muted">{{ $siswa->rfid }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($siswa->status === 'aktif')
                                                    <span class="badge bg-success"><i
                                                            class="bi bi-check-circle me-1"></i>Aktif</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i
                                                            class="bi bi-clock me-1"></i>Pending</span>
                                                @endif
                                            </td>
                                            <!-- Bagian Action Buttons dalam tabel -->
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info editSiswaBtn"
                                                        data-id="{{ $siswa->id }}" data-nama="{{ $siswa->nama }}"
                                                        data-kelas="{{ $siswa->kelas_id }}"
                                                        data-rfid="{{ $siswa->rfid }}" data-status="{{ $siswa->status }}"
                                                        title="Edit Siswa">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                                                        class="d-inline deleteSiswaForm">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger deleteSiswaBtn"
                                                            title="Hapus Siswa">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                                    <h6>Belum ada data siswa</h6>
                                                    <small>Silakan tambah siswa baru atau import dari Excel</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Default Table -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importSiswaModal" tabindex="-1" aria-labelledby="importSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importSiswaLabel">
                        <i class="bi bi-upload me-2"></i>Import Siswa dari Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel</label>
                            <input type="file" name="file" id="file" class="form-control"
                                accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">File yang diizinkan: .xlsx, .xls, .csv</div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle me-1"></i>Format File</h6>
                            <p class="mb-0">
                                Pastikan file Excel memiliki kolom: <strong>nama</strong>, <strong>kelas</strong><br>
                                <small class="text-muted">Kolom opsional: rfid, status (default: aktif)</small>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-upload me-1"></i>Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Scan RFID -->
    <div class="modal fade" id="scanRfidModal" tabindex="-1" aria-labelledby="scanRfidLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="scanRfidForm" method="POST" action="{{ route('siswa.scan') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="scanRfidLabel">
                            <i class="bi bi-credit-card me-2"></i>Scan RFID
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="siswa_id" id="siswa_id">
                        <div class="mb-3">
                            <label for="rfid" class="form-label">Masukkan RFID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                <input type="text" class="form-control" id="rfid" name="rfid" required
                                    autocomplete="off" placeholder="Scan atau ketik RFID...">
                            </div>
                        </div>
                        <div id="modalSiswaNama" class="alert alert-light">
                            <i class="bi bi-person me-1"></i>
                            <strong>Siswa:</strong> <span class="nama-siswa"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan RFID
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSiswaLabel">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Siswa Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSiswaForm" action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-1"></i>Terjadi
                                    Kesalahan!</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                    class="form-control" required placeholder="Masukkan nama lengkap">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house"></i></span>
                                <select name="kelas_id" id="kelas_id" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- RFID & Status otomatis di controller --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Edit Siswa -->
    <div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSiswaLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Data Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSiswaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_siswa_id" name="siswa_id">

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="edit_nama" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" name="nama" id="edit_nama" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="edit_kelas_id" class="form-label">Kelas <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-house"></i></span>
                                        <select name="kelas_id" id="edit_kelas_id" class="form-select" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($kelas as $k)
                                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="edit_rfid" class="form-label">RFID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                        <input type="text" name="rfid" id="edit_rfid" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                        <select name="status" id="edit_status" class="form-select" required>
                                            <option value="aktif">Aktif</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-circle me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/siswa.js') }}"></script>
@endpush
