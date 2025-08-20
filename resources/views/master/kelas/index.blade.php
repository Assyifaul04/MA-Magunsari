@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1><i class="bi bi-collection text-primary"></i> Data Kelas</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kelas</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Card Header dengan Tombol Tambah -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0"><i class="bi bi-table me-1"></i> Daftar Kelas</h5>
                        <small class="text-muted">Kelola data kelas dalam sistem</small>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kelas
                    </button>
                </div>

                <!-- Card Body dengan Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">#</th>
                                    <th scope="col">
                                        <i class="bi bi-bookmark-fill me-1"></i>Nama Kelas
                                    </th>
                                    <th scope="col" style="width: 150px;" class="text-center">
                                        <i class="bi bi-gear-fill me-1"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas as $index => $k)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-mortarboard text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $k->nama }}</h6>
                                                <small class="text-muted">ID: {{ $k->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <!-- Tombol Edit -->
                                            <button type="button" 
                                                    class="btn btn-outline-info btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editKelasModal{{ $k->id }}"
                                                    title="Edit Kelas">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('kelas.destroy', $k->id) }}" 
                                                  method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Yakin ingin hapus kelas {{ $k->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Hapus Kelas">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit Kelas -->
                                <div class="modal fade" id="editKelasModal{{ $k->id }}" tabindex="-1" aria-labelledby="editKelasLabel{{ $k->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="editKelasLabel{{ $k->id }}">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Kelas
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama{{ $k->id }}" class="form-label">
                                                            <i class="bi bi-bookmark me-1"></i>Nama Kelas
                                                        </label>
                                                        <input type="text" 
                                                               name="nama" 
                                                               id="nama{{ $k->id }}"
                                                               class="form-control" 
                                                               value="{{ old('nama', $k->nama) }}" 
                                                               placeholder="Masukkan nama kelas..."
                                                               required>
                                                        <div class="form-text">Contoh: X IPA 1, XI IPS 2, XII Bahasa</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle me-1"></i>Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-info">
                                                        <i class="bi bi-check-circle me-1"></i>Update Kelas
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum ada data kelas</h5>
                                            <p class="text-muted">Klik tombol "Tambah Kelas" untuk menambahkan kelas baru</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Total: {{ $kelas->count() }} kelas
                        </small>
                        <small class="text-muted">
                            Data diperbarui: {{ now()->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahKelasLabel">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kelas Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            <i class="bi bi-bookmark me-1"></i>Nama Kelas <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="nama" 
                               id="nama"
                               class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama') }}" 
                               placeholder="Masukkan nama kelas..."
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-lightbulb me-1"></i>
                            Contoh: X IPA 1, XI IPS 2, XII Bahasa
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="alert alert-light border-start border-4 border-primary">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-eye me-2 text-primary"></i>
                            <small class="text-muted">Pastikan nama kelas sudah sesuai sebelum menyimpan</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .avatar {
        width: 32px;
        height: 32px;
    }
    
    .avatar-sm {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
    
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        padding: 1rem 1.25rem;
    }
    
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    .modal-header.bg-primary,
    .modal-header.bg-info {
        border-bottom: none;
    }
    
    .alert-dismissible .btn-close {
        padding: 0.75rem 0.75rem;
    }
    
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }
    
    .border-start {
        border-left: 4px solid !important;
    }
    
    .pagetitle h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: #495057;
    }
    
    .card-title {
        color: #2c3e50;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/kelas.js') }}"></script>

<script>
$(document).ready(function() {
    // Auto focus pada input nama saat modal dibuka
    $('#tambahKelasModal').on('shown.bs.modal', function () {
        $('#nama').focus();
    });
    
    // Auto focus pada input edit saat modal edit dibuka
    $('[id^="editKelasModal"]').on('shown.bs.modal', function () {
        $(this).find('input[name="nama"]').focus().select();
    });
    
    // Preview nama kelas saat mengetik (opsional)
    $('#nama').on('input', function() {
        const value = $(this).val();
        if (value.length > 0) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid');
        }
    });
});
</script>
@endpush