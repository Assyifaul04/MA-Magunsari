@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Absensi Izin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item">Absensi</li>
                <li class="breadcrumb-item active">Absensi Izin</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Form Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Absensi Izin</h5>

                        <!-- General Form Elements -->
                        <form id="formAbsensiIzin">
                            @csrf
                            <input type="hidden" name="jenis" value="izin">

                            <div class="row mb-3">
                                <label for="inputRFID" class="col-sm-2 col-form-label">RFID</label>
                                <div class="col-sm-10">
                                    <input type="text" name="rfid" class="form-control" id="inputRFID" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputKeterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan" class="form-control" id="inputKeterangan" style="height: 100px" required
                                        placeholder="Masukkan keterangan..."></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select" aria-label="Default select example" required>
                                        <option selected disabled value="">-- Pilih Status --</option>
                                        <option value="izin">Izin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Simpan Data
                                    </button>
                                    <button type="reset" class="btn btn-secondary ms-2">
                                        <i class="bi bi-x-circle"></i> Reset
                                    </button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->

                    </div>
                </div><!-- End Form Card -->

                <!-- Data Table Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Absensi Izin</h5>

                        <!-- Table with stripped rows, responsive -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">RFID</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($absensi as $i => $a)
                                        <tr>
                                            <th scope="row">{{ $i + 1 }}</th>
                                            <td>{{ $a->siswa->nama ?? '-' }}</td>
                                            <td>{{ $a->keterangan ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match ($a->status) {
                                                        'izin' => 'bg-info',
                                                        default => 'bg-primary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($a->status) }}</span>
                                            </td>
                                            <td><code>{{ $a->rfid }}</code></td>
                                            <td>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada data absensi yang
                                                tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table Responsive -->

                    </div>
                </div><!-- End Data Table Card -->

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/absensi.js') }}"></script>
@endpush
