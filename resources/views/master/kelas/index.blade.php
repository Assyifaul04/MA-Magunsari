@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Data Kelas</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item">Master Data</li>
      <li class="breadcrumb-item active">Kelas</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <!-- Alert Success -->
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
            <h5 class="card-title">Daftar Kelas</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
              <i class="bi bi-plus-circle"></i> Tambah Kelas
            </button>
          </div>

          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Kelas</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($kelas as $index => $k)
                <tr>
                  <th scope="row">{{ $index + 1 }}</th>
                  <td>{{ $k->nama }}</td>
                  <td>
                    <button type="button" class="btn btn-outline-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editKelasModal{{ $k->id }}"
                            title="Edit Kelas">
                      <i class="bi bi-pencil"></i>
                    </button>
                    
                    <button type="button" class="btn btn-outline-danger btn-sm delete-btn" 
                            data-url="{{ route('kelas.destroy', $k->id) }}" 
                            data-id="{{ $k->id }}"
                            data-nama="{{ $k->nama }}"
                            title="Hapus Kelas">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>

                <!-- Modal Edit Kelas -->
                <div class="modal fade" id="editKelasModal{{ $k->id }}" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                          <div class="row mb-3">
                            <label for="nama{{ $k->id }}" class="col-sm-3 col-form-label">Nama Kelas</label>
                            <div class="col-sm-9">
                              <input type="text" name="nama" id="nama{{ $k->id }}" class="form-control" 
                                     value="{{ old('nama', $k->nama) }}" required>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Update Kelas</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="3" class="text-center">
                    <div class="py-4">
                      <i class="bi bi-inbox fs-1 text-muted"></i>
                      <p class="text-muted">Belum ada data kelas</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="tambahKelasModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kelas Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('kelas.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row mb-3">
            <label for="nama" class="col-sm-3 col-form-label">Nama Kelas</label>
            <div class="col-sm-9">
              <input type="text" name="nama" id="nama" 
                     class="form-control @error('nama') is-invalid @enderror" 
                     value="{{ old('nama') }}" 
                     placeholder="Contoh: X IPA 1" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan Kelas</button>
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